<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Model;
use App\Models\Meeting\Meeting;
use App\Models\Meeting\MeetingMember;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class People extends Model
{

  use  SoftDeletes;
  protected $table = 'people';

   /*
  |--------------------------------------------------------------------------
  | GLOBAL VARIABLES
  |--------------------------------------------------------------------------
  */

  //protected $table = 'document_users';
  protected $primaryKey = 'id';
  public $timestamps = true;
  protected $guarded = ['id'];
  // protected $fillable = ['firstname', 'lastname', 'gender', 'dob', 'mobile_phone', 'office_phone', 'email', 'nid', 'father', 'mother', 'image', 'marry_status'];
  protected $hidden = ['deleted_at', 'created_by', 'updated_by', 'deleted_by'];
  // protected $dates = [];

  /*
  |--------------------------------------------------------------------------
  | FUNCTIONS
  |--------------------------------------------------------------------------
  */

  /*
  |--------------------------------------------------------------------------
  | RELATIONS
  |--------------------------------------------------------------------------
  */
    public function languages(){
        return $this->hasMany( \App\Models\People\PeopleLanguage::class , 'people_id' , 'id' );
    }
  public function cards(){
    return $this->hasMany( \App\Models\People\Card::class , 'people_id' , 'id' );
  }
  public function officers(){
    return $this->hasMany( \App\Models\Officer\Officer::class , 'people_id' , 'id' );
  }
  public function users(){
    return $this->hasMany( \App\Models\User::class , 'people_id' , 'id' );
  }
  public function countesy(){
    return $this->belongsTo( \App\Models\People\Countesy::class , 'countesy_id' , 'id' );
  }
  public function position(){
    return $this->belongsTo( \App\Models\Position\Position::class , 'position_id' , 'id' );
  }
  public function organization(){
    return $this->belongsTo( \App\Models\Organization\Organization::class , 'organization_id' , 'id' );
  }
  public function certificates(){
    return $this->hasMany( \App\Models\People\Certificate::class , 'people_id' , 'id' );
  }
  public function certificatesHighSchool(){
    return $this->certificates()->where('certificate_group_id','<=' , 3 )->get()->map(function($record){
      $record->group;
      return $record;
    });
  }
  public function certificatesPostGraduated(){
    return $this->certificates()->where('certificate_group_id', '>' , 3 )->where('certificate_group_id','<=',8)->get()->map(function($record){
      $record->group;
      return $record;
    });
  }
  public function certificatesOthers(){
    return $this->certificates()->where('certificate_group_id', '>' , 8 )->get()->map(function($record){
      $record->group;
      return $record;
    });
  }
  public function nationalCards(){
    return $this->hasMany( \App\Models\People\People::class , 'people_id' , 'id' );
  }
  public function passports(){
    return $this->hasMany( \App\Models\People\Passport::class , 'people_id' , 'id' );
  }
  public function birthCertificates(){
    return $this->hasMany( \App\Models\People\BirthCertificate::class , 'people_id' , 'id' );
  }
  public function selfBirthCertificates(){
    return $this->birthCertificates()->where(function($q){
      $q->whereNull('wedding_certificate_id')->orWhere('wedding_certificate_id',0);
    })->whereNull('deleted_at');
  }
  //របៀបនេះវិញ
  // public function birthCertificates(){
  //   return $this->hasMany( \App\Models\People\BirthCertificate::class , 'people_id' , 'id' );
  // }

  public function weddingCertificates(){
    return $this->hasMany( \App\Models\People\WeddingCertificate::class , 'people_id' , 'id' );
  }
  public function fatherKids(){
    return $this->hasMany( \App\Models\People\People::class , 'father_id' , 'id' );
  }
  public function motherKids(){
    return $this->hasMany( \App\Models\People\People::class , 'mother_id' , 'id' );
  }
  public function kidFather(){
    return $this->belongsTo( \App\Models\People\People::class , 'father_id' , 'id' );
  }
  public function kidMother(){
    return $this->blongsTo( \App\Models\People\People::class , 'mother_id' , 'id' );
  }
  public function addressProvince(){
    return $this->belongsTo( \App\Models\Location\Province::class , 'address_province_id' , 'id' );
  }
  public function addressDistrict(){
    return $this->belongsTo( \App\Models\Location\District::class , 'address_district_id' , 'id' );
  }
  public function addressCommune(){
    return $this->belongsTo( \App\Models\Location\Commune::class , 'address_commune_id' , 'id' );
  }
  public function addressVillage(){
    return $this->belongsTo( \App\Models\Location\Village::class , 'address_village_id' , 'id' );
  }
  public function currentAddressProvince(){
    return $this->belongsTo( \App\Models\Location\Province::class , 'current_address_province_id' , 'id' );
  }
  public function currentAddressDistrict(){
    return $this->belongsTo( \App\Models\Location\District::class , 'current_address_district_id' , 'id' );
  }
  public function currentAddressCommune(){
    return $this->belongsTo( \App\Models\Location\Commune::class , 'current_address_commune_id' , 'id' );
  }
  public function currentAddressVillage(){
    return $this->belongsTo( \App\Models\Location\Village::class , 'current_address_village_id' , 'id' );
  }
  public function pobProvince(){
    return $this->belongsTo( \App\Models\Location\Province::class , 'pob_province_id' , 'id' );
  }
  public function pobDistrict(){
    return $this->belongsTo( \App\Models\Location\District::class , 'pob_district_id' , 'id' );
  }
  public function pobCommune(){
    return $this->belongsTo( \App\Models\Location\Commune::class , 'pob_commune_id' , 'id' );
  }
  public function pobVillage(){
    return $this->belongsTo( \App\Models\Location\Village::class , 'pob_village_id' , 'id' );
  }
  public function emergencyProvince(){
    return $this->belongsTo( \App\Models\Location\Province::class , 'emergency_address_province_id' , 'id' );
  }
  public function emergencyDistrict(){
    return $this->belongsTo( \App\Models\Location\District::class , 'emergency_address_district_id' , 'id' );
  }
  public function emergencyCommune(){
    return $this->belongsTo( \App\Models\Location\Commune::class , 'emergency_address_commune_id' , 'id' );
  }
  public function emergencyVillage(){
    return $this->belongsTo( \App\Models\Location\Village::class , 'emergency_address_village_id' , 'id' );
  }
  /*
  |--------------------------------------------------------------------------
  | SCOPES
  |--------------------------------------------------------------------------
  */

  /*
  |--------------------------------------------------------------------------
  | ACCESORS
  |--------------------------------------------------------------------------
  */

  /*
  |--------------------------------------------------------------------------
  | MUTATORS
  |--------------------------------------------------------------------------
  */
  /*
  |----------
  | FUNCTIONS
  |----------
  */

  /**
   * Reports
   */
  // Age group statistics method
public static function getAgeGroupStatistics()
    {
        return DB::select("
            WITH age_categories AS (
                SELECT
                    CASE
                        WHEN AGE(CURRENT_DATE, dob) < INTERVAL '18 years' THEN 'Under 18'
                        WHEN AGE(CURRENT_DATE, dob) BETWEEN INTERVAL '18 years' AND INTERVAL '30 years' THEN '18-30'
                        WHEN AGE(CURRENT_DATE, dob) BETWEEN INTERVAL '31 years' AND INTERVAL '40 years' THEN '31-40'
                        WHEN AGE(CURRENT_DATE, dob) BETWEEN INTERVAL '41 years' AND INTERVAL '50 years' THEN '41-50'
                        WHEN AGE(CURRENT_DATE, dob) BETWEEN INTERVAL '51 years' AND INTERVAL '60 years' THEN '51-60'
                        ELSE 'Over 60'
                    END AS age_group
                FROM people
                WHERE dob IS NOT NULL
            )
            SELECT
                age_group,
                COUNT(*) as count,
                ROUND(COUNT(*) * 100.0 / SUM(COUNT(*)) OVER(), 2) as percentage
            FROM age_categories
            GROUP BY age_group
            ORDER BY
                CASE age_group
                    WHEN 'Under 18' THEN 1
                    WHEN '18-30' THEN 2
                    WHEN '31-40' THEN 3
                    WHEN '41-50' THEN 4
                    WHEN '51-60' THEN 5
                    ELSE 6
                END
        ");
    }

    // Get age distribution with exact ages
    public static function getAgeDistribution()
    {
        return DB::table('people')
            ->select(DB::raw("
                EXTRACT(YEAR FROM AGE(CURRENT_DATE, dob)) as age,
                COUNT(*) as count
            "))
            ->whereNotNull('dob')
            ->groupBy('age')
            ->orderBy('age')
            ->get();
    }

    // Get summary statistics
    public static function getAgeStatistics()
    {
        return DB::select("
            SELECT
                COUNT(*) as total_people_with_dob,
                COUNT(CASE WHEN dob IS NULL THEN 1 END) as missing_dob,
                MIN(dob) as earliest_birth,
                MAX(dob) as latest_birth,
                ROUND(AVG(EXTRACT(YEAR FROM AGE(CURRENT_DATE, dob))), 1) as average_age,
                PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY EXTRACT(YEAR FROM AGE(CURRENT_DATE, dob))) as median_age
            FROM people
        ");
    }
    /**
     * Full Text Search
     */
    /**
     * Advanced search across name fields with full name support
     */
    public static function advancedSearch($searchTerm, $limit = 20)
    {
        if (empty($searchTerm)) {
            return self::limit($limit)->get();
        }

        $searchTerm = trim($searchTerm);

        // Split search term into parts for full name search
        $nameParts = preg_split('/\s+/', $searchTerm);
        $hasMultipleParts = count($nameParts) >= 2;

        return self::where(function($query) use ($searchTerm, $nameParts, $hasMultipleParts) {
            // Method 1: Full-text search on search_vector
            $query->orWhereRaw("search_vector @@ to_tsquery('simple', ?)", [
                $this->formatSearchQuery($searchTerm)
            ]);

            // Method 2: Exact matches with concatenated full name (Khmer)
            $query->orWhere(DB::raw("CONCAT(firstname, ' ', lastname)"), 'ILIKE', "%{$searchTerm}%");
            $query->orWhere(DB::raw("CONCAT(lastname, ' ', firstname)"), 'ILIKE', "%{$searchTerm}%");

            // Method 3: Exact matches with concatenated full name (Latin)
            $query->orWhere(DB::raw("CONCAT(enfirstname, ' ', enlastname)"), 'ILIKE', "%{$searchTerm}%");
            $query->orWhere(DB::raw("CONCAT(enlastname, ' ', enfirstname)"), 'ILIKE', "%{$searchTerm}%");

            // Method 4: Individual field matches
            $query->orWhere('firstname', 'ILIKE', "%{$searchTerm}%");
            $query->orWhere('lastname', 'ILIKE', "%{$searchTerm}%");
            $query->orWhere('enfirstname', 'ILIKE', "%{$searchTerm}%");
            $query->orWhere('enlastname', 'ILIKE', "%{$searchTerm}%");
            $query->orWhere('namekh', 'ILIKE', "%{$searchTerm}%");
            $query->orWhere('nameen', 'ILIKE', "%{$searchTerm}%");

            // Method 5: For multiple name parts (first + last name search)
            if ($hasMultipleParts) {
                $query->orWhere(function($q) use ($nameParts) {
                    $q->where('firstname', 'ILIKE', "%{$nameParts[0]}%")
                      ->where('lastname', 'ILIKE', "%{$nameParts[1]}%");
                });

                $query->orWhere(function($q) use ($nameParts) {
                    $q->where('enfirstname', 'ILIKE', "%{$nameParts[0]}%")
                      ->where('enlastname', 'ILIKE', "%{$nameParts[1]}%");
                });
            }
        })
        ->orderByRaw("
            CASE
                WHEN CONCAT(firstname, ' ', lastname) ILIKE ? THEN 1
                WHEN CONCAT(enfirstname, ' ', enlastname) ILIKE ? THEN 2
                WHEN search_vector @@ to_tsquery('simple', ?) THEN 3
                ELSE 4
            END",
            [$searchTerm, $searchTerm, self::formatSearchQuery($searchTerm)]
        )
        ->limit($limit)
        ->get();
    }

    /**
     * Format search term for tsquery
     */
    private static function formatSearchQuery($term)
    {
        // Split into words and add :* for prefix matching
        $words = preg_split('/\s+/', $term);
        $formatted = [];

        foreach ($words as $word) {
            if (strlen($word) > 2) {
                $formatted[] = $word . ':*';
            } else {
                $formatted[] = $word;
            }
        }

        return implode(' & ', $formatted);
    }


    /**
     * Simple search with match information (supports space-less)
     */
    public static function searchWithMatches($searchTerm, $limit = 20)
    {
        $likeTerm = "%{$searchTerm}%";
        $noSpaceTerm = str_replace(' ', '', $searchTerm);
        $noSpaceLikeTerm = "%{$noSpaceTerm}%";

        $results = DB::select("
            SELECT
                p.*,
                -- Calculate match score
                (
                    -- Regular full name matches
                    CASE WHEN CONCAT(p.firstname, ' ', p.lastname) ILIKE ? THEN 100 ELSE 0 END +
                    CASE WHEN CONCAT(p.lastname, ' ', p.firstname) ILIKE ? THEN 100 ELSE 0 END +
                    CASE WHEN CONCAT(p.enfirstname, ' ', p.enlastname) ILIKE ? THEN 90 ELSE 0 END +
                    CASE WHEN CONCAT(p.enlastname, ' ', p.enfirstname) ILIKE ? THEN 90 ELSE 0 END +

                    -- Space-less full name matches
                    CASE WHEN REPLACE(CONCAT(p.firstname, p.lastname), ' ', '') ILIKE ? THEN 85 ELSE 0 END +
                    CASE WHEN REPLACE(CONCAT(p.lastname, p.firstname), ' ', '') ILIKE ? THEN 85 ELSE 0 END +
                    CASE WHEN REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', '') ILIKE ? THEN 75 ELSE 0 END +
                    CASE WHEN REPLACE(CONCAT(p.enlastname, p.enfirstname), ' ', '') ILIKE ? THEN 75 ELSE 0 END +

                    -- Individual field matches
                    CASE WHEN p.firstname ILIKE ? THEN 60 ELSE 0 END +
                    CASE WHEN p.lastname ILIKE ? THEN 60 ELSE 0 END +
                    CASE WHEN p.enfirstname ILIKE ? THEN 50 ELSE 0 END +
                    CASE WHEN p.enlastname ILIKE ? THEN 50 ELSE 0 END
                ) as match_score,

                -- Match flags as array
                ARRAY[
                    CASE WHEN CONCAT(p.firstname, ' ', p.lastname) ILIKE ? THEN 'khmer_full_name' END,
                    CASE WHEN CONCAT(p.lastname, ' ', p.firstname) ILIKE ? THEN 'khmer_full_name_reverse' END,
                    CASE WHEN CONCAT(p.enfirstname, ' ', p.enlastname) ILIKE ? THEN 'latin_full_name' END,
                    CASE WHEN CONCAT(p.enlastname, ' ', p.enfirstname) ILIKE ? THEN 'latin_full_name_reverse' END,
                    CASE WHEN REPLACE(CONCAT(p.firstname, p.lastname), ' ', '') ILIKE ? THEN 'khmer_combined_no_space' END,
                    CASE WHEN REPLACE(CONCAT(p.lastname, p.firstname), ' ', '') ILIKE ? THEN 'khmer_reverse_no_space' END,
                    CASE WHEN REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', '') ILIKE ? THEN 'latin_combined_no_space' END,
                    CASE WHEN REPLACE(CONCAT(p.enlastname, p.enfirstname), ' ', '') ILIKE ? THEN 'latin_reverse_no_space' END,
                    CASE WHEN p.firstname ILIKE ? THEN 'firstname' END,
                    CASE WHEN p.lastname ILIKE ? THEN 'lastname' END,
                    CASE WHEN p.enfirstname ILIKE ? THEN 'enfirstname' END,
                    CASE WHEN p.enlastname ILIKE ? THEN 'enlastname' END
                ] as matched_fields,

                -- Matched values as array
                ARRAY[
                    CASE WHEN CONCAT(p.firstname, ' ', p.lastname) ILIKE ? THEN CONCAT(p.firstname, ' ', p.lastname) END,
                    CASE WHEN CONCAT(p.lastname, ' ', p.firstname) ILIKE ? THEN CONCAT(p.lastname, ' ', p.firstname) END,
                    CASE WHEN CONCAT(p.enfirstname, ' ', p.enlastname) ILIKE ? THEN CONCAT(p.enfirstname, ' ', p.enlastname) END,
                    CASE WHEN CONCAT(p.enlastname, ' ', p.enfirstname) ILIKE ? THEN CONCAT(p.enlastname, ' ', p.enfirstname) END,
                    CASE WHEN REPLACE(CONCAT(p.firstname, p.lastname), ' ', '') ILIKE ? THEN REPLACE(CONCAT(p.firstname, p.lastname), ' ', '') END,
                    CASE WHEN REPLACE(CONCAT(p.lastname, p.firstname), ' ', '') ILIKE ? THEN REPLACE(CONCAT(p.lastname, p.firstname), ' ', '') END,
                    CASE WHEN REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', '') ILIKE ? THEN REPLACE(CONCAT(p.enfirstname, p.enlastname), ' ', '') END,
                    CASE WHEN REPLACE(CONCAT(p.enlastname, p.enfirstname), ' ', '') ILIKE ? THEN REPLACE(CONCAT(p.enlastname, p.enfirstname), ' ', '') END,
                    CASE WHEN p.firstname ILIKE ? THEN p.firstname END,
                    CASE WHEN p.lastname ILIKE ? THEN p.lastname END,
                    CASE WHEN p.enfirstname ILIKE ? THEN p.enfirstname END,
                    CASE WHEN p.enlastname ILIKE ? THEN p.enlastname END
                ] as matched_values

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
            ORDER BY match_score DESC
            LIMIT ?
        ", array_merge(
            // For match_score calculation (12 parameters)
            array_fill(0, 4, $likeTerm),        // Regular full name (4)
            array_fill(0, 4, $noSpaceLikeTerm), // Space-less full name (4)
            array_fill(0, 4, $likeTerm),        // Individual fields (4)

            // For matched_fields (12 parameters)
            array_fill(0, 4, $likeTerm),        // Regular full name (4)
            array_fill(0, 4, $noSpaceLikeTerm), // Space-less full name (4)
            array_fill(0, 4, $likeTerm),        // Individual fields (4)

            // For matched_values (12 parameters)
            array_fill(0, 4, $likeTerm),        // Regular full name (4)
            array_fill(0, 4, $noSpaceLikeTerm), // Space-less full name (4)
            array_fill(0, 4, $likeTerm),        // Individual fields (4)

            // For WHERE clause (12 parameters)
            array_fill(0, 8, $likeTerm),        // Regular matches (8)
            array_fill(0, 4, $noSpaceLikeTerm), // Space-less matches (4)

            [$limit]
        ));

        // Clean up results
        return collect($results)->map(function($result) {
            $result->matched_fields = array_values(array_filter(
                $this->parsePostgresArray($result->matched_fields)
            ));
            $result->matched_values = array_values(array_filter(
                $this->parsePostgresArray($result->matched_values)
            ));
            return $result;
        });
    }

    /**
     * Parse PostgreSQL array format to PHP array
     */
    private static function parsePostgresArray($arrayString)
    {
        if (empty($arrayString) || $arrayString === '{}' || $arrayString === '{NULL}') {
            return [];
        }

        $arrayString = trim($arrayString, '{}');
        $arrayString = str_replace('NULL', '', $arrayString);

        if (empty($arrayString)) {
            return [];
        }

        $items = explode(',', $arrayString);
        return array_map(function($item) {
            return trim($item, '"');
        }, $items);
    }

}
