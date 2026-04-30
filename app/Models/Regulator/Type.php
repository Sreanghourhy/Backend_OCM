<?php

namespace App\Models\Regulator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * This class is use to identify the type of the regulator
 */
class Type extends Model
{
    use SoftDeletes;
    /**
     * Abstract methods
     */
    protected $table = "regulator_types" ;
    /**
     * Relationships
     */
    public function regulators(){
        return $this->hasMany( \App\Models\Regulator\Regulator::class ,'document_type' , 'id' );
    }
}
