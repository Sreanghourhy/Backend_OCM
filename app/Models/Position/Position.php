<?php

namespace App\Models\Position;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded = ['id'];
    /**
     * Abstract methods
     */
    protected static function getModel(){
        return self::class;
    }
    protected static function getRoot(){
        return self::where('model',self::class)->first();
    }
    protected static function getTree($nodeId=false){
        $node = intval( $nodeId ) ? self::find( intval($nodeId) ) : [] ;
        if( $node != null && $node->childNodes != null && !$node->childNodes->isEmpty() ){
            $node->childNodes = $node->childNodes()->select('id','name','desp')->where('active',1)->orderby('record_index','asc')->get()->map(function($c){
                return self::getChilds( $c );
            }) ;
        }
        return $node ;
    }
    private static function getChilds($node){
        if( !$node->childNodes->isEmpty() ){
            return $node->childNodes()->select('id','name','desp')->where('active',1)->orderby('record_index','asc')->get()->map(function($c){ 
                return self::getChilds( $c );
            });
        }
        return $node ;
    }
    public function childNodes(){
        return $this->hasMany(self::class,'pid','id');
    }
    public function parentNode(){
        return $this->hasOne(self::class,'id','pid');
    }
    public function totalChildNodesOfAllLevels(){
        return self::where('tpid',"LIKE", $this->tpid . "%" )->count();
    }
    public function childs(){
        return $this->hasManyThrough(self::class, \App\Models\Position\PositionStructure::class ,'child_position_id','parent_position_id' );
    }
    public function parents(){
        return $this->hasManyThrough(self::class, \App\Models\Position\PositionStructure::class ,'parent_position_id','child_position_id' );
    }
    /**
     * Relationship
     */
    /**
     * Regulators
     */
    public function regulators(){
        return $this->belongsToMany('\App\Models\Regulator\Regulator','position_regulators','position_id','regulator_id');
    }
    public function staffs(){
        return $this->belongsToMany('\App\Models\User','position_users','position_id','user_id');
    }
    public static function getInstance(){
        return static::where('model', self::class )->first();
    }
    public static function move($pid,$parentNode=null){
        $node = intval( $pid ) 
            ? \App\Models\Regulator\Tag\Position::find( intval($pid) ) : 
            [] ;
        if( $node != null ){
            $parent = Position::create([
                'name' => $node->name ,
                'desp' => $node->desp ,
                'tpid' => $parentNode != null ? $parentNode->tpid : 0 ,
                'pid' => $parentNode != null ? $parentNode->id : 0 ,
                'record_index' => $node->record_index ,
                'active' => $node->active ,
                'created_at' => $node->created_at ,
                'updated_at' => $node->updated_at ,
                'prefix' => $node->code
            ]);
            // Update the relationship
            \DB::table('people_positions')->where('position_id',$node->id)->update([
                'position_id'=>$parent->id ,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
            if( $node->childNodes != null && !$node->childNodes->isEmpty() ){
                $node->childNodes()->get()->map(function($c) use($parent) {
                    return self::getChildsMove( $c , $parent );
                }) ;
            }
        }
    }
    private static function getChildsMove($node , $parentNode ){
        $parent = Position::create([
            'name' => $node->name ,
            'desp' => $node->desp ,
            'tpid' => $parentNode != null && intval( $parentNode->tpid ) ? $parentNode->tpid : $parentNode->pid ,
            'pid' => $parentNode != null ? $parentNode->id : 0 ,
            'record_index' => $node->record_index ,
            'active' => $node->active ,
            'created_at' => $node->created_at ,
            'updated_at' => $node->updated_at ,
            'prefix' => $node->code
        ]);
        // Update the relationship
        \DB::table('people_positions')->where('position_id',$node->id)->update([
            'position_id'=>$parent->id ,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);
        if( $node->childNodes != null && !$node->childNodes->isEmpty() ){
            $node->childNodes()->get()->map(function($c) use($parent) {
                return self::getChildsMove( $c , $parent );
            }) ;
        }
    }
    /**
     * Organizations
     */
    public function organizations(){
        return $this->belongsToMany(\App\Models\Organization\Organization::class , 'organization_positions','position_id','organization_id');
    }
    public function organizationsStructureOfPositions(){
        return $this->hasMany( \App\Models\Organization\OrganizationStructurePosition::class , 'position_id' , 'id' );
    }
    public function roles(){
        return $this->hasManyThrough( \App\Models\Role::class , 'position_has_roles' , 'position_id' , 'role_id' );
    }
    public function permissions(){
        return $this->hasManyThrough( \App\Models\Permission::class , 'position_has_permissions' , 'position_id' , 'permission_id' );
    }
    public static function generateKeyname(){
        echo "START GENERATE : " . PHP_EOL;
        self::all()->map(function($record){
            echo 'Name : ' . $record->name . ' => Keyname : ';
            $record->update([ 'keyname' => str_replace( [' ','​' ] , '' , $record->name ) ]);
            echo $record->keyname . PHP_EOL;
        });
        echo "END GENERATE : " . PHP_EOL;
    }
}
