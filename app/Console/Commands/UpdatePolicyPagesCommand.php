<?php

namespace App\Console\Commands;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;
use Illuminate\Console\Command;

class UpdatePolicyPagesCommand extends Command
{
    protected $signature = 'cms:update-policy-pages';

    protected $description = 'Upgrade all legal and policy pages (Privacy, About, Disclaimer, Terms, Cookies, Editorial) to gold-standard E-E-A-T and AdSense compliance';

    public function handle(): int
    {
        $this->info("==================================================");
        $this->info("🛡️ CareerInPak — Upgrading Policy & Trust Pages");
        $this->info("==================================================");

        $this->updatePrivacyPolicy();
        $this->updateAboutUs();
        $this->updateDisclaimer();
        $this->updateTermsConditions();
        $this->updateCookiePolicy();
        $this->updateEditorialPolicy();
        $this->updateContactUs();

        $this->info("\n🧹 Clearing application caches...");
        $this->callSilent('optimize:clear');
        $this->info("✔ Cache cleared!");

        $this->info("\n🎉 All policy pages have been successfully upgraded to Gold-Standard AdSense compliance!");
        return self::SUCCESS;
    }

    protected function savePage(string $name, string $slugKey, string $content, string $description): Page
    {
        $page = Page::query()->updateOrCreate(
            ['name' => $name],
            [
                'content' => $content,
                'description' => $description,
                'status' => BaseStatusEnum::PUBLISHED,
                'template' => 'default',
                'user_id' => 1,
            ]
        );

        Slug::query()->firstOrCreate(
            [
                'key' => $slugKey,
                'reference_type' => Page::class,
            ],
            [
                'reference_id' => $page->id,
                'prefix' => '',
            ]
        );

        $this->line("  ✔ Upgraded: <comment>{$name}</comment> (/{$slugKey})");
        return $page;
    }

