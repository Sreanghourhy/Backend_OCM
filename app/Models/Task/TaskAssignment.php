<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\People\People;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskAssignment extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $guarded = ['id'] ;

    public function task(){
        return $this->belongsTo( Task::class , 'task_id', 'id' );
    }
    public function assignee(){
        return $this->belongsTo( People::class , 'assignee', 'id' );
    }
    public function start(){
        $this->start = Carbon::now()->format('Y-m-d H:i:s');
        $this->save();
    }
    public function end(){
        $this->end = Carbon::now()->format('Y-m-d H:i:s');
        $this->save();
    }
    public function setCompletion(Float $completionPercentage = 0.0 ){
        if( floatVal( $completionPercentage ) >= 0 && floatVal( $completionPercentage ) <= 100 ){
            $this->completion_percentage = floatVal( $completionPercentage ) ;
            $this->save();
        }
    }
}
