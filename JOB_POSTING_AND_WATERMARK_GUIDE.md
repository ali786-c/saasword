# 📌 CareerInPak — Complete Job Posting & Watermarking Guide 2026

Yeh guide **CareerInPak.com** par nayi job posts create karne, 11-section SEO template ko follow karne, aur advertisement pictures par automatic **`careerinpak.com`** watermark lagane ke liye mukammal SOP (Standard Operating Procedure) hai.

---

## 🎯 1. Overview & Key Features

Every job post published on **CareerInPak** follows an **11-section AdSense & SEO compliant structure**:
* **High-Contrast Typography:** Body text in crisp dark black (`#111111`) with bold entity highlights.
* **Red Banner Headings:** Solid red background (`#e53935`) with white text and left blue accent border (`5px solid #007bff`).
* **Dynamic Last Date / Expiry Box:**
  * 🟢 **Active Job:** Light blue box (`#eef7ff`) with 📅 calendar badge + remaining days countdown.
  * 🔴 **Expired Job:** Light red box (`#fdf2f2`) with ❌ warning badge ("This Job Has Expired").
* **Google Jobs SEO (`schema.org/JobPosting`):** Embedded JSON-LD structured data for Google Jobs Search Snippets.
* **Bilingual How to Apply:** Pure English for main content + RTL Urdu Nastaliq box for Pakistani applicants.
* **Automatic Image Watermarking:** Stamping `careerinpak.com` on all uploaded advertisement pictures.

---

## 📝 2. How to Create a Job Post

### 🟢 Method A: Programmatic / CLI Generation (Recommended for Fast Posting)
Humari system mein Laravel artisan commands pehle se moujood hain:

```bash
# 1. Create a Fresh Active Job Post (e.g. FBR 2026)
php artisan cms:create-fresh-job --type=fbr

# 2. Create an Expired Job Post Demo (e.g. PAEC 2026)
php artisan cms:create-fresh-job --type=paec
```

### 🔵 Method B: Using `JobPostTemplateService` in Code
Aap kisi bhi Controller ya Script ke andar `JobPostTemplateService::render($jobData)` call kar ke HTML generate kar sakte hain:

```php
use App\Services\JobPostTemplateService;

$jobData = [
    'posted_on'           => 'October 02, 2026',
    'city'                => 'Islamabad, Lahore, Karachi',
    'education'           => 'Bachelor / Master / Intermediate',
    'vacancies'           => '350+ Positions',
    'apply_method'        => 'Online via FPSC & FBR Portal',
    'organization'        => 'Federal Board of Revenue (FBR)',
    'salary'              => 'BPS-11 to BPS-16 (Rs. 45,000 - 95,000/Month)',
    'official_source_url' => 'https://www.fbr.gov.pk',
    'official_apply_url'  => 'https://www.fpsc.gov.pk',
    'last_checked'        => 'October 02, 2026',
    'deadline'            => 'October 25, 2026', // Format: Month Day, Year
    'ad_image_url'        => '/storage/news/advertisement.jpg',
    'also_apply_title'    => 'PPSC Jobs 2026 Advertisement No 18',
    'also_apply_url'      => '/ppsc-jobs-2026-punjab-public-service-commission-advertisement-no-18',
    'job_description'     => '<p>The <strong>Federal Board of Revenue (FBR)</strong> has announced...</p>',
    'who_can_apply'       => '<p>Citizens of Pakistan having valid domicile...</p>',
    'eligibility_criteria'=> '<ul><li><strong>Education:</strong> Bachelor Degree...</li></ul>',
    'vacant_positions'    => [
        [
            'name'      => 'Inspector Inland Revenue',
            'vacancies' => '180',
            'education' => 'Bachelor (Economics / BBA / B.Com)',
            'scale'     => 'BS-16 (Regular)',
            'location'  => 'All Regional Offices',
            'age_limit' => '20 - 33 Years',
        ],
    ],
    'documents_required'  => [
        'Original CNIC card copy',
        'Domicile Certificate of relevant district',
    ],
    'mistakes_to_avoid'   => [
        'Do not submit incomplete online forms.',
    ],
    'selection_process'   => [
        'Online registration on official portal.',
        'Screening MCQs test.',
    ],
    'how_to_apply_urdu'   => '<ol><li>ایف بی آر یا FPSC کی سرکاری ویب سائٹ پر جائیں...</li></ol>',
];

$renderedContent = JobPostTemplateService::render($jobData);
```

---

## 🖼️ 3. Advertisement Image Watermarking Guide

Jab bhi aap koi **Advertisement Picture** upload karenge:

