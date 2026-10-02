<?php

namespace App\Console\Commands;

use App\Services\JobPostTemplateService;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Slug\Models\Slug;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateFreshJobCommand extends Command
{
    protected $signature = 'cms:create-fresh-job {--type=umw : Type of job to create (umw, fbr, ppsc, paec)} {--draft : Save post as draft}';

    protected $description = 'Create a brand new fresh job post using the updated 11-section template engine';

    public function handle(): int
    {
        $type = $this->option('type') ?? 'umw';
        $isDraft = $this->option('draft') || $type === 'umw';
        $status = $isDraft ? BaseStatusEnum::DRAFT : BaseStatusEnum::PUBLISHED;

        $this->info("Creating fresh job post ({$type}) [Status: {$status}] using 11-section JobPostTemplateService...");

        if ($type === 'umw') {
            $title = 'University of Mianwali UMW Jobs 2026 - Consolidated Advt No 07/2026 Non-Teaching Vacancies';
            $slugKey = Str::slug('University of Mianwali UMW Jobs 2026 Consolidated Advt No 07 2026 Non Teaching Vacancies');
            $jobData = [
                'posted_on' => 'October 02, 2026',
                'city' => 'Mianwali',
                'education' => 'Master / Bachelor / B.Com / DAE / Intermediate / Matric / Primary',
                'vacancies' => '32+ Positions (14 Categories)',
                'apply_method' => 'Online Portal + Hard Copy via Courier',
                'organization' => 'University of Mianwali (UMW)',
                'salary' => 'BPS-01 to BPS-16 (Rs. 30,000 - 85,000/Month)',
                'official_source_url' => 'https://careers.umw.edu.pk/',
                'official_apply_url' => 'https://careers.umw.edu.pk/',
                'last_checked' => 'October 02, 2026',
                'deadline' => 'October 13, 2026', // Online Deadline
                'ad_image_url' => '', // Left empty for user to attach on live editor
                'also_apply_title' => 'PPSC Jobs 2026 - Punjab Public Service Commission Advertisement No 18',
                'also_apply_url' => '/ppsc-jobs-2026-punjab-public-service-commission-advertisement-no-18',
                'job_description' => '<p>The <strong>University of Mianwali (UMW)</strong> has issued Consolidated Advertisement No. 07/2026 inviting online and hard-copy applications from eligible male and female candidates holding <strong>Punjab Domicile</strong> for <strong>32+ non-teaching staff vacancies</strong> across BS-01 to BS-16 scales.</p><p>Selected candidates will be appointed on regular/contract basis at the main campus (1 KM University Road, Mianwali). Candidates must submit online applications on <a href="https://careers.umw.edu.pk/" target="_blank" rel="nofollow">careers.umw.edu.pk</a> before <strong>October 13, 2026</strong>, followed by hard copy submission via courier before <strong>October 16, 2026</strong>.</p>',
                'who_can_apply' => '<p>Only candidates possessing valid <strong>Punjab Domicile</strong> are eligible to apply. Applicants must meet the prescribed educational qualifications, post-qualification experience, computer typing proficiency, and age limits (18 to 35 years depending on scale).</p>',
                'eligibility_criteria' => '<ul>
                    <li><strong>Domicile Restriction:</strong> Mandatory Punjab Domicile only.</li>
                    <li><strong>Fee Structure:</strong> BS-16: Rs. 2,500/- | BS-14 to BS-15: Rs. 2,000/- | BS-01 to BS-11: Rs. 1,500/- (Payable via Bank Draft in favor of Treasurer, University of Mianwali).</li>
                    <li><strong>Application Dual Requirement:</strong> Online application submission on careers.umw.edu.pk AND hard copy submission via courier to Registrar Office are mandatory.</li>
                    <li><strong>Contact Office:</strong> Office of Registrar, University of Mianwali, 1 KM University Road, Mianwali (Phone: 0459-920270, Email: registrar@umw.edu.pk).</li>
                </ul>',
                'vacant_positions' => [
                    ['name' => 'Assistant (BS-16)', 'vacancies' => '02', 'education' => 'Master / BS (2nd Div) + 5 Yrs MS Office Exp', 'scale' => 'BS-16', 'location' => 'Mianwali', 'age_limit' => '21 - 35 Years'],
                    ['name' => 'Accountant (BS-15)', 'vacancies' => '01', 'education' => 'B.Com (2nd Div) or equivalent', 'scale' => 'BS-15', 'location' => 'Mianwali', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Caretaker (BS-14)', 'vacancies' => '01', 'education' => 'Bachelor Degree (2nd Div)', 'scale' => 'BS-14', 'location' => 'Mianwali', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Electrician (BS-11)', 'vacancies' => '01', 'education' => 'Inter + DAE Electrical + 2 Yrs Exp', 'scale' => 'BS-11', 'location' => 'Mianwali', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Junior Clerk (BS-11)', 'vacancies' => '09', 'education' => 'HSSC (2nd Div) + 25 wpm typing + MS Office', 'scale' => 'BS-11', 'location' => 'Mianwali', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Qari (BS-09)', 'vacancies' => '01', 'education' => 'SSC (2nd Div) + Hifz-e-Quran Tajveed', 'scale' => 'BS-09', 'location' => 'Mianwali', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Junior Storekeeper (BS-07)', 'vacancies' => '01', 'education' => 'SSC (2nd Div) + 25 wpm typing speed', 'scale' => 'BS-07', 'location' => 'Mianwali', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Cook (BS-05)', 'vacancies' => '02', 'education' => 'Matric (2nd Div) + 2 Yrs Cooking Exp', 'scale' => 'BS-05', 'location' => 'Mianwali', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Library Attendant (BS-05)', 'vacancies' => '02', 'education' => 'SSC (2nd Div) + Certificate in Library Science', 'scale' => 'BS-05', 'location' => 'Mianwali', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Bus Driver (BS-04)', 'vacancies' => '04', 'education' => 'Matric + Valid HTV & PSV License + 5 Yrs Exp', 'scale' => 'BS-04', 'location' => 'Mianwali', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Driver LTV (BS-04)', 'vacancies' => '03', 'education' => 'Matric + Valid LTV & PSV License + 5 Yrs Exp', 'scale' => 'BS-04', 'location' => 'Mianwali', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Computer Lab Attendant (BS-01)', 'vacancies' => '01', 'education' => 'SSC (2nd Div) with Computer Science', 'scale' => 'BS-01', 'location' => 'Mianwali', 'age_limit' => '18 - 25 Years'],
                    ['name' => 'Junior Lab Attendant (BS-01)', 'vacancies' => '04', 'education' => 'Secondary School Certificate (2nd Div)', 'scale' => 'BS-01', 'location' => 'Mianwali', 'age_limit' => '18 - 25 Years'],
                    ['name' => 'Guest House Attendant (BS-01)', 'vacancies' => '01', 'education' => 'Secondary School Certificate (2nd Div)', 'scale' => 'BS-01', 'location' => 'Mianwali', 'age_limit' => '18 - 25 Years'],
                ],
                'documents_required' => [
                    'Printed copy of Online Application Form from careers.umw.edu.pk',
                    'Original Bank Draft favoring Treasurer, University of Mianwali',
                    'Attested copies of CNIC and Punjab Domicile Certificate',
                    'Attested copies of all Educational Certificates, Degrees & Transcripts',
                    'Experience Certificates & Valid Driving Licenses (where applicable)',
                    '03 Passport size recent photographs',
                ],
                'mistakes_to_avoid' => [
                    'Do not miss the hard-copy courier deadline (October 16, 2026) — online submission alone is not sufficient.',
                    'Do not deposit fee via cash or online transfer — UMW requires a Bank Draft favoring Treasurer, University of Mianwali.',
                    'Candidates without Punjab Domicile should not apply as non-Punjab applications will be rejected.',
                ],
                'selection_process' => [
                    'Online application submission on careers.umw.edu.pk before October 13, 2026.',
                    'Bank Draft payment and courier dispatch of complete application dossier to Registrar Office before October 16, 2026.',
                    'Written test & typing/practical test for Clerical, Computer, and Technical positions.',
                    'Shortlisting of qualified candidates for Interview by UMW Selection Board.',
                ],
                'how_to_apply_urdu' => '<ol>
                    <li>یونیورسٹی آف میانوالی کی سرکاری کیریئر پورٹل <strong>careers.umw.edu.pk</strong> پر جائیں۔</li>
                    <li>آن لائن درخواست فارم پر کریں اور پرنٹ آؤٹ حاصل کریں۔</li>
                    <li>اپنے سکیل کے مطابق پروسیسنگ فیس (BS-16: 2500, BS-14/15: 2000, BS-01 to 11: 1500) کا بینک ڈرافٹ "Treasurer, University of Mianwali" کے نام بنوائیں۔</li>
                    <li>تمام تعلیمی اسناد، ڈومیسائل، سی این آئی سی اور تصاویر کی تصدیق شدہ کاپیاں آن لائن فارم کے ساتھ منسلک کریں۔</li>
                    <li>درخواست کی ہارڈ کاپی بذریعہ ڈاک/کوریئر <strong>16 اکتوبر 2026</strong> سے پہلے دفتر رجسٹرار، یونیورسٹی آف میانوالی ارسال کریں۔</li>
                </ol>',
            ];
        } elseif ($type === 'paec') {
            $title = 'Pakistan Atomic Energy Commission PAEC Jobs 2026 - Scientific Officer & Tech-I';
            $slugKey = Str::slug('Pakistan Atomic Energy Commission PAEC Jobs 2026 Scientific Officer Tech I');
            $jobData = [
                'posted_on' => 'August 01, 2026',
                'city' => 'Islamabad, Rawalpindi, Karachi',
                'education' => 'BS / MSc / DAE / Matric',
                'vacancies' => '85 Positions',
                'apply_method' => 'Online via Career Portal',
                'organization' => 'Pakistan Atomic Energy Commission (PAEC)',
                'salary' => 'SPS-04 to SPS-08 (Rs. 50,000 - 150,000/Month)',
                'official_source_url' => 'https://www.paec.gov.pk/careers',
                'official_apply_url' => 'https://www.paec.gov.pk/careers',
                'last_checked' => 'August 01, 2026',
                'deadline' => 'August 27, 2026', // Expired date!
                'ad_image_url' => '/storage/news/3.jpg',
                'also_apply_title' => 'NESCOM Jobs 2026 - National Engineering & Scientific Commission',
                'also_apply_url' => '/category/nescom',
                'job_description' => '<p>The <strong>Pakistan Atomic Energy Commission (PAEC)</strong> invites applications from highly motivated Pakistani citizens for various technical and scientific positions under Public Sector Organization (PO Box 1114).</p><p>Selected candidates will be offered competitive pay packages, medical facilities, and government quarter allowances as per SPS scale rules.</p>',
                'who_can_apply' => '<p>Pakistani citizens from all provinces (Punjab, Sindh, KPK, Balochistan, AJK, GB) possessing requisite educational degrees, first division in academic career, and meeting physical fitness standards.</p>',
                'eligibility_criteria' => '<ul>
                    <li><strong>Scientific Officer (SPS-08):</strong> BS / MSc Physics / Chemistry / Electronics (1st Div).</li>
                    <li><strong>Tech-I (SPS-04):</strong> 3-Years DAE (Mechanical / Electrical / Chemical) from recognized Board of Technical Education.</li>
                    <li><strong>Junior Assistant-I (SPS-04):</strong> B.Com / BBA / BCs (1st Div) with computer literacy.</li>
                    <li><strong>Age Limit:</strong> 18 to 35 Years.</li>
                </ul>',
                'vacant_positions' => [
                    ['name' => 'Scientific Officer (Physics/Electronics)', 'vacancies' => '15', 'education' => 'BS / MSc (1st Div)', 'scale' => 'SPS-08', 'location' => 'Islamabad', 'age_limit' => '18 - 35 Years'],
                    ['name' => 'Tech-I (Electrical / Mechanical)', 'vacancies' => '45', 'education' => '3-Years DAE (1st Div)', 'scale' => 'SPS-04', 'location' => 'Rawalpindi / Karachi', 'age_limit' => '18 - 35 Years'],
                    ['name' => 'Junior Assistant-I (Admin)', 'vacancies' => '25', 'education' => 'B.Com / BBA / BCs', 'scale' => 'SPS-04', 'location' => 'Islamabad', 'age_limit' => '18 - 35 Years'],
                ],
                'documents_required' => [
                    'Original CNIC and Domicile Certificate',
                    'All Educational Degrees & Transcripts from Matric to Highest Degree',
                    'PEC Registration / Technical Board Diploma',
                    'NOC from current employer (if government servant)',
                ],
                'mistakes_to_avoid' => [
                    'Do not submit duplicate online forms.',
                    'Candidates with 2nd division in final degree are not eligible for SPS-08 posts.',
                ],
                'selection_process' => [
                    'Online application submission on official career portal.',
                    'Written screening test for shortlisting.',
                    'Interview by PAEC Selection Board.',
                ],
                'how_to_apply_urdu' => '<ol>
                    <li>سرکاری پورٹل پر آن لائن فارم پر کریں۔</li>
                    <li>اپنی تمام تعلیمی اسناد اور کمپیوٹرائزڈ شناختی کارڈ کی کاپی سکین کر کے اپ لوڈ کریں۔</li>
                    <li>درخواست کی حتمی تاریخ 27 اگست 2026 تھی۔ (یہ جاب ایکسپائر ہو چکی ہے)۔</li>
                </ol>',
            ];
        } else {
            // Default FBR Job (Active)
            $title = 'FBR Jobs 2026 - Federal Board of Revenue Inspector Inland Revenue & DEO 350+ Vacancies';
            $slugKey = Str::slug('FBR Jobs 2026 Federal Board of Revenue Inspector Inland Revenue DEO 350 Vacancies');
            $jobData = [
                'posted_on' => 'October 02, 2026',
                'city' => 'Islamabad, Lahore, Karachi, Peshawar, Quetta',
                'education' => 'Bachelor / Master / Intermediate / Matric',
                'vacancies' => '350+ Positions',
                'apply_method' => 'Online via FPSC & FBR Portal',
                'organization' => 'Federal Board of Revenue (FBR)',
                'salary' => 'BPS-11 to BPS-16 (Rs. 45,000 - 95,000/Month)',
                'official_source_url' => 'https://www.fbr.gov.pk',
                'official_apply_url' => 'https://www.fpsc.gov.pk',
                'last_checked' => 'October 02, 2026',
                'deadline' => 'October 25, 2026', // Active date!
                'ad_image_url' => '/storage/news/1.jpg',
                'also_apply_title' => 'State Bank of Pakistan SBP Officers Training Scheme 2026',
                'also_apply_url' => '/category/banking',
                'job_description' => '<p>The <strong>Federal Board of Revenue (FBR)</strong> has announced recruitment for <strong>350+ vacant posts</strong> across its Regional Tax Offices (RTOs) and Customs Collectorates nationwide. Opportunities include <strong>Inspector Inland Revenue (BPS-16)</strong>, <strong>Data Entry Operator (BPS-14)</strong>, <strong>Stenotypist (BPS-14)</strong>, and <strong>Upper Division Clerk (BPS-11)</strong>.</p><p>Both male and female candidates holding valid CNIC and provincial domicile of Punjab, Sindh, KPK, Balochistan, AJK, and FATA/GB are invited to submit online applications before the last date.</p>',
                'who_can_apply' => '<p>Citizens of Pakistan having valid domicile of any province/region can apply. Female quota (15%), Minority quota (5%), and Disabled quota (2%) will be strictly observed in accordance with Federal Government rules. General age relaxation of 5 years is admissible to all applicants.</p>',
                'eligibility_criteria' => '<ul>
                    <li><strong>Inspector Inland Revenue (BPS-16):</strong> Second Class or Grade "C" Bachelor Degree with Economics, Business Administration, Commerce, Accounting, Statistics or Law.</li>
                    <li><strong>Data Entry Operator (DEO - BPS-14):</strong> Bachelor Degree in Computer Science / Physics / Math / Stat with minimum typing speed of 10,000 key depressions per hour.</li>
                    <li><strong>Stenotypist (BPS-14):</strong> Intermediate (FA/FSc) with 80/40 wpm shorthand/typing speed and computer literacy.</li>
                    <li><strong>Age Limit:</strong> 20 to 28 years (+ 5 years general age relaxation = 33 years max).</li>
                </ul>',
                'vacant_positions' => [
                    ['name' => 'Inspector Inland Revenue', 'vacancies' => '180', 'education' => 'Bachelor (Economics / BBA / B.Com / LLB)', 'scale' => 'BS-16 (Regular)', 'location' => 'All FBR Regional Offices', 'age_limit' => '20 - 33 Years'],
                    ['name' => 'Data Entry Operator (DEO)', 'vacancies' => '95', 'education' => 'BCS / BSc (Computer Science)', 'scale' => 'BS-14 (Regular)', 'location' => 'Islamabad, Lahore, Karachi', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Stenotypist', 'vacancies' => '50', 'education' => 'Intermediate + Shorthand (80 wpm)', 'scale' => 'BS-14 (Regular)', 'location' => 'All Regional Collectorates', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Upper Division Clerk (UDC)', 'vacancies' => '25', 'education' => 'Intermediate (FA / FSc / I.Com)', 'scale' => 'BS-11 (Regular)', 'location' => 'Headquarters Islamabad', 'age_limit' => '18 - 30 Years'],
                ],
                'documents_required' => [
                    'Original CNIC card copy',
                    'Domicile Certificate of relevant district/province',
                    'Educational Certificates (Matric, Inter, Bachelor) & Marksheets',
                    'Computer / Shorthand Course Certificate (for DEO and Stenotypist posts)',
                    'Passport size recent photographs with blue background',
                ],
                'mistakes_to_avoid' => [
                    'Do not submit incomplete online forms or omit typing speed credentials.',
                    'Ensure your email address and mobile number are active for SMS roll number alerts.',
                    'Do not pay any fee to unauthorized private agencies — FBR/FPSC recruitment is conducted strictly on merit.',
                ],
                'selection_process' => [
                    'Online registration on the FPSC / FBR official recruitment web portal.',
                    'MCQs Screening Test / Professional Typing & Skill Test for Stenotypists/DEOs.',
                    'Document verification and shortlisting of qualified candidates.',
                    'Panel Interview and final appointment letter issuance by FBR Head Office.',
                ],
                'how_to_apply_urdu' => '<ol>
                    <li>ایف بی آر (FBR) یا FPSC کی سرکاری ویب سائٹ پر جائیں۔</li>
                    <li>اپنا این آئی سی اور پاسورڈ درج کر کے آن لائن پروفائل بنائیں۔</li>
                    <li>مطلوبہ پوسٹ (مثلاً انسپکٹر ان لینڈ ریونیو یا ڈیٹا انٹری آپریٹر) کا انتخاب کریں۔</li>
                    <li>تعلیمی معلومات، ٹائپنگ سپیڈ اور ڈومیسائل کی تفصیلات احتیاط سے پر کریں۔</li>
                    <li>درخواست کی حتمی تاریخ <strong>25 اکتوبر 2026</strong> سے پہلے آن لائن submit کریں۔</li>
                </ol>',
            ];
        }

        $content = JobPostTemplateService::render($jobData);

        $category = Category::query()->where('name', 'Federal Jobs')->first()
            ?: Category::query()->where('name', 'Jobs')->first()
            ?: Category::query()->first();

        $post = Post::query()->updateOrCreate(
            ['name' => $title],
            [
                'description' => "{$jobData['organization']} Jobs 2026 announced for {$jobData['vacancies']}. Check eligibility, age limit, salary details and apply online before last date.",
                'content' => $content,
                'status' => $status,
                'is_featured' => 1,
                'user_id' => 1,
                'views' => rand(300, 750),
            ]
        );

        if ($category) {
            $post->categories()->sync([$category->id]);
        }

        Slug::query()->firstOrCreate(
            [
                'key' => $slugKey,
                'reference_type' => $post::class,
            ],
            [
                'reference_id' => $post->id,
                'prefix' => '',
            ]
        );

        if (class_exists(\Botble\Language\Models\LanguageMeta::class)) {
            \Botble\Language\Models\LanguageMeta::query()->firstOrCreate(
                [
                    'reference_id' => $post->id,
                    'reference_type' => $post::class,
                ],
                [
                    'lang_meta_code' => setting('language_default_code', 'en_US'),
                    'lang_meta_origin' => md5(microtime() . $post->id),
                ]
            );
        }

        \Botble\Support\Services\Cache\Cache::make(Post::class)->flush();
        $this->callSilent('optimize:clear');

        $this->info("✔ Fresh Job Post Created Successfully!");
        $this->line("  Title: {$post->name}");
        $this->line("  URL: " . url($slugKey));

        return self::SUCCESS;
    }
}
