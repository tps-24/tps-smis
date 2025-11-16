<?php

namespace App\Imports;

use App\Models\Staff;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class UpdateStaffDetails implements ToCollection
{
    public $errors = [];
    public $warnings = [];

    public function collection(Collection $rows)
    {
        $num = 0;

        foreach ($rows as $row) {
            $num++;
            if ($num <= 5) continue; // Skip headers

            $staff = !empty($row[0])
                ? Staff::where('force_number', trim($row[0]))->first()
                : Staff::where([
                    ['first_name', '=', $row[2]],
                    ['middle_name', '=', $row[3]],
                    ['last_name', '=', $row[4]],
                    ['company_id', '=', $this->getCompanyId($row[6])],
                    ['platoon', '=', $row[7]],
                ])->first();

            if (!$staff) {
                $this->warnings[] = "Row $num: Staff not found.";
                Log::warning("Row $num staff not found: " . json_encode($row));
                continue;
            }

            // --- Session Check ---
            try {
                $selectedSessionId = $this->getSessionId();
            } catch (\Exception $e) {
                $this->errors[] = "Row $num: " . $e->getMessage();
                continue;
            }

            if ($staff->session_programme_id != $selectedSessionId) {
                $this->warnings[] = "Row $num: Session mismatch.";
                Log::warning("Row $num session mismatch: " . json_encode($row));
                continue;
            }

            // --- NIN Conflict Check ---
            if (!empty($row[10])) {
                $ninConflict = Staff::where('nin', $row[10])
                    ->where('id', '!=', $staff->id)
                    ->exists();

                if ($ninConflict) {
                    $this->errors[] = "Row $num: NIN already exists for another staff.";
                    Log::error("Row $num duplicate NIN: " . json_encode($row));
                    continue;
                }
                $staff->nin = $row[10];
            }

            // --- Update Fields Conditionally ---
            $staff->fill([
                'force_number'   => $row[0] ?? $staff->force_number,
                'phone'          => $row[8] ?? $staff->phone,
                'dob'            => $row[9] ?? $staff->dob,
                'blood_group'    => $row[11] ?? $staff->blood_group,
                'home_region'    => $row[12] ?? $staff->home_region,
                'entry_region'   => $row[13] ?? $staff->entry_region,
                'education_level'=> $row[14] ?? $staff->education_level,
                'profession'     => $row[15] ?? $staff->profession,
                'weight'         => $row[16] ?? $staff->weight,
                'height'         => $row[17] ?? $staff->height,
                'account_number' => $row[18] ?? $staff->account_number,
                'bank_name'      => $row[19] ?? $staff->bank_name,
                'registration_number' =>$row[21] ?? $staff->registration_number
            ]);
            $staff->save(); // More semantic than update()
        }
    }

    private function getCompanyId($companyName)
    {
        $mapping = [
            'HQ' => 1,
            'A'  => 2,
            'B'  => 3,
            'C'  => 4,
            'D'  => 5,
        ];
        return $mapping[strtoupper(trim($companyName))] ?? null;
    }

    private function getSessionId()
    {
        $selectedSessionId = session('selected_session');
        if (!$selectedSessionId) {
            throw new \Exception('Please select a session.');
        }
        return $selectedSessionId;
    }
}
