<?php

namespace App\Http\Controllers\Api\AuthenticationCenter\Officer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CrudController;
use App\Models\Officer\OfficerJob as RecordModel;
use \App\Models\Organization\OrganizationStructurePosition as Postion;


use Illuminate\Http\Request;

class OfficerJobController extends Controller
{   
     private $selectFields = [
        'id',
        'organization_structure_position_id' ,
        'unofficial_position_id' ,
        'officer_id' ,
        'countesy_id' ,
        'start' ,
        'end' ,
        'created_at' ,
        'updated_at' ,
        'created_by' ,
        'updated_by'
    ];

    /*
    * CRUD Officer Job
    */
    public function index(Request $request)
    {
        $user = \Auth::user() != null
            ? \Auth::user()
            : (
                auth('api')->user()
                    ? auth('api')->user()
                    : ($request->user() != null ? $request->user() : null)
            );

        /** Format from query string */
        $search  = isset($request->search) && strlen($request->search) > 0 ? $request->search : false;
        $perPage = isset($request->perPage) && intval($request->perPage) > 0 ? $request->perPage : 20;
        $page    = isset($request->page) && intval($request->page) > 0 ? $request->page : 1;

        $queryString = [
            "pagination" => [
                'perPage' => $perPage,
                'page'    => $page
            ],
            "search" => $search === false ? [] : [
                'value'  => $search,
                'fields' => [
                    'start',
                    'end'
                ]
            ],
            "order" => [
                'field' => 'id',
                'by'    => 'desc'
            ],
        ];

        $request->merge($queryString);

        $crud = new CrudController(new \App\Models\Officer\OfficerJob(), $request, $this->selectFields);

        $crud->setRelationshipFunctions([
            'organizationStructurePosition' => [
                'id', 'name', 'image', 'pdf', 
                'organizationStructure' => [
                    'id', 'organization_id', 'name'
                ]
            ], 
            'officer' => [
                'id',
                'code',
                'user' => ['id', 'firstname', 'lastname'],
                'countesy' => ['id','name'],
                'people' => [ 'id', 
                    
                ]
            ],
        ]);

        $builder = $crud->getListBuilder();
        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true;

        return response()->json($responseData);
    }

