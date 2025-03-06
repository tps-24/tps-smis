<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Student;
use App\Models\CourseworkResult;
use Auth;
class CourseworkResultImport implements ToCollection,ToModel
{
    /**
    * @param Collection $collection
    */

    private $num =0;

    private $courseworkId;
    private $courseId;
    private $semesterId;

    // Add a constructor to accept the extra variable
    public function __construct($semesterId, $courseId, $courseworkId)
    {
        $this->semesterId = $semesterId;
        $this->courseId = $courseId;
        $this->courseworkId = $courseworkId;
        
    }
    public function collection(Collection $collection)
    {
        //
    }

    public function model(array $row)
    {
        $this->num++;
        if ($this->num > 5) {
            if (empty($row[1])) {
                return;  // or you can use continue; depending on where the loop is
            }
            $courseworkResult = new CourseworkResult();
            $courseworkResult->student_id = $this->getStudentId($row[1]);
            $courseworkResult->course_id = $this->courseId;
            $courseworkResult->coursework_id = $this->courseworkId;
            $courseworkResult->semester_id = $this->semesterId;
            $courseworkResult->score = $row[2];
            $courseworkResult->created_by = Auth::user()->id;
            $courseworkResult->save();
        }
    }

    /**
     * Summary of getStudentId
     * @param mixed $forceNumber
     */
    private function getStudentId($forceNumber){
        $student = Student::where('force_number',$forceNumber)->get()[0];
        return $student->id;
    }
}
