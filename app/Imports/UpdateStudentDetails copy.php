<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class UpdateStudentDetails implements ToCollection
{
    public function collection(Collection $rows)
    {

        $num = 0;

        foreach ($rows as $row) {
            
            $num++;
            if ($num <= 3) {
                continue; // Skip header rows
            }
            
            // dd($this->getCompanyId($row[11]));

            // Validation for data in each row
            $validator = Validator::make([
                'first_name' => $row[2] ?? null,
                'middle_name' => $row[3] ?? null,
                'last_name' => $row[4] ?? null,
                'company_id' => $this->getCompanyId($row[11]) ?? null,
                'platoon' => $row[12] ?? null,
                'force_number' => $row[0] ?? null,
            ], [
                'first_name' => 'required|string|max:255',
                'middle_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'company_id' => 'required',
                'platoon' => 'required',
                'force_number' => 'required|string',
            ]);

            if ($validator->fails()) {
                \Log::error('Validation failed for row: ' . json_encode($row));
                continue;
            }

            // Locate the student record by unique attributes
            $student = Student::where('first_name', $row[2])
                            ->where('middle_name', $row[3])
                            ->where('last_name', $row[4])
                            ->where('company_id', $this->getCompanyId($row[11]))
                            ->where('platoon', $row[12])
                            ->first();

            if ($student) {
                // Check if the selected session matches the student's session
                $selectedSessionId = $this->getSessionId();
                if ($selectedSessionId != $student->session_programme_id) {
                    \Log::warning('Session mismatch for row: ' . json_encode($row));
                    continue; // Skip rows with mismatched sessions
                }

                // Update the student record
                $student->force_number = $row[0];
                $student->entry_region = $row[16];
                $student->update();
            } else {
                \Log::warning('Student not found for row: ' . json_encode($row));
            }
        }
    }

    private function getSessionId()
    {
        $selectedSessionId = session('selected_session');
        if (!$selectedSessionId) {
            throw new Exception('Please select session.');
        }
        return $selectedSessionId;
    }

    private function getCompanyId($companyName)
    {
        $mapping = [
            'HQ' => 1,
            'A' => 2,
            'B' => 3,
            'C' => 4,
        ];

        // Convert the input to uppercase
        $key = strtoupper($companyName);

        return $mapping[$key] ?? null;
    }

}
