<?php

namespace App\Http\Controllers\Api\Law\Book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Law\Book\Unit AS RecordModel;
use App\Http\Controllers\CrudController;

class UnitController extends Controller
{
    public function forfilter(Request $request){
        if (($user = $request->user()) !== null) {
            /** Merge variable created_by and updated_by into request */
            $crud = new CrudController(new RecordModel(), $request, ['id', 'name']);
            if (($record = $crud->getListBuilder()->where('active',1)->orderby('name','asc')->get()) !== false) {
                return response()->json([
                    'records' => $record,
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
}
