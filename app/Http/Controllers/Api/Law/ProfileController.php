<?php

namespace App\Http\Controllers\Api\Law;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

class ProfileController extends Controller
{
    public function getAuthUser (Request $request)
    {
        $user = Auth::user();
        // if( Storage::disk('public')->exists( $user->avatar_url ) )  $user->avatar_url = Storage::url( $user->avatar_url  );
        if( Storage::disk('public')->exists( $user->avatar_url ) )  $user->avatar_url = Storage::disk("public")->url( $user->avatar_url  );
        return response( [
            'user' => $user != null ? $user : null ,
            'message' => 'អានព័ត៌មានអ្នកប្រើប្រាស់ បានជោគជ័យ !'
        ],200 );
    }

    public function updateAuthUser (Request $request)
    {
        $user = User::find(Auth::id());
        $user->update([
            'firstname' => $request->firstname ,
            'lastname' => $request->lastname ,
            // 'role' => $request->role ? 1 : 0 ,
            'username' => $request->username ,
            'phone' => $request->phone ,
            'updated_at' => $request->updated_at ,
        ]);
        $user->save();

        return response([
            'user' => $user ,
            'message' => 'រក្សាទុកព័ត៌មានបានជោគជ័យ !'
        ],200);
    }

    public function updateAuthUserPassword(Request $request)
    {
        // $this->validate($request, [
        //     'current' => 'required',
        //     'password' => 'required|confirmed',
        //     'password_confirmation' => 'required'
        // ]);

        $user = User::find(Auth::id());

        if (!Hash::check($request->current, $user->password)) {
            return response([
                'message' => 'ពាក្យសម្ងាត់បច្ចុប្បន្ន មិនត្រឹមត្រូវឡើយ !'
            ],201);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response([
            'user' => $user ,
            'message' => 'ផ្លាស់ប្ដូរពាក្យសម្ងាត់ថ្មីបានជោគជ័យ !'
        ],200);
    }
    public function upload(Request $request){
        $user = Auth::user();
        if( $user ){

            $uniqeName = Storage::disk('public')->putFile( "avatars/".$user->id, new File( $_FILES['files']['tmp_name'][0] ) );
            $user->avatar_url = $uniqeName ;
            $user->save();

            if( Storage::disk('public')->exists( $user->avatar_url ) ){
                // $user->avatar_url = Storage::url( $user->avatar_url  );
                $user->avatar_url = Storage::disk("public")->url( $user->avatar_url  );
                return response([
                    'user' => $user ,
                    'message' => 'ជោគជ័យក្នុងការដាក់រូបភាពរបស់អ្នកប្រើប្រាស !'
                ],200);
            }
        }else{
            return response([
                'message' => 'បរាជ័យក្នុងការដាក់រូបភាពរបស់អ្នកប្រើប្រាស !'
            ],201);
        }
    }
}
