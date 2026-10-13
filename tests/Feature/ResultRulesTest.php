<?php
namespace Tests\Feature;
use App\Models\{Mark, SchoolClass, Student, Subject};
use App\Services\ResultService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResultRulesTest extends TestCase
{
    use RefreshDatabase;

    private function klass(string $name = "X"): array
    {
        $c = SchoolClass::create(["class_name" => $name]);
        $m = Subject::create(["class_id" => $c->id, "subject_name" => "Math", "maximum_marks" => 100, "passing_marks" => 35]);
        $e = Subject::create(["class_id" => $c->id, "subject_name" => "English", "maximum_marks" => 50, "passing_marks" => 20]);
        return [$c, $m, $e];
    }
    private function student(SchoolClass $c, string $roll, ?float $math, ?float $eng, array $subs): Student
    {
        $s = Student::create(["class_id" => $c->id, "roll_number" => $roll, "name" => "S$roll"]);
        if ($math !== null) Mark::create(["student_id" => $s->id, "subject_id" => $subs[0]->id, "marks_obtained" => $math]);
        if ($eng !== null) Mark::create(["student_id" => $s->id, "subject_id" => $subs[1]->id, "marks_obtained" => $eng]);
        return $s->load("schoolClass.subjects", "marks");
    }

    public function test_total_percentage_grade_and_pass(): void
    {
        [$c, $m, $e] = $this->klass();
        $r = (new ResultService)->evaluate($this->student($c, "1", 80, 40, [$m, $e]));
        $this->assertSame(120.0, $r["total"]); $this->assertSame(150.0, $r["max"]);
        $this->assertEqualsWithDelta(80.0, $r["percentage"], 0.001);
        $this->assertSame("A", $r["grade"]); $this->assertSame("PASS", $r["status"]);
    }
    public function test_failing_one_subject_fails_overall_despite_high_percentage(): void
    {
        [$c, $m, $e] = $this->klass();
        $r = (new ResultService)->evaluate($this->student($c, "1", 100, 10, [$m, $e]));   // 110/150 = 73%
        $this->assertSame("FAIL", $r["status"]);
    }
    public function test_missing_marks_are_incomplete_not_zero(): void
    {
        [$c, $m, $e] = $this->klass();
        $r = (new ResultService)->evaluate($this->student($c, "1", 90, null, [$m, $e]));
        $this->assertSame("INCOMPLETE", $r["status"]); $this->assertNull($r["total"]); $this->assertNull($r["grade"]);
    }
    public function test_class_summary_counts_students_not_subject_rows(): void
    {
        [$c, $m, $e] = $this->klass();
        $this->student($c, "1", 80, 40, [$m, $e]); $this->student($c, "2", 20, 40, [$m, $e]); $this->student($c, "3", 60, null, [$m, $e]);
        $row = (new ResultService)->classSummary()->firstWhere("class_name", "X");
        $this->assertSame([3, 1, 1, 1, 2, 50.0], [$row->enrolled, $row->passed, $row->failed, $row->incomplete, $row->completed, $row->pass_rate]);
    }
    public function test_class_with_no_completed_results_has_null_pass_rate(): void
    {
        [$c, $m, $e] = $this->klass(); $this->student($c, "1", null, null, [$m, $e]); SchoolClass::create(["class_name" => "Empty"]);
        foreach ((new ResultService)->classSummary() as $row) { $this->assertSame(0, $row->completed); $this->assertNull($row->pass_rate); }
    }
    public function test_duplicate_roll_number_in_class_is_rejected(): void
    {
        [$c] = $this->klass(); Student::create(["class_id" => $c->id, "roll_number" => "7", "name" => "A"]);
        $this->post(route("students.store"), ["name" => "B", "roll_number" => "7", "class_id" => $c->id])->assertSessionHasErrors("roll_number");
        $this->assertSame(1, Student::count());
    }
    public function test_student_validation_requires_fields(): void
    {
        $this->post(route("students.store"), [])->assertSessionHasErrors(["name", "roll_number", "class_id"]);
    }
    public function test_subject_passing_marks_cannot_exceed_maximum(): void
    {
        [$c] = $this->klass();
        $this->post(route("subjects.store"), ["class_id" => $c->id, "subject_name" => "Art", "maximum_marks" => 50, "passing_marks" => 60])->assertSessionHasErrors("passing_marks");
        $this->post(route("subjects.store"), ["class_id" => $c->id, "subject_name" => "Art", "maximum_marks" => 0, "passing_marks" => 0])->assertSessionHasErrors("maximum_marks");
    }
    public function test_negative_and_excess_marks_are_rejected_and_nothing_saved(): void
    {
        [$c, $m, $e] = $this->klass(); $s = $this->student($c, "1", null, null, [$m, $e]);
        $this->put(route("marks.update", $s), ["marks" => [$m->id => -1, $e->id => 10]])->assertSessionHasErrors("marks.{$m->id}");
        $this->put(route("marks.update", $s), ["marks" => [$m->id => 50, $e->id => 51]])->assertSessionHasErrors("marks.{$e->id}");
        $this->assertSame(0, Mark::count());
    }
    public function test_valid_marks_save_and_update_without_duplicates(): void
    {
        [$c, $m, $e] = $this->klass(); $s = $this->student($c, "1", null, null, [$m, $e]);
        $this->put(route("marks.update", $s), ["marks" => [$m->id => 50, $e->id => 30]])->assertSessionHasNoErrors();
        $this->put(route("marks.update", $s), ["marks" => [$m->id => 60, $e->id => 30]]);
        $this->assertSame(2, Mark::count()); $this->assertEquals(60, Mark::where("subject_id", $m->id)->value("marks_obtained"));
    }
    public function test_database_blocks_duplicate_mark_rows(): void
    {
        [$c, $m, $e] = $this->klass(); $s = $this->student($c, "1", 50, null, [$m, $e]);
        $this->expectException(QueryException::class);
        Mark::create(["student_id" => $s->id, "subject_id" => $m->id, "marks_obtained" => 70]);
    }
    public function test_pages_load_with_seeded_data(): void
    {
        $this->seed();
        foreach (["dashboard", "students.index", "classes.index", "subjects.index", "marks.index", "marksheets.index", "results.index"] as $r)
            $this->get(route($r))->assertOk();
        $this->get(route("marksheets.show", Student::first()))->assertOk();
    }
}
