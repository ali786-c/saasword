# WordPress → Botble Migration Guide (Puri Documentation)

> **Roman Urdu me, step-by-step.** Ye guide aapke project ke built-in `wp:migrate` tool ki hai jo
> purani WordPress site ki sari posts/pages/categories/tags — images aur SEO meta ke sath — is
> Botble CMS me import karta hai.

---

## 1. Ye Tool Kya Hai?

Aapke project me 2 migration modes hain — **dono ka result bilkul same hota hai**:

| Mode | Kab use karein | Source |
|---|---|---|
| **REST API** (Mode 1) | Jab purani WP site **abhi online** hai | `https://aapki-site.com/wp-json/` |
| **Database** (Mode 2) | Jab WP site **band/offline** ho gayi hai (deploy ke baad) | WP ki MySQL database + uploads folder |

**Dono modes me ye cheezein preserve hoti hain (SEO safe):**

| Cheez | Kya hota hai |
|---|---|
| **URLs (slugs)** | WP ke slugs 1:1 copy hote hain → Google ranking safe |
| **Publish dates** | Purani dates waisi ki waisi rehti hain |
| **SEO meta** | Yoast/RankMath ke titles, descriptions, og:image → Botble SEO fields |
| **Images** | Featured + content wali + og:image → media library (`wp-import` folder) |
| **Categories/Tags** | Sab import aur posts se attach |
| **Sticky posts** | WP sticky → Botble "Featured" |
| **Malware cleanup** | Har post ka content sanitizer se saaf — script/iframe/PHP nikaal diye jate hain |

**Resume-safe:** Beech me internet band, PC band, crash — kuch bhi ho jaye, **wahi command dobara
chalao**. `wp_import_mapping` table yaad rakhti hai kya import ho chuka hai; sirf bache hue items
import honge. **Duplicate kabhi nahi bante.**

---

## 2. Important — Aapke Environment Ke Hisab Se

PHP PATH pe nahi hai, is liye har command poori path ke sath chalao. **Project root me khade ho kar:**

```bash
# PHP shortcut (har command me ye hi use hoga):
set PHP=C:/Users/Muhammad Aliyan/Downloads/Compressed/php-8.3.33-nts-Win32-vs16-x64/php.exe

# Phir aise chalao:
"%PHP%" -d max_execution_time=0 artisan wp:migrate ...
```

- `-d max_execution_time=0` zaroori hai — bari sites pe import ghanton tak chal sakta hai.
- MySQL (XAMPP) chal raha ho: `C:\xampp\mysql_start.bat`
- Agar `%PHP%` shortcut session me set karna skip karo to poori path har baar likhni hogi.

---

## 3. Mode 1 — REST API Import (Site Online Hai)

### Step 1: Pehle TEST karo (5 posts)

```bash
"%PHP%" -d max_execution_time=0 artisan wp:migrate --url=https://purani-site.com --limit=5
```

- `--limit=5` sirf 5 posts layega — dekh lo sab theek chal raha hai.
- Ye posts **DRAFTS** me aayengi (publish nahi hongi) — koi risk nahi.
- Media bhi aayegi (featured image + content images WP se download ho kar media library ke
  `wp-import` folder me jayengi).

### Step 2: Result check karo

1. Admin panel kholo: `http://127.0.0.1:8000/ca-admin-9K4`
2. **Blog → Posts** — 5 drafts dikhni chahiye
3. Ek post kholo, check karo: title, content, image, category, SEO fields (Title/Meta description)

### Step 3: Full import chalao

```bash
# Sab posts + pages (drafts me, recommended)
"%PHP%" -d max_execution_time=0 artisan wp:migrate --url=https://purani-site.com

# Ya pehle sirf posts, baad me pages
"%PHP%" -d max_execution_time=0 artisan wp:migrate --url=https://purani-site.com --posts-only
"%PHP%" -d max_execution_time=0 artisan wp:migrate --url=https://purani-site.com --pages-only
```

