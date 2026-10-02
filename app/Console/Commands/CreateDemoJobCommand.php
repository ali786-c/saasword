<?php

namespace App\Console\Commands;

use App\Services\JobPostTemplateService;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Slug\Models\Slug;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateDemoJobCommand extends Command
{
    protected $signature = 'cms:create-demo-job';

    protected $description = 'Create a high-value, 11-section SEO & AdSense compliant demo job post in Botble CMS';

    public function handle(): int
    {
        $this->info('Generating high-value demo job post using updated 11-section JobPostTemplateService...');

        $title = 'NADRA Scanning Operator Jobs 2026 - 150 Vacancies Available';
        $slugKey = Str::slug('NADRA Scanning Operator Jobs 2026 150 Vacancies Available');

        $jobData = [
            'posted_on' => 'September 28, 2026',
            'city' => 'Lahore',
            'education' => 'Intermediate',
            'vacancies' => 'Multiple',
            'apply_method' => 'Online',
            'organization' => 'NADRA',
            'salary' => '40,000 / Month + Incentive',
            'official_source_url' => 'https://careers.nadra.gov.pk',
            'official_apply_url' => 'https://careers.nadra.gov.pk',
            'last_checked' => 'September 28, 2026',
            'deadline' => 'October 25, 2026',
            'ad_image_url' => '/storage/news/1.jpg',
            'also_apply_title' => 'Scholarship at Polytechnic di Torino University Italy 2025',
            'also_apply_url' => '/category/jobs',
            'job_description' => '<p>The <strong>National Database & Registration Authority (NADRA)</strong> has officially announced recruitment for <strong>Scanning Operators</strong> across multiple regional offices in Pakistan for 2026. This announcement provides contract employment for male and female candidates holding Intermediate or Bachelor qualifications with basic computer typing skills.</p><p>Eligible candidates from Lahore, Rawalpindi, Islamabad, Karachi, Peshawar, and Quetta are encouraged to review the eligibility criteria, age limits, and online application process below before submitting their forms before the deadline.</p>',
            'who_can_apply' => '<p>Male and female citizens of Pakistan holding a valid CNIC and domicile of the respective district can apply. Fresh Intermediate (FA/FSc/ICS) candidates with typing speed of at least 30 WPM and basic computer knowledge are eligible. Age limit is 18 to 28 years (with official age relaxation as per government rules).</p>',
            'eligibility_criteria' => '<ul>
                <li><strong>Education:</strong> Minimum Intermediate (FA / FSc / ICS / ICom) from a recognized board. Higher qualification (BA / BSc / BS IT) will be preferred.</li>
                <li><strong>Computer Skills:</strong> Minimum 30 words per minute (WPM) typing speed with basic knowledge of document scanning and MS Office.</li>
                <li><strong>Age Limit:</strong> 18 to 28 years (General relaxation applicable as per NADRA policy).</li>
                <li><strong>Domicile:</strong> Respective district / division domicile required as per regional quota.</li>
            </ul>',
            'vacant_positions' => [
                [
                    'name' => 'Scanning Operator',
                    'vacancies' => '100',
                    'education' => 'Intermediate / ICS',
                    'scale' => 'Contract (Rs. 40,000/month + Incentive)',
                    'location' => 'Lahore',
                    'age_limit' => '18 - 28 Years',
                ],
                [
                    'name' => 'Data Entry & Verification Assistant',
                    'vacancies' => '50',
                    'education' => 'Bachelor (BA / BSc / Computer Science)',
                    'scale' => 'Contract (Rs. 55,000/month)',
                    'location' => 'Lahore & Regional Offices',
                    'age_limit' => '20 - 30 Years',
                ],
            ],
            'documents_required' => [
                'Original & attested photocopies of CNIC / B-Form',
                'Domicile certificate of relevant district',
                'Intermediate / Bachelor marksheets & degree certificates',
                'Computer diploma or typing certificate (if available)',
                '2 recent passport-size photographs (blue background)',
            ],
            'mistakes_to_avoid' => [
                'Do not apply after the last date (October 25, 2026).',
                'Do not enter an invalid CNIC or mobile number on the online portal.',
                'Do not pay any application fee on unauthorized websites — NADRA online registration has no processing fee unless specified on careers.nadra.gov.pk.',
                'Ensure your typing speed meets the minimum 30 WPM requirement before appearing for the test.',
            ],
            'selection_process' => [
                'Online registration on the official NADRA Careers portal.',
                'Initial computer typing and scanning test at designated regional centres.',
                'Shortlisting based on test score and academic merit.',
                'Interview and original document verification.',
                'Final offer letter issuance and medical checkup.',
            ],
            'how_to_apply_urdu' => '<ol>
                <li>سب سے پہلے نیڈرا کی آفیشل ویب سائٹ <strong>careers.nadra.gov.pk</strong> پر جائیں۔</li>
                <li>اپنی قومی شناختی کارڈ (CNIC) اور بنیادی معلومات درج کر کے اکاؤنٹ بنائیں۔</li>
                <li>اپنی تعلیمی اسناد، ٹائپنگ کی مہارت اور ڈومیسائل کی معلومات آن لائن فارم میں پر کریں۔</li>
                <li>فارم مکمل کرنے کے بعد اپنی فراہم کردہ معلومات کی تصدیق کریں اور Submit بٹن پر کلک کریں۔</li>
                <li>ٹیسٹ اور انٹرویو کی تاریخ کے لیے نادرا کی طرف سے موصول ہونے والے ایس ایم ایس یا ای میل کا انتظار کریں۔</li>
            </ol>',
        ];

        $content = JobPostTemplateService::render($jobData);

        // Find or associate category
        $category = Category::query()->where('name', 'Jobs')->first() ?: Category::query()->first();

        // Create or update Post
        $post = Post::query()->updateOrCreate(
            ['name' => $title],
            [
                'description' => 'NADRA Scanning Operator Jobs 2026 announced for 150 contract vacancies across Pakistan. Check eligibility, typing speed, and apply online at careers.nadra.gov.pk.',
                'content' => $content,
                'status' => BaseStatusEnum::PUBLISHED,
                'is_featured' => 1,
                'user_id' => 1,
                'views' => rand(150, 450),
            ]
        );

        if ($category) {
            $post->categories()->sync([$category->id]);
        }

        // Generate Slug
        Slug::query()->firstOrCreate(
            [
                'key' => $slugKey,
                'reference_type' => $post::class,
            ],
            [
                'reference_id' => $post->id,
                'prefix' => Slug::getPrefix($post::class, ''),
            ]
        );

        $this->info("✔ Demo Job Post Updated Successfully!");
        $this->line("  Title: {$post->name}");
        $this->line("  URL: " . url($slugKey));

        return self::SUCCESS;
    }
}
