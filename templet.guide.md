# CareerInPak Elementor Template Guide

This guide defines how the local Elementor job post template should be used and improved.

The template must support helpful, clear, trust-focused posts for Pakistani job seekers. The main post content must be simple English. Urdu is allowed only in the “How to Apply” section.

## 1. Current Template

Current Elementor template ID:

`1156`

Current placeholders found in the template:

- `[Date]`
- `[City]`
- `[Education]`
- `[Dept Name]`
- `[Salary]`
- `[Deadline]`
- `Detailed SEO paragraph about the job goes here.`
- `<ul><li>Position 1</li><li>Position 2</li></ul>`
- `<ol><li>Step 1 instructions.</li><li>Step 2 instructions.</li></ol>`

Current sections:

- Job Summary
- Job Description
- Vacant Positions
- How to Apply

These are useful, but not enough for AdSense/helpful-content quality.

## 2. Required Template Improvements

The template should be upgraded with these sections.

### 2.1 Quick Job Summary

This section should show:

- Organization
- Job type
- Location
- Education
- Vacancies
- Last date
- Apply method
- Official source
- Last checked date

Recommended placeholders:

- `[Organization]`
- `[Job Type]`
- `[City]`
- `[Education]`
- `[Vacancies]`
- `[Deadline]`
- `[Apply Method]`
- `[Official Source URL]`
- `[Last Checked Date]`

### 2.2 Job Description

This section should contain the main simple-English article.

Rules:

- pure English only
- no Roman Urdu
- no Urdu
- short paragraphs
- no clickbait
- no hard words
- no keyword stuffing
- explain the job clearly

Placeholder:

`[Job Description Content]`

The old placeholder can still be replaced:

`<p>Detailed SEO paragraph about the job goes here.</p>`

### 2.3 Who Can Apply

This is a required helpful section.

It should explain:

- education
- age limit
- domicile/province
- gender if mentioned
- experience
- fresh/experienced candidate suitability

Placeholder:

`[Who Can Apply]`

### 2.4 Eligibility Criteria

This section should use bullets or a clean table.

Placeholder:

`[Eligibility Criteria]`

### 2.5 Vacant Positions

Use a table, not a plain list, when data is available.

Recommended columns:

- Sr.
- Position
- Vacancies
- Education
- Scale/Pay
- Location
- Age Limit

Placeholder:

`[Vacant Positions Table]`

The old placeholder can still be replaced:

`<ul><li>Position 1</li><li>Position 2</li></ul>`

### 2.6 Documents Required

This section adds real user value.

Placeholder:

`[Documents Required]`

Recommended default text:

“The official advertisement may require some or all of the following documents. Candidates should confirm the final list from the official advertisement before applying.”

### 2.7 Application Mistakes to Avoid

This section is required.

Placeholder:

`[Mistakes To Avoid]`

Recommended points:

- Do not apply after the last date.
- Do not enter the wrong CNIC or mobile number.
- Do not submit incomplete information.
- Do not pay a fee on an unofficial link.
- Read the official advertisement before applying.

### 2.8 Selection Process

This section should explain official selection steps if mentioned.

Placeholder:

`[Selection Process]`

If not mentioned, skip this section. Do not show filler text.

### 2.9 How to Apply

This is the only section where Urdu is allowed.

Rules:

- Urdu script only
- no Roman Urdu
- use RTL styling
- clear step-by-step instructions
- do not repeat the same instructions in English elsewhere

Placeholder:

`[How To Apply Urdu]`

The old placeholder can still be replaced:

`<ol><li>Step 1 instructions.</li><li>Step 2 instructions.</li></ol>`

Recommended wrapper:

```html
<div dir="rtl" style="text-align: right; font-family: 'Jameel Noori Nastaleeq', 'Noto Nastaliq Urdu', sans-serif; line-height: 2;">
  <ol style="list-style-position: inside; padding-right: 15px;">
    <li>سب سے پہلے آفیشل اشتہار غور سے پڑھیں۔</li>
    <li>تعلیم، عمر، ڈومیسائل اور آخری تاریخ ضرور چیک کریں۔</li>
    <li>درخواست صرف آفیشل ویب سائٹ یا آفیشل طریقہ کار کے مطابق جمع کروائیں۔</li>
  </ol>
</div>
```

### 2.10 Official Source and Verification

This section is required for trust.

Placeholders:

- `[Official Source URL]`
- `[Official Advertisement URL]`
- `[Last Checked Date]`
- `[Verification Note]`
- `[Correction Email]`

Recommended text:

“CareerInPak collected this information from the official advertisement or official portal. Candidates should verify the details from the official source before applying. If you find an error, contact us at info@careerinpak.com.”

### 2.11 Disclaimer

This section is required on every post.

Placeholder:

`[Disclaimer Note]`

Recommended text:

“CareerInPak is not a government website. We collect jobs and scholarships from official sources to help users find information in one place. CareerInPak does not guarantee selection, test calls, interviews, or employment. Always verify details from the official advertisement or official portal before applying.”

### 2.12 Expired Job Notice

Do not add a separate manual expired notice section in the Elementor template.

Expiry is already handled by the existing deadline/countdown shortcode/plugin through the `[Deadline]` placeholder. The generator should continue to inject `[job_countdown deadline="YYYY-MM-DD"]` when an ISO deadline is available.

## 3. Template Layout Order

Recommended Elementor order:

1. Job Summary
2. Job Description
3. Who Can Apply
4. Eligibility Criteria
5. Vacant Positions
6. Documents Required
7. Application Mistakes to Avoid
8. Selection Process
9. How to Apply
10. Official Source and Verification
11. Disclaimer

This order helps users understand the job before they click the apply link.

## 4. Apply Button Rules

The apply button must point to:

- official application portal, or
- official advertisement/source page, or
- relevant category page only if no official direct link is provided

Button text should be clear:

- `Apply Through Official Portal`
- `View Official Advertisement`
- `Check Official Source`

Do not use misleading text.

## 5. Styling Rules

The template should stay clean and readable.

Use:

- simple headings
- short sections
- clean tables
- enough white space
- readable font size
- mobile-friendly spacing

Avoid:

- too many colors
- large decorative blocks
- hidden text
- keyword-stuffed headings
- fake badges
- dummy images

## 6. Placeholder Replacement Rules

When generating a post:

- replace every placeholder
- if optional information is missing, hide that row/field/section
- do not fill the post with repeated “Not mentioned in the official advertisement” text
- never leave raw placeholders visible
- keep HTML valid
- do not break Elementor JSON
- save `_elementor_data` using `wp_slash`

## 7. Local Publish, Live Draft Rule

Generated posts should be published locally first so the local preview and sync workflow are smooth. The live receiver/status mapping should still receive these posts as drafts for review.

Flow:

1. create local published post
2. inspect local Elementor preview
3. run quality checklist
4. sync to live draft
5. inspect live preview
6. publish after review

Do not auto-publish on the live site unless the user clearly asks.

## 8. Required Quality Checklist

Before pushing to live:

- main content is simple English
- no Roman Urdu in main content
- Urdu appears only in How to Apply
- official source is present
- last checked date is present
- disclaimer is present
- who can apply section is present
- documents section is present
- mistakes section is present
- selection process is present only if available
- missing data is not invented
- optional missing data is skipped
- apply button is official or clearly labeled
- no clickbait words
- no keyword stuffing
- no broken HTML
- no visible placeholders

## 9. Future Automation

The posting system should later add:

- pre-publish checklist inside WordPress admin
- missing-field warnings
- expired-job detection
- apply-button validation
- official-source validation
- quality score based on helpfulness, not only SEO
