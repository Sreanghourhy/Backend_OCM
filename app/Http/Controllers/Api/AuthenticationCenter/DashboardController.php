<?php

namespace App\Http\Controllers\Api\AuthenticationCenter;

use App\Models\Officer\Officer;
use App\Models\People\Certificate;
use App\Models\People\People;
use App\Models\Officer\OfficerJob;
use App\Models\Officer\OfficerRank;
use App\Http\Controllers\Controller;
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

    public function getStats()
    {
        return response()->json([
            'metrics' => $this->getMetrics(),
            'educationLevels' => $this->getEducationData(),
            'officerTypes' => $this->getOfficerTypesData(),
            'serviceYears' => $this->getServiceYearsData(),
            'ageGroups' => $this->getAgeGroupsData(),
            'ranks' => $this->getRankDistributionData(),
            'medals' => $this->getMedalDistributionData(),
            'organizations' => $this->getOrganizationData(),
            'fillRate' => $this->getFillRateData(),
            'educationTrends' => $this->getEducationTrendsData(),
            'careerProgression' => $this->getCareerProgressionData()
        ]);
    }

    private function getMetrics()
    {
        return [
            'totalOfficers' => Officer::whereNull('deleted_at')->count(),
            'activeOfficers' => OfficerJob::whereNull('end')
                ->whereNull('deleted_at')
                ->distinct('officer_id')
                ->count('officer_id'),
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
            'avgAge' => DB::table('people')
                ->join('officers', 'people.id', '=', 'officers.people_id')
                ->whereNull('officers.deleted_at')
                ->whereNotNull('people.dob')
                ->avg(DB::raw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, people.dob))")) ?? 0,
            'avgServiceYears' => Officer::whereNotNull('unofficial_date')
                ->whereNull('deleted_at')
                ->avg(DB::raw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(unofficial_date, 'YYYY-MM-DD')))")) ?? 0
        ];
    }

    private function getEducationData()
    {
        $result = DB::select("
            WITH officer_highest_education AS (
                SELECT
                    o.id as officer_id,
                    cg.education_level,
                    ROW_NUMBER() OVER (
                        PARTITION BY o.id
                        ORDER BY
                            CASE
                                WHEN cg.education_level = 'PhD' THEN 10
                                WHEN cg.education_level = 'Doctorate' THEN 9
                                WHEN cg.education_level = 'Master' THEN 8
                                WHEN cg.education_level = 'Bachelor' THEN 7
                                WHEN cg.education_level = 'Associate' THEN 6
                                WHEN cg.education_level = 'High School' THEN 5
                                WHEN cg.education_level = 'Secondary' THEN 4
                                WHEN cg.education_level = 'Primary' THEN 3
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
                COALESCE(education_level, 'No Record') as education_level,
                COUNT(*) as count
            FROM officer_highest_education
            WHERE rn = 1
            GROUP BY education_level
            ORDER BY
                CASE
                    WHEN education_level = 'PhD' THEN 1
                    WHEN education_level = 'Doctorate' THEN 2
                    WHEN education_level = 'Master' THEN 3
                    WHEN education_level = 'Bachelor' THEN 4
                    WHEN education_level = 'Associate' THEN 5
                    WHEN education_level = 'High School' THEN 6
                    WHEN education_level = 'Secondary' THEN 7
                    WHEN education_level = 'Primary' THEN 8
                    ELSE 9
                END
        ");

        $labels = [];
        $data = [];
        foreach ($result as $item) {
            $labels[] = $item->education_level;
            $data[] = $item->count;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getOfficerTypesData()
    {
        $result = Officer::select('additional_officer_type', DB::raw('COUNT(*) as count'))
            ->whereNull('deleted_at')
            ->groupBy('additional_officer_type')
            ->get();

        $labels = [];
        $data = [];
        foreach ($result as $item) {
            $labels[] = $item->additional_officer_type ?: 'Not Specified';
            $data[] = $item->count;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getServiceYearsData()
    {
        $result = DB::select("
            SELECT
                service_range,
                COUNT(*) as count
            FROM (
                SELECT
                    CASE
                        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(unofficial_date, 'YYYY-MM-DD'))) < 3 THEN '0-2 years'
                        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(unofficial_date, 'YYYY-MM-DD'))) BETWEEN 3 AND 5 THEN '3-5 years'
                        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(unofficial_date, 'YYYY-MM-DD'))) BETWEEN 6 AND 10 THEN '6-10 years'
                        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, TO_DATE(unofficial_date, 'YYYY-MM-DD'))) BETWEEN 11 AND 15 THEN '11-15 years'
                        ELSE '15+ years'
                    END as service_range
                FROM officers
                WHERE unofficial_date IS NOT NULL
                    AND deleted_at IS NULL
            ) AS service_data
            GROUP BY service_range
            ORDER BY
                CASE service_range
                    WHEN '0-2 years' THEN 1
                    WHEN '3-5 years' THEN 2
                    WHEN '6-10 years' THEN 3
                    WHEN '11-15 years' THEN 4
                    ELSE 5
                END
        ");

        $labels = [];
        $data = [];
        foreach ($result as $item) {
            $labels[] = $item->service_range;
            $data[] = $item->count;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getAgeGroupsData()
    {
        $result = DB::select("
            SELECT
                age_group,
                COUNT(*) as count
            FROM (
                SELECT
                    CASE
                        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, dob)) < 25 THEN 'Under 25'
                        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, dob)) BETWEEN 25 AND 35 THEN '25-35'
                        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, dob)) BETWEEN 36 AND 45 THEN '36-45'
                        WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, dob)) BETWEEN 46 AND 55 THEN '46-55'
                        ELSE 'Over 55'
                    END as age_group
                FROM people p
                INNER JOIN officers o ON p.id = o.people_id
                WHERE o.deleted_at IS NULL
                    AND p.dob IS NOT NULL
            ) AS age_data
            GROUP BY age_group
            ORDER BY
                CASE age_group
                    WHEN 'Under 25' THEN 1
                    WHEN '25-35' THEN 2
                    WHEN '36-45' THEN 3
                    WHEN '46-55' THEN 4
                    ELSE 5
                END
        ");

        $labels = [];
        $data = [];
        foreach ($result as $item) {
            $labels[] = $item->age_group;
            $data[] = $item->count;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getRankDistributionData()
    {
        $result = DB::select("
            SELECT
                r.name as rank_name,
                COUNT(*) as count
            FROM officer_ranks orank
            INNER JOIN ranks r ON orank.rank_id = r.id
            WHERE orank.deleted_at IS NULL
                AND (orank.\"end\" IS NULL OR orank.\"end\" = '9999-12-31')
            GROUP BY r.name
            ORDER BY count DESC
            LIMIT 6
        ");

        $labels = [];
        $data = [];
        foreach ($result as $item) {
            $labels[] = $item->rank_name;
            $data[] = $item->count;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getMedalDistributionData()
    {
        $result = DB::select("
            SELECT
                m.name as medal_name,
                COUNT(*) as count
            FROM officer_medals om
            INNER JOIN medals m ON om.medal_id = m.id
            WHERE om.deleted_at IS NULL
            GROUP BY m.name
            ORDER BY count DESC
            LIMIT 5
        ");

        $labels = [];
        $data = [];
        foreach ($result as $item) {
            $labels[] = $item->medal_name;
            $data[] = $item->count;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getOrganizationData()
    {
        $result = DB::select("
            SELECT
                org.name as organization_name,
                COUNT(DISTINCT o.id) as officer_count
            FROM organizations org
            INNER JOIN organization_structures os ON org.id = os.organization_id
            INNER JOIN organization_structure_positions osp ON os.id = osp.organization_structure_id
            INNER JOIN officer_jobs oj ON osp.id = oj.organization_structure_position_id
            INNER JOIN officers o ON oj.officer_id = o.id
            WHERE oj.\"end\" IS NULL
                AND oj.deleted_at IS NULL
                AND o.deleted_at IS NULL
            GROUP BY org.name
            ORDER BY officer_count DESC
            LIMIT 5
        ");

        $labels = [];
        $data = [];
        foreach ($result as $item) {
            $labels[] = $item->organization_name;
            $data[] = $item->officer_count;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getFillRateData()
    {
        $result = DB::select("
            SELECT
                COUNT(DISTINCT CASE WHEN oj.id IS NOT NULL THEN oj.officer_id END) as filled_positions,
                SUM(osp.total_jobs) - COUNT(DISTINCT CASE WHEN oj.id IS NOT NULL THEN oj.officer_id END) as vacant_positions
            FROM organization_structure_positions osp
            LEFT JOIN officer_jobs oj ON osp.id = oj.organization_structure_position_id
                AND oj.\"end\" IS NULL
                AND oj.deleted_at IS NULL
            WHERE osp.deleted_at IS NULL
        ");

        $filled = (int)($result[0]->filled_positions ?? 0);
        $vacant = (int)($result[0]->vacant_positions ?? 0);

        return [
            'labels' => ['Filled Positions', 'Vacant Positions'],
            'data' => [$filled, $vacant]
        ];
    }

    private function getEducationTrendsData()
    {
        $result = DB::select("
            SELECT
                EXTRACT(YEAR FROM TO_DATE(c.\"end\", 'YYYY-MM-DD')) as year,
                cg.education_level,
                COUNT(*) as count
            FROM certificates c
            INNER JOIN certificate_groups cg ON c.certificate_group_id = cg.id
            WHERE c.deleted_at IS NULL
                AND c.\"end\" IS NOT NULL
                AND cg.education_level IN ('PhD', 'Master', 'Bachelor')
            GROUP BY year, cg.education_level
            ORDER BY year DESC
        ");

        $years = [];
        $phdData = [];
        $mastersData = [];
        $bachelorsData = [];

        foreach ($result as $item) {
            $year = (int)$item->year;
            if (!in_array($year, $years)) {
                $years[] = $year;
            }

            if ($item->education_level === 'PhD') {
                $phdData[$year] = $item->count;
            } elseif ($item->education_level === 'Master') {
                $mastersData[$year] = $item->count;
            } elseif ($item->education_level === 'Bachelor') {
                $bachelorsData[$year] = $item->count;
            }
        }

        sort($years);

        $phd = [];
        $masters = [];
        $bachelors = [];

        foreach ($years as $year) {
            $phd[] = $phdData[$year] ?? 0;
            $masters[] = $mastersData[$year] ?? 0;
            $bachelors[] = $bachelorsData[$year] ?? 0;
        }

        return [
            'years' => $years,
            'phd' => $phd,
            'masters' => $masters,
            'bachelors' => $bachelors
        ];
    }

    private function getCareerProgressionData()
    {
        $result = DB::select("
            WITH rank_changes AS (
                SELECT
                    officer_id,
                    rank_id,
                    start,
                    LAG(rank_id) OVER (PARTITION BY officer_id ORDER BY start) as prev_rank_id,
                    LAG(start) OVER (PARTITION BY officer_id ORDER BY start) as prev_start
                FROM officer_ranks
                WHERE deleted_at IS NULL
            ),
            rank_names AS (
                SELECT
                    rc.officer_id,
                    r_curr.name as current_rank,
                    r_prev.name as previous_rank,
                    EXTRACT(YEAR FROM AGE(TO_DATE(rc.start, 'YYYY-MM-DD'), TO_DATE(rc.prev_start, 'YYYY-MM-DD'))) as years_to_promotion
                FROM rank_changes rc
                INNER JOIN ranks r_curr ON rc.rank_id = r_curr.id
                INNER JOIN ranks r_prev ON rc.prev_rank_id = r_prev.id
                WHERE rc.prev_rank_id IS NOT NULL
            )
            SELECT
                CONCAT(previous_rank, ' → ', current_rank) as promotion_path,
                ROUND(AVG(years_to_promotion), 1) as avg_years
            FROM rank_names
            WHERE years_to_promotion IS NOT NULL
            GROUP BY promotion_path
            ORDER BY avg_years
            LIMIT 5
        ");

        $ranks = [];
        $avgYears = [];

        foreach ($result as $item) {
            $ranks[] = $item->promotion_path;
            $avgYears[] = (float)$item->avg_years;
        }

        return [
            'ranks' => $ranks,
            'avgYears' => $avgYears
        ];
    }

}
