# CarreerinPak Botble CMS — Setup, Migration & Deployment Guide

> **Node.js kahin nahi chahiye** — compiled theme assets included hain. Sirf PHP 8.2+ (local: 8.3.33) aur MySQL.

## Phase 0 — XAMPP Local Setup (ho chuka hai)

✅ PHP 8.3.33 (Downloads wala) — extensions gd/zip/intl/exif ON (php.ini backup: `php.ini.bak-cp`)
✅ Composer 2.10.3 (`composer.phar` project folder mein — `php composer.phar <cmd>`)
✅ `.env` — DB `careerinpak`, root/empty password, APP_KEY set, ADMIN_DIR=`ca-admin-9K4`
✅ `careerinpak` database MySQL mein ban gayi
✅ Phone-home disabled: [Core.php](platform/core/base/src/Supports/Core.php) `createRequest()` patch + `CMS_ENABLE_MARKETPLACE_FEATURE=false` — **koi request botble.com ko nahi jati**
✅ Boot verified: `artisan about`, `route:list` (239 routes)

**PHP chalane ka tarika** (PATH mein nahi hai):
```
C:\Users\Muhammad Aliyan\Downloads\Compressed\php-8.3.33-nts-Win32-vs16-x64\php.exe artisan serve
```

## Aapke manual steps (XAMPP)

1. XAMPP Control Panel → **Apache** start (MySQL already chal raha hai)
2. Browser: `http://localhost/<folder>/public` → web installer khulega → admin user banao
3. Login: `/public/ca-admin-9K4` (naya ADMIN_DIR — `/admin` nahi!)
4. **Appearance → Themes → "Stories" activate**
5. Homepage: Pages → new page → template **Homepage** → [index.blade.php](platform/themes/stories/views/index.blade.php) ke shortcodes paste → Theme options → Page → homepage set

## Phase 1 — WordPress Migration (REST API)

```bash
php artisan wp:migrate --url=https://purani-site.com            # drafts mein import (recommended)
php artisan wp:migrate --url=https://purani-site.com --publish  # direct publish
php artisan wp:migrate --url=... --posts-only                   # sirf posts
php artisan wp:migrate --url=... --without-media                # images skip
```

Kya hota hai:
- Posts + pages + categories + tags, **slugs aur publish dates preserve**
- **Malware cleanup**: [ContentSanitizer](app/Services/Wp/ContentSanitizer.php) — script/iframe/on* handlers/php snippets/shortcodes strip + HTMLPurifier
- **Images** WP se download hokar media library mein (`wp-import` folder), content URLs rewrite
- **Yoast/RankMath meta** (`yoast_head_json`) → Botble SEO fields
- **Re-run safe**: [wp_import_mapping](database/migrations/2026_09_29_181654_create_wp_import_mapping_table.php) table progress track karti hai — interrupt ho jaye to wahi command dobara chalao, sirf bache hue import honge

Flow: pehle `--url` ke sath **bina `--publish`** chalao → admin mein drafts review karo → publish karo.

## Phase 2 — SEO Preservation

- WP permalinks `/%postname%/` the → slugs 1:1 match (kuch nahi karna)
- Dated URLs (`/2024/05/post/`), `/category/x`, `/tag/x`, purane slugs → **301 redirects** [routes/web.php](routes/web.php) fallback se (mapping table se resolve)
- Sitemap: `/sitemap.xml` (Botble auto) — production pe Search Console mein submit karo
- robots.txt updated (`public/robots.txt`) — deploy pe Sitemap URL apne domain se update karo

## Phase 3 — Site Kit Equivalent (sab native, settings se)

| Site Kit feature | Kahan configure karein (admin panel) |
|---|---|
| Google Analytics (GA4) | Settings → **General → Website Tracking** → type "Google tag ID" → `G-XXXXXXXXXX`. Dashboard widget ke liye (optional): Analytics plugin settings mein GA4 property ID + service-account JSON |
| Search Console | `seo_helper_google_site_verification` setting mein verification token (theme head mein meta render hota hai) + sitemap submit |
| AdSense | Ads plugin → Settings → **Google AdSense mode** → client ID (`ca-pub-...`) ya auto-ads script |

Note: Ye nulled build hai — GA4 **dashboard widget** (Reporting API) ke liye Google service account credentials aapko khud banane hongi; **tracking tag** sirf G- ID se chal jata hai.

## Phase 4 — Security Hardening

Done: `ADMIN_DIR=ca-admin-9K4` (default `/admin` khatam), HTTP security headers ON (.env), marketplace/phone-home off.

Production `.env` mein zaroor:
```
APP_ENV=production
APP_DEBUG=false
FORCE_SCHEMA=https
SESSION_SECURE_COOKIE=true
```

Rules: **koi random nulled plugin install nahi** (yahi WP wali galti thi 😄), Backup plugin + daily cron ON rakho, strong admin password + alag username.

## Phase 5 — cPanel Deployment

1. Local pe final check → `php artisan optimize` (cache config/routes)
2. Zip banao (vendor + platform + public + app + ... — `.env` **exclude**) → cPanel File Manager upload → extract
3. cPanel MySQL DB + user banao → `.env` upload karo production values ke sath (DB creds, APP_URL, https flags)
4. cPanel Terminal/SSH: `php artisan migrate --force` (mapping table), `php artisan storage:link`
5. Cron: backup plugin ka command + (agar queue use ho) `queue:work`
6. DNS cutover → Search Console: naya sitemap submit + Change of Address request
7. Pehle 2 hafte: 404 monitor karo (Logs → 404) — koi purana URL miss na ho

## Phase 6 — Testing

```bash
php vendor/phpunit/phpunit/phpunit tests/Unit tests/Feature
```
21 tests: sanitizer (malware strip, image rewrite), WpClient (pagination, error handling), command validation. Sab pass ✅

## Project Map

```
app/Services/Wp/          WpClient, ContentSanitizer, MediaDownloader, WpImporter
app/Console/Commands/     WpMigrateCommand (wp:migrate)
app/Models/               WpImportMapping
database/migrations/      create_wp_import_mapping_table
routes/web.php            301 redirect fallback
scripts/                  phone-home audit helpers
tests/                    Unit + Feature tests
```
