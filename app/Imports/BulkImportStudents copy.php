<?php

namespace App\Imports;

use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Exception;

class BulkImportStudents implements ToCollection, ToModel
{
    private $num = 0;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        // You can process the collection here if needed
    }

    public function model(array $row)
    {
        $this->num++;

        // Ensure the import starts after the required row number
        if ($this->num > 5) {
            // Validate essential fields
            if (empty($row[1]) || empty($row[0])) {
                return; // Skip if key fields are missing
            }

            // Create new student record
            $student = new Student();
            $student->force_number = $row[0];
            $student->first_name = $row[1];
            $student->middle_name = $row[2];
            $student->last_name = $row[3];
            $student->session_programme_id = $this->getSessionId();

            // Handling gender and rank based on session programme
            if ($student->session_programme_id == 1) {
                $student->gender = ($row[20] == "RC") ? "M" : "F";
            } elseif ($student->session_programme_id == 5) {
                $student->gender = $row[4];
                $student->rank = 'Bigular';
            } else {
                $student->gender = $row[4];
                $student->rank = $row[20];
            }

            $student->phone = $row[5];
            $student->nin = !empty($row[7]) ? $row[7] : null;
            $student->blood_group = !empty($row[8]) ? $row[8] : null;
            $student->home_region = $row[9];
            $student->company_id = $this->getCompanyId($row[10]);
            $student->platoon = $row[11];
            $student->education_level = $row[12];

            // Concatenate height and weight
            $student->physical_attributes = trim($row[13] . ' | ' . $row[14]);

            $student->next_kin_names = $row[16];
            $student->next_kin_phone = $row[17];
            $student->religion = $row[21];

            // Save student record
            $student->save();
        }
    }

    /**
     * Get session ID from the active session
     */
    private function getSessionId()
    {
        $selectedSessionId = session('selected_session');
        if (!$selectedSessionId) {
            throw new Exception('Please select session.');
        }
        return $selectedSessionId;
    }

    /**
     * Map company names to IDs
     */
    private function getCompanyId($companyName)
    {
        $companyMapping = [
            'HQ' => 1,
            'A' => 2,
            'B' => 3,
            'C' => 4,
            'D' => 5,
            'E' => 6,
            'F' => 7,
        ];

        return $companyMapping[$companyName] ?? null;
    }
}
