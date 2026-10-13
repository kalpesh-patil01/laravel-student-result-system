<?php
namespace App\Http\Controllers;
use App\Models\{Mark, SchoolClass, Student};
use App\Services\ResultService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarksController extends Controller {
    public function index(Request $r, ResultService $svc) {
        return view("marks.pick", ["students" => $svc->paginateWithResults($r), "classes" => SchoolClass::orderBy("class_name")->get(),
            "title" => "Marks Entry", "subtitle" => "Pick a student to enter or edit subject-wise marks.", "route" => "marks.edit", "label" => "Enter marks", "page" => "marks.index"]);
    }
    public function edit(Student $student) {
        $student->load("schoolClass.subjects", "marks");
        return view("marks.form", ["student" => $student, "existing" => $student->marks->pluck("marks_obtained", "subject_id")]);
    }
    public function update(Request $r, Student $student) {
        $errors = []; $clean = [];
        foreach ($student->schoolClass->subjects as $s) {
            $v = $r->input("marks.{$s->id}");
            if ($v === null || trim($v) === "") { $clean[$s->id] = null; continue; }   // blank = not entered
            if (!is_numeric($v) || $v < 0 || $v > $s->maximum_marks) { $errors["marks.{$s->id}"] = "{$s->subject_name}: enter a number from 0 to " . ($s->maximum_marks + 0) . "."; continue; }
            $clean[$s->id] = (float) $v;
        }
        if ($errors) return back()->withErrors($errors)->withInput();
        DB::transaction(function () use ($clean, $student) {
            foreach ($clean as $sid => $v) {
                if ($v === null) Mark::where(["student_id" => $student->id, "subject_id" => $sid])->delete();
                else Mark::updateOrCreate(["student_id" => $student->id, "subject_id" => $sid], ["marks_obtained" => $v]);
            }
        });
        return redirect()->route("marks.index", ["class_id" => $student->class_id])->with("success", "Marks saved for {$student->name}.");
    }
}
