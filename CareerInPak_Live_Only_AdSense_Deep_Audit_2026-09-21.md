# CareerInPak.com — Live-Only AdSense “Low Value Content” Deep Audit

**Audit date:** 21 September 2026  
**Scope:** Public live website only (`https://careerinpak.com/`)  
**Excluded from evidence:** local WordPress database, local files, development notes, and local publishing history  
**Reason:** AdSense rejection — “Low value content”

## 1. Executive conclusion

The live website currently presents a strong **site-level low-value risk**, even though individual job pages contain useful facts and the site is already receiving search traffic.

The main live-site problems are:

1. **Repetitive, promotional vacancy content dominates the site.** Of 97 live posts, 72 titles contain words such as “Amazing,” “Ultimate,” “Massive,” “Best,” “Top,” “Exclusive,” “Golden,” or “Urgent.”
2. **Expired jobs still look actionable.** Six of seven clearly expired sampled JobPosting pages still showed “Apply/Online Apply” and no visible closed notice; the seventh expired on the audit date and also remained active-looking.
3. **Trust/compliance is visibly unfinished.** The footer links to a Privacy Policy that returns 404. Default “Sample Page” and a test page named “Elementor #1046” remain indexable.
4. **The public information architecture is bloated.** The live REST API exposes 421 tags for only 97 posts. Thin tags, one-post categories, `Uncategorized`, and the single-author archive are indexable.
5. **The site claims more than it demonstrates.** About Us says every listing is “100% verified,” offers official syllabuses, past papers, expert preparation strategies, and helps users achieve “confirmed selections”; the visible site does not provide enough editorial methodology, named expertise, or evergreen resources to substantiate those claims.
6. **Technical consolidation is incomplete.** HTTP returns a full 200 page instead of redirecting to HTTPS, and schema still contains HTTP organization/logo URLs.
7. **Live response times were poor during this audit.** Sample public pages took roughly 6–20 seconds to complete from the audit location. Google PageSpeed API quota was unavailable, so lab Core Web Vitals could not be independently confirmed.

**Live-only root-cause confidence:** high. The rejection is unlikely to be fixed by one plugin setting, increasing word count, or adding more posts. CareerInPak must reduce low-value indexable inventory, make job status truthful, strengthen editorial accountability, and add original career help beyond rewritten vacancy advertisements.

## 2. Live inventory and crawlability

### What was detected

- `robots.txt`: 200 OK; normal crawling allowed; `/wp-admin/` blocked; sitemap declared.
- Rank Math sitemap index: 200 OK.
- Sitemap groups:
  - post sitemap: 97 URLs;
  - page sitemap: 6 URLs;
  - category sitemap: 12 URLs;
  - author sitemap: 1 URL.
- Total sitemap URLs: 116.
- Public REST API totals:
  - 97 posts;
  - 24 categories, including empty legacy/demo categories;
  - 421 tags.
- `ads.txt`: 200 OK and contains the correct AdSense publisher ID.

### Interpretation

Google can crawl and evaluate the site. The primary AdSense issue is not a robots.txt block or missing ads.txt. Crawlability exposes the quality issues rather than hiding them.

## 3. P0 critical issues

### 3.1 Footer Privacy Policy link is broken

The live footer links to:

`https://careerinpak.com/privacy-policy/`

That URL returns:

- HTTP 404;
- `noindex`;
- title “Page Not Found - CareerInPak.”

This is a major trust/compliance defect for a site connected to Google advertising/analytics products.

**Required fix**

- Publish a site-specific Privacy Policy at the linked URL.
- Explain cookies, analytics, Google advertising, data collection, data retention, contact forms, third-party links, and user choices.
- Make the effective date and last-updated date visible.
- Test the footer link on desktop and mobile.

### 3.2 Indexable default/test pages make the property look unfinished

Both URLs return 200 and `index, follow`:

| URL | Live title | H1 | Approx. visible words |
|---|---|---:|---:|
| `/sample-page/` | Sample Page - CareerInPak | 1 | 289 |
| `/elementor-1046/` | Elementor #1046 - CareerInPak | 0 | 167 |

The Elementor page also lacks an H1.

**Required fix**

- Delete both pages.
- Return 410 if they have no equivalent replacement.
- Remove them from the sitemap and all internal links.
- Do not redirect unrelated test pages to the homepage.

