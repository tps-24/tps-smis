<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class UpdateStudentDetails implements ToCollection
{
    public $errors = [];  // To store errors
    public $warnings = []; // To store warnings

    public function collection(Collection $rows)
    {
        $num = 0;

        foreach ($rows as $row) {
            $num++;
            if ($num <= 3) {
                continue; // Skip headers
            }

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
                $this->errors[] = 'Row ' . $num . ': ' . implode(', ', $validator->errors()->all());
                \Log::error('Validation failed for row: ' . json_encode($row));
                continue;
            }

            // Locate the student
            $student = Student::where('first_name', $row[2])
                ->where('middle_name', $row[3])
                ->where('last_name', $row[4])
                ->where('company_id', $this->getCompanyId($row[11]))
                ->where('platoon', $row[12])
                ->first();

            if ($student) {
                $selectedSessionId = $this->getSessionId();
                if ($selectedSessionId != $student->session_programme_id) {
                    $this->warnings[] = 'Row ' . $num . ': Session mismatch.';
                    \Log::warning('Session mismatch for row: ' . json_encode($row));
                    continue;
                }

                // Update details
                $student->force_number = $row[0];
                $student->entry_region = $row[16];
                $student->update();
            } else {
                $this->warnings[] = 'Row ' . $num . ': Student not found.';
                \Log::warning('Student not found for row: ' . json_encode($row));
            }
        }
    }

    private function getCompanyId($companyName)
    {
        $mapping = [
            'HQ' => 1,
            'A' => 2,
            'B' => 3,
            'C' => 4,
        ];
        return $mapping[strtoupper($companyName)] ?? null;
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
