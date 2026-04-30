<?php

namespace App\Models\Officer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OfficerJob extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function unofficialPosition(){
        return $this->belongsTo( \App\Models\Position\Position::class , 'unofficial_position_id' , 'id' );
    }
    public function organizationStructurePosition(){
        return $this->belongsTo( \App\Models\Organization\OrganizationStructurePosition::class , 'organization_structure_position_id' , 'id' );
    }
    public function countesy(){
        return $this->belongsTo( \App\Models\People\Countesy::class , 'countesy_id' , 'id' );
    }
    public function officer(){
        return $this->belongsTo( \App\Models\Officer\Officer::class , 'officer_id' , 'id' );
    }
    public function totalWorkingDays(){
        return strlen( $this->start ) > 0
            ? Carbon::parse( $this->start )->diffInDays( strlen( $this->end ) > 0 ? Carbon::parse( $this->end ) : Carbon::now() )
            : 0 ;
    }
    /**
     * មុខងារចាប់យករចនាសម្ព័ន្ធនៃតួនាទី
     */
    public function getParentIdsInStructure(){
        /**
         * លេខសម្គាល់ថ្នាក់ដឹកនាំក្នុងអង្គភាព
         */
        $parentIds = [];
        if( $this->organizationStructurePosition != null ){
            $parentIds = array_filter( explode(':',$this->organizationStructurePosition->tpid) , function($id){ return intval( $id ) > 0 ;} );
        }
        /**
         * លេខសម្គាល់ថ្នាក់ដឹកនាំក្នុងស្ថាប័នទាំងមូល
         */
        $organizatoinStructgureIds = [] ;
        if( $this->organizationStructurePosition->organizatoinStructure != null ){
            $organizatoinStructgureIds = array_filter( function($id){ return intval( $id ) > 0 ;} , explode(':',$this->organizationStructurePosition->organizatoinStructure->tpid) );
            /**
             * អាចយកលេខសម្គាល់នៃអង្គភាពនីមួយដែលជាឋានានុក្រុមខាងលើនៃ អង្គភាពបច្ចុប្បដែលតួនាទីរបស់មន្ត្រីនៅ
             */
        }

        return $parentIds ;
    }
    /**
     * Reports
     */
    // 1. Get all current officers with their positions
    public static function getCurrentOfficers()
    {
        return DB::select("
            SELECT
                o.id as officer_id,
                CONCAT(p.firstname, ' ', p.lastname) as officer_name,
                o.code,
                o.additional_officer_type,
                org.name as organization_name,
                pos.name as position_name,
                oj.start as start_date,
                oj.countesy_id
            FROM officer_jobs oj
            INNER JOIN officers o ON oj.officer_id = o.id
            INNER JOIN people p ON o.people_id = p.id
            INNER JOIN organization_structure_positions osp ON oj.organization_structure_position_id = osp.id
            INNER JOIN organization_structures os ON osp.organization_structure_id = os.id
            INNER JOIN organizations org ON os.organization_id = org.id
            INNER JOIN positions pos ON osp.position_id = pos.id
            WHERE oj.\"end\" IS NULL
                AND oj.deleted_at IS NULL
                AND o.deleted_at IS NULL
            ORDER BY org.name, pos.name, officer_name
        ");
    }

    // 2. Get organization structure with officer counts
    public static function getStructureOfficerCounts()
    {
        return DB::select("
            WITH officer_counts AS (
                SELECT
                    os.id as structure_id,
                    COUNT(DISTINCT oj.officer_id) as total_officers,
                    COUNT(CASE WHEN oj.\"end\" IS NULL THEN 1 END) as current_officers
                FROM organization_structures os
                LEFT JOIN organization_structure_positions osp ON os.id = osp.organization_structure_id
                LEFT JOIN officer_jobs oj ON osp.id = oj.organization_structure_position_id
                    AND oj.deleted_at IS NULL
                WHERE os.deleted_at IS NULL
                GROUP BY os.id
            )
            SELECT
                os.id,
                org.name as organization_name,
                os.desc as structure_description,
                os.active,
                COALESCE(oc.total_officers, 0) as total_officers,
                COALESCE(oc.current_officers, 0) as current_officers,
                os.total_childs
            FROM organization_structures os
            INNER JOIN organizations org ON os.organization_id = org.id
            LEFT JOIN officer_counts oc ON os.id = oc.structure_id
            WHERE os.deleted_at IS NULL
            ORDER BY org.name, os.id
        ");
    }

    // 3. Get position utilization report
    public static function getPositionUtilization()
    {
        return DB::select("
            SELECT
                osp.id as structure_position_id,
                org.name as organization_name,
                pos.name as position_name,
                osp.job_desp,
                osp.total_jobs as allocated_positions,
                COUNT(DISTINCT oj.officer_id) as filled_positions,
                ROUND(COUNT(DISTINCT oj.officer_id) * 100.0 / NULLIF(osp.total_jobs, 0), 2) as fill_rate,
                COUNT(CASE WHEN oj.\"end\" IS NULL THEN 1 END) as current_occupants
            FROM organization_structure_positions osp
            INNER JOIN organization_structures os ON osp.organization_structure_id = os.id
            INNER JOIN organizations org ON os.organization_id = org.id
            INNER JOIN positions pos ON osp.position_id = pos.id
            LEFT JOIN officer_jobs oj ON osp.id = oj.organization_structure_position_id
                AND oj.deleted_at IS NULL
            WHERE osp.deleted_at IS NULL
            GROUP BY osp.id, org.name, pos.name, osp.job_desp, osp.total_jobs
            ORDER BY fill_rate DESC, org.name, pos.name
        ");
    }

    // 4. Get officer career paths
    public static function getOfficerCareerPaths()
    {
        return DB::select("
            SELECT
                o.id as officer_id,
                CONCAT(p.firstname, ' ', p.lastname) as officer_name,
                o.code as officer_code,
                o.additional_officer_type,
                STRING_AGG(
                    CONCAT(
                        org.name, ' - ', pos.name,
                        CASE
                            WHEN oj.start IS NOT NULL THEN ' (' || oj.start || ')'
                            ELSE ''
                        END,
                        CASE
                            WHEN oj.\"end\" IS NOT NULL THEN ' - ' || oj.\"end\"
                            ELSE ' - Present'
                        END
                    ),
                    ' → ' ORDER BY oj.start
                ) as career_path,
                COUNT(oj.id) as total_positions_held,
                COUNT(CASE WHEN oj.\"end\" IS NULL THEN 1 END) as current_positions
            FROM officers o
            INNER JOIN people p ON o.people_id = p.id
            LEFT JOIN officer_jobs oj ON o.id = oj.officer_id AND oj.deleted_at IS NULL
            LEFT JOIN organization_structure_positions osp ON oj.organization_structure_position_id = osp.id
            LEFT JOIN organization_structures os ON osp.organization_structure_id = os.id
            LEFT JOIN organizations org ON os.organization_id = org.id
            LEFT JOIN positions pos ON osp.position_id = pos.id
            WHERE o.deleted_at IS NULL
            GROUP BY o.id, p.firstname, p.lastname, o.code, o.additional_officer_type
            ORDER BY officer_name
        ");
    }

    // 5. Get summary dashboard statistics
    public static function getDashboardStats()
    {
        return [
            'total_officers' => DB::selectOne("SELECT COUNT(*) as total FROM officers WHERE deleted_at IS NULL"),
            'current_assignments' => DB::selectOne("
                SELECT COUNT(DISTINCT officer_id) as total
                FROM officer_jobs
                WHERE \"end\" IS NULL AND deleted_at IS NULL
            "),
            'organizations_with_officers' => DB::selectOne("
                SELECT COUNT(DISTINCT org.id) as total
                FROM organizations org
                INNER JOIN organization_structures os ON org.id = os.organization_id
                INNER JOIN organization_structure_positions osp ON os.id = osp.organization_structure_id
                INNER JOIN officer_jobs oj ON osp.id = oj.organization_structure_position_id
                WHERE oj.\"end\" IS NULL AND oj.deleted_at IS NULL
            "),
            'position_fill_rate' => DB::selectOne("
                SELECT
                    ROUND(
                        COUNT(DISTINCT oj.officer_id) * 100.0 / NULLIF(SUM(osp.total_jobs), 0),
                        2
                    ) as fill_rate
                FROM organization_structure_positions osp
                LEFT JOIN officer_jobs oj ON osp.id = oj.organization_structure_position_id
                    AND oj.\"end\" IS NULL AND oj.deleted_at IS NULL
                WHERE osp.deleted_at IS NULL
            "),
            'top_organizations' => DB::select("
                SELECT
                    org.name,
                    COUNT(DISTINCT oj.officer_id) as officer_count
                FROM organizations org
                INNER JOIN organization_structures os ON org.id = os.organization_id
                INNER JOIN organization_structure_positions osp ON os.id = osp.organization_structure_id
                INNER JOIN officer_jobs oj ON osp.id = oj.organization_structure_position_id
                WHERE oj.\"end\" IS NULL AND oj.deleted_at IS NULL
                GROUP BY org.name
                ORDER BY officer_count DESC
                LIMIT 10
            ")
        ];
    }
}
