# CareerInPak Posting System & Complete Workflow Guide

This guide is the master Standard Operating Procedure (SOP) for creating, formatting, watermarking, publishing, and syncing job and scholarship posts on **CareerInPak.com**.

The main goal is to publish content that is useful, clear, trustworthy, easy for a Pakistani job seeker to understand, and 100% compliant with search engine (SEO) and AdSense standards.

---

## 1. Language Policy

Use this rule for every post:

- **Main Content:** Must be written in simple, pure English.
- **Tone:** Direct, clear, and easy to understand.
- **Prohibitions:**
  - Do NOT use Roman Urdu in the main body text.
  - Do NOT use difficult vocabulary, long complex sentences, or corporate jargon.
  - Do NOT use emojis.
  - Do NOT use AI-style filler or hype ("prestigious opportunity", "golden chance").
- **Urdu Allowed Location:** Urdu script is allowed **only** inside Section 4.9 ("How to Apply").

### Examples:
* **Bad:** "This prestigious opportunity offers a remarkable career pathway for ambitious candidates."
* **Good:** "Punjab Police has announced new jobs for eligible candidates. Read the official advertisement before applying. Check the last date, age limit, and required documents carefully."

---

## 2. Content Quality Goal & High-Value Standard

Every post must answer these core questions clearly:

1. What is the job or scholarship?
2. Which organization announced it?
3. Who can apply?
4. What education, age, domicile, and experience are required?
5. What is the last date / deadline?
6. How can the user apply?
7. Which documents are required?
8. Where is the official source or application link?
9. What should the candidate verify before applying?
10. Is CareerInPak official or an informational portal?

### High-Value Writing Rules:
- Write in simple English tailored for Pakistani readers.
- Do not copy advertisements line-by-line. Re-organize the information logically.
- Include practical original sections: Required Documents, Application Mistakes to Avoid, Selection Process, and Domicile/Eligibility highlights.
- Avoid filler text added solely to lengthen the post.

---

## 3. No-Assumption Rule

Never invent missing information.

If official sources omit optional details (e.g., exact salary, specific test date), omit those optional fields or leave optional table cells blank. Do not invent fake data.

### Mandatory Required Fields (Post Creation Blocks if Missing):
1. Focus keyword
2. Organization name
3. Apply method
4. Official source URL
5. Last checked date
6. At least one job title / position
7. "How to Apply" Urdu steps

---

## 4. Required Post Structure (11 Sections)

Every job post must follow this 11-section layout in sequence:

### 4.1 Short Introduction
- 2 short paragraphs explaining organization, main position, location, deadline, apply method, and official source note.
- Include the primary focus keyword naturally in the first paragraph.

### 4.2 Quick Job Summary
A clean summary card/table containing:
- Organization
- Job type
- Location
- Education
- Vacancies
- Last date / Deadline
- Apply method
- Official source
- Last checked date

*(Hide any row where data is unavailable instead of writing placeholder text).*

### 4.3 Who Can Apply
Simple English summary explaining: required education, age limits, domicile/province restrictions, gender quota, and experience requirements.

### 4.4 Vacant Positions
An HTML table with columns: `Sr.`, `Post Name`, `Vacancies`, `Education`, `Scale/Pay`, `Location`, `Age Limit`.

### 4.5 Eligibility Criteria
Bulleted list covering education, age limits, domicile requirements, and skill/test requirements.

### 4.6 Documents Required
A helpful checklist for applicants with standard verification note:
> *"The official advertisement may require some or all of the following documents. Candidates should confirm the final list from the official advertisement before applying."*
- CNIC copy
- Domicile Certificate
- Educational Degrees & Transcripts
- Experience Certificates (if applicable)
- Passport-size Photographs
- Fee Challan / Bank Draft receipt (if required)

### 4.7 Application Mistakes to Avoid
Crucial practical advice for real users:
- Do not apply after the last date.
- Do not enter incorrect CNIC or mobile number.
- Do not submit incomplete forms.
- Do not pay fees on unofficial links.
- Read official advertisement thoroughly before applying.
- Keep a copy of submitted receipt/form.