    public function read(Request $request)
    {
        $user = \Auth::user() != null
            ? \Auth::user()
            : (
                auth('api')->user()
                    ? auth('api')->user()
                    : ($request->user() != null ? $request->user() : null)
            );

        $record = intval( $request->id ) > 0 ? RecordModel::find( $request->id ) : null ;
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'record' => $record ,
                'message' => 'មិនមានព័ត៌មាននេះឡើយ។'
            ],403);
        }

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields);

        $crud->setRelationshipFunctions([
            'organizationStructurePosition' => [
                'id', 'name', 'image', 'pdf', 
                'organizationStructure' => [
                    'id', 'organization_id', 'name'
                ]
            ], 
            'officer' => [
                'id',
                'code',
                'user' => ['id', 'firstname', 'lastname'],
                'countesy' => ['id','name'],
                'people' => [ 'id', 
                    
                ]
            ],
        ]);

        $builder = $crud->getListBuilder();
        $builder->where('id' , $record->id );
        $responseData = $crud->pagination(true, $builder);
        return response()->json([
            'message' => __("crud.read.success") ,
            'record' => $responseData['records']->first() ,
            'ok' => true
        ], 200);
    }

    public function addOfficeJob(Request $request)
    {
        $user = \Auth::user() != null
            ? \Auth::user()
            : (
                auth('api')->user()
                    ? auth('api')->user()
                    : (
                        $request->user() != null
                            ? $request->user()
                            : 0
                    )
            );
            
        if (
            !isset($request->organization_structure_position_id) ||
            intval($request->organization_structure_position_id) <= 0 &&
            !isset($request->officer_id) ||
            intval($request->officer_id) <= 0
        ) {
            return response()->json([
                'ok' => false,
                'message' => 'ទិន្នន័យមិនគ្រប់គ្រាន់។'
            ], 422);
        }
        // $backendMemberRole = \App\Models\Role::backend()->first();
        //     if( $backendMemberRole != null ){
        //         $user->roles()->sync( [$backendMemberRole]);
        //     }
        
        $Officer = \App\Models\Officer\Officer::where('id', $request->officer_id)->first();
        if ($Officer = null) {
            return response()->json([
                'ok' => false,
                'record' => $Officer,
                'message' => 'ទិន្នន័យមន្ត្រីនេះមិនមានក្នុងប្រព័ន្ធ។'
            ], 200);
        }
        
        $existing = \App\Models\Officer\OfficerJob::where('organization_structure_position_id', $request->organization_structure_position_id)
            ->where('officer_id', $request->officer_id)
            ->where('start', $request->start)
            ->first();

        if ($existing != null) {
            return response()->json([
                'ok' => false,
                'record' => $existing,
                'message' => 'តួនាទីមន្ត្រីនេះមានរួចហើយ។'
            ], 200);
        }

        $position = \App\Models\Organization\OrganizationStructurePosition::where('id', $request->organization_structure_position_id)
            ->first();

        if (!$position) {
            return response()->json([
                'ok' => false,
                'message' => 'តួនាទីនេះមិនមានក្នុងប្រព័ន្ធ។.'
            ], 404);
        }

        // Get the organization_structure_id from the position
        // $organizationStructureId = $position->organization_structure_id;

        $parentOrg = $position->organizationStructure->parentNode;
        $organization = \App\Models\Organization\OrganizationStructure::where('id', $parentOrg->id)->first();  //shouldn't use get() because it will return a collection but use first() because it will return a json object instance or module
        $organizationName = $organization->organization->name ?? null;

        $suborganizationName = $position->organizationStructure->organization->name;
        $positionName = $position->position->name;
        $officeJobBackground = \App\Models\Officer\OfficerJobBackground::create([
            // 'organization_structure_id' => $organizationStructureId,  // <--- added here
            'officer_id' => $request->officer_id,
            'officer_job_id' => null,
            'organization' => $organizationName,
            'sub_organization' => $suborganizationName,
            'position' => $positionName,
            'start' => $request->start ?? \Carbon\Carbon::now()->format('Y-m-d'),
            'pdf' => $request->pdf,
            'skill_of_position' => $request->skill_of_position ?? null,
            'sector' => $$request->sector,
            'created_by' => $user->id
        ]);

        $officerJob = \App\Models\Officer\OfficerJob::create([
            'organization_structure_position_id' => $request->organization_structure_position_id,
            //ប្រើនៅពេលដែលតួនាទីរបស់មន្រ្តីមាន​សិទ្ធិស្មើនិងតួនាទីណាមួយ
            'unofficial_position_id' => $request->organization_structure_unofficial_position_id,
            'officer_id' => $request->officer_id,
            'is_primary' => $request->boolean('is_primary'),
            'countesy_id' => $request->countesy_id ?? null,
            'start' => $request->start ?? \Carbon\Carbon::now()->format('Y-m-d'),
            'created_by' => $user->id,
        ]);
        $officeJobBackground->update(['officer_job_id'=>$officerJob->id]);

        return response()->json([
            'ok' => true,
            'record' => $officerJob,
            'officer_job_background' => $officeJobBackground,
            'message' => 'បានបញ្ចូលតួនាទីមន្រ្តីរួចហើយ។'
        ], 200);
    }

    public function updateOfficerJob(Request $request)
    {
        $user = \Auth::user() != null
            ? \Auth::user()
            : (
                auth('api')->user()
                    ? auth('api')->user()
                    : (
                        $request->user() != null
                            ? $request->user()
                            : 0
                    )
            );
        // dd($request->all());

        // Find the OfficerJob
        $officerJob = \App\Models\Officer\OfficerJob::where('id', $request->id)->first();
        if (!$officerJob) {
            return response()->json([
                'ok' => false,
                'message' => 'តួនាទីមន្ត្រីមិនមានក្នុងប្រព័ន្ធ.'
            ], 404);
        }

        // Update fields only if provided, otherwise keep previous values
        $officerJob->organization_structure_position_id = $request->organization_structure_position_id ?? $officerJob->organization_structure_position_id;
        $officerJob->unofficial_position_id = $request->unofficial_position_id ?? $officerJob->unofficial_position_id;
        $officerJob->officer_id = $request->officer_id ?? $officerJob->officer_id;
        $officerJob->countesy_id = $request->countesy_id ?? $officerJob->countesy_id;
        $officerJob->start = $request->start ?? $officerJob->start;
        $officerJob->end = $request->end ?? $officerJob->end;
        $officerJob->updated_by = $user->id;

        $officerJob->save();

        return response()->json([
            'ok' => true,
            'record' => $officerJob,
            'message' => 'តួនាទីមន្ត្រីកែប្រែបានសម្រេច.'
        ], 200);
    }

    public function destroyOfficerJob(Request $request)
    {
        $officerJob = \App\Models\Officer\OfficerJob::find($request->id);

        if ($officerJob) {
            $officerJob->delete(); // requires SoftDeletes trait on OfficerJob model

            return response()->json([
                'ok' => true,
                'record' => $officerJob,
                'message' => 'Officer job deleted successfully!'
            ], 200);
        }

        return response()->json([
            'ok' => false,
            'record' => null,
            'message' => 'Officer job not found!'
        ], 404);
    }
}
