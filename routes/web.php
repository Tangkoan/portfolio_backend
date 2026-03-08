<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AssignPermissionController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleAssignmentRuleController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ShopInfoController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\TechnologyController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SocialController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\AboutMeController;
use App\Http\Controllers\Admin\CertificateController;



use App\Http\Controllers\Frontend\PortfolioController;




use Illuminate\Support\Facades\Session;


/*
|--------------------------------------------------------------------------
| ផ្នែកទំព័រដើម (Portfolio)
|--------------------------------------------------------------------------
*/

// ជម្រើសទី ១៖ (ណែនាំ) ប្រើ '/' ជាទំព័រ Portfolio ផ្ទាល់តែម្តង (ឧ. kuytangkoan.online/)
Route::get('/', [PortfolioController::class, 'index'])->name('portfolio.home');

/* // ជម្រើសទី ២៖ បើនៅតែចង់ប្រើកន្ទុយ /kuytangkoan អាចប្រើកូដនេះវិញ (លុបជម្រើសទី១ចោល)
Route::get('/kuytangkoan', [PortfolioController::class, 'index'])->name('portfolio.home');
Route::get('/', function () {
    return redirect()->route('portfolio.home'); // វានឹងរុញពី / ទៅរក /kuytangkoan ដោយស្វ័យប្រវត្តិ
});
*/

// Route កំណត់ភាសា
Route::get('/change-language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'km'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back(); 
})->name('switch.language');


/*
|--------------------------------------------------------------------------
| ផ្នែកសម្រាប់អ្នកមិនទាន់ Login (Guest)
|--------------------------------------------------------------------------
*/

// អនុញ្ញាតអោយចូលបានតែ ៥ ដងប៉ុណ្ណោះក្នុង ១ នាទី (60s) ដើម្បីការពារការវាយប្រហារ (Brute Force)
Route::middleware(['guest', 'throttle:5,1'])->group(function () {
    
    // បង្ហាញ Login Form
    // សំខាន់៖ ត្រូវតែដាក់ name('login') ដើម្បីអោយ Middleware 'auth' ស្គាល់កន្លែងដែលត្រូវរុញមកពេលគេមិនទាន់ Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

    // Post ទិន្នន័យ Login
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    
});

