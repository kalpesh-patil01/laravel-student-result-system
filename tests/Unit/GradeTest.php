<?php
namespace Tests\Unit;

use App\Services\ResultService;
use PHPUnit\Framework\TestCase;

class GradeTest extends TestCase
{
    public function test_grade_boundaries(): void
    {
        $cases = [
            [100, "A+"], [90, "A+"], [89.99, "A"], [80, "A"], [79.99, "B"], [70, "B"],
            [69.99, "C"], [60, "C"], [59.99, "D"], [35, "D"], [34.99, "F"], [0, "F"],
        ];
        foreach ($cases as [$pct, $grade]) {
            $this->assertSame($grade, ResultService::grade((float) $pct), "at $pct");
        }
    }

    public function test_grade_uses_unrounded_percentage(): void
    {
        $this->assertSame("A", ResultService::grade(89.999)); // shows as 90.00 but is not yet A+
    }
}
