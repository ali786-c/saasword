# Live Website Pe WP Import — cPanel Guide (Step by Step)

> **Aapki live WordPress site ki DB cPanel me hai — Botble bhi wahi cPanel pe hai.**
> Ye guide usi setup ke liye hai: import **server pe hi** chalega, images **server pe hi copy**
> hongi (koi upload/download nahi), aur WP site chalti rahegi kyunke import sirf **read** karta hai.

---

## 0. Pehle Ye Samajh Lo

```
Same cPanel Server
├── WordPress site        →  ~/public_html/ (ya jahan bhi ho)
│   ├── wp-config.php         (DB details yahan hain)
│   └── wp-content/uploads/   (images yahan hain — 127 posts ki)
│
└── Botble site           →  ~/careerinpak.com/
    └── artisan wp:migrate     (ye command WP DB + uploads dono parh kar import karegi)
```

- Import **read-only** hai — WP ki site/DB me kuch change nahi hota, site live rehti hai ✅
- Sab kuch **localhost pe** hota hai — fast hai, internet speed ki tension nahi ✅
- Posts **drafts** me aayengi — review karne ke baad publish karoge ✅

---

## 1. Botble Ka Code Update Karo (Elementor support ke liye)

```bash
cd ~/careerinpak.com
git pull origin main
php artisan optimize:clear
php artisan optimize
```

---

## 2. WordPress Ki DB Details Nikalo

Apni WP install ka path dhoondo (agar yaad nahi to):

```bash
ls ~/public_html/wp-config.php
# ya
find ~ -maxdepth 3 -name "wp-config.php" 2>/dev/null | head -5
```

Ab us file se DB details nikalo (**path apni WP install ke hisab se badlo**):

```bash
grep -E "DB_NAME|DB_USER|DB_PASSWORD" ~/public_html/wp-config.php
```

Output aisa kuch aayega:

```php
define( 'DB_NAME', 'devwitguru_wp123' );      ← ye DB_NAME hai
define( 'DB_USER', 'devwitguru_wpuser' );     ← ye DB_USER hai
define( 'DB_PASSWORD', 'KoiPassword123' );    ← ye DB_PASSWORD hai
```

**Teen cheezein note kar lo:**
- `DB_NAME` = e.g. `devwitguru_wp123`
- `DB_USER` = e.g. `devwitguru_wpuser`
- `DB_PASSWORD` = e.g. `KoiPassword123`

---

## 3. Uploads Folder Ka Path Confirm Karo

```bash
ls ~/public_html/wp-content/uploads/ | head -5
```

Agar images dikh jayen (2024, 2025, 2026 folders waghera) to path ye hai:

```
/home/APNA_USERNAME/public_html/wp-content/uploads
```

`APNA_USERNAME` = aapka cPanel username (terminal me `whoami` chala kar dekh sakte ho).
**Ye poora absolute path note kar lo** — agli command me chahiye.

---

## 4. PEHLE TEST IMPORT (3 newest posts)

`SAB KUCH APNI VALUES SE REPLACE KARO` (DB_NAME, DB_USER, DB_PASSWORD, PATH, DOMAIN):

```bash
cd ~/careerinpak.com

php -d max_execution_time=0 -d memory_limit=512M artisan wp:migrate \
  --from-db \
  --db-name=devwitguru_wp123 \
  --db-user=devwitguru_wpuser \
  --db-pass='KoiPassword123' \
  --uploads-path=/home/devwitguru/public_html/wp-content/uploads \
  --wp-base-url=https://aapki-site.com \
  --limit=3
```

**Output aisa aana chahiye:**

```
Migrating from DATABASE devwitguru_wp123@127.0.0.1:3306 ...
Categories found ....... 20
Tags found ............. 411
Posts found ............ 3
Posts imported ......... 3
Posts failed ........... 0
```

---

## 5. Admin Me Check Karo

1. `https://aapki-site.com/ca-admin-9K4` kholo
2. **Blog → Posts** → filter **Draft** → 3 nayi posts
3. Ek post kholo — **content (Elementor HTML), images, category, SEO title** sab check karo
4. Sab theek? → Step 6 pe jao

---

## 6. FULL IMPORT (Sari Posts)

Wahi command **bina `--limit`** ke:

```bash
cd ~/careerinpak.com

php -d max_execution_time=0 -d memory_limit=512M artisan wp:migrate \
  --from-db \
  --db-name=devwitguru_wp123 \
  --db-user=devwitguru_wpuser \
  --db-pass='KoiPassword123' \
  --uploads-path=/home/devwitguru/public_html/wp-content/uploads \
  --wp-base-url=https://aapki-site.com
```

- Saari published posts + pages + categories + tags + images import hongi
- Beech me ruk jaye (connection issue waghera)? → **wahi command dobara chalao** — sirf bachi hui cheezein import hongi (duplicate kabhi nahi)

---

## 7. Posts Publish Karo (Bulk Me)

