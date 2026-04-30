<?php

namespace App\Services;

use App\Models\People;
use Illuminate\Support\Facades\DB;

class PeopleSearchService
{
    /**
     * Search with detailed match information (supports space-less search)
     * Now includes officer code search
     */
    public function search($query, $limit = 20)
    {
        $query = trim($query);
        if (strlen($query) < 2) {
            return collect([]);
        }

        $likeTerm = "%{$query}%";
        $noSpaceTerm = str_replace(' ', '', $query);
        $noSpaceLikeTerm = "%{$noSpaceTerm}%";

        $results = DB::select("
            SELECT
                p.id,
                -- Officer information
                o.code as officer_code,
                o.officer_type,
                o.organization_id,
                o.position_id,
                o.rank_id,

                -- Calculate relevance score (with space-less support and officer code)
                (
                    -- Exact matches with spaces (highest priority)
                    CASE WHEN CONCAT(p.firstname, ' ', p.lastname) ILIKE ? THEN 100 ELSE 0 END +
                    CASE WHEN CONCAT(p.lastname, ' ', p.firstname) ILIKE ? THEN 100 ELSE 0 END +
                    CASE WHEN CONCAT(p.enfirstname, ' ', p.enlastname) ILIKE ? THEN 90 ELSE 0 END +
                    CASE WHEN CONCAT(p.enlastname, ' ', p.enfirstname) ILIKE ? THEN 90 ELSE 0 END +

                    -- Officer code match (high priority for exact matches)
                    CASE WHEN o.code ILIKE ? THEN 98 ELSE 0 END +

                    -- Space-less matches (for combined names without spaces)
                    CASE WHEN REPLACE(CONCAT(p.firstname, p.lastname), ' ', '') ILIKE ? THEN 85 ELSE 0 END +
                    CASE WHEN REPLACE(CONCAT(p.lastname, p.firstname), ' ', '') ILIKE ? THEN 85 ELSE 0 END +
                    CASE WHEN REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', '') ILIKE ? THEN 75 ELSE 0 END +
                    CASE WHEN REPLACE(CONCAT(p.enlastname, p.enfirstname), ' ', '') ILIKE ? THEN 75 ELSE 0 END +

                    -- Individual field matches
                    CASE WHEN p.firstname ILIKE ? THEN 60 ELSE 0 END +
                    CASE WHEN p.lastname ILIKE ? THEN 60 ELSE 0 END +
                    CASE WHEN p.enfirstname ILIKE ? THEN 50 ELSE 0 END +
                    CASE WHEN p.enlastname ILIKE ? THEN 50 ELSE 0 END +

                    -- NID match
                    CASE WHEN p.nid ILIKE ? THEN 95 ELSE 0 END +

                    -- Officer code partial match
                    CASE WHEN o.code ILIKE ? THEN 80 ELSE 0 END +

                    -- Individual field space-less matches (for partial names without spaces)
                    CASE WHEN REPLACE(p.firstname, ' ', '') ILIKE ? THEN 55 ELSE 0 END +
                    CASE WHEN REPLACE(p.lastname, ' ', '') ILIKE ? THEN 55 ELSE 0 END +
                    CASE WHEN REPLACE(p.enfirstname, ' ', '') ILIKE ? THEN 45 ELSE 0 END +
                    CASE WHEN REPLACE(p.enlastname, ' ', '') ILIKE ? THEN 45 ELSE 0 END
                ) as relevance_score,

                -- Build matched fields as JSON
                jsonb_build_object(
                    'khmer_full_name', jsonb_build_object(
                        'matched', CONCAT(p.firstname, ' ', p.lastname) ILIKE ?,
                        'value', CONCAT(p.firstname, ' ', p.lastname)
                    ),
                    'khmer_full_name_reverse', jsonb_build_object(
                        'matched', CONCAT(p.lastname, ' ', p.firstname) ILIKE ?,
                        'value', CONCAT(p.lastname, ' ', p.firstname)
                    ),
                    'latin_full_name', jsonb_build_object(
                        'matched', CONCAT(p.enfirstname, ' ', p.enlastname) ILIKE ?,
                        'value', CONCAT(p.enfirstname, ' ', p.enlastname)
                    ),
                    'latin_full_name_reverse', jsonb_build_object(
                        'matched', CONCAT(p.enlastname, ' ', p.enfirstname) ILIKE ?,
                        'value', CONCAT(p.enlastname, ' ', p.enfirstname)
                    ),
                    'khmer_combined_no_space', jsonb_build_object(
                        'matched', REPLACE(CONCAT(p.firstname, p.lastname), ' ', '') ILIKE ?,
                        'value', REPLACE(CONCAT(p.firstname, p.lastname), ' ', '')
                    ),
                    'khmer_reverse_no_space', jsonb_build_object(
                        'matched', REPLACE(CONCAT(p.lastname, p.firstname), ' ', '') ILIKE ?,
                        'value', REPLACE(CONCAT(p.lastname, p.firstname), ' ', '')
                    ),
                    'latin_combined_no_space', jsonb_build_object(
                        'matched', REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', '') ILIKE ?,
                        'value', REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', '')
                    ),
                    'latin_reverse_no_space', jsonb_build_object(
                        'matched', REPLACE(CONCAT(p.enlastname, p.enfirstname), ' ', '') ILIKE ?,
                        'value', REPLACE(CONCAT(p.enlastname, p.enfirstname), ' ', '')
                    ),
                    'firstname', jsonb_build_object(
                        'matched', p.firstname ILIKE ?,
                        'value', p.firstname
                    ),
                    'lastname', jsonb_build_object(
                        'matched', p.lastname ILIKE ?,
                        'value', p.lastname
                    ),
                    'enfirstname', jsonb_build_object(
                        'matched', p.enfirstname ILIKE ?,
                        'value', p.enfirstname
                    ),
                    'enlastname', jsonb_build_object(
                        'matched', p.enlastname ILIKE ?,
                        'value', p.enlastname
                    ),
                    'nid', jsonb_build_object(
                        'matched', p.nid ILIKE ?,
                        'value', p.nid
                    ),
                    'officer_code', jsonb_build_object(
                        'matched', o.code ILIKE ?,
                        'value', o.code
                    ),
                    'firstname_no_space', jsonb_build_object(
                        'matched', REPLACE(p.firstname, ' ', '') ILIKE ?,
                        'value', p.firstname
                    ),
                    'lastname_no_space', jsonb_build_object(
                        'matched', REPLACE(p.lastname, ' ', '') ILIKE ?,
                        'value', p.lastname
                    ),
                    'enfirstname_no_space', jsonb_build_object(
                        'matched', REPLACE(p.enfirstname, ' ', '') ILIKE ?,
                        'value', p.enfirstname
                    ),
                    'enlastname_no_space', jsonb_build_object(
                        'matched', REPLACE(p.enlastname, ' ', '') ILIKE ?,
                        'value', p.enlastname
                    )
                ) as match_details

            FROM people p
            LEFT JOIN officers o ON o.people_id = p.id AND o.deleted_at IS NULL
            WHERE
                -- Regular matches with spaces
                p.firstname ILIKE ? OR
                p.lastname ILIKE ? OR
                p.enfirstname ILIKE ? OR
                p.enlastname ILIKE ? OR
                p.nid ILIKE ? OR
                o.code ILIKE ? OR
                CONCAT(p.firstname, ' ', p.lastname) ILIKE ? OR
                CONCAT(p.lastname, ' ', p.firstname) ILIKE ? OR
                CONCAT(p.enfirstname, ' ', p.enlastname) ILIKE ? OR
                CONCAT(p.enlastname, ' ', p.enfirstname) ILIKE ? OR

                -- Space-less matches (for searching without spaces)
                REPLACE(CONCAT(p.firstname, p.lastname), ' ', '') ILIKE ? OR
                REPLACE(CONCAT(p.lastname, p.firstname), ' ', '') ILIKE ? OR
                REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', '') ILIKE ? OR
                REPLACE(CONCAT(p.enlastname, p.enfirstname), ' ', '') ILIKE ? OR
                REPLACE(p.firstname, ' ', '') ILIKE ? OR
                REPLACE(p.lastname, ' ', '') ILIKE ? OR
                REPLACE(p.enfirstname, ' ', '') ILIKE ? OR
                REPLACE(p.enlastname, ' ', '') ILIKE ?
            ORDER BY relevance_score DESC
        ", array_merge(
            // For score calculation (19 parameters for pattern matches)
            array_fill(0, 4, $likeTerm),        // Regular full name matches (4)
            array_fill(0, 1, $likeTerm),        // Officer code exact match (1)
            array_fill(0, 4, $noSpaceLikeTerm), // Space-less full name matches (4)
            array_fill(0, 5, $likeTerm),        // Individual field matches (4 names + 1 NID)
            array_fill(0, 1, $likeTerm),        // Officer code partial match (1)
            array_fill(0, 4, $noSpaceLikeTerm), // Individual field space-less (4)

            // For match_details (19 parameters)
            array_fill(0, 4, $likeTerm),        // Regular full name (4)
            array_fill(0, 4, $noSpaceLikeTerm), // Space-less combined (4)
            array_fill(0, 5, $likeTerm),        // Individual fields (4 names + 1 NID)
            array_fill(0, 1, $likeTerm),        // Officer code (1)
            array_fill(0, 4, $noSpaceLikeTerm), // Individual fields no space (4)

            // For WHERE clause (19 parameters)
            array_fill(0, 10, $likeTerm),       // Regular matches (8 names + 1 NID + 1 code)
            array_fill(0, 8, $noSpaceLikeTerm)  // Space-less matches (8)
        ));

