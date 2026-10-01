# cPanel Deployment Guide (Git Se) — Step by Step

> **Roman Urdu me, cPanel Terminal ki exact commands ke sath.**
> Aapka code ab GitHub pe hai: `https://github.com/ali786-c/saasword` — cPanel se wahan se
> clone karoge, phir setup commands chalaoge. Har step ke sath "kya hoga" bhi likha hai.

---

## 0. Cheezein Jo Pehle Ready Karo (cPanel ke bahar)

| Cheez | Kahan se |
|---|---|
| Hosting login | Aapke hosting provider ne diya hoga |
| **Domain** cPanel account se linked | Domains section me nazar aata hai |
| **SSH/Terminal enabled** | cPanel → Terminal (ya Task Scheduler se bhi chal jata hai) |
| PHP version | cPanel → **Select PHP Version / MultiPHP Manager** → **PHP 8.2 ya 8.3** select karo |
| `composer` available? | cPanel Terminal me `composer -V` chala kar dekho (nii to Step 5 me composer.phar wala tareeqa likha hai) |
| WP DB dump (agar import karna hai) | Purani site se `.sql` file + `uploads` folder copy — WP-MIGRATION-GUIDE.md dekho |

> **Private repo note:** Agar repo private hai to cPanel me pehle ek **GitHub Personal Access
> Token (classic)** banao (GitHub → Settings → Developer settings → Tokens → repo scope). Clone
> karte waqt username me `ali786-c` aur password me token dalna hoga. Repo **public** hai to
> token ki zaroorat nahi.

---

## 1. cPanel Me MySQL Database Banao

**cPanel → MySQL® Databases** me:

1. **Database name:** `careerinpak` (prefix lagega, e.g. `ali786_careerinpak`) → Create
2. **User banao:** username + strong password → Create User
3. **Add User To Database** → user + database → **ALL PRIVILEGES** tick → Make Changes

**In 4 cheezon ko note kar lo** (`.env` me aayengi):
```
DB_HOST=localhost        (cPanel me hamesha localhost hota hai)
DB_DATABASE=ali786_careerinpak
DB_USERNAME=ali786_user
DB_PASSWORD=jo-strong-password-dala
```

---

## 2. Domain Ka Document Root Tayyar Karo

Best practice: project **`public_html` ke BAHAR** rakho aur domain ka document root
`public_html` ki jagah project ke `public` folder pe point karo — lekin shared hosting me
subfolder + `.htaccess` trick zyada asaan hai (Step 8 me handle kiya hai), is liye:

**cPanel → Domains → apni domain → Document Root** ko `public_html` hi rehne do.
Project `public_html` ke bahar clone hoga: `/home/ali786/saasword`

---

## 3. cPanel Terminal Kholo

cPanel → **Terminal** (Advanced section me). Ek black window khulegi — ye Linux shell hai.

---

## 4. GitHub Se Clone Karo

```bash
# public_html ke bahar jao
cd ~

# Purana koi folder ho to hatao (pehli baar skip karo)
# rm -rf saasword

# Clone karo
git clone https://github.com/ali786-c/saasword.git
cd saasword
```

> **Private repo hai to aise clone karo** (TOKEN ki jagah apna token):
> ```bash
> git clone https://ali786-c:TOKEN@github.com/ali786-c/saasword.git
> ```

Kya hoga: poora project (~11,500 files, vendor ke baghair) download hoga.

---

## 5. Composer Install (Dependencies)

```bash
cd ~/saasword

# Pehle dekho composer available hai
composer -V
```

**Agar composer mila:**
```bash
composer install --no-dev --optimize-autoloader --no-interaction
```

**Agar composer NAHI mila** (shared hosting pe common hai):
```bash
# Composer download karo local composer.phar ki tarah
cd ~
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# Ab project me install karo
cd ~/saasword
php ~/composer.phar install --no-dev --optimize-autoloader --no-interaction
```

> Is step me 2-5 minute lag sakte hain — poora `vendor/` folder banega.
> Agar memory limit error aaye to: `php -d memory_limit=512M ~/composer.phar install ...`

---

## 6. `.env` File Banao (SAB SE IMPORTANT)

Repo me `.env` nahi hai (security ke liye) — khud banani hai:

```bash
cd ~/saasword
cp .env.example .env
nano .env
```

