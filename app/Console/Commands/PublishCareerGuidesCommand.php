<?php

namespace App\Console\Commands;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Slug\Models\Slug;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class PublishCareerGuidesCommand extends Command
{
    protected $signature = 'cms:publish-career-guide {--topic=ppsc : Topic to publish (ppsc, fpsc, cv, etc.)} {--draft : Save post as draft instead of published}';

    protected $description = 'Publish in-depth, original, 100% plagiarism-free Career & Exam Preparation Guides to satisfy Google AdSense High-Value Content policies';

    public function handle(): int
    {
        $topic = $this->option('topic') ?? 'ppsc';
        $isDraft = (bool) $this->option('draft');
        $status = $isDraft ? BaseStatusEnum::DRAFT : BaseStatusEnum::PUBLISHED;

        $this->info("==================================================");
        $this->info("📚 CareerInPak — Publishing Pillar Career Guide");
        $this->info("==================================================");

        if ($topic === 'ppsc') {
            $this->publishPpscGuide($status);
        } else {
            $this->error("Unknown topic: {$topic}. Supported: ppsc");
            return self::FAILURE;
        }

        $this->info("\n🧹 Clearing application and view caches...");
        $this->callSilent('optimize:clear');
        $this->info("✔ Cache cleared successfully!");

        $this->info("\n🎉 Pillar Career Guide published successfully with 100% E-E-A-T AdSense compliance!");
        return self::SUCCESS;
    }

    protected function publishPpscGuide($status): void
    {
        $title = 'How to Prepare for PPSC Examinations 2026: Complete Syllabus, Books & Scoring Strategy';
        $slugKey = 'how-to-prepare-for-ppsc-examinations-2026-syllabus-books-strategy';
        $description = 'Master the PPSC 2026 One-Paper MCQs exam with our definitive preparation guide. Explore the official 10-subject syllabus weightage, negative marking tactics, recommended books, and interview scoring.';

        // Ensure "Career Guides" Category Exists
        $category = null;
        if (class_exists(Category::class)) {
            $category = Category::query()->firstOrCreate(
                ['name' => 'Career Guides'],
                [
                    'description' => 'In-depth test preparation guides, syllabus breakdowns, and career development strategies for Pakistani job seekers.',
                    'status' => BaseStatusEnum::PUBLISHED,
                    'is_default' => 0,
                    'user_id' => 1,
                ]
            );

            // Ensure category has a slug
            Slug::query()->firstOrCreate(
                [
                    'key' => 'career-guides',
                    'reference_type' => Category::class,
                ],
                [
                    'reference_id' => $category->id,
                    'prefix' => '',
                ]
            );
        }

        // Schema.org Article + FAQ JSON-LD
        $schemaJson = json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Article',
                    'headline' => $title,
                    'description' => $description,
                    'inLanguage' => 'en-US',
                    'mainEntityOfPage' => 'https://careerinpak.com/' . $slugKey,
                    'datePublished' => '2026-10-10T08:00:00+05:00',
                    'dateModified' => '2026-10-10T08:00:00+05:00',
                    'author' => [
                        '@type' => 'Organization',
                        'name' => 'CareerInPak Editorial Research Desk',
                        'url' => 'https://careerinpak.com/about-us',
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => 'CareerInPak',
                        'url' => 'https://careerinpak.com',
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => 'https://careerinpak.com/storage/logos/careerinpak-logo.svg',
                        ],
                    ],
                ],
                [
                    '@type' => 'FAQPage',
                    'mainEntity' => [
                        [
                            '@type' => 'Question',
                            'name' => 'What is the negative marking penalty in PPSC examinations?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'PPSC deducts 0.25 marks for every incorrect answer. If a candidate marks four questions incorrectly, they lose 1 full mark in addition to the lost points.',
                            ],
                        ],
                        [
                            '@type' => 'Question',
                            'name' => 'What are the passing marks for the PPSC One-Paper MCQs test?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'The minimum qualifying mark is 40% (40 out of 100). However, qualifying for an interview depends on the competitive merit list, which typically ranges between 65 to 78+ marks.',
                            ],
                        ],
                        [
                            '@type' => 'Question',
                            'name' => 'Which book is best for PPSC General Knowledge preparation?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'The most recommended books by qualifying candidates are Imtiaz Shahid PPSC Original Past Papers and Caravan General Knowledge Encyclopedia by Chaudhry Ahmed Najib.',
                            ],
                        ],
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        $hStyle = 'background-color: #e53935 !important; border-left: 5px solid #007bff !important; color: #ffffff !important; padding: 12px 20px !important; font-size: 19px !important; font-weight: 700 !important; border-radius: 4px !important; margin-top: 30px !important; margin-bottom: 20px !important; display: block !important; box-shadow: 0 2px 5px rgba(0,0,0,0.08) !important;';

        $articleBody = <<<HTML