### 3.3 Expired jobs remain active-looking

Live rendered-page sample on 21 September 2026:

| Page | `validThrough` | Apply CTA detected | Closed notice detected |
|---|---:|---:|---:|
| PERA Jobs | 27 Aug | Yes | No |
| OGRA Jobs | 4 Sep | Yes | No |
| POF Jobs | 7 Sep | Yes | No |
| Islamabad Police | 20 Sep | Yes | No |
| Board of Revenue Punjab “new” | 20 Sep | Yes | No |
| CM Punjab Climate Internship | 18 Sep | Yes | No |
| PPSC Advertisement 9 | 21 Sep | Yes | No |

This is harmful to users and undermines the claim that all listings are verified. A correct schema deadline does not compensate for a misleading visible CTA.

**Required fix**

- At deadline, automatically change status to `Applications closed`.
- Remove/disable the Apply button.
- Preserve the closing date.
- Link to the latest relevant jobs hub.
- Remove current JobPosting markup after expiry or ensure Google’s expired-job requirements are followed.
- Never keep “urgent,” “apply now,” or equivalent wording on closed opportunities.

### 3.4 HTTP is a second live version of the site

Observed behavior:

- `https://careerinpak.com/` → 200;
- `https://www.careerinpak.com/` → 301 to the HTTPS non-www URL;
- `http://careerinpak.com/` → 200 and remains HTTP.

The homepage schema also contains:

- `http://careerinpak.com` organization URL;
- HTTP logo `url` and `contentUrl` values.

**Required fix**

- Force every HTTP URL to its equivalent HTTPS non-www URL with one 301 hop.
- Change every organization, logo, Open Graph and structured-data URL to HTTPS.
- Flush LiteSpeed/server caches after the change.
- Verify HTTP subpaths, images and sitemap URLs—not only the homepage.

## 4. Content-quality findings

### 4.1 Promotional titles are the norm, not the exception

Live public post titles were measured through the WordPress REST API:

- Total posts: 97.
- Promotional-superlative titles: **72 (74.2%)**.
- Titles longer than 65 characters: **31 (32.0%)**.
- Custom excerpts longer than 170 characters: **72**.

Common title modifiers include:

`Amazing, Ultimate, Massive, Best, Top, Exclusive, Urgent`

Examples:

- “PERA Jobs 2026 - Massive 101+ Vacancies in Punjab”
- “OGRA Jobs 2026 - Massive 16 Trainee Executive Vacancies”
- “Islamabad Police Jobs Recruitment 2026 - Ultimate 1620+ Vacancies”
- “CM Punjab Climate Internship 2026 - Amazing 60,000 Stipend”
- “Board of Revenue Punjab Jobs 2026 - Best Career Opportunities”
- “Sindh Police Jobs 2026 - Urgent 3146+ Vacancies under Shaheed Quota”

This creates a repetitive, sensational SERP footprint and weakens factual trust.

**Required fix**

Use factual titles:

`[Organization] Jobs 2026 — [Specific roles/vacancy count/ad number]`

Remove promotional adjectives unless they are part of the official announcement.

### 4.2 Most job posts use the same content skeleton

A sample of older live posts showed 14 of 17 using the identical H2 sequence:

`Job Description → Vacant Positions → How to Apply → Official Advertisement`

The repeated structure is not automatically wrong, but the body copy also repeatedly uses promotional language and generic career persuasion. Google’s indexed rendering of the FIA article includes phrases such as:

- “highly prestigious career”;
- “highly anticipated”;
- “golden opportunity”;
- “extremely competitive”;
- “exceptional candidates”;
- “perfectly meet”;
- “secure your future.”

The page also makes generalized claims about allowances, health benefits, housing options and job security. Every such claim must be supported by the official source.

**Required fix**

Replace persuasion/filler with applicant utility:

- exact source and advertisement/case number;
- eligibility interpretation;
- quota and domicile explanation;
- document checklist;
- fee and portal steps;
- likely rejection mistakes;
- source-verified salary/pay scale;
- status and last verified time;
- changes since the previous advertisement.

### 4.3 The site adds limited value beyond the advertisement

Most public content consists of vacancy announcements. The live sitemap contains very little durable guidance content. A jobs publisher needs a reason for users to choose it over the official advertisement.

