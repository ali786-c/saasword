# CareerInPak AdSense “Low value content” — Deep Audit & Recovery Plan

**Audit date:** 21 September 2026  
**Site:** https://careerinpak.com/  
**AdSense status:** Needs attention — Low value content  
**Domain registration:** 10 August 2026  
**Evidence reviewed:** live site, public WordPress REST API, XML sitemaps, robots.txt, HTTP behavior, representative job pages, local WordPress repository/history, and Search Console export dated 13 September 2026.

## 1. Executive verdict

The rejection is most likely a **site-wide value and maturity problem**, not a single SEO setting or an ads.txt problem.

CareerInPak has genuine search demand and some useful job data, but its present footprint resembles a newly launched, high-output, template-driven jobs site:

- 97 published posts in roughly 42 days;
- only a very small amount of durable editorial/guidance content;
- 421 WordPress tags for 97 posts;
- many empty or one-post categories;
- nearly identical page structure across job posts;
- an explicitly programmatic publishing history based on fixed word counts and keyword-density targets;
- expired jobs that still display “Apply Now” without a clear closed notice;
- indexable demo/placeholder pages;
- no live Privacy Policy at the standard URL;
- weak and generic authorship/trust presentation;
- HTTP remains directly accessible instead of redirecting to HTTPS.

**Root-cause confidence:** high (about 85%). Google does not disclose the reviewer’s exact internal scoring, so no outside audit can prove one single cause.

**Recommendation:** do **not** immediately request another review. First complete the P0 and P1 work in this report, then allow the cleaned site to be crawled again. A realistic recovery window is 3–6 weeks, depending on implementation and recrawl speed. Approval can never be guaranteed.

## 2. What is already working

This is not a dead or completely valueless site.

- `robots.txt` returns 200 and permits normal crawling.
- Rank Math sitemap index returns 200 and exposes post, page, category and author sitemaps.
- Core pages have self-referencing canonical tags and index/follow directives.
- HTTPS works.
- `ads.txt` is present and correctly contains publisher ID `pub-2645430008723493`.
- Job pages usually provide an advertisement image, vacancies, eligibility, deadline and application steps.
- JobPosting schema exists on many sampled posts.
- Search Console export (10 Aug–10 Sep) records **1,025 clicks, 50,896 impressions and 2.01% overall CTR**.
- The strongest page, `/fia-jobs-2026/`, recorded 312 clicks and 22,608 impressions in that export.
- Mobile generated 803 of 1,025 clicks, confirming a real Pakistan-focused mobile audience.

These are assets worth preserving. The correct strategy is to improve and consolidate—not wipe the site.

## 3. Critical findings

### P0 — The publishing footprint looks scaled and search-first

The local publishing history repeatedly describes cloning one Elementor template, forcing 600–790+ words, targeting approximately 1.0–1.2% exact-keyword density, weaving a prescribed set of secondary keywords, and programmatically publishing posts. Google’s official AdSense guidance requires unique, relevant content and specifically warns against repeated keywords and “cookie cutter” pages with little original value. Google Search defines scaled content abuse as generating many pages primarily to manipulate rankings rather than help users.

The risk is not “AI content” by itself. The risk is that many pages are built to pass an SEO checklist while contributing little beyond information already available in the official advertisement.

**Required change:** retire keyword-density and minimum-word-count rules. Each article must be edited around the applicant’s task, source evidence and unique practical value.

### P0 — Expired jobs remain misleadingly active

On 21 September, the following sampled expired pages still had no detectable “closed/expired” notice and still contained “Apply Now”:

| Page | Schema `validThrough` | Closed notice | “Apply Now” |
|---|---:|---:|---:|
| PERA Jobs | 27 Aug 2026 | No | Yes |
| OGRA Jobs | 4 Sep 2026 | No | Yes |
| POF Jobs | 7 Sep 2026 | No | Yes |
| Islamabad Police recruitment | 20 Sep 2026 | No | Yes |
| Board of Revenue Punjab (new) | 20 Sep 2026 | No | Yes |
| CM Punjab Climate Internship | 18 Sep 2026 | No | Yes |

This is bad for user trust: a visitor can land from Google, see an expired deadline, yet still receive an active CTA. Correct `validThrough` alone does not fix the visible experience.

