# post.md — Posting kaise karni hai aur Local → Live sync kaise hota hai

> Ye file **practical kadam-by-kadam guide** hai. Rule-book (11-section layout,
> title format, SEO) [guide.md](guide.md) me hai — yahan sirf *operation*
> bataya gaya hai: post kaise banta hai, kaise live jata hai, aur dashboard se
> kaise track karte hain.

---

## 0. Poora flow ek nazar me

```
┌────────────── LOCAL (aapki machine) ──────────────┐
│                                                    │
│  1. Post banana                                    │
│     • php artisan cms:create-fresh-job --type=X    │
│     • ya admin panel me hand                        │
│     • ya guide.md §9 ke $jobData se                 │
│                                                    │
│  2. Check:  php artisan post:push <slug> --dry-run │
│  3. Push:   php artisan post:push <slug>           │
└───────────────────────┬────────────────────────────┘
                        │  HTTPS + Bearer token
                        ▼
┌────────────── LIVE (careerinpak.com) ──────────────┐
│  POST /api/v1/sync/posts                           │
│     • token check (401/403)                        │
│     • validation (422)                             │
│     • post save karo →                            │
│         - NAYA post  → DRAFT banta hai             │
│         - MOJODA post → status waisa hi rehta hai  │
│     • har attempt ka log  → post_sync_logs         │
└───────────────────────┬────────────────────────────┘
                        ▼
   4. Admin → Blog → Posts → Draft → image attach → Publish
```

---

## 1. One-time setup

### 1.1 LIVE server (cPanel terminal)

```bash
cd ~/careerinpak.com
git fetch origin main && git reset --hard origin/main
php artisan migrate --force
php artisan optimize:clear
php artisan post:sync-token
```

`post:sync-token` ye dega:

```
Token issued for user: admin@company.com

  SYNC_LIVE_URL=https://your-live-domain.com      ← ye EXAMPLE hai, ignore
  SYNC_LIVE_TOKEN=1|AbCdEf...                     ← ye COPY karo
```

> 🔴 **Token ko kabhi chat/forum me paste mat karo.** Ye live site par post
> likhne ki credential hai. Leak ho jaye to turant rotate karo:
> `php artisan post:sync-token --revoke`

### 1.2 LOCAL machine

`.env` file me (project root) do line add karo:

```
SYNC_LIVE_URL=https://careerinpak.com
SYNC_LIVE_TOKEN=1|AbCdEf...
```

Check karne ke liye:
```bash
php artisan tinker --execute='echo config("sync.url");'
# → https://careerinpak.com
```

> **SSL error aaye** (`cURL error 60`) to `SYNC_CA_BUNDLE` set karo:
> ```
> SYNC_CA_BUNDLE=C:\Program Files\Git\usr\ssl\certs\ca-bundle.crt
> ```
> (Git for Windows ke paas wo file hoti hai. `post:push` ise apne aap bhi
> dhoond leta hai.)

---

## 2. Post banana (LOCAL) — 3 tarike

### Tarika A — CLI command (sabse tez)

```bash
php artisan cms:create-fresh-job --type=fbr          # published
php artisan cms:create-fresh-job --type=umw --draft  # local draft
```

### Tarika B — Admin panel me haath se

`http://127.0.0.1:8000/ca-admin-9K4` → Blog → Posts → New. Content [guide.md](guide.md)
§4 ke 11 sections ke hisaab se.

### Tarika C — Programmatic (guide.md §9)

```php
$html = (new \App\Services\JobPostTemplateService())->render($jobData);
```

`$jobData` ka poora schema [guide.md](guide.md) §9 me hai.

---

## 3. Push karne se PEHLE — dry run

```bash
php artisan post:push your-post-slug --dry-run
```

Kuch jaata **nahi** — sirf JSON payload dikhta hai. Dhyan do:

| Field | Kya hona chahiye |
|---|---|
| `slug` | lowercase + hyphens, focus keyword se bana |
| `name` | `[Organization] Jobs [Year] - [Vacancies]` format |
| `content` | poori HTML, red banner headings ke saath |
| `category` | sahi category naam |
| `status` | **payload me hota hi nahi** (receiver decide karta hai) |

Slug nikalne ke liye (agar yaad nahi):
```bash
php artisan tinker --execute='
$p = \Botble\Blog\Models\Post::query()->orderByDesc("id")->first();
echo \Botble\Slug\Models\Slug::query()
    ->where("reference_type", get_class($p))
    ->where("reference_id", $p->id)->value("key");'
```

---

## 4. ASLI PUSH

```bash
php artisan post:push your-post-slug
```

**Success:**
```
Pushing FBR Jobs 2026... to https://careerinpak.com/api/v1/sync/posts

Created on live as a DRAFT.
  Live ID : 143
  URL     : https://careerinpak.com/your-post-slug
  Status  : draft (publish it from the live admin panel)
```

**Options:**

| Option | Kaam |
|---|---|
| `--dry-run` | Sirf payload dikhao, kuch bhejo mat |
| `--draft` | Live par **already published** post ho to wapas **draft** kar do (review ke liye) |
| `--url=` / `--token=` | `.env` ki jagah direct override |
| `post` (number) | Slug ki jagad post ID bhi chalega: `php artisan post:push 631` |

---

## 5. Live par kya hota hai (status rules) 🔴

Yeh sabse zaroori hissa hai — isko dhyan se padho:

