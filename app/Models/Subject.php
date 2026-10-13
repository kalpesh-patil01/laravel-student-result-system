<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model {
    protected $fillable = ["subject_name", "class_id", "maximum_marks", "passing_marks"];
    protected $casts = ["maximum_marks" => "float", "passing_marks" => "float"];
    public function schoolClass() { return $this->belongsTo(SchoolClass::class, "class_id"); }
    public function marks() { return $this->hasMany(Mark::class); }
}
