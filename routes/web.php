<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\CourseRegisterController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\PrivatefileController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/activities-all', [HomeController::class, 'activities_all'])->name('activities.all');

Route::get('/admin/courses', [CourseController::class, 'index'])->name('courses.index');


// หรือถ้าใช้ Resource Route (แนะนำ)
Route::resource('admin/courses', CourseController::class);

Route::get('/dashboard', function () {
        return redirect()->route('courses.index');
})->middleware(['auth'])->name('dashboard');


Route::get('courses/{course}/lessons', [LessonController::class, 'index'])->name('lessons.index');
Route::get('courses/{course}/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
Route::post('courses/{course}/lessons', [LessonController::class, 'store'])->name('lessons.store');

Route::get('courses/{course}/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
Route::get('courses/{course}/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
Route::post('courses/{course}/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
Route::put('quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
Route::get('quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');

Route::get('lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
Route::put('lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
Route::delete('lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');



Route::get('categories', [CategoriesController::class, 'index'])->name('categories.index');
Route::delete('categories/{category}', [CategoriesController::class, 'destroy'])->name('categories.destroy');
Route::get('categories/create', [CategoriesController::class, 'create'])->name('categories.create');
Route::post('categories', [CategoriesController::class, 'store'])->name('categories.store');
Route::get('categories/{category}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
Route::put('categories/{category}', [CategoriesController::class, 'update'])->name('categories.update');



Route::get('lecturer', [InstructorController::class, 'index'])->name('lecturer.index');
Route::post('lecturer.store', [InstructorController::class, 'store'])->name('lecturer.store');
Route::delete('lecturer/{user}', [InstructorController::class, 'destroy'])->name('lecturer.destroy');
Route::get('lecturer/create', [InstructorController::class, 'create'])->name('lecturer.create');
Route::get('lecturer/{user}/edit', [InstructorController::class, 'edit'])->name('lecturer.edit');
Route::get('districts/{provinceCode}', [InstructorController::class, 'getDistricts'])->name('districts.get');
Route::get('subdistricts/{districtCode}', [InstructorController::class, 'getSubdistricts'])->name('subdistricts.get');
Route::get('zipcode/{subdistrictCode}', [InstructorController::class, 'getZipcode'])->name('zipcode.get');


Route::get('/courses/{id}', [HomeController::class, 'courses_show'])->name('courses.show');
Route::get('/courses/{id}/register', [CourseRegisterController::class, 'courses_register'])->name('courses.register.view');
Route::post('/courses/{id}/register', [CourseRegisterController::class, 'courses_store'])->name('courses.register.submit');

Route::get('/courses', [HomeController::class, 'course_all'])->name('courses.all');
Route::get('/categoriesall', [HomeController::class, 'categories_all'])->name('categories.all');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');


Route::get('/courses/{id}/learn/{lesson_id?}', [CourseController::class, 'learn'])
     ->middleware(['auth'])
     ->name('courses.learn');

Route::post('/course/update-progress', [CourseController::class, 'updateProgress'])->name('course.progress.update');

Route::post('courses/add_file', [CourseController::class, 'addFile'])->name('courses.add_file');
Route::delete('courses/remove_file', [CourseController::class, 'removeFile'])->name('courses.remove_file');
Route::get('/courses/files/{id}', [CourseController::class, 'showFiles'])->name('courses.files.show');

// ส่วนของหน้าบ้าน (Public)

Route::get('/activities/{slug}', [ActivityController::class, 'show'])->name('activities.show');
// ตรวจสอบว่ามีการครอบด้วย 'admin.' หรือไม่
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    Route::resource('courses.quizzes', QuizController::class)->shallow();

    Route::get('quizzes/{quiz}/questions', [QuestionController::class, 'index'])->name('questions.index');

    Route::get('quizzes/{quiz}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::put('quizzes/{quiz}/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('quizzes/{quiz}/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    Route::get('quizzes/{quiz}/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');

    Route::resource('activities', ActivityController::class);

    Route::post('activities/{id}/toggle-status', [ActivityController::class, 'toggleStatus'])
        ->name('activities.toggle-status');

    Route::post('/patals', [InstructorController::class, 'patals_store'])->name('patals.store');
    Route::get('patals', [InstructorController::class, 'patals'])->name('patals.index');
    // Route สำหรับลบทางลัด
    Route::delete('/patals/{id}', [InstructorController::class, 'patals_destroy'])->name('patals.destroy');

    Route::get('/patals/{id}', [InstructorController::class, 'patals_show'])->name('patals.show');
    Route::post('/patals', [InstructorController::class, 'patals_store'])->name('patals.store');
    Route::post('/patals.detail.store', [InstructorController::class, 'patals_detail_store'])->name('patals_detail.store');
    Route::post('/patals_detail.destroy', [InstructorController::class, 'patals_detail_destroy'])->name('patals_detail.destroy');
    
    Route::get('/patals.detail/{id}', [InstructorController::class, 'patals_detail'])->name('patals.detail');
    Route::get('/patals.detail.show/{id}', [InstructorController::class, 'patals_detail_show'])->name('patals.detail.show');
    
    Route::get('patals', [InstructorController::class, 'patals'])->name('patals.index');
    Route::get('reports/student', [ReportController::class, 'index'])->name('reports.student');
    Route::get('reports/course', [ReportController::class, 'course_report'])->name('reports.course');

});

Route::get('/quiz/{quiz_id}', [QuizController::class, 'show'])->name('quiz.show');
Route::post('/quiz/submit', [QuizController::class, 'submit'])->name('quiz.submit');

Route::get('/amphurs/{provinceCode}', [StudentController::class, 'getAmphurs'])->name('amphurs.get');
Route::get('/districts/{amphurCode}', [StudentController::class, 'getDistricts'])->name('districts.get');

// กลุ่ม Route สำหรับ Admin
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // 1. หน้า List รายการ
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');

    // 2. หน้า Create (ต้องวางไว้ก่อน {id})
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');

    // 3. หน้าจัดการรายบุคคล (Dynamic ID)
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

    // 4. หน้าจัดการไฟล์ส่วนตัว (Private Files)
    Route::get('/privatefiles', [PrivatefileController::class, 'index'])->name('privatefiles.index');
    Route::get('/privatefiles/create', [PrivatefileController::class, 'create'])->name('privatefiles.create');
    Route::post('/privatefiles', [PrivatefileController::class, 'store'])->name('privatefiles.store');
    Route::delete('/privatefiles/{id}', [PrivatefileController::class, 'destroy'])->name('privatefiles.destroy');


});


    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');


require __DIR__.'/auth.php';
