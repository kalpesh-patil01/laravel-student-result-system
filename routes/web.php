<?php
use App\Http\Controllers\{ClassController, DashboardController, MarksController, MarksheetController, ResultController, StudentController, SubjectController};
use Illuminate\Support\Facades\Route;

Route::get("/", [DashboardController::class, "index"])->name("dashboard");
Route::resource("students", StudentController::class)->except("show");
Route::resource("classes", ClassController::class)->parameters(["classes" => "schoolClass"])->except("show");
Route::resource("subjects", SubjectController::class)->except("show");
Route::get("marks", [MarksController::class, "index"])->name("marks.index");
Route::get("marks/{student}", [MarksController::class, "edit"])->name("marks.edit");
Route::put("marks/{student}", [MarksController::class, "update"])->name("marks.update");
Route::get("marksheets", [MarksheetController::class, "index"])->name("marksheets.index");
Route::get("marksheets/{student}", [MarksheetController::class, "show"])->name("marksheets.show");
Route::get("results", [ResultController::class, "index"])->name("results.index");
