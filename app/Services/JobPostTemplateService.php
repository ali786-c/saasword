<?php

namespace App\Services;

class JobPostTemplateService
{
    /**
     * Render the full 11-section Job Post Template HTML with guaranteed inline red/blue banner headings,
     * dynamic expiry notice boxes, crisp black text, WhatsApp channel promo card, and schema.org/JobPosting JSON-LD.
     *
     * @param array $data Structured job data
     * @return string Validated HTML string
     */
    public static function render(array $data): string
    {
        $postedOn = e($data['posted_on'] ?? date('F d, Y'));
        $location = e($data['city'] ?? $data['location'] ?? 'Pakistan');
        $education = e($data['education'] ?? 'Intermediate / Bachelor');
        $vacancies = e($data['vacancies'] ?? 'Multiple');
        $applyMethod = e($data['apply_method'] ?? 'Online');
        $organization = e($data['organization'] ?? 'Government Organization');
        $salary = e($data['salary'] ?? '40,000 / Month + Incentive');
        $officialSourceUrl = e($data['official_source_url'] ?? 'https://careers.nadra.gov.pk');
        $officialApplyUrl = e($data['official_apply_url'] ?? $officialSourceUrl);
        $lastChecked = e($data['last_checked'] ?? $data['last_checked_date'] ?? date('F d, Y'));
        $deadline = e($data['deadline'] ?? 'October 30, 2026');

        $jobDescription = $data['job_description'] ?? '';
        $whoCanApply = $data['who_can_apply'] ?? '';
        $eligibilityCriteria = $data['eligibility_criteria'] ?? '';
        $positions = $data['vacant_positions'] ?? [];
        $adImageUrl = $data['ad_image_url'] ?? null;
        $alsoApplyTitle = e($data['also_apply_title'] ?? 'Scholarship at Polytechnic di Torino University Italy 2025');
        $alsoApplyUrl = e($data['also_apply_url'] ?? '/category/jobs');
        $whatsappUrl = e($data['whatsapp_channel_url'] ?? 'https://whatsapp.com/channel/0029Vb1vBU95a249w33Gha16');

        $documents = $data['documents_required'] ?? [
            'Attested copy of CNIC / B-Form',
            'Domicile certificate of relevant district/province',
            'Educational certificates & degrees',
            'Experience certificates (if required)',
            'Recent passport-size photographs',
            'Application fee deposit slip / challan (if required)',
        ];
        $mistakes = $data['mistakes_to_avoid'] ?? [
            "Do not submit your application after the last date ({$deadline}).",
            'Do not enter incorrect CNIC, mobile number, or email on the form.',
            'Do not submit incomplete information or unverified documents.',
            'Do not pay any fee to unauthorized persons or fake online links.',
            'Read the official advertisement carefully before applying.',
        ];
        $selectionProcess = $data['selection_process'] ?? null;
        $howToApplyUrdu = $data['how_to_apply_urdu'] ?? '';

        // Heading Inline Styles (Guaranteed Red Background + Blue Left Accent + White Text)
        $hStyle = 'background-color: #e53935 !important; border-left: 5px solid #007bff !important; color: #ffffff !important; padding: 12px 20px !important; font-size: 18px !important; font-weight: 700 !important; border-radius: 4px !important; margin-top: 25px !important; margin-bottom: 20px !important; display: block !important; box-shadow: 0 2px 5px rgba(0,0,0,0.08) !important;';
        $summaryHStyle = 'background-color: #e53935 !important; border-left: 5px solid #007bff !important; color: #ffffff !important; padding: 10px 16px !important; font-size: 17px !important; font-weight: 700 !important; border-radius: 4px !important; margin-bottom: 18px !important; display: block !important;';
        $alsoStyle = 'background-color: #e53935 !important; border-left: 5px solid #007bff !important; color: #ffffff !important; padding: 12px 20px !important; font-size: 16px !important; font-weight: 700 !important; border-radius: 4px !important; margin: 25px 0 !important; display: block !important;';

        // Dynamic Deadline / Expiry Notice Box Calculator
        $deadlineTime = strtotime($deadline);
        $todayTime = strtotime(date('Y-m-d'));
        $deadlineBox = '';

        if ($deadlineTime !== false) {
            $daysRemaining = (int) round(($deadlineTime - $todayTime) / (60 * 60 * 24));
            if ($daysRemaining >= 0) {
                // Active Notice Box
                $deadlineBox = "
                <div class=\"job-deadline-box active-notice mb-4\" style=\"background-color: #eef7ff; border: 1px solid #cce5ff; border-left: 5px solid #007bff; border-radius: 6px; padding: 16px 20px;\">
                    <div class=\"d-flex align-items-center mb-1\">
                        <span style=\"font-size: 20px; margin-right: 10px;\">📅</span>
                        <strong style=\"color: #004085; font-size: 16px;\">Last Date to Apply: {$deadline}</strong>
                    </div>
                    <p class=\"mb-0\" style=\"margin-left: 32px; font-size: 14px; color: #111111 !important;\">You have <strong style=\"color: #004085;\">{$daysRemaining} days</strong> remaining to submit your application.</p>
                </div>";
            } else {
                // Expired Notice Box
                $deadlineBox = "
                <div class=\"job-deadline-box expired-notice mb-4\" style=\"background-color: #fdf2f2; border: 1px solid #f8d7da; border-left: 5px solid #d32f2f; border-radius: 6px; padding: 16px 20px;\">
                    <div class=\"d-flex align-items-center mb-1\">
                        <span style=\"font-size: 20px; margin-right: 10px; color: #d32f2f; font-weight: bold;\">❌</span>
                        <strong style=\"color: #721c24; font-size: 16px;\">This Job Has Expired</strong>
                    </div>
                    <p class=\"mb-0\" style=\"margin-left: 32px; font-size: 14px; color: #111111 !important;\">Last date was <strong>{$deadline}</strong>. <a href=\"/category/jobs\" style=\"color: #007bff; text-decoration: underline; font-weight: bold;\">View Latest Jobs 2026 →</a></p>
                </div>";
            }
        }

        // WhatsApp Channel Promo Banner Component (Fail-Safe Table Layout)
        $whatsappBanner = "
        <div class=\"whatsapp-channel-banner my-4\" style=\"background-color: #f0fdf4 !important; border: 1.5px solid #25D366 !important; border-radius: 12px !important; padding: 16px 20px !important; margin-top: 20px !important; margin-bottom: 25px !important; box-shadow: 0 2px 8px rgba(37, 211, 102, 0.12) !important;\">
            <table style=\"width: 100% !important; border-collapse: collapse !important; border: 0 !important; background: transparent !important; margin: 0 !important; padding: 0 !important;\">
                <tr style=\"background: transparent !important;\">
                    <td style=\"width: 54px !important; vertical-align: middle !important; border: 0 !important; padding: 0 15px 0 0 !important;\">
                        <div style=\"width: 46px !important; height: 46px !important; background-color: #25D366 !important; border-radius: 50% !important; display: block !important; text-align: center !important; line-height: 46px !important;\">
                            <span style=\"font-size: 24px !important; color: #ffffff !important; line-height: 46px !important; display: inline-block !important;\">💬</span>
                        </div>
                    </td>
                    <td style=\"vertical-align: middle !important; border: 0 !important; padding: 0 !important;\">
                        <div style=\"font-size: 18px !important; font-weight: 700 !important; color: #111111 !important; line-height: 1.3 !important; margin: 0 0 4px 0 !important;\">
                            Join our WhatsApp Channel!
                        </div>
                        <div style=\"font-size: 14px !important; color: #4a5568 !important; line-height: 1.4 !important; margin: 0 !important;\">
                            Don't miss out! Get the latest Government &amp; Private jobs alerts directly on your phone.
                        </div>
                    </td>
                    <td style=\"text-align: right !important; vertical-align: middle !important; border: 0 !important; padding: 0 0 0 15px !important; width: 130px !important;\">
                        <a href=\"{$whatsappUrl}\" target=\"_blank\" rel=\"noopener noreferrer\" style=\"background-color: #25D366 !important; color: #ffffff !important; font-weight: 700 !important; font-size: 15px !important; padding: 10px 22px !important; border-radius: 30px !important; text-decoration: none !important; display: inline-block !important; white-space: nowrap !important; box-shadow: 0 4px 10px rgba(37, 211, 102, 0.3) !important;\">
                            Join Now
                        </a>
                    </td>
                </tr>
            </table>
        </div>";

        // Build Vacant Positions Table Rows
        $positionRows = '';
        if (is_array($positions) && ! empty($positions)) {
            foreach ($positions as $index => $pos) {
                $sr = $index + 1;
                $name = e($pos['name'] ?? '');
                $posVacancies = e($pos['vacancies'] ?? '1');
                $posEdu = e($pos['education'] ?? $education);
                $posScale = e($pos['scale'] ?? 'BPS-01 to BPS-16');
                $posLoc = e($pos['location'] ?? $location);
                $posAge = e($pos['age_limit'] ?? '18-35 Years');

                $positionRows .= "<tr style=\"color: #111111;\">
                    <td>{$sr}</td>
                    <td style=\"font-weight: 700;\">{$name}</td>
                    <td><span class=\"badge bg-info text-dark\">{$posVacancies}</span></td>
                    <td>{$posEdu}</td>
                    <td>{$posScale}</td>
                    <td>{$posLoc}</td>
                    <td>{$posAge}</td>
                </tr>";
            }
        }

        // Build Documents List
        $documentItems = '';
        if (is_array($documents)) {
            foreach ($documents as $doc) {
                $docText = e($doc);
                $documentItems .= "<li class=\"list-group-item d-flex align-items-center py-2\" style=\"color: #111111;\">
                    <span class=\"mr-2 text-success\">✔</span> {$docText}
                </li>";
            }
        }

        // Build Mistakes List
        $mistakeItems = '';
        if (is_array($mistakes)) {
            foreach ($mistakes as $mis) {
                $misText = e($mis);
                $mistakeItems .= "<li class=\"mb-1\" style=\"color: #111111;\">{$misText}</li>";
            }
        }

        // Selection Process Block (Optional)
        $selectionBlock = '';
        if ($selectionProcess) {
            $selText = is_array($selectionProcess) ? implode('</li><li>', array_map('e', $selectionProcess)) : e($selectionProcess);
            $selectionBlock = "
            <div class=\"job-section mb-4\">
                <div style=\"{$hStyle}\">7. Official Selection Process</div>
                <ol class=\"pl-4\" style=\"line-height: 1.8; color: #111111 !important;\">
                    " . (is_array($selectionProcess) ? "<li>{$selText}</li>" : "<li>{$selText}</li>") . "
                </ol>
            </div>";
        }

        // Official Advertisement Image Block
        $adImageBlock = '';
        if ($adImageUrl) {
            $safeAdUrl = e($adImageUrl);
            $adImageBlock = "
            <div class=\"job-section mb-4 text-center\">
                <div style=\"{$hStyle}\" class=\"text-left\">9. Official Job Advertisement</div>
                <div class=\"p-2 border rounded bg-white shadow-sm d-inline-block mw-100 mb-3\" style=\"min-height: 350px; contain: layout;\">
                    <img src=\"{$safeAdUrl}\" alt=\"Official Job Advertisement\" class=\"img-fluid rounded\" width=\"800\" height=\"1000\" loading=\"lazy\" style=\"max-height: 700px; width: auto; height: auto; aspect-ratio: 4 / 5;\">
                </div>
                <div>
                    <a href=\"{$safeAdUrl}\" download target=\"_blank\" class=\"btn btn-outline-primary font-weight-bold btn-sm\">
                        📥 Download Official Newspaper Advertisement Image
                    </a>
                </div>
            </div>";
        } else {
            $adImageBlock = "
            <div class=\"job-section mb-4 text-center\">
                <div style=\"{$hStyle}\" class=\"text-left\">9. Official Job Advertisement</div>
                <div class=\"p-4 border border-dashed rounded bg-light text-muted mb-2\">
                    <p class=\"mb-1 font-weight-bold\">📄 Official Advertisement Image Container</p>
                    <p class=\"small mb-0\">Verify details directly on the official portal below or view newspaper clip upon publication.</p>
                </div>
            </div>";
        }

        // Generate schema.org/JobPosting JSON-LD for Google Jobs SEO
        $isoPosted = date('Y-m-d', strtotime($postedOn) ?: time());
        $isoValidThrough = $deadlineTime ? date('Y-m-d', $deadlineTime) : date('Y-m-d', strtotime('+30 days'));
        $cleanDesc = e(strip_tags($jobDescription));

        $schemaJson = json_encode([
            '@context' => 'https://schema.org/',
            '@type' => 'JobPosting',
            'title' => e($data['title'] ?? $organization . ' Jobs 2026'),
            'description' => $cleanDesc,
            'identifier' => [
                '@type' => 'PropertyValue',
                'name' => $organization,
                'value' => md5($organization . $postedOn),
            ],
            'datePosted' => $isoPosted,
            'validThrough' => $isoValidThrough,
            'employmentType' => 'FULL_TIME',
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name' => $organization,
                'sameAs' => $officialSourceUrl,
            ],
            'jobLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $location,
                    'addressCountry' => 'PK',
                ],
            ],
            'baseSalary' => [
                '@type' => 'MonetaryAmount',
                'currency' => 'PKR',
                'value' => [
                    '@type' => 'QuantitativeValue',
                    'value' => $salary,
                    'unitText' => 'MONTH',
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        $schemaBlock = "<script type=\"application/ld+json\">\n{$schemaJson}\n</script>";

        // Build Full HTML
        return <<<HTML
{$schemaBlock}
<div class="job-post-template font-sans" style="color: #111111 !important;">

  <!-- 1. Job Summary Box -->
  <div class="job-summary-box" style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; margin-bottom: 25px; box-shadow: 0 3px 10px rgba(0,0,0,0.04); color: #111111 !important;">
    <div style="{$summaryHStyle}">📋 Job Summary</div>
    <div class="row" style="font-size: 15px; line-height: 1.8;">
      <div class="col-md-6 mb-2"><strong>Posted on:</strong> {$postedOn}</div>
      <div class="col-md-6 mb-2"><strong>Location:</strong> {$location}</div>
      <div class="col-md-6 mb-2"><strong>Education:</strong> {$education}</div>
      <div class="col-md-6 mb-2"><strong>Vacancies:</strong> {$vacancies}</div>
      <div class="col-md-6 mb-2"><strong>Apply Method:</strong> {$applyMethod}</div>
      <div class="col-md-6 mb-2"><strong>Organization:</strong> {$organization}</div>
      <div class="col-md-6 mb-2"><strong>Salary:</strong> <span class="badge bg-success text-white px-2 py-1">{$salary}</span></div>
      <div class="col-md-6 mb-2"><strong>Official Source:</strong> <a href="{$officialSourceUrl}" target="_blank" rel="nofollow noopener" class="text-primary font-weight-bold">{$officialSourceUrl}</a></div>
      <div class="col-md-12 mt-2 pt-2 border-top text-muted small"><strong>Last Checked:</strong> {$lastChecked}</div>
    </div>
  </div>

  {$deadlineBox}

  {$whatsappBanner}

  <!-- 2. Job Description -->
  <div class="job-section mb-4">
    <div style="{$hStyle}">1. Job Description</div>
    <div class="job-text lead-sm" style="line-height: 1.8; color: #111111 !important; font-size: 16px;">
      {$jobDescription}
    </div>
  </div>

  <!-- Screenshot Style "Also Apply For" Banner -->
  <div style="{$alsoStyle}">
    Also Apply For: <a href="{$alsoApplyUrl}" style="color: #ffffff !important; text-decoration: underline !important;">{$alsoApplyTitle}</a>
  </div>

  <!-- 3. Who Can Apply -->
  <div class="job-section mb-4">
    <div style="{$hStyle}">2. Who Can Apply</div>
    <div class="job-text" style="line-height: 1.8; color: #111111 !important; font-size: 16px;">
      {$whoCanApply}
    </div>
  </div>

  <!-- 4. Eligibility Criteria -->
  <div class="job-section mb-4">
    <div style="{$hStyle}">3. Eligibility Criteria</div>
    <div class="job-text" style="line-height: 1.8; color: #111111 !important; font-size: 16px;">
      {$eligibilityCriteria}
    </div>
  </div>

  <!-- 5. Vacant Positions Table -->
  <div class="job-section mb-4">
    <div style="{$hStyle}">4. Vacant Positions</div>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle" style="color: #111111 !important;">
        <thead style="background-color: #5869DA; color: #ffffff;">
          <tr>
            <th>Sr #</th>
            <th>Position Title</th>
            <th>Vacancies</th>
            <th>Education</th>
            <th>Scale</th>
            <th>Location</th>
            <th>Age Limit</th>
          </tr>
        </thead>
        <tbody>
          {$positionRows}
        </tbody>
      </table>
    </div>
  </div>

  <!-- 6. Documents Required -->
  <div class="job-section mb-4">
    <div style="{$hStyle}">5. Documents Required</div>
    <p class="text-muted small">The official advertisement may require some or all of the following documents. Candidates should confirm the final list from the official advertisement before applying:</p>
    <ul class="list-group list-group-flush mb-3">
      {$documentItems}
    </ul>
  </div>

  <!-- 7. Application Mistakes to Avoid -->
  <div class="job-section mb-4">
    <div style="{$hStyle}">6. Application Mistakes to Avoid</div>
    <div class="p-3 border-left border-warning bg-light rounded" style="border-left-width: 5px !important;">
      <ul class="mb-0 small" style="line-height: 1.7; color: #111111 !important;">
        {$mistakeItems}
      </ul>
    </div>
  </div>

  {$selectionBlock}

  <!-- 8. How to Apply (Urdu RTL Section) -->
  <div class="job-section mb-4">
    <div style="{$hStyle} display: flex !important; justify-content: space-between; align-items: center;">
      <span>8. How to Apply</span>
      <span dir="rtl">درخواست جمع کروانے کا طریقہ</span>
    </div>
    <div class="p-4 bg-light border rounded">
      <div dir="rtl" style="text-align: right; font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', 'Segoe UI', Tahoma, sans-serif; font-size: 17px; line-height: 2.2; color: #111111 !important;">
        {$howToApplyUrdu}
      </div>
    </div>
  </div>

  {$adImageBlock}

  <!-- 10. Official Source & Verification -->
  <div class="job-section mb-4">
    <div style="{$hStyle}">10. Official Source & Verification</div>
    <div class="p-4 border rounded bg-white text-center shadow-sm" style="color: #111111 !important;">
      <p class="small text-muted mb-0">CareerInPak collected this information from the official advertisement or official portal. Candidates should verify the details from the official source before applying. If you find an error, contact us at <a href="mailto:info@careerinpak.com">info@careerinpak.com</a>.</p>
    </div>
  </div>

  <!-- 11. Disclaimer -->
  <div class="job-disclaimer alert alert-secondary small text-muted text-center mb-4">
    <strong>Disclaimer:</strong> CareerInPak is an independent job and scholarship information portal. We are not affiliated with any government department or hiring agency. We do not issue test calls, roll number slips, or selection letters. Always verify all details directly from the official advertisement or official portal before applying.
  </div>

</div>
HTML;
    }
}
