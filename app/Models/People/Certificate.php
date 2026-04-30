<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class Certificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    public function people(){
        return $this->belongsTo( \App\Models\People\People::class , 'people_id' , 'id' );
    }
    public function group(){
        return $this->belongsTo( \App\Models\People\CertificateGroup::class , 'certificate_group_id' , 'id' );
    }
    /**
     * Report
     */
    // Get education statistics by education level using certificate groups
    public static function getEducationStatisticsByLevel()
    {
        return DB::select("
            WITH officer_highest_education AS (
                SELECT
                    o.id as officer_id,
                    o.additional_officer_type,
                    cg.education_level,
                    cg.education_level_name,
                    ROW_NUMBER() OVER (
                        PARTITION BY o.id
                        ORDER BY
                            CASE
                                WHEN cg.education_level_name = 'PhD' THEN 10
                                WHEN cg.education_level_name = 'Doctorate' THEN 9
                                WHEN cg.education_level_name = 'Master' THEN 8
                                WHEN cg.education_level_name = 'Bachelor' THEN 7
                                WHEN cg.education_level_name = 'Associate' THEN 6
                                WHEN cg.education_level_name = 'High School' THEN 5
                                WHEN cg.education_level_name = 'Secondary' THEN 4
                                WHEN cg.education_level_name = 'Primary' THEN 3
                                ELSE 1
                            END DESC,
                            c.\"end\" DESC
                    ) as rn
                FROM officers o
                INNER JOIN people p ON o.people_id = p.id
                LEFT JOIN certificates c ON p.id = c.people_id AND c.deleted_at IS NULL
                LEFT JOIN certificate_groups cg ON c.certificate_group_id = cg.id AND cg.deleted_at IS NULL
                WHERE o.deleted_at IS NULL
            )
            SELECT
                COALESCE(additional_officer_type, 'Not Specified') as officer_type,
                COUNT(*) as total_officers,
                COUNT(CASE WHEN education_level_name = 'PhD' AND rn = 1 THEN 1 END) as phd,
                COUNT(CASE WHEN education_level_name = 'Doctorate' AND rn = 1 THEN 1 END) as doctorate,
                COUNT(CASE WHEN education_level_name = 'Master' AND rn = 1 THEN 1 END) as masters,
                COUNT(CASE WHEN education_level_name = 'Bachelor' AND rn = 1 THEN 1 END) as bachelors,
                COUNT(CASE WHEN education_level_name = 'Associate' AND rn = 1 THEN 1 END) as associate,
                COUNT(CASE WHEN education_level_name = 'High School' AND rn = 1 THEN 1 END) as high_school,
                COUNT(CASE WHEN education_level_name = 'Secondary' AND rn = 1 THEN 1 END) as secondary,
                COUNT(CASE WHEN education_level_name = 'Primary' AND rn = 1 THEN 1 END) as primary,
                COUNT(CASE WHEN education_level_name IS NULL AND rn = 1 THEN 1 END) as no_education_record
            FROM officer_highest_education
            WHERE rn = 1
            GROUP BY additional_officer_type
            ORDER BY total_officers DESC
        ");
    }

    // Get detailed education report with certificate groups
    public static function getDetailedEducationWithGroups()
    {
        return DB::select("
            SELECT
                o.id as officer_id,
                CONCAT(p.firstname, ' ', p.lastname) as officer_name,
                o.code,
                o.additional_officer_type,
                c.id as certificate_id,
                cg.name as certificate_group_name,
                cg.education_level,
                cg.education_level_name,
                c.field_name as specific_field,
                c.start as start_date,
                c.\"end\" as end_date,
                c.location,
                c.place_name as institution,
                c.certificate_note,
                CASE
                    WHEN cg.education_level_name = 'PhD' THEN 10
                    WHEN cg.education_level_name = 'Doctorate' THEN 9
                    WHEN cg.education_level_name = 'Master' THEN 8
                    WHEN cg.education_level_name = 'Bachelor' THEN 7
                    WHEN cg.education_level_name = 'Associate' THEN 6
                    WHEN cg.education_level_name = 'High School' THEN 5
                    WHEN cg.education_level_name = 'Secondary' THEN 4
                    WHEN cg.education_level_name = 'Primary' THEN 3
                    ELSE 1
                END as education_level_rank
            FROM officers o
            INNER JOIN people p ON o.people_id = p.id
            INNER JOIN certificates c ON p.id = c.people_id AND c.deleted_at IS NULL
            INNER JOIN certificate_groups cg ON c.certificate_group_id = cg.id AND cg.deleted_at IS NULL
            WHERE o.deleted_at IS NULL
            ORDER BY officer_name, education_level_rank DESC, c.\"end\" DESC
        ");
    }

    // Get education summary by certificate group
    public static function getEducationSummaryByGroup()
    {
        // return DB::select("
        //     SELECT
        //         cg.name as certificate_group,
        //         cg.education_level,
        //         cg.education_level_name,
        //         COUNT(DISTINCT c.id) as total_certificates_awarded,
        //         COUNT(DISTINCT o.id) as total_officers,
        //         MIN(c.start) as earliest_certificate,
        //         MAX(c.\"end\") as latest_certificate,
        //         STRING_AGG(DISTINCT c.field_name, ', ' LIMIT 10) as fields_of_study
        //     FROM certificates c
        //     INNER JOIN certificate_groups cg ON c.certificate_group_id = cg.id AND cg.deleted_at IS NULL
        //     INNER JOIN people p ON c.people_id = p.id
        //     INNER JOIN officers o ON p.id = o.people_id
        //     WHERE c.deleted_at IS NULL
        //         AND o.deleted_at IS NULL
        //     GROUP BY cg.name, cg.education_level, cg.education_level_name
        //     ORDER BY
        //         CASE
        //             WHEN cg.education_level_name = 'PhD' THEN 1
        //             WHEN cg.education_level_name = 'Doctorate' THEN 2
        //             WHEN cg.education_level_name = 'Master' THEN 3
        //             WHEN cg.education_level_name = 'Bachelor' THEN 4
        //             WHEN cg.education_level_name = 'Associate' THEN 5
        //             WHEN cg.education_level_name = 'High School' THEN 6
        //             WHEN cg.education_level_name = 'Secondary' THEN 7
        //             WHEN cg.education_level_name = 'Primary' THEN 8
        //             ELSE 9
        //         END
        // ");
        return DB::select("
        SELECT
            cg.name as certificate_group,
            cg.education_level,
            cg.education_level_name,
            COUNT(DISTINCT c.id) as total_certificates_awarded,
            COUNT(DISTINCT o.id) as total_officers,
            MIN(c.start) as earliest_certificate,
            MAX(c.\"end\") as latest_certificate,
            ARRAY_TO_STRING(
                (ARRAY_AGG(DISTINCT c.field_name ORDER BY c.field_name))[1:10],
                ', '
            ) as fields_of_study
        FROM certificates c
        INNER JOIN certificate_groups cg ON c.certificate_group_id = cg.id AND cg.deleted_at IS NULL
        INNER JOIN people p ON c.people_id = p.id
        INNER JOIN officers o ON p.id = o.people_id
        WHERE c.deleted_at IS NULL
            AND o.deleted_at IS NULL
        GROUP BY cg.name, cg.education_level, cg.education_level_name
        ORDER BY
            CASE
                WHEN cg.education_level = 'PhD' THEN 1
                WHEN cg.education_level = 'Doctorate' THEN 2
                WHEN cg.education_level = 'Master' THEN 3
                WHEN cg.education_level = 'Bachelor' THEN 4
                WHEN cg.education_level = 'Associate' THEN 5
                WHEN cg.education_level = 'High School' THEN 6
                WHEN cg.education_level = 'Secondary' THEN 7
                WHEN cg.education_level = 'Primary' THEN 8
                ELSE 9
            END
        ");
    }

    // Get officers by specific education level
    public static function getOfficersByEducationLevel($educationLevel)
    {
        return DB::select("
            SELECT DISTINCT
                o.id,
                CONCAT(p.firstname, ' ', p.lastname) as officer_name,
                o.code,
                o.additional_officer_type,
                STRING_AGG(
                    CONCAT(cg.name, ' (', c.\"end\", ')'),
                    ', ' ORDER BY c.\"end\" DESC
                ) as certificates,
                MAX(c.\"end\") as latest_certificate_date
            FROM officers o
            INNER JOIN people p ON o.people_id = p.id
            INNER JOIN certificates c ON p.id = c.people_id AND c.deleted_at IS NULL
            INNER JOIN certificate_groups cg ON c.certificate_group_id = cg.id AND cg.deleted_at IS NULL
            WHERE o.deleted_at IS NULL
                AND cg.education_level_name = :education_level_name
            GROUP BY o.id, p.firstname, p.lastname, o.code, o.additional_officer_type
            ORDER BY latest_certificate_date DESC
        ", ['education_level_name' => $educationLevel]);
    }

    // Get education progression for each officer (career path in education)
    public static function getOfficerEducationProgression()
    {
        return DB::select("
            WITH officer_education_timeline AS (
                SELECT
                    o.id as officer_id,
                    CONCAT(p.firstname, ' ', p.lastname) as officer_name,
                    cg.education_level_name,
                    cg.name as certificate_name,
                    c.\"end\" as completion_date,
                    ROW_NUMBER() OVER (
                        PARTITION BY o.id
                        ORDER BY
                            CASE
                                WHEN cg.education_level_name = 'Primary' THEN 1
                                WHEN cg.education_level_name = 'Secondary' THEN 2
                                WHEN cg.education_level_name = 'High School' THEN 3
                                WHEN cg.education_level_name = 'Associate' THEN 4
                                WHEN cg.education_level_name = 'Bachelor' THEN 5
                                WHEN cg.education_level_name = 'Master' THEN 6
                                WHEN cg.education_level_name = 'Doctorate' THEN 7
                                WHEN cg.education_level_name = 'PhD' THEN 8
                                ELSE 9
                            END,
                            c.\"end\"
                    ) as step
                FROM officers o
                INNER JOIN people p ON o.people_id = p.id
                INNER JOIN certificates c ON p.id = c.people_id AND c.deleted_at IS NULL
                INNER JOIN certificate_groups cg ON c.certificate_group_id = cg.id AND cg.deleted_at IS NULL
                WHERE o.deleted_at IS NULL
            )
            SELECT
                officer_id,
                officer_name,
                STRING_AGG(
                    CONCAT(education_level_name, ': ', certificate_name, ' (', completion_date, ')'),
                    ' → ' ORDER BY step
                ) as education_journey,
                MAX(step) as total_steps_completed,
                MAX(completion_date) as latest_education
            FROM officer_education_timeline
            GROUP BY officer_id, officer_name
            ORDER BY total_steps_completed DESC, latest_education DESC
        ");
    }
}
