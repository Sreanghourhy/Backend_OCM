<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskComment extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'] ;

    public function task(){
        return $this->belongsTo( Task::class , 'task_id', 'id' );
    }
    public function commentor(){
        return $this->belongsTo( People::class , 'people_id', 'id' );
    }
}
