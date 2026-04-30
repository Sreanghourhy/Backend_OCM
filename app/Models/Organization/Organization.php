<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * This class is use to identify the organization of the regulator
 */
class Organization extends Model
{
    use HasFactory , SoftDeletes;

    protected $guarded = ['id'];
    /**
     * Abstract methods
     */
    protected static function getTree($nodeId=false){
        $node = intval( $nodeId ) ? self::find( intval($nodeId) ) : [] ;
        if( $node != null && $node->childNodes != null && !$node->childNodes->isEmpty() ){
            $node->childNodes = $node->childNodes()->select('id','name','desp')->where('active',1)->orderby('record_index','asc')->get()->map(function($c){
                return self::getChilds( $c );
            }) ;
        }
        return $node ;
    }
    private static function getChilds($node ){
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
        return $this->where('tpid',"LIKE", $this->tpid . ":" . $this->id . "%" )->count();
    }
    public function childs(){
        return $this->hasManyThrough(self::class, \App\Models\Organization\OrganizationStructure::class ,'child_id','parent_id' );
    }
    public function parents(){
        return $this->hasManyThrough(self::class, \App\Models\Organization\OrganizationStructure::class ,'parent_id','child_id' );
    }
    public function totalStaffsOfAllLevels(){
        $staffs = $this->where('tpid',"LIKE", $this->tpid . ":" . $this->id . "%" )->pluck('id')->map(function($organizationId){
            if( ( $organization = Organization::find( $organizationId ) ) != null ){
                return [
                    'totalOfficers' => 
                    // ( $organization->leader != null ? $organization->leader->count() : 0 ) + 
                    ( $organization->staffs != null ? $organization->staffs->count() : 0 )
                ];
            }
            return [
                'totalOfficers' => 0
            ] ;
        });
        return $staffs->sum('totalOfficers');
    }
    /**
     * Relationship
     */
    /**
     * Organization
     */
    public function regulators(){
        return $this->belongsToMany( \App\Models\Regulator\Regulator::class ,'organization_regulators', 'organization_id', 'regulator_id' );
    }
    public function officers(){
        return $this->hasMany( \App\Models\Officer\Officer::class , 'organization_id', 'id' );
    }
    public function meetings(){
        return $this->belongsToMany( Meeting::class , MeetingOrganization::class , 'organization_id' , 'meeting_id' );
    }
    public function attendantChecktimes(){
        return $this->hasMany( App\Models\Attendant\AttendantCheckTime::class,'organization_id','id');
    }
    public function industries(){
        return $this->hasManyThrough( \App\Models\Organization\Industry::class, \App\Models\Organization\OrganizationIndustry::class , 'industry_id' , 'organization_id' );
    }
    // public function industry(){
    //     return $this->hasMany( \App\Models\Organization\Industry::class , 'industry_id' , 'id' );
    // }
    public function industry(){
    return $this->belongsTo(
        \App\Models\Organization\Industry::class,
        'industry_id', // on organizations table
        'id'
    );
}
    public static function move($pid,$parentNode=null){
        $node = intval( $pid ) 
            ? \App\Models\Regulator\Tag\Organization::find( intval($pid) ) : 
            [] ;
        if( $node != null ){
            $parent = Organization::create([
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
            \DB::table('organization_people')->where('organization_id',$node->id)->update([
                'organization_id'=>$parent->id ,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                'code' => $parent->prefix
            ]);
            \DB::table('organization_leader')->where('organization_id',$node->id)->update([
                'organization_id'=>$parent->id ,
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
        $parent = Organization::create([
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
        \DB::table('organization_people')->where('organization_id',$node->id)->update([
            'organization_id'=>$parent->id ,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            'code' => $parent->prefix
        ]);
        \DB::table('organization_leader')->where('organization_id',$node->id)->update([
            'organization_id'=>$parent->id ,
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
     * Positions
     */
    public function positions(){
        return $this->belongsToMany(\App\Models\Position\Position::class , 'organization_positions','organization_id','position_id');
    }
    public function structure(){
        return $this->hasMany( \App\Models\Organization\OrganizationStructure::class , 'organization_id' , 'id' );
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
    public function documentOrganizationFocalPeople(){
        return $this->hasManyThrough( \App\Models\User::class , \App\Models\Document\OrganizationFocalPeople::class , 'organization_id' , 'id');
    }


    /*
    * Get All Organization base each on industries's tag 
    */
    public static function secretariatOfStateOffice()
    {
        return self::join('industries', 'organizations.industry_id', '=', 'industries.id')
            ->where('industries.tags', 'Like', '%#secretariat_of_state_office%')
            ->select('organizations.*', 'industries.name as industry_name');
    }
    
    public static function consultationgroup()
    {
        return self::join('industries', 'organizations.industry_id', '=', 'industries.id')
            ->where('industries.tags', 'Like', '%#consultation_group%')
            ->select('organizations.*', 'industries.name as industry_name');
    }

    public static function authority()
    {
        return self::join('industries', 'organizations.industry_id', '=', 'industries.id')
            ->where('industries.tags', 'Like', '%#authority%')
            ->select('organizations.*', 'industries.name as industry_name');
    }

    public static function council()
    {
        return self::join('industries', 'organizations.industry_id', '=', 'industries.id')
            ->where('industries.tags', 'Like', '%#council%')
            ->select('organizations.*', 'industries.name as industry_name');
    }
    public static function ministry()
    {
        return self::join('industries', 'organizations.industry_id', '=', 'industries.id')
            ->where('industries.tags', 'Like', '%#ministry%')
            ->select('organizations.*', 'industries.name as industry_name');
    }
    public static function general_department()
    {
        return self::join('industries', 'organizations.industry_id', '=', 'industries.id')
            ->where('industries.tags', 'Like', '%#general_department%')
            ->select('organizations.*', 'industries.name as industry_name');
    }
    public static function department()
    {
        return self::join('industries', 'organizations.industry_id', '=', 'industries.id')
            ->where('industries.tags', 'Like', '%#department%')
            ->select('organizations.*', 'industries.name as industry_name');
    }
    public static function division()
    {
        return self::join('industries', 'organizations.industry_id', '=', 'industries.id')
            ->where('industries.tags', 'Like', '%#office#division%')
            ->select('organizations.*', 'industries.name as industry_name');
    }
    public static function teamwork()
    {
        return self::join('industries', 'organizations.industry_id', '=', 'industries.id')
            ->where('industries.tags', 'Like', '%#teamwork%')
            ->select('organizations.*', 'industries.name as industry_name');
    }

    public static function getAllOrganizationTypes()
    {
        // $data = array_merge(
        //     self::secretariatOfStateOffice()->orderBy('record_index')->get(),
        //     self::consultationgroup()->orderBy('record_index')->get(),
        //     self::authority()->orderBy('record_index')->get(),
        //     self::council()->orderBy('record_index')->get(),
        //     self::ministry()->orderBy('record_index')->get(),
        //     self::general_department()->orderBy('record_index')->get(),
        //     self::department()->orderBy('record_index')->get(),
        //     self::division()->orderBy('record_index')->get(),
        //     self::teamwork()->orderBy('record_index')->get(),
        // );
        // $data = [
        //     'secretariat_of_state_office' => self::secretariatOfStateOffice()->orderBy('record_index')->get(),
        //     'consultation_group' => self::consultationgroup()->orderBy('record_index')->get(),
        //     'authority' => self::authority()->orderBy('record_index')->get(),
        //     'council' => self::council()->orderBy('record_index')->get(),
        //     'ministry' => self::ministry()->orderBy('record_index')->get(),
        //     'general_department' => self::general_department()->orderBy('record_index')->get(),
        //     'department' => self::department()->orderBy('record_index')->get(),
        //     'division' => self::division()->orderBy('record_index')->get(),
        //     'teamwork' => self::teamwork()->orderBy('record_index')->get(),
        // ];
        $data = collect([
            self::secretariatOfStateOffice()->orderBy('record_index')->get(),
            self::consultationgroup()->orderBy('record_index')->get(),
            self::authority()->orderBy('record_index')->get(),
            self::council()->orderBy('record_index')->get(),
            self::ministry()->orderBy('record_index')->get(),
            self::general_department()->orderBy('record_index')->get(),
            self::department()->orderBy('record_index')->get(),
            self::division()->orderBy('record_index')->get(),
            self::teamwork()->orderBy('record_index')->get(),
        ])->collapse();
        
        return $data;
    }
    public static function getHierarchyFromConsultationGroup(){
        $data = collect([
            self::consultationgroup()->orderBy('record_index')->get(),
            self::authority()->orderBy('record_index')->get(),
            self::council()->orderBy('record_index')->get(),
            self::ministry()->orderBy('record_index')->get(),
            self::general_department()->orderBy('record_index')->get(),
            self::department()->orderBy('record_index')->get(),
            self::division()->orderBy('record_index')->get(),
            self::teamwork()->orderBy('record_index')->get(),
        ])->collapse();
        
        return $data;
    }
    public static function getHierarchyFromAuthority(){
        $data = collect([
            self::authority()->orderBy('record_index')->get(),
            self::council()->orderBy('record_index')->get(),
            self::ministry()->orderBy('record_index')->get(),
            self::general_department()->orderBy('record_index')->get(),
            self::department()->orderBy('record_index')->get(),
            self::division()->orderBy('record_index')->get(),
            self::teamwork()->orderBy('record_index')->get(),
        ])->collapse();
        
        return $data;
    }
    public static function getHierarchyFromCouncil(){
        $data = collect([
            self::council()->orderBy('record_index')->get(),
            self::ministry()->orderBy('record_index')->get(),
            self::general_department()->orderBy('record_index')->get(),
            self::department()->orderBy('record_index')->get(),
            self::division()->orderBy('record_index')->get(),
            self::teamwork()->orderBy('record_index')->get(),
        ])->collapse();
        
        return $data;
    }
    public static function getHierarchyFromMinistry(){
        $data = collect([
            self::ministry()->orderBy('record_index')->get(),
            self::general_department()->orderBy('record_index')->get(),
            self::department()->orderBy('record_index')->get(),
            self::division()->orderBy('record_index')->get(),
            self::teamwork()->orderBy('record_index')->get(),
        ])->collapse();
        
        return $data;
    }
    public static function getHierarchyFromGeneralDepartment(){
        $data = collect([
            self::general_department()->orderBy('record_index')->get(),
            self::department()->orderBy('record_index')->get(),
            self::division()->orderBy('record_index')->get(),
            self::teamwork()->orderBy('record_index')->get(),
        ])->collapse();
        
        return $data;
    }
    public static function getHierarchyFromDepartment(){
        $data = collect([
            self::department()->orderBy('record_index')->get(),
            self::division()->orderBy('record_index')->get(),
            self::teamwork()->orderBy('record_index')->get(),
        ])->collapse();
        
        return $data;
    }
    public static function getHierarchyFromDivision(){
        $data = collect([
            self::division()->orderBy('record_index')->get(),
            self::teamwork()->orderBy('record_index')->get(),
        ])->collapse();
        
        return $data;
    }
    public function isMinistry(){
        return $this->industry()->where('tags','like','%#ministry%')->first() != null ? true : false;
    }
    public function isGeneralDepartment(){
        return $this->industry()->where('tags','like','%#general_department%')->first() != null ? true : false;
    }
    public function isDepartment(){
        return $this->industry()->where('tags','like','%#department%')->first() != null ? true : false;
    }
    public function isOffice(){
        return $this->industry()->where('tags','like','%#office%')->first() != null ? true : false;
    }
}
