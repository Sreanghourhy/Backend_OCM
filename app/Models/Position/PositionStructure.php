<?php

namespace App\Models\Position;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PositionStructure extends Model
{
    use HasFactory, SoftDeletes;

    public function child(){
        return $this->belongsTo( \App\Models\Position\Position::class , 'child_position_id' , 'id' );
    }
    public function parent(){
        return $this->belongsTo( \App\Models\Position\Position::class , 'parent_position_id' , 'id' );
    }
    
}