    /**
     * 1. PRIVACY POLICY
     */
    protected function updatePrivacyPolicy(): void
    {
        $content = <<<'HTML'
<div class="policy-container" style="color: #111111 !important; line-height: 1.8; font-size: 16px;">

    <div class="p-3 mb-4 rounded" style="background-color: #eef7ff; border-left: 5px solid #007bff;">
        <p class="mb-0"><strong>Effective Date:</strong> October 10, 2026 | <strong>Last Updated:</strong> October 10, 2026</p>
        <p class="mb-0 small text-muted">This Privacy Policy applies to CareerInPak.com and governs all data collection, usage, and privacy practices in compliance with Google AdSense Policies, the General Data Protection Regulation (GDPR), and the California Consumer Privacy Act (CCPA).</p>
    </div>

    <h2>1. Introduction & Overview</h2>
    <p>At <strong>CareerInPak</strong> (accessible from <a href="https://careerinpak.com" style="color: #007bff; text-decoration: underline;">https://careerinpak.com</a>), the privacy and data security of our visitors across Pakistan and internationally is of paramount importance. This document clearly articulates what information is collected, how it is processed, and how your privacy rights are safeguarded when accessing our career, government recruitment, and scholarship resources.</p>

    <h2>2. Information We Collect</h2>
    <p>We believe in data minimization. We only collect information necessary to provide educational and career updates:</p>
    <ul>
        <li><strong>Log Files & Telemetry:</strong> Like virtually all web servers, CareerInPak automatically logs standard visitor information. This includes Internet Protocol (IP) addresses, browser type, Internet Service Provider (ISP), date/time stamps, referring/exit pages, platform type, and click counts. This data is not linked to personally identifiable information and is utilized exclusively for traffic analysis, server security, and website administration.</li>
        <li><strong>Voluntarily Provided Information:</strong> When you contact our editorial team via email (<code>info@careerinpak.com</code>) or submit an inquiry through our contact form, we collect your name, email address, and message contents solely to answer your questions.</li>
        <li><strong>Notification Subscriptions:</strong> If you opt-in to our web push notifications or WhatsApp channel, your device token or contact is managed securely per your explicit consent and can be revoked at any time.</li>
    </ul>

    <h2>3. Cookies and Web Beacons</h2>
    <p>CareerInPak uses standard HTTP cookies to store visitor preferences, record user-specific information on which pages the visitor accesses, and customize content based on browser types. You have the absolute right to manage, disable, or delete cookies via your personal browser settings (Chrome, Safari, Firefox, Edge).</p>

    <h2>4. Google AdSense & Third-Party Advertising (DART Cookies)</h2>
    <p>We partner with Google AdSense and authorized third-party ad networks to display advertisements when you visit our website:</p>
    <ul>
        <li><strong>Google DoubleClick DART Cookies:</strong> Google is a third-party vendor on our site. It uses cookies, known as DART cookies, to serve ads to our site visitors based upon their visit to CareerInPak.com and other websites across the Internet.</li>
        <li><strong>Opt-Out of Personalized Ads:</strong> Visitors may choose to decline the use of DART cookies by visiting the Google Ad and Content Network Privacy Policy at: <a href="https://policies.google.com/technologies/ads" target="_blank" rel="noopener noreferrer" style="color: #007bff; text-decoration: underline;">https://policies.google.com/technologies/ads</a>.</li>
        <li><strong>Third-Party Ad Networks:</strong> These third-party ad servers or ad networks use technology in their respective advertisements and links that appear on CareerInPak, which are sent directly to your browser. They automatically receive your IP address when this occurs. CareerInPak has no access to or control over these cookies that are used by third-party advertisers.</li>
    </ul>

    <h2>5. California Consumer Privacy Act (CCPA) Rights</h2>
    <p>Under the CCPA, California consumers possess specific privacy rights:</p>
    <ul>
        <li>The right to request disclosure of categories and specific pieces of personal data collected.</li>
        <li>The right to request deletion of any personal data collected.</li>
        <li><strong>We Do Not Sell Personal Information:</strong> CareerInPak does not sell, rent, or trade your personal information to third parties under any circumstances.</li>
    </ul>

    <h2>6. General Data Protection Regulation (GDPR) Rights</h2>
    <p>Every European Economic Area (EEA) user is entitled to the following rights:</p>
    <ul>
        <li><strong>Right to access:</strong> You have the right to request copies of your personal data.</li>
        <li><strong>Right to rectification:</strong> You have the right to request correction of inaccurate or incomplete information.</li>
        <li><strong>Right to erasure:</strong> You have the right to request that we erase your personal data under certain conditions.</li>
        <li><strong>Right to restrict or object to processing:</strong> You have the right to object to our processing of your personal data.</li>
    </ul>

    <h2>7. Children's Online Privacy Protection (COPPA)</h2>
    <p>Protecting children's privacy is vital. CareerInPak does not knowingly collect any Personal Identifiable Information from children under the age of 13. If you believe your child provided this information on our website, please contact us immediately at <code>info@careerinpak.com</code> and we will promptly remove such records.</p>

    <h2>8. External Links to Government & Departmental Websites</h2>
    <p>CareerInPak publishes career notices that link directly to official government portals (such as FPSC, PPSC, NTS, Federal Ministries). Once you click an outbound link to an external website, you are subject to the privacy policy of that third-party entity. We encourage users to read the privacy statements of every external website they visit.</p>

    <h2>9. Data Security Measures</h2>
    <p>We deploy standard SSL (Secure Sockets Layer) encryption, firewalls, and regular security patching to prevent unauthorized access, alteration, or disclosure of data.</p>

    <h2>10. Contact Us Regarding Your Privacy</h2>
    <p>If you have additional questions or require more information about our Privacy Policy, do not hesitate to contact our Data Protection Officer:</p>
    <ul>
        <li><strong>Email:</strong> <a href="mailto:info@careerinpak.com" style="color: #007bff; font-weight: bold;">info@careerinpak.com</a></li>
        <li><strong>Postal Address:</strong> CareerInPak Editorial Office, Sector F-7, Blue Area, Islamabad, Pakistan.</li>
    </ul>

</div>
HTML;

        $this->savePage(
            'Privacy Policy',
            'privacy-policy',
            $content,
            'Privacy Policy for CareerInPak. Learn how we collect, protect, and process user data in compliance with Google AdSense, GDPR, and CCPA standards.'
        );
    }

    /**
     * 2. ABOUT US
     */
    protected function updateAboutUs(): void
    {
        $content = <<<'HTML'
<div class="about-container" style="color: #111111 !important; line-height: 1.8; font-size: 16px;">

    <div class="p-4 mb-4 rounded shadow-sm" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 100%); border-left: 5px solid #007bff;">
        <h3 style="color: #1a365d; margin-top: 0;">Empowering Pakistani Youth with Authentic Career Information</h3>
        <p class="mb-0"><strong>CareerInPak.com</strong> is Pakistan's premier independent career news and employment aggregation platform. Founded with the vision of bridging the information gap between job seekers and public/private opportunities, we transform complicated official recruitment gazettes into clear, structured, and actionable guidance.</p>
    </div>

    <h2>1. Our Mission & Vision</h2>
    <p>Millions of educated Pakistani students, graduates, and professionals miss career deadlines every year simply because official newspaper advertisements are hard to read, scattered across multiple regional gazettes, or burdened with bureaucratic jargon. Our mission is to:</p>
    <ul>
        <li><strong>Democratize Career Opportunities:</strong> Ensure that whether an applicant lives in Karachi, Lahore, Quetta, Peshawar, Gilgit, or a rural district, they have equal, free access to authentic job notices.</li>
        <li><strong>Eradicate Employment Fraud:</strong> Protect job seekers from fraudulent recruitment scams by providing verified official links, verified government bank challans, and warnings against unofficial fee requests.</li>
        <li><strong>Provide Comprehensive Guidance:</strong> Accompany every listing with eligibility criteria, provincial quota details, age relaxation rules, and step-by-step application procedures in both English and Urdu.</li>
    </ul>

    <h2>2. Our Rigorous 4-Step Verification Protocol</h2>
    <p>Every single job, scholarship, and testing announcement published on CareerInPak undergoes a strict editorial verification workflow before publication:</p>
    <ol>
        <li><strong>Primary Source Sourcing:</strong> Announcements are sourced directly from official daily newspapers (Jang, Express, Dawn, The News, Nawaiwaqt) and official government gazettes.</li>
        <li><strong>Direct Portal Cross-Checking:</strong> Our research desk verifies the announcement on the hiring department's official website (e.g. FPSC, PPSC, SPSC, BPSC, KPPSC, Federal Ministries, or Army selection portals).</li>
        <li><strong>Editorial Data Structuring:</strong> We convert raw ad images into clean 11-section structured formats detailing exact salary scales (BPS), required documents, quota distributions, and interview schedules.</li>
        <li><strong>Continuous Expiry Tracking:</strong> Our automated deadline engine monitors validity dates daily, instantly marking expired opportunities to save applicants time and application fees.</li>
    </ol>

    <h2>3. What Sets Us Apart</h2>
    <div class="row mt-3 mb-4">
        <div class="col-md-4 mb-3">
            <div class="p-3 border rounded bg-light h-100">
                <h5 style="color: #007bff;">✔ 100% Free Information</h5>
                <p class="small mb-0">We never charge candidates for job alerts, guidance, or portal access. All information is completely free.</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="p-3 border rounded bg-light h-100">
                <h5 style="color: #007bff;">✔ Zero Clickbait Policy</h5>
                <p class="small mb-0">Our titles state exact vacancies and departments. We strictly forbid misleading titles or sensational marketing hype.</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="p-3 border rounded bg-light h-100">
                <h5 style="color: #007bff;">✔ Bilingual Accessibility</h5>
                <p class="small mb-0">Clear international English combined with native Nastaliq Urdu RTL instructions for ground-level Pakistani applicants.</p>
            </div>
        </div>
    </div>

    <h2>4. Editorial Leadership & Team</h2>
    <p>Our editorial and research team comprises experienced career counselors, higher education researchers, and digital publishing professionals based in Islamabad, Lahore, and Karachi:</p>
    <ul>
        <li><strong>Chief Editor:</strong> Leads daily verification of Federal and Armed Forces recruitment notifications.</li>
        <li><strong>Provincial Recruitment Desks:</strong> Dedicated editors tracking Punjab (PPSC), Sindh (SPSC), Khyber Pakhtunkhwa (KPPSC), and Balochistan (BPSC) civil service opportunities.</li>
        <li><strong>Scholarships Research Desk:</strong> Dedicated specialists tracking HEC national scholarships and international funding programs (CSC, Chevening, Fulbright, Turkiye Burslari).</li>
    </ul>

    <h2>5. Institutional Independence</h2>
    <p><strong>CareerInPak.com is an independent private publishing portal.</strong> We are not a government department, recruitment agency, or testing service. We do not conduct interviews, collect test fees, or issue roll number slips. We provide authentic informational signposts directing applicants to the official hiring bodies.</p>

    <h2>6. Get in Touch</h2>
    <p>We welcome feedback, official press releases, and editorial corrections from departments and readers:</p>
    <ul>
        <li><strong>Official Email:</strong> <a href="mailto:info@careerinpak.com" style="color: #007bff; font-weight: bold;">info@careerinpak.com</a></li>
        <li><strong>News Desk & Corrections:</strong> <a href="mailto:editorial@careerinpak.com" style="color: #007bff;">editorial@careerinpak.com</a></li>
        <li><strong>Headquarters:</strong> CareerInPak Editorial Media House, Sector F-7, Blue Area, Islamabad, 44000, Pakistan.</li>
    </ul>

</div>
HTML;

        $this->savePage(
            'About Us',
            'about-us',
            $content,
            'About CareerInPak — Learn about our mission, 4-step editorial verification protocol, leadership team, and commitment to authentic career reporting in Pakistan.'
        );
    }

