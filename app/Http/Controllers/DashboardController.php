<?php

namespace App\Http\Controllers;

use App\Models\Officer\Officer;
use App\Models\People\Certificate;
use App\Models\Officer\OfficerJob;
use App\Models\Officer\OfficerRank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get education statistics
        $educationStats = DB::select("
            WITH officer_highest_education AS (
                SELECT
                    o.id,
                    cg.education_level,
                    ROW_NUMBER() OVER (
                        PARTITION BY o.id
                        ORDER BY
                            CASE
                                WHEN cg.education_level = 'PhD' THEN 10
                                WHEN cg.education_level = 'Doctorate' THEN 9
                                WHEN cg.education_level = 'Master' THEN 8
                                WHEN cg.education_level = 'Bachelor' THEN 7
                                ELSE 1
                            END DESC
                    ) as rn
                FROM officers o
                LEFT JOIN people p ON o.people_id = p.id
                LEFT JOIN certificates c ON p.id = c.people_id AND c.deleted_at IS NULL
                LEFT JOIN certificate_groups cg ON c.certificate_group_id = cg.id
                WHERE o.deleted_at IS NULL
            )
            SELECT
                COUNT(CASE WHEN education_level = 'PhD' AND rn = 1 THEN 1 END) as phd,
                COUNT(CASE WHEN education_level = 'Master' AND rn = 1 THEN 1 END) as masters,
                COUNT(CASE WHEN education_level = 'Bachelor' AND rn = 1 THEN 1 END) as bachelors,
                COUNT(CASE WHEN education_level IS NULL AND rn = 1 THEN 1 END) as no_record
            FROM officer_highest_education
            WHERE rn = 1
        ")[0];

        // Get officer type distribution
        $officerTypes = Officer::select('additional_officer_type', DB::raw('COUNT(*) as count'))
            ->whereNull('deleted_at')
            ->groupBy('additional_officer_type')
            ->get();

        // Get years of service distribution
        $serviceYears = DB::select("
            SELECT
                CASE
                    WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(unofficial_date, 'YYYY-MM-DD'))) < 3 THEN '0-2 years'
                    WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(unofficial_date, 'YYYY-MM-DD'))) BETWEEN 3 AND 5 THEN '3-5 years'
                    WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(unofficial_date, 'YYYY-MM-DD'))) BETWEEN 6 AND 10 THEN '6-10 years'
                    WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(unofficial_date, 'YYYY-MM-DD'))) BETWEEN 11 AND 15 THEN '11-15 years'
                    ELSE '15+ years'
                END as service_range,
                COUNT(*) as count
            FROM officers
            WHERE unofficial_date IS NOT NULL AND deleted_at IS NULL
            GROUP BY service_range
        ");

        // Get age distribution
        $ageGroups = DB::select("
            SELECT
                CASE
                    WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, dob)) < 25 THEN 'Under 25'
                    WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, dob)) BETWEEN 25 AND 35 THEN '25-35'
                    WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, dob)) BETWEEN 36 AND 45 THEN '36-45'
                    WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, dob)) BETWEEN 46 AND 55 THEN '46-55'
                    ELSE 'Over 55'
                END as age_group,
                COUNT(*) as count
            FROM people p
            INNER JOIN officers o ON p.id = o.people_id
            WHERE o.deleted_at IS NULL AND p.dob IS NOT NULL
            GROUP BY age_group
        ");

        // Get rank distribution
        $rankDistribution = DB::select("
            SELECT r.name, COUNT(*) as count
            FROM officer_ranks orank
            INNER JOIN ranks r ON orank.rank_id = r.id
            WHERE orank.deleted_at IS NULL
                AND (orank.\"end\" IS NULL OR orank.\"end\" = '9999-12-31')
            GROUP BY r.name
            ORDER BY count DESC
            LIMIT 6
        ");

        // Get medal distribution
        $medalDistribution = DB::select("
            SELECT m.name, COUNT(*) as count
            FROM officer_medals om
            INNER JOIN medals m ON om.medal_id = m.id
            WHERE om.deleted_at IS NULL
            GROUP BY m.name
            ORDER BY count DESC
            LIMIT 5
        ");

        // Get top organizations
        $topOrganizations = DB::select("
            SELECT org.name, COUNT(DISTINCT o.id) as officer_count
            FROM organizations org
            INNER JOIN organization_structures os ON org.id = os.organization_id
            INNER JOIN organization_structure_positions osp ON os.id = osp.organization_structure_id
            INNER JOIN officer_jobs oj ON osp.id = oj.organization_structure_position_id
            INNER JOIN officers o ON oj.officer_id = o.id
            WHERE oj.\"end\" IS NULL AND oj.deleted_at IS NULL AND o.deleted_at IS NULL
            GROUP BY org.name
            ORDER BY officer_count DESC
            LIMIT 5
        ");

        // Get education trends
        $educationTrends = DB::select("
            SELECT
                EXTRACT(YEAR FROM TO_DATE(c.\"end\", 'YYYY-MM-DD')) as year,
                cg.education_level,
                COUNT(*) as count
            FROM certificates c
            INNER JOIN certificate_groups cg ON c.certificate_group_id = cg.id
            WHERE c.deleted_at IS NULL AND c.\"end\" IS NOT NULL
            GROUP BY year, cg.education_level
            ORDER BY year DESC
        ");

        // Get career progression data
        $careerProgression = DB::select("
            WITH rank_changes AS (
                SELECT
                    officer_id,
                    rank_id,
                    start,
                    LAG(rank_id) OVER (PARTITION BY officer_id ORDER BY start) as prev_rank_id,
                    LAG(start) OVER (PARTITION BY officer_id ORDER BY start) as prev_start
                FROM officer_ranks
                WHERE deleted_at IS NULL
            )
            SELECT
                CONCAT(r_prev.name, ' → ', r_curr.name) as promotion_path,
                AVG(EXTRACT(YEAR FROM AGE(TO_DATE(rc.start, 'YYYY-MM-DD'), TO_DATE(rc.prev_start, 'YYYY-MM-DD')))) as avg_years
            FROM rank_changes rc
            INNER JOIN ranks r_curr ON rc.rank_id = r_curr.id
            INNER JOIN ranks r_prev ON rc.prev_rank_id = r_prev.id
            WHERE rc.prev_rank_id IS NOT NULL
            GROUP BY promotion_path
            ORDER BY avg_years
        ");

        // Get metrics
        $metrics = [
            'totalOfficers' => Officer::whereNull('deleted_at')->count(),
            'activeOfficers' => OfficerJob::whereNull('end')->whereNull('deleted_at')->distinct('officer_id')->count('officer_id'),
            'probationOfficers' => Officer::whereNull('deleted_at')
                ->whereNotNull('unofficial_date')
                ->whereNull('official_date')
                ->whereRaw("AGE(CURRENT_DATE, TO_DATE(unofficial_date, 'YYYY-MM-DD')) < INTERVAL '1 year'")
                ->count(),
            'readyPromotion' => Officer::whereNull('deleted_at')
                ->whereNotNull('unofficial_date')
                ->whereNull('official_date')
                ->whereRaw("AGE(CURRENT_DATE, TO_DATE(unofficial_date, 'YYYY-MM-DD')) >= INTERVAL '1 year'")
                ->count(),
            'avgAge' => DB::selectOne("
                SELECT ROUND(AVG(EXTRACT(YEAR FROM AGE(CURRENT_DATE, dob))), 1) as avg_age
                FROM people p
                INNER JOIN officers o ON p.id = o.people_id
                WHERE o.deleted_at IS NULL AND p.dob IS NOT NULL
            ")->avg_age ?? 0,
            'avgServiceYears' => DB::selectOne("
                SELECT ROUND(AVG(EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(unofficial_date, 'YYYY-MM-DD')))), 1) as avg_years
                FROM officers
                WHERE unofficial_date IS NOT NULL AND deleted_at IS NULL
            ")->avg_years ?? 0
        ];

        return view('dashboard', compact(
            'educationStats',
            'officerTypes',
            'serviceYears',
            'ageGroups',
            'rankDistribution',
            'medalDistribution',
            'topOrganizations',
            'educationTrends',
            'careerProgression',
            'metrics'
        ));
    }
}
