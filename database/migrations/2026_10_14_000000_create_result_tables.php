<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("classes", function (Blueprint $t) {
            $t->id(); $t->string("class_name", 100)->unique(); $t->timestamps();
        });
        Schema::create("students", function (Blueprint $t) {
            $t->id(); $t->string("roll_number", 30); $t->string("name", 150);
            $t->foreignId("class_id")->constrained("classes")->restrictOnDelete();
            $t->timestamps();
            $t->unique(["class_id", "roll_number"]);
        });
        Schema::create("subjects", function (Blueprint $t) {
            $t->id(); $t->string("subject_name", 100);
            $t->foreignId("class_id")->constrained("classes")->restrictOnDelete();
            $t->decimal("maximum_marks", 6, 2); $t->decimal("passing_marks", 6, 2);
            $t->timestamps();
            $t->unique(["class_id", "subject_name"]);
        });
        Schema::create("marks", function (Blueprint $t) {
            $t->id();
            $t->foreignId("student_id")->constrained("students")->cascadeOnDelete();
            $t->foreignId("subject_id")->constrained("subjects")->restrictOnDelete();
            $t->decimal("marks_obtained", 6, 2); $t->timestamps();
            $t->unique(["student_id", "subject_id"]);
        });
    }
    public function down(): void {
        foreach (["marks", "subjects", "students", "classes"] as $n) Schema::dropIfExists($n);
    }
};
