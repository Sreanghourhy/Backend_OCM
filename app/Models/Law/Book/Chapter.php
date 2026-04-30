<?php

namespace App\Models\Law\Book;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use SoftDeletes;
     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    //protected $table = 'chapters';
    //protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getParts(){
        return $this->parts()->get()->map(function($record){
            return [
                'id' => $record->id ,
                'title' => $record->number . " ៖ " . $record->title,
                'children' => $record->getSections(),
                'type'=>'part'
            ];
        });
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
    public function editor()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
    public function book()
    {
        return $this->belongsTo(\App\Models\Law\Book\Book::class, 'book_id', 'id');
    }
    public function kunty()
    {
        return $this->belongsTo(\App\Models\Law\Book\Kunty::class, 'kunty_id', 'id');
    }
    public function matika()
    {
        return $this->belongsTo(\App\Models\Law\Book\Matika::class,'matika_id','id');
    }
    public function parts()
    {
        return $this->hasMany(\App\Models\Law\Book\Part::class,'chapter_id','id');
    }
    public function sections()
    {
        return $this->hasMany(\App\Models\Law\Book\Section::class, 'chapter_id', 'id');
    }
    public function matras()
    {
        return $this->hasMany(\App\Models\Law\Book\Matra::class,'chapter_id','id');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
