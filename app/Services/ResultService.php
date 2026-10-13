<?php
namespace App\Services;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/** The ONLY place where totals, percentage, grade and pass/fail are decided. */
class ResultService
{
    /** [minimum percentage, grade] - checked from the top. Change grading here. */
    public const GRADES = [[90, "A+"], [80, "A"], [70, "B"], [60, "C"], [35, "D"]];

    public static function grade(float $percentage): string
    {
        foreach (self::GRADES as [$min, $grade]) if ($percentage >= $min) return $grade;
        return "F";
    }

    /** Result for one student. Missing marks => INCOMPLETE (never treated as 0). */
    public function evaluate(Student $student): array
    {
        $subjects = $student->schoolClass->subjects;
        $marks = $student->marks->pluck("marks_obtained", "subject_id");
        $rows = []; $total = 0.0; $max = 0.0; $missing = 0; $failed = 0;

        foreach ($subjects as $s) {
            $got = $marks->has($s->id) ? (float) $marks[$s->id] : null;
            $max += $s->maximum_marks;
            if ($got === null) { $missing++; $passed = null; }
            else { $total += $got; $passed = $got >= $s->passing_marks; if (!$passed) $failed++; }
            $rows[] = ["subject" => $s, "obtained" => $got, "passed" => $passed];
        }

        $complete = $subjects->isNotEmpty() && $missing === 0;
        $pct = ($complete && $max > 0) ? $total / $max * 100 : null;   // unrounded
        return [
            "rows" => $rows, "complete" => $complete, "max" => $max,
            "total" => $complete ? $total : null,
            "percentage" => $pct,
            "grade" => $pct === null ? null : self::grade($pct),
            "status" => !$complete ? "INCOMPLETE" : ($failed > 0 ? "FAIL" : "PASS"),
        ];
    }

    /** Students list (filtered, paginated) with each student's result attached. */
    public function paginateWithResults(Request $r)
    {
        $page = Student::with(["schoolClass.subjects", "marks"])
            ->when($r->class_id, fn ($q, $c) => $q->where("class_id", $c))
            ->when($r->q, fn ($q, $t) => $q->where(fn ($w) => $w->where("name", "like", "%$t%")->orWhere("roll_number", "like", "%$t%")))
            ->orderBy("class_id")->orderBy("roll_number")->paginate(15)->withQueryString();
        $page->getCollection()->each(fn ($s) => $s->result = $this->evaluate($s));
        return $page;
    }

    /**
     * Class-wise summary using SQL aggregation. One row per STUDENT is built first
     * (joined to the class subjects), then counted per class - so students are never
     * overcounted by their number of subjects.
     */
    public function classSummary(): Collection
    {
        $perStudent = DB::table("students as st")
            ->join("subjects as sb", "sb.class_id", "=", "st.class_id")
            ->leftJoin("marks as m", function ($j) { $j->on("m.subject_id", "=", "sb.id")->on("m.student_id", "=", "st.id"); })
            ->groupBy("st.id", "st.class_id")
            ->selectRaw("st.id as sid, st.class_id, COUNT(sb.id) as req, COUNT(m.id) as entered,
                         SUM(CASE WHEN m.marks_obtained < sb.passing_marks THEN 1 ELSE 0 END) as failed_subjects");

        return DB::table("classes as c")
            ->leftJoinSub($perStudent, "r", "r.class_id", "=", "c.id")
            ->groupBy("c.id", "c.class_name")->orderBy("c.class_name")
            ->selectRaw("c.id, c.class_name,
                (SELECT COUNT(*) FROM students WHERE students.class_id = c.id) as enrolled,
                SUM(CASE WHEN r.entered = r.req AND r.failed_subjects = 0 THEN 1 ELSE 0 END) as passed,
                SUM(CASE WHEN r.entered = r.req AND r.failed_subjects > 0 THEN 1 ELSE 0 END) as failed")
            ->get()->map(function ($row) {
                $row->enrolled = (int) $row->enrolled; $row->passed = (int) $row->passed; $row->failed = (int) $row->failed;
                $row->completed = $row->passed + $row->failed;
                $row->incomplete = $row->enrolled - $row->completed;
                // pass % = passed / COMPLETED results (null when nothing is completed)
                $row->pass_rate = $row->completed > 0 ? round($row->passed / $row->completed * 100, 2) : null;
                return $row;
            });
    }
}
