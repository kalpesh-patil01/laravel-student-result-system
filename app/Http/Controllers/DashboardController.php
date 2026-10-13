<?php
namespace App\Http\Controllers;
use App\Models\{SchoolClass, Student, Subject};
use App\Services\ResultService;

class DashboardController extends Controller {
    public function index(ResultService $svc) {
        $rows = $svc->classSummary();
        $passed = $rows->sum("passed"); $failed = $rows->sum("failed"); $done = $passed + $failed;
        return view("dashboard.index", [
            "rows" => $rows, "passed" => $passed, "failed" => $failed, "completed" => $done,
            "passRate" => $done ? round($passed / $done * 100, 2) : null,
            "students" => Student::count(), "classes" => SchoolClass::count(), "subjects" => Subject::count(),
            "recent" => Student::with("schoolClass")->latest()->take(5)->get(),
        ]);
    }
}