**Required change:** implement automatic expiry handling. After the deadline, replace the primary CTA with a red “Applications closed” status, retain the deadline, link to current related jobs, and ensure JobPosting markup is no longer presented as a current opportunity.

### P0 — Indexable placeholder and test pages

Both of these return 200 with `index, follow`:

- `/sample-page/` — default WordPress sample content;
- `/elementor-1046/` — “Elementor #1046,” a programmatically generated test page.

These pages directly signal an unfinished, low-quality property.

**Required change:** delete them and return 410, or 301 only where a truly equivalent destination exists. Remove them from the sitemap and all internal links.

### P0 — Privacy Policy is missing

`/privacy-policy/` returns 404 and no privacy page appears in the page sitemap. For an advertising site using Google tags/analytics, a clear privacy/cookie disclosure is a foundational trust and compliance requirement.

**Required change:** publish a site-specific Privacy Policy covering cookies, Google advertising products, analytics, data retention, contact details and user choices. Add it prominently to the footer. Do not paste a generic policy that makes claims the site cannot honor.

### P0 — Technical canonicalization is incomplete

- `https://www.careerinpak.com/` redirects to `https://careerinpak.com/` — good.
- `http://careerinpak.com/` returns 200 and remains on HTTP — bad.
- HTTPS responses sampled did not expose an HSTS header.
- Schema organization/logo URLs include some `http://careerinpak.com` values while page canonicals use HTTPS.

**Required change:** enforce a single hop 301 from every HTTP URL to the equivalent HTTPS non-www URL; then normalize all schema/logo/site URLs to HTTPS. Add HSTS only after confirming the entire domain/subdomain setup is HTTPS-safe.

### P1 — Taxonomy bloat and weak information architecture

Public API totals:

- 97 posts;
- 421 tags (about 4.34 tags per post, with many one-use tags);
- 24 categories.

Examples of weak indexable archives include:

- `/category/uncategorized/` — one post;
- `/category/chef-jobs/` — one post;
- `/tag/engineering-jobs/` — indexable archive;
- `/author/admin/` — indexable archive duplicating the main post stream.

There are also empty legacy/demo categories such as World, Tech, Games, Foods, Business, Travel and Life Style.

**Required change:** consolidate to a small, intentional hierarchy. Noindex or delete thin tags, remove empty demo categories, move the Uncategorized post, and noindex the single-author archive unless it becomes a genuinely useful author profile/hub. Do not create a taxonomy term unless it will have sustained content and a unique introduction.

### P1 — Too little durable original value

The site overwhelmingly publishes vacancy notices. The sitemap shows only a few non-vacancy posts, while users need evergreen help such as:

- how to verify an official job advertisement;
- how to use PPSC/FPSC/NTS portals, with original screenshots;
- domicile, quota, challan and document checklists;
- role-specific syllabus and test preparation based on official sources;
- application-error troubleshooting;
- scam-job identification;
- interview and document-verification checklists;
- salary/BPS explainers backed by official sources;
- a current-jobs dashboard that distinguishes open, closing soon and closed.

**Required change:** publish 12–20 genuinely researched evergreen resources before reapplying. Each must include firsthand screenshots/examples, cited official sources, a named reviewer, updated date and a clear reason to choose CareerInPak over the original advertisement alone.

### P1 — Generic authorship and weak editorial accountability

All sampled posts are attributed to “CareerInPak Editorial Team,” but the underlying author archive is `/author/admin/`, social metadata uses `@admin`, and the schema description is promotional rather than biographical. The author sitemap contains only this one identity.

**Required change:** create a credible editorial identity page with real responsible person/team information, expertise, verification workflow and contact route. Add Editorial Policy, Corrections Policy, Sources & Verification Policy, and Advertising Disclosure pages. Replace `@admin` metadata with real brand accounts or remove unsupported profiles.

### P1 — Schema values require a factual audit

Recent samples include exact monthly salary values (for example PKR 150,000) and `employmentType: CONTRACTOR`. These fields must be present only if supported by the official advertisement. Guessed/default salary or employment values are misleading and violate structured-data accuracy principles.

**Required change:** audit all JobPosting JSON-LD against visible content and the official source. Remove unsupported salary, employment type, address, vacancy count and organization details. Validate a sample with Google Rich Results Test after correction.

### P1 — Duplicate and competing job URLs

The post list includes patterns such as:

