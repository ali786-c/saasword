<?php

namespace App\Services;

class JobPostTemplateService
{
    /**
     * Render the full 11-section Job Post Template HTML matching guide.md & templet.guide.md standards.
     *
     * @param array $data Structured job data
     * @return string Validated HTML string
     */
    public static function render(array $data): string
    {
        $organization = e($data['organization'] ?? 'Government Organization');
        $jobType = e($data['job_type'] ?? 'Full Time / Contract');
        $city = e($data['city'] ?? 'Pakistan');
        $education = e($data['education'] ?? 'Matric / Intermediate / Bachelor');
        $vacancies = e($data['vacancies'] ?? 'Multiple');
        $deadline = e($data['deadline'] ?? 'See Official Advt');
        $applyMethod = e($data['apply_method'] ?? 'Online / Postal');
        $officialSourceUrl = e($data['official_source_url'] ?? '#');
        $officialApplyUrl = e($data['official_apply_url'] ?? $officialSourceUrl);
        $lastCheckedDate = e($data['last_checked_date'] ?? date('F d, Y'));

        $jobDescription = $data['job_description'] ?? '';
        $whoCanApply = $data['who_can_apply'] ?? '';
        $eligibilityCriteria = $data['eligibility_criteria'] ?? '';
        $positions = $data['vacant_positions'] ?? [];
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
                $posLoc = e($pos['location'] ?? $city);
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
                <h3 class=\"h4 font-weight-bold mb-3 text-dark\">6. Official Selection Process</h3>
                <ol class=\"pl-4 text-secondary\" style=\"line-height: 1.8;\">
                    " . (is_array($selectionProcess) ? "<li>{$selText}</li>" : "<li>{$selText}</li>") . "
                </ol>
            </div>";
        }

        // Build Full HTML
        return <<<HTML
<div class="job-post-template font-sans">

  <!-- 1. Quick Job Summary Card -->
  <div class="job-summary-card mb-4 p-4 border rounded shadow-sm bg-light">
    <h3 class="h5 font-weight-bold mb-3 text-primary border-bottom pb-2">📋 Quick Job Summary</h3>
    <div class="row">
      <div class="col-md-6 mb-2"><strong>Organization:</strong> {$organization}</div>
      <div class="col-md-6 mb-2"><strong>Job Type:</strong> {$jobType}</div>
      <div class="col-md-6 mb-2"><strong>Location / City:</strong> {$city}</div>
      <div class="col-md-6 mb-2"><strong>Education Required:</strong> {$education}</div>
      <div class="col-md-6 mb-2"><strong>Total Vacancies:</strong> {$vacancies}</div>
      <div class="col-md-6 mb-2"><strong>Last Date / Deadline:</strong> <span class="badge bg-danger text-white">{$deadline}</span></div>
      <div class="col-md-6 mb-2"><strong>Apply Method:</strong> {$applyMethod}</div>
      <div class="col-md-6 mb-2"><strong>Official Source:</strong> <a href="{$officialSourceUrl}" target="_blank" rel="nofollow noopener" class="text-primary font-weight-bold">Verified Advertisement</a></div>
      <div class="col-md-12 mt-2 pt-2 border-top text-muted small"><strong>Last Checked Date:</strong> {$lastCheckedDate}</div>
    </div>
  </div>

  <!-- 2. Job Description -->
  <div class="job-section mb-4">
    <h3 class="h4 font-weight-bold mb-3 text-dark">1. Job Description</h3>
    <div class="job-text lead-sm text-secondary" style="line-height: 1.8;">
      {$jobDescription}
    </div>
  </div>

  <!-- 3. Who Can Apply -->
  <div class="job-section mb-4">
    <h3 class="h4 font-weight-bold mb-3 text-dark">2. Who Can Apply</h3>
    <div class="job-text text-secondary" style="line-height: 1.8;">
      {$whoCanApply}
    </div>
  </div>

  <!-- 4. Eligibility Criteria -->
  <div class="job-section mb-4">
    <h3 class="h4 font-weight-bold mb-3 text-dark">3. Eligibility Criteria</h3>
    <div class="job-text text-secondary" style="line-height: 1.8;">
      {$eligibilityCriteria}
    </div>
  </div>

  <!-- 5. Vacant Positions Table -->
  <div class="job-section mb-4">
    <h3 class="h4 font-weight-bold mb-3 text-dark">4. Vacant Positions</h3>
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
    <h3 class="h4 font-weight-bold mb-3 text-dark">5. Documents Required</h3>
    <p class="text-muted small">The official advertisement may require some or all of the following documents. Candidates should confirm the final list from the official advertisement before applying:</p>
    <ul class="list-group list-group-flush mb-3">
      {$documentItems}
    </ul>
  </div>

  <!-- 7. Application Mistakes to Avoid -->
  <div class="job-section mb-4 p-3 border-left border-warning bg-light rounded" style="border-left-width: 5px !important;">
    <h3 class="h5 font-weight-bold mb-2 text-warning">⚠️ Application Mistakes to Avoid</h3>
    <ul class="mb-0 text-dark small" style="line-height: 1.7;">
      {$mistakeItems}
    </ul>
  </div>

  {$selectionBlock}

  <!-- 9. How to Apply (Urdu RTL Section) -->
  <div class="job-section mb-4 p-4 bg-light border rounded">
    <h3 class="h4 font-weight-bold mb-3 text-success text-right" dir="rtl">درخواست جمع کروانے کا طریقہ (How to Apply)</h3>
    <div dir="rtl" style="text-align: right; font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', 'Segoe UI', Tahoma, sans-serif; font-size: 17px; line-height: 2.2;" class="text-dark">
      {$howToApplyUrdu}
    </div>
  </div>

  <!-- 10. Official Source & Verification -->
  <div class="job-section mb-4 p-4 border rounded bg-white text-center shadow-sm">
    <h4 class="h5 font-weight-bold text-dark mb-2">🔍 Official Source & Verification</h4>
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

  <!-- 11. Disclaimer -->
  <div class="job-disclaimer alert alert-secondary small text-muted text-center mb-4">
    <strong>Disclaimer:</strong> CareerInPak is an independent job and scholarship information portal. We are not affiliated with any government department or hiring agency. We do not issue test calls, roll number slips, or selection letters. Always verify all details directly from the official advertisement or official portal before applying.
  </div>

</div>
HTML;
    }
}
