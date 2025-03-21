<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Student;
use App\Models\CourseworkResult;
use App\Models\CourseWork;
use Auth;
use Exception;
class CourseworkResultImport implements ToCollection, ToModel
{
    /**
     * @param Collection $collection
     */

    private $num = 0;

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
            $student = Student::where('force_number', $row[1])->first();
            
            if (!$student) {
                // If no student found, throw an exception with a detailed message
                throw new Exception('Student with force number ' . $row[1] . ' not found.');
            }
            dd($this->courseId);
            $coursework = CourseWork::findOrFail($this->courseId);
            
            if (!$coursework) {
                // If no coursework found, throw an exception with a detailed message
                throw new Exception('coursework with Id ' . $this->courseId . ' not found.');
            }

            //Check if the score is greater than the max score
            if ($row[2] > $coursework->max_score) {
                throw new Exception('Coursework score(' . $row[2] . ') of ' . $row[1] . ' must be less than the maximum score(' . $coursework->max_score . ').');
            }

            // Check for duplicate coursework result for the student
            if ($this->checkStudentDuplication($student->id)) {
                throw new Exception('Duplicate entry for student ' . $row[1] . ' in coursework ' . $this->courseworkId . ' for semester ' . $this->semesterId);
            }

            $courseworkResult = new CourseworkResult();
            $courseworkResult->student_id = $this->getStudentId($row[1]);
            $courseworkResult->course_id = $this->courseId;
            $courseworkResult->coursework_id = $this->courseworkId;
            $courseworkResult->semester_id = $this->semesterId;
            $courseworkResult->score = $row[3];
            $courseworkResult->created_by = Auth::user()->id;
            $courseworkResult->save();
            return $courseworkResult;
        }
    }

    /**
     * Summary of getStudentId
     * @param mixed $forceNumber
     */
    private function getStudentId($forceNumber)
    {
        $student = Student::where('force_number', $forceNumber)->get()[0];
        return $student->id;
    }

    private function checkStudentDuplication($studentId)
    {
        // Check if a result already exists for this student for the specific course, coursework, and semester
        $existingResult = CourseworkResult::where('student_id', $studentId)
            ->where('course_id', $this->courseId)
            ->where('coursework_id', $this->courseworkId)
            ->where('semester_id', $this->semesterId)
            ->first();

        // If a result is found, return true (indicating a duplication)
        return $existingResult ? true : false;
    }

}
