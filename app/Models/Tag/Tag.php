<?php

namespace App\Models\Tag;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class Tag extends Model 
{
    use SoftDeletes;
    protected $table = "tags" ;
    private $model = null ;
    protected $guarded = ['id'];
    abstract static protected function getModel();
    abstract static protected function getRoot();
    abstract static protected function getTree();
    abstract protected function childNodes();
    abstract protected function parentNode();
    abstract protected function totalChildNodesOfAllLevels();
    /**
     * Relationship
     */
    /**
     * Functions
     */
    // public function root(){
    //     return $this->where('model',$this->getModel())
    //         ->whereNull('deleted_at')
    //         ->where(function($q){ 
    //             $q->whereNull('tpid')->orWhere('tpid',""); 
    //         })->first();
    // }
    // public function children(){
    //     $root = $this->root() ;
    //     return $root != null 
    //         ? $this->whereNull('deleted_at')
    //             ->where('pid',$root->id)
    //         : null ;
    // }
    // public function add($name,$desp=""){
    //     if( ( $root = $this->root() ) != null ){
    //         return \DB::table( $this->getTableName() )->updateOrInsert(
    //             [
    //                 'name' => $name , 
    //                 'desp' => $desp 
    //             ],
    //             [    
    //                 'tpid' => $root->id ,
    //                 'pid' => null ,
    //                 'model' => $root->model ,
    //                 'deleted_at' => null
    //             ]
    //         );
    //     }
    //     return null ;
    // }
}
