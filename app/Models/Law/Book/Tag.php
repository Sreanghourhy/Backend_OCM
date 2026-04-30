<?php

namespace App\Models\Law\Book;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = [
        'id' => 'int' ,
        'name' => 'string' ,
        'active' => 'int'
    ];
    public function setActiveAttribute($val){
        $this->attributes['active'] = (int) $val ;
    }
    public function getActiveAttribute(){
        return (int) $this->attributes['active'] ;
    }
}