High-value additions should include:

- original portal walkthroughs with current screenshots;
- PPSC/FPSC/NTS fee and PSID troubleshooting;
- domicile/quota explainers;
- official-document preparation checklists;
- job-scam verification guide;
- BPS/pay-scale explanations using official notifications;
- test syllabus and preparation guides backed by official sources;
- interview/document-verification guides;
- application error solutions;
- open/closing-today/closed status dashboards.

Publish at least 12–20 substantial evergreen resources before requesting another review.

### 4.4 About Us makes unsupported or excessive claims

Live About Us copy says:

- CareerInPak is “Pakistan’s premier portal”;
- “every job posting and scholarship link is 100% verified and authentic”;
- the site provides official syllabuses, past papers and expert preparation strategies;
- it helps users “secure confirmed selections”;
- it offers step-by-step international scholarship assistance.

Problems:

- No visible, detailed verification methodology was found.
- The author page is mainly a paginated post archive, not an expert profile.
- No named editors, qualifications or accountable reviewers were visible.
- “100% verified” is contradicted by expired active-looking jobs and the broken Privacy link.
- “Confirmed selections” is an inappropriate outcome claim.

**Required fix**

- Replace superlatives and guarantees with measurable processes.
- Explain what is checked, against which official source, by whom, and how often.
- Publish Editorial Policy, Sources & Verification Policy, Corrections Policy and Advertising Disclosure.
- Name responsible editors/reviewers and show relevant experience.

## 5. Structured-data audit

### What is working

- Recent sampled job pages generally contain JobPosting JSON-LD.
- `datePosted`, `validThrough`, hiring organization and location are commonly present.
- Recent NPF and NADRA pages had future expiry dates at audit time.

### Problems and risks

- FIA and Ministry of Defence live pages in the sample had no JobPosting schema while still presenting active Apply language.
- Expired JobPosting pages remain visibly actionable.
- Exact salary ranges are present in schema, for example:
  - PERA: PKR 100,000–250,000/month;
  - OGRA: PKR 40,000–80,000/month;
  - PPSC Advertisement 9: PKR 50,000–150,000/month;
  - NPF/NADRA samples: PKR 150,000/month.
- Employment types include `CONTRACT`, `CONTRACTOR`, `INTERN` and `FULL_TIME`.

These are acceptable only where the official advertisement explicitly supports them and the same information is visible on the page. Guessed salary ranges or default employment types are a serious structured-data accuracy risk.

**Required fix**

- Audit every JobPosting field against the official advertisement.
- Remove unsupported salary rather than estimating it.
- Use Google-supported employment type values consistently.
- Ensure all schema information is visible to users.
- Remove or update expired job markup immediately.
- Validate representative pages in Google Rich Results Test.

## 6. Taxonomy and index-bloat audit

### Live evidence

- 421 public tags for 97 posts—approximately 4.34 tags per post.
- 24 categories exist in the public API.
- Only 12 categories have enough activity to appear in the category sitemap.
- Empty legacy/demo categories include topics such as World, Tech, Games, Foods, Business, Travel and Life Style.
- Indexable thin archives include:
  - `/category/uncategorized/` — about 163 visible words;
  - one-post categories such as Chef Jobs and Saudia;
  - `/tag/engineering-jobs/`;
  - `/author/admin/`.
- Google search results already expose tag pages such as `/tag/fia-jobs/`.

Tags are absent from the sitemap but sampled tag pages still use `index, follow`; sitemap exclusion alone does not prevent indexing.

**Required fix**

- Noindex all ordinary tag archives now.
- Keep an indexable tag only if it becomes a manually curated hub with unique, useful copy and sustained inventory.
- Remove empty demo categories.
- Reassign `Uncategorized` content and remove/noindex that archive.
- Consolidate one-post categories.
- Noindex the single-author archive unless it becomes a real profile plus distinct editorial content.

## 7. Duplicate-intent and URL-quality risks

Suspicious live slug patterns include:

- `/pakistan-rangers-punjab-jobs-2026-4/`;
- `/ecsp-jobs-2026-2/`;
- `/quaid-e-azam-industrial-estate-jobs-2026-2/`;
- `/cm-e-bike-scheme-apply-online-3/`;
- `/ict-police-inspector-jobs-2026-2/`;
- `/national-cybercrime-investigation-agency-jobs-2026-2/`;
- `/board-of-revenue-punjab-jobs-2026-new/`.

