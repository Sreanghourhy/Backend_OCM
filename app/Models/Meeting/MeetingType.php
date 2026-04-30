<?php

namespace App\Models\Meeting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * This class is use to identify the type of the regulator
 */
class MeetingType extends Model
{
    use SoftDeletes;
    protected $table = 'meeting_types';

    protected $guarded = ['id'];

    // Relationships
    public function meetings(){
        return $this->hasMany(\App\Models\Meeting\Meeting::class,'type_id','id');
    }
    /**
     * Get children
     */
    public function children(){
        return $this->hasMany( self::class , 'pid' , 'id' );
    }
    /**
     * Get parent
     */
    public function ancestor(){
        return $this->belongsTo( self::class , 'pid' , 'id' );
    }
    public function totalChildNodesOfAllLevels(){
        return self::where('tpid',"LIKE", $this->tpid . "%" )->count();
    }
}
