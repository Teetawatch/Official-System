<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Typing System Routes (Phase 1 - Mock UI)
|--------------------------------------------------------------------------
|
| Mock routes for testing UI without authentication.
| These will be replaced with proper auth in Phase 2.
|
*/

Route::prefix('typing')->name('typing.')->group(function () {
    // Login Page (Guest only)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
        
        // Student Registration Routes
        Route::get('/register', [App\Http\Controllers\StudentRegistrationController::class, 'showForm'])->name('student-register');
        Route::post('/register', [App\Http\Controllers\StudentRegistrationController::class, 'register'])->name('student-register.submit');
        Route::get('/register/students', [App\Http\Controllers\StudentRegistrationController::class, 'getStudentsByClass'])->name('student-register.students');
    });
    
    Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Protected Routes
    Route::middleware('auth')->group(function () {
        
        // Admin Routes
        Route::prefix('admin')->name('admin.')
            ->middleware('role:admin')
            ->group(function () {
                Route::get('/dashboard', [App\Http\Controllers\TypingAdminController::class, 'dashboard'])->name('dashboard');
                Route::get('/students', [App\Http\Controllers\TypingAdminController::class, 'students'])->name('students.index');
                Route::get('/students/template', [App\Http\Controllers\TypingAdminController::class, 'downloadTemplate'])->name('students.template');
                Route::post('/students/import', [App\Http\Controllers\TypingAdminController::class, 'importStudents'])->name('students.import');
                Route::get('/students/create', [App\Http\Controllers\TypingAdminController::class, 'createStudent'])->name('students.create');
                Route::post('/students', [App\Http\Controllers\TypingAdminController::class, 'storeStudent'])->name('students.store');
                Route::get('/students/{id}/edit', [App\Http\Controllers\TypingAdminController::class, 'editStudent'])->name('students.edit');
                Route::put('/students/{id}', [App\Http\Controllers\TypingAdminController::class, 'updateStudent'])->name('students.update');
                Route::delete('/students/{id}', [App\Http\Controllers\TypingAdminController::class, 'destroyStudent'])->name('students.destroy');
                Route::resource('assignments', App\Http\Controllers\TypingAssignmentController::class);
                Route::get('/submissions', [App\Http\Controllers\TypingAdminController::class, 'submissions'])->name('submissions');
                Route::post('/submissions/{id}/score', [App\Http\Controllers\TypingAdminController::class, 'updateScore'])->name('submissions.score');
                Route::get('/submissions/{id}/pdf', [App\Http\Controllers\TypingPDFController::class, 'download'])->name('submissions.pdf');
                Route::get('/grades', [App\Http\Controllers\TypingAdminController::class, 'grades'])->name('grades');
                Route::get('/grades/export/csv', [App\Http\Controllers\TypingAdminController::class, 'exportGradesCsv'])->name('grades.export.csv');
                Route::get('/grades/export/pdf', [App\Http\Controllers\TypingAdminController::class, 'exportGradesPdf'])->name('grades.export.pdf');
                Route::get('/submissions/export/zip', [App\Http\Controllers\TypingAdminController::class, 'exportSubmissionsZip'])->name('submissions.export.zip');
                Route::post('/submissions/{id}/auto-grade', [App\Http\Controllers\TypingAdminController::class, 'autoGradeSubmission'])->name('submissions.autograde');
                Route::post('/submissions/auto-grade-all/{assignmentId}', [App\Http\Controllers\TypingAdminController::class, 'autoGradeAllSubmissions'])->name('submissions.autograde.all');
            });
        
        // Student Routes
        Route::prefix('student')->name('student.')
            ->middleware('role:student')
            ->group(function () {
                Route::get('/dashboard', [App\Http\Controllers\TypingController::class, 'dashboard'])->name('dashboard');
                Route::get('/assignments', [App\Http\Controllers\TypingController::class, 'assignments'])->name('assignments');
                Route::get('/submissions', [App\Http\Controllers\TypingController::class, 'submissions'])->name('submissions');
                Route::get('/submissions/{id}', [App\Http\Controllers\TypingController::class, 'showSubmission'])->name('submissions.show');
                Route::get('/grades', [App\Http\Controllers\TypingController::class, 'grades'])->name('grades');

                // Practice Routes
                Route::get('/practice/{id}', [App\Http\Controllers\TypingController::class, 'practice'])->name('practice');
                Route::post('/practice/{id}/submit', [App\Http\Controllers\TypingController::class, 'store'])->name('practice.submit');

                // File Upload Routes
                Route::get('/upload/{id}', [App\Http\Controllers\TypingController::class, 'showUpload'])->name('upload');
                Route::post('/upload/{id}', [App\Http\Controllers\TypingController::class, 'storeUpload'])->name('upload.submit');

                // 1v1 Match Routes
                Route::get('/matches', [App\Http\Controllers\TypingMatchController::class, 'index'])->name('matches.index');
                Route::post('/matches/find', [App\Http\Controllers\TypingMatchController::class, 'findMatch'])->name('matches.find');
                Route::get('/matches/{id}', [App\Http\Controllers\TypingMatchController::class, 'show'])->name('matches.show');
                Route::get('/matches/{id}/status', [App\Http\Controllers\TypingMatchController::class, 'status'])->name('matches.status');
                Route::post('/matches/{id}/progress', [App\Http\Controllers\TypingMatchController::class, 'updateProgress'])->name('matches.progress');
                Route::post('/matches/{id}/finish', [App\Http\Controllers\TypingMatchController::class, 'finish'])->name('matches.finish');
            });
        
        // Shared Routes
        Route::get('/leaderboard', [App\Http\Controllers\TypingController::class, 'leaderboard'])->name('leaderboard');
        
        // Notification Routes
        Route::get('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::get('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
        
        // Profile Routes
        Route::get('/profile', [App\Http\Controllers\TypingProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\TypingProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [App\Http\Controllers\TypingProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/avatar', [App\Http\Controllers\TypingProfileController::class, 'updateAvatar'])->name('profile.avatar');
        
        // Reward Shop Routes
        Route::prefix('shop')->name('shop.')->group(function () {
            Route::get('/', [App\Http\Controllers\RewardShopController::class, 'index'])->name('index');
            Route::post('/purchase/{id}', [App\Http\Controllers\RewardShopController::class, 'purchase'])->name('purchase');
            Route::get('/my-rewards', [App\Http\Controllers\RewardShopController::class, 'myRewards'])->name('my-rewards');
            Route::post('/equip/{id}', [App\Http\Controllers\RewardShopController::class, 'equip'])->name('equip');
            Route::post('/unequip/{id}', [App\Http\Controllers\RewardShopController::class, 'unequip'])->name('unequip');
        });
        
        // Redirect base /typing to appropriate dashboard based on role
        Route::get('/', function () {
            if (auth()->user()->role === 'admin') {
                return redirect()->route('typing.admin.dashboard');
            }
            return redirect()->route('typing.student.dashboard');
        });
    });
});