    /**
     * 3. DISCLAIMER
     */
    protected function updateDisclaimer(): void
    {
        $content = <<<'HTML'
<div class="disclaimer-container" style="color: #111111 !important; line-height: 1.8; font-size: 16px;">

    <div class="p-3 mb-4 rounded alert alert-warning" style="border-left: 5px solid #ffc107; background-color: #fff9e6;">
        <h4 class="mb-1" style="color: #856404;">⚠️ Mandatory Legal Notice & Government Disassociation</h4>
        <p class="mb-0 small">Please read this disclaimer carefully before utilizing any information, links, or resources published on CareerInPak.com.</p>
    </div>

    <h2>1. Independent Educational & Informational Portal</h2>
    <p><strong>CareerInPak.com is an independent informational and career news aggregation portal.</strong> We are <strong>NOT</strong> affiliated, associated, authorized, endorsed by, or in any way officially connected with the Government of Pakistan, any provincial government (Punjab, Sindh, Khyber Pakhtunkhwa, Balochistan, Gilgit-Baltistan, AJK), or any federal ministry, department, or state enterprise.</p>

    <h2>2. No Testing Agency or Hiring Authority Representation</h2>
    <p>CareerInPak is not a recruitment agency, testing service, or government hiring board. Specifically:</p>
    <ul>
        <li>We do <strong>NOT</strong> issue roll number slips, test date schedules, interview call letters, or final selection notifications.</li>
        <li>We do <strong>NOT</strong> accept job applications on behalf of any government or private organization.</li>
        <li>We do <strong>NOT</strong> have any influence over candidate screening, merit list preparation, exam checking, or appointment decisions.</li>
    </ul>

    <h2>3. Strict No-Fee Collection Policy</h2>
    <p><strong>CareerInPak NEVER requests money, processing fees, or bank transfers from job seekers.</strong></p>
    <p>Any application fees, testing charges, or examination challans mentioned in our job posts are official government dues payable directly through designated public banks (such as National Bank of Pakistan, State Bank, or official 1Link channels) into official government treasury heads. If any individual claiming to represent CareerInPak demands payment for employment, please report them immediately to official cybercrime authorities (FIA) and inform us at <code>info@careerinpak.com</code>.</p>

    <h2>4. Primacy of the Official Advertisement</h2>
    <p>While our editorial team exercises rigorous care to extract and summarize recruitment advertisements accurately, official organizations reserve the right to alter eligibility criteria, vacancy counts, test centers, and deadlines without prior notice. <strong>In any case of discrepancy between CareerInPak.com and the official departmental gazette, the official gazette/advertisement shall always be treated as final and legally binding.</strong></p>
    <p>Candidates are strongly advised to inspect the original advertisement clipping and visit the hiring department's official website prior to submitting applications or paying examination fees.</p>

    <h2>5. Third-Party Outbound Links</h2>
    <p>Our posts contain hyperlinks pointing directly to official departmental portals (e.g. <code>fpsc.gov.pk</code>, <code>ppsc.gop.pk</code>, <code>nts.org.pk</code>). These external sites are owned and operated by independent authorities. CareerInPak has no control over the content, uptime, security, or privacy policies of third-party websites and accepts no responsibility for external content.</p>

    <h2>6. Limitation of Liability</h2>
    <p>Under no circumstances shall CareerInPak.com, its editors, or affiliates be liable for any direct, indirect, incidental, consequential, or punitive damages arising out of your access to, use of, or inability to use this website, or any errors or omissions in the content presented.</p>

    <h2>7. Reporting Errors & Corrections</h2>
    <p>We are dedicated to accuracy. If you identify an error, expired deadline, or broken link, please notify our editorial desk with the post URL and official source reference:</p>
    <ul>
        <li><strong>Email:</strong> <a href="mailto:info@careerinpak.com" style="color: #007bff; font-weight: bold;">info@careerinpak.com</a></li>
    </ul>

</div>
HTML;

        $this->savePage(
            'Disclaimer',
            'disclaimer',
            $content,
            'Official Legal Disclaimer for CareerInPak.com — Independent informational aggregator status, government disassociation, and no-fee policy.'
        );
    }