- `board-of-revenue-punjab-jobs-2026/` and `board-of-revenue-punjab-jobs-2026-new/`;
- several Islamabad Police pages;
- repeated organization posts with `-2` or `-4` suffixes;
- both LWMC long-form and short-form URLs;
- multiple closely related PPSC transport pages.

Some may represent distinct advertisements, but suffixes and overlapping intent make the editorial distinction unclear.

**Required change:** map every URL to a distinct intent. Merge true duplicates with 301 redirects. If both remain, clearly show advertisement number/date, role and deadline in the title and introduction, and link them through an organization hub.

### P2 — Homepage and navigation need stronger trust/value signals

The homepage is an Elementor page with no normal REST-rendered body content. Its live output is heavily card/list driven. The navigation and homepage should visibly answer:

1. What does CareerInPak add beyond reposting advertisements?
2. How does it verify information?
3. Which jobs are currently open?
4. Who is responsible for errors?
5. Where are the practical application guides?

Add “Verified today,” “Closing soon,” and “Applications closed” states, evergreen guide modules and an editorial-method summary.

## 4. Search Console interpretation

The Search Console file is evidence that Google can discover and rank the site; therefore crawlability is not the main rejection reason.

Key numbers (10 Aug–10 Sep 2026):

- 1,025 clicks;
- 50,896 impressions;
- 2.01% CTR;
- 154 clicks and 7,402 impressions in the last seven recorded days;
- Pakistan accounts for 996 clicks;
- mobile accounts for 803 clicks.

However, results are concentrated. FIA, FGEI, POF and a handful of vacancy pages produce much of the traffic. This supports a **preserve and improve** approach for proven pages while consolidating weak duplicates.

Traffic is not the same as AdSense quality approval. Google’s AdSense review asks whether the site adds distinctive, useful value and provides a good user experience.

## 5. Page-level decision framework

Audit all 97 posts in a spreadsheet with these columns:

`URL, title, publish date, deadline, current status, official source, source checked date, unique value, Search Console clicks, impressions, backlinks, duplicate cluster, schema accurate, action, owner, completion date`

Use these actions:

- **Keep + upgrade:** page has traffic/backlinks or a distinct advertisement, accurate facts and enough unique value.
- **Merge + 301:** multiple URLs satisfy the same intent or repeat the same advertisement.
- **Noindex temporarily:** useful to existing users but too thin/unfinished for Search; improve before reindexing.
- **410:** test pages, accidental duplicates with no value, or obsolete pages with no equivalent replacement.
- **Retain as closed archive:** page has useful historic/search value; visibly mark closed, remove active CTA and link to the current hub.

Do not mass-redirect expired pages to the homepage.

## 6. New editorial standard for job posts

Every job post must pass this gate:

1. Link to the official department/recruitment source and name the source.
2. Record “Verified by” and “Last checked” visibly.
3. Show a machine-readable and human-readable status: Open, Closing today, or Closed.
4. Extract only source-supported facts; never invent salary, benefits, permanence or selection steps.
5. Explain ambiguities in the source instead of filling gaps.
6. Add original utility: eligibility interpretation, document checklist, application pitfalls, portal screenshots or a role comparison.
7. Use concise prose. No minimum word count and no keyword-density target.
8. Avoid promotional filler such as “golden,” “amazing,” “ultimate,” “massive,” “prestigious,” and “secure your future.”
9. Use one primary H1 and descriptive H2s.
10. Provide 2–5 contextual internal links, not mass tags.
11. Include a correction/report-error link.
12. Ensure visible content and JobPosting schema match exactly.

## 7. 30-day recovery plan

### Days 1–3 — Stop further quality dilution

- Pause automated bulk publishing.
- Remove fixed word-count and exact-keyword-density rules from the workflow.
- Delete/410 Sample Page and Elementor #1046.
- Publish Privacy Policy; add footer links to Privacy, Terms, About, Contact and Editorial Policy.
- Force HTTP → HTTPS 301 and normalize schema URLs.
- Move the Uncategorized post and remove empty demo categories.
- Noindex tag archives and the thin author archive as an immediate containment step.

### Days 4–10 — Inventory and expiry cleanup

- Complete the 97-URL content inventory.
- Mark every expired job visibly closed.
- Remove or disable expired Apply buttons.
- Correct/remove expired JobPosting markup.
- Identify duplicate clusters and implement exact 301 mappings.
- Review every schema salary/employment/address value against the advertisement.
- Fix broken official links and link directly to official sources.

