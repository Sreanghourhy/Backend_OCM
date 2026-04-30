<?php

namespace App\Http\Controllers\Api\Law;

use App\Models\User;
use App\Models\People\People;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Laravolt\Avatar\Avatar;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class GoogleController extends Controller
{

    public function updateOrCreate(Request $request){
        // User Data
        /*
        email: "chamroeunoum@gmail.com"
        email_verified: true
        family_name: "oum"
        given_name: "chamroeun"
        name: "chamroeun oum"
        picture: "https://lh3.googleusercontent.com/a/ACg8ocJ3BXrm7vWWO_A-9VVRIKnX-6Brji0vMuICqShCBJsqq95YawBW=s96-c"
        sub: "115273140778490497838"
        */
        $email = '' ;
        if( isset( $request->email ) && strlen(trim($request->email))>0 ){
            $email = $request->email;
        }else{
            $email = $request->given_name . $request->family_name . $request->sub . "@google.otc" ;
        }
        // Check whether has been already registerd
        $user = \App\Models\User::
            where('google_user_id', $request->sub)
            ->whereNotNull('google_user_id')
            ->first();
        // Check whether the user is already our member with the email
        if( $user == null ){
                $user = \App\Models\User::
                where('email', $email )
                ->whereNotNull('email')
                ->first();
        }
        // Check whether the user is already our member with the email
        if( $user == null && $request->phone != "" ){
            $user = \App\Models\User::
            where('phone', $request->phone)
            ->whereNotNull('phone')
            ->first();
        }

        /**
         * Check roles
         * Check whether the admin and super admin come to visit
         * This does not allow the admin and super admin to visit
         */
        if( $user != null && !empty( array_intersect( $user->roles->pluck('id')->toArray() , \App\Models\Role::where('name','super')->orWhere('name','admin')->pluck('id')->toArray() ) ) ){
            $user = null ;
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានសិទ្ធិប្រើប្រាស់ប្រព័ន្ធនេះឡើយ។' ,
            ],403);
        }

        /** Just in case admin has deleted user from system with SoftDeletes */
        if (( $user = \App\Models\User::where(function($query) use($request, $email){
                $query->where('google_user_id', $request->sub)
                ->whereNotNull('google_user_id');
            })
            ->orWhere(function($query) use($request, $email){
                $query->where('phone', $request->phone)
                ->whereNotNull('phone');
            })
            ->orWhere(function($query) use($request, $email){
                $query->where('email', $email)
                ->whereNotNull('email');
            })->onlyTrashed()
            ->first() ) !== null) {
            /**
             * Check roles
             * Check whether the admin and super admin come to visit
             * This does not allow the admin and super admin to visit
             */
            if( $user != null && !empty( array_intersect( $user->roles->pluck('id')->toArray() , \App\Models\Role::where('name','super')->orWhere('name','admin')->pluck('id')->toArray() ) ) ){
                // if the deleted account is the admin or super admin type then this operation does not allow
                $user == null ;
                return response()->json([
                    'ok' => false ,
                    'message' => 'មិនមានសិទ្ធិប្រើប្រាស់ប្រព័ន្ធនេះឡើយ។' ,
                ],403);
            }else{
                $user->restore();
            }
        }

        if( $user != null ){
            $user
            ->update([
                'google_user_id' => $request->sub ,
                'google_user_lastname' => $request->family_name ,
                'google_user_firstname' => $request->given_name ,
                'google_user_fullname' => $request->name ,
                'google_user_picture' => $request->picture ,
                'google_user_email' => $email 
            ]);
        }else{
            /** If does not exist then create account for user */
            $user = \App\Models\User::create([
                'firstname' => $request->family_name,
                'lastname' => $request->given_name,
                'name' => $request->family_name . ' ' . $request->given_name,
                'email' => $email,
                'phone' => $request->phone != "" ? $request->phone : "",
                'password' => bcrypt('1234567890!@#$%^&*()'),
                'active' => 1,
                'google_user_id' => $request->sub,
                'google_user_email' => $email,
                'google_user_picture' => $request->picture ,
                'google_user_phone' => $request->phone != "" ? $request->phone : "",
                'google_user_lastname' => $request->family_name,
                'google_user_firstname' => $request->given_name,
                'google_user_fullname' => $request->name
            ]);
            $clientClientRole = \App\Models\Role::where('name','client')->orWhere('name','Client')->first();
            if( $clientClientRole != null ){
                $user->assignRole( $clientClientRole );
            }
        }

        /** Check member with his/her email or phone */
        if( $user->person == null ){
            if( $user->people_id > 0 && ( $person = \App\Models\People\People::find( $user->people_id )->onlyTrashed()->first() ) != null ){
                $person->restore();
                $person->update([
                    'firstname' => $user->firstname ,
                    'lastname' => $user->lastname ,
                    'email' => $user->email ,
                    'mobile_phone' => $user->phone
                ]);
            }else{
                $person = \App\Models\People\People::create([
                    'firstname' => $user->firstname ,
                    'lastname' => $user->lastname ,
                    'email' => $user->email ,
                    'mobile_phone' => $user->phone
                ]);
            }
            $user->people_id = $person->id;
            $user->save();
            $user->person;
        }
        /** Then create access token for api access */
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        /** Create user profile picture */
        if ( $user->avatar_url == null || strlen(trim($user->avatar_url))<1 || 
            (
                strlen(trim($user->avatar_url))>0 && !Storage::disk(env("FILESYSTEM_DRIVER","public"))->exists($user->avatar_url)
            )
        ) {
            $profile_picture = null;
            if (file_exists($request->picture) && ($content = file_get_contents($request->picture)) !== false) {
                $profile_picture = $content;
            } else {
                $avatar = new Avatar();
                $profile_picture = $avatar->create($user->name)->getImageObject()->encode('png');
            }
            $path = 'avatars/' . $user->id ;
            if( !Storage::disk('public')->exists( $path ) ){
                if( Storage::makeDirectory( $path ) ){
                    $uniqeName = md5( $user->name );
                    Storage::disk(env("FILESYSTEM_DRIVER","public"))->put( $path . '/'.$uniqeName.'.png', (string) $profile_picture, "public");
                    $user->avatar_url = $path . '/'.$uniqeName.'.png';
                    $user->save();
                }
            }
        }

        if( $user ){
            if( $user->avatar_url !== null && Storage::disk('public')->exists( $user->avatar_url ) ){
                $user->avatar_url = Storage::disk("public")->url( $user->avatar_url  );
            }
        }

        return response()->json([
            'ok' => true ,
            'upload_max_filesize' => ini_get("upload_max_filesize") ,
            'token' => [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ],
            'record' => $user ,
            'message' => 'ចូលប្រើប្រាស់បានជោគជ័យ !'
        ],200);
    }

}