The site also has multiple URLs around Islamabad Police, Board of Revenue, LWMC, Pakistan Rangers, PPSC transport and Punjab Agriculture.

Suffixes do not prove duplication, but they strongly indicate that a URL-by-URL intent review is needed.

**Required fix**

- Keep separate pages only when the advertisement number/date, roles or application window are genuinely distinct.
- Put the differentiator in the title and opening paragraph.
- Merge true duplicates using exact 301 mappings.
- Create organization hub pages for recurring recruiters.
- Do not mass-redirect old jobs to the homepage.

## 8. Trust, authorship and accountability

### Positive live signals

- About, Contact and Terms pages exist.
- Contact page returns 200 and contains:
  - `info@careerinpak.com`;
  - a Pakistani phone number;
  - Islamabad/Pakistan location information;
  - a contact form.
- Individual posts are attributed to “CareerInPak Editorial Team.”

### Weak signals

- Author URL remains `/author/admin/`.
- The author page is a generic archive and does not demonstrate the team’s identity, experience or review qualifications.
- Social metadata previously exposed generic `@admin` handles on sampled job pages.
- No live Editorial Policy, Corrections Policy, Verification Policy or Advertising Disclosure was found.
- About Us does not name a responsible editor or explain correction turnaround.

**Required fix**

- Replace the generic admin identity with a proper editorial profile.
- Add named ownership/responsibility where truthful and appropriate.
- Show “Verified by,” official source and “Last checked” on job pages.
- Add an easy “Report incorrect information” action on every post.
- Publish policies and link them in the footer.

## 9. On-page SEO and UX observations

### Homepage

- 200 OK, self-canonical and indexable.
- One H1: “Latest Govt Jobs, NTS, PPSC, and International Scholarships.”
- Browser title is 73 characters—likely to truncate.
- Approx. 605 visible words, but much of the page is cards/archive content rather than unique editorial guidance.
- HTML response was approximately 132 KB, with 19 script elements detected.

### Trust pages

- About title is only 22 characters and generic.
- Contact and Terms have correct single H1s.
- Sample Page and Elementor test page are still crawlable/indexable.

### Response-time warning

Observed total live fetch times from the audit environment:

- Homepage: ~15.8 s;
- About: ~13.0 s;
- Terms: ~11.1 s;
- Contact: ~6.6 s;
- Author archive: ~20.7 s.

These are not official Core Web Vitals and may include network/geographic variance. However, repeated slow responses and intermittent timeouts warrant immediate server profiling.

**Required fix**

- Check real-user CrUX/Search Console Core Web Vitals.
- Run PageSpeed Insights manually when API quota is available.
- Measure TTFB from Pakistan and Googlebot-relevant regions.
- Inspect LiteSpeed cache hit rate, PHP execution, external requests, Elementor asset load and database queries.
- Avoid adding more optimization plugins until the bottleneck is measured.

## 10. Security/header hygiene

The sampled HTTPS homepage response did not expose:

- `Strict-Transport-Security`;
- `Content-Security-Policy`;
- `X-Frame-Options`;
- `X-Content-Type-Options`;
- `Referrer-Policy`.

These are not direct “low value content” causes, but their absence contributes to an unfinished technical profile.

Add them carefully after compatibility testing. HSTS should be enabled only after all required hosts are permanently HTTPS-ready.

## 11. What should be preserved

Do not treat the site as worthless. Preserve and improve:

- clean self-canonical job URLs;
- official advertisement images;
- structured job summary fields;
- direct official application links;
- Search Console-performing URLs;
- clear organization, location, education and deadline information;
- mobile-first layout;
- Contact/About/Terms foundations.

The goal is to move from “rewritten advertisement + Apply button” to “verified application assistant.”

## 12. Live-only remediation plan

### Phase 1 — 48 hours

1. Publish the Privacy Policy at the existing footer URL.
2. Delete/410 Sample Page and Elementor #1046.
3. Enforce HTTP → HTTPS.
4. Normalize schema/logo URLs to HTTPS.
5. Pause publication of promotional/template-style posts.
6. Noindex tag archives, `Uncategorized`, empty/one-post categories and the generic author archive.
7. Remove exaggerated title modifiers from the most visible pages.