### 4.8 Selection Process
Explain official process steps (written test, physical test, interview, merit list) **only if mentioned**. Skip if not specified in official ad.

### 4.9 How to Apply (Urdu RTL Section)
This is the **only** section where Urdu is permitted.
- Must use pure Urdu script with `dir="rtl"` layout.
- Uses Nastaliq font stack (`font-family: 'Jameel Noori Nastaleeq', 'Noto Nastaliq Urdu', sans-serif`).
- Short numbered list (`<ol>`).

```html
<div dir="rtl" style="text-align: right; font-family: 'Jameel Noori Nastaleeq', 'Noto Nastaliq Urdu', sans-serif; line-height: 2;">
  <ol style="list-style-position: inside; padding-right: 15px;">
    <li>سب سے پہلے آفیشل اشتہار غور سے پڑھیں۔</li>
    <li>تعلیم، عمر، ڈومیسائل اور آخری تاریخ ضرور چیک کریں۔</li>
    <li>درخواست صرف آفیشل ویب سائٹ یا آفیشل طریقہ کار کے مطابق جمع کروائیں۔</li>
  </ol>
</div>
```

### 4.10 Official Source and Verification
Must include: official source link, advertisement image link, last checked date, and correction email (`info@careerinpak.com`).

> *"CareerInPak collected this information from the official advertisement or portal. Candidates should verify details from the official source before applying. If you find an error, contact us at info@careerinpak.com."*

### 4.11 Disclaimer
Mandatory notice on every post:

> *"CareerInPak is not a government website. We collect jobs and scholarships from official sources to help users find information in one place. CareerInPak does not guarantee selection, test calls, interviews, or employment. Always verify details from the official advertisement or portal before applying."*

---

## 5. Title Rules

Titles must be strictly factual without clickbait adjectives.
Format: `[Organization] Jobs [Year] - [Exact Vacancies or Main Position]`

* **Good Examples:**
  - `FBR Jobs 2026 - Inspector Inland Revenue & DEO 350+ Vacancies`
  - `University of Mianwali UMW Jobs 2026 - Consolidated Advt No 07/2026`
  - `PPSC Jobs 2026 - Punjab Public Service Commission Advertisement No 18`

---

## 6. SEO, Keywords & Google Jobs Schema

### Keyword Formula:
- **1 Primary Focus Keyword:** Used in Title (<60 chars), Meta Description (140-160 chars), Slug (lowercase hyphenated), and 1st Paragraph.
- **2 to 4 Secondary Keywords:** Placed naturally in body paragraphs.
- **Internal Links:** 2 to 4 contextual links to relevant hub pages (Govt Jobs, Sindh Jobs, Teaching Jobs, IT Jobs).
- **External Links:** Clear link to official source portal.

### Google Jobs JSON-LD Schema (`schema.org/JobPosting`):
Every post must embed or generate a clean JSON-LD script matching official data (`datePosted`, `validThrough`, `hiringOrganization`, `jobLocation`, `title`, `description`). No fake salary or address fields.

---

## 7. Visual Styling & CSS Specifications

| Visual Element | Styling Rules |
| :--- | :--- |
| **Body Typography** | High contrast dark text (`color: #111111; font-family: Inter, Roboto, sans-serif; line-height: 1.7;`) with bold entity highlights. |
| **Section Headings (H2/H3)** | Solid Red Banner (`background-color: #e53935; color: #ffffff; padding: 10px 15px; border-left: 5px solid #007bff; border-radius: 4px; font-weight: bold;`). |
| **Active Deadline Box** | Light blue background (`#eef7ff`), border `#b3d7ff`, 📅 calendar badge + dynamic countdown (`[job_countdown deadline="YYYY-MM-DD"]`). |
| **Expired Deadline Box** | Light red background (`#fdf2f2`), border `#f5c6cb`, ❌ alert badge ("This Job Has Expired"). |

---

## 8. Advertisement Images & Automatic Watermarking

