<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use App\Models\Task\TaskAssignment;
use App\Models\Task\TaskComment;
use App\Models\Officer\Officer;
class Task extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'] ;
    protected $casts = [
        'objective' => 'string' ,
        'start' => 'datetime',
        'end' => 'datetime',
        'amount' => 'double',
        'amount_type' => 'int',
        'active' => 'int',
        'pdfs' => 'array'
    ];
    protected $date=['created_at','updated_at','deleted_at'];

    public function creator(){
        return $this->belongsTo('App\Models\User','created_by');
    }
    public function setStartAttribute($val){
        $this->attributes['start'] = \Carbon\Carbon::parse( $val )->format( 'Y-m-d H:i:s');
    }
    public function getStartAttribute()
    {
        return isset( $this->attributes['start'] ) && $this->attributes['start'] !== null ? \Carbon\Carbon::parse($this->attributes['start'])->format('Y-m-d H:i:s') : null ;
    }
    public function setEndAttribute($val)
    {
        $this->attributes['end'] = \Carbon\Carbon::parse($val)->format('Y-m-d H:i:s');
    } 
    public function getEndAttribute()
    {
        return isset( $this->attributes['end'] ) && $this->attributes['end'] !== null ? \Carbon\Carbon::parse($this->attributes['end'])->format('Y-m-d H:i:s') : null ;
    }
    /**
     * Functions
     */
    public function startTask(){
        $this->start = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        $this->save();
    }
    public function endTask(){
        $this->end = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        $this->save();
    }
    public function getActualDuration(){
        if( \Carbon\Carbon::parse( $this->end )->gte( \Carbon\Carbon::parse( $this->start ) ) ){
            return \Carbon\Carbon::parse( $this->end )->subtract( \Carbon\Carbon::parse( $this->start ) );
        }
        return 0;
    }
    /**
     * Status => 0: New, 1: Progressing, 2: End, 4: Pending, Cancel: 8, Close: 16, Completed: 32
     */
    public function markAsNew(){
        $this->status = 0 ;
        $this->start = $this->end = null ;
        return $this->save() ? true : false ;
    }
    public function markAsStart(){
        $this->status = 1 ;
        $this->start = \Carbon\Carbon::now()->format('Y-m-d H:i:s') ;
        return $this->save() ? true : false ;
    }
    public function markAsContinue(){
        $this->status = 1 ;
        $this->end = \Carbon\Carbon::now()->format('Y-m-d H:i:s') ;
        return $this->save() ? true : false ;
    }
    public function markAsEnd(){
        $this->status = 2 ;
        $this->end = \Carbon\Carbon::now()->format('Y-m-d H:i:s') ;
        return $this->save() ? true : false ;
    }
    public function markAsPending(){
        $this->status = 4 ;
        $this->end = \Carbon\Carbon::now()->format('Y-m-d H:i:s') ;
        return $this->save() ? true : false ;
    }
    public function markAsCancel(){
        $this->status = 8 ;
        $this->end = \Carbon\Carbon::now()->format('Y-m-d H:i:s') ;
        return $this->save() ? true : false ;
    }
    public function markAsClosed(){
        $this->status = 16 ;
        $this->end = \Carbon\Carbon::now()->format('Y-m-d H:i:s') ;
        return $this->save() ? true : false ;
    }
    public function markAsActive(){
        $this->active = 1 ;
        return $this->save() ? true : false ;
    }
    public function markAsUnactive(){
        $this->active = 0 ;
        return $this->save() ? true : false ;
    }
    /**
     * Total Earn
     */
    public static function getTotalEarn(){
        return ( ( $task = static::select(DB::raw("(sum(amount)) AS total"))->where('amount_type',1)->groupby('amount_type')->first() ) != null ) ? $task->total : 0 ;
    }
    /**
     * Total Earn By month
     */
    public static function getTotalEarnByMonthOfYear($date = false ){
        $date = $date === false ? \Carbon\Carbon::now()->format('Y-m') : $date ;
        return static::select(DB::raw("(sum(amount)) AS total"))->where('amount_type',1)->groupby('amount_type')->get();
    }
    /**
     * Total Earn by day(s)
     */
    public static function getTotalEarnBetween($start = false ,$end = false ){
        $start = $start === false ? \Carbon\Carbon::now()->format('Y-m-d') : $start ;
        $end = $end === false ? \Carbon\Carbon::now()->addMonth()->format('Y-m-d') : $end ;
        return static::select(DB::raw("(sum(amount)) AS total"))->where('amount_type',1)
            ->where('created_at','>=',$start)
            ->where('created_at','<=',$end)
            ->groupby('created_at')->get();
    }
    /**
     * Total Expense
     */
    public static function getTotalExpense(){
        return ( ( $task = static::select(DB::raw("(sum(amount)) AS total"))->where('amount_type',0)->groupby('amount_type')->first() ) != null ) ? $task->total : 0 ;
    }
    /**
     * Total Earn By month
     */
    public static function getTotalExpenseByMonthOfYear($date = false ){
        $date = $date === false ? \Carbon\Carbon::now()->format('Y-m') : $date ;
        return static::select(DB::raw("(sum(amount)) AS total"))->where('amount_type',0)->groupby('amount_type')->get();
    }
    /**
     * Total Earn by day(s)
     */
    public static function getTotalExpenseBetween($start = false ,$end = false ){
        $start = $start === false ? \Carbon\Carbon::now()->format('Y-m-d') : $start ;
        $end = $end === false ? \Carbon\Carbon::now()->addMonth()->format('Y-m-d') : $end ;
        return static::select(DB::raw("(sum(amount)) AS total"))->where('amount_type',0)
            ->where(function($query) use($start, $end){
                $query->where('created_at','>=',$start)
                ->orWhere('created_at','<=',$end);
                // return $query;
            })
            ->groupby('created_at')->get();
    }
    /**
     * Get new tasks
     */
    public static function getNewTasks(){
        return static::where('status',0);
    }
    /**
     * Get in progress tasks
     */
    public static function getInProgressTasks(){
        return static::where('status',1);
    }
    /**
     * Get pending tasks
     */
    public static function getPendingTasks(){
        return static::where('status',4);
    }
    /**
     * Get ended tasks
     */
    public static function getEndedTasks(){
        return static::where('status',2);
    }
    /**
     * Total new tasks
     */
    public static function getTotalNewTasks(){
        return static::where('status',0)->count();
    }
    /**
     * Total in progress tasks
     */
    public static function getTotalInProgressTasks(){
        return static::where('status',1)->count();
    }
    /**
     * Total pending tasks
     */
    public static function getTotalPendingTasks(){
        return static::where('status',4)->count();
    }
    /**
     * Total ended tasks
     */
    public static function getTotalEndedTasks(){
        return static::where('status',2)->count();
    }

    /**
     * Get children at first level of this record
     */
    public function children(){
        return $this->hasMany( Task::class , 'pid' , 'id' );
    }
    /**
     * Get children of all level of this record
     */
    public function childrenAllLevels(){
        return $this->hasMany( Task::class , 'tpid' , 'id' );
    }
    /**
     * Get parent
     */
    public function ancestor(){
        return $this->belongsTo( Task::class , 'pid' , 'id' );
    }
    /**
     * The assignees of the task
     */
    public function assignees(){
        return $this->belongsToMany( Officer::class, 'task_assignments', 'task_id', 'assignee_id');
    }
    /**
     * The assignors of the task
     */
    public function assignors(){
        return $this->belongsToMany( Officer::class, 'task_assignments', 'task_id', 'assignor_id');
    }
    /**
     * The Task assignments
     */
    public function assignments(){
        return $this->hasMany( TaskAssignment::class , 'task_id' , 'id' );
    }
    /**
     * The Task comments
     */
    public function comments(){
        return $this->hasMany( TaskComment::class , 'task_id' , 'id' );
    }
    
}
