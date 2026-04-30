<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers\Api\AuthenticationCenter\Officer;
use App\Services\PeopleSearchService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CrudController;
use App\Models\Officer\Officer as RecordModel ;
use Illuminate\Http\File;


class OfficerContractedController extends Controller
{
    private $selectFields = [
        'id',
        'public_key' ,
        'code' ,
        'people_id' ,
        'official_date' ,
        'unofficial_date' ,
        'image' ,
        'leader' ,
        'organization_id' ,
        'position_id' ,
        'rank_id' ,
        'user_id' ,
        'countesy_id' ,
        'email' ,
        'phone' ,
        'passport' ,
        'salary_rank' ,
        'officer_type' ,
        'additional_officer_type'
    ];

    protected $searchService;

    public function __construct(PeopleSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Listing function
     */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && strlen( $request->search ) > 0
            ? str_replace( [ '​' ] , '' , $request->search )
            : false ;
        $perPage = isset( $request->perPage ) && intval( $request->perPage ) > 0 ? $request->perPage : 10 ;
        $page = isset( $request->page ) && intval( $request->page ) > 0 ? $request->page : 1 ;

        $gender = isset( $request->gender ) && ( $request->gender == 0 || $request->gender == 1 ) ? $request->gender : false ;

        // Clean the search string with hidden_space of Khmer character


        $isWildSearch = isset( $request->wild_search ) && intval( $request->wild_search ) > 0 ? intval( $request->wild_search ) : 0 ;

        $positions = isset( $request->positions ) ? explode(',',$request->positions) : false ;
        if( is_array( $positions ) && !empty( $positions ) ){
            $positions = array_filter( $positions, function($position){
                return intval( $position ) > 0 ;
            } );
        }

        $unofficialPositions = isset( $request->unofficial_position_ids ) ? explode(',',$request->unofficial_position_ids) : false ;
        if( is_array( $unofficialPositions ) && !empty( $unofficialPositions ) ){
            $unofficialPositions = array_filter( $unofficialPositions, function($unofficialPosition){
                return intval( $unofficialPosition ) > 0 ;
            } );
        }

        $officerTypes = isset( $request->officer_types ) ? explode(',',$request->officer_types) : false ;
        if( is_array( $officerTypes ) && !empty( $officerTypes ) ){
            $officerTypes = array_filter( $officerTypes, function($officerType){
                return strlen( $officerType ) > 0 ;
            } );
        }

        $organizations = isset( $request->organizations ) ? explode(',',$request->organizations) : false ;
        if( is_array( $organizations ) && !empty( $organizations ) ){
            $organizations = array_filter( $organizations , function($organization){
                return intval( $organization ) > 0 ;
            } );
        }

        $officerIds = isset( $request->ids ) ? explode(',',$request->ids) : false ;
        if( is_array( $officerIds ) && !empty( $officerIds ) ){
            $officerIds = array_filter( $officerIds , function($officerIds){
                return intval( $officerIds ) > 0 ;
            } );
        }

        $educationLevels = isset( $request->education_levels ) ? explode(',',$request->education_levels) : false ;
        if( is_array( $educationLevels ) && !empty( $educationLevels ) ){
            $educationLevels = array_filter( $educationLevels , function($educationLevel){
                return intval( $educationLevel ) > 0 ;
            } );
        }

        $ranks = isset( $request->rank_ids ) ? explode(',',$request->rank_ids) : false ;
        if( is_array( $ranks ) && !empty( $ranks ) ){
            $ranks = array_filter( $ranks , function($rank){
                return intval( $rank ) > 0 ;
            } );
        }

        $dob = isset( $request->dob ) && strlen( $request->dob ) > 0 ? $request->dob : false ;
        $unofficial_date = isset( $request->unofficial_date ) && strlen( $request->unofficial_date ) > 0 ? $request->unofficial_date : false ;
        $official_date = isset( $request->official_date ) && strlen( $request->official_date ) > 0 ? $request->official_date : false ;

        $searchFields = isset($request->search_fields) && strlen(trim($request->search_fields)) > 0 && !empty(explode($request->search_fields, ',')) ? explode($request->search_fields, ',') : [];
        // Filter the searchFields base on pivot table's fields
        $pivotDefaultSearchFields = [
            // This four fields has already implemented full text search
            // 'firstname', 'lastname', 'enfirstname', 'enlastname',
            'nid'
            // , 'dob' , 'mobile_phone' , 'office_phone' , 'email' , 'passport' , 'address'
        ];
        $pivotSearchFields = array_filter( $searchFields ,function ($field) {
            return in_array($field, [
                // 'firstname' , 'lastname' , 'enfirstname' , 'enlastname' ,
                'nid'
                // , 'dob' , 'mobile_phone' , 'office_phone' , 'email' , 'passport' , 'address'
            ] );
        });
        // Filter the searchFields to make sure it does exists within the table
        $searchFields = array_filter( $searchFields , function ($field) {
            return in_array($field, $this->selectFields);
        });

        $searchResults = $this->searchService->search(
            $search , $perPage
        )->pluck('id')->toArray();

        $queryString = [
            "where" => [
                // 'default' => [
                //     $official_date != false
                //         ?[
                //             'field' => 'dob' ,
                //             'value' => $type === false ? "" : $type
                //         ]
                //         : []
                // ],
                'in' => [
                    // Filer by officer ids
                    is_array( $officerIds ) && !empty( $officerIds )
                        ?   [
                            'field' => 'id' ,
                            'value' => $officerIds
                        ]
                        : []
                ] ,
            //     'not' => [] ,
                'like' => [
                    $official_date != false
                    ?[
                        'field' => 'official_date' ,
                        'value' => $official_date
                    ]
                    : []
                ] ,
            ] ,
            "pivots" => [
                // is_array( $organizations ) && !empty( $organizations ) ?
                // [
                //     "relationship" => 'organization',
                //     "where" => [
                //         "in" => [
                //             "field" => "organization_id",
                //             "value" => $organizations
                //         ]
                //     ]
                // ]
                // : [] ,
                // is_array( $positions ) && !empty( $positions ) ?
                // [
                //     "relationship" => 'position',
                //     "where" => [
                //         "in" => [
                //             "field" => "position_id",
                //             "value" => $positions
                //         ]
                //     ]
                // ]
                // : [] ,
                strlen( $search ) > 0 ?
                [
                    "relationship" => 'people',
                    "where" => [
                        "like" => [
                            "value" => $search ,
                            "fields" => !empty( $pivotSearchFields ) ? $pivotSearchFields : $pivotDefaultSearchFields
                        ]
                    ]
                ]
                : []
            ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" =>
            // $isWildSearch > 0
            //     ? (
                    $search === false
                        ? []
                        : [
                            'value' => $search ,
                            'fields' => !empty( $searchFields ) ? $searchFields : [
                                'code' ,
                                'official_date' ,
                                'unofficial_date'
                            ]
                        ]
                // )
                // : []
            ,
            "order" => [
                'field' => 'id' ,
                'by' => 'desc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields,[
            'image' => function( $officer ){
                return $officer['image'] != null && \Storage::disk('public')->exists( $officer['image'] )
                ? \Storage::disk('public')->url( $officer['image'] )
                : (
                    isset( $officer['user'] ) && $officer['user']['avatar_url'] != null && \Storage::disk('public')->exists( $officer['user']['avatar_url'] )
                    ? \Storage::disk('public')->url( $officer['user']['avatar_url'] )
                    : (
                        isset( $officer['people'] ) && $officer['people']['image'] != null && \Storage::disk('public')->exists( $officer['people']['image'] )
                        ? \Storage::disk('public')->url( $officer['people']['image'] )
                        : false
                    )
                );
            }
        ],false ,[
            'current_job' => function( $officer ){
                $officer = RecordModel::find( $officer['id'] ) ;
                $job = $officer == null ? null : $officer->getCurrentJob() ;
                if( $job != null && $job->organizationStructurePosition != null ){
                    $job->organizationStructurePosition->position;
                    if( $job->organizationStructurePosition->organizationStructure != null ){
                        $job->organizationStructurePosition->organizationStructure->organization;
                    }
                    $job->unofficialPosition;
                }
                if( $job != null ){
                    $job->unofficialPosition;
                }
                return $officer == null || $job == null ? null : $job ;
            }
        ]);
        $crud->setRelationshipFunctions([
            //     /** relationship name => [ array of fields name to be selected ] */
            //     "person" => ['id','firstname' , 'lastname' , 'gender' , 'dob' , 'pob' , 'picture' ] ,
            //     "roles" => ['id','name', 'tag'] ,
            'user' => [
                'id' , 'username' , 'phone' , 'email' , 'avatar_url' , 'firstname' , 'lastname' ,
                'roles' => [ 'id' , 'name' ]
            ] ,
            'rank' => [ 'id' , 'name' , 'ank' , 'krobkhan' , 'krobkhan_name' , 'rank' , 'thnak' , 'prefix' ] ,
            "people" => [
                'id','firstname' , 'lastname' , 'enfirstname' , 'enlastname' , 'gender' , 'dob' , 'pob' , 'image' , 'mobile_phone' , 'office_phone' , 'passport' , 'nid' , 'marry_status' , 'email' , 'nationality' , 'national' , 'death' , 'body_condition' , 'body_condition_desp' ,
                'address' ,
                'address_province_id' ,
                'address_district_id' ,
                'address_commune_id' ,
                'address_village_id' ,
                'addressProvince' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'addressDistrict' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'addressCommune' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'addressVillage' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'current_address' ,
                'current_address_province_id' ,
                'current_address_district_id' ,
                'current_address_commune_id' ,
                'current_address_village_id' ,
                'currentAddressProvince' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'currentAddressDistrict' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'currentAddressCommune' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'currentAddressVillage' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'pob_province_id' ,
                'pob_district_id' ,
                'pob_commune_id' ,
                'pob_village_id' ,
                'pobProvince' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'pobDistrict' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'pobCommune' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'pobVillage' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'emergency_lastname' ,
                'emergency_firstname' ,
                'emergency_gender' ,
                'emergency_profession' ,
                'emergency_relationship' ,
                'emergency_phone' ,
                'emergency_email' ,
                'emergency_address' ,
                'emergency_address_province_id' ,
                'emergency_address_district_id' ,
                'emergency_address_commune_id' ,
                'emergency_address_village_id' ,
                'emergencyProvince' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'emergencyDistrict' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'emergencyCommune' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'emergencyVillage' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                // father
                'father_firstname' ,
                'father_lastname' ,
                'father_enfirstname' ,
                'father_enlastname' ,
                'father_dob' ,
                'father_nationality' ,
                'father_national' ,
                'father_pob' ,
                'father_address' ,
                'father_address_province_id' ,
                'father_address_district_id' ,
                'father_address_commune_id' ,
                'father_address_village_id' ,
                'father_death' ,
                'father_profession' ,
                'father_nid' ,

                // mother
                'mother_firstname' ,
                'mother_lastname'  ,
                'mother_enfirstname' ,
                'mother_enlastname' ,
                'mother_dob' ,
                'mother_nationality' ,
                'mother_national' ,
                'mother_pob' ,
                'mother_address' ,
                'mother_address_province_id' ,
                'mother_address_district_id' ,
                'mother_address_commune_id' ,
                'mother_address_village_id' ,
                'mother_death' ,
                'mother_profession' ,
                'mother_nid' ,

                'weddingCertificates' => [
                    'id' ,
                    'wedding_number' ,
                    'book_number' ,
                    'year' ,
                    'province_id' ,
                    'district_id' ,
                    'commune_id' ,
                    'issued_date' ,
                    'issued_location' ,
                    'signed_name' ,
                    'pdf' ,
                    'spouse_death' ,
                    // Spouse
                    'spouse_id' ,
                    'spouse_firstname',
                    'spouse_lastname',
                    'spouse_enfirstname' ,
                    'spouse_enlastname' ,
                    'spouse_national' ,
                    'spouse_nationality' ,
                    'spouse_dob' ,
                    'spouse_profession' ,
                    'spouse_profession_organization' ,
                    'spouse_pob' ,
                    'spouse_address' ,

                    // Father information
                    'spouse_father_firstname' ,
                    'spouse_father_lastname' ,
                    'spouse_father_enfirstname' ,
                    'spouse_father_enlastname' ,
                    'spouse_father_dob' ,
                    'spouse_father_nationality' ,
                    'spouse_father_national' ,
                    'spouse_father_pob' ,
                    'spouse_father_address' ,
                    'spouse_father_profession' ,
                    'spouse_father_picture' ,
                    'spouse_father_death' ,

                    // Mother information
                    'spouse_mother_firstname' ,
                    'spouse_mother_lastname' ,
                    'spouse_mother_enfirstname' ,
                    'spouse_mother_enlastname' ,
                    'spouse_mother_dob' ,
                    'spouse_mother_nationality' ,
                    'spouse_mother_national' ,
                    'spouse_mother_pob' ,
                    'spouse_mother_address' ,
                    'spouse_mother_profession' ,
                    'spouse_mother_picture' ,
                    'spouse_mother_death'
                ],
                'certificates' => [ 'id' , 'field_name' , 'start' , 'end' , 'place_name' , 'certificate_note' , 'certificate_group_id' ]
            ],
            // 'position' => [ 'id' , 'name' , 'desp' , 'prefix' ] ,
            // 'organization' => [ 'id' , 'name' , 'desp' , 'prefix' ] ,
            'countesy' => [ 'id' , 'name' , 'desp' , 'prefix' ] ,
            'jobs' => [ 'id' , 'organization_structure_position_id' , 'officer_id' ,'countesy_id' , 'start' , 'end' ,
                'unoficialPosition' => [ 'id' , 'name' , 'desp' , 'prefix'] ,
                'organizationStructurePosition' => [
                    'id' , 'name' , 'pid' , 'tpid' , 'cids' , 'image' , 'organization_structure_id' , 'position_id' , 'job_desp' ,
                    'position' => [ 'id' , 'name' , 'desp' , 'prefix' ] ,
                    'organizationStructure' => [
                        'id' , 'organization_id' , 'pid' , 'name' , 'tpid' , 'cids' , 'desp' , 'active'
                        , 'organization' => [ 'id' , 'name' , 'desp' , 'prefix' ]
                    ]
                ]
            ],
            'card' => [
                'id' ,
                'uuid',
                'number',
                'people_id',
                'officer_id',
                'start',
                'end',
                'active',
                'author' => [ 'id' , 'firstname' , 'lastname' ],
                'editor' => [ 'id' , 'firstname' , 'lastname' ]
            ]
        ]); 

        $builder = $crud->getListBuilder()->whereNull('deleted_at');
        // --------start: filter for contracted officers only--------
        // Filter to get only officers with contracted_officer type
        $builder->where('additional_officer_type', 'contracted_officer');
        // --------end: filter for contracted officers only--------

        if( !empty( $searchResults ) ){
            $builder->orWhereIn('people_id', $searchResults);
        }

        if( $dob != false ){
            $builder->whereHas('people', function ($q) use ($dob) {
                $q->where('dob', 'like', '%' . $dob . '%');
            });
        }

        if( $gender !== false ){
            $builder->whereHas('people', function ($q) use ($gender) {
                $q->where('gender', $gender );
            });
        }

        if( !empty( $educationLevels ) ){
            $builder->whereHas('people', function ($q) use ( $educationLevels ){
                $q->whereHas('certificates', function ($q) use ( $educationLevels ){
                    $q->whereIn('certificate_group_id', $educationLevels);
                });
            });
        }

        if( !empty( $officerTypes ) ){
            $builder->whereIn('additional_officer_type', $officerTypes);
        }


        if( !empty( $ranks ) ){
            $builder->whereHas('rank', function ($q) use ( $ranks ){
                $q->whereIn('id', $ranks );
            });
        }

        if (
            !empty($organizations) ||
            !empty($positions) ||
            !empty($unofficialPositions)
        ) {
            $builder->whereHas('jobs', function ($jobQuery) use ($organizations, $positions, $unofficialPositions) {
                $jobQuery->whereHas('organizationStructurePosition', function ($positionQuery) use ($organizations, $positions) {
                    if (is_array($positions) && !empty($positions)) {
                        $positionQuery->whereIn('position_id', $positions);
                    }
                    if (is_array($organizations) && !empty($organizations)) {
                        $positionQuery->whereHas('organizationStructure', function ($query) use ($organizations) {
                            $query->whereIn('organization_id', $organizations);
                        });
                    }
                });
                if (!empty($unofficialPositions)) {
                    $jobQuery->whereHas('unofficialPosition', function ($q) use ($unofficialPositions) {
                        $q->whereIn('unofficial_position_id', $unofficialPositions);
                    });
                }
            });
        }

        /**
         * Filter the officers to get only the officer that is not admin and super admin
         */
        $builder->whereHas('user',function($query){
            $query->whereHas('roles',function($query){
                $query->whereNot('name',['super','admin']);
            });
        });

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        $responseData['fts'] = [
            'search_results' => $searchResults ,
            // 'succestions' => $suggestions ,
            // 'fuzzySearch' => $fuzzySearch ,
            // 'searchWithHighlight' => $searchWithHighlight ,
            // 'combinedSearch' => $combinedSearch
        ];
        return response()->json($responseData, 200);
    }

    public function getOfficerContractedByIds(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់គណនី។'
            ],422);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ស្វែងរកគណនីមិនឃើញឡើយ។'
            ],403);
        }

        $record->user;
        $record->card;
        $record->countesy;
        $record->position;
        $record->organization;
        $record->people;

        if( $record->people != null ){
            $record->people->wedding_certificates = $record->people->weddingCertificates->map(function( $weddingCertificate ){
                $weddingCertificate->birthCertificates;
                return $weddingCertificate;
            }) ;
            $record->people->passports;

            $certificates['first'] = $record->people->certificatesHighSchool();
            $certificates['middle'] = $record->people->certificatesPostGraduated();
            $certificates['others'] = $record->people->certificatesOthers();
            $record->people->birthCertificates;
            $record->people->certificates = $certificates ;
            $record->people->languages;
            $record->jobBackgrounds;
            $record->officer_ranks = $record->officerRanks->map(function($officerRank){
                $officerRank->rank;
                $officerRank->countesy;
                return $officerRank;
            });
            $record->krob_khans = $record->officer_ranks;
            $record->ranking_by_certificates = $record->rankingByCertificates->map(function($rank){
                $rank->rank;
                $rank->previousRank;
                return $rank;
            });
            $record->ranking_by_workings = $record->rankingByWorkings->map(function($rank){
                $rank->rank;
                $rank->previousRank;
                return $rank;
            });
            $record->rankingByWorkings;
            $record->pendingWorks;
            $record->paneltyHistories;
            $record->medalHistories;

        }

        $record->job = $record->getCurrentJob();
        if( $record->job != null && $record->job->organizationStructurePosition != null ){
            $record->job->organizationStructurePosition->position;
            $record->job->organizationStructurePosition->permissions;
            if( $record->job->organizationStructurePosition->organizationStructure != null ){
                $record->job->organizationStructurePosition->organizationStructure->organization;
            }
        }
        $record->image = $record->image != null && trim($record->image ) != "" && \Storage::disk('public')->exists( $record->image )
            ? \Storage::disk('public')->url( $record->image )
            : (
                $record->user != null && $record->user->avatar_url != null && trim($record->user->avatar_url) != "" && \Storage::disk('public')->exists( $record->user->avatar_url )
                ? \Storage::disk('public')->url( $record->user->avatar_url )
                : false
            );

        return response()->json([
            'record' => $record ,
            'ok' => true ,
            'message' => 'រួចរាល់'
        ],200);
    }

        /**
     * Create an account
     */
    public function storeContractedOfficer(Request $request){
        $isDraftCreate = filter_var($request->draft, FILTER_VALIDATE_BOOLEAN) || intval($request->draft) > 0;
        $request->merge([
            'firstname' => trim((string) ($request->firstname ?? '')),
            'lastname' => trim((string) ($request->lastname ?? '')),
            'enfirstname' => trim((string) ($request->enfirstname ?? '')),
            'enlastname' => trim((string) ($request->enlastname ?? ''))
        ]);

        $validated = $request->validate([
            // 'code' => 'required|unique:officers|max:50',
            // 'nid' => 'required|unique:people|max:50' ,
            // 'organization_id' => 'required',
            // 'position_id' => 'required',
            'firstname' => $isDraftCreate ? 'nullable' : 'required' ,
            'lastname' => $isDraftCreate ? 'nullable' : 'required' ,
            'enfirstname' => $isDraftCreate ? 'nullable' : 'required' ,
            'enlastname' => $isDraftCreate ? 'nullable' : 'required'
        ]);


        // Check the ranking of the officer
        $ank = isset( $request->ank ) && strlen( $request->ank ) > 0 ? trim($request->ank) : false ;
        $krobkhan = isset( $request->krobkhan ) && strlen( $request->krobkhan ) > 0 ? trim($request->krobkhan) : false ;
        $rank = isset( $request->rank ) && strlen( $request->rank ) > 0 ? trim($request->rank) : false ;
        $thnak = isset( $request->thnak ) && strlen( $request->thnak ) > 0 ? trim($request->thnak) : false ;
        $rank_object = null ;
        if( $ank != false && $krobkhan != false && $rank != false && $thnak != false ){
            $rank_object = \App\Models\Officer\Rank::where([
                'ank' => $ank ,
                'krobkhan' => $krobkhan ,
                'rank' => $rank ,
                'thnak' => $thnak
            ])->first();
        }

        // Check whether the officer has been assigned a position yet
        $organizationStructurePosition = intval( $request->organization_structure_position_id ) > 0 ? \App\Models\Organization\OrganizationStructurePosition::find( $request->organization_structure_position_id ) : null ;
        $unOfficialPosition = intval( $request->unofficial_position_id ) > 0 ? \App\Models\Position\Position::find( $request->unofficial_position_id ) : null ;
        $position = $organizationStructurePosition != null && $organizationStructurePosition->position != null ? $organizationStructurePosition->position : null ;
        $organization = $organizationStructurePosition != null && $organizationStructurePosition->organizationStructure != null && $organizationStructurePosition->organizationStructure->organization != null ? $organizationStructurePosition->organizationStructure->organization : null ;
        $formattedDob = isset($request->dob) && strlen(trim((string) $request->dob)) > 0
            ? \Carbon\Carbon::parse($request->dob)->format('Y-m-d')
            : null;
        $email = isset($request->email) ? trim((string) $request->email) : '';
        $mobilePhone = isset($request->mobile_phone) ? trim((string) $request->mobile_phone) : '';

        $peopleWhereConditions = [
            'firstname' => $request->firstname ,
            'lastname' => $request->lastname ,
            'enfirstname' => $request->enfirstname ,
            'enlastname' => $request->enlastname ,
        ];
        $duplicateIdentityFieldCount = 0;
        if( isset($request->nid) && strlen(trim((string) $request->nid)) > 0 ){
            $peopleWhereConditions['nid'] = trim((string) $request->nid);
            $duplicateIdentityFieldCount++;
        }
        if( $formattedDob != null ){
            $peopleWhereConditions['dob'] = $formattedDob;
            $duplicateIdentityFieldCount++;
        }
        if( strlen( $mobilePhone ) > 0 ){
            $peopleWhereConditions['mobile_phone'] = $mobilePhone;
            $duplicateIdentityFieldCount++;
        }
        if( strlen( $email ) > 0 ){
            $peopleWhereConditions['email'] = $email;
            $duplicateIdentityFieldCount++;
        }
        $people = $duplicateIdentityFieldCount > 0
            ? \App\Models\People\People::where( $peopleWhereConditions )->first()
            : null;

        if( $people != null && $people->officer != null ){

            // អ្នកប្រើប្រាស់បានចុះឈ្មោះរួចរាល់ហើយ
            return response([
                    'message' => 'អ្នកកំពុងព្យាយាមបញ្ចូលព័ត៌មានដែលមានរួចហើយ ' . implode( " , " , [ ( $people->officer != null ? $people->officer->code : '' ) , $people->lastname , $people->firstname ] )
                ],
                500
            );
        }
        $user = \Auth::user() == null ? null : \Auth::user() ;
        /**
         * Create detail information of the owner of the account
         */
        if( $people == null ){
            $people = \App\Models\People\People::create([
                'public_key' => md5(
                    \Carbon\Carbon::now()->format('YmdHis') .
                    $request->enfirstname .
                    $request->enlastname .
                    $request->gender .
                    ($formattedDob ?? '') .
                    $request->nid .
                    $request->mobile_phone .
                    $request->office_phone
                ) ,

                'namekh' => $request->lastname.$request->firstname ,
                'namekhreverse' => $request->firstname.$request->lastname ,
                'nameen' => $request->enlastname.$request->enfirstname ,
                'nameenreverse' => $request->enfirstname.$request->enlastname ,

                'firstname' => $request->firstname ,
                'lastname' => $request->lastname ,
                'enfirstname' => $request->enfirstname ,
                'enlastname' => $request->enlastname ,
                'gender' => $request->gender ,
                'dob' => $formattedDob ,
                'nid' => $request->nid ,
                'nid_start_at' => isset($request->nid_start_at) && strlen(trim((string) $request->nid_start_at)) > 0
                    ? \Carbon\Carbon::parse($request->nid_start_at)->format('Y-m-d')
                    : null,
                'nid_expired_at' => isset($request->nid_expired_at) && strlen(trim((string) $request->nid_expired_at)) > 0
                    ? \Carbon\Carbon::parse($request->nid_expired_at)->format('Y-m-d')
                    : null,
                'marry_status' => $request->marry_status ?? 'single',
                'mobile_phone' => $mobilePhone ,
                'office_phone' => $request->office_phone ,
                'email' => $email ,
                'body_condition' => intval($request->body_condition ?? 0),
                'body_condition_desp' => $request->body_condition_desp ?? '' ,
                'nationality' => $request->nationality ?? '' ,
                'national' => $request->national ?? '' ,
                'address' => $request->address ?? '' ,
                'address_province_id' => intval( $request->address_province_id ) > 0 ? intval( $request->address_province_id ) : 0 ,
                'address_district_id' => intval( $request->address_district_id ) > 0 ? intval( $request->address_district_id ) : 0 ,
                'address_commune_id' => intval( $request->address_commune_id ) > 0 ? intval( $request->address_commune_id ) : 0 ,
                'address_village_id' => intval( $request->address_village_id ) > 0 ? intval( $request->address_village_id ) : 0 ,
                'current_address' => $request->current_address ?? '' ,
                'current_address_province_id' => intval( $request->current_address_province_id ) > 0 ? intval( $request->current_address_province_id ) : 0 ,
                'current_address_district_id' => intval( $request->current_address_district_id ) > 0 ? intval( $request->current_address_district_id ) : 0 ,
                'current_address_commune_id' => intval( $request->current_address_commune_id ) > 0 ? intval( $request->current_address_commune_id ) : 0 ,
                'current_address_village_id' => intval( $request->current_address_village_id ) > 0 ? intval( $request->current_address_village_id ) : 0 ,
                'pob' => $request->pob ?? '' ,
                'pob_province_id' => intval( $request->pob_province_id ) > 0 ? intval( $request->pob_province_id ) : 0 ,
                'pob_district_id' => intval( $request->pob_district_id ) > 0 ? intval( $request->pob_district_id ) : 0 ,
                'pob_commune_id' => intval( $request->pob_commune_id ) > 0 ? intval( $request->pob_commune_id ) : 0 ,
                'pob_village_id' => intval( $request->pob_village_id ) > 0 ? intval( $request->pob_village_id ) : 0 ,
                'created_by' => $user == null ? 0 : $user->id ,
                'updated_by' => $user == null ? 0 : $user->id ,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if( strlen( $email ) <= 0 ){
            $people->update([
                'email' => strtolower( $request->enlastname.'.'.$request->enfirstname.str_pad( $people->id , 3 , '0' , STR_PAD_LEFT ).'@ocm.gov.kh' )
            ]);
        }

        $resolvedOrganizationId = $organization != null
            ? intval($organization->id)
            : (intval($request->organization_id) > 0 ? intval($request->organization_id) : 0);
        $resolvedPositionId = $position != null
            ? intval($position->id)
            : (intval($request->position_id) > 0 ? intval($request->position_id) : 0);
        $officerDate = isset($request->date) && strlen(trim((string) $request->date)) > 0
            ? \Carbon\Carbon::parse($request->date)->format('Y-m-d')
            : \Carbon\Carbon::now()->format('Y-m-d');
        $officerOfficialDate = isset($request->official_date) && strlen(trim((string) $request->official_date)) > 0
            ? \Carbon\Carbon::parse($request->official_date)->format('Y-m-d')
            : \Carbon\Carbon::now()->format('Y-m-d');
        $officerUnofficialDate = isset($request->unofficial_date) && strlen(trim((string) $request->unofficial_date)) > 0
            ? \Carbon\Carbon::parse($request->unofficial_date)->format('Y-m-d')
            : \Carbon\Carbon::now()->format('Y-m-d');

        /**
         * Create officer
         */
        $officer = $people->officers()->create([
            'public_key' => md5(
                \Carbon\Carbon::now()->format('YmdHis') .
                $request->code  .
                $people->id .
                $resolvedOrganizationId .
                $resolvedPositionId .
                $request->countesy_id .
                ($formattedDob ?? '')
            ),
            'date' => $officerDate ,
            'salary_rank' => $request->salary_rank?? 'ក.៣.៤' ,
            // 'officer_type' => $request->officer_type?? '' ,
            'officer_type' => $request->ank?? '' ,
            'additional_officer_type' => $request->additional_officer_type ?? 18,
            'code' => strlen( $request->code ) > 0 ? $request->code : '' ,
            'organization_id' => $resolvedOrganizationId ,
            'position_id' => $resolvedPositionId?? 0 ,
            'countesy_id' => $request->countesy_id?? 0  ,
            'rank_id' => $rank_object == null ? 0 : $rank_object->id ,
            'unofficial_date' => $officerUnofficialDate,
            'official_date' => $officerOfficialDate,
            'leader' => 0 ,
            'phone' => $people->officer_phone ?? $people->mobile_phone ,
            'passport' => $request->officer_passport ?? '' ,
            'email' => $request->officer_email ?? $people->email ,
            'created_by' => $user == null ? 0 : $user->id ,
            'updated_by' => $user == null ? 0 : $user->id ,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);


        if ($organizationStructurePosition != null) {
            $officer->jobs()->create([
                'organization_structure_position_id' => $organizationStructurePosition->id ?? 692,
                'unofficial_position_id' => $unOfficialPosition == null ? 0 : $unOfficialPosition->id ,
                'officer_id' => $officer->id ,
                'countesy_id' => $request->countesy_id?? 0  ,
                'start' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                'end' => null ,
                'created_by' => $user == null ? 0 : $user->id ,
                'updated_by' => $user == null ? 0 : $user->id ,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        // $card = $people->cards()->create([
        //     'number' => "OCM-". str_pad( $people->id , 4 , "0" , STR_PAD_LEFT ) ,
        //     'uuid' => md5( \Carbon\Carbon::now()->format('YmdHis') . $people->id ) ,
        //     'people_id' => $people->id ,
        //     'officer_id' => $officer->id ,
        //     'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
        //     'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        // ]);

        $account = $officer->user()->create([
            'firstname' => $people->firstname,
            'lastname' => $people->lastname,
            'email' => $people->email,
            'username' => $people->email,
            'active' => 0 ,
            'phone' => $people->mobile_phone ,
            'password' => bcrypt(
                $officer->phone != null && strlen( $officer->phone ) > 0
                    ? $officer->phone
                    : (
                        $people->mobile_phone != null && strlen( $people->mobile_phone ) > 0
                            ? $request->mobile_phone
                            : 'ocm@123456'
                    )
            ),
            'created_by' => $user == null ? 0 : $user->id ,
            'updated_by' => $user == null ? 0 : $user->id ,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);

        $officer->update([
            'people_id' => $people->id ,
            'user_id' => $account->id ,
        ]);

        /**
         * Assign role
         */
        $backendMemberRole = \App\Models\Role::where('name','backend')->first();
        if( $backendMemberRole != null ){
            $account->roles()->sync([ $backendMemberRole->id ]);
        }
        $account->save();

        $officer->user ;
        $officer->organization;
        $officer->countesy;
        $officer->position;
        $officer->people;
        $officer->jobs;

        if( $officer->people != null ){
            $officer->people->weddingCertificates ;
        }

        return response()->json([
            'ok' => true ,
            'message' => 'បង្កើតបានជោគជ័យ !',
            'record' => $officer
        ], 200);
    }

        /**
     * Update Officer and its relavant data
     */
    public function update(Request $request){
        // Check whether the officer has been assigned a position yet
        $organizationStructurePosition = intval( $request->organization_structure_position_id ) > 0 ? \App\Models\Organization\OrganizationStructurePosition::find( $request->organization_structure_position_id ) : null ;
        $position = $organizationStructurePosition != null && $organizationStructurePosition->position != null ? $organizationStructurePosition->position : null ;
        $unofficialPosition = isset( $request->unofficial_position_id ) && intval( $request->unofficial_position_id ) > 0 ? \App\Models\Position\Position::find( $request->unofficial_position_id ) : null ;
        $organization = $organizationStructurePosition != null && $organizationStructurePosition->organizationStructure != null && $organizationStructurePosition->organizationStructure->organization != null ? $organizationStructurePosition->organizationStructure->organization : null ;
        $organization = intval( $request->organization_id ) > 0 ? \App\Models\Organization\Organization::find( $request->organization_id ) : null ;

        // Check the ranking of the officer
        $ank = isset( $request->ank ) && strlen( $request->ank ) > 0 ? trim($request->ank) : false ;
        $krobkhan = isset( $request->krobkhan ) && strlen( $request->krobkhan ) > 0 ? trim($request->krobkhan) : false ;
        $rank = isset( $request->rank ) && strlen( $request->rank ) > 0 ? trim($request->rank) : false ;
        $thnak = isset( $request->thnak ) && strlen( $request->thnak ) > 0 ? trim($request->thnak) : false ;
        $rank_object = null ;
        if( $ank != false && $krobkhan != false && $rank != false && $thnak != false ){
            $rank_object = \App\Models\Officer\Rank::where([
                'ank' => $ank ,
                'krobkhan' => $krobkhan ,
                'rank' => $rank ,
                'thnak' => $thnak
            ])->first();
        }
        // return response()->json( [
        //         'code' => $request->code ,
        //         // 'organization_id' => $organization != null && intval( $organization->id ) > 0 ? $organization->id : null ,
        //         // 'position_id' => $position != null && intval( $position->id ) > 0 ? $position->id : null ,
        //         // 'rank_id' => $rank_object == null ? $officer->rank_id : $rank_object->id ,
        //         'rank_id' => $rank_object == null ? null : $rank_object->id ,
        //         'countesy_id' => intval( $request->countesy_id ) ,
        //         'passport' => $request->passport ,
        //         'email' => $request->email ,
        //         'phone' => $request->phone ,
        //         'unofficial_date' => strlen( $request->unofficial_date ) > 0 ? \Carbon\Carbon::parse( $request->unofficial_date )->format('Y-m-d') : '' ,
        //         'official_date' => strlen( $request->official_date ) > 0 ? \Carbon\Carbon::parse( $request->official_date )->format('Y-m-d') : '' ,
        //         'salary_rank' => $request->salary_rank?? ( $rank_object != null ? $rank_object->prefix : '' ) ,
        //         'officer_type' => $request->officer_type?? '' ,
        //         'additional_officer_type' => $request->additional_officer_type?? '' ,
        //         'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        //     ] );
        $user = \Auth::user() == null ? null : \Auth::user() ;
        $officer = intval( $request->id ) > 0 ? RecordModel::find( $request->id ) : null ;
        if( $request->people != null ){
            $officer->people->update([
                'firstname' => $request->people['firstname'] ,
                'lastname' => $request->people['lastname'] ,
                'enfirstname' => $request->people['enfirstname'] ,
                'enlastname' => $request->people['enlastname'] ,
                'gender' => intval($request->people['gender']) >= 0 ? intval( $request->people['gender'] ) :  1 ,
                'email' => $request->people['email'] ,
                'dob' => \Carbon\Carbon::parse( $request->people['dob'] )->format('Y-m-d') ,
                'nid' => $request->people['nid'] ,
                'mobile_phone' => $request->people['mobile_phone'] ,
                'office_phone' => $request->people['office_phone'] ,
                'marry_status' => $request->people['marry_status'] != null && $request->people['marry_status'] != '' ? $request->people['marry_status'] : 'single' ,
                'address' => isset( $request->people['address'] ) ? $request->people['address'] : '' ,
                'address_province_id' => isset( $request->people['address_province_id'] ) && intval( $request->people['address_province_id'] ) > 0 ? intval( $request->people['address_province_id'] ) : 0 ,
                'address_district_id' => isset( $request->people['address_district_id'] ) && intval( $request->people['address_district_id'] ) > 0 ? intval( $request->people['address_district_id'] ) : 0 ,
                'address_commune_id' => isset( $request->people['address_commune_id'] ) && intval( $request->people['address_commune_id'] ) > 0 ? intval( $request->people['address_commune_id'] ) : 0 ,
                'address_village_id' => isset( $request->people['address_village_id'] ) && intval( $request->people['address_village_id'] ) > 0 ? intval( $request->people['address_village_id'] ) : 0 ,
                'current_address' => $request->people['current_address'] ?? '' ,
                'current_address_province_id' => isset( $request->people['current_address_province_id'] ) && intval( $request->people['current_address_province_id'] ) > 0 ? intval( $request->people['current_address_province_id'] ) : 0 ,
                'current_address_district_id' => isset( $request->people['current_address_district_id'] ) && intval( $request->people['current_address_district_id'] ) > 0 ? intval( $request->people['current_address_district_id'] ) : 0 ,
                'current_address_commune_id' => isset( $request->people['current_address_commune_id'] ) && intval( $request->people['current_address_commune_id'] ) > 0 ? intval( $request->people['current_address_commune_id'] ) : 0 ,
                'current_address_village_id' => isset( $request->people['current_address_village_id'] ) && intval( $request->people['current_address_village_id'] ) > 0 ? intval( $request->people['current_address_village_id'] ) : 0 ,
                'pob' => $request->people['pob'] ?? '' ,
                'pob_province_id' => isset( $request->people['pob_province_id'] ) && intval( $request->people['pob_province_id'] ) > 0 ? intval( $request->people['pob_province_id'] ) : 0 ,
                'pob_district_id' => isset( $request->people['pob_district_id'] ) && intval( $request->people['pob_district_id'] ) > 0 ? intval( $request->people['pob_district_id'] ) : 0 ,
                'pob_commune_id' => isset( $request->people['pob_commune_id'] ) && intval( $request->people['pob_commune_id'] ) > 0 ? intval( $request->people['pob_commune_id'] ) : 0 ,
                'pob_village_id' => isset( $request->people['pob_village_id'] ) && intval( $request->people['pob_village_id'] ) > 0 ? intval( $request->people['pob_village_id'] ) : 0 ,
                'body_condition' => intval( $request->people['body_condition'] ) ,
                'body_condition_desp' => $request->people['body_condition_desp']??'' ,
                'nationality' => $request->people['nationality'] ?? '' ,
                'national' => $request->people['national'] ?? '' ,
                // father
                'father_firstname' => $request->people['father_firstname'] ?? '' ,
                'father_lastname' => $request->people['father_lastname'] ?? '' ,
                'father_enfirstname' => $request->people['father_enfirstname'] ?? '' ,
                'father_enlastname' => $request->people['father_enlastname'] ?? '' ,
                'father_dob' => $request->people['father_dob'] ?? '' ,
                'father_nationality' => $request->people['father_nationality'] ?? '' ,
                'father_national' => $request->people['father_national'] ?? '' ,
                'father_nid' => $request->people['father_nid'] ?? '' ,
                'father_pob' => $request->people['father_pob'] ?? '' ,
                'father_address' => $request->people['father_address'] ?? '' ,
                'father_address_province_id' => isset( $request->people['father_address_province_id'] ) && intval( $request->people['father_address_province_id'] ) > 0 ? intval( $request->people['father_address_province_id'] ) : 0 ,
                'father_address_district_id' => isset( $request->people['father_address_district_id'] ) && intval( $request->people['father_address_district_id'] ) > 0 ? intval( $request->people['father_address_district_id'] ) : 0 ,
                'father_address_commune_id' => isset( $request->people['father_address_commune_id'] ) && intval( $request->people['father_address_commune_id'] ) > 0 ? intval( $request->people['father_address_commune_id'] ) : 0 ,
                'father_address_village_id' => isset( $request->people['father_address_village_id'] ) && intval( $request->people['father_address_village_id'] ) > 0 ? intval( $request->people['father_address_village_id'] ) : 0 ,
                'father_death' => intval($request->people['father_death']) ,
                'father_profession' => $request->people['father_profession'] ?? '' ,
                // mother
                'mother_firstname' => $request->people['mother_firstname'] ?? '' ,
                'mother_lastname' => $request->people['mother_lastname'] ?? '' ,
                'mother_enfirstname' => $request->people['mother_enfirstname'] ?? '' ,
                'mother_enlastname' => $request->people['mother_enlastname'] ?? '' ,
                'mother_dob' => $request->people['mother_dob'] ?? '' ,
                'mother_nationality' => $request->people['mother_nationality'] ?? '' ,
                'mother_national' => $request->people['mother_national'] ?? '' ,
                'mother_nid' => $request->people['mother_nid'] ?? '' ,
                'mother_pob' => $request->people['mother_pob'] ?? '' ,
                'mother_address' => $request->people['mother_address'] ?? '' ,
                'mother_address_province_id' => isset( $request->people['mother_address_province_id'] ) && intval( $request->people['mother_address_province_id'] ) > 0 ? intval( $request->people['mother_address_province_id'] ) : 0 ,
                'mother_address_district_id' => isset( $request->people['mother_address_district_id'] ) && intval( $request->people['mother_address_district_id'] ) > 0 ? intval( $request->people['mother_address_district_id'] ) : 0 ,
                'mother_address_commune_id' => isset( $request->people['mother_address_commune_id'] ) && intval( $request->people['mother_address_commune_id'] ) > 0 ? intval( $request->people['mother_address_commune_id'] ) : 0 ,
                'mother_address_village_id' => isset( $request->people['mother_address_village_id'] ) && intval( $request->people['mother_address_village_id'] ) > 0 ? intval( $request->people['mother_address_village_id'] ) : 0 ,
                'mother_death' => intval($request->people['mother_death']) ,
                'mother_profession' => $request->people['mother_profession'] ?? '' ,
                // Emergency
                'emergency_lastname' => $request->people['emergency_lastname'] ,
                'emergency_firstname' => $request->people['emergency_firstname'] ,
                'emergency_gender' => intval( $request->people['emergency_gender'] ) ,
                'emergency_relationship' => $request->people['emergency_relationship'] ,
                'emergency_profession' => $request->people['emergency_profession'] ,
                'emergency_phone' => $request->people['emergency_phone'] ,
                'emergency_email' => $request->people['emergency_email'] ,
                'emergency_address' => $request->people['emergency_address'] ,
                'emergency_address_province_id' => isset( $request->people['emergency_address_province_id'] ) && intval( $request->people['emergency_address_province_id'] ) > 0 ? $request->people['emergency_address_province_id'] : 0 ,
                'emergency_address_district_id' => isset( $request->people['emergency_address_district_id'] ) && intval( $request->people['emergency_address_district_id'] ) > 0 ? $request->people['emergency_address_district_id'] : 0 ,
                'emergency_address_commune_id' => isset( $request->people['emergency_address_commune_id'] ) && intval( $request->people['emergency_address_commune_id'] ) > 0 ? $request->people['emergency_address_commune_id'] : 0 ,
                'emergency_address_village_id' => isset( $request->people['emergency_address_village_id'] ) && intval( $request->people['emergency_address_village_id'] ) > 0 ? $request->people['emergency_address_village_id'] : 0 ,
                'updated_by' => $user == null ? 0 : $user->id ,
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        $whereCondition = [
                'code' => $request->code ?? $officer->code,
                // 'organization_id' => $organization != null && intval( $organization->id ) > 0 ? $organization->id : null ,
                // 'position_id' => $position != null && intval( $position->id ) > 0 ? $position->id : null ,
                // 'rank_id' => $rank_object == null ? $officer->rank_id : $rank_object->id ,
                'rank_id' => $rank_object == null ? 0 : $rank_object->id ,
                'countesy_id' => intval( $request->countesy_id ) ?? '',
                'passport' => $request->passport ?? $officer->people->passport_id,
                'email' => $request->email ,
                'phone' => $request->phone ,
                'unofficial_date' => strlen( $request->unofficial_date ) > 0 ? \Carbon\Carbon::parse( $request->unofficial_date )->format('Y-m-d') : '' ,
                'official_date' => strlen( $request->official_date ) > 0 ? \Carbon\Carbon::parse( $request->official_date )->format('Y-m-d') : '' ,
                'salary_rank' => $request->salary_rank?? ( $rank_object != null ? $rank_object->prefix : '' ) ,
                'officer_type' => $request->officer_type?? '' ,
                'additional_officer_type' => $request->additional_officer_type?? '' ,
                'updated_by' => $user == null ? 0 : $user->id ,
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ];
        $officer->update( $whereCondition );

        $currentJob = $officer->getCurrentJob();
        if( $organizationStructurePosition != null ){
            if( $currentJob == null ){
                $currentJob = $officer->jobs()->create([
                    'organization_structure_position_id' => $organizationStructurePosition->id ,
                    'unofficial_position_id' => $unofficialPosition == null ? 0 : $unofficialPosition->id ,
                    'officer_id' => $officer->id ,
                    'countesy_id' => intval( $request->countesy_id ) ,
                    'start' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                    'end' => null ,
                    'created_by' => $user == null ? 0 : $user->id ,
                    'updated_by' => $user == null ? 0 : $user->id ,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }else{
                $currentJob->update([
                    'organization_structure_position_id' => $organizationStructurePosition->id ,
                    'unofficial_position_id' => $unofficialPosition == null ? 0 : $unofficialPosition->id ,
                    'countesy_id' => intval( $request->countesy_id ) > 0 ? intval( $request->countesy_id ) : $currentJob->countesy_id ,
                    'updated_by' => $user == null ? 0 : $user->id ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }
        }

        $officer->user ;
        $officer->organization;
        $officer->countesy;
        $officer->currentJobs;
        $officer->position;
        $officer->people;
        $officer->rank;
        $officer->jobs;

        $job = $officer == null ? null : $officer->getCurrentJob() ;
        if( $job != null && $job->organizationStructurePosition != null ){
            $job->organizationStructurePosition->position;
            if( $job->organizationStructurePosition->organizationStructure != null ){
                $job->organizationStructurePosition->organizationStructure->organization;
            }
        }
        $officer->current_job = $job ;


        if( $officer->people != null ){
            $officer->people->weddingCertificates ;
        }

        return response()->json([
            'record' => $officer ,
            'message' => 'កែប្រែព័ត៌មានរួចរាល់ !' ,
            'ok' => true
        ], 200);
    }

        /**
     * Function delete an account
     */
    public function destroy(Request $request){
        $officer = RecordModel::find($request->id) ;
        if( $officer ){
            if( $officer->user != null ){
                $officer->user->deleted_at = \Carbon\Carbon::now() ;
                $officer->user->save();
            }
            if( $officer->people != null ){
                $officer->people->deleted_at = \Carbon\Carbon::now() ;
                $officer->people->save();
            }
            $officer->delete();
            return response([
                'ok' => true ,
                'officer' => $officer ,
                'message' => 'បានលុបដោយជោគជ័យ !' ,
                'ok' => true
            ],200
            );
        }else{
            // User does not exists
            return response([
                'ok' => false ,
                'user' => null ,
                'message' => 'សូមទោស ព័ត៌មាននេះមិនមានទេ !' ],
                201
            );
        }
    }

        public function uploadProfile(Request $request){
        $user = \Auth::user();
        if( $user ){
           
            if( isset( $_FILES['files']['tmp_name'] ) && $_FILES['files']['tmp_name'] != "" ) {
                if( ( $officer = RecordModel::find($request->id) ) !== null ){
                     // លុបឯកសារយោងដែលមានមុនពេលដាក់ឯកសារថ្មី
                    if( !empty($officer->image) && Storage::disk('public')->exists( $officer->image ) ){
                        Storage::disk('public')->delete( $officer->image );
                    }
                    $uniqeName = Storage::disk('public')->putFile( 'contracted_officer_image/'.$officer->id , new File( $_FILES['files']['tmp_name'] ) );
                    $officer->image = $uniqeName ;
                    $officer->save();
                    
                    if( Storage::disk('public')->exists( $officer->image ) ){
                        $officer->image = Storage::disk('public')->url( $officer->image);
                        return response([
                            'record' => $officer ,
                            'message' => 'ជោគជ័យក្នុងការបញ្ចូលរូបថត។'
                        ],200);
                    }else{
                        return response([
                            'record' => $officer ,
                            'message' => 'គណនីនេះមិនមានរូបថតឡើយ។'
                        ],403);
                    }
                }else{
                    return response([
                        'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់របស់គណនី។'
                    ],403);
                }
            }else{
                return response([
                    'result' => $_FILES ,
                    'message' => 'មានបញ្ហាជាមួយរូបភាពដែលអ្នកបញ្ជូនមក។'
                ],403);
            }

        }else{
            return response([
                'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
            ],403);
        }
    }
}
