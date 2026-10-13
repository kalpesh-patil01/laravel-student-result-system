<?php
namespace Database\Seeders;
use App\Models\{Mark, SchoolClass, Student, Subject};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [["Mathematics", 100, 35], ["English", 100, 35], ["Science", 100, 35], ["Computer", 50, 18]];
        $data = [
            "FY BCA" => [["Aarav Patil", [92, 88, 95, 47]], ["Isha Kulkarni", [81, 76, 84, 40]], ["Rohan Deshmukh", [72, 65, 70, 35]],
                         ["Sneha Joshi", [62, 58, 66, 30]], ["Vikram More", [28, 55, 61, 33]], ["Neha Shinde", [74, 68, null, 36]]],
            "SY BCA" => [["Kunal Pawar", [55, 48, 52, 25]], ["Pooja Jadhav", [88, 91, 79, 44]], ["Omkar Gaikwad", [40, 33, 45, 20]], ["Riya Sawant", [null, null, null, null]]],
            "TY BCA" => [["Tanvi Bhosale", [null, null, null, null]], ["Yash Kale", [null, 70, null, null]]],   // no completed results
            "Empty Class" => [],
        ];
        foreach ($data as $cname => $students) {
            $class = SchoolClass::create(["class_name" => $cname]);
            if ($cname === "Empty Class") continue;
            $subs = collect($subjects)->map(fn ($s) => Subject::create(["class_id" => $class->id, "subject_name" => $s[0], "maximum_marks" => $s[1], "passing_marks" => $s[2]]));
            foreach ($students as $i => [$name, $marks]) {
                $st = Student::create(["class_id" => $class->id, "roll_number" => (string) ($i + 1), "name" => $name]);
                foreach ($marks as $k => $m) if ($m !== null) Mark::create(["student_id" => $st->id, "subject_id" => $subs[$k]->id, "marks_obtained" => $m]);
            }
        }
    }
}