### Image Standards:
1. **Featured Image / Grid Thumbnail:**
   - **Dimensions:** `1200 x 630` pixels (16:9 Landscape).
   - **Watermark:** **Clean (No watermark)** so home page cards and social thumbnails look neat.
2. **Full Newspaper Job Advertisement Image:**
   - **Dimensions:** `1000 x 1400` pixels (4:5 Portrait).
   - **Watermark:** Automatic `careerinpak.com` stamp in bottom-right corner.
   - **Format:** Automatic WebP encoding for 80% compression.

### Artisan Watermarking Commands:
```bash
# Generate watermark PNG stamp
php artisan cms:generate-watermark --text=careerinpak.com

# Apply watermark to all uploaded media
php artisan cms:media:insert-watermark

# Convert all images to WebP
php artisan cms:convert-images-to-webp
```

---

## 9. Programmatic Post Creation Engine (`JobPostTemplateService`)

In PHP code or CLI commands, posts are built via `App\Services\JobPostTemplateService::render($jobData)`.

### Complete `$jobData` PHP Schema Array:
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

$htmlContent = JobPostTemplateService::render($jobData);
```

### Quick CLI Artisan Commands:
```bash
# Create active job post demo
php artisan cms:create-fresh-job --type=fbr

# Create expired job post demo
php artisan cms:create-fresh-job --type=paec

# Create as draft
php artisan cms:create-fresh-job --type=umw --draft
```

---

## 10. AI Pair-Posting Protocol (Local-to-cPanel 2-Step Workflow)

To create and publish any job post efficiently with AI, follow this 2-step protocol:

### Step 1: User Provides Raw Job Data
The user provides basic job information (e.g., job title, ad picture, organization, deadline, key positions, or official URL).

### Step 2: AI Automated Post Generation & Publishing
The AI assistant takes the raw input and automatically:
1. Formats the post using `JobPostTemplateService::render($jobData)`.
2. Enforces all 11-section SEO & AdSense compliance rules.
3. Applies high-contrast text (`#111111`), Red banner headings (`#e53935`), and dynamic deadline notice boxes (`#eef7ff` / `#fdf2f2`).
4. Generates embedded Google Jobs `schema.org/JobPosting` JSON-LD data.
5. Embeds bilingual English body + Urdu RTL Nastaliq section for "How to Apply".
6. Creates the post as **Draft** status so admin can review and attach featured image on live CMS.
7. Auto-commits and pushes code/data to GitHub (`origin/main`).
8. Updates dynamic `llms.txt` and `llms-full.txt` feeds.
9. Provides the 1-line cPanel deployment command with `--draft` to sync live server database.

---

## 11. Live Production Deployment Command (cPanel)

Whenever new posts or code changes are pushed, execute this single terminal command on cPanel to update the live production server (posts created as **Draft** status):

```bash
cd ~/careerinpak.com && git fetch origin main && git reset --hard origin/main && php artisan cms:site-setup && php artisan cms:create-fresh-job --type=nadra --draft && php artisan optimize:clear
```

---

## 12. Quality Checkpoints Before Pushing Live

- [x] **Title Format:** `[Organization Name] Jobs 2026 - [Position / Vacancies]`
- [x] **Crisp Black Text (`#111111`):** High contrast body text with bold highlights.
- [x] **Red Banner Headings:** Solid red (`#e53935`) background with left blue border accent (`5px solid #007bff`).
- [x] **Dynamic Deadline Notice Box:** Active light blue (`#eef7ff`) countdown vs Expired light red (`#fdf2f2`) alert box.
- [x] **Google Jobs Schema:** Embedded `schema.org/JobPosting` JSON-LD block.
- [x] **Clean Thumbnails + Watermarked Full Ad Image:** Automatic `careerinpak.com` watermark on main ad picture only.
- [x] **WebP Conversion:** Next-Gen WebP encoding active.
- [x] **Language Rule:** Pure simple English main body + RTL Urdu Nastaliq box for "How to Apply".
- [x] **Git Pushed & Live cPanel Command Provided.**

---
*Guide updated October 2026 for CareerInPak.com (Botble CMS / Laravel 12).*