    /**
     * 4. TERMS & CONDITIONS
     */
    protected function updateTermsConditions(): void
    {
        $content = <<<'HTML'
<div class="terms-container" style="color: #111111 !important; line-height: 1.8; font-size: 16px;">

    <div class="p-3 mb-4 rounded" style="background-color: #f8f9fa; border-left: 5px solid #6c757d;">
        <p class="mb-0"><strong>Last Revised:</strong> October 10, 2026</p>
        <p class="mb-0 small text-muted">Please read these Terms & Conditions carefully before using CareerInPak.com. By accessing or using this website, you agree to be bound by these terms in full.</p>
    </div>

    <h2>1. Acceptance of Terms</h2>
    <p>By accessing and utilizing <strong>CareerInPak.com</strong> ("the Website"), you acknowledge that you have read, understood, and agreed to adhere to these Terms & Conditions, together with our Privacy Policy and Legal Disclaimer. If you do not agree with any part of these terms, you must immediately discontinue use of this site.</p>

    <h2>2. Intellectual Property Rights & Fair Use</h2>
    <p>All original content published on CareerInPak—including structured summaries, editorial career guides, website layout, graphics, custom watermarks, and compilation databases—is the intellectual property of CareerInPak.com and protected by applicable copyright laws.</p>
    <ul>
        <li><strong>Permitted Use:</strong> You are granted a limited license to access, view, and print pages for your personal, non-commercial educational use.</li>
        <li><strong>Prohibited Use:</strong> You may not scrape, republish, reproduce, duplicate, or redistribute content from CareerInPak for commercial gain without prior written consent and clear do-follow attribution.</li>
        <li><strong>Official Public Notices:</strong> Newspaper advertisements, departmental logos, and government notices remain the property of their respective departments and are reproduced under educational fair use.</li>
    </ul>

    <h2>3. User Conduct & Acceptable Use</h2>
    <p>When accessing CareerInPak, you agree not to:</p>
    <ul>
        <li>Use automated scrapers, bots, or data extraction scripts that overburden server infrastructure.</li>
        <li>Attempt to breach website security, bypass authentication, or introduce viruses or malicious code.</li>
        <li>Post abusive, defamatory, or unlawful comments on interactive sections.</li>
        <li>Misrepresent yourself as an official representative of CareerInPak or any government department.</li>
    </ul>

    <h2>4. Accuracy of Information & External Content</h2>
    <p>CareerInPak strives to ensure that all information regarding vacancies, educational requirements, and deadlines is authentic. However, employment notices are subject to revisions by hiring organizations. We make no warranties, express or implied, regarding the continuous completeness or accuracy of third-party data.</p>

    <h2>5. Termination of Access</h2>
    <p>We reserve the right to restrict or terminate access to any user who violates these terms, engages in abusive behavior, or disrupts website operations, without prior notice.</p>

    <h2>6. Governing Law & Jurisdiction</h2>
    <p>These Terms & Conditions are governed by and construed in accordance with the laws of the Islamic Republic of Pakistan. Any legal disputes arising in relation to this website shall be subject to the exclusive jurisdiction of the competent courts in Islamabad, Pakistan.</p>

    <h2>7. Modifications to Terms</h2>
    <p>We reserve the right to modify these terms at any time. Continued use of the website following any revisions constitutes your acceptance of the updated terms.</p>

    <h2>8. Contact Information</h2>
    <p>For inquiries regarding these Terms & Conditions, please contact us at:</p>
    <p><strong>Email:</strong> <a href="mailto:info@careerinpak.com" style="color: #007bff;">info@careerinpak.com</a></p>

</div>
HTML;

        $this->savePage(
            'Terms & Conditions',
            'terms-conditions',
            $content,
            'Terms and Conditions of CareerInPak.com — Legal agreement governing acceptable use, intellectual property, and user responsibilities.'
        );
    }