### Phase 2 — Days 3–10

1. Audit all 97 posts for deadline/status.
2. Mark expired pages closed and remove active CTAs.
3. Verify every schema field against the official source.
4. Build a duplicate-intent map and merge true duplicates.
5. Add official source, advertisement/case number, “Last checked” and correction link.
6. Rewrite the top 20–25 traffic pages around unique applicant value.

### Phase 3 — Days 11–24

1. Publish 12–20 substantial evergreen guides.
2. Create high-quality PPSC, FPSC, NTS, Punjab and Federal job hubs.
3. Publish Editorial, Verification, Corrections and Advertising policies.
4. Replace generic admin authorship with credible editorial accountability.
5. Improve homepage sections for open, closing soon and recently closed jobs.

### Phase 4 — Days 25–35

1. Recrawl all sitemap URLs.
2. Validate status codes, canonicals, meta robots and internal links.
3. Test representative JobPosting pages.
4. Check Search Console indexing and Core Web Vitals.
5. Confirm Google has recrawled the cleaned pages.
6. Request AdSense review only when the mandatory gate below passes.

## 13. Mandatory reapplication gate

- [ ] Privacy URL returns 200 and contains a complete policy.
- [ ] No test/default pages are indexable.
- [ ] HTTP redirects to HTTPS in one hop.
- [ ] No HTTP URLs remain in schema.
- [ ] Every expired job has a visible closed state and no active Apply CTA.
- [ ] JobPosting fields match official sources.
- [ ] Unsupported salary estimates are removed.
- [ ] Tags and thin archives are noindexed/consolidated.
- [ ] Duplicate-intent URLs are resolved.
- [ ] At least 12 strong evergreen resources are live.
- [ ] Top search-performing pages have been manually upgraded.
- [ ] Editorial, Verification, Corrections and Advertising policies are live.
- [ ] Author/reviewer accountability is visible.
- [ ] Promotional title modifiers are exceptional rather than present on 74% of posts.
- [ ] Response time/Core Web Vitals have been measured and major failures fixed.
- [ ] Sitemap contains only canonical, useful, indexable URLs.

## 14. Priority scorecard

| Area | Live status | Risk | Priority |
|---|---|---:|---:|
| Privacy/compliance | Footer link returns 404 | Critical | P0 |
| Expired job handling | Active CTA/no closed notice | Critical | P0 |
| Test pages | Indexable defaults | Critical | P0 |
| Content originality/value | Vacancy-heavy, repetitive/promotional | Critical | P0 |
| HTTP canonicalization | HTTP returns 200 | High | P0 |
| Taxonomy bloat | 421 tags / 97 posts | High | P1 |
| Editorial trust | Generic team/admin identity | High | P1 |
| Schema accuracy | Salary/employment fields require proof | High | P1 |
| Duplicate intent | Multiple suffixed/overlapping URLs | High | P1 |
| Performance | 6–20 s observed fetches | High, verify | P1 |
| Titles/descriptions | 31 long titles, promotional footprint | Medium | P2 |
| Security headers | Major headers absent | Medium | P2 |

## 15. Final verdict

The live site has useful raw job information, valid early search visibility and a functioning WordPress/Rank Math foundation. However, AdSense reviewers can currently see an unfinished trust layer, large repetitive vacancy footprint, active-looking expired jobs, thin indexable archives and too little demonstrable original career assistance.

The recovery strategy is therefore:

**clean → verify → differentiate → build trust → recrawl → reapply**

Do not solve this by increasing article length, creating more tags, forcing keyword density, or publishing more vacancy pages. Solve it by making each indexed URL accurate, accountable, current and meaningfully more useful than the source advertisement alone.

## Official guidance used

- Google AdSense: Make sure your site's pages are ready for AdSense — https://support.google.com/adsense/answer/7299563
- Google AdSense policies: beginner’s guide — https://support.google.com/adsense/answer/23921
- Google Publisher Policies overview — https://support.google.com/adsense/answer/10008391
- Google Search spam policies — https://developers.google.com/search/docs/essentials/spam-policies
- Google guidance on generative AI content — https://developers.google.com/search/docs/fundamentals/using-gen-ai-content