Import ke baad sab drafts hain. Admin se publish karo:

**Blog → Posts → status filter "Draft" → saari select karo → Bulk Actions → Change Status → Published**

Ya ek command se (sirf imported posts publish hongi, demo nahi):

```bash
php artisan tinker --execute="\$ids = \App\Models\WpImportMapping::query()->where('wp_type','post')->pluck('local_id'); \Botble\Blog\Models\Post::query()->whereIn('id', \$ids)->update(['status' => 'published']); echo 'Published: ' . \$ids->count() . ' posts';"
```

---

## 8. Homepage Set Karo (Production Pe Pehli Baar)

Production pe abhi homepage set nahi hai. Ye command ek "Home" page bana kar usme aapki
**asli categories** ke sections daal degi aur usay homepage bana degi:

```bash
php artisan tinker --execute="
\$cats = \Botble\Blog\Models\Category::query()->pluck('id', 'name');
\$page = \Botble\Page\Models\Page::query()->firstOrCreate(['name' => 'Home'], ['status' => 'published', 'user_id' => 1]);
\$page->content = '[about-banner title=\"Welcome to CareerInPak\" subtitle=\"Latest Jobs & Scholarships\" text_muted=\"Jobs., Scholarships., Govt Jobs\" image=\"general/featured.png\"][/about-banner]'
    . '[featured-posts title=\"Featured posts\"][/featured-posts]'
    . '[blog-categories-posts category_id=\"' . (\$cats['Jobs'] ?? 0) . '\"][/blog-categories-posts]'
    . '[categories-with-posts category_id_1=\"' . (\$cats['Scholarships'] ?? 0) . '\" category_id_2=\"' . (\$cats['Govt Jobs'] ?? 0) . '\" category_id_3=\"' . (\$cats['Blog'] ?? 0) . '\"][/categories-with-posts]'
    . '[featured-categories title=\"Categories\"][/featured-categories]';
\$page->save();
theme_option()->setOption('homepage_id', \$page->id)->saveOptions();
echo 'Home page ID ' . \$page->id . ' set as homepage';
"
```

Phir 5 newest posts ko featured bana do (Featured section ke liye):

```bash
php artisan tinker --execute="\$ids = \App\Models\WpImportMapping::query()->where('wp_type','post')->pluck('local_id'); \$newest = \Botble\Blog\Models\Post::query()->whereIn('id', \$ids)->latest('created_at')->take(5)->pluck('id'); \Botble\Blog\Models\Post::query()->whereIn('id', \$newest)->update(['is_featured' => 1]); echo '5 posts featured';"
```

---

## 9. Cache Clear + Final Check

```bash
php artisan optimize:clear
php artisan optimize
```

Browser me `https://aapki-site.com` kholo:
- Homepage pe posts dikhni chahiye
- Koi post kholo — URL, images, formatting check karo
- `https://aapki-site.com/sitemap.xml` — sitemap me posts hain?

---

## 10. WP Site Down Karne Ka Time? (Cutover)

Import **pehle** karna (jab WP live hai) — phir jab sab check ho jaye:

1. **Search Console** me naya sitemap submit karo
2. Purane URL patterns ke liye **301 redirects** mapping table se automatic chal rahe hain
3. WP ko **delete na karo** — pehle 2 hafte sirf rename/disable kar do (agar koi masla aaye to backup)
4. Search Console me **Change of Address** request karo (agar domain same hai to zaroorat nahi)

---

## Common Problems

| Problem | Hal |
|---|---|
| `Could not connect to the WordPress database` | DB_NAME/DB_USER/DB_PASSWORD check karo (wp-config.php se exactly copy karo). cPanel DB user ka access us DB pe hona chahiye |
| `Uploads path not found` | Path galat hai — `ls` se confirm karo, poora absolute path likho (`/home/user/...`) |
| Images import nahi hui (`wp-content/uploads` ka path ghalat) | Path ke baghair chalaya to warning aati hai — dobara sahi path ke sath chalao. **Note:** already imported posts skip hongi — agar images chahiye to pehle un posts ko delete karo (admin se) phir re-run |
| Import bohat slow | Normal hai (hundreds of images) — terminal band na karo. Ruk jaye to wahi command dobara chalao (resume-safe) |
| `php: command not found` | cPanel ke Terminal me `php -v` check karo. Na chale to hosting se poocho ya cPanel → PHP Selector se CLI path lo |
| Memory error | Command me `-d memory_limit=512M` add karo (guide ki commands me already hai) |

---

## Zaroori Yaad Rakho

- **`--limit=3`** se pehle test — kabhi seedha full import na karo
- Import **resume-safe** hai — dobara chalane se duplicate nahi bante
- WP site **live rehti hai** import ke doran — visitors ko kuch farq nahi parta
- Import ke **baad** posts publish karo — pehle nahi
- **Backup:** import se pehle Botble ka backup bana lo (cPanel → Backup ya admin → Backup plugin)