| Situation | Kya hota hai |
|---|---|
| Post live par **nahi** hai (naya) | **DRAFT** banta hai — hamesha |
| Post live par **draft** hai | Update hota hai, **draft hi rehta hai** |
| Post live par **published** hai | Content update hota hai, **status PUBLISHED hi rehta hai** (page 404 nahi hota) |
| Post live par published hai **aur** `--draft` lagaya | Content update + **DRAFT** (page review ke liye offline) |

**Kya KABHI nahi hota:**
- ❌ Push karne se post **publish** nahi hoti — receiver sirf `draft` jaanta hai
- ❌ Duplicate post **nahi** banta (slug = identity; dobara push = update)
- ❌ Live ka **view counter reset** nahi hota
- ❌ Featured/ad **image sync nahi** hoti — live CMS pe khud attach karni hai

---

## 6. LIVE par review + publish

1. `https://careerinpak.com/ca-admin-9K4` → login
2. **Blog → Posts** → status **Draft** filter
3. Post kholo → **featured image / advertisement image attach karo**
4. Content check karo (11 sections, headings, Urdu RTL block, links)
5. **Publish** dabao

> [!IMPORTANT]
> **Draft public nahi dikhta.** `https://careerinpak.com/<slug>` par 404 aayega
> jab tak aap khud publish na kar dein. Ye sahi hai — review ke liye hi draft hai.

---

## 7. Dashboard — progress kahan dekhein

**URL:** `https://careerinpak.com/ca-admin-9K4/post-sync`
(Admin menu me **"Post Sync"** bhi dikhega)

Kya milta hai:

| Widget | Matlab |
|---|---|
| **Total pushes** | Kitni baar local se live push hui |
| **Today** | Aaj ki attempts |
| **Created / Updated** | Naye posts bane vs purane update hue |
| **Failed** | Kitni reject/fail hui (+ aaj ki) |
| **Avg. duration** | Har push me kitna time laga + **last sync kab hua** |
| **Why pushes are failing** | Failures ka reason-wise count — jaise "Missing or invalid bearer token (×5)" |

Neeche table me har attempt hai:

| Column | Matlab |
|---|---|
| When | Timestamp |
| Post | Title + `live #ID` |
| Slug | Kis slug par aaya |
| Action | `created` / `updated` / `rejected` / `failed` |
| HTTP | 201 / 200 / 401 / 403 / 422 / 500 |
| Duration | Kitna time laga |
| Key | Kis token se (sirf naam, value nahi) |
| Result | Fail hone ki wajah |

**Filter:** upar **All / Success / Failed** buttons.

### Dashboard padhne ka tareeqa

- **Failed zyada + "Missing or invalid bearer token"** → token expire/galat hai → live pe `php artisan post:sync-token --revoke`, naya `.env` me daalo
- **422 + "The slug field..."** → slug me uppercase/space/galat character hai
- **403** → token me `post-sync` ability nahi → `post:sync-token` se naya banao (wo hamesha sahi ability deta hai)
- **500** → server error → live pe laravel log dekho

---

## 8. Errors → ilaaj

| Message | Wajah | Ilaaj |
|---|---|---|
| `Sync is not configured` | `.env` me URL/token missing | §1.2 |
| `HTTP 401` | Token galat/purana/leak | `php artisan post:sync-token --revoke` (live) → naya `.env` me |
| `HTTP 403` | Token me `post-sync` ability nahi | Wahi command dobara chalao |
| `HTTP 422` | Payload invalid | `--dry-run` se dekho; slug = lowercase+hyphens |
| `HTTP 500` | Server error / table missing | `php artisan migrate --force` + log dekho |
| `Could not reach` | URL galat / net down | `SYNC_LIVE_URL=https://careerinpak.com` (trailing slash nahi) |
| `cURL error 60` | CA bundle nahi mila | `SYNC_CA_BUNDLE` set karo (§1.2) |

---

## 9. Hamesha yaad rakhne wale rules

1. **Slug = identity.** Slug badal doge to live par **doosri post** ban jayegi aur purani orphan ho jayegi.
2. **Live par kabhi `--publish` nahi.** Publish sirf admin panel se.
3. **Images sync nahi hoti.** Push ke baad live CMS me attach karni hain.
4. **Token secret hai.** Chat me mat daalo; `SYNC_LIVE_TOKEN` line `.env` me hai aur `.env` gitignored hai.
5. **`--draft` sirf draft banata hai, publish kabhi nahi.** Receiver sirf draft direction honour karta hai.
6. Push se pehle **dry-run** — 30 second bachate hain aur galti pakad lete hain.
7. **Dashboard hafte me ek baar dekh lo** — pending drafts aur failing syncs pata chal jate hain.

---

## 10. Quick checklist (har naye post ke liye)

- [ ] Post LOCAL me bana (command / admin / template)
- [ ] Slug = focus keyword ke words, hyphenated, lowercase
- [ ] `post:push <slug> --dry-run` → payload check
- [ ] `post:push <slug>` → "Created on live as a DRAFT"
- [ ] Live admin → post **Draft** me dikhi
- [ ] Featured + advertisement image attach ki
- [ ] Content / links / Urdu block verify kiya
- [ ] **Publish** kiya
- [ ] Live URL 200 de raha hai
- [ ] Dashboard me row **green/created** dikhi

---

*Post sync system added October 2026 for CareerInPak (Botble CMS / Laravel 12).*
*Dashboard: {admin}/post-sync · Endpoint: POST /api/v1/sync/posts · Table: post_sync_logs*
