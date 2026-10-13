<?php
namespace App\Http\Controllers;
use App\Models\{SchoolClass, Student};
use App\Services\ResultService;
use Illuminate\Http\Request;

class MarksheetController extends Controller {
    public function index(Request $r, ResultService $svc) {
        return view("marks.pick", ["students" => $svc->paginateWithResults($r), "classes" => SchoolClass::orderBy("class_name")->get(),
            "title" => "Marksheets", "subtitle" => "Open a student's printable marksheet.", "route" => "marksheets.show", "label" => "View marksheet", "page" => "marksheets.index"]);
    }
    public function show(Student $student, ResultService $svc) {
        $student->load("schoolClass.subjects", "marks");
        return view("marksheets.show", ["student" => $student, "r" => $svc->evaluate($student)]);
    }
}