Progress bar chalega aur end pe counts dikhenge:

```
Posts found .................... 120
Posts imported ................. 120
Posts skipped (already imported)  0
Posts failed ...................  0
```

### Step 4: Drafts review karo, phir publish

Admin me posts kholo → jo theek lagti hain unhe **Publish** karo (ek ek kar ke ya bulk select kar
ke). Jab tak aap khud publish nahi karte, site pe public kuch nahi jata.

> **Direct publish ka option hai lekin recommended NAHI:**
> `--publish` flag laga do to posts import hote hi published ho jayengi. Pehli import me drafts
> me lena hamesha better hai — malware cleanup ka result aankhon se verify kar lo.

### REST mode ke sari options

| Option | Kaam |
|---|---|
| `--url=https://...` | Purani site ka URL (**zaroori**) |
| `--limit=5` | Sirf N posts (test ke liye) |
| `--publish` | Import hote hi publish (default: drafts) |
| `--posts-only` | Sirf posts, pages skip |
| `--pages-only` | Sirf pages, posts skip |
| `--without-media` | Images download na karo |
| `--no-terms` | Categories/tags import na karo (usually mat use karna) |

---

## 4. Mode 2 — Database Import (Site Offline/Deploy Ke Baad)

Ye mode tab kaam aata hai jab **WP site down ho jaye** — sab data WP ki MySQL database se aata hai
aur images **disk se copy** hoti hain (internet ki zaroorat nahi).

### Step 1: WP database ka dump (backup) lo

**cPanel se (agar hosting abhi chal rahi hai):**
1. cPanel → **phpMyAdmin**
2. Left me apni WP database select karo
3. **Export** tab → Quick → SQL → **Go** → `.sql` file download

**Local XAMPP se (agar WP ka backup pehle se hai):** phpMyAdmin → Import → `.sql` file.

### Step 2: Dump ko local MySQL me import karo

1. XAMPP MySQL start karo (`C:\xampp\mysql_start.bat`)
2. Browser: `http://localhost/phpmyadmin`
3. **New** → database banao (e.g. `purani_wp_db`) → **Import** → apni `.sql` file → Go

> Bari databases (50MB+) phpMyAdmin me fail ho sakti hain — tab terminal se import karo:
> ```bash
> C:/xampp/mysql/bin/mysql.exe -u root purani_wp_db < "C:/backups/purani-site.sql"
> ```

### Step 3: Uploads folder copy karo

Purani site ka `wp-content/uploads` folder kahin rakh do, e.g.:

```
C:/backups/wp-content/uploads/
├── 2023/
├── 2024/
└── 2025/
```

- Ye cPanel File Manager se zip kar ke download hua hoga, ya FTP se.
- Purani site ka base URL bhi note rakho (e.g. `https://purani-site.com`) — content ke andar
  image URLs isi se match honge.

### Step 4: Import chalao

```bash
"%PHP%" -d max_execution_time=0 artisan wp:migrate --from-db ^
  --db-name=purani_wp_db ^
  --uploads-path="C:/backups/wp-content/uploads" ^
  --wp-base-url=https://purani-site.com
```

