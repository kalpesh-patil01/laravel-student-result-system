<?php
namespace App\Http\Controllers;
use App\Services\ResultService;

class ResultController extends Controller {
    public function index(ResultService $svc) { return view("results.index", ["rows" => $svc->classSummary()]); }
}
