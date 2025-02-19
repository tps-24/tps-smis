<?php

namespace App\Imports;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;

class BulkImportStaff implements ToCollection,ToModel
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
        if ($this->num > 5) {
            $user = new User();
            $user->name = $row[3]. " ". $row[4]. " ".$row[5];
            $user->email = $row[6];
            $user->password= Hash::make(strtoupper($row[5]));
            Log::info($row[3]);
            $user->save();

            $staff = new Staff();
            $staff->forceNumber = $row[0];
            $staff->rank = $row[1];
            $staff->nin = $row[2];
            $staff->firstName = $row[3];
            $staff->middleName = $row[4];
            $staff->lastName = $row[5];
            $staff->email = $row[6];
            $staff->gender = $row[7];   
            $staff->user_id = $user->id;
            $staff->created_by = "1";
            
            $staff->DoB = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[8]))->format('Y-m-d');
            $staff->maritalStatus = $row[9];
            $staff->religion = $row[10];
            $staff->tribe = $row[11];
            $staff->phoneNumber = $row[12];
            
            $staff->currentAddress = $row[13];
            $staff->permanentAddress = $row[14];
            $staff->department_id = $row[15];
            $staff->designation = $row[16];
            // $staff->educationLevel = $row[17];
            // $staff->contractType = $row[18];
            // $staff->joiningDate = $row[19];
            // $staff->location = $row[20];
            // $staff->nextofkinFullName = $row[21];
            // $staff->nextofkinRelationship = $row[22];
            // $staff->nextofkinPhoneNumber = $row[23];
            // $staff->nextofkinPysicalAddress = $row[24];

            $staff->save();

        }
    }
}