    /**
     * 5. COOKIE POLICY
     */
    protected function updateCookiePolicy(): void
    {
        $content = <<<'HTML'
<div class="cookie-container" style="color: #111111 !important; line-height: 1.8; font-size: 16px;">

    <div class="p-3 mb-4 rounded" style="background-color: #f0fdf4; border-left: 5px solid #28a745;">
        <p class="mb-0"><strong>Cookie Compliance Notice:</strong> CareerInPak.com respects your privacy and provides full transparency regarding our cookie usage in compliance with EU GDPR and global privacy standards.</p>
    </div>

    <h2>1. What Are Cookies?</h2>
    <p>Cookies are small text files stored on your computer or mobile device when you browse websites. They are widely used to make websites work efficiently, remember your preferences, and provide analytical data to website operators.</p>

    <h2>2. Categories of Cookies We Use</h2>
    <table class="table table-bordered mt-3 mb-4" style="color: #111111;">
        <thead style="background-color: #5869DA; color: #ffffff;">
            <tr>
                <th>Cookie Category</th>
                <th>Purpose</th>
                <th>Can Be Disabled?</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Strictly Necessary</strong></td>
                <td>Essential for site navigation, security tokens (CSRF protection), and session management.</td>
                <td>No (Essential)</td>
            </tr>
            <tr>
                <td><strong>Performance & Analytics</strong></td>
                <td>Collects anonymous metrics on page visits, traffic sources, and popular job listings to help us optimize content.</td>
                <td>Yes</td>
            </tr>
            <tr>
                <td><strong>Advertising & Targeting</strong></td>
                <td>Used by Google AdSense and advertising partners to serve relevant advertisements and cap ad frequency.</td>
                <td>Yes</td>
            </tr>
            <tr>
                <td><strong>Functional Preferences</strong></td>
                <td>Remembers your visual preferences (such as dark/light mode and cookie consent acceptance).</td>
                <td>Yes</td>
            </tr>
        </tbody>
    </table>

    <h2>3. Third-Party Advertising Cookies (Google AdSense)</h2>
    <p>Third-party advertising partners, including Google, place cookies on your browser to deliver personalized advertising based on your prior browsing history. Google's use of advertising cookies enables it and its partners to serve ads based on your visit to our sites and/or other sites on the Internet.</p>
    <p>You can opt out of personalized advertising by visiting: <a href="https://www.google.com/settings/ads" target="_blank" rel="noopener noreferrer" style="color: #007bff; text-decoration: underline;">Google Ads Settings</a>.</p>

    <h2>4. How to Manage and Disable Cookies in Your Browser</h2>
    <p>You can adjust your browser settings to accept, reject, or delete cookies at any time. Instructions for common browsers:</p>
    <ul>
        <li><strong>Google Chrome:</strong> Settings → Privacy and Security → Cookies and other site data.</li>
        <li><strong>Mozilla Firefox:</strong> Options → Privacy & Security → Enhanced Tracking Protection.</li>
        <li><strong>Apple Safari:</strong> Preferences → Privacy → Block all cookies.</li>
        <li><strong>Microsoft Edge:</strong> Settings → Cookies and site permissions.</li>
    </ul>

    <h2>5. Updates to This Cookie Policy</h2>
    <p>We may update this policy periodically to reflect changes in legal requirements or technological implementations. Revisit this page regularly to stay informed.</p>

    <h2>6. Contact Us</h2>
    <p>For questions concerning our cookie usage, contact: <code>info@careerinpak.com</code>.</p>

</div>
HTML;

        $this->savePage(
            'Cookie Policy',
            'cookie-policy',
            $content,
            'Cookie Policy for CareerInPak.com — Full disclosure of essential, analytical, and Google AdSense advertising cookies with opt-out instructions.'
        );
    }