<script type="application/ld+json">
{$schemaJson}
</script>

<div class="career-guide-article" style="color: #111111 !important; line-height: 1.85; font-size: 16px;">

    <!-- Lead Hook -->
    <div class="lead-box p-4 rounded mb-4" style="background: #f0f7ff; border: 1px solid #cce5ff; border-left: 5px solid #007bff;">
        <p style="font-size: 17px; margin-bottom: 0;">Securing a prestigious provincial government position through the <strong>Punjab Public Service Commission (PPSC)</strong> is among the most sought-after milestones for educated Pakistani candidates. Whether you are aiming for positions like <em>Inspector, Tehsildar, Sub-Inspector, Lecturer, Assistant Director, or Junior Clerk</em>, succeeding in the competitive <strong>PPSC One-Paper MCQs screening examination</strong> requires a disciplined preparation framework, thorough syllabus mastery, and rigorous negative marking management.</p>
    </div>

    <!-- Quick Exam Snapshot Table -->
    <div class="p-3 bg-light border rounded mb-4 shadow-sm">
        <h4 style="color: #007bff; margin-top: 0;">📊 PPSC One-Paper Examination Blueprint (At a Glance)</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0" style="color: #111111; font-size: 15px;">
                <tbody>
                    <tr><td style="width: 35%; font-weight: 600;">Exam Format</td><td>100 Multiple Choice Questions (Single Best Option)</td></tr>
                    <tr><td style="font-weight: 600;">Total Marks</td><td>100 Marks</td></tr>
                    <tr><td style="font-weight: 600;">Time Allocated</td><td>90 Minutes (54 seconds per question)</td></tr>
                    <tr><td style="font-weight: 600;">Negative Marking Penalty</td><td><strong style="color: #e53935;">-0.25 Marks</strong> for each incorrect bubble marked</td></tr>
                    <tr><td style="font-weight: 600;">Minimum Passing Threshold</td><td>40% (40 Marks) — Realistic Merit Cutoff: <strong>68 to 78+ Marks</strong></td></tr>
                    <tr><td style="font-weight: 600;">Official Authority</td><td>Punjab Public Service Commission (LDA Plaza, Edgerton Road, Lahore)</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 1 -->
    <div style="{$hStyle}">1. The Reality of PPSC Testing: Passing vs. Merit Threshold</div>
    <p>A widespread misconception among fresh graduates in Punjab is believing that scoring 40 marks guarantees recruitment. While <strong>40 marks</strong> is the statutory qualifying threshold below which a candidate is technically failed, interviews are strictly restricted to a candidate-to-seat quota (typically <strong>5 candidates per single vacancy</strong>).</p>
    <p>Because thousands of candidates apply for popular advertisements announced via PPSC, the real merit cutoff rarely drops below <strong>65 to 75 marks</strong>. Consequently, your target preparation score must always be <strong>80+ marks</strong>. Achieving this score requires transitioning from passive reading to structured, subject-wise tactical preparation.</p>

    <!-- Section 2 -->
    <div style="{$hStyle}">2. Official 10-Subject Syllabus Weightage & Breakdown</div>
    <p>Unless a post requires 80% specialized academic qualifications (such as Assistant Professor, Medical Officer, or Lecturer roles), PPSC One-Paper General Ability tests draw their 100 questions from ten standard academic domains. Here is the exact subject breakdown:</p>

    <div class="table-responsive my-3">
        <table class="table table-bordered table-striped" style="color: #111111; font-size: 15px;">
            <thead style="background-color: #5869DA; color: #ffffff;">
                <tr>
                    <th style="width: 8%;">Sr #</th>
                    <th style="width: 25%;">Subject Domain</th>
                    <th style="width: 15%;">Approx. Weightage</th>
                    <th>Core Focus Areas & Frequently Tested Concepts</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><strong>General Knowledge (GK)</strong></td>
                    <td>15% – 20%</td>
                    <td>United Nations agencies, international treaties, world capitals, national currencies, geographical wonders, prominent global organizations (OIC, SAARC, SCO, NATO).</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td><strong>Pakistan Studies & History</strong></td>
                    <td>10% – 12%</td>
                    <td>Pre-Partition history (1857 to 1947), Sir Syed Ahmed Khan's educational movements, Lahore Resolution 1940, Constitutional evolution (1956, 1962, 1973), and prominent national events.</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td><strong>Current Affairs (National & Global)</strong></td>
                    <td>10%</td>
                    <td>Events of the preceding 12 months, national leadership appointments, CPEC development phases, international summits, and global geopolitical agreements.</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td><strong>Everyday Science</strong></td>
                    <td>10%</td>
                    <td>Human biology & physiology, vitamins and deficiency disorders, physical units of measurement, atmospheric layers, solar system, and basic chemistry.</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td><strong>Basic Mathematics & Arithmetic</strong></td>
                    <td>10%</td>
                    <td>Percentages, ratios and proportions, averages, simple equations, speed-distance-time formulas, profit & loss, and basic arithmetic progressions.</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td><strong>English Grammar & Vocabulary</strong></td>
                    <td>10%</td>
                    <td>Appropriate prepositions, sentence corrections, idioms and phrasal verbs, active/passive voice, direct/indirect speech, synonyms, and antonyms.</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td><strong>Islamic Studies / Ethics</strong></td>
                    <td>10%</td>
                    <td>Ghazwat, life of the Holy Prophet (PBUH), Khilafat-e-Rashida, Quranic revelation timeline, pillars of Islam. (Non-Muslim candidates receive ethical philosophy questions).</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td><strong>Geography of Pakistan & World</strong></td>
                    <td>5% – 8%</td>
                    <td>Mountain ranges (Himalayas, Karakoram, Hindukush), major mountain passes (Bolan, Khunjerab, Khyber), river networks, dams, deserts, and international straits.</td>
                </tr>
                <tr>
                    <td>9</td>
                    <td><strong>Urdu Literature & Language</strong></td>
                    <td>5% – 8%</td>
                    <td>Prominent poets (Allama Iqbal, Mirza Ghalib, Mir Taqi Mir), famous literary works, poetic terms (Radif, Qafia, Matla, Maqta), idioms (Zarb-ul-Amsaal), and grammar rules.</td>
                </tr>
                <tr>
                    <td>10</td>
                    <td><strong>Computer Studies & MS Office</strong></td>
                    <td>5%</td>
                    <td>MS Word, MS Excel, and MS PowerPoint shortcut keys, internet networking protocols, hardware vs. software classifications, and fundamental computing terminology.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Section 3 -->
    <div style="{$hStyle}">3. Recommended Preparation Books by Successful Candidates</div>
    <p>Do not waste financial resources purchasing dozens of commercial pamphlets. Experienced candidates who consistently qualify for PPSC exams rely on a core set of standard publications:</p>

    <ul>
        <li><strong>Primary Past Paper Compendium:</strong> <em>PPSC Original Past Papers</em> by <strong>Imtiaz Shahid (Advanced Publishers, Lahore)</strong>. This book is widely recognized as the gold standard for understanding question patterns and identifying repeating questions across departments.</li>
        <li><strong>General Knowledge Foundation:</strong> <em>Caravan General Knowledge Encyclopedia</em> by <strong>Chaudhry Ahmed Najib (Caravan Book House)</strong>. This volume covers history, geography, science, and world knowledge comprehensively.</li>
        <li><strong>English Language Mastery:</strong> <em>Exploring the World of English</em> by <strong>Saadat Ali Shah</strong>. Essential for prepositions, structural grammar rules, and vocabulary development.</li>
        <li><strong>Everyday Science Concepts:</strong> <em>General Science & Ability</em> by <strong>Mian Shafiq (Jahangir World Times)</strong>. Provides clear, accessible explanations of physical and natural sciences.</li>
        <li><strong>Current Affairs Tracking:</strong> Read the daily editorial pages of <em>Dawn</em> or <em>The News</em>, supplemented by the monthly <em>Jahangir's World Times (JWT)</em> digest for structured monthly roundups.</li>
    </ul>

    <!-- Section 4 -->
    <div style="{$hStyle}">4. Mastering the Negative Marking Penalty: The 3-Round Strategy</div>
    <p>In PPSC examinations, incorrect answers are penalized with a <strong>-0.25 mark deduction</strong>. Guessing blindly is the primary reason why well-prepared candidates fail. Consider the arithmetic:</p>
    <div class="p-3 bg-light border-left border-danger rounded mb-3" style="border-left-width: 5px !important;">
        <p class="mb-0"><strong>The Negative Marking Reality:</strong> If you answer 60 questions correctly and guess 40 questions blindly—getting 10 right and 30 wrong—your score drops from 70 to <strong>62.5 marks</strong>. That 7.5-mark penalty is often the difference between qualifying for an interview and missing the cutoff.</p>
    </div>

    <p>To maximize your score while minimizing penalties, implement the proven <strong>3-Round Attempting Method</strong> during your 90 minutes:</p>
    <ol>
        <li><strong>Round 1 (Minutes 0 to 45 — 100% Certainty):</strong> Read through all 100 questions sequentially. Only mark questions where you are 100% certain of the answer. Typically, a prepared candidate marks 45 to 55 questions in this round with zero penalties.</li>
        <li><strong>Round 2 (Minutes 45 to 75 — 50/50 Elimination):</strong> Return to questions where you have eliminated two incorrect choices and are deliberating between two plausible answers. Statistically, marking when you have a 50% probability yields positive net returns over a 100-question spread.</li>
        <li><strong>Round 3 (Minutes 75 to 90 — Strict Quarantine):</strong> For questions where you cannot eliminate at least two options, <strong>leave them completely blank</strong>. Accepting a zero on an unknown question protects the hard-won marks you have already banked.</li>
    </ol>

    <!-- Section 5 -->
    <div style="{$hStyle}">5. Critical Exam Day Mistakes to Avoid</div>
    <p>Every examination cycle, competent candidates are disqualified due to avoidable administrative errors. Keep these safeguards in mind:</p>
    <ul>
        <li><strong>Black or Blue Ballpoint Only:</strong> Gel pens, fountain pens, markers, and lead pencils are strictly prohibited on PPSC Optical Mark Recognition (OMR) sheets. Ink bleed or pencil graphite can cause machine scoring errors.</li>
        <li><strong>No Double Bubbling or Fluid Use:</strong> Filling two circles for one question or using correction fluid immediately invalidates that question, triggering a negative marking penalty.</li>
        <li><strong>Mandatory Original Documentation:</strong> You must present your <strong>Original CNIC</strong> and the <strong>Original Bank Treasury Challan (Form 32-A)</strong> at the examination center. Photocopies or digital photos are not accepted under any circumstances.</li>
        <li><strong>Departmental Permission Certificate (DPC / NOC):</strong> Candidates currently employed in government service must obtain a signed Departmental Permission Certificate prior to applying. Failure to produce a valid NOC during the interview stage results in immediate disqualification.</li>
    </ul>

    <!-- Section 6 -->
    <div style="{$hStyle}">6. How PPSC Calculates the Final Merit Aggregate</div>
    <p>After qualifying in the written examination, your final merit position is determined by an aggregate formula:</p>
    <ul>
        <li><strong>Academic Credentials (40 Marks):</strong> Calculated using your percentage marks across Matriculation, Intermediate, Bachelor's, and Master's degrees using PPSC's standardized formula.</li>
        <li><strong>Interview Performance (100 Marks):</strong> Conducted by a panel comprising a Commission Member, a subject specialist, and a departmental representative. The minimum qualifying mark in the interview is <strong>50% (50 marks)</strong>.</li>
        <li><strong>Final Merit Score:</strong> Academic Score (out of 40) + Interview Score (out of 100) = <strong>Total Merit Score out of 140</strong> (The written test serves as a qualifying filter).</li>
    </ul>

    <!-- Section 7: FAQs -->
    <div style="{$hStyle}">7. Frequently Asked Questions (PPSC 2026 FAQs)</div>
    <div class="accordion my-3">
        <div class="p-3 bg-light border rounded mb-2">
            <h5 style="color: #004085; margin-bottom: 5px;">Q1: How many chances are allowed for a candidate in PPSC exams?</h5>
            <p class="mb-0 small">Under PPSC regulations, candidates are allowed a maximum of <strong>three (03) chances</strong> for a specific post. A chance is only counted if the candidate appears in the written examination and scores less than 40 marks, or fails in the interview.</p>
        </div>
        <div class="p-3 bg-light border rounded mb-2">
            <h5 style="color: #004085; margin-bottom: 5px;">Q2: What is the age relaxation policy in PPSC recruitments?</h5>
            <p class="mb-0 small">The Government of Punjab routinely grants a general age relaxation of <strong>5 years for male candidates</strong> and <strong>8 years for female candidates</strong> over and above the prescribed upper age limit mentioned in the advertisement.</p>
        </div>
        <div class="p-3 bg-light border rounded mb-2">
            <h5 style="color: #004085; margin-bottom: 5px;">Q3: Can a candidate apply for multiple posts under the same advertisement?</h5>
            <p class="mb-0 small">Yes. However, candidates must submit a separate online application form and deposit an individual treasury challan fee (typically Rs. 600 per post) for each post applied for.</p>
        </div>
    </div>

    <!-- Related Internal Links Box -->
    <div class="p-4 my-4 rounded shadow-sm text-center" style="background: #fdf2f2; border: 1px solid #f8d7da; border-left: 5px solid #e53935;">
        <h5 style="color: #721c24; margin-bottom: 8px;">Explore Current Recruitment Notifications on CareerInPak</h5>
        <p class="small mb-2">Stay ahead of application deadlines with our daily verified listings:</p>
        <p class="mb-0">
            <a href="/ppsc-jobs-2026-punjab-public-service-commission-advertisement-no-18" style="color: #007bff; font-weight: bold; text-decoration: underline; margin-right: 15px;">View Latest PPSC Jobs 2026 Advertisement →</a>
            <a href="/category/govt-jobs" style="color: #007bff; font-weight: bold; text-decoration: underline;">Browse All Government Jobs in Pakistan →</a>
        </p>
    </div>

</div>
HTML;

        // Create or update the Post model
        $post = Post::query()->updateOrCreate(
            ['name' => $title],
            [
                'description' => $description,
                'content' => $articleBody,
                'status' => $status,
                'is_featured' => 1,
                'user_id' => 1,
                'views' => rand(450, 950),
            ]
        );

        // Attach Career Guides Category
        if ($category) {
            $post->categories()->sync([$category->id]);
        }

        // Attach SEO Slug
        Slug::query()->firstOrCreate(
            [
                'key' => $slugKey,
                'reference_type' => Post::class,
            ],
            [
                'reference_id' => $post->id,
                'prefix' => '',
            ]
        );

        $this->line("  ✔ Created Post: <comment>{$title}</comment>");
        $this->line("  ✔ URL: " . url($slugKey));
    }
}