(PowerShell me `^` ki jagah backtick `` ` `` ya poori command ek line me likho.)

Start pe aisa kuch dikhega:

```
Migrating from DATABASE purani_wp_db@127.0.0.1:3306 (prefix: wp_, media: C:/backups/..., ...)
Image files in uploads copy ....... 1240
Categories found .................. 12
Tags found ........................ 45
Fetching posts from database...
Posts found ....................... 120
```

### Step 5: cPanel ki DB ko DIRECT use karna (dump ke baghair)

Agar purani site ka hosting hai aur cPanel ne **Remote MySQL** allow kiya hai (cPanel →
Remote MySQL → apna IP add karo), to dump ki zaroorat hi nahi:

```bash
"%PHP%" -d max_execution_time=0 artisan wp:migrate --from-db ^
  --db-host=server-host.com --db-port=3306 ^
  --db-name=cpanel_wpdb --db-user=cpanel_user --db-pass=secret ^
  --uploads-path="C:/backups/wp-content/uploads" ^
  --wp-base-url=https://purani-site.com
```

### Database mode ki sari options

| Option | Default | Kaam |
|---|---|---|
| `--from-db` | — | DB mode ON karta hai (**zaroori**) |
| `--db-name` | — | WP database ka naam (**zaroori**) |
| `--db-host` | `127.0.0.1` | DB host |
| `--db-port` | `3306` | DB port |
| `--db-user` | `root` | DB user |
| `--db-pass` | *(khali)* | DB password |
| `--db-prefix` | `wp_` | WP table prefix (kisi ne change kiya ho to) |
| `--uploads-path` | — | WP uploads folder ki copy (images ke liye) |
| `--wp-base-url` | `--url` | Purana site URL (image URLs match karne ke liye) |
| `--statuses` | `publish` | Kaunse post statuses import hon: `publish,draft,pending` |
| `--limit` | *(sab)* | Sirf N items (test ke liye) |
| `--publish` | off | Import hote hi publish (default: drafts) |
| `--posts-only` / `--pages-only` | off | Ek hi type import karo |
| `--without-media` | off | Images skip |
| `--no-terms` | off | Categories/tags skip |

### Database mode me media kaise chalti hai

- Content/featured image URLs (kisi bhi domain pe: `purani-site.com`, `cdn.xyz.com`) se
  `/uploads/2024/05/photo.jpg` jaisa path nikala jata hai
- Wo file `--uploads-path` wali copy me dhoondi jati hai
- Size renditions (`photo-300x200.jpg`) na milein to **full size** (`photo.jpg`) use hota hai
- File mil gayi → media library me copy hoti hai (`wp-import` folder) → content ka URL rewrite
- File na mile → original URL rehne dete hain + log me warning (post phir bhi import hoti hai)

---

## 5. Import Ke Baad: Review + Publish

1. Admin: `http://127.0.0.1:8000/ca-admin-9K4` → **Blog → Posts**
2. Status filter me **Draft** select karo — sari imported posts yahan hain
3. Kuch sample posts khol ke check karo:
   - Content saaf hai? (koi `<script>` nazar nahi aani chahiye — sanitizer laga hai)
   - Featured image lagi hai?
   - Categories/tags sahi?
   - SEO fields (title/description) filled?
4. Theek hain to publish karo — ek ek kar ke ya bulk me
5. Pages bhi aise hi check karo (**Pages** section)
6. **Front-end pe kholo:** `http://127.0.0.1:8000/purani-post-slug` — khulni chahiye

---

## 6. SEO Kaise Safe Rehta Hai (Details)

### Slugs
WP ki post `/best-jobs-2026` pe thi → Botble me bhi `/best-jobs-2026`. Google ka indexed URL
same rehta hai → ranking lose nahi hoti.

### 301 Redirects (purane URL patterns ke liye)

Agar WP me permalinks `/%year%/%month%/%postname%/` wale the, to purane URLs ab bhi kaam karenge —
`routes/web.php` me fallback redirect laga hai jo mapping table se resolve karta hai:

| Purana WP URL | Kya hoga |
|---|---|
| `/2024/05/best-jobs-2026` | → 301 → `/best-jobs-2026` |
| `/2024/05/01/best-jobs-2026` | → 301 → `/best-jobs-2026` |
| `/category/jobs` | → 301 → Botble category URL |
| `/tag/govt` | → 301 → Botble tag URL |
| `/koi-bhi-purana-slug` | → 301 → matching post (agar mapping me hai) |

Ye redirects **tabhi kaam karte hain jab import kiya ho** (mapping table rows import se banti hain).

### Deploy ke baad (production)
1. `php artisan optimize` chalao
2. `/sitemap.xml` Search Console me submit karo
3. Pehle 2 hafte **Logs → 404** monitor karo — koi purana URL miss na ho

---

## 7. Elementor Wali Posts (Important!)

Agar purani site ki posts **Elementor builder** se bani hain, to kuch khas baatein:

- Elementor apna asli content `post_content` me **nahi** rakhta — wo `wp_postmeta` ki
  `_elementor_data` key me JSON tree rakhta hai. `post_content` me sirf ek degraded snapshot hota hai.
- **REST API mode me Elementor data nahi milta** (API wo meta expose nahi karta) — is liye
  Elementor sites ke liye **DB mode hi use karo**.
- DB mode automatically `_elementor_data` ko parse kar ke **proper HTML banata hai**: headings,
  paragraphs, images, buttons, lists, galleries, video links — sab document order me.
- Layout grids flatten ho jate hain (2-column design → ek ke neeche ek) — content kuch nahi khota.
- Jo widgets support nahi (maps, forms, sliders) wo skip hote hain; agar post ka koi output hi na
  bane to original snapshot fallback hota hai.
- Import ke baad har Elementor post ko **admin editor me ek dafa khol kar dekho** — formatting
  waisi hi hai jaise reader ko chahiye (writer ke tools ke baghair).

## 8. Malware Cleanup (Kyunki Purani Site Infected Thi)

Har imported post/page/category ka content is se guzarta hai ([ContentSanitizer](app/Services/Wp/ContentSanitizer.php)):

- `<script>`, `<iframe>`, `<object>`, `<embed>`, `<form>`, `<template>` — poore remove
- `onclick=`, `onerror=` type sari inline event handlers remove
- `javascript:` / `data:` URLs neutralize
- Raw PHP snippets (`<?php ... ?>`) remove
- WP ke anjaan shortcodes `[xyz]` remove
- Aakhir me **HTMLPurifier** (Botble ka configured policy) final check karta hai

> Yani infected site ka content bhi clean aata hai — virus scripts nayi site pe nahi aayengi.
> Lekin phir bhi drafts review karo, 100% automation kabhi bharosemand nahi hota.

---

## 9. Masail Aur Unka Hal (Troubleshooting)

**`Could not connect to the WordPress database`**
- XAMPP MySQL chal raha hai? (`C:\xampp\mysql_start.bat`)
- `--db-name` ka spelling, `--db-user`/`--db-pass` check karo
- cPanel remote DB hai to host/port aur Remote MySQL IP allowlist check karo

**`Uploads path not found: ...`**
- Path ka spelling check karo; Windows me `/` ya `\` dono chalte hain
- Path **folder** ka hona chahiye (khud `uploads` pe khatam), kisi file ka nahi

**Images post me nahi aayin (content me purane URLs)**
- `--uploads-path` ke baghair chalaya tha → media skip ho gayi. **Aage ke liye:** path ke sath
  dobara chalao, lekin pehle se import hui posts re-run pe SKIP ho jati hain (mapping ki wajah se).
  Un posts ko dobara media ke sath lene ke liye admin me un posts ko delete karo aur re-run chalao —
  mapping row bhi delete hoti hai, post fresh import hogi.
- Kuch specific files na milein → log me warnings (`storage/logs/laravel.log`) dekho, kaunsi
  files miss huin.

**Import beech me ruk gaya / net gaya / PC band hua**
- Tension not! **Wahi command dobara chalao** — jo import ho chuki hai wo `skipped` counts me
  aayegi, sirf bache hue items import honge.

**REST mode me `401 Unauthorized` ya khali posts**
- Site ka REST API closed hai (security plugin se block). Phir DB mode use karo — zyada reliable bhi hai.

**Bohat sari posts fail ho gayin**
- End pe top 5 errors print hote hain + poora list `storage/logs/laravel.log` me hai
- Fix karo aur re-run (resume-safe)

**PHP "not recognized" error**
- Poori path use karo: `C:/Users/Muhammad Aliyan/Downloads/Compressed/php-8.3.33-nts-Win32-vs16-x64/php.exe`
- `-d max_execution_time=0` lagana mat bhoolo

---

## 10. Testing — Sab Kuch Verified Hai

```bash
"%PHP%" -d max_execution_time=0 vendor/phpunit/phpunit/phpunit tests/Unit tests/Feature --no-coverage
```

**95 tests, 272 assertions — sab green.** Migration ke apne tests:

| Test file | Kya cover karta hai |
|---|---|
| `tests/Unit/WpDbClientTest.php` (15) | WP DB rows → REST-shape payloads, Yoast/RankMath meta, template vars (`%%title%%`), safe unserialization |
| `tests/Unit/ElementorConverterTest.php` (12) | Elementor JSON tree → clean HTML (headings, images, buttons, lists, video links, ordering, fallback) |
| `tests/Unit/LocalMediaCopierTest.php` (10) | URL → disk path resolution, renditions fallback, any-host matching |
| `tests/Unit/WpClientTest.php` | REST pagination, error handling |
| `tests/Unit/ContentSanitizerTest.php` | Malware strip, image rewrite |
| `tests/Feature/WpMigrateFromDbCommandTest.php` (7) | **End-to-end**: asli WP-schema DB bana kar import — malware strip, slugs, dates, sticky, SEO meta, statuses/limit options, re-run idempotency |
| `tests/Feature/WpMigrateCommandTest.php` | Command validation |

---

## 11. Technical Map (Kya Kahan Hai)

```
app/Console/Commands/WpMigrateCommand.php   wp:migrate command (dono modes)
app/Services/Wp/WpClient.php                REST API client (live site)app/Services/Wp/WpDbClient.php                WP database reader (offline mode)
app/Services/Wp/ElementorConverter.php        Elementor JSON → clean HTML (DB mode me auto)
app/Services/Wp/WpImporter.php              Common import engine (slugs, dates, SEO, mapping)
app/Services/Wp/ContentSanitizer.php        Malware cleaner
app/Services/Wp/MediaDownloader.php         HTTP se images (REST mode)
app/Services/Wp/LocalMediaCopier.php        Disk se images (DB mode)
app/Services/Wp/MediaSource.php             Dono media services ka contract
app/Models/WpImportMapping.php              Resume-safety + 301 redirects ki mapping table
database/migrations/...wp_import_mapping    Mapping table schema
routes/web.php                              301 redirect fallback (legacy WP URLs)
DEPLOYMENT.md                               Deployment overview (Phase 1/1b/2)
```

**Ander ka flow (dono modes same):**

```
Source (REST ya DB) → REST-shape payload → ContentSanitizer (malware clean)
  → Media (download ya disk-copy) → content URLs rewrite
  → Post/Page create (draft) → original date force-fill
  → Slug create (WP slug preserve) → SEO meta save (Yoast/RankMath → Botble)
  → Categories/tags attach → mapping row (resume + 301 redirects)
```

---

## 12. Quick Checklist (Jaldi Kaam Karne Walon Ke Liye)

- [ ] XAMPP MySQL chal raha hai
- [ ] Site online hai → **REST mode**; site down hai → **DB mode**
- [ ] Elementor posts hain? → **DB mode zaroori** (REST me Elementor data nahi aata)
- [ ] REST: `--limit=5` pehle chalao, drafts admin me check karo
- [ ] DB: WP DB dump import karo + uploads folder copy rakho
- [ ] Full import chalao (drafts me)
- [ ] Admin me drafts review → publish
- [ ] Front-end pe 2-3 purane URLs khol ke check karo (dated + plain)
- [ ] Production deploy pe: sitemap submit, 404 monitor 2 hafte

---

*Badlav ki tareekh: October 2026 — DB import mode + tests is project me hi bane hain.*