    /**
     * 6. EDITORIAL & VERIFICATION POLICY
     */
    protected function updateEditorialPolicy(): void
    {
        $content = <<<'HTML'
<div class="editorial-container" style="color: #111111 !important; line-height: 1.8; font-size: 16px;">

    <div class="p-3 mb-4 rounded" style="background-color: #f8f9fa; border-left: 5px solid #5869DA;">
        <h4 style="color: #2d3d8b; margin-top: 0;">Our Editorial Charter & Fact-Checking Standard</h4>
        <p class="mb-0 small text-muted">CareerInPak is committed to journalistic integrity, accuracy, and transparency in employment reporting across Pakistan.</p>
    </div>

    <h2>1. Editorial Independence</h2>
    <p>Our editorial decisions are entirely independent of commercial interests. We publish employment notifications based strictly on public relevance, verified validity, and utility to Pakistani job seekers. No advertiser or private agency exerts influence over our editorial reporting.</p>

    <h2>2. Fact-Checking & Primary Sourcing</h2>
    <p>Every post must link to an authenticated primary source. Our editors cross-verify the following details against official gazettes before approval:</p>
    <ul>
        <li>Department Name and Official Notification / Tender Reference number.</li>
        <li>Official pay scales (Basic Pay Scale BPS-01 to BPS-22 or private market packages).</li>
        <li>Quota allocations (Provincial, Women, Minorities, and Special Persons).</li>
        <li>Application deadline and designated bank challan verification.</li>
    </ul>

    <h2>3. Correction Policy</h2>
    <p>When an error occurs, we correct it transparently and promptly. If a government department extends a deadline, amends eligibility, or cancels a post, our editors update the listing immediately and append an editorial revision notice.</p>
    <p>To submit a correction, email: <a href="mailto:info@careerinpak.com" style="color: #007bff; font-weight: bold;">info@careerinpak.com</a> with the subject <em>"Editorial Correction Request"</em>.</p>

</div>
HTML;

        $this->savePage(
            'Editorial and Verification Policy',
            'editorial-and-verification-policy',
            $content,
            'Editorial and Verification Policy of CareerInPak.com — Our commitment to fact-checking, primary sourcing, and journalistic transparency.'
        );
    }

