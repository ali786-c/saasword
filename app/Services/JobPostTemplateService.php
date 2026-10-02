<?php

namespace App\Services;

class JobPostTemplateService
{
    /**
     * Render the full 11-section Job Post Template HTML matching user specifications & screenshot styling.
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
        $deadline = e($data['deadline'] ?? 'See Official Advt');

        $jobDescription = $data['job_description'] ?? '';
        $whoCanApply = $data['who_can_apply'] ?? '';
        $eligibilityCriteria = $data['eligibility_criteria'] ?? '';
        $positions = $data['vacant_positions'] ?? [];
        $adImageUrl = $data['ad_image_url'] ?? null;
        $alsoApplyTitle = e($data['also_apply_title'] ?? 'Scholarships & Latest Government Jobs 2026');
        $alsoApplyUrl = e($data['also_apply_url'] ?? '/category/jobs');

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

                $positionRows .= "<tr>
                    <td>{$sr}</td>
                    <td class=\"font-weight-bold\">{$name}</td>
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
                $documentItems .= "<li class=\"list-group-item d-flex align-items-center py-2\">
                    <span class=\"mr-2 text-success\">✔</span> {$docText}
                </li>";
            }
        }

        // Build Mistakes List
        $mistakeItems = '';
        if (is_array($mistakes)) {
            foreach ($mistakes as $mis) {
                $misText = e($mis);
                $mistakeItems .= "<li class=\"mb-1\">{$misText}</li>";
            }
        }

        // Selection Process Block (Optional)
        $selectionBlock = '';
        if ($selectionProcess) {
            $selText = is_array($selectionProcess) ? implode('</li><li>', array_map('e', $selectionProcess)) : e($selectionProcess);
            $selectionBlock = "
            <div class=\"job-section mb-4\">
                <div class=\"job-heading-banner\">7. Official Selection Process</div>
                <ol class=\"pl-4 text-secondary\" style=\"line-height: 1.8;\">
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
                <div class=\"job-heading-banner text-left\">9. Official Job Advertisement</div>
                <div class=\"p-2 border rounded bg-white shadow-sm d-inline-block mw-100 mb-3\">
                    <img src=\"{$safeAdUrl}\" alt=\"Official Job Advertisement\" class=\"img-fluid rounded\" style=\"max-height: 700px; width: auto;\">
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
                <div class=\"job-heading-banner text-left\">9. Official Job Advertisement</div>
                <div class=\"p-4 border border-dashed rounded bg-light text-muted mb-2\">
                    <p class=\"mb-1 font-weight-bold\">📄 Official Advertisement Image Container</p>
                    <p class=\"small mb-0\">Verify details directly on the official portal below or view newspaper clip upon publication.</p>
                </div>
            </div>";
        }

        // Build Full HTML
        return <<<HTML
<style>
/* === CareerInPak Job Template Heading Banner (Screenshot Style) === */
.job-heading-banner {
    background-color: #e53935;
    border-left: 5px solid #007bff;
    color: #ffffff !important;
    padding: 12px 20px;
    font-size: 18px;
    font-weight: 700;
    border-radius: 4px;
    margin-top: 25px;
    margin-bottom: 20px;
    display: block;
    box-shadow: 0 2px 5px rgba(0,0,0,0.08);
}
.job-summary-box {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.04);
}
.job-summary-box .job-summary-header {
    background-color: #e53935;
    border-left: 5px solid #007bff;
    color: #ffffff !important;
    padding: 10px 16px;
    font-size: 17px;
    font-weight: 700;
    border-radius: 4px;
    margin-bottom: 18px;
}
.also-apply-banner {
    background-color: #e53935;
    border-left: 5px solid #007bff;
    color: #ffffff !important;
    padding: 12px 20px;
    font-size: 16px;
    font-weight: 700;
    border-radius: 4px;
    margin: 25px 0;
    display: block;
}
.also-apply-banner a {
    color: #ffffff !important;
    text-decoration: underline;
}
.also-apply-banner a:hover {
    color: #ffebee !important;
}
</style>

