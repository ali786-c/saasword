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
    protected $signature = 'cms:create-fresh-job {--type=nadra : Type of job to create (nadra, umw, fbr, railways, pidcl, ppsc, paec)} {--draft : Save post as draft} {--publish : Publish post directly}';

    protected $description = 'Create a brand new fresh job post using the updated 11-section template engine';

    public function handle(): int
    {
        $type = $this->option('type') ?? 'nadra';
        $isPublish = $this->option('publish');
        $status = $isPublish ? BaseStatusEnum::PUBLISHED : BaseStatusEnum::DRAFT;

        $this->info("Creating fresh job post ({$type}) [Status: {$status}] using 11-section JobPostTemplateService...");

        if ($type === 'nadra') {
            $title = 'NADRA Jobs 2026 Islamabad - Scanning Operator Project-Based Vacancies';
            $slugKey = Str::slug('NADRA Jobs 2026 Islamabad Scanning Operator Project Based Vacancies');
            $jobData = [
                'posted_on'           => 'September 27, 2026',
                'city'                => 'Islamabad (NADRA Regional Office)',
                'education'           => 'Minimum Intermediate / FA / FSc / I.Com or Equivalent',
                'vacancies'           => 'Multiple Positions (Project-Based)',
                'apply_method'        => 'Online via NADRA Careers Portal',
                'organization'        => 'National Database & Registration Authority (NADRA)',
                'salary'              => 'Rs. 40,000 / Month Base + Per Page Scanned Incentive',
                'official_source_url' => 'https://epaper.brecorder.com/2026/09/27/7-page/attachment/2337612-picture.html',
                'official_apply_url'  => 'https://careers.nadra.gov.pk',
                'last_checked'        => 'October 04, 2026',
                'deadline'            => 'October 11, 2026',
                'ad_image_url'        => 'https://epaper.brecorder.com/2026/09/27/7-page/attachment/2337612-picture.html',
                'also_apply_title'    => 'FBR Jobs 2026 - Inspector Inland Revenue & DEO 350+ Vacancies',
                'also_apply_url'      => '/category/federal-jobs',
                'job_description'     => '<p>The <strong>National Database & Registration Authority (NADRA)</strong>, Ministry of Interior & Narcotics Control, Government of Pakistan, has announced online applications from eligible residents of Islamabad for project-based <strong>Scanning Operator</strong> positions at the NADRA Regional Office Islamabad.</p><p>Under Regulation 9, 10, and 11 of NADRA Employees (Service) Regulations 2002, selected candidates will be assigned the task of archiving old NADRA records with a daily target of scanning a minimum of <strong>400 pages per day</strong> across three work shifts (Morning, Evening, Night). Interested male, female, transgender, and differently-abled applicants must apply online on <a href="https://careers.nadra.gov.pk" target="_blank" rel="nofollow">careers.nadra.gov.pk</a> before <strong>October 11, 2026</strong>.</p>',
                'who_can_apply'       => '<p>Only candidates possessing valid <strong>CNIC / Domicile of Islamabad</strong> are eligible to apply and appear for the interview. Applicants must possess a minimum qualification of Intermediate (FA / FSc / I.Com / ICS or equivalent), be up to <strong>25 years of age</strong> (including 5 years general age relaxation), and be willing to work in any assigned shift (Morning, Evening, or Night) six days a week.</p>',
                'eligibility_criteria'=> '<ul>
                    <li><strong>Domicile Restriction:</strong> Strictly reserved for Residents of Islamabad (as per CNIC / Domicile).</li>
                    <li><strong>Educational Qualification:</strong> Minimum Intermediate (HSSC / FA / FSc / I.Com / ICS) or equivalent from a recognized board.</li>
                    <li><strong>Age Limit:</strong> Maximum 25 Years (General 5 years age relaxation is already included).</li>
                    <li><strong>Daily Scanning Target:</strong> Candidates must scan a minimum of 400 pages per day.</li>
                    <li><strong>Shift Requirement:</strong> Project operates in 3 shifts (Morning, Evening, Night), 6 days a week. Candidates must be willing to serve in any shift.</li>
                    <li><strong>Employment Terms:</strong> Purely project-based contract for 3 months (90 days) – extendable based on project requirements.</li>
                </ul>',
                'vacant_positions'    => [
                    [
                        'name'      => 'Scanning Operator (Project-Based)',
                        'vacancies' => 'Multiple',
                        'education' => 'Minimum Intermediate or Equivalent',
                        'scale'     => 'Contract (Rs. 40,000 + Incentive)',
                        'location'  => 'NADRA Regional Office Islamabad',
                        'age_limit' => 'Max 25 Years',
                    ],
                ],
                'documents_required'  => [
                    'Candidate Updated Curriculum Vitae (CV)',
                    'Original CNIC and Domicile Certificate of Islamabad',
                    'Educational Board Certificates & Transcripts (Matric & Intermediate)',
                    'No Objection Certificate (NOC) if currently serving in Govt / Semi-Govt',
                    'Recent passport-size photographs',
                ],
                'mistakes_to_avoid'   => [
                    'Do not submit hard-copy applications — only online applications via careers.nadra.gov.pk are accepted.',
                    'Non-residents of Islamabad should not apply as CNIC/Domicile verification is strictly mandatory.',
                    'Do not bring electronic gadgets (mobile phone, smartwatch, etc.) to the interview room as they are strictly prohibited.',
                    'Do not provide false or forged information — misleading data will result in permanent disqualification.',
                ],
                'selection_process'   => [
                    'Online application submission on official NADRA portal (careers.nadra.gov.pk) before October 11, 2026.',
                    'Shortlisting of qualified candidates based on Islamabad domicile and educational credentials.',
                    'Interview at NADRA Headquarters / Regional Office Islamabad (Bring original documents).',
                    'Issuance of 3-month project-based contract offer letter to selected candidates.',
                ],
                'how_to_apply_urdu'   => '<ol>
                    <li>نادرا کی سرکاری کیریئر ویب سائٹ <strong>careers.nadra.gov.pk</strong> پر جائیں۔</li>
                    <li>آن لائن درخواست فارم پُر کریں اور سی وی، تعلیمی اسناد، شناختی کارڈ اور اسلام آباد ڈومیسائل اپ لوڈ کریں۔</li>
                    <li>درخواست جمع کروانے کی آخری تاریخ <strong>11 اکتوبر 2026</strong> ہے۔</li>
                    <li>صرف اسلام آباد کے شناختی کارڈ اور ڈومیسائل کے حامل امیدواران انٹرویو کے لیے اہل ہوں گے۔</li>
                    <li>انٹرویو کے وقت اصل تعلیمی اسناد، سی این آئی سی اور ڈومیسائل ساتھ لانا لازمی ہے۔</li>
                </ol>',
            ];
        } elseif ($type === 'umw') {
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
        } elseif ($type === 'railways') {
            $title = 'Pakistan Railways Jobs 2026 - Headquarters Office Lahore 45 Vacancies';
            $slugKey = Str::slug('Pakistan Railways Jobs 2026 Headquarters Office Lahore 45 Vacancies');
            $jobData = [
                'posted_on' => 'September 27, 2026',
                'city' => 'Lahore (All Pakistan Posting)',
                'education' => 'DAE / Mechanical / Middle',
                'vacancies' => '45 Positions (11 Categories)',
                'apply_method' => 'By Post / Courier to Chief Personnel Officer',
                'organization' => 'Pakistan Railways (Headquarters Office, Lahore)',
                'salary' => 'BS-06 to BS-14 (As per last pay drawn + usual allowances)',
                'official_source_url' => 'https://epaper.dawn.com/?page=27_09_2026_119',
                'official_apply_url' => 'https://epaper.dawn.com/?page=27_09_2026_119',
                'last_checked' => 'October 03, 2026',
                'deadline' => 'October 12, 2026',
                'ad_image_url' => '',
                'also_apply_title' => 'FBR Jobs 2026 - Inspector Inland Revenue & DEO 350+ Vacancies',
                'also_apply_url' => '/category/federal-jobs',
                'job_description' => '<p>The <strong>Pakistan Railways Headquarters Office, Lahore</strong> has announced contractual recruitment for <strong>45 vacant positions</strong> in the <strong>Carriage & Wagon (C&W)</strong> and <strong>Mechanical Loco Departments</strong>. Suitable retired supervisory and technical staff up to <strong>62 years of age</strong> are invited to apply for 1-year contract appointment (extendable for another year).</p><p>Selected candidates will receive salary according to their last pay drawn in BS-06, BS-09, BS-11, BS-12, and BS-14 along with usual allowances. Interested applicants must submit typed applications along with Rs. 500 postal order to the Chief Personnel Officer before the closing date.</p>',
                'who_can_apply' => '<p>Retired Pakistan Railways supervisory and technical employees of Carriage & Wagon and Mechanical Loco departments meeting age limit (up to 62 years), requisite qualification (DAE Mechanical / Middle), medical fitness from Railway Hospital, and minimum relevant field experience (10 to 20 years) are eligible to apply. Employees retired on compulsory, removal, dismissal, or medical grounds are NOT eligible.</p>',
                'eligibility_criteria' => '<ul>
                    <li><strong>Maximum Age Limit:</strong> Up to 62 Years (Physical fitness required).</li>
                    <li><strong>Carriage & Wagon Positions:</strong> TXR Gr-III (BS-14), Foreman Gr-I (BS-14), TXR Gr-II (BS-12), Sub Engineer-II/Sr Chargeman (BS-12), Mistry (BS-09), Skilled Fitter/Coach Builder/Welder (BS-06).</li>
                    <li><strong>Loco Department Positions:</strong> Foreman/Electrical Foreman Gr-I (BS-14), AFO/Sr Chargeman (BS-12), JCM & S.E.E Diesel (BS-11), Mistry Mech/Elect (BS-09), Skilled Wireman/Fitter (BS-06).</li>
                    <li><strong>Postal Order Fee:</strong> Rs. 500/- Postal Order in favor of Pakistan Railways.</li>
                    <li><strong>Medical Fitness:</strong> Passing medical examination at Pakistan Railways Hospital is mandatory.</li>
                </ul>',
                'vacant_positions' => [
                    ['name' => 'TXR / Gr-III (C&W)', 'vacancies' => '01', 'education' => 'DAE Mechanical + 20 Yrs Exp', 'scale' => 'BS-14', 'location' => 'Lahore / Railway System', 'age_limit' => 'Up to 62 Years'],
                    ['name' => 'Foreman / Gr-I (C&W)', 'vacancies' => '02', 'education' => 'DAE Mechanical + 20 Yrs Exp', 'scale' => 'BS-14', 'location' => 'Lahore / Railway System', 'age_limit' => 'Up to 62 Years'],
                    ['name' => 'TXR / Gr-II (C&W)', 'vacancies' => '06', 'education' => 'DAE Mechanical + 10 Yrs Exp', 'scale' => 'BS-12', 'location' => 'Lahore / Railway System', 'age_limit' => 'Up to 62 Years'],
                    ['name' => 'Sub Engineer-II / Sr Chargeman (C&W)', 'vacancies' => '08', 'education' => 'DAE Mechanical + 10 Yrs Exp', 'scale' => 'BS-12', 'location' => 'Lahore / Railway System', 'age_limit' => 'Up to 62 Years'],
                    ['name' => 'Mistry (C&W)', 'vacancies' => '01', 'education' => 'Middle + 10 Yrs Exp', 'scale' => 'BS-09', 'location' => 'Lahore / Railway System', 'age_limit' => 'Up to 62 Years'],
                    ['name' => 'Skilled (C&W Fitter, Coach Builder, Welder)', 'vacancies' => '03', 'education' => 'Middle + 10 Yrs Exp', 'scale' => 'BS-06', 'location' => 'Lahore / Railway System', 'age_limit' => 'Up to 62 Years'],
                    ['name' => 'Foreman/Gr-I & Electrical Foreman Diesel (Loco)', 'vacancies' => '04', 'education' => 'DAE Mechanical + 10 Yrs Exp', 'scale' => 'BS-14', 'location' => 'Lahore / Railway System', 'age_limit' => 'Up to 62 Years'],
                    ['name' => 'AFO / Sr. Chargeman / AEFO Diesel (Loco)', 'vacancies' => '07', 'education' => 'DAE Mechanical + 10 Yrs Exp', 'scale' => 'BS-12', 'location' => 'Lahore / Railway System', 'age_limit' => 'Up to 62 Years'],
                    ['name' => 'JCM & S.E.E Diesel (Loco)', 'vacancies' => '04', 'education' => 'DAE Mechanical + 10 Yrs Exp', 'scale' => 'BS-11', 'location' => 'Lahore / Railway System', 'age_limit' => 'Up to 62 Years'],
                    ['name' => 'Mistry Mech & Elect Diesel (Loco)', 'vacancies' => '03', 'education' => 'Middle + 10 Yrs Exp', 'scale' => 'BS-09', 'location' => 'Lahore / Railway System', 'age_limit' => 'Up to 62 Years'],
                    ['name' => 'Skilled Wireman & Fitter Diesel (Loco)', 'vacancies' => '06', 'education' => 'Middle + 10 Yrs Exp', 'scale' => 'BS-06', 'location' => 'Lahore / Railway System', 'age_limit' => 'Up to 62 Years'],
                ],
                'documents_required' => [
                    'Typed application form with complete database',
                    'Postal Order of Rs. 500/-',
                    'Attested copies of all Educational Certificates',
                    'Attested copy of Computerized CNIC',
                    'Railway Retired Identity Card / Experience Certificate',
                    'Photocopy of PPO (Pension Payment Order)',
                    'Photocopy of LPC (Last Pay Certificate)',
                    'Service Certificate',
                    'Three (03) fresh passport size photographs',
                    'Three (03) registered postal envelopes with RAD card and cell number written',
                ],
                'mistakes_to_avoid' => [
                    'Do not forget to attach the Rs. 500 Postal Order with your application.',
                    'Employees retired on compulsory, removal, dismissal, or medical grounds should not apply.',
                    'Ensure 3 registered envelopes with RAD card and cell number are attached with application.',
                    'Send application directly to Chief Personnel Officer, Empress Road, Lahore before deadline.',
                ],
                'selection_process' => [
                    'Submission of typed application dossier with postal order to Chief Personnel Officer Lahore.',
                    'Shortlisting of eligible retired employees.',
                    'Interview by Pakistan Railways Selection Board.',
                    'Medical fitness examination at Pakistan Railways Hospital.',
                    'Contractual appointment letter issuance for 1 year.',
                ],
                'how_to_apply_urdu' => '<ol>
                    <li>اپنی تمام معلومات (ڈیٹا بیس) کے ساتھ ایک مائیکروسافٹ ورڈ میں ٹائپ شدہ درخواست تیار کریں۔</li>
                    <li>درخواست کے ساتھ <strong>500 روپے ka Postal Order</strong> (پاکستان ریلویز کے نام) منسلک کریں۔</li>
                    <li>تمام تعلیمی اسناد، شناختی کارڈ، ریلوے رٹائرڈ آئی ڈی کارڈ، PPO، LPC، اور سروس سرٹیفکیٹ کی تصدیق شدہ کاپیاں لف کریں۔</li>
                    <li>تین (03) تازہ پاسپورٹ سائز تصاویر اور 3 رجسٹری لفافے (RAD کارڈ اور فون نمبر درج شدہ) درخواست کے ساتھ پن کریں۔</li>
                    <li>مکمل درخواست بذریعہ ڈاک/کوریئر <strong>"Chief Personnel Officer, Pakistan Railways Headquarters Office, Empress Road, Lahore"</strong> کے پتے پر ارسال کریں۔</li>
                </ol>',
            ];
        } elseif ($type === 'pidcl') {
            $title = 'PIDCL Jobs 2026 - Pakistan Infrastructure Development Company 20 Vacancies';
            $slugKey = Str::slug('PIDCL Jobs 2026 Pakistan Infrastructure Development Company 20 Vacancies');
            $jobData = [
                'posted_on' => 'September 27, 2026',
                'city' => 'Islamabad, Karachi',
                'education' => 'Master / Bachelor / DAE',
                'vacancies' => '20 Positions (5 Categories)',
                'apply_method' => 'Online via National Job Portal (www.njp.gov.pk)',
                'organization' => 'Pakistan Infrastructure Development Company Limited (PIDCL)',
                'salary' => 'Rs. 100,000 - 300,000/Month (Lumpsum)',
                'official_source_url' => 'https://epaper.dawn.com/?page=27_09_2026_119',
                'official_apply_url' => 'https://www.njp.gov.pk',
                'last_checked' => 'October 03, 2026',
                'deadline' => 'October 12, 2026',
                'ad_image_url' => '',
                'also_apply_title' => 'FBR Jobs 2026 - Inspector Inland Revenue & DEO 350+ Vacancies',
                'also_apply_url' => '/category/federal-jobs',
                'job_description' => '<p>The <strong>Pakistan Infrastructure Development Company Limited (PIDCL)</strong>, operating under the <strong>Ministry of Housing & Works, Government of Pakistan</strong>, has announced project-based recruitment for <strong>20 key vacancies</strong> across Islamabad and Karachi. Positions include Senior Site Engineer, Site Engineer (Civil), Project Coordinator, Sub-Engineer Civil, and Admn / Finance Assistant on an open-merit contract basis.</p><p>Qualified professionals meeting HEC degree criteria, PEC engineering registrations, and relevant experience are invited to apply online through the <strong>National Job Portal (<a href="https://www.njp.gov.pk" target="_blank" rel="nofollow">www.njp.gov.pk</a>)</strong> before the last date.</p>',
                'who_can_apply' => '<p>Candidates from all over Pakistan meeting the prescribed educational qualifications (BS Civil Engineering, MS Construction/Project Management, DAE, Bachelor Degree), valid PEC registration (for engineering roles), and minimum age limits (25 to 30 years) are eligible on an open-merit contract basis.</p>',
                'eligibility_criteria' => '<ul>
                    <li><strong>Senior Site Engineer (Rs. 300,000/Month):</strong> Bachelor degree in Civil Engineering (HEC recognized) + at least 5 years relevant experience + mandatory PEC registration. Min Age: 30 Years.</li>
                    <li><strong>Site Engineer Civil (Rs. 150,000/Month):</strong> Bachelor degree in Civil Engineering + at least 2 years relevant experience + mandatory PEC registration. Min Age: 25 Years.</li>
                    <li><strong>Project Coordinator (Rs. 150,000/Month):</strong> Master degree in Construction Management / Project Management + at least 2 years experience. Min Age: 25 Years.</li>
                    <li><strong>Sub-Engineer Civil (Rs. 100,000/Month):</strong> DAE Civil, Mechanical, or Electrical + at least 2 years experience. Min Age: 25 Years.</li>
                    <li><strong>Admn / Finance Assistant (Rs. 100,000/Month):</strong> Bachelor Degree + relevant administrative/finance experience. Min Age: 25 Years.</li>
                </ul>',
                'vacant_positions' => [
                    ['name' => 'Senior Site Engineer', 'vacancies' => '02', 'education' => 'BS Civil Engineering + 5 Yrs Exp', 'scale' => 'Contract (Rs. 300,000)', 'location' => 'Islamabad (1), Karachi (1)', 'age_limit' => '30+ Years'],
                    ['name' => 'Site Engineer (Civil)', 'vacancies' => '05', 'education' => 'BS Civil Engineering + 2 Yrs Exp', 'scale' => 'Contract (Rs. 150,000)', 'location' => 'Islamabad (3), Karachi (2)', 'age_limit' => '25+ Years'],
                    ['name' => 'Project Coordinator', 'vacancies' => '02', 'education' => 'MS Construction / Project Mgmt + 2 Yrs Exp', 'scale' => 'Contract (Rs. 150,000)', 'location' => 'Islamabad (1), Karachi (1)', 'age_limit' => '25+ Years'],
                    ['name' => 'Sub-Engineer Civil', 'vacancies' => '07', 'education' => 'DAE Civil / Mechanical / Electrical + 2 Yrs Exp', 'scale' => 'Contract (Rs. 100,000)', 'location' => 'Islamabad (4), Karachi (3)', 'age_limit' => '25+ Years'],
                    ['name' => 'Admn. / Finance Assistant', 'vacancies' => '04', 'education' => 'Bachelor Degree + Relevant Exp', 'scale' => 'Contract (Rs. 100,000)', 'location' => 'Islamabad (2), Karachi (2)', 'age_limit' => '25+ Years'],
                ],
                'documents_required' => [
                    'Attested copy of CNIC card',
                    'Educational Degrees & Transcripts (HEC recognized)',
                    'PEC Registration Certificate (Mandatory for Engineers)',
                    'DAE Diploma Certificate from Technical Board',
                    'Experience Certificates from previous employers',
                    'Recent Passport size photographs',
                ],
                'mistakes_to_avoid' => [
                    'Do not submit hard-copy applications directly to PIDCL office — apply online via www.njp.gov.pk.',
                    'Ensure your PEC registration is active before applying for engineering roles.',
                    'Do not apply after the last date (October 12, 2026).',
                    'Do not enter unverified experience details or incorrect degree names.',
                ],
                'selection_process' => [
                    'Online application submission on National Job Portal (www.njp.gov.pk).',
                    'Screening and shortlisting based on educational & PEC credentials.',
                    'Interview at PIDCL Head Office Islamabad / Karachi (No TA/DA admissible).',
                    'Appointment on project-based contractual terms.',
                ],
                'how_to_apply_urdu' => '<ol>
                    <li>قومی جاب پورٹل <strong>www.njp.gov.pk</strong> پر جائیں اور اپنا اکاؤنٹ لاگ ان کریں۔</li>
                    <li>سرچ بار میں <strong>Pakistan Infrastructure Development Company Limited (PIDCL)</strong> یا اپنی مطلوبہ اسامی (مثلاً Senior Site Engineer, Sub-Engineer) منتخب کریں۔</li>
                    <li>اپنی تمام تعلیمی اسناد، PEC رجسٹریشن نمبر اور تجربے کی تفصیلات احتیاط سے فارم میں درج کریں۔</li>
                    <li>تمام معلومات کی تصدیق کے بعد درخواست کو <strong>12 اکتوبر 2026</strong> سے پہلے آن لائن submit کریں۔</li>
                    <li>یاد رہے کہ PIDCL دفتر میں براہ راست یا بذریعہ کوریئر درخواستیں قابل قبول نہیں ہوں گی۔</li>
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
