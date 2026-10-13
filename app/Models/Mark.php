<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model {
    protected $fillable = ["student_id", "subject_id", "marks_obtained"];
    protected $casts = ["marks_obtained" => "float"];
    public function student() { return $this->belongsTo(Student::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
}