// ==========================
// AUTH MIDDLEWARE
// ==========================
Route::middleware('auth')->group(function () {

    // ======================
    // Admin Dashboard
    // ======================
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile (Default + Custom)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/user', [UserController::class, 'userList'])->name('user.list');

    // ======================
    // All Routes Under /admin
    // ======================
    Route::prefix('admin')->name('admin.')->group(function () {

        // Theme
        Route::view('/theme', 'admin.theme')->name('theme')->middleware('permission:theme-color');
        Route::post('/theme/update', [ThemeController::class, 'update'])->name('theme.update')->middleware('permission:theme-save');

        // User Info
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::put('/profile', [UserController::class, 'updateProfile'])
            ->name('profile.update')
            ->middleware('permission:update-profile');

        // Change Password
        Route::get('/password', [UserController::class, 'password'])->name('password');
        Route::put('/password', [UserController::class, 'updatePassword'])->name('password.update')->middleware('permission:update-password');

        
        // ======================
        // Shop Info CRUD
        // ======================
        Route::controller(ShopInfoController::class)->group(function () {
            Route::get('/shop-info', [ShopInfoController::class, 'index'])->name('shop_info.index')->middleware('permission:setting-shop_info');
            Route::post('/shop-info/save', [ShopInfoController::class, 'save'])->name('shop_info.save')->middleware('permission:shop-info-save');            
        });
        
        // ======================
        // USER CRUD
        // ======================
        Route::controller(UserController::class)->group(function () {

            Route::get('/users', 'index')
                ->name('users.index')
                ->middleware('permission:user-list');

            Route::get('/users/fetch', 'fetchUsers')
                ->name('users.fetch')
                ->middleware('permission:user-list');

            Route::post('/users', 'store')
                ->name('users.store')
                ->middleware('permission:user-create');

            Route::put('/users/{id}', 'update')
                ->name('users.update')
                ->middleware('permission:user-edit');

            Route::delete('/users/{id}', 'destroy')
                ->name('users.destroy')
                ->middleware('permission:user-delete');

            Route::post('/users/bulk-delete', 'bulkDestroy')
                ->name('users.bulk_delete')
                ->middleware('permission:user-delete');

            Route::post('/users/bulk-update', 'bulkUpdate')
                ->name('users.bulk_update')
                ->middleware('permission:user-edit');
        });


        // ======================
        // ROLE
        // ======================
        Route::controller(RoleController::class)->group(function () {

            Route::get('/roles', 'index')
                ->name('roles.index')
                ->middleware('permission:role-list');

            Route::get('/roles/fetch', 'fetchRoles')
                ->name('roles.fetch')
                ->middleware('permission:role-list');

            Route::post('/roles', 'store')
                ->name('roles.store')
                ->middleware('permission:role-create');

            Route::put('/roles/{id}', 'update')
                ->name('roles.update')
                ->middleware('permission:role-edit');

            Route::delete('/roles/{id}', 'destroy')
                ->name('roles.destroy')
                ->middleware('permission:role-delete');

            Route::post('/roles/bulk-delete', 'bulkDelete')
                ->name('roles.bulk_delete')
                ->middleware('permission:role-delete');
        });


        // ======================
        // PERMISSION ASSIGN TO ROLE
        // ======================
        Route::controller(AssignPermissionController::class)->group(function () {

            Route::get('/assign-permissions/{roleId}', 'fetchRolePermissions')
                ->name('assign_permissions.fetch')
                ->middleware('permission:role-list');

            Route::post('/assign-permissions', 'update')
                ->name('assign_permissions.update')
                ->middleware('permission:role-assign');
        });


        // ======================
        // PERMISSION CRUD
        // ======================
        Route::controller(PermissionController::class)->group(function () {

            Route::get('/permissions', 'index')
                ->name('permissions.index')
                ->middleware('permission:permission-list');

            Route::get('/permissions/fetch', 'fetchPermissions')
                ->name('permissions.fetch')
                ->middleware('permission:permission-list');

            Route::post('/permissions', 'store')
                ->name('permissions.store')
                ->middleware('permission:permission-create');

            Route::put('/permissions/{id}', 'update')
                ->name('permissions.update')
                ->middleware('permission:permission-edit');

            Route::delete('/permissions/{id}', 'destroy')
                ->name('permissions.destroy')
                ->middleware('permission:permission-delete');

            Route::post('/permissions/bulk-delete', 'bulkDelete')
                ->name('permissions.bulk_delete')
                ->middleware('permission:permission-delete');
        });


        // ======================
        // ROLE ASSIGNMENT RULE (SUPER ADMIN ONLY)
        // ======================
        // ប្រើ Permission ជំនួស Role ដើម្បីឱ្យ Admin ចូលបានដែរ (បើគាត់មានសិទ្ធិ)
        Route::middleware(['auth', 'can:rule-list'])->group(function () {
            Route::resource('rules', RoleAssignmentRuleController::class)
                ->only(['index', 'edit', 'update']);
        });



        // ប្រើ Permission: activity-list ដើម្បីចូលមើល
        Route::middleware(['auth', 'can:activity-list'])->group(function () {
            // 1. ទំព័រដើម (View)
            Route::get('/activity-logs', [ActivityLogController::class, 'index'])
                ->name('activity_logs.index');

            // 2. API សម្រាប់ទាញទិន្នន័យ (Ajax)
            Route::get('/activity-logs/fetch', [ActivityLogController::class, 'fetchLogs'])
                ->name('activity_logs.fetch');

            // 3. API លុប (ត្រូវការ Permission: activity-delete)
            Route::middleware(['can:activity-delete'])->group(function() {
                Route::delete('/activity-logs/{id}', [ActivityLogController::class, 'destroy'])
                    ->name('activity_logs.destroy');
                    
                Route::post('/activity-logs/bulk-delete', [ActivityLogController::class, 'bulkDelete'])
                    ->name('activity_logs.bulk_delete');
            });
        });

        


        Route::controller(TechnologyController::class)->group(function () {
            Route::get('/technologies', 'index')->name('technologies.index')->middleware('permission:technologies-list');
            Route::get('/technologies/fetch', 'fetch')->name('technologies.fetch')->middleware('permission:technologies-list');
            
            Route::post('/technologies', 'store')->name('technologies.store')->middleware('permission:technologies-create');
            Route::post('/technologies/bulk-delete', 'bulkDelete')->name('technologies.bulk-delete')->middleware('permission:technologies-delete');
            Route::post('/technologies/bulk-edit', 'bulkEdit')->name('technologies.bulk-edit')->middleware('permission:technologies-edit');

            

            Route::put('/technologies/{id}', 'update')->name('technologies.update')->middleware('permission:technologies-edit');
            Route::delete('/technologies/{id}', 'destroy')->name('technologies.destroy')->middleware('permission:technologies-delete');
            
            Route::post('/technologies/{id}/toggle', 'toggleStatus')->name('technologies.toggle')->middleware('permission:technologies-edit-status');
        });


        Route::controller(ExperienceController::class)->group(function () {
            Route::get('/experiences', 'index')->name('experiences.index')->middleware('permission:experiences-list');
            Route::get('/experiences/fetch', 'fetch')->name('experiences.fetch')->middleware('permission:experiences-list');
            
            Route::post('/experiences', 'store')->name('experiences.store')->middleware('permission:experiences-create');
            Route::post('/experiences/bulk-delete', 'bulkDelete')->name('experiences.bulk-delete')->middleware('permission:experiences-delete');
            Route::post('/experiences/bulk-edit', 'bulkEdit')->name('experiences.bulk-edit')->middleware('permission:experiences-edit');

            

            Route::put('/experiences/{id}', 'update')->name('experiences.update')->middleware('permission:experiences-edit');
            Route::delete('/experiences/{id}', 'destroy')->name('experiences.destroy')->middleware('permission:experiences-delete');
            
            Route::post('/experiences/{id}/toggle', 'toggleStatus')->name('experiences.toggle')->middleware('permission:experiences-edit-status');
        });
        
        Route::controller(ProjectController::class)->group(function () {
            Route::get('/projects', 'index')->name('projects.index')->middleware('permission:projects-list');
            Route::get('/projects/fetch', 'fetch')->name('projects.fetch')->middleware('permission:projects-list');
            
            Route::post('/projects', 'store')->name('projects.store')->middleware('permission:projects-create');
            Route::post('/projects/bulk-delete', 'bulkDelete')->name('projects.bulk-delete')->middleware('permission:projects-delete');
            Route::post('/projects/bulk-edit', 'bulkEdit')->name('projects.bulk-edit')->middleware('permission:projects-edit');

            

            Route::put('/projects/{id}', 'update')->name('projects.update')->middleware('permission:projects-edit');
            Route::delete('/projects/{id}', 'destroy')->name('projects.destroy')->middleware('permission:projects-delete');
            
            Route::post('/projects/{id}/toggle', 'toggleStatus')->name('projects.toggle')->middleware('permission:projects-edit-status');
        });
        



        Route::controller(SocialController::class)->group(function () {
            Route::get('/socials', 'index')->name('socials.index')->middleware('permission:socials-list');
            Route::get('/socials/fetch', 'fetch')->name('socials.fetch')->middleware('permission:socials-list');
            
            Route::post('/socials', 'store')->name('socials.store')->middleware('permission:socials-create');
            Route::post('/socials/bulk-delete', 'bulkDelete')->name('socials.bulk-delete')->middleware('permission:socials-delete');
            Route::post('/socials/bulk-edit', 'bulkEdit')->name('socials.bulk-edit')->middleware('permission:socials-edit');

            

            Route::put('/socials/{id}', 'update')->name('socials.update')->middleware('permission:socials-edit');
            Route::delete('/socials/{id}', 'destroy')->name('socials.destroy')->middleware('permission:socials-delete');
            
            Route::post('/socials/{id}/toggle', 'toggleStatus')->name('socials.toggle')->middleware('permission:socials-edit-status');
        });

        Route::controller(ToolController::class)->group(function () {
            Route::get('/tools', 'index')->name('tools.index')->middleware('permission:tools-list');
            Route::get('/tools/fetch', 'fetch')->name('tools.fetch')->middleware('permission:tools-list');
            
            Route::post('/tools', 'store')->name('tools.store')->middleware('permission:tools-create');
            Route::post('/tools/bulk-delete', 'bulkDelete')->name('tools.bulk-delete')->middleware('permission:tools-delete');
            Route::post('/tools/bulk-edit', 'bulkEdit')->name('tools.bulk-edit')->middleware('permission:tools-edit');

            Route::put('/tools/{id}', 'update')->name('tools.update')->middleware('permission:tools-edit');
            Route::delete('/tools/{id}', 'destroy')->name('tools.destroy')->middleware('permission:tools-delete');
            
            Route::post('/tools/{id}/toggle', 'toggleStatus')->name('tools.toggle')->middleware('permission:tools-edit-status');
        });


        Route::controller(AboutMeController::class)->group(function () {
            Route::get('/about-me', 'index')->name('about_me.index')->middleware('permission:about_me-list');
            Route::get('/about-me/fetch', 'fetch')->name('about_me.fetch')->middleware('permission:about_me-list');
            
            Route::post('/about-me', 'store')->name('about_me.store')->middleware('permission:about_me-create');
            Route::post('/about-me/bulk-delete', 'bulkDelete')->name('about_me.bulk-delete')->middleware('permission:about_me-delete');

            Route::put('/about-me/{id}', 'update')->name('about_me.update')->middleware('permission:about_me-edit');
            Route::delete('/about-me/{id}', 'destroy')->name('about_me.destroy')->middleware('permission:about_me-delete');
            
            Route::post('/about-me/{id}/toggle', 'toggleStatus')->name('about_me.toggle')->middleware('permission:about_me-edit-status');
        });


        Route::controller(CertificateController::class)->group(function () {
            Route::get('/certificates', 'index')->name('certificates.index')->middleware('permission:certificates-list');
            Route::get('/certificates/fetch', 'fetch')->name('certificates.fetch')->middleware('permission:certificates-list');
            
            Route::post('/certificates', 'store')->name('certificates.store')->middleware('permission:certificates-create');
            Route::post('/certificates/bulk-delete', 'bulkDelete')->name('certificates.bulk-delete')->middleware('permission:certificates-delete');

            Route::put('/certificates/{id}', 'update')->name('certificates.update')->middleware('permission:certificates-edit');
            Route::delete('/certificates/{id}', 'destroy')->name('certificates.destroy')->middleware('permission:certificates-delete');
            
            Route::post('/certificates/{id}/toggle', 'toggleStatus')->name('certificates.toggle')->middleware('permission:certificates-edit-status');
        });
        

    });
});



// require __DIR__.'/auth.php'; // បិទចោលសិន កុំអោយជាន់គ្នាជាមួយ Custom Auth របស់យើង