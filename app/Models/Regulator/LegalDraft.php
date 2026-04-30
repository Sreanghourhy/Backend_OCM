<?php

namespace App\Models\Regulator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Meeting\Meeting;
use App\Models\Regulator\Regulator;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegalDraft extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'] ;
    /**
     * Get all of the meetings for the LegalDraft
    */
    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'legal_draft_id', 'id');
    }
    /**
     * Get the regulator that owns the LegalDraft
     */
    public function regulator()
    {
        return $this->belongsTo(Regulator::class, 'regulator_id', 'id');
    }

    /**
     * Get the creator that owns the LegalDraft
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the editor that owns the LegalDraft
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * Get the deleted person that owns the LegalDraft
     */
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    /**
     * Total number of days that the meeting has been spended
     */
    public function totalSpentDays(){
        return $this->meetings->groupby('date')->count();
    }
    public function totalSpentMinutes(){
        return $this->meetings->map(function($m){ return $m->totalSpentMinutes(); })->sum();
    }
}
