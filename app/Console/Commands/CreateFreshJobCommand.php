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
    protected $signature = 'cms:create-fresh-job {--type=neb : Type of job to create (neb, prosecution, mohmand, cpsp, nadra, umw, fbr, railways, pidcl, ppsc, paec, uoc)} {--draft : Save post as draft} {--publish : Publish post directly}';

    protected $description = 'Create a brand new fresh job post using the updated 11-section template engine';

    public function handle(): int
    {
        $type = $this->option('type') ?? 'neb';
        $isPublish = $this->option('publish');
        $status = $isPublish ? BaseStatusEnum::PUBLISHED : BaseStatusEnum::DRAFT;

        if ($type === 'neb') {
            $title = 'National Employment Bureau NEB Jobs 2026 - 105+ Vacancies';
            $slugKey = Str::slug('National Employment Bureau NEB Jobs 2026 105 Vacancies Islamabad');
            $jobData = [
                'posted_on'           => 'October 08, 2026',
                'city'                => 'Islamabad (Headquarters) / All Pakistan Quota',
                'education'           => 'Primary / Middle / Matric / Intermediate / Bachelor / Master / ACCA / B.Com / BS CS / Software Engineering',
                'vacancies'           => '105 Positions (12 Categories)',
                'apply_method'        => 'Online via Official Portal (www.nebpakistan.org)',
                'organization'        => 'National Employment Bureau Pakistan (NEB)',
                'salary'              => 'Rs. 45,000 to Rs. 155,000 / Month (Post Wise)',
                'official_source_url' => 'https://www.nebpakistan.org',
                'official_apply_url'  => 'https://www.nebpakistan.org',
                'last_checked'        => 'October 08, 2026',
                'deadline'            => 'October 15, 2026',
                'also_apply_title'    => 'PPSC Jobs 2026 - Punjab Public Service Commission Advertisement No 18',
                'also_apply_url'      => '/ppsc-jobs-2026-punjab-public-service-commission-advertisement-no-18',
                'job_description'     => '<p>The <strong>National Employment Bureau Pakistan (NEB)</strong>, located at State Life Building, Block L, Sector F-7/4, Islamabad, has officially announced high-pay career opportunities for Pakistani citizens (Male & Female). Applications are invited for <strong>105+ vacancies</strong> across 12 distinct positions ranging from support staff to executive director roles.</p><p>Selected candidates will receive attractive monthly remuneration packages ranging from <strong>Rs. 45,000 to Rs. 155,000 per month</strong> depending on the scale and post grade. Eligible candidates across Punjab, Sindh, Khyber Pakhtunkhwa (KP), Balochistan, AJK, and Gilgit-Baltistan (GB) must submit their applications online at <strong>www.nebpakistan.org</strong> before <strong>October 15, 2026</strong>.</p>',
                'who_can_apply'       => '<p>Pakistani male and female citizens holding valid Domicile of Punjab, Sindh, Khyber Pakhtunkhwa, Balochistan, AJK, GB, or Merit seats are eligible to apply. Applicants must meet the prescribed educational qualifications (ranging from Primary to 16 Years Master’s/BS Degree) and age limits (up to 45 years depending on the post). Reserved provincial quotas are strictly allocated as per Government of Pakistan policy.</p>',
                'eligibility_criteria'=> '<ul>
                    <li><strong>Assistant Director (Admin & Employment):</strong> 16 Years Master’s / Bachelor’s degree in Public Administration, Management, HR, Business Administration, Economics, or Social Sciences. Max Age: 35 Years.</li>
                    <li><strong>IT / Software Officer:</strong> BS / MSc in Computer Science, IT, Software Engineering or equivalent with computer & software development knowledge. Max Age: 35 Years.</li>
                    <li><strong>Accounts Officer:</strong> M.Com, ACCA, MBA Finance, or B.Com (Hons) with relevant accounting experience. Max Age: 40 Years.</li>
                    <li><strong>Clerical Staff (UDC / LDC / Record Keeper):</strong> Intermediate or Matric with minimum 30 wpm typing speed and MS Office / computer proficiency. Max Age: 35 to 40 Years.</li>
                    <li><strong>Junior Accountant & Statistical Assistant:</strong> Intermediate / Associate Degree in Commerce or Statistics/Mathematics with computer literacy. Max Age: 35 Years.</li>
                    <li><strong>Support Staff (Naib Qasid, Chowkidar, Office Attendant):</strong> Primary / Middle / Literate with physical fitness and office support capabilities. Max Age: 40 to 45 Years.</li>
                </ul>',
                'vacant_positions'    => [
                    ['name' => 'Assistant Director (Administration)', 'vacancies' => '01 (Merit)', 'education' => 'Master / Bachelor (16 Yrs) in Public Admin / HR / MBA', 'scale' => 'Rs. 155,000/- p.m', 'location' => 'Islamabad', 'age_limit' => 'Max 35 Years'],
                    ['name' => 'Assistant Director (Employment Services)', 'vacancies' => '16 (Punjab:09, Sindh:03, KP:02, Bal:01, AJK/GB:01)', 'education' => '16 Yrs in Economics / Public Admin / HR / Social Sciences', 'scale' => 'Rs. 155,000/- p.m', 'location' => 'Islamabad / Regional', 'age_limit' => 'Max 35 Years'],
                    ['name' => 'IT / Software Officer', 'vacancies' => '04 (Merit:01, Punjab:02, KP:01)', 'education' => 'BS / MSc in CS, IT, Software Engineering', 'scale' => 'Rs. 155,000/- p.m', 'location' => 'Islamabad', 'age_limit' => 'Max 35 Years'],
                    ['name' => 'Accounts Officer', 'vacancies' => '05 (Punjab:03, Sindh:01, KP:01)', 'education' => 'M.Com / ACCA / MBA Finance / B.Com (Hons)', 'scale' => 'Rs. 155,000/- p.m', 'location' => 'Islamabad', 'age_limit' => 'Max 40 Years'],
                    ['name' => 'UDC (Upper Division Clerk)', 'vacancies' => '21 (Merit:02, Punjab:04, Sindh:13, KP:01, Bal:01)', 'education' => 'Intermediate + 30 wpm typing speed + MS Office', 'scale' => 'Rs. 80,000/- p.m', 'location' => 'Islamabad / Regional', 'age_limit' => 'Max 35 Years'],
                    ['name' => 'Junior Accountant', 'vacancies' => '04 (Punjab:02, Sindh:01, KP:01)', 'education' => 'Intermediate / Associate Degree in Commerce', 'scale' => 'Rs. 75,000/- p.m', 'location' => 'Islamabad', 'age_limit' => 'Max 35 Years'],
                    ['name' => 'Junior Statistical Assistant', 'vacancies' => '04 (Punjab:02, Sindh:01, KP:01)', 'education' => 'Intermediate with Statistics / Mathematics', 'scale' => 'Rs. 75,000/- p.m', 'location' => 'Islamabad', 'age_limit' => 'Max 35 Years'],
                    ['name' => 'LDC (Lower Division Clerk)', 'vacancies' => '06 (Punjab:02, Sindh:01, KP:01, Bal:01, AJK/GB:01)', 'education' => 'Matric + 30 wpm typing speed + computer basics', 'scale' => 'Rs. 72,000/- p.m', 'location' => 'Islamabad / Regional', 'age_limit' => 'Max 35 Years'],
                    ['name' => 'Record Keeper', 'vacancies' => '04 (Punjab:02, Sindh:01, KP:01)', 'education' => 'Intermediate / ICS + computer knowledge', 'scale' => 'Rs. 65,000/- p.m', 'location' => 'Islamabad', 'age_limit' => 'Max 40 Years'],
                    ['name' => 'Naib Qasid', 'vacancies' => '13 (Punjab:07, Sindh:02, KP:02, Bal:01, AJK/GB:01)', 'education' => 'Primary / Middle pass (Physically fit)', 'scale' => 'Rs. 45,500/- p.m', 'location' => 'Islamabad / Regional', 'age_limit' => 'Max 45 Years'],
                    ['name' => 'Chowkidar (Naibowkidar)', 'vacancies' => '23 (Punjab:13, Sindh:04, KP:03, Bal:02, AJK/GB:01)', 'education' => 'Primary education (Physically fit)', 'scale' => 'Rs. 45,500/- p.m', 'location' => 'Islamabad / Regional', 'age_limit' => 'Max 45 Years'],
                    ['name' => 'Office Attendant', 'vacancies' => '04 (Punjab:02, Sindh:01, KP:01)', 'education' => 'Primary / Literate', 'scale' => 'Rs. 45,000/- p.m', 'location' => 'Islamabad', 'age_limit' => 'Max 40 Years'],
                ],
                'documents_required'  => [
                    'Original CNIC card copy (Computerized National Identity Card)',
                    'Domicile Certificate of relevant district / province (Punjab, Sindh, KP, Balochistan, AJK, GB)',
                    'Educational degrees, certificates, and detailed marks certificates (DMC)',
                    'Experience certificates (for accounting, IT, and admin positions where applicable)',
                    'Recent passport-size photographs with blue background',
                    'Computer / typing skill certificate (for UDC and LDC posts)',
                ],
                'mistakes_to_avoid'   => [
                    'Do not submit online applications after the last date (October 15, 2026).',
                    'Do not provide false CNIC number, phone number, or incorrect domicile quota.',
                    'Candidates applying for UDC/LDC must ensure they pass the mandatory 30 wpm typing test.',
                    'Do not pay any fee to unauthorized persons; verify official procedure at www.nebpakistan.org.',
                    'Read the complete Terms of Reference (ToRs) on the official website before applying.',
                ],
                'selection_process'   => [
                    'Online registration through the official web portal www.nebpakistan.org.',
                    'Scrutiny of online applications and initial shortlisting of eligible candidates.',
                    'Skill / typing test for clerical candidates (UDC / LDC).',
                    'Written screening test and formal interview by NEB selection board.',
                    'Final merit list display and appointment letter issuance for selected candidates.',
                ],
                'how_to_apply_urdu'   => '<ol style="list-style-position: inside; padding-right: 15px;">
                    <li>آفیشل ویب سائٹ <strong>www.nebpakistan.org</strong> پر جائیں اور آن لائن فارم پر کریں۔</li>
                    <li>تعلیم، عمر کی حد، ڈومیسائل کوٹہ اور مطلوبہ پوسٹ کی شرائط غور سے چیک کریں۔</li>
                    <li>یو ڈی سی (UDC) اور ایل ڈی سی (LDC) کی پوسٹوں کے لیے 30 الفاظ فی منٹ ٹائپنگ سپیڈ لازمی ہے۔</li>
                    <li>آن لائن درخواست جمع کروانے کی آخری تاریخ <strong>15 اکتوبر 2026</strong> ہے۔</li>
                    <li>صرف شارٹ لسٹ شدہ امیدواروں کو تحریری ٹیسٹ، سکل ٹیسٹ اور انٹرویو کے لیے بلایا جائے گا۔</li>
                </ol>',
            ];
        } elseif ($type === 'prosecution' || $type === 'sindhprosecution') {
            $title = 'Prosecutor General Sindh Jobs 2026 - 570+ Driver, Naib Qasid, Daftari & Staff Vacancies';
            $slugKey = Str::slug('Prosecutor General Sindh Jobs 2026 Criminal Prosecution Department Walk-in Interview');
            $jobData = [
                'posted_on'           => 'October 10, 2026',
                'city'                => 'Karachi (Head Office) & All 28 Districts of Sindh',
                'education'           => 'Primary Pass / Driving License (Motorcycle/LTV) / Literate / Practical Experience',
                'vacancies'           => '570+ Positions (Head Office: 232, District Offices: 338)',
                'apply_method'        => 'Walk-in Interview with Application & Attested Documents',
                'organization'        => 'Criminal Prosecution Services Department, Government of Sindh',
                'salary'              => 'BPS-01 to BPS-04 (Rs. 32,000 to Rs. 45,000 / Month + Govt Allowances)',
                'official_source_url' => 'https://www.iwork4sindh.com',
                'official_apply_url'  => 'https://www.iwork4sindh.com',
                'last_checked'        => 'October 10, 2026',
                'deadline'            => 'October 31, 2026',
                'also_apply_title'    => 'PPSC Jobs 2026 - Punjab Public Service Commission Advertisement No 18',
                'also_apply_url'      => '/ppsc-jobs-2026-punjab-public-service-commission-advertisement-no-18',
                'job_description'     => '<p>The <strong>Criminal Prosecution Services Department, Government of Sindh</strong>, through the Office of the <strong>Prosecutor General Sindh</strong> (4th Floor, Administration Block, High Court of Sindh, Karachi), has officially announced recruitment for <strong>570+ vacant posts (BPS-01 to BPS-04)</strong>. Opportunities are available at the Provincial Head Office in Karachi (232 vacancies) as well as across all 28 District Public Prosecutor Offices throughout Sindh (338 vacancies).</p><p>Applications are invited for the positions of <strong>Dispatch Rider (BPS-04)</strong>, <strong>Driver (BPS-04)</strong>, <strong>Daftari (BPS-02)</strong>, <strong>Chowkidar (BPS-01)</strong>, <strong>Naib Qasid (BPS-01)</strong>, and <strong>Sanitary Worker (BPS-01)</strong>. Recruitment will be conducted strictly through scheduled <strong>Walk-in Interviews</strong> from <strong>October 19, 2026 to October 31, 2026</strong> as per district quotas and published schedules (INF-KRY 4363-26).</p>',
                'who_can_apply'       => '<p>Candidates holding valid <strong>Domicile and PRC (Form-D) of Sindh Province</strong> (allocated under Rural 60% and Urban 40% quotas) are eligible. For Head Office positions in Karachi, candidates holding Sindh domicile are eligible. For District Public Prosecutor offices, candidates must possess the domicile/PRC of the concerned district. Applicants must be between <strong>18 to 30 years of age</strong> (general upper age relaxation is admissible per Government of Sindh policy). Separate <strong>5% quotas</strong> are reserved for Women, Minorities, and Persons with Disabilities.</p>',
                'eligibility_criteria'=> '<ul>
                    <li><strong>Dispatch Rider (BPS-04):</strong> At least Primary pass with a valid Motorcycle driving license. Age: 18-30 years.</li>
                    <li><strong>Driver (BPS-04):</strong> At least Primary pass with a valid Car/LTV driving license, minimum 2 years driving experience, and ability to maintain a vehicle log book. Age: 18-30 years.</li>
                    <li><strong>Daftari (BPS-02):</strong> Primary pass will be preferred. Age: 18-30 years.</li>
                    <li><strong>Chowkidar (BPS-01):</strong> Preferably literate. Age: 18-30 years.</li>
                    <li><strong>Naib Qasid (BPS-01):</strong> Preferably literate. Age: 18-30 years.</li>
                    <li><strong>Sanitary Worker (BPS-01):</strong> Relevant practical experience in sanitary work. Age: 18-30 years.</li>
                    <li><strong>Age Limit & Relaxation:</strong> 18 to 30 years. Upper age relaxation admissible in accordance with Government of Sindh policy.</li>
                </ul>',
                'vacant_positions'    => [
                    ['name' => 'Naib Qasid', 'vacancies' => '394 (Head Office: 105, 28 Districts: 289)', 'education' => 'Preferably Literate', 'scale' => 'BPS-01', 'location' => 'Karachi & All 28 Sindh Districts', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Dispatch Rider', 'vacancies' => '44 (Head Office: 33, Districts: 11)', 'education' => 'Primary Pass + Motorcycle License', 'scale' => 'BPS-04', 'location' => 'Karachi & District Offices', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Daftari', 'vacancies' => '41 (Head Office)', 'education' => 'Primary Pass Preferred', 'scale' => 'BPS-02', 'location' => 'Prosecutor General Office Karachi', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Sanitary Worker', 'vacancies' => '45 (Head Office: 32, Districts: 13)', 'education' => 'Experience of Sanitary Work', 'scale' => 'BPS-01', 'location' => 'Karachi & District Offices', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Driver', 'vacancies' => '27 (Head Office: 19, Districts: 08)', 'education' => 'Primary Pass + Car/LTV License + 2 Yrs Exp', 'scale' => 'BPS-04', 'location' => 'Karachi & District Offices', 'age_limit' => '18 - 30 Years'],
                    ['name' => 'Chowkidar', 'vacancies' => '19 (Head Office: 02, Districts: 17)', 'education' => 'Preferably Literate', 'scale' => 'BPS-01', 'location' => 'Karachi & District Offices', 'age_limit' => '18 - 30 Years'],
                ],
                'documents_required'  => [
                    'Separate written application for each post applied for',
                    'Attested copies of Primary pass certificate / School Leaving Certificate (where applicable)',
                    'Attested copy of valid Computerized National Identity Card (CNIC)',
                    'Attested copies of Domicile Certificate and PRC (Form-D) of relevant Sindh district',
                    'Valid Driving License (Motorcycle or LTV) with log book record for Driver and Dispatch Rider candidates',
                    'Experience Certificate (for Driver and Sanitary Worker applicants)',
                    'Recent passport-size photographs with blue background',
                    'Disability / Minority Certificate from authorized board (for reserved quota applicants)',
                    'Departmental NOC (No Objection Certificate) for candidates already employed in government service',
                ],
                'mistakes_to_avoid'   => [
                    'Do not miss your district interview schedule — walk-in interviews are conducted strictly district-wise.',
                    'Candidates must submit a separate written application with attested documents for each post applied for.',
                    'Do not bring un-attested photocopies of certificates; all documents must be properly verified and attested.',
                    'No TA/DA will be admissible for appearing in walk-in interviews at High Court Karachi or District offices.',
                    'Government servants must apply through proper channel with official NOC.',
                ],
                'selection_process'   => [
                    'Submission of written application and attested document dossier at the walk-in interview desk.',
                    'Head Office Interviews (Karachi): Held at Prosecutor General Sindh, 4th Floor, High Court of Sindh, Karachi from October 19 to 24, 2026.',
                    'District Public Prosecutor Office Interviews: Held at concerned District offices from October 26 to 31, 2026.',
                    'Practical driving and log book verification test for Driver and Dispatch Rider applicants.',
                    'Successful and rejected candidates will be informed via official telephonic call within 15 days after the walk-in interview.',
                    'Final merit list compilation and appointment letters issued in accordance with Sindh Government recruitment policy.',
                ],
                'how_to_apply_urdu'   => '<ol style="list-style-position: inside; padding-right: 15px;">
                    <li>امیدوار اپنی درخواست (Application) کے ہمراہ تعلیمی اسناد، سی این آئی سی، ڈومیسائل، پی آر سی (PRC) کی تصدیق شدہ کاپیاں اور پاسپورٹ سائز تصاویر تیار کریں۔</li>
                    <li>اگر ایک سے زائد پوسٹوں پر اپلائی کرنا ہو تو ہر اسامی کے لیے الگ الگ درخواست اور تصدیق شدہ دستاویزات لانا لازمی ہے۔</li>
                    <li><strong>ہیڈ آفس کراچی کی اسامیوں کے لیے انٹرویو:</strong> 19 تا 24 اکتوبر 2026 کو پراسیکیوٹر جنرل سندھ، چوتھی منزل، ایڈمنسٹریشن بلاک، ہائی کورٹ آف سندھ، کراچی میں ہوں گے۔</li>
                    <li><strong>ضلعی پبلک پراسیکیوٹر دفاتر کے لیے انٹرویو:</strong> 26 تا 31 اکتوبر 2026 کو متعلقہ ضلع کے پبلک پراسیکیوٹر دفتر میں ہوں گے۔</li>
                    <li>اپنے ضلع کے مقررہ دن اور تاریخ کے مطابق تمام اصل کاغذات اور تصدیق شدہ کاپیاں لے کر انٹرویو کے لیے حاضر ہوں۔</li>
                    <li>منتخب اور مسترد ہونے والے امیدواروں کو واک ان انٹرویو کے 15 دن کے اندر اندر سرکاری فون کال کے ذریعے مطلع کیا جائے گا۔</li>
                </ol>',
            ];
        } elseif ($type === 'uoc') {
            $title = 'University of Chakwal UoC Jobs 2026 - Advertisement No 06/2026';
            $slugKey = Str::slug('University of Chakwal UoC Jobs 2026 Advertisement No 06 2026');
            $jobData = [
                'posted_on'           => 'October 07, 2026',
                'city'                => 'Chakwal, Punjab',
                'education'           => 'PhD / MS / M.Phil / Master / BS / B.Sc Civil / MBBS / MBA / ACCA',
                'vacancies'           => '21 Positions',
                'apply_method'        => 'Online via UoC Portal + Hard Copy Courier',
                'organization'        => 'University of Chakwal (UoC)',
                'salary'              => 'BPS-17 to BPS-20 (As per Govt / UoC Scale)',
                'official_source_url' => 'https://uoc.edu.pk/jobs.php',
                'official_apply_url'  => 'https://uoc.edu.pk/jobs.php',
                'last_checked'        => 'October 07, 2026',
                'deadline'            => 'October 07, 2026',
                'also_apply_title'    => 'PPSC Jobs 2026 - Punjab Public Service Commission Advertisement No 18',
                'also_apply_url'      => '/ppsc-jobs-2026-punjab-public-service-commission-advertisement-no-18',
                'job_description'     => '<p>The <strong>University of Chakwal (UoC)</strong> has published official <strong>Advertisement No: 06/2026</strong> (IPL-9880) inviting online applications from qualified candidates holding a <strong>Punjab Domicile</strong>. The announced vacancies include statutory executive leadership roles such as <em>Controller of Examinations (BS-20)</em> and <em>Registrar (BS-20)</em>, alongside several administrative officer roles spanning BS-17 to BS-19 on regular and contract bases.</p><p>Eligible candidates meeting the Higher Education Commission (HEC) and Pakistan Engineering Council (PEC) qualification standards must apply online through the official University of Chakwal portal. Printed application dossiers accompanied by required certificates and bank challan receipts must reach the Registrar Office by courier.</p>',
                'who_can_apply'       => '<p>Candidates having valid <strong>Punjab Domicile</strong> meeting the HEC recognized educational qualification, required post-qualification experience, and prescribed age limits are eligible. Both fresh and experienced male, female, and minority candidates can apply as per Government of Punjab quota policies. Foreign degree holders must submit HEC/IBCC equivalence certificates.</p>',
                'eligibility_criteria'=> '<ul>
                    <li><strong>Domicile:</strong> Punjab Province Domicile holders only.</li>
                    <li><strong>Education:</strong> PhD, MS/M.Phil, Master’s/BS (2nd division), B.Sc Civil Engineering, MBBS, MBA, M.Com, ACCA, ACMA, or equivalent from HEC recognized institutes.</li>
                    <li><strong>Age Limit:</strong> 21 to 50 Years (Upper age relaxation applicable as per Punjab Govt policy).</li>
                    <li><strong>Professional Registration:</strong> PEC registration required for Civil Engineering posts; PMC registration for Medical Officer.</li>
                </ul>',
                'vacant_positions'    => [
                    ['name' => 'Controller of Examinations', 'vacancies' => '01', 'education' => 'PhD + 8 yrs exp OR MS/M.Phil + 10 yrs exp OR Master/BS + 12 yrs exp', 'scale' => 'BS-20 (Contract)', 'location' => 'Chakwal', 'age_limit' => '40-50 Years'],
                    ['name' => 'Registrar', 'vacancies' => '01', 'education' => 'PhD + 8 yrs exp OR MS/M.Phil + 10 yrs exp OR Master/BS + 12 yrs exp', 'scale' => 'BS-20 (Contract)', 'location' => 'Chakwal', 'age_limit' => '40-50 Years'],
                    ['name' => 'Project Director', 'vacancies' => '01', 'education' => 'B.Sc Civil Engineering (PEC Registered) + 12 yrs exp', 'scale' => 'BS-19', 'location' => 'Chakwal', 'age_limit' => '35-50 Years'],
                    ['name' => 'Deputy Director (Sports)', 'vacancies' => '01', 'education' => 'Master/BS Sports Sciences / Physical Education + 5 yrs exp', 'scale' => 'BS-18', 'location' => 'Chakwal', 'age_limit' => '25-45 Years'],
                    ['name' => 'Deputy Director (Press, Media & Publication)', 'vacancies' => '01', 'education' => 'MS/M.Phil + 3 yrs exp OR Master/BS Mass Comm + 5 yrs exp', 'scale' => 'BS-18', 'location' => 'Chakwal', 'age_limit' => '25-45 Years'],
                    ['name' => 'Deputy Controller of Examinations', 'vacancies' => '01', 'education' => 'MS/M.Phil + 3 yrs exp OR Master/BS + 5 yrs exp', 'scale' => 'BS-18', 'location' => 'Chakwal', 'age_limit' => '25-45 Years'],
                    ['name' => 'Deputy Registrar', 'vacancies' => '01', 'education' => 'MS/M.Phil + 3 yrs exp OR Master/BS + 5 yrs exp', 'scale' => 'BS-18', 'location' => 'Chakwal', 'age_limit' => '25-45 Years'],
                    ['name' => 'Deputy Treasurer', 'vacancies' => '02', 'education' => 'MS/M.Phil Finance/M.Com/ACCA/ACMA + 3-5 yrs exp', 'scale' => 'BS-18', 'location' => 'Chakwal', 'age_limit' => '25-45 Years'],
                    ['name' => 'Senior Press Manager', 'vacancies' => '01', 'education' => 'Master/BS Mass Comm / Media Studies + 5 yrs exp', 'scale' => 'BS-18', 'location' => 'Chakwal', 'age_limit' => '25-45 Years'],
                    ['name' => 'Assistant Controller of Examinations', 'vacancies' => '02', 'education' => 'Master’s degree or BS (2nd Division)', 'scale' => 'BS-17', 'location' => 'Chakwal', 'age_limit' => '21-35 Years'],
                    ['name' => 'Assistant Registrar', 'vacancies' => '02', 'education' => 'MBA / M.Com / MCS / ACMA / ACCA / Master / BS', 'scale' => 'BS-17', 'location' => 'Chakwal', 'age_limit' => '21-35 Years'],
                    ['name' => 'Assistant Treasurer', 'vacancies' => '02', 'education' => 'MBA / M.Com / ACMA / ACCA / M.Sc Economics', 'scale' => 'BS-17', 'location' => 'Chakwal', 'age_limit' => '21-35 Years'],
                    ['name' => 'Assistant Director (Academics)', 'vacancies' => '01', 'education' => 'Master’s degree or BS (2nd Division)', 'scale' => 'BS-17', 'location' => 'Chakwal', 'age_limit' => '21-35 Years'],
                    ['name' => 'Assistant Director (Purchase & Store)', 'vacancies' => '01', 'education' => 'MBA / M.Com / ACMA / ACCA / M.Sc Economics', 'scale' => 'BS-17', 'location' => 'Chakwal', 'age_limit' => '21-35 Years'],
                    ['name' => 'Assistant Director (Planning & Development)', 'vacancies' => '01', 'education' => 'Master / BS in Economics, Engineering, or Mgmt Sciences', 'scale' => 'BS-17', 'location' => 'Chakwal', 'age_limit' => '21-35 Years'],
                    ['name' => 'Assistant Engineer (Civil)', 'vacancies' => '01', 'education' => 'B.Sc Civil Engineering (PEC Registered)', 'scale' => 'BS-17', 'location' => 'Chakwal', 'age_limit' => '21-35 Years'],
                    ['name' => 'Assistant Librarian', 'vacancies' => '01', 'education' => 'Master / BS Library & Information Science', 'scale' => 'BS-17', 'location' => 'Chakwal', 'age_limit' => '21-35 Years'],
                    ['name' => 'Estate Officer', 'vacancies' => '01', 'education' => 'Master / BS + 2 yrs relevant experience', 'scale' => 'BS-17', 'location' => 'Chakwal', 'age_limit' => '21-35 Years'],
                    ['name' => 'Medical Officer', 'vacancies' => '01', 'education' => 'MBBS (1st Div) + PMC Registration + 1 yr House Job', 'scale' => 'BS-17', 'location' => 'Chakwal', 'age_limit' => '21-35 Years'],
                    ['name' => 'Security Officer', 'vacancies' => '01', 'education' => 'Retired Commissioned Armed Forces Officer', 'scale' => 'BS-17', 'location' => 'Chakwal', 'age_limit' => '21-35 Years'],
                    ['name' => 'Personal Secretary', 'vacancies' => '01', 'education' => 'Master/BS + 100 wpm shorthand & 50 wpm typing speed', 'scale' => 'BS-17', 'location' => 'Chakwal', 'age_limit' => '21-35 Years'],
                ],
                'documents_required'  => [
                    'Duly signed online application form printout (02 hard copy sets)',
                    'Original paid Bank of Punjab system-generated fee challan',
                    'Attested copy of Punjab Domicile Certificate & CNIC',
                    'Attested copies of Educational Degrees, Transcripts, and Result Cards',
                    'Experience certificates issued by authorized appointing authority',
                    'HEC / IBCC Equivalence Certificate (for foreign qualification holders)',
                    'NOC / Departmental Permission Certificate (for Govt employees)',
                    'Detailed updated Resume / CV (preferably double-sided print)',
                ],
                'mistakes_to_avoid'   => [
                    'Do not miss the online application cutoff date (October 07, 2026).',
                    'Do not send incomplete dossiers or missing fee challan copies.',
                    'Do not submit private experience without valid entity registration proof.',
                    'Do not send hard copies late (must reach by October 08, 2026, 04:00 PM).',
                    'Do not pay fee on unverified links (fee must be paid via Bank of Punjab challan).',
                ],
                'selection_process'   => [
                    'Online Registration on official UoC portal (uoc.edu.pk/jobs.php).',
                    'Hard copy submission of 02 dossier sets via registered courier.',
                    'Scrutiny of documents and eligibility verification by UoC committee.',
                    'Screening MCQ Test (if required by University competent authority).',
                    'Shortlisting and official interview call for eligible candidates.',
                ],
                'how_to_apply_urdu'   => '<ol style="list-style-position: inside; padding-right: 15px;">
                    <li>سب سے پہلے آفیشل پورٹل <strong>uoc.edu.pk/jobs.php</strong> پر جا کر آن لائن فارم پر کریں۔</li>
                    <li>آن لائن فارم جمع کروانے کے بعد سسٹم سے جنریٹ شدہ چالان فارم بینک آف پنجاب (BOP) کی کسی بھی برانچ میں جمع کروائیں۔</li>
                    <li>رجسٹرار اور کنٹرولر امتحانات کی اسامیوں کے لیے پروسیسنگ فیس 5,000 روپے جبکہ دیگر انتظامی اسامیوں (BS-17 تا BS-19) کے لیے 3,000 روپے ہے۔</li>
                    <li>آن لائن فارم کا پرنٹ نکال کر ساتھ اصل چالان، ڈومیسائل، قومی شناختی کارڈ، تعلیمی اسناد اور تجربہ کے سرٹیفکیٹس کے دو (02) مکمل ڈوسیئر سیٹ تیار کریں۔</li>
                    <li>تیار شدہ 02 سیٹ مورخہ 08 اکتوبر 2026 شام 04:00 بجے تک رجسٹرار آفس، یونیورسٹی آف چکوال، سٹی کیمپس، تلہ گنگ روڈ، چکوال کو بذریعہ رجسٹرڈ کورئیر/ڈاک ارسال کریں۔</li>
                </ol>',
            ];
        } elseif ($type === 'mohmand') {
            $title = 'Cadet College Mohmand Jobs 2026 - Teaching & Admin Staff 55+ Vacancies';
            $slugKey = Str::slug('Cadet College Mohmand Jobs 2026 Teaching Admin Staff 55 Vacancies');
            $jobData = [
                'posted_on'           => 'October 06, 2026',
                'city'                => 'Mohmand / Peshawar, Khyber Pakhtunkhwa',
                'education'           => 'MBBS / Master / MSc / BA / BSc / FA / FSc / Matric / Middle / Primary',
                'vacancies'           => '55 Positions (22 Categories)',
                'apply_method'        => 'By Post / Courier to PO Box No. 62 GPO Peshawar',
                'organization'        => 'Cadet College Mohmand (Govt of KPK)',
                'salary'              => 'BPS-02 to BPS-18 (As per KPK Govt / College Rules)',
                'official_source_url' => 'https://ccmohmand.edu.pk',
                'official_apply_url'  => 'mailto:ccmohmand@gmail.com',
                'last_checked'        => 'October 06, 2026',
                'deadline'            => 'October 30, 2026',
                'ad_image_url'        => '',
                'also_apply_title'    => 'PPSC Jobs 2026 - Punjab Public Service Commission Advertisement No 18',
                'also_apply_url'      => '/ppsc-jobs-2026-punjab-public-service-commission-advertisement-no-18',
                'job_description'     => '<p>The <strong>Government of Khyber Pakhtunkhwa</strong> has announced official recruitment for <strong>Cadet College Mohmand Jobs 2026</strong> for teaching and administrative staff. Applications are invited from eligible candidates initially for a one-year probation period, extendable or convertible to regular posts based on satisfactory performance.</p><p>Vacancies are open across 22 categories including <strong>Security Officer (BPS-18)</strong>, <strong>RMO / Doctor (BPS-18)</strong>, <strong>Lecturers (BPS-17)</strong> in Physics, Chemistry, Biology, English, Urdu, Islamiat & Computer Science, <strong>Psychologist (BPS-17)</strong>, <strong>Hostel Supervisor (BPS-16)</strong>, <strong>PT Instructor (BPS-14)</strong>, <strong>Clerical & Technical Staff</strong>, and support personnel. Complete applications with bio-data, CV, and attested documents must reach <strong>PO Box No. 62 GPO, Peshawar</strong> by <strong>October 30, 2026</strong>.</p>',
                'who_can_apply'       => '<p>Qualified candidates possessing relevant academic degrees (MBBS, Masters, MSc Clinical Psychology, BA/BSc, FA/FSc, Matric, Middle, Primary) with requisite practical experience are eligible to apply. Preference will be given to retired personnel from the Armed Forces / Army JCOs / NCOs for security, administration, hostel supervision, PT drill instruction, and clerical roles. Applicants must meet the prescribed age limits ranging from 30 to 50 years depending on the post grade.</p>',
                'eligibility_criteria'=> '<ul>
                    <li><strong>Security Officer (BPS-18):</strong> BA / equivalent with Retired Major / Captain background from Armed Forces. Max Age: 50 years.</li>
                    <li><strong>RMO / Doctor (BPS-18):</strong> MBBS / MD with minimum 5 years experience as a Doctor in a renowned hospital. Max Age: 50 years.</li>
                    <li><strong>Lecturers (BPS-17):</strong> Master / equivalent in Physics, Chemistry, Biology, English, Urdu, Islamiat, or Computer Science. Preferably 5 years teaching experience in a Cadet College or renowned institution. Max Age: 35 years.</li>
                    <li><strong>Psychologist (BPS-17):</strong> MSc Clinical Psychology with preferably 5 years experience in Cadet College / institution. Max Age: 35 years.</li>
                    <li><strong>Hostel Supervisor (BPS-16):</strong> BA / BSc with 3 years relevant experience (Army retired preferred). Max Age: 45 years.</li>
                    <li><strong>PT Instructor (BPS-14):</strong> Retired Army personnel qualified in PT / Drill Course with min 2 years experience at Army Training Institution. Max Age: 48 years.</li>
                    <li><strong>Clerical & NCO Cadre (BPS-11 to 13):</strong> FA / FSc with 3 years experience for Adm NCO, Catering NCO, and Junior Clerk positions. Max Age: 45 years.</li>
                    <li><strong>Technical & Support Staff (BPS-02 to 07):</strong> Driver (Middle + HTV/LTV), CCTV Operator (Matric + Diploma/Exp), Storeman, Plumber, Cook, Nan Bai, Sanitary Worker, Naib Qasid, Class Attendant, Mess Waiter, Dish Washer, House Bearer, and Ground Man. Max Age: 30 to 40 years.</li>
                </ul>',
                'vacant_positions'    => [
                    ['name' => 'Security Officer', 'vacancies' => '01', 'education' => 'BA / Equivalent (Retired Major / Captain)', 'scale' => 'BPS-18', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 50 Years'],
                    ['name' => 'RMO / Doctor', 'vacancies' => '01', 'education' => 'MBBS / MD (Min 5 Yrs Exp)', 'scale' => 'BPS-18', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 50 Years'],
                    ['name' => 'Lecturers (Physics, Chemistry, Biology, English, Urdu, Islamiat, CS)', 'vacancies' => '07', 'education' => 'Master / Equivalent in Subject (5 Yrs Exp Pref)', 'scale' => 'BPS-17', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 35 Years'],
                    ['name' => 'Psychologist', 'vacancies' => '01', 'education' => 'MSc Clinical Psychology (5 Yrs Exp Pref)', 'scale' => 'BPS-17', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 35 Years'],
                    ['name' => 'Hostel Supervisor', 'vacancies' => '01', 'education' => 'BA / BSc + 3 Yrs Exp (Retired Army Pref)', 'scale' => 'BPS-16', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 45 Years'],
                    ['name' => 'PT Instructor', 'vacancies' => '01', 'education' => 'Retired Army (PT / Drill Course Qualified + 2 Yrs Exp)', 'scale' => 'BPS-14', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 48 Years'],
                    ['name' => 'Adm NCO', 'vacancies' => '01', 'education' => 'FA / FSc + 3 Yrs Exp (Retired Army JCO/NCO Pref)', 'scale' => 'BPS-13', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 45 Years'],
                    ['name' => 'Catering NCO', 'vacancies' => '01', 'education' => 'FA / FSc + 3 Yrs Exp (Retired Army Pref)', 'scale' => 'BPS-13', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 45 Years'],
                    ['name' => 'Junior Clerk', 'vacancies' => '01', 'education' => 'FA / FSc + 3 Yrs Exp (Retired Army Pref)', 'scale' => 'BPS-11', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 45 Years'],
                    ['name' => 'Driver', 'vacancies' => '01', 'education' => 'Middle + HTV/LTV License + Repair Exp', 'scale' => 'BPS-07', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 40 Years'],
                    ['name' => 'CCTV Operator', 'vacancies' => '01', 'education' => 'Matric + 3 Yrs Exp (Diploma & Army Pref)', 'scale' => 'BPS-06', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 35 Years'],
                    ['name' => 'Storeman', 'vacancies' => '01', 'education' => 'Matric + 3 Yrs Exp (Retired Army Pref)', 'scale' => 'BPS-06', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 40 Years'],
                    ['name' => 'Plumber', 'vacancies' => '01', 'education' => 'Middle + 3 Yrs Exp', 'scale' => 'BPS-06', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 35 Years'],
                    ['name' => 'Cook', 'vacancies' => '04', 'education' => 'Primary + 2 Yrs Exp', 'scale' => 'BPS-04', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 30 Years'],
                    ['name' => 'Nan Bai', 'vacancies' => '03', 'education' => 'Primary + 2 Yrs Exp', 'scale' => 'BPS-04', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 30 Years'],
                    ['name' => 'Sanitary Worker', 'vacancies' => '05', 'education' => 'Middle + 2 Yrs Exp', 'scale' => 'BPS-03', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 30 Years'],
                    ['name' => 'Naib Qasid', 'vacancies' => '01', 'education' => 'Middle + 2 Yrs Exp', 'scale' => 'BPS-02', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 30 Years'],
                    ['name' => 'Class Room Attendant', 'vacancies' => '04', 'education' => 'Matric + 2 Yrs Exp', 'scale' => 'BPS-02', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 35 Years'],
                    ['name' => 'Mess Waiter', 'vacancies' => '06', 'education' => 'Middle + 2 Yrs Exp', 'scale' => 'BPS-02', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 35 Years'],
                    ['name' => 'Dish Washer', 'vacancies' => '01', 'education' => 'Middle + 2 Yrs Exp', 'scale' => 'BPS-02', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 30 Years'],
                    ['name' => 'House Bearer', 'vacancies' => '10', 'education' => 'Middle + 2 Yrs Exp', 'scale' => 'BPS-02', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 30 Years'],
                    ['name' => 'Ground man', 'vacancies' => '03', 'education' => 'Middle + 2 Yrs Exp', 'scale' => 'BPS-02', 'location' => 'Cadet College Mohmand', 'age_limit' => 'Max 35 Years'],
                ],
                'documents_required'  => [
                    'Detailed Bio-Data / Curriculum Vitae (CV) with active contact number and email',
                    'Attested photocopies of all Educational Certificates, Degrees & Transcripts',
                    'Attested copy of Computerized CNIC and Domicile Certificate',
                    'Attested photocopies of Experience Certificates / Military Discharge Book (where applicable)',
                    'Valid HTV / LTV Driving License copy (for Driver position)',
                    'Latest passport-size photographs with blue background',
                ],
                'mistakes_to_avoid'   => [
                    'Do not submit applications after the deadline of October 30, 2026.',
                    'Ensure the application envelope is clearly marked with the name of the post applied for.',
                    'Do not send un-attested copies of certificates or incomplete bio-data forms.',
                    'No TA/DA will be paid for appearing in test/interview at Cadet College Mohmand.',
                ],
                'selection_process'   => [
                    'Receipt of applications duly marked for the post at PO Box No. 62 GPO, Peshawar before October 30, 2026.',
                    'Initial document verification and shortlisting of qualified candidates.',
                    'Interview conducted by the Selection Board at Cadet College Mohmand.',
                    'Final appointment initially for 1-year probation period (extendable or convertible to regular status).',
                ],
                'how_to_apply_urdu'   => '<ol>
                    <li>اپنی تعلیمی اسناد، تجربہ سرٹیفکیٹس، سی این آئی سی کاپی اور تازہ ترین تصاویر کی تصدیق شدہ (Attested) کاپیاں تیار کریں۔</li>
                    <li>ایک مکمل سی وی (Bio-Data / CV) تیار کریں اور درخواست کے لفافے کے اوپر مطلوبہ اسامی (Post) کا نام واضح تحریر کریں۔</li>
                    <li>درخواست بذریعہ پوسٹ/کوریئر پتے <strong>"PO Box No. 62 GPO, Peshawar"</strong> پر ارسال کریں۔</li>
                    <li>درخواست موصول ہونے کی حتمی تاریخ <strong>30 اکتوبر 2026</strong> ہے۔</li>
                    <li>صرف شارٹ لسٹ شدہ امیدواروں کو کیڈٹ کالج مہمند میں انٹرویو کے لیے بلایا جائے گا۔ (کوئی TA/DA نہیں دیا جائے گا)۔</li>
                    <li>کسی بھی رہنمائی کے لیے فون نمبر <strong>0924-29341</strong> یا ای میل <strong>ccmohmand@gmail.com</strong> پر رابطہ کریں۔</li>
                </ol>',
            ];
        } elseif ($type === 'cpsp') {
            $title = 'CPSP Jobs 2026 Lahore - College of Physicians and Surgeons Pakistan Opportunities';
            $slugKey = Str::slug('CPSP Jobs 2026 Lahore College of Physicians and Surgeons Pakistan Opportunities');
            $jobData = [
                'posted_on'           => 'October 05, 2026',
                'city'                => 'Lahore Based (Regional Office)',
                'education'           => 'Master / BS Computer Science / Graduate / DAE Electrical / Matric / Middle',
                'vacancies'           => 'Multiple Positions (10 Categories)',
                'apply_method'        => 'Email CV to jobs@cpsp.edu.pk or Mail to HR Dept Karachi',
                'organization'        => 'College of Physicians and Surgeons Pakistan (CPSP)',
                'salary'              => 'Competitive Package + Medical & Provident Fund (As per CPSP Rules)',
                'official_source_url' => 'https://cpsp.edu.pk',
                'official_apply_url'  => 'mailto:jobs@cpsp.edu.pk',
                'last_checked'        => 'October 05, 2026',
                'deadline'            => 'October 12, 2026',
                'ad_image_url'        => '/storage/news/cpsp-jobs-2026-advertisement.jpg',
                'also_apply_title'    => 'PPSC Jobs 2026 - Punjab Public Service Commission Advertisement No 18',
                'also_apply_url'      => '/ppsc-jobs-2026-punjab-public-service-commission-advertisement-no-18',
                'job_description'     => '<p>The <strong>College of Physicians and Surgeons Pakistan (CPSP)</strong>, a premier postgraduate medical institution established in 1962, has officially announced new <strong>CPSP Jobs 2026 Lahore</strong> across multiple departments. Qualified, energetic, and experienced candidates seeking <strong>medical education jobs in Pakistan</strong> or administrative and technical roles in Lahore are invited to apply for these positions.</p><p>Vacancies are open for Lahore-based operational roles including Assistant Manager (Department of Medical Education), Assistant Manager (IT), Office Assistant, Generator Operator, Electrician, Plumber, Driver, Office Boy, Room Boy, and Security Guard. Interested applicants must submit their updated resume along with a recent passport-size photograph by <strong>October 12, 2026</strong> via email at <a href="mailto:jobs@cpsp.edu.pk">jobs@cpsp.edu.pk</a> or by post to the CPSP HR Department in DHA Phase II, Karachi.</p>',
                'who_can_apply'       => '<p>Male and female candidates holding relevant academic degrees (Masters, BS CS/IT, Graduation, DAE, Matric, Middle) with required technical skills and practical work experience in their respective trades are eligible to apply. Applicants must be physically fit, reliable, and willing to serve at the CPSP Regional Office in Lahore.</p>',
                'eligibility_criteria'=> '<ul>
                    <li><strong>Assistant Manager DME:</strong> Graduate or preferably Master in relevant field with hands-on experience in medical education, academic coordination, curriculum activities, and proficiency in MS Office.</li>
                    <li><strong>Assistant Manager IT:</strong> BS in Computer Science or IT with 2 to 3 years experience in Cisco Layer 2/3 devices, Linux servers, Video Conferencing, VoIP systems, TCP/IP (DNS, DHCP), desktop support, and conducting IT workshops.</li>
                    <li><strong>Office Assistant:</strong> Graduation with relevant office work experience, computer literacy, and strong communication skills.</li>
                    <li><strong>Generator Operator:</strong> Matric with DAE / Certificate in Electrical or Mechanical Engineering from a recognized institute with experience operating and maintaining Diesel Generators (Siemens / Caterpillar).</li>
                    <li><strong>Electrician:</strong> Matric with DAE in Electrical Engineering and relevant practical experience.</li>
                    <li><strong>Plumber:</strong> Middle pass with practical experience in pipeline installation, sanitary fittings, leak repairs, and valve maintenance.</li>
                    <li><strong>Driver:</strong> Middle pass holding a valid HTV or LTV driving license with clean driving history and relevant experience.</li>
                    <li><strong>Support Staff (Office Boy, Room Boy, Security Guard):</strong> Energetic candidates able to read and write; Room Boys responsible for room cleaning/servicing; Security Guards preferably with licensed arms.</li>
                </ul>',
                'vacant_positions'    => [
                    [
                        'name'      => 'Assistant Manager (DME)',
                        'vacancies' => '01',
                        'education' => 'Graduate / Master (Medical Education / Relevant)',
                        'scale'     => 'CPSP Managerial Cadre',
                        'location'  => 'Lahore',
                        'age_limit' => '25 - 40 Years',
                    ],
                    [
                        'name'      => 'Assistant Manager (IT)',
                        'vacancies' => '01',
                        'education' => 'BS Computer Science / Information Technology',
                        'scale'     => 'CPSP Technical Cadre (2-3 Yrs Exp)',
                        'location'  => 'Lahore',
                        'age_limit' => '24 - 38 Years',
                    ],
                    [
                        'name'      => 'Office Assistant',
                        'vacancies' => 'Multiple',
                        'education' => 'Graduation (BA / B.Sc / B.Com / BBA)',
                        'scale'     => 'CPSP Staff Cadre',
                        'location'  => 'Lahore',
                        'age_limit' => '20 - 35 Years',
                    ],
                    [
                        'name'      => 'Generator Operator',
                        'vacancies' => '01',
                        'education' => 'Matric + DAE Electrical / Mechanical',
                        'scale'     => 'Technical Staff',
                        'location'  => 'Lahore',
                        'age_limit' => '22 - 40 Years',
                    ],
                    [
                        'name'      => 'Electrician',
                        'vacancies' => '01',
                        'education' => 'Matric + DAE Electrical',
                        'scale'     => 'Technical Staff',
                        'location'  => 'Lahore',
                        'age_limit' => '20 - 40 Years',
                    ],
                    [
                        'name'      => 'Plumber',
                        'vacancies' => '01',
                        'education' => 'Minimum Middle with Sanitary Experience',
                        'scale'     => 'Technical Staff',
                        'location'  => 'Lahore',
                        'age_limit' => '20 - 42 Years',
                    ],
                    [
                        'name'      => 'Driver (HTV / LTV)',
                        'vacancies' => 'Multiple',
                        'education' => 'Minimum Middle + Valid HTV/LTV License',
                        'scale'     => 'Transport Staff',
                        'location'  => 'Lahore',
                        'age_limit' => '22 - 45 Years',
                    ],
                    [
                        'name'      => 'Office Boy',
                        'vacancies' => 'Multiple',
                        'education' => 'Matric (Energetic & Reliable)',
                        'scale'     => 'Support Staff',
                        'location'  => 'Lahore',
                        'age_limit' => '18 - 30 Years',
                    ],
                    [
                        'name'      => 'Room Boy',
                        'vacancies' => 'Multiple',
                        'education' => 'Literate (Ability to Read & Write)',
                        'scale'     => 'Guest House Staff',
                        'location'  => 'Lahore',
                        'age_limit' => '18 - 35 Years',
                    ],
                    [
                        'name'      => 'Security Guard',
                        'vacancies' => 'Multiple',
                        'education' => 'Literate (Licensed Arm Preferred)',
                        'scale'     => 'Security Cadre',
                        'location'  => 'Lahore',
                        'age_limit' => '25 - 48 Years',
                    ],
                ],
                'documents_required'  => [
                    'Updated Curriculum Vitae (CV) with recent passport-size photograph attached',
                    'Attested copies of Educational Degrees / Certificates (Matric, Inter, Graduation, Master, BS CS)',
                    'Diploma in Electrical / Mechanical / Sanitary (DAE / Certificate) where applicable',
                    'Valid HTV / LTV Driving License copy (for Driver position)',
                    'Valid CNIC copy and Domicile certificate',
                    'Experience certificates proving relevant work history in institutional maintenance or IT management',
                ],
                'mistakes_to_avoid'   => [
                    'Do not send resumes without attaching a recent passport-size photograph.',
                    'Do not submit applications after the deadline of October 12, 2026.',
                    'Ensure the subject line of your email clearly states the post applied for (e.g., "Application for Assistant Manager IT - Lahore").',
                    'Do not send incomplete documents or unreadable scanned copies of degrees.',
                ],
                'selection_process'   => [
                    'Receipt and initial screening of resumes sent via email (jobs@cpsp.edu.pk) or postal mail before October 12, 2026.',
                    'Shortlisting of eligible candidates based on academic qualification and years of relevant experience.',
                    'Written / Technical test or practical skill demonstration (for IT, Technical, and Driving positions).',
                    'Formal interview conducted by the CPSP HR Selection Board at Lahore / Karachi office.',
                    'Final offer letter issuance and medical fitness evaluation.',
                ],
                'how_to_apply_urdu'   => '<ol>
                    <li>اپنی اپ ڈیٹ شدہ سی وی (CV) اور حالیہ پاسپورٹ سائز تصویر تیار کریں۔</li>
                    <li>درخواست آن لائن ای میل کے ذریعے <strong>jobs@cpsp.edu.pk</strong> پر ارسال کریں۔ (ای میل کے سبجیکٹ میں پوسٹ کا نام ضرور لکھیں)۔</li>
                    <li>یا اپنی سی وی بحساب HR Department, College of Physicians & Surgeons Pakistan, 7th Central Street, D.H.A Phase II, Karachi پر بذریعہ ڈاک/کوریئر بھیجیں۔</li>
                    <li>درخواست جمع کروانے کی آخری تاریخ <strong>12 اکتوبر 2026</strong> ہے۔</li>
                    <li>صرف شارٹ لسٹ شدہ امیدواران کو ٹیسٹ/انٹرویو کے لیے کال کی جائے گی۔</li>
                </ol>',
            ];
        } elseif ($type === 'nadra') {
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

        if (! class_exists(\Botble\Blog\Models\Post::class)) {
            $this->error('Botble Blog plugin Post model not found.');
            return self::FAILURE;
        }

        $category = null;
        if (class_exists(\Botble\Blog\Models\Category::class)) {
            $category = \Botble\Blog\Models\Category::query()->where('name', 'Federal Jobs')->first()
                ?: \Botble\Blog\Models\Category::query()->where('name', 'Jobs')->first()
                ?: \Botble\Blog\Models\Category::query()->first();
        }

        $post = \Botble\Blog\Models\Post::query()->updateOrCreate(
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