        // Process results to extract only matched fields
        return collect($results)->map(function($result) {
            $matchDetails = json_decode($result->match_details, true);

            // Filter only matched fields
            $matchedFields = [];
            $matchedValues = [];

            foreach ($matchDetails as $field => $info) {
                if ($info['matched']) {
                    $matchedFields[] = $field;
                    $matchedValues[] = $info['value'];
                }
            }

            $result->matched_fields = $matchedFields;
            $result->matched_values = $matchedValues;
            $result->match_details = null;

            return $result;
        });
    }

    /**
     * Get search suggestions with field matching (supports space-less)
     * Now includes officer code suggestions
     */
    public function getSuggestions($query, $limit = 5)
    {
        $likeTerm = "%{$query}%";
        $noSpaceTerm = str_replace(' ', '', $query);
        $noSpaceLikeTerm = "%{$noSpaceTerm}%";

        $sql = "
            SELECT suggestion, field_matched, priority FROM (
                -- Khmer full name
                SELECT CONCAT(p.firstname, ' ', p.lastname) as suggestion, 'khmer_full_name' as field_matched, 1 as priority
                FROM people p WHERE CONCAT(p.firstname, ' ', p.lastname) ILIKE ?
                LIMIT ?

                UNION ALL

                -- Khmer full name reverse
                SELECT CONCAT(p.lastname, ' ', p.firstname) as suggestion, 'khmer_full_name_reverse' as field_matched, 2 as priority
                FROM people p WHERE CONCAT(p.lastname, ' ', p.firstname) ILIKE ?
                LIMIT ?

                UNION ALL

                -- Latin full name
                SELECT CONCAT(p.enfirstname, ' ', p.enlastname) as suggestion, 'latin_full_name' as field_matched, 3 as priority
                FROM people p WHERE CONCAT(p.enfirstname, ' ', p.enlastname) ILIKE ?
                LIMIT ?

                UNION ALL

                -- Latin full name reverse
                SELECT CONCAT(p.enlastname, ' ', p.enfirstname) as suggestion, 'latin_full_name_reverse' as field_matched, 4 as priority
                FROM people p WHERE CONCAT(p.enlastname, ' ', p.enfirstname) ILIKE ?
                LIMIT ?

                UNION ALL

                -- Khmer combined no space
                SELECT REPLACE(CONCAT(p.firstname, p.lastname), ' ', '') as suggestion, 'khmer_combined_no_space' as field_matched, 5 as priority
                FROM people p WHERE REPLACE(CONCAT(p.firstname, p.lastname), ' ', '') ILIKE ?
                LIMIT ?

                UNION ALL

                -- Khmer reverse no space
                SELECT REPLACE(CONCAT(p.lastname, p.firstname), ' ', '') as suggestion, 'khmer_reverse_no_space' as field_matched, 6 as priority
                FROM people p WHERE REPLACE(CONCAT(p.lastname, p.firstname), ' ', '') ILIKE ?
                LIMIT ?

                UNION ALL

                -- Latin combined no space
                SELECT REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', '') as suggestion, 'latin_combined_no_space' as field_matched, 7 as priority
                FROM people p WHERE REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', '') ILIKE ?
                LIMIT ?

                UNION ALL

                -- Latin reverse no space
                SELECT REPLACE(CONCAT(p.enlastname, p.enfirstname), ' ', '') as suggestion, 'latin_reverse_no_space' as field_matched, 8 as priority
                FROM people p WHERE REPLACE(CONCAT(p.enlastname, p.enfirstname), ' ', '') ILIKE ?
                LIMIT ?

                UNION ALL

                -- Firstname
                SELECT p.firstname as suggestion, 'firstname' as field_matched, 9 as priority
                FROM people p WHERE p.firstname ILIKE ?
                LIMIT ?

                UNION ALL

                -- Lastname
                SELECT p.lastname as suggestion, 'lastname' as field_matched, 10 as priority
                FROM people p WHERE p.lastname ILIKE ?
                LIMIT ?

                UNION ALL

                -- Enfirstname
                SELECT p.enfirstname as suggestion, 'enfirstname' as field_matched, 11 as priority
                FROM people p WHERE p.enfirstname ILIKE ?
                LIMIT ?

                UNION ALL

                -- Enlastname
                SELECT p.enlastname as suggestion, 'enlastname' as field_matched, 12 as priority
                FROM people p WHERE p.enlastname ILIKE ?
                LIMIT ?

                UNION ALL

                -- NID
                SELECT p.nid as suggestion, 'nid' as field_matched, 13 as priority
                FROM people p WHERE p.nid ILIKE ?
                LIMIT ?

                UNION ALL

                -- Officer Code
                SELECT o.code as suggestion, 'officer_code' as field_matched, 14 as priority
                FROM officers o
                WHERE o.deleted_at IS NULL AND o.code ILIKE ?
                LIMIT ?

                UNION ALL

                -- Firstname no space
                SELECT p.firstname as suggestion, 'firstname_no_space' as field_matched, 15 as priority
                FROM people p WHERE REPLACE(p.firstname, ' ', '') ILIKE ?
                LIMIT ?

                UNION ALL

                -- Lastname no space
                SELECT p.lastname as suggestion, 'lastname_no_space' as field_matched, 16 as priority
                FROM people p WHERE REPLACE(p.lastname, ' ', '') ILIKE ?
                LIMIT ?

                UNION ALL

                -- Enfirstname no space
                SELECT p.enfirstname as suggestion, 'enfirstname_no_space' as field_matched, 17 as priority
                FROM people p WHERE REPLACE(p.enfirstname, ' ', '') ILIKE ?
                LIMIT ?

                UNION ALL

                -- Enlastname no space
                SELECT p.enlastname as suggestion, 'enlastname_no_space' as field_matched, 18 as priority
                FROM people p WHERE REPLACE(p.enlastname, ' ', '') ILIKE ?
                LIMIT ?
            ) as suggestions
            GROUP BY suggestion, field_matched, priority
            ORDER BY priority
            LIMIT ?
        ";

        $params = [];

        // Add parameters for each of the 18 subqueries (each has 2 parameters: LIKE term and LIMIT)
        for ($i = 0; $i < 18; $i++) {
            if ($i < 4) {
                // First 4 subqueries: regular full name matches
                $params[] = $likeTerm;
            } elseif ($i < 8) {
                // Next 4 subqueries: space-less matches
                $params[] = $noSpaceLikeTerm;
            } elseif ($i < 13) {
                // Next 5 subqueries: individual field exact matches (firstname, lastname, enfirstname, enlastname, nid)
                $params[] = $likeTerm;
            } elseif ($i < 14) {
                // Officer code subquery
                $params[] = $likeTerm;
            } else {
                // Last 4 subqueries: individual field space-less matches
                $params[] = $noSpaceLikeTerm;
            }
            $params[] = $limit; // LIMIT for each subquery
        }

        $params[] = $limit; // Final LIMIT

        $results = DB::select($sql, $params);

        return collect($results)
            ->unique('suggestion')
            ->take($limit)
            ->values();
    }

    /**
     * Search with highlighting (supports space-less)
     * Now includes officer code highlighting
     */
    public function searchWithHighlight($query, $limit = 20)
    {
        $query = trim($query);
        if (strlen($query) < 2) {
            return collect([]);
        }

        $likeTerm = "%{$query}%";
        $noSpaceTerm = str_replace(' ', '', $query);
        $highlightStart = '<mark class="bg-yellow-200 px-0.5 rounded">';
        $highlightEnd = '</mark>';

        $results = DB::select("
            SELECT
                p.id,
                p.firstname,
                p.lastname,
                p.enfirstname,
                p.enlastname,
                p.nid,
                p.gender,
                p.dob,
                p.email,
                p.mobile_phone,

                -- Officer information
                o.code as officer_code,
                o.officer_type,
                o.organization_id,
                o.position_id,
                o.rank_id,

                -- Regular names with spaces
                CONCAT(p.firstname, ' ', p.lastname) as khmer_full_name,
                CONCAT(p.enfirstname, ' ', p.enlastname) as latin_full_name,

                -- Space-less versions for highlighting
                REPLACE(CONCAT(p.firstname, p.lastname), ' ', '') as khmer_combined_no_space,
                REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', '') as latin_combined_no_space,

                -- Calculate relevance score
                (
                    CASE WHEN CONCAT(p.firstname, ' ', p.lastname) ILIKE ? THEN 100 ELSE 0 END +
                    CASE WHEN CONCAT(p.lastname, ' ', p.firstname) ILIKE ? THEN 100 ELSE 0 END +
                    CASE WHEN CONCAT(p.enfirstname, ' ', p.enlastname) ILIKE ? THEN 90 ELSE 0 END +
                    CASE WHEN CONCAT(p.enlastname, ' ', p.enfirstname) ILIKE ? THEN 90 ELSE 0 END +
                    CASE WHEN REPLACE(CONCAT(p.firstname, p.lastname), ' ', '') ILIKE ? THEN 85 ELSE 0 END +
                    CASE WHEN REPLACE(CONCAT(p.lastname, p.firstname), ' ', '') ILIKE ? THEN 85 ELSE 0 END +
                    CASE WHEN REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', '') ILIKE ? THEN 75 ELSE 0 END +
                    CASE WHEN REPLACE(CONCAT(p.enlastname, p.enfirstname), ' ', '') ILIKE ? THEN 75 ELSE 0 END +
                    CASE WHEN p.firstname ILIKE ? THEN 60 ELSE 0 END +
                    CASE WHEN p.lastname ILIKE ? THEN 60 ELSE 0 END +
                    CASE WHEN p.enfirstname ILIKE ? THEN 50 ELSE 0 END +
                    CASE WHEN p.enlastname ILIKE ? THEN 50 ELSE 0 END +
                    CASE WHEN p.nid ILIKE ? THEN 95 ELSE 0 END +
                    CASE WHEN o.code ILIKE ? THEN 98 ELSE 0 END
                ) as relevance_score,

                -- Build matched fields info
                (
                    SELECT jsonb_agg(field_info)
                    FROM (
                        VALUES
                            ('khmer_full_name', CONCAT(p.firstname, ' ', p.lastname) ILIKE ?),
                            ('khmer_full_name_reverse', CONCAT(p.lastname, ' ', p.firstname) ILIKE ?),
                            ('latin_full_name', CONCAT(p.enfirstname, ' ', p.enlastname) ILIKE ?),
                            ('latin_full_name_reverse', CONCAT(p.enlastname, ' ', p.enfirstname) ILIKE ?),
                            ('khmer_combined_no_space', REPLACE(CONCAT(p.firstname, p.lastname), ' ', '') ILIKE ?),
                            ('khmer_reverse_no_space', REPLACE(CONCAT(p.lastname, p.firstname), ' ', '') ILIKE ?),
                            ('latin_combined_no_space', REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', '') ILIKE ?),
                            ('latin_reverse_no_space', REPLACE(CONCAT(p.enlastname, p.enfirstname), ' ', '') ILIKE ?),
                            ('firstname', p.firstname ILIKE ?),
                            ('lastname', p.lastname ILIKE ?),
                            ('enfirstname', p.enfirstname ILIKE ?),
                            ('enlastname', p.enlastname ILIKE ?),
                            ('nid', p.nid ILIKE ?),
                            ('officer_code', o.code ILIKE ?)
                    ) AS matches(field_name, is_match)
                    WHERE is_match = true
                ) as matched_fields_info

            FROM people p
            LEFT JOIN officers o ON o.people_id = p.id AND o.deleted_at IS NULL
            WHERE
                -- Regular matches
                p.firstname ILIKE ? OR
                p.lastname ILIKE ? OR
                p.enfirstname ILIKE ? OR
                p.enlastname ILIKE ? OR
                p.nid ILIKE ? OR
                o.code ILIKE ? OR
                CONCAT(p.firstname, ' ', p.lastname) ILIKE ? OR
                CONCAT(p.lastname, ' ', p.firstname) ILIKE ? OR
                CONCAT(p.enfirstname, ' ', p.enlastname) ILIKE ? OR
                CONCAT(p.enlastname, ' ', p.enfirstname) ILIKE ? OR

                -- Space-less matches
                REPLACE(CONCAT(p.firstname, p.lastname), ' ', '') ILIKE ? OR
                REPLACE(CONCAT(p.lastname, p.firstname), ' ', '') ILIKE ? OR
                REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', '') ILIKE ? OR
                REPLACE(CONCAT(p.enlastname, p.enfirstname), ' ', '') ILIKE ?
            ORDER BY relevance_score DESC
            LIMIT ?
        ", array_merge(
            // For score calculation (14 parameters)
            array_fill(0, 4, $likeTerm),        // Regular full name matches (4)
            array_fill(0, 4, $noSpaceTerm),     // Space-less full name matches (4)
            array_fill(0, 6, $likeTerm),        // Individual field matches (4 names + 1 NID + 1 code)

            // For matched fields info (14 parameters)
            array_fill(0, 8, $likeTerm),        // Regular matches (8)
            array_fill(0, 6, $noSpaceTerm),     // Space-less matches (4 names + 1 NID + 1 code)

            // For WHERE clause (14 parameters)
            array_fill(0, 10, $likeTerm),       // Regular matches (8 names + 1 NID + 1 code)
            array_fill(0, 4, $noSpaceTerm),     // Space-less matches (4)

            [$limit]
        ));

        // Process results
        return collect($results)->map(function($result) use($query, $highlightStart, $highlightEnd) {
            $matchedFields = json_decode($result->matched_fields_info, true);
            $result->matched_fields = array_column($matchedFields, 'field_name');
            $result->matched_fields_info = null;

            // Add highlight for the matched term in names, NID, and officer code
            $searchTerms = [trim($query), str_replace(' ', '', $query)];
            foreach ($searchTerms as $term) {
                if (!empty($term)) {
                    $result->khmer_full_name = str_ireplace(
                        $term,
                        "{$highlightStart}{$term}{$highlightEnd}",
                        $result->khmer_full_name
                    );
                    $result->latin_full_name = str_ireplace(
                        $term,
                        "{$highlightStart}{$term}{$highlightEnd}",
                        $result->latin_full_name
                    );
                    if (isset($result->nid) && $result->nid) {
                        $result->nid = str_ireplace(
                            $term,
                            "{$highlightStart}{$term}{$highlightEnd}",
                            $result->nid
                        );
                    }
                    if (isset($result->officer_code) && $result->officer_code) {
                        $result->officer_code = str_ireplace(
                            $term,
                            "{$highlightStart}{$term}{$highlightEnd}",
                            $result->officer_code
                        );
                    }
                }
            }

            return $result;
        });
    }

    /**
     * Search with normalization (handles spaces, special characters)
     * Now includes officer code search
     */
    public function normalizedSearch($query, $limit = 20)
    {
        $query = trim($query);
        if (strlen($query) < 2) {
            return collect([]);
        }

        // Normalize the query: remove spaces, normalize unicode
        $normalizedQuery = preg_replace('/\s+/', '', $query);
        $normalizedQuery = $this->normalizeUnicode($normalizedQuery);

        $results = DB::select("
            SELECT
                p.*,
                o.code as officer_code,
                o.officer_type,
                o.organization_id,
                o.position_id,
                o.rank_id,

                -- Normalize fields for comparison
                REPLACE(REPLACE(REPLACE(
                    REPLACE(CONCAT(p.firstname, p.lastname), ' ', ''),
                    '្', ''), '​', ''), '‍', '') as normalized_khmer,
                REPLACE(REPLACE(REPLACE(
                    REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', ''),
                    '-', ''), '.', ''), '. \"' . \"', '') as normalized_latin,

                -- Calculate match score
                (
                    CASE WHEN REPLACE(REPLACE(REPLACE(
                        REPLACE(CONCAT(p.firstname, p.lastname), ' ', ''),
                        '្', ''), '​', ''), '‍', '') ILIKE ? THEN 100 ELSE 0 END +
                    CASE WHEN REPLACE(REPLACE(REPLACE(
                        REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', ''),
                        '-', ''), '.', ''), '. \"' . \"', '') ILIKE ? THEN 90 ELSE 0 END +
                    CASE WHEN p.nid ILIKE ? THEN 95 ELSE 0 END +
                    CASE WHEN o.code ILIKE ? THEN 98 ELSE 0 END
                ) as relevance_score

            FROM people p
            LEFT JOIN officers o ON o.people_id = p.id AND o.deleted_at IS NULL
            WHERE
                REPLACE(REPLACE(REPLACE(
                    REPLACE(CONCAT(p.firstname, p.lastname), ' ', ''),
                    '្', ''), '​', ''), '‍', '') ILIKE ? OR
                REPLACE(REPLACE(REPLACE(
                    REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', ''),
                    '-', ''), '.', ''), '. \"' . \"', '') ILIKE ? OR
                p.nid ILIKE ? OR
                o.code ILIKE ?
            ORDER BY relevance_score DESC
            LIMIT ?
        ", [
            "%{$normalizedQuery}%",
            "%{$normalizedQuery}%",
            "%{$normalizedQuery}%",
            "%{$normalizedQuery}%",
            "%{$normalizedQuery}%",
            "%{$normalizedQuery}%",
            "%{$normalizedQuery}%",
            "%{$normalizedQuery}%",
            $limit
        ]);

        return collect($results);
    }

    /**
     * Search specifically by officer code (fast lookup)
     */
    public function searchByOfficerCode($code, $limit = 20)
    {
        if (empty($code)) {
            return collect([]);
        }

        $results = DB::select("
            SELECT
                p.*,
                o.code as officer_code,
                o.officer_type,
                o.organization_id,
                o.position_id,
                o.rank_id,
                o.official_date,
                o.unofficial_date
            FROM people p
            INNER JOIN officers o ON o.people_id = p.id AND o.deleted_at IS NULL
            WHERE o.code ILIKE ?
            ORDER BY o.code
            LIMIT ?
        ", ["%{$code}%", $limit]);

        return collect($results);
    }

    /**
     * Advanced search with officer filters
     */
    public function advancedSearch($query, $filters = [], $limit = 20)
    {
        $query = trim($query);
        if (strlen($query) < 2 && empty($filters)) {
            return collect([]);
        }

        $likeTerm = "%{$query}%";
        $noSpaceTerm = str_replace(' ', '', $query);
        $noSpaceLikeTerm = "%{$noSpaceTerm}%";

        $sql = "
            SELECT
                p.id,
                p.firstname,
                p.lastname,
                p.enfirstname,
                p.enlastname,
                p.nid,
                o.code as officer_code,
                o.officer_type,
                o.organization_id,
                o.position_id,
                o.rank_id,

                -- Calculate relevance score
                (
                    CASE WHEN CONCAT(p.firstname, ' ', p.lastname) ILIKE ? THEN 100 ELSE 0 END +
                    CASE WHEN CONCAT(p.lastname, ' ', p.firstname) ILIKE ? THEN 100 ELSE 0 END +
                    CASE WHEN CONCAT(p.enfirstname, ' ', p.enlastname) ILIKE ? THEN 90 ELSE 0 END +
                    CASE WHEN CONCAT(p.enlastname, ' ', p.enfirstname) ILIKE ? THEN 90 ELSE 0 END +
                    CASE WHEN o.code ILIKE ? THEN 98 ELSE 0 END +
                    CASE WHEN p.nid ILIKE ? THEN 95 ELSE 0 END +
                    CASE WHEN p.firstname ILIKE ? THEN 60 ELSE 0 END +
                    CASE WHEN p.lastname ILIKE ? THEN 60 ELSE 0 END
                ) as relevance_score

            FROM people p
            LEFT JOIN officers o ON o.people_id = p.id AND o.deleted_at IS NULL
            WHERE 1=1
        ";

        $params = [
            $likeTerm, $likeTerm, $likeTerm, $likeTerm,  // Full name matches
            $likeTerm,  // Officer code
            $likeTerm,  // NID
            $likeTerm, $likeTerm  // Firstname, lastname
        ];

        // Add search conditions if query is provided
        if (strlen($query) >= 2) {
            $sql .= " AND (
                p.firstname ILIKE ? OR
                p.lastname ILIKE ? OR
                p.enfirstname ILIKE ? OR
                p.enlastname ILIKE ? OR
                p.nid ILIKE ? OR
                o.code ILIKE ? OR
                CONCAT(p.firstname, ' ', p.lastname) ILIKE ? OR
                CONCAT(p.enfirstname, ' ', p.enlastname) ILIKE ?
            )";

            $params = array_merge($params, [
                $likeTerm, $likeTerm, $likeTerm, $likeTerm,
                $likeTerm, $likeTerm, $likeTerm, $likeTerm
            ]);
        }

        // Add officer type filter
        if (!empty($filters['officer_type'])) {
            $sql .= " AND o.officer_type = ?";
            $params[] = $filters['officer_type'];
        }

        // Add organization filter
        if (!empty($filters['organization_id'])) {
            $sql .= " AND o.organization_id = ?";
            $params[] = $filters['organization_id'];
        }

        // Add position filter
        if (!empty($filters['position_id'])) {
            $sql .= " AND o.position_id = ?";
            $params[] = $filters['position_id'];
        }

        // Add rank filter
        if (!empty($filters['rank_id'])) {
            $sql .= " AND o.rank_id = ?";
            $params[] = $filters['rank_id'];
        }

        $sql .= " ORDER BY relevance_score DESC LIMIT ?";
        $params[] = $limit;

        $results = DB::select($sql, $params);

        return collect($results);
    }

    /**
     * Normalize Unicode characters (handle Khmer and Latin)
     */
    private function normalizeUnicode($string)
    {
        // Remove zero-width spaces and other invisible characters
        $string = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $string);

        // Normalize Khmer vowels and diacritics
        $string = preg_replace('/\x{17B6}/u', 'ា', $string); // AA
        $string = preg_replace('/\x{17B7}/u', 'ិ', $string); // I
        $string = preg_replace('/\x{17B8}/u', 'ី', $string); // II

        return $string;
    }
}