**`nano` me ye values set karo** (Ctrl+O save, Ctrl+X exit):

```env
APP_NAME=CareerInPak
APP_ENV=production
APP_DEBUG=false
APP_URL=https://aapki-domain.com

# Ye key baad me Step 7 se aayegi — abhi rehne do
APP_KEY=

LOG_CHANNEL=daily

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ali786_careerinpak
DB_USERNAME=ali786_user
DB_PASSWORD=APKA-DB-PASSWORD
DB_STRICT=false

ADMIN_DIR=ca-admin-9K4
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true

ENABLE_HTTP_SECURITY_HEADERS=true
CMS_ENABLE_INSTALLER=false
CMS_ENABLE_MARKETPLACE_FEATURE=false
```

> **APP_URL me apna real domain** likhna (`https://`) — localhost nahi.
> `CMS_ENABLE_INSTALLER=false` — installer production pe off.

---

## 7. APP_KEY Generate Karo

```bash
cd ~/saasword
php artisan key:generate
```

Kya hoga: `.env` me `APP_KEY=base64:...` auto-fill ho jayegi (encryption ki key).

---

## 8. `public_html` Se Project Tak Link (Do Tareeqe)

### Tareeqa A (recommended — document root point karna)

Agar hosting allow kare to **cPanel → Domains → Edit** → Document Root badal kar
`/home/ali786/saasword/public` kar do. Bas — sab se clean rasta.

### Tareeqa B (shared hosting trick — .htaccess)

Document root change na ho sake to `public_html` ke andar ek `.htaccess` banao:

```bash
cd ~/public_html
nano .htaccess
```

Ye content dalo:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ /saasword/public/$1 [L]
</IfModule>
```

Phir `~/saasword/public/.htaccess` khud Laravel ka serve karega. Agar CSS/images na
milen to `.env` me `APP_URL=https://aapki-domain.com/saasword/public` bhi try karo
(behtar hai k Tareeqa A use karo).

---

## 9. Storage Link + Cache + Migrations

```bash
cd ~/saasword

# 1. Database tables banao (wp_import_mapping bhi isme banegi)
php artisan migrate --force

# 2. Media uploads ka link (images iske baghair nahi dikhengi!)
php artisan storage:link

# 3. Sab cache karo (speed ke liye)
php artisan optimize
```

Kya hoga:
- `migrate --force` — production me confirm ke baghair tables bana dega
- `storage:link` — `public/storage` → `storage/app/public` (media library isi pe chalti hai)
- `optimize` — config/routes/views cache — site fast hogi

---

## 10. Permissions (Agar 500 errors aayen)

Zyada tar cPanel pe zaroorat nahi hoti, lekin agar error aaye:

```bash
cd ~/saasword
chmod -R 775 storage bootstrap/cache
```

---

## 11. Pehli Baar Site Kholo

1. Browser: `https://aapki-domain.com` — homepage aana chahiye
2. Admin: `https://aapki-domain.com/ca-admin-9K4`
3. **Login problem?** Local wala admin user production DB me nahi hoga (fresh DB hai) —
   to pehli login ke liye user banana parega. 2 options:
   - **Option A (asaan):** `.env` me temporarily `CMS_ENABLE_INSTALLER=true` karke
     `https://aapki-domain.com/install` kholo → admin user banao → phir wapas `false` karo
   - **Option B (terminal):** tinker se seedha user banao:
     ```bash
     php artisan tinker --execute="\
       \$u = new \Botble\ACL\Models\User(); \
       \$u->first_name='Admin'; \$u->last_name='User'; \
       \$u->email='aapka@email.com'; \$u->username='admin'; \
       \$u->password=\Hash::make('StrongPassword@123'); \
       \$u->super_user=1; \$u->save(); echo 'User created: ID '.\$u->id;"
     ```

---

## 12. Theme Activate Karo (Fresh DB pe)

Fresh database me themes register nahi hongi jab tak plugins activate na hon:

```bash
cd ~/saasword
php artisan cms:theme:activate stories -n
php artisan cms:plugin:activate blog -n
php artisan cms:plugin:activate page -n
php artisan cms:plugin:activate seo-helper -n
php artisan cms:plugin:activate sitemap -n
php artisan cms:plugin:activate seo-boost -n
```

