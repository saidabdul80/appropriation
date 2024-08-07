<?php

use App\Http\Controllers\SchemeController;
use App\Http\Controllers\AppropriationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubHeadController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\SubHeadBudgetController;
use App\Http\Controllers\SubheadBudgetItemController;
use App\Http\Controllers\SubheadBudgetItemNameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VirementController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Role;

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

Route::get('/', function () {
    return  redirect('/login');
    //Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Auth::routes();
Route::group(["middleware"=>['web','auth']],function(){

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/user', [UserController::class, 'userIndex'])->middleware('permission:manage_user')->name('user');
    Route::post('/get_users', [UserController::class, 'getUsers']);
    Route::post('/new_update_user', [UserController::class, 'newUpdateUser']);
    Route::post('/change_password', [UserController::class, 'changePassword']);
    Route::post('/assign_role', [UserController::class, 'assignRole']);
    Route::post('/assign_permission', [UserController::class, 'assignPermission']);
    Route::get('/scheme', [SchemeController::class, 'index'])->name('account');
    Route::get('/scheme2', [SchemeController::class, 'schemeScreen'])->name('account2');
    Route::post('/add_appropriation', [SchemeController::class, 'addAppropriation']);
    Route::post('/appropriation/delete/{id}', [SchemeController::class, 'deleteAppropriation']);
    Route::post('/add_scheme', [SchemeController::class, 'addScheme']);
    Route::get('/schemes', [SchemeController::class, 'getSchemes']);
    Route::post('/fund_programme', [SchemeController::class, 'fundProgramme']);
    Route::post('/undo_fund_programme', [SchemeController::class, 'reverseFundProgramme']);
    Route::post('/fetch_fund', [FundController::class, 'fetchFund']);
    Route::post('/dashborad_data', [DashboardController::class, 'byProgramme']);

    Route::post('/direct_fund_scheme', [SchemeController::class, 'directFundScheme']);

    Route::post('/appropriate', [SchemeController::class, 'appropriate']);
    Route::post('/get_appropriation_histories', [AppropriationController::class, 'getAppropriations']);
    Route::post('/fund_month_year', [AppropriationController::class, 'fundMonthYear']);
    Route::post('/get_prepared_data', [AppropriationController::class, 'getPreparedData']);
    Route::post('/get_appropriations', [AppropriationController::class, 'FundCategoryAppropriations']);
    Route::post('/get_amount_summary_data', [AppropriationController::class, 'getAmountSummaryData']);
    Route::get('/get_appropriation_types', [AppropriationController::class, 'getApproTypes']);

    Route::get('/department', [DepartmentController::class, 'index'])->name('department');
    Route::post('/department/create_update', [DepartmentController::class, 'createUpdate']);
    Route::post('/get_appropriations_projection', [AppropriationController::class, 'getAppropriationsProjection']);
    Route::post('/get_transactions', [TransactionsController::class, 'transactions']);
    Route::post('/save_appropriation_transaction', [TransactionsController::class, 'saveAppropriationTransaction']);
    Route::post('/upload_appropriation_transaction', [TransactionsController::class, 'uploadAppropriationTransaction']);
    Route::post('/delete_appropriation_transaction', [TransactionsController::class, 'deleteAppropriationTransaction']);
    Route::post('/get_wallet_detail', [WalletController::class, 'getWalletsBalance']);
    Route::post('/fetch_expenditures', [TransactionsController::class, 'expenditureDetails']);
    Route::get('/report/{scheme_id}', [HomeController::class, 'report']);
    Route::post('/get_appropriation_transactions', [TransactionsController::class, 'appropriationTransactions']);

    Route::get('/sub_head_budgets', [SubHeadBudgetController::class, 'index']);
    Route::get('/sub_head_budget/appropriation/{id}/{fund_category}', [SubHeadBudgetController::class, 'getSubHeadBudgetByAppropriation']);
    Route::post('/sub_head_budget/save', [SubHeadBudgetController::class, 'updateCreate']);
    Route::post('/sub_head_budget/delete/{id}', [SubHeadBudgetController::class, 'destroy']);

    Route::post('/subhead/create', [SubHeadController::class, 'store']);
    Route::post('/subhead/update/{id}', [SubHeadController::class, 'update']);
    Route::get('/subhead/{search?}', [SubHeadController::class, 'index']);

    Route::get('/sub_head_budget_item', [SubheadBudgetItemController::class, 'index']);
    Route::get('/sub_head_budget_item/subhead_budget/{id}', [SubheadBudgetItemController::class, 'bySubheadBudgetId']);
    Route::post('/sub_head_budget_item/save', [SubheadBudgetItemController::class, 'updateCreate']);
    Route::post('/sub_head_budget_item/delete/{id}', [SubheadBudgetItemController::class, 'destroy']);
    Route::post('/sub_head_budget_item/virement', [VirementController::class, 'subheadBudgetItem']);


    Route::post('/subhead_item_name/create', [SubheadBudgetItemNameController::class, 'store']);
    Route::post('/subhead_item_name/delete/{id}', [SubheadBudgetItemNameController::class, 'destroy']);
    Route::get('/subhead_item_name/{search?}', [SubheadBudgetItemNameController::class, 'index']);

    /* Route::post('/search_subhead', [SubHeadController::class, 'searchSubhead']); */
    Route::post('/delete', [SubHeadController::class, 'destroy']);
    Route::post('/get_fund_categories', [SchemeController::class, 'fetchFundCategories']);

});
