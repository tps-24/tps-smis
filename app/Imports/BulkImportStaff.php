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
            $user->email = $row[6] == null? trim(strtolower($row[3]).".".strtolower($row[5])."@tpf.go.tz") : trim($row[6]);
            $user->password= Hash::make(strtoupper($row[5]));
       
            $user->save();

            $staff = new Staff();
            $staff->forceNumber = $row[0];
            $staff->rank = $row[1];
            $staff->nin = $row[2];
            $staff->firstName = $row[3];
            $staff->middleName = $row[4];
            $staff->lastName = $row[5];
            $staff->email = $row[6] == null? trim(strtolower($row[3]).".".strtolower($row[5])."@tpf.go.tz") : trim($row[6]);
            $staff->gender = $row[7];   
            $staff->user_id = $user->id;
            $staff->created_by = "1";
            
            //$staff->DoB = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[8]))->format('Y-m-d');
            $staff->company_id = $this->getCompanyId($row[9]);
            $staff->maritalStatus = $row[10];
            $staff->religion = $row[11];
            $staff->tribe = $row[12];
            $staff->phoneNumber = $row[13];
            
            $staff->currentAddress = $row[14];
            $staff->permanentAddress = $row[15];
            $staff->department_id = $row[16];
            $staff->designation = $row[17];
            // $staff->educationLevel = $row[18];
            // $staff->contractType = $row[19];
            // $staff->joiningDate = $row[20];
            // $staff->location = $row[21];
            // $staff->nextofkinFullName = $row[22];
            // $staff->nextofkinRelationship = $row[23];
            // $staff->nextofkinPhoneNumber = $row[24];
            // $staff->nextofkinPysicalAddress = $row[25];

            $staff->save();

        }
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