> Agar plugin pehle se active ho to error "already activated" aayega — ignore karo.

---

## 13. WP Content Import (Production Pe)

Ab wo data lao jo aapne local pe test kiya tha. **Do raste:**

### Raste A: Local DB ko dump karke production me import (RECOMMENDED)

Local pe import + review ho chuka hai? To local se dump lo aur cPanel me daal do:

```bash
# LOCAL PC pe:
C:/xampp/mysql/bin/mysqldump.exe -u root careerinpak > careerinpak-final.sql

# cPanel phpMyAdmin → careerinpak DB → Import → careerinpak-final.sql
```

### Raste B: Seedha WP DB se (agar WP abhi bhi online hai)

```bash
cd ~/saasword

# REST API mode (WP online hai to):
php artisan wp:migrate --url=https://purani-site.com

# Database mode (WP down hai, dump cPanel me import kiya hua hai):
php artisan wp:migrate --from-db --db-name=ali786_purani_wp \
  --uploads-path=~/backups/wp-content/uploads \
  --wp-base-url=https://purani-site.com
```

> Full detail: [WP-MIGRATION-GUIDE.md](WP-MIGRATION-GUIDE.md)

---

## 14. SEO Settings (Deploy Ke Baad)

1. **`.env` check:** `APP_URL=https://aapki-domain.com` (https + real domain)
2. **Admin → Settings → General:** site URL verify karo
3. **IndexNow key:** SEO Boost settings me `api_key` wahi rahegi jo local thi (ya nayi bana lo)
4. **Google Indexing:** service account JSON dobara upload karo (local wali file production pe nahi hogi)
5. **Sitemap:** `https://aapki-domain.com/sitemap.xml` → Google Search Console me submit
6. **robots.txt:** `public/robots.txt` me `Sitemap:` line ka URL apne domain se update karo
7. **SSL:** cPanel → SSL/TLS Status → domain pe AutoSSL/Let's Encrypt ON

---

## 15. Backup Cron (Zaroori!)

cPanel → **Cron Jobs** → Add:

```cron
# Daily backup (Botble ka backup plugin use karta hua)
0 3 * * * cd /home/ali786/saasword && php artisan backup:clean && php artisan backup:run >> /dev/null 2>&1
```

(Exact command Botble Backup plugin ki settings se verify kar lena.)

---

## 16. Pehle 2 Hafte — Monitor Karna

- **Logs:** cPanel File Manager → `~/saasword/storage/logs/` — daily errors dekho
- **404s:** Admin → Logs → 404 — koi purana WP URL miss to nahi ho raha
- **Search Console:** Coverage report me errors dekhte raho
- **Speed:** `php artisan optimize` already chala hua hai — agar config change karo to dobara chalao

---

## Quick Command Reference (cPanel Terminal)

```bash
# Project folder me jao
cd ~/saasword

# Naya code GitHub se lao (updates ke liye)
git pull origin main

# Pull ke baad hamesha:
php artisan migrate --force
php artisan optimize

# Storage link (agar missing ho)
php artisan storage:link

# Cache clear (masla ho to)
php artisan optimize:clear

# Theme/plugin activate
php artisan cms:theme:activate stories -n

# Logs dekho
tail -50 storage/logs/laravel-$(date +%Y-%m-%d).log
```

---

## Common Problems (Troubleshooting)

| Problem | Hal |
|---|---|
| `composer: command not found` | Step 5 ka `composer.phar` wala tareeqa use karo |
| 500 Internal Server Error | `.env` me `APP_DEBUG=true` temporarily → error dekho → fix → `false` wapas |
| CSS/images na chalein | `php artisan storage:link` + `APP_URL` sahi hai? + Step 8 ka Tareeqa A better hai |
| `database connection failed` | cPanel DB creds + `DB_HOST=localhost` (127.0.0.1 nahi) |
| Admin panel 404 | `ADMIN_DIR=ca-admin-9K4` hai `.env` me? URL me `/ca-admin-9K4` likh rahe ho? |
| Import slow/fail ho raha | `php -d max_execution_time=0 artisan wp:migrate ...` use karo |
| Themes khali dikh rahi hain | Step 12 ke activate commands chalao |

---

*Deploy hone ke baad ek test post bana kar check karo ke sab kuch (images, SEO meta, sitemap) production pe bhi chal raha hai.*