<div class="job-post-template font-sans">

  <!-- 1. Job Summary Box -->
  <div class="job-summary-box">
    <div class="job-summary-header">📋 Job Summary</div>
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

  <!-- 2. Job Description -->
  <div class="job-section mb-4">
    <div class="job-heading-banner">1. Job Description</div>
    <div class="job-text lead-sm text-secondary" style="line-height: 1.8;">
      {$jobDescription}
    </div>
  </div>

  <!-- Screenshot Style "Also Apply For" Banner -->
  <div class="also-apply-banner">
    Also Apply For: <a href="{$alsoApplyUrl}">{$alsoApplyTitle}</a>
  </div>

  <!-- 3. Who Can Apply -->
  <div class="job-section mb-4">
    <div class="job-heading-banner">2. Who Can Apply</div>
    <div class="job-text text-secondary" style="line-height: 1.8;">
      {$whoCanApply}
    </div>
  </div>

  <!-- 4. Eligibility Criteria -->
  <div class="job-section mb-4">
    <div class="job-heading-banner">3. Eligibility Criteria</div>
    <div class="job-text text-secondary" style="line-height: 1.8;">
      {$eligibilityCriteria}
    </div>
  </div>

  <!-- 5. Vacant Positions Table -->
  <div class="job-section mb-4">
    <div class="job-heading-banner">4. Vacant Positions</div>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="bg-primary text-white">
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
    <div class="job-heading-banner">5. Documents Required</div>
    <p class="text-muted small">The official advertisement may require some or all of the following documents. Candidates should confirm the final list from the official advertisement before applying:</p>
    <ul class="list-group list-group-flush mb-3">
      {$documentItems}
    </ul>
  </div>

  <!-- 7. Application Mistakes to Avoid -->
  <div class="job-section mb-4">
    <div class="job-heading-banner">6. Application Mistakes to Avoid</div>
    <div class="p-3 border-left border-warning bg-light rounded" style="border-left-width: 5px !important;">
      <ul class="mb-0 text-dark small" style="line-height: 1.7;">
        {$mistakeItems}
      </ul>
    </div>
  </div>

  {$selectionBlock}

  <!-- 8. How to Apply (Urdu RTL Section) -->
  <div class="job-section mb-4">
    <div class="job-heading-banner" style="display: flex; justify-content: space-between; align-items: center;">
      <span>8. How to Apply</span>
      <span dir="rtl">درخواست جمع کروانے کا طریقہ</span>
    </div>
    <div class="p-4 bg-light border rounded">
      <div dir="rtl" style="text-align: right; font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', 'Segoe UI', Tahoma, sans-serif; font-size: 17px; line-height: 2.2;" class="text-dark">
        {$howToApplyUrdu}
      </div>
    </div>
  </div>

  {$adImageBlock}

  <!-- 10. Official Source & Verification -->
  <div class="job-section mb-4">
    <div class="job-heading-banner">10. Official Source & Verification</div>
    <div class="p-4 border rounded bg-white text-center shadow-sm">
      <p class="small text-muted mb-3">CareerInPak collected this information from the official advertisement or official portal. Candidates should verify the details from the official source before applying. If you find an error, contact us at <a href="mailto:info@careerinpak.com">info@careerinpak.com</a>.</p>
      <div class="d-flex justify-content-center flex-wrap gap-2">
        <a href="{$officialApplyUrl}" target="_blank" rel="nofollow noopener" class="btn btn-danger btn-lg font-weight-bold px-4 py-2 text-white">
          🚀 Apply Online / Official Portal
        </a>
        <a href="{$officialSourceUrl}" target="_blank" rel="nofollow noopener" class="btn btn-outline-secondary btn-lg font-weight-bold px-4 py-2 ml-2">
          📄 View Official Advertisement
        </a>
      </div>
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
