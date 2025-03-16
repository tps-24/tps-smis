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
    }

    public function model(array $row)
    {
        $this->num++;
        if($this->num>5){
            if (empty($row[1])) {
                return;  // or you can use continue; depending on where the loop is
            }
            $student = new Student();
            $student->force_number = $row[0];
            $student->first_name = $row[1];
            $student->middle_name = $row[2];
            $student->last_name = $row[3];
            $student->session_programme_id = $this->getSessionId();
            if($student->session_programme_id == 1)
                $student->gender = $row[20] == "RC" ? "M" : "F";
            elseif($student->session_programme_id == 5){
                $student->gender = $row[4];
                $student->rank = 'Bigular';
            }
                
            $student->phone = $row[5];
           // $student->dob = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[6]))->format('Y-m-d');
            $student->nin = $row[7];
            $student->blood_group = $row[8];
            $student->home_region = $row[9];
            $student->company_id = $this->getCompanyId($row[10]);
            $student->platoon = $row[11];
            $student->education_level = $row[12];
            // $student->height = $row[13];
            // $student->weight = $row[14];
            // $student->next_kin_names = $row[15];
            // $student->next_kin_phone = $row[16];
            // $student->next_kin_relationship = $row[17];
            // $student->next_kin_address = $row[18];
                $student->save();

                
        }
        
         //return new Student([
            // 'first_name' => $row['first_name'],
        //     'middle_name' => $row['middle_name'],
            // 'last_name' => $row['last_name'],
        //     'gender' => $row['gender'],
        //     'phone' => $row['phone'],
        //     'nin' => $row['nin'],
        //     'dob' => $row['dob'],
        //     'home_region'=> $row['home_region'],
        //     'company' => $row['company'],
        //     'platoon' => $row['platoon'],
        //     'education_level' => $row['education_level'],
        //     'rank' => $row['rank'],
        //     'height' => $row['height'],
        //     'weight' => $row['weight'],
        // ]);
    }
    private function getSessionId(){
        $selectedSessionId = session('selected_session');
        if (!$selectedSessionId){
            throw new Exception('Please select session.');
        }
        return $selectedSessionId;
    }

    private function getCompanyId($companyName){
        if($companyName == 'HQ'){
            return "1";
        }elseif($companyName == 'A'){
            return "2";
        }elseif($companyName == 'B'){
            return "3";
        }elseif($companyName == 'C'){
            return "4";
        }
    }
}
