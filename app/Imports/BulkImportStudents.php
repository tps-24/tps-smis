<?php

namespace App\Imports;
use App\Models\Student;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

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
        if($this->num>1){
            $student = new Student();
            $student->force_number = $row[0];
            $student->first_name = $row[1];
            $student->middle_name = $row[2];
            $student->last_name = $row[3];
            $student->gender = $row[4];
            $student->dob = $row[5];
            $student->nin = $row[6];
            $student->blood_group = $row[7];
            $student->home_region = $row[8];
            $student->company = $row[9];
            $student->platoon = $row[10];
            $student->education_level = $row[11];
            $student->height = $row[12];
            $student->weight = $row[13];
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
}
