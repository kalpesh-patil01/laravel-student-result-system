<?php
namespace App\Http\Controllers;
use App\Models\{SchoolClass, Subject};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller {
    public function index(Request $r) {
        $subjects = Subject::with("schoolClass")->when($r->class_id, fn ($q, $c) => $q->where("class_id", $c))->orderBy("class_id")->orderBy("subject_name")->get();
        return view("subjects.index", ["subjects" => $subjects, "classes" => SchoolClass::orderBy("class_name")->get()]);
    }
    public function create() { return redirect()->route("subjects.index"); }
    public function store(Request $r) { Subject::create($this->data($r)); return back()->with("success", "Subject added."); }
    public function edit(Subject $subject) { return view("subjects.edit", ["subject" => $subject, "classes" => SchoolClass::orderBy("class_name")->get()]); }
    public function update(Request $r, Subject $subject) {
        $data = $this->data($r, $subject);
        if ($subject->marks()->exists() && (int) $data["class_id"] !== $subject->class_id)
            return back()->withErrors(["class_id" => "Cannot move a subject that already has marks to another class."])->withInput();
        $subject->update($data);
        return redirect()->route("subjects.index")->with("success", "Subject updated.");
    }
    public function destroy(Subject $subject) {
        if ($subject->marks()->exists()) return back()->withErrors(["subject" => "Cannot delete a subject that already has marks."]);
        $subject->delete();
        return back()->with("success", "Subject deleted.");
    }
    private function data(Request $r, ?Subject $s = null): array {
        return $r->validate([
            "class_id" => ["required", "exists:classes,id"],
            "subject_name" => ["required", "string", "max:100", Rule::unique("subjects")->where("class_id", $r->class_id)->ignore($s?->id)],
            "maximum_marks" => ["required", "numeric", "gt:0", "max:9999"],
            "passing_marks" => ["required", "numeric", "gte:0", "lte:maximum_marks"],
        ], ["subject_name.unique" => "This class already has a subject with that name.", "passing_marks.lte" => "Passing marks cannot exceed maximum marks."]);
    }
}
