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
    protected $signature = 'cms:create-fresh-job';

    protected $description = 'Create a brand new fresh job post using the updated 11-section template engine';

    public function handle(): int
    {
        $this->info('Creating fresh new job post using 11-section JobPostTemplateService...');

        $title = 'PPSC Jobs 2026 - Punjab Public Service Commission Advertisement No 18';
        $slugKey = Str::slug('PPSC Jobs 2026 Punjab Public Service Commission Advertisement No 18');

        $jobData = [
            'posted_on' => 'October 02, 2026',
            'city' => 'Lahore, Rawalpindi, Multan & Punjab',
            'education' => 'Bachelor / Master / LLB',
            'vacancies' => '245 Positions',
            'apply_method' => 'Online via PPSC Portal',
            'organization' => 'Punjab Public Service Commission (PPSC)',
            'salary' => 'BPS-16 to BPS-18 (Rs. 60,000 - 120,000/Month)',
            'official_source_url' => 'https://www.ppsc.gop.pk',
            'official_apply_url' => 'https://www.ppsc.gop.pk',
            'last_checked' => 'October 02, 2026',
            'deadline' => 'October 30, 2026',
            'ad_image_url' => '/storage/news/2.jpg',
            'also_apply_title' => 'FPSC Jobs 2026 - Federal Public Service Commission Consolidated Advt',
            'also_apply_url' => '/category/fpsc',
            'job_description' => '<p>The <strong>Punjab Public Service Commission (PPSC)</strong> has officially published Advertisement No. 18 for 2026, inviting online applications for <strong>245 permanent and contract posts</strong> across multiple Punjab government departments including Police, Housing, Agriculture, and Disaster Management.</p><p>Male, female, and transgender candidates holding Punjab domicile with relevant Bachelor, Master, or LLB qualifications are eligible to apply through the official PPSC online portal before the closing date.</p>',
            'who_can_apply' => '<p>Candidates having Punjab domicile can apply. Male, female, and transgender applicants meeting the required academic qualification, age limit, and physical standards (where applicable) are eligible. General age relaxation of up to 5 years for males and 8 years for females is included in accordance with Punjab Government notification policy.</p>',
            'eligibility_criteria' => '<ul>
                <li><strong>Education:</strong> Graduate / Master / LLB from a HEC recognized university in relevant discipline.</li>
                <li><strong>Age Limit:</strong> 21 to 35 years for Assistant Director posts; 20 to 28 years for Sub-Inspector posts (General relaxation applicable).</li>
                <li><strong>Domicile:</strong> Any district of Punjab province.</li>
                <li><strong>Registration Fee:</strong> Rs. 600 PPSC test fee payable via JazzCash, EasyPaisa, or ATM/Internet Banking using PSID.</li>
            </ul>',
            'vacant_positions' => [
                [
                    'name' => 'Assistant Director (Planning & Development)',
                    'vacancies' => '25',
                    'education' => 'Master / BS (4-Years) Economics / Stat',
                    'scale' => 'BS-17 (Regular)',
                    'location' => 'Lahore',
                    'age_limit' => '21 - 33 Years',
                ],
                [
                    'name' => 'Sub Inspector (Service Quota & Open Merit)',
                    'vacancies' => '150',
                    'education' => 'Graduate (BA / BSc / BS)',
                    'scale' => 'BS-14 (Regular)',
                    'location' => 'All Punjab Regions',
                    'age_limit' => '20 - 28 Years',
                ],
                [
                    'name' => 'Assistant Engineer (Civil)',
                    'vacancies' => '70',
                    'education' => 'B.Sc Engineering (Civil) + PEC Reg.',
                    'scale' => 'BS-17 (Contract)',
                    'location' => 'Punjab Housing Dept',
                    'age_limit' => '21 - 35 Years',
                ],
            ],
            'documents_required' => [
                'Original CNIC and Domicile Certificate of Punjab',
                'Matric, Intermediate, Graduation / Master Degree & Transcripts',
                'PEC Registration Certificate for Engineering posts',
                'Equivalence Certificate from HEC (if foreign or private degree)',
                'PSID Paid Fee Receipt copy (Rs. 600)',
            ],
            'mistakes_to_avoid' => [
                'Do not wait for the closing date (October 30, 2026) to avoid online portal server overload.',
                'Do not deposit fee using old manual challan — PPSC accepts PSID e-Payment only.',
                'Do not claim wrong domicile or age relaxation category during online data entry.',
                'Ensure your photo and CNIC upload scans are clear and under 25KB.',
            ],
            'selection_process' => [
                'Online application submission on ppsc.gop.pk using PSID e-Payment.',
                'PPSC Written Competitive Examination / MCQ Screening Test (100 Marks).',
                'Physical endurance and measurement test (for Police Sub-Inspector posts).',
                'Shortlist of candidates on 1:5 ratio for Psychological Assessment & Interview.',
                'Final Merit List publication and department recommendation letter.',
            ],
            'how_to_apply_urdu' => '<ol>
                <li>سب سے پہلے PPSC کی آن لائن پورٹل <strong>www.ppsc.gop.pk</strong> پر جائیں۔</li>
                <li>مطلوبہ پوسٹ منتخب کریں اور سسٹم کے ذریعے 17 ہندسوں پر مشتمل PSID نمبر حاصل کریں۔</li>
                <li>جاز کیش، ایزی پیسہ یا اپنے موبائل بینکنگ ایپ کے ذریعے 600 روپے فیس ادا کریں۔</li>
                <li>شناختی کارڈ نمبر، سی این آئی سی کی تصویر اور پاسپورٹ سائز فوٹو اپ لوڈ کریں۔</li>
                <li>تعلیمی معلومات، ڈگری مارکس اور ڈومیسائل کی تفصیلات درست درج کر کے درخواست حتمی جمع (Submit) کریں۔</li>
            </ol>',
        ];

        $content = JobPostTemplateService::render($jobData);

        $category = Category::query()->where('name', 'PPSC')->first()
            ?: Category::query()->where('name', 'Jobs')->first()
            ?: Category::query()->first();

        $post = Post::query()->updateOrCreate(
            ['name' => $title],
            [
                'description' => 'PPSC Jobs 2026 Advertisement 18 announced for 245 Assistant Director, Sub Inspector & Civil Engineer vacancies across Punjab. Check eligibility, age limit, syllabus and apply online at ppsc.gop.pk.',
                'content' => $content,
                'status' => BaseStatusEnum::PUBLISHED,
                'is_featured' => 1,
                'user_id' => 1,
                'views' => rand(200, 500),
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