### ⚙️ How Automatic Watermarking Works:
1. **Clean Thumbnails (Featured Image Cards, Grid Views, Home Page):** System thumbnails (150x150, 300x200, etc.) **pehle clean generate hote hain (WITHOUT watermark)** taake site ka layout aur cards bilkul neat aur clean rahein.
2. **Full Advertisement Picture (Modal View / High-Res View):** Full-size original advertisement image par automatic **bottom-right** corner mein `careerinpak.com` watermark stamp ho jata hai.
3. **Fully Automatic Process:** Aap ko sirf image upload karni hai, thumbnail clean rahega aur main full ad image par watermark automatically lag jaye ga.

### 📐 Recommended Image Dimensions & Specifications:

1. **Post Featured Image / Thumbnail (Best for Google & Social Shares):**
   * **Dimensions:** `1200 x 630` pixels (Landscape 16:9 / 1.91:1 ratio)
   * **Why:** This is the standard size for Google Search, Facebook OpenGraph, and Twitter/X Cards. It prevents awkward cropping on post cards.

2. **Official Newspaper Advertisement Image (Full Job Ad View):**
   * **Dimensions:** `1000 x 1400` pixels (Portrait 4:5 ratio)
   * **Why:** Newspaper job ads are long vertically. Portrait size keeps text clear and readable.

3. **Format & Compression:**
   * **Upload Format:** JPG or PNG (System **automatically converts to WebP** for 80% size reduction!).
   * **Ideal Size:** Under `1 MB` before upload.

### 💻 CLI Commands for Watermark Management:

```bash
# 1. Regenerate Watermark PNG with custom text (if domain changes)
php artisan cms:generate-watermark --text=careerinpak.com

# 2. Insert Watermark on all uploaded pictures in database
php artisan cms:media:insert-watermark
```

---

## 📋 4. Checklist for Job Posting

Barae meharbani nayi post publish karte waqt is checklist ko zaroor verify karain:

- [ ] **Title Format:** `[Organization Name] Jobs 2026 - [Post Name / Details]`
- [ ] **Deadline Format:** `October 25, 2026` (Expiry Box is se automatic active/expired calculate karta hai).
- [ ] **Text Contrast:** Ensure all body text uses `#111111` for high readability.
- [ ] **Heading Styling:** Every major section header must use the red banner style with left blue border.
- [ ] **Image Upload:** Upload official advertisement picture via Media Manager. Watermark automatically stamp ho jaye ga.
- [ ] **Google Jobs Schema:** Verify JSON-LD block is included at the top of post content.

---

## 🚀 5. Live Production Deployment Commands (cPanel)

Jab aap code ya database live cPanel par deploy karain, terminal par ye command chalayein:

```bash
cd ~/careerinpak.com && git fetch origin main && git reset --hard origin/main && php artisan cms:site-setup && php artisan cms:convert-images-to-webp && php artisan cms:generate-watermark --text=careerinpak.com && php artisan cms:media:insert-watermark && php artisan cms:enable-litespeed-cache && php artisan tinker --execute="\App\Services\LlmsTxtService::generate();" && php artisan optimize:clear
```

---

## 🤖 6. AI Pair-Posting Workflow & Quality Checkpoints

CareerInPak par post publishing ka **2-Step Workflow**:

1. **Step 1 (Raw Input):** Aap sirf job ka raw data ya ad picture/link AI Assistant ko dein.
2. **Step 2 (Auto Generation & Push):** AI Assistant automatic 11-section template engine (`JobPostTemplateService::render($jobData)`) use kar ke post create karega, SEO & AdSense rules enforce karega, Git push karega, aur aap ko cPanel deploy command provide karega.

### ✅ Mandatory Quality Checkpoints (Every Post Must Satisfy):
- [x] **Crisp Black Text (`#111111`):** High contrast body text with bold highlights.
- [x] **Red Banner Headings:** Solid red (`#e53935`) background with left blue border accent (`5px solid #007bff`).
- [x] **Dynamic Deadline Notice Box:** Active light blue (`#eef7ff`) countdown vs. Expired light red (`#fdf2f2`) alert box.
- [x] **Google Jobs Schema (`schema.org/JobPosting`):** Embedded JSON-LD script for rich snippets.
- [x] **Clean Thumbnails + Watermarked Full Ad Image:** Automatic `careerinpak.com` watermark on main ad picture only.
- [x] **Automatic WebP Conversion:** Next-Gen WebP encoding for ultra-fast loading.
- [x] **Dynamic `llms.txt` Feed:** Real-time update of AI agent discoverability file.
- [x] **Git Pushed & Live cPanel Command:** Clean git sync and copy-paste live server deployment command.

---

*Guide updated as of October 2026 for CareerInPak.com (Botble CMS / Laravel 12).*