    /**
     * 7. CONTACT US
     */
    protected function updateContactUs(): void
    {
        $content = <<<'HTML'
<div class="contact-container" style="color: #111111 !important; line-height: 1.8; font-size: 16px;">

    <p class="lead">We are here to assist you! Whether you need guidance on official application procedures, have spotted an error requiring correction, or want to explore institutional partnerships, our team is ready to help.</p>

    <div class="row my-4">
        <div class="col-md-6 mb-3">
            <div class="p-4 border rounded shadow-sm bg-white h-100">
                <h4 style="color: #007bff;">📍 Editorial Office</h4>
                <p class="mb-2"><strong>CareerInPak Media House</strong></p>
                <p class="mb-2 text-muted">Sector F-7, Blue Area, Islamabad, 44000, Pakistan</p>
                <p class="mb-2"><strong>Email:</strong> <a href="mailto:info@careerinpak.com" style="color: #007bff; font-weight: bold;">info@careerinpak.com</a></p>
                <p class="mb-0"><strong>WhatsApp Channel:</strong> <a href="https://whatsapp.com/channel/0029Vb1vBU95a249w33Gha16" target="_blank" rel="noopener noreferrer" style="color: #28a745; font-weight: bold;">Join Official Channel</a></p>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="p-4 border rounded shadow-sm bg-white h-100">
                <h4 style="color: #007bff;">🕒 Operating Hours & Support</h4>
                <p class="mb-2"><strong>Monday – Friday:</strong> 9:00 AM – 6:00 PM (PKT)</p>
                <p class="mb-2"><strong>Saturday:</strong> 10:00 AM – 2:00 PM (PKT)</p>
                <p class="mb-2"><strong>Sunday:</strong> Closed (Editorial Updates Only)</p>
                <p class="mb-0 text-muted small">We strive to respond to all editorial and user queries within 24 to 48 business hours.</p>
            </div>
        </div>
    </div>

    <h2>Institutional Partnerships & Press Releases</h2>
    <p>Government departments, universities, and corporate HR departments wishing to publish official recruitment circulars or scholarship notifications can submit press releases directly to: <code>editorial@careerinpak.com</code>.</p>

    <div class="p-3 my-4 rounded alert alert-info">
        <strong>Important Advisory for Job Seekers:</strong> CareerInPak is an informational portal. We do not accept physical CVs at our office, nor do we issue roll number slips or test dates. Please submit applications directly through the official portals specified in each job announcement.
    </div>

</div>
HTML;

        $this->savePage(
            'Contact Us',
            'contact-us',
            $content,
            'Contact CareerInPak — Reach our editorial team, report errors, or submit official announcements. Editorial office in Islamabad, Pakistan.'
        );
    }
}
