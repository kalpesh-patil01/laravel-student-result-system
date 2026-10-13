<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Student extends Model {
    protected $fillable = ["roll_number", "name", "class_id"];
    public function schoolClass() { return $this->belongsTo(SchoolClass::class, "class_id"); }
    public function marks() { return $this->hasMany(Mark::class); }
}
