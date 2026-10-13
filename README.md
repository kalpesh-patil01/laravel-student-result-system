# Student Result and Marksheet Management System (Laravel overlay)

This ZIP contains the application files only (migration, models, `ResultService`, controllers,
routes, Blade views, CSS, seeder, tests). It does NOT contain the Laravel framework itself,
`vendor/`, or `composer.lock` - you create those with Composer in step 1.

## Setup (Windows + Laragon + Composer)
1. Create a fresh Laravel project (Laragon: Menu > Quick app > Laravel, or in the terminal):
   `composer create-project laravel/laravel result-management-system`
2. Extract this ZIP INTO that project folder and allow overwriting `routes/web.php`
   (the folders `app`, `database`, `public`, `resources`, `routes`, `tests` merge).
   Delete `tests/Feature/ExampleTest.php` if it fails (it expects the default welcome page).
3. In Laragon open HeidiSQL and create an empty database `result_management`.
4. Edit `.env`:
   DB_CONNECTION=mysql, DB_HOST=127.0.0.1, DB_PORT=3306, DB_DATABASE=result_management, DB_USERNAME=root, DB_PASSWORD=(blank)
5. Run:
   `php artisan migrate --seed`
   `php artisan serve`   (or open http://result-management-system.test in Laragon)
6. Tests (use an in-memory SQLite DB, not your MySQL data; needs the pdo_sqlite extension): `php artisan test`

## Design notes
- All calculations live in `app/Services/ResultService.php` (grades, totals, pass/fail, class summary SQL).
- Subjects belong to a class; there is no separate examinations table (one result set per student).
- Blank marks = not entered. Such students are INCOMPLETE and excluded from pass/fail counts.
- Pass % = passed / completed results (N/A when none completed).
- Grades: 90+ A+, 80+ A, 70+ B, 60+ C, 35+ D, else F (percentage-based). Pass needs every subject at/above its passing marks.
- Classes/subjects that still have students/marks cannot be deleted.
- Bootstrap and icons load from a CDN, so an internet connection is needed to view the styling.
