<?php
namespace App\Http\Controllers;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassController extends Controller {
    public function index() { return view("classes.index", ["classes" => SchoolClass::withCount(["students", "subjects"])->orderBy("class_name")->get()]); }
    public function create() { return redirect()->route("classes.index"); }
    public function store(Request $r) { SchoolClass::create($this->data($r)); return back()->with("success", "Class created."); }
    public function edit(SchoolClass $schoolClass) { return view("classes.edit", ["class" => $schoolClass]); }
    public function update(Request $r, SchoolClass $schoolClass) { $schoolClass->update($this->data($r, $schoolClass)); return redirect()->route("classes.index")->with("success", "Class updated."); }
    public function destroy(SchoolClass $schoolClass) {
        if ($schoolClass->students()->exists() || $schoolClass->subjects()->exists())
            return back()->withErrors(["class" => "Cannot delete a class that still has students or subjects."]);
        $schoolClass->delete();
        return back()->with("success", "Class deleted.");
    }
    private function data(Request $r, ?SchoolClass $c = null): array {
        return $r->validate(["class_name" => ["required", "string", "max:100", Rule::unique("classes")->ignore($c?->id)]]);
    }
}
