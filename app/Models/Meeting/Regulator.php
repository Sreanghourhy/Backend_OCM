<?php
namespace App\Models\Meeting;

use Illuminate\Database\Eloquent\Model;
use App\Models\Regulator\LegalDraft;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regulator extends Model
{
    use SoftDeletes;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'regulators';
    //protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['fid','title','objective', 'year','pdf','created_by','updated_by','publish', 'active', 'accessibility'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
      The 1-1 relationship is the relation between two tables which one table is required another table's information to fullfil its information
      To define 1-1 relationship.
        we suppose that a regulator has only one type
        then we define a function with the method 'hasOne' with 3 parameters
            Function: hasOne( param1, param2, param3 )
            Param1 : must be a related model of the current class
            Param2 : must be a primary field of the related model
            Param3 : must be a forien field of the related field
    */

    public function folders(){
      return $this->belongsToMany('\App\Models\Folder\Folder','regulator_folder','regulator_id','folder_id');
    }
    public function favorites(){
      return $this->belongsToMany('\App\Models\Regulator\Favorite','regulator_favorite','regulator_id','user_id');
    }

    public function creater()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    /**
     * Get all of the legal drafts for the Regulator
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function legalDrafts(): HasMany
    {
        return $this->hasMany(LegalDraft::class, 'regulator_id', 'id');
    }
    
    public function updater()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }
    public function type(){
      return $this->belongsTo( \App\Models\Regulator\Type::class , 'document_type' , 'id' );
    }
    public function organizations(){
      return $this->belongsToMany('\App\Models\Organization\Organization','organization_regulators','regulator_id','organization_id');
    }
    public function ownOrganizations(){
      return $this->belongsToMany('\App\Models\Organization\Organization','organization_own_regulators','regulator_id','organization_id');
    }
    public function relatedOrganizations(){
      return $this->belongsToMany('\App\Models\Organization\Organization','organization_related_regulators','regulator_id','organization_id');
    }
    public function signatures(){
      return $this->belongsToMany('\App\Models\Regulator\Tag\Signature','regulator_signatures','regulator_id','signature_id');
    }
    public function books(){
      return $this->belongsToMany(\App\Models\Law\Book\Book::class,'book_references','regulator_id','book_id');
  }

    // public function setPdfAttribute($value)
    // {
    //   $attribute_name = "pdf";
    //   $disk = "regulator";
    //   $destination_path = "regulators";
    //   if( $this->$attribute_name != null && $this->$attribute_name != "" ){
    //     if( \Storage::disk($disk)->exists( $this->$attribute_name ) ){
    //       \Storage::disk($disk)->delete( $this->$attribute_name);
    //     }
    //   }
    //   // if the pdf was erased
    //   if ($value==null) {
    //     $this->attributes[$attribute_name] = null;
    //   }else {
    //     $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    //   }
    // }
    
}
