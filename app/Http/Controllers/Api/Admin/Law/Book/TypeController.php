<?php
namespace App\Http\Controllers\Api\Admin\Law\Book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Law\Book\BookType AS RecordModel;
use Illuminate\Http\File;
use App\Http\Controllers\CrudController;

class TypeController extends Controller
{
    private $selectedFields ;
    public function __construct(){
        $this->selectedFields = ['id', 'format', 'name', 'order','created_at','updated_at'] ;
    }
    /** Get a list of Archives */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 50 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        $queryString = [
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'name'
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'desc'
            ],
        ];

        $request->merge( $queryString );
        $crud = new CrudController(new RecordModel(), $request, $this->selectedFields );
        $builder = $crud->getListBuilder();
        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        return response()->json($responseData, 200);
    }
    /** Create a new Archive */
    public function store(Request $request){
        if( ($user = $request->user() ) !== null ){
            /** Merge variable created_by and updated_by into request */
            $input = $request->input();
            $input['created_at'] = $input['updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $request->merge($input);
            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields );
            if (($record = $crud->create()) !== false) {
                $record = $crud->formatRecord($record);
                return response()->json([
                    'record' => $record,
                    'message' => __("crud.save.success")
                ], 200);
            }
            return response()->json([
                'record' => null,
                'message' => __("crud.save.failed")
            ], 201);
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.auth.failed")
        ], 401);
        
    }
    /** Updating the archive */
    public function update(Request $request)
    {
        if (($user = $request->user()) !== null) {
            /** Merge variable created_by and updated_by into request */
            $input = $this->request->input();
            $input['updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $request->merge($input);

            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields);
            if (($record = $crud->update()) !== false) {
                $record = $crud->formatRecord($record);
                return response()->json([
                    'record' => $record,
                    'message' => __("crud.update.success")
                ], 200);
            }
            return response()->json([
                'record' => null,
                'message' => __("crud.update.failed")
            ], 201);
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    /** Updating the archive */
    public function read(Request $request)
    {
        if (($user = $request->user()) !== null) {
            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields );
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
            $input = $this->request->input();
            $input['updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $request->merge($input);

            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields );
            if (($record = $crud->delete()) !== false) {
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
    /** Check duplicate archive */
    public function exists(Request $request){
        if (($user = $request->user()) !== null) {
            $crud = new CrudController(new RecordModel(), $request, $this->selectedFields );
            if ( ($record = $crud->exists(['fid','year'],true)) !== false) {
                $record = $crud->formatRecord($record);
                return response()->json([
                    'record' => $record,
                    'message' => __("crud.duplicate.no")
                ], 200);
            }
            return response()->json([
                'record' => null,
                'message' => __("crud.duplicate.yes")
            ], 201);
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.auth.failed")
        ], 401);
    }
    public function compactList(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $queryString = [
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'name'
                ]
            ],
            "order" => [
                'field' => 'order' ,
                'by' => 'asc'
            ],
        ];
        $request->merge( $queryString );
        $crud = new CrudController(new RecordModel(), $request, ['id', 'name','order','format']);
        $responseData['records'] = $crud->getListBuilder()->get();
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true;
        return response()->json($responseData);
    }
}
