<?php

namespace App\Models\Folder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
	use SoftDeletes;
    /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/

	protected $table = 'folders';
	// protected $primaryKey = 'id';
	protected $guarded = ['id'];
	// protected $hidden = ['id'];
	// protected $fillable = ['name', 'user_id','pid'];
	public $timestamps = true;
	protected $dates = ['created_at' , 'updated' ,'deleted_at'];
	protected $casts = [
		'created_at' => 'datetime',
		'updated_at' => 'datetime'
	];

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	/**
	 * Get all of the comments for the Folder
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
	 */
	public function regulators(){
		return $this->belongsToMany('\App\Models\Regulator\Regulator','regulator_folder','folder_id','regulator_id');
	}
	public function matras(){
		return $this->belongsToMany('\App\Models\Law\Book\Matra','matras_folder','folder_id','matra_id');
	}
	public function matrasFolder(){
		return $this->hasMany( \App\Models\Law\Book\FolderMatra::class ,'folder_id' , 'id' );
	}
	public function files(){
		return $this->belongsToMany('\App\Models\File\File','file_folders','folder_id','file_id');
	}
	public function user(){
    	return $this->belongsTo('\App\Models\User','user_id','id');
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
