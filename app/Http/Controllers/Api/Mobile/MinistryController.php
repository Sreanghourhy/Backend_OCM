<?php

namespace App\Http\Controllers\Api\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class MinistryController extends Controller
{
    /**
     * Listing function
     */
    public function index(Request $request){
        // $perpage = 
        return response([
            'records' => \App\Models\Ministry::orderby('name','asc')->get(),
            'message' => 'អានឈ្មោះ ក្រសួង  ស្ថាប័ន បានជោគជ័យ !' 
        ],200 );
    }
    
}
