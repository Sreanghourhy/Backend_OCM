<?php

namespace App\Services;

use App\Models\People;
use Illuminate\Support\Facades\DB;

class PeopleSearchService
{
    /**
     * Search with detailed match information (supports space-less search)
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
                -- Calculate relevance score (with space-less support)
                (
                    -- Exact matches with spaces (highest priority)
                    CASE WHEN CONCAT(p.firstname, ' ', p.lastname) ILIKE ? THEN 100 ELSE 0 END +
                    CASE WHEN CONCAT(p.lastname, ' ', p.firstname) ILIKE ? THEN 100 ELSE 0 END +
                    CASE WHEN CONCAT(p.enfirstname, ' ', p.enlastname) ILIKE ? THEN 90 ELSE 0 END +
                    CASE WHEN CONCAT(p.enlastname, ' ', p.enfirstname) ILIKE ? THEN 90 ELSE 0 END +

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
            WHERE
                -- Regular matches with spaces
                p.firstname ILIKE ? OR
                p.lastname ILIKE ? OR
                p.enfirstname ILIKE ? OR
                p.enlastname ILIKE ? OR
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
            -- LIMIT ?
        ", array_merge(
            // For score calculation (16 parameters for pattern matches)
            array_fill(0, 4, $likeTerm),        // Regular full name matches (4)
            array_fill(0, 4, $noSpaceLikeTerm), // Space-less full name matches (4)
            array_fill(0, 4, $likeTerm),        // Individual field matches (4)
            array_fill(0, 4, $noSpaceLikeTerm), // Individual field space-less (4)

            // For match_details (16 parameters)
            array_fill(0, 4, $likeTerm),        // Regular full name (4)
            array_fill(0, 4, $noSpaceLikeTerm), // Space-less combined (4)
            array_fill(0, 4, $likeTerm),        // Individual fields (4)
            array_fill(0, 4, $noSpaceLikeTerm), // Individual fields no space (4)

            // For WHERE clause (16 parameters)
            array_fill(0, 8, $likeTerm),        // Regular matches (8)
            array_fill(0, 8, $noSpaceLikeTerm), // Space-less matches (8)

            // [$limit]
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
     */
    public function getSuggestions($query, $limit = 5)
    {
        $likeTerm = "%{$query}%";
    $noSpaceTerm = str_replace(' ', '', $query);
    $noSpaceLikeTerm = "%{$noSpaceTerm}%";

    $sql = "
        SELECT suggestion, field_matched, priority FROM (
            -- Khmer full name
            SELECT CONCAT(firstname, ' ', lastname) as suggestion, 'khmer_full_name' as field_matched, 1 as priority
            FROM people WHERE CONCAT(firstname, ' ', lastname) ILIKE ?
            LIMIT ?

            UNION ALL

            -- Khmer full name reverse
            SELECT CONCAT(lastname, ' ', firstname) as suggestion, 'khmer_full_name_reverse' as field_matched, 2 as priority
            FROM people WHERE CONCAT(lastname, ' ', firstname) ILIKE ?
            LIMIT ?

            UNION ALL

            -- Latin full name
            SELECT CONCAT(enfirstname, ' ', enlastname) as suggestion, 'latin_full_name' as field_matched, 3 as priority
            FROM people WHERE CONCAT(enfirstname, ' ', enlastname) ILIKE ?
            LIMIT ?

            UNION ALL

            -- Latin full name reverse
            SELECT CONCAT(enlastname, ' ', enfirstname) as suggestion, 'latin_full_name_reverse' as field_matched, 4 as priority
            FROM people WHERE CONCAT(enlastname, ' ', enfirstname) ILIKE ?
            LIMIT ?

            UNION ALL

            -- Khmer combined no space
            SELECT REPLACE(CONCAT(firstname, lastname), ' ', '') as suggestion, 'khmer_combined_no_space' as field_matched, 5 as priority
            FROM people WHERE REPLACE(CONCAT(firstname, lastname), ' ', '') ILIKE ?
            LIMIT ?

            UNION ALL

            -- Khmer reverse no space
            SELECT REPLACE(CONCAT(lastname, firstname), ' ', '') as suggestion, 'khmer_reverse_no_space' as field_matched, 6 as priority
            FROM people WHERE REPLACE(CONCAT(lastname, firstname), ' ', '') ILIKE ?
            LIMIT ?

            UNION ALL

            -- Latin combined no space
            SELECT REPLACE(CONCAT(enfirstname, enlastname), ' ', '') as suggestion, 'latin_combined_no_space' as field_matched, 7 as priority
            FROM people WHERE REPLACE(CONCAT(enfirstname, enlastname), ' ', '') ILIKE ?
            LIMIT ?

            UNION ALL

            -- Latin reverse no space
            SELECT REPLACE(CONCAT(enlastname, enfirstname), ' ', '') as suggestion, 'latin_reverse_no_space' as field_matched, 8 as priority
            FROM people WHERE REPLACE(CONCAT(enlastname, enfirstname), ' ', '') ILIKE ?
            LIMIT ?

            UNION ALL

            -- Firstname
            SELECT firstname as suggestion, 'firstname' as field_matched, 9 as priority
            FROM people WHERE firstname ILIKE ?
            LIMIT ?

            UNION ALL

            -- Lastname
            SELECT lastname as suggestion, 'lastname' as field_matched, 10 as priority
            FROM people WHERE lastname ILIKE ?
            LIMIT ?

            UNION ALL

            -- Enfirstname
            SELECT enfirstname as suggestion, 'enfirstname' as field_matched, 11 as priority
            FROM people WHERE enfirstname ILIKE ?
            LIMIT ?

            UNION ALL

            -- Enlastname
            SELECT enlastname as suggestion, 'enlastname' as field_matched, 12 as priority
            FROM people WHERE enlastname ILIKE ?
            LIMIT ?

            UNION ALL

            -- Firstname no space
            SELECT firstname as suggestion, 'firstname_no_space' as field_matched, 13 as priority
            FROM people WHERE REPLACE(firstname, ' ', '') ILIKE ?
            LIMIT ?

            UNION ALL

            -- Lastname no space
            SELECT lastname as suggestion, 'lastname_no_space' as field_matched, 14 as priority
            FROM people WHERE REPLACE(lastname, ' ', '') ILIKE ?
            LIMIT ?

            UNION ALL

            -- Enfirstname no space
            SELECT enfirstname as suggestion, 'enfirstname_no_space' as field_matched, 15 as priority
            FROM people WHERE REPLACE(enfirstname, ' ', '') ILIKE ?
            LIMIT ?

            UNION ALL

            -- Enlastname no space
            SELECT enlastname as suggestion, 'enlastname_no_space' as field_matched, 16 as priority
            FROM people WHERE REPLACE(enlastname, ' ', '') ILIKE ?
            LIMIT ?
        ) as suggestions
        GROUP BY suggestion, field_matched, priority
        ORDER BY priority
        LIMIT ?
    ";

    $params = [];

    // Add parameters for each of the 16 subqueries (each has 2 parameters: LIKE term and LIMIT)
    for ($i = 0; $i < 16; $i++) {
        if ($i < 4) {
            // First 4 subqueries: regular full name matches
            $params[] = $likeTerm;
        } elseif ($i < 8) {
            // Next 4 subqueries: space-less matches
            $params[] = $noSpaceLikeTerm;
        } elseif ($i < 12) {
            // Next 4 subqueries: individual field exact matches
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
                p.gender,
                p.dob,
                p.email,
                p.mobile_phone,

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
                    CASE WHEN p.enlastname ILIKE ? THEN 50 ELSE 0 END
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
                            ('enlastname', p.enlastname ILIKE ?)
                    ) AS matches(field_name, is_match)
                    WHERE is_match = true
                ) as matched_fields_info

            FROM people p
            WHERE
                -- Regular matches
                p.firstname ILIKE ? OR
                p.lastname ILIKE ? OR
                p.enfirstname ILIKE ? OR
                p.enlastname ILIKE ? OR
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
            // For score calculation (12 parameters)
            array_fill(0, 4, $likeTerm),        // Regular full name matches (4)
            array_fill(0, 4, $noSpaceTerm),     // Space-less full name matches (4)
            array_fill(0, 4, $likeTerm),        // Individual field matches (4)

            // For matched fields info (12 parameters)
            array_fill(0, 8, $likeTerm),        // Regular matches (8)
            array_fill(0, 4, $noSpaceTerm),     // Space-less matches (4)

            // For WHERE clause (12 parameters)
            array_fill(0, 8, $likeTerm),        // Regular matches (8)
            array_fill(0, 4, $noSpaceTerm),     // Space-less matches (4)

            [$limit]
        ));

        // Process results
        return collect($results)->map(function($result) use( $query , $highlightStart , $highlightEnd ){
            $matchedFields = json_decode($result->matched_fields_info, true);
            $result->matched_fields = array_column($matchedFields, 'field_name');
            $result->matched_fields_info = null;

            // Add highlight for the matched term in names
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
                }
            }

            return $result;
        });
    }

    /**
     * Search with normalization (handles spaces, special characters)
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
                -- Normalize fields for comparison
                REPLACE(REPLACE(REPLACE(
                    REPLACE(CONCAT(p.firstname, p.lastname), ' ', ''),
                    '្', ''), '​', ''), '‍', '') as normalized_khmer,
                REPLACE(REPLACE(REPLACE(
                    REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', ''),
                    '-', ''), '.', ''), ". "'" .", '') as normalized_latin,

                -- Calculate match score
                (
                    CASE WHEN REPLACE(REPLACE(REPLACE(
                        REPLACE(CONCAT(p.firstname, p.lastname), ' ', ''),
                        '្', ''), '​', ''), '‍', '') ILIKE ? THEN 100 ELSE 0 END +
                    CASE WHEN REPLACE(REPLACE(REPLACE(
                        REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', ''),
                        '-', ''), '.', ''), ". "'" .", '') ILIKE ? THEN 90 ELSE 0 END
                ) as relevance_score

            FROM people p
            WHERE
                REPLACE(REPLACE(REPLACE(
                    REPLACE(CONCAT(p.firstname, p.lastname), ' ', ''),
                    '្', ''), '​', ''), '‍', '') ILIKE ? OR
                REPLACE(REPLACE(REPLACE(
                    REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', ''),
                    '-', ''), '.', ''), ". "'" .", '') ILIKE ?
            ORDER BY relevance_score DESC
            LIMIT ?
        ", [
            "%{$normalizedQuery}%",
            "%{$normalizedQuery}%",
            "%{$normalizedQuery}%",
            "%{$normalizedQuery}%",
            $limit
        ]);

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
