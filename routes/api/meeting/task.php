<?php 
use App\Http\Controllers\Api\Meeting\Task\TaskController;
/** Task SECTION */
Route::group([
  'prefix' => 'tasks' ,
  'middleware' => 'auth:api'
  ], function() {
    /**
     * Methods to apply for each of the CRUD operations
     * Create => POST
     * Read => GET
     * Update => PUT
     * Delete => DELETE
     */

    /**
     * Get all records
     */
    Route::get('',[TaskController::class,'index'])->name("taskList");
    /**
     * Get a record with id
     */
    Route::get('{id}/read',[TaskController::class,'read'])->name("taskRead");
    /**
     * Create a record
     */
    Route::post('',[TaskController::class,'create'])->name("taskCreate");
    /**
     * Update a reccord with id
     */
    Route::put('',[TaskController::class,'update'])->name("taskUpdate");
    /**
     * Delete a record
     */
    Route::delete('{id}/delete',[TaskController::class,'delete'])->name("taskDelete");
    /**
     * Get assignees
     */
    Route::get('assignees',[TaskController::class,'getAssignees'])->name("assigneesRead");
    /**
     * Activate, Deactivate account
     */
    Route::put('activate',[TaskController::class,'activate'])->name('taskActivate');
    Route::put('deactivate',[TaskController::class,'deactivate'])->name('taskDeactivate');

    Route::put('start',[TaskController::class,'startTask'])->name('taskStart');
    Route::put('end',[TaskController::class,'endTask'])->name('taskEnd');
    Route::put('pending',[TaskController::class,'pendingTask'])->name('taskPending');
    Route::put('continue',[TaskController::class,'continueTask'])->name('taskContinue');
    /**
     * Get number of the tasks base on it status
     */
    Route::get('total_number_of_each_status',function(Request $request){
        return response()->json([
            'new' => \App\Models\Task\Task::getTotalNewTasks() ,
            'in_progress' => \App\Models\Task\Task::getTotalInProgressTasks() ,
            'pending' => \App\Models\Task\Task::getTotalPendingTasks() ,
            'ended' => \App\Models\Task\Task::getTotalEndedTasks()
        ],200);
    });
    Route::get('total_number_of_new',function(Request $request){
        return \App\Models\Task\Task::getTotalNewTasks();
    });
    Route::get('total_number_of_in_progress',function(Request $request){
        return \App\Models\Task\Task::getTotalInProgressTasks();
    });
    Route::get('total_number_of_pending',function(Request $request){
        return \App\Models\Task\Task::getTotalPendingTasks();
    });
    Route::get('total_number_of_ended',function(Request $request){
        return \App\Models\Task\Task::getTotalEndedTasks();
    });
    /**
     * Get total earn
     */
    Route::get('total_earn',function(){
        return \App\Models\Task\Task::getTotalEarn();
    });
    Route::get('total_earn_by_month_of_year/{date}',function(){
        return \App\Models\Task\Task::getTotalEarn($date);
    });
    Route::get('total_earn_between/{start}/{end}',function(){
        return \App\Models\Task\Task::getTotalEarn($start,$end);
    });
    /**
     * Get total expense
     */
    Route::get('total_expense',function(){
        return \App\Models\Task\Task::getTotalExpense();
    });
    Route::get('total_expense_by_month_of_year/{date}',function(){
        return \App\Models\Task\Task::getTotalExpenseByMonthOfYear($date);
    });
    Route::get('total_expense_between/{start}/{end}',function(){
        return \App\Models\Task\Task::getTotalExpenseBetween($start,$end);
    });
    /**
     * Get total expense and earn
     */
    /**
     * Total tasks, expense, earn by day
     */
    Route::get('total_tasks_earn_expense',function(Request $request){
        return response()->json([
            'new' => \App\Models\Task\Task::getTotalNewTasks() ,
            'progress' => \App\Models\Task\Task::getTotalInProgressTasks() ,
            'pending' => \App\Models\Task\Task::getTotalPendingTasks() ,
            'ended' => \App\Models\Task\Task::getTotalEndedTasks() ,
            'earn' => \App\Models\Task\Task::getTotalEarn() ,
            'expense' => \App\Models\Task\Task::getTotalExpense()
        ],200);
    });
    Route::get('total_tasks_earn_expense_by_day',function(Request $request){
        return response()->json([
            'new' => \App\Models\Task\Task::getNewTasks()->where('created_at','like',\Carbon\Carbon::now()->format('Y-m-d')."%")->count() ,
            'progress' => \App\Models\Task\Task::getInProgressTasks()->where('created_at','like',\Carbon\Carbon::now()->format('Y-m-d')."%")->count() ,
            'pending' => \App\Models\Task\Task::getPendingTasks()->where('created_at','like',\Carbon\Carbon::now()->format('Y-m-d')."%")->count() ,
            'ended' => \App\Models\Task\Task::getEndedTasks()->where('created_at','like',\Carbon\Carbon::now()->format('Y-m-d')."%")->count() ,
            'earn' => number_format( \App\Models\Task\Task::getTotalEarnBetween(\Carbon\Carbon::now()->format('Y-m-d'),\Carbon\Carbon::now()->format('Y-m-d'))->sum('total'),2,'.',',' ) ,
            'expense' => number_format( \App\Models\Task\Task::getTotalExpenseBetween(\Carbon\Carbon::now()->format('Y-m-d'),\Carbon\Carbon::now()->format('Y-m-d'))->sum('total'),2,'.',',' )
        ],200);
    });
  });