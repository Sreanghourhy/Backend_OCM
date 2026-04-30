<?php

namespace App\Http\Controllers\Api\Admin\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File\File AS RecordModel;

class FileController extends Controller
{
    private $selectedFields = ['id', 'name','meta', 'model', 'model_id','created_by', 'updated_by' , 'created_at' , 'updated_at' , 'tags'];
    /** Get a list of Archives */
    public function index(Request $request){

        $user = \Auth::user();
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមចូលប្រើប្រាស់ជាមុនសិន។'
            ],403);
        }
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 50 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;
        // $number = isset( $request->number ) && $request->number !== "" ? $request->number : false ;
        // $type = isset( $request->type ) && $request->type !== "" ? $request->type : false ;
        // $unit = isset( $request->unit ) && $request->unit !== "" ? $request->unit : false ;
        // $date = isset( $request->date ) && $request->date !== "" ? $request->date : false ;


        $queryString = [
            "where" => [
                'default' => [
                    $user->hasRole('admin') || $user->hasRole('super')
                        ? [] 
                        : (
                            $user->id > 0 
                                ? [ 'field' => 'created_by' ,'value' => $user->id ]
                                : []
                        )
                ],
                // 'in' => [] ,
                // 'not' => [] ,
                // 'like' => [
                //     [
                //         'field' => 'objective' ,
                //         'value' => $search
                //     ]
                // ] ,
            ] ,
            // "pivots" => [
            //     $unit ?
            //     [
            //         "relationship" => 'units',
            //         "where" => [
            //             "in" => [
            //                 "field" => "id",
            //                 "value" => [$request->unit]
            //             ],
            //         // "not"=> [
            //         //     [
            //         //         "field" => 'fieldobjective' ,
            //         //         "value"=> 'value'
            //         //     ]
            //         // ],
            //         // "like"=>  [
            //         //     [
            //         //        "field"=> 'fieldobjective' ,
            //         //        "value"=> 'value'
            //         //     ]
            //         // ]
            //         ]
            //     ]
            //     : []
            // ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'objective', 'start' , 'end'
                ]
            ],
            "order" => [
                'field' => 'created_at' ,
                'by' => 'desc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectedFields );
        $crud->setRelationshipFunctions([
            /** relationship objective => [ array of fields objective to be selected ] */
            'creator' => ['id', 'firstname', 'lastname' ,'phone', 'avatar_url' ] ,
            'assignees' => ['id', 'firstname', 'lastname' ,'phone', 'image' ] ,
            'assignors' => ['id', 'firstname', 'lastname' ,'phone', 'image' ] ,
            'ancestor' => ['id', 'objective','minutes', 'start', 'end', 'created_at','created_by','status','pid'] ,
            'children' => ['id', 'objective','minutes', 'start', 'end', 'created_at','created_by','status','pid'] ,
            'childrenAllLevels' => ['id', 'objective','minutes', 'start', 'end', 'created_at','created_by','status','pid'] 
        ]);
        $builder = $crud->getListBuilder();

        /** Filter the record by the user role */
        // if( ( $user = $request->user() ) !== null ){
        //     /** In case user is the administrator, all archives will show up */
        //     if( array_intersect( $user->roles()->pluck('id')->toArray() , [2,3,4] ) ){
        //         /** In case user is the super, auditor, member then the archives will show up if only that archives are own by them */
        //         $builder->where('created_by',$user->id);
        //     }else{
        //         /** In case user is the customer */
        //         /** Filter archives by its type before showing to customer */
        //     }
        // }

        $responseData = $crud->pagination(true, $builder);
        $responseData['records'] = $responseData['records']->map(function($task){
            $task['creator']['avatar_url'] = ( $task['creator']['avatar_url'] != null && $task['creator']['avatar_url'] != "" && \Storage::disk( 'public' )->exists( $task['creator']['avatar_url'] ) )
                ? \Storage::disk('public')->url( $task['creator']['avatar_url'] ) 
                : null ;
            return $task ;
        });
        $responseData['message'] = __("crud.read.success");
        return response()->json($responseData, 200);
    }
    /** Create a new Archive */
    public function create(Request $request){
        $user = \Auth::user();
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមចូលប្រើប្រាស់ជាមុនសិន។'
            ],403);
        }        

        /**
         * Create a meeting under another meeting
         */
        $parent = intval( $request->pid ) > 0 ? RecordModel::find( $request->pid ) : null ;

        if (($record = RecordModel::create([
            'objective' => $request->objective ,
            'minutes' => $request->minutes ,
            'created_by' => $user->id ,
            'pid' => $parent != null && $parent->id > 0 ? $parent->id : 0 ,
            'tpid' => $parent != null && $parent->tpid > 0 ? $parent->tpid : 0
        ])) !== false) {
            /** Link the archive to the units */
            return response()->json([
                'record' => $record,
                'message' => __("crud.created.success")
            ], 200);
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.created.failed")
        ], 500);
    }
    /** Updating the archive */
    public function update(Request $request)
    {
        $user = \Auth::user();
        if( $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមចូលប្រើប្រាស់ជាមុនសិន។'
            ],403);
        }
        $record = RecordModel::find($request->id);
        if ( $record->update([
            'objective' => $request->objective ,
            'minutes' => $request->minutes ,
            'updated_by' => $user->id
        ]) ) {
            return response()->json([
                'ok' => true ,
                'record' => $record,
                'message' => __("crud.update.success")
            ], 200);
        }
        return response()->json([
            'ok' => false ,
            'record' => null,
            'message' => __("crud.update.failed")
        ], 201);
    }
    /** Updating the archive */
    public function read(Request $request)
    {
        if (($user = $request->user()) !== null) {
            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields);
            // $crud->setRelationshipFunctions([
            //     'units' => false
            // ]);
            if (($record = $crud->read()) !== false) {
                $record = $crud->formatRecord($record);
                return response()->json([
                    'record' => $record,
                    'message' => __("crud.read.success")
                ], 200);
            }
            return response()->json([
                'record' => null,
                'message' => __("crud.read.failed")
            ], 201);
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Reading an archive */
    public function delete(Request $request)
    {
        if (($user = $request->user()) !== null) {
            /** Merge variable created_by and updated_by into request */
            // $input = $request->input();
            // $input['updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            // $input['updated_by'] = $user->id;
            // $request->merge($input);

            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields);
            if (($record = $crud->delete()) !== false) {
                /** Delete its structure and matras too */
                return response()->json([
                    'ok' => true ,
                    'record' => $record,
                    'message' => __("crud.delete.success")
                ], 200);
            }
            return response()->json([
                'ok' => false ,
                'record' => null,
                'message' => __("crud.delete.failed")
            ], 201);
        }
        return response()->json([
            'ok' => false ,
            'record' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Upload file */
    public function upload(Request $request){
        if (($user = $request->user()) !== null) {
            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields);
            $record = $crud->read();
            list($year,$month,$day) = explode('-', \Carbon\Carbon::parse( $record->year )->format('Y-m-d') );
            $path = $record->type_id."/".$year;
            if (($record = $crud->upload('pdfs',$path, new File($_FILES['files']['tmp_objective'][0]),$record->type_id.'-'.$year.$month.$day."-".$record->number.'.pdf' )) !== false) {
                // $record = $crud->formatRecord($record);
                return response()->json([
                    'record' => $record,
                    'message' => __("crud.delete.success")
                ], 200);
            }
            return response()->json([
                'record' => null,
                'message' => __("crud.delete.failed")
            ], 201);
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Active the record */
    public function active(Request $request)
    {
        if (($user = $request->user()) !== null) {
            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields);
            if ($crud->booleanField('active', 1)) {
                $record = $crud->formatRecord($record = $crud->read());
                return response(
                    [
                        'record' => $record,
                        'message' => 'Activated !'
                    ],
                    200
                );
            } else {
                return response(
                    [
                        'record' => null,
                        'message' => 'There is not record matched !'
                    ],
                    350
                );
            }
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Unactive the record */
    public function unactive(Request $request)
    {
        if (($user = $request->user()) !== null) {
            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields);
            if ( $crud->booleanField('active', 0) ) {
                // User does exists
                return response(
                    [
                        'record' => $record,
                        'message' => 'Deactivated !'
                    ],
                    200
                );
            } else {
                return response(
                    [
                        'record' => null,
                        'message' => 'There is not record matched !'
                    ],
                    350
                );
            }
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /**
     * Remove file
     */
    public function removefile(Request $request)
    {
        $crud = new CrudController(new RecordModel(), $request, $this->selectedFields);
        if (($record = $crud->removeFile('pdfs')) != null) {
            $record = $crud->formatRecord( $record );
            return response()->json([
                'record' => $record ,
                'message' => __('crud.remove.file.success')
            ], 200);
        }
        return response()->json([
            'message' => __('crud.remove.file.success')
        ], 350);
    }
}
