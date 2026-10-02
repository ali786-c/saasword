<?php

namespace App\Services;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LlmsTxtService
{
    /**
     * Generate dynamic llms.txt and llms-full.txt content featuring latest published jobs
     */
    public static function generate(): string
    {
        // 1. Fetch latest published job posts
        $latestPosts = Post::query()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->latest('created_at')
            ->take(30)
            ->get();

        $postLinks = "";
        foreach ($latestPosts as $post) {
            $url = $post->url;
            $title = e($post->name);
            $cleanDesc = Str::limit(strip_tags($post->description ?: $post->name), 140);
            $cleanDesc = e(str_replace(["\r", "\n"], ' ', $cleanDesc));
            $postLinks .= "- [{$title}]({$url}): {$cleanDesc}\n";
        }

        if (empty($postLinks)) {
            $postLinks = "- [PPSC Jobs 2026](https://careerinpak.com/category/ppsc): Punjab Public Service Commission advertisement notices.\n";
        }

        // 2. Fetch main job categories
        $categories = Category::query()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->take(12)
            ->get();

        $categoryLinks = "";
        foreach ($categories as $cat) {
            $catUrl = $cat->url;
            $catName = e($cat->name);
            $categoryLinks .= "- [{$catName} Jobs]({$catUrl}): Latest {$catName} recruitment advertisements and application details.\n";
        }

        if (empty($categoryLinks)) {
            $categoryLinks = "- [Federal Government Jobs](https://careerinpak.com/category/federal-jobs): FPSC, FBR, Pak Army, and Ministries recruitment.\n";
        }

        // 3. Construct Standard llms.txt Markdown
        $content = <<<MARKDOWN
# CareerInPak — Pakistan Government & Private Jobs Portal

> CareerInPak provides authentic daily updates for government jobs, PPSC, FPSC, NTS, Defence, Banking, and Private sector vacancies across Pakistan.

## Latest Published Job Notifications

{$postLinks}

## Primary Job Categories & Portals

{$categoryLinks}

## Machine-Readable Resources & Feeds

- [XML Sitemap Index](https://careerinpak.com/sitemap.xml): Machine-readable index of all job posts, categories, and tags.
- [RSS Feed](https://careerinpak.com/feed): Live RSS 2.0 feed of latest published job alerts.
- [Full Text Specification](https://careerinpak.com/llms-full.txt): Detailed structural documentation for AI agents.

## Core Features & Guidelines

- [Job Posting & Watermark SOP](https://careerinpak.com/JOB_POSTING_AND_WATERMARK_GUIDE.md): Technical standard operating procedure for job publishing and automated image watermarking.
MARKDOWN;

        // 4. Also generate llms-full.txt
        $fullContent = <<<MARKDOWN
# CareerInPak — Pakistan Government & Private Jobs Portal (Full Specification)

> CareerInPak is a leading career information portal delivering authentic daily job updates, official recruitment advertisements, syllabus details, and application procedures across Pakistan.

## About CareerInPak

CareerInPak aggregates, verifies, and publishes official hiring notifications from Pakistani federal and provincial government departments, public sector organizations (PAEC, KRL, NESCOM), armed forces, commercial banks, and private enterprises.

## Latest Published Job Notifications (Live Feed)

{$postLinks}

## Primary Job Categories & Portals

{$categoryLinks}

## Standard Job Post Structure

Every job post published on CareerInPak includes:
1. **Job Summary Table:** Quick reference for Posted Date, City, Required Education, Vacancies, Salary Range, and Official Source.
2. **Dynamic Expiry Notice Box:** Real-time countdown for active applications and red alert badge for expired jobs.
3. **Job Description & Who Can Apply:** Detailed eligibility criteria, domicile requirements, and age relaxation limits.
4. **Vacant Positions Table:** Structured breakdown of job titles, pay scales (BPS/SPS), required qualifications, and seat counts.
5. **Application Mistakes to Avoid:** Guidelines to prevent application rejection.
6. **Selection Process:** Step-by-step recruitment procedure from screening test to interview.
7. **Bilingual How to Apply:** Step-by-step instructions in English and Urdu (RTL Nastaliq typography).
8. **Official Newspaper Advertisement Image:** High-resolution viewable and downloadable official newspaper clip with automated `careerinpak.com` watermark.
9. **Google Jobs Schema (`schema.org/JobPosting`):** Embedded JSON-LD structured data for Google Search Jobs Rich Snippets.

## Technical Feeds & Resources

- [XML Sitemap Index](https://careerinpak.com/sitemap.xml): Machine-readable index of all job posts, categories, and tags.
- [RSS Feed](https://careerinpak.com/feed): Live RSS feed of latest job alerts.
- [Job Posting Guide](https://careerinpak.com/JOB_POSTING_AND_WATERMARK_GUIDE.md): Technical SOP for publishing and watermarking.
MARKDOWN;

        // 5. Update disk files synchronously
        try {
            File::ensureDirectoryExists(public_path('.well-known'));
            File::put(public_path('llms.txt'), $content);
            File::put(public_path('llms-full.txt'), $fullContent);
            File::put(public_path('.well-known/llms.txt'), $content);
        } catch (\Throwable $e) {
            // Log fallback
            logger()->error('Failed to write llms.txt files: ' . $e->getMessage());
        }

        return $content;
    }
}