### Days 11–20 — Add distinctive value

- Deeply rewrite the 15–25 pages with the strongest Search Console performance.
- Publish at least 8 high-quality evergreen guides using original screenshots and official citations.
- Build curated PPSC, FPSC, NTS, Federal Jobs and Punjab Jobs hubs with unique introductory/help content—not plain archives.
- Add named editorial ownership, verification methodology and correction workflow.
- Redesign the homepage around current status, closing dates and practical guides.

### Days 21–27 — Expand and validate

- Upgrade the next 20–30 pages or noindex them until ready.
- Publish 4–8 more evergreen resources.
- Crawl the site for 200/3xx/4xx status, canonicals, titles, indexability and orphan pages.
- Validate representative JobPosting markup.
- Test mobile navigation, forms, tap targets, layout shifts and image readability.
- Confirm there are no intrusive popups or ad-like buttons near application CTAs.

### Days 28–30 — Reapplication gate

Only reapply when every mandatory item below is true.

## 8. Mandatory reapplication checklist

- [ ] No default/test/demo pages are indexable.
- [ ] Privacy, About, Contact, Terms, Editorial Policy, Corrections Policy and Advertising Disclosure are live and linked in the footer.
- [ ] HTTP redirects to HTTPS in one hop.
- [ ] All visible/schema URLs use HTTPS.
- [ ] All expired jobs show “Applications closed” and no active Apply CTA.
- [ ] JobPosting schema matches the official source and visible content.
- [ ] Thin tags, empty categories and duplicate author archives are noindexed or removed.
- [ ] Duplicate job URLs have been merged or clearly differentiated.
- [ ] At least 12 strong evergreen guides are live.
- [ ] Top traffic pages have been manually upgraded with original value.
- [ ] No content workflow uses keyword density or padding to hit a word count.
- [ ] Every post has a source, checked date, responsible editor and correction route.
- [ ] Sitemap contains only canonical, indexable, useful URLs.
- [ ] Search Console shows no manual action/security issue and important URLs have been recrawled.
- [ ] Mobile UX and Core Web Vitals have been checked.

## 9. What not to do

- Do not resubmit after only adding a Privacy Policy or changing the theme.
- Do not buy traffic, backlinks or social signals to appear established.
- Do not publish 20 more templated posts while cleanup is in progress.
- Do not rewrite filler with synonyms and call it unique.
- Do not add fabricated reviews, authors, addresses or salary data.
- Do not mass-delete proven search pages without checking Search Console.
- Do not chase a Rank Math 100/100 score as a proxy for user value.
- Do not assume a minimum number of posts, domain age or traffic guarantees approval; Google publishes no such approval threshold.

## 10. Success measures

Track weekly:

- percentage of published jobs with verified source/date/status;
- percentage of expired pages with correct closed state;
- number of indexable placeholder/thin taxonomy URLs;
- number of upgraded pages and evergreen guides;
- Search Console indexed vs submitted URLs;
- clicks/impressions to evergreen guides;
- returning users and engaged sessions;
- broken official-link count;
- valid JobPosting items and schema errors;
- mobile Core Web Vitals.

Target before resubmission:

- 100% status accuracy on job pages;
- 100% schema/source alignment in the audited set;
- zero indexable test pages;
- zero HTTP 200 duplicates;
- zero empty indexable categories;
- at least 12–20 substantial evergreen resources;
- top 25 traffic pages manually reviewed and enhanced.

## 11. Official references

- Google AdSense, “Make sure your site's pages are ready for AdSense”: https://support.google.com/adsense/answer/7299563
- Google AdSense policies beginner’s guide: https://support.google.com/adsense/answer/23921
- Google Publisher Policies overview: https://support.google.com/adsense/answer/10008391
- Google Search spam policies (including scaled content abuse): https://developers.google.com/search/docs/essentials/spam-policies
- Google guidance on generative AI content: https://developers.google.com/search/docs/fundamentals/using-gen-ai-content

## Final assessment

CareerInPak has enough early search traction to recover, but the site must transition from a **programmatic vacancy-posting operation** into a **verified career-help publication**. The highest-impact work is not adding more words or SEO fields. It is reducing low-value indexable inventory, making job status truthful, proving editorial responsibility, and adding original guidance users cannot obtain by merely opening the official advertisement.
