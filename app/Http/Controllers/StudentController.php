<?php
namespace App\Http\Controllers;
use App\Models\{SchoolClass, Student};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller {
    public function index(Request $r) {
        $students = Student::with("schoolClass")
            ->when($r->class_id, fn ($q, $c) => $q->where("class_id", $c))
            ->when($r->q, fn ($q, $t) => $q->where(fn ($w) => $w->where("name", "like", "%$t%")->orWhere("roll_number", "like", "%$t%")))
            ->orderBy("class_id")->orderBy("roll_number")->paginate(10)->withQueryString();
        return view("students.index", ["students" => $students, "classes" => SchoolClass::orderBy("class_name")->get()]);
    }
    public function create() { return view("students.form", ["student" => new Student, "classes" => SchoolClass::orderBy("class_name")->get()]); }
    public function store(Request $r) { Student::create($this->data($r)); return redirect()->route("students.index")->with("success", "Student added."); }
    public function edit(Student $student) { return view("students.form", ["student" => $student, "classes" => SchoolClass::orderBy("class_name")->get()]); }
    public function update(Request $r, Student $student) { $student->update($this->data($r, $student)); return redirect()->route("students.index")->with("success", "Student updated."); }
    public function destroy(Student $student) { $student->delete(); return back()->with("success", "Student and their marks deleted."); }

    private function data(Request $r, ?Student $student = null): array {
        return $r->validate([
            "name" => ["required", "string", "max:150"],
            "class_id" => ["required", "exists:classes,id"],
            "roll_number" => ["required", "string", "max:30", Rule::unique("students")->where("class_id", $r->class_id)->ignore($student?->id)],
        ], ["roll_number.unique" => "This roll number is already used in the selected class."]);
    }
}
