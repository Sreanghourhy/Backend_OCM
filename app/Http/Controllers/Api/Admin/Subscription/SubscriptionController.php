<?php

namespace App\Http\Controllers\Api\Admin\Subscription;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subscription as RecordModel;
use App\User;
use App\Http\Controllers\CrudController;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /** Get a list of Archives */
    public function index(Request $request){
        $crud = new CrudController(new RecordModel(), $request, ['id','amount','start_date','user_id', 'end_date','updated_at','created_at']);
        $crud->setRelationshipFunctions([
            "user" => ['id','username'],
        ]);
        $builder = $crud->getListBuilder();
        $responseData = $crud->pagination(true, $builder);
        $responseData['users'] = User::pluck('username','id');
        $responseData['message'] = __("crud.read.success");
        return response()->json($responseData, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'amount' => 'required',
        ]);
        $new = RecordModel::create([
            'user_id'=>$request->user_id,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'amount'=>$request->amount
        ]);
        $responseData['message'] = __("crud.read.fail");
        $status = 404;
        if($new){
            $responseData['message'] = __("crud.read.success");
            $status = 200;
        }
        return response()->json($responseData, $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        if (($user = $request->user()) !== null) {
            $crud = new CrudController(new RecordModel(), $request, ['id','amount','start_date','user_id', 'end_date','updated_at','created_at']);
            if (($record = $crud->read()) !== false) {
                $record = $crud->formatRecord($record);
                $record['start_date']  = $record['start_date']->format("Y-m-d H:i:s");
                $record['created_at']  = $record['created_at']->format("Y-m-d H:i:s");
                $record['end_date']  = $record['end_date']->format("Y-m-d H:i:s");
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $subscription = RecordModel::find($id);
        if($subscription){
            $subscription->start_date = $request->start_date;
            $subscription->end_date = $request->end_date;
            $subscription->user_id = $request->user_id;
            $subscription->amount = $request->amount;
            $subscription->save();
            if($subscription){
                return response()->json([
                    'record' => $subscription,
                    'message' => __("crud.update.success")
                ], 200);
            }
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.update.failed")
        ], 404);
    }
    /** Reading an archive */
    public function delete(Request $request)
    {
        if (($user = $request->user()) !== null) {
            /** Merge variable created_by and updated_by into request */
            $input = $request->input();
            $input['updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $input['updated_by'] = $user->id;
            $request->merge($input);

            $crud = new CrudController(new RecordModel(), $request, ['id', 'number','type_id', 'active', 'title', 'objective', 'year', 'pdfs','created_at','updated_at','created_by','updated_by']);
            if (($record = $crud->delete()) !== false) {
                /** Delete its structure and matras too */
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
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = RecordModel::find($id);
        if($record) {
            $record->delete();
            return response()->json([
                'record' => $record,
                'message' => __("crud.delete.success")
            ], 200);
        }
        return response()->json([
            'record' => null,
            'message' => __("crud.delete.failed")
        ], 404);
    }
}
