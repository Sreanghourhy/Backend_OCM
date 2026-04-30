<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use Illuminate\Foundation\File;
use Storage;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon as Carbon;
use \App\Models\Attendant\Attendant ;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'public_key' , 'firstname', 'lastname', 'email', 'password','phone', 'active', 'activation_token', 'avatar' , 'avatar_url' , 'username' , 'login_count' , 'last_login' , 'last_logout' , 'ip' , 'mac_address' , 'authenip' , 'authy_id' , 'people_id' , 'google_user_id' , 'google_user_email' , 'google_user_phone', 'google_user_picture' , 'google_user_fullname' , 'google_user_lastname' , 'google_user_firstname'  
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = ['deleted_at'];

    protected $is_admin = true;

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getName(){
      $user = $this->find($this->id);
      return $user->lastname.' '.$user->firstname;
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function roles(){
      return $this->belongsToMany('App\Models\Role','user_role','user_id','role_id');
    }
    public function attendants(){
      return $this->hasMany(\App\Models\Attendant\Attendant::class,'user_id','id');
    }
    public function timeslots(){
      return $this->belongsToMany(\App\Models\Attendant\Timeslot::class,'user_timeslots','user_id','timeslot_id');
    }
    /**
     * The actual working time as minutes exclude the rest period
     */
    public function totalActualWorkingHoursOfTimeslots(){
      return $this->timeslots->map(function($ts){ 
        return [
          'id' => $ts->id ,
          'title' => $ts->title ,
          'rest_duration' => intval( $ts->rest_duration ) ,
          'minutes' => $ts->getMinutes() 
        ];
      });
    }
    public function userTimeslots(){
      return $this->hasMany( \App\Models\Attendant\UserTimeslot::class , 'user_id' , 'id' );
    }
    public function organizationLeader()
    {
        return $this->belongsToMany('App\Models\Organization\Organization','organization_leader','people_id','organization_id');
    }
    /**
     * ឯកសារដែលគណនីមួយនេះបានបង្កើត
     */
    public function regulators(){
        return $this->hasMany(\App\Models\Regulator\Regulator::class,'created_by');
    }
    public function officer(){
      return $this->hasOne( \App\Models\Officer\Officer::class , 'user_id' ,'id' );
    }
    public function favorites(){
      return $this->belongsToMany('\App\Models\Regulator\Regulator','regulator_favorite','user_id','regulator_id');
    }
    public function signatures(){
      return $this->belongsToMany('\App\Models\Regulator\Tag\Signature','user_signatures','user_id','signature_id');
    }

    public function folders(){
      return $this->hasMany('\App\Models\Folder\Folder','people_id','id');
    }

    public function getIsAdminAttribute()
    {
        return true;
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function($obj) {
          if( $obj->image != null && strlen(trim( $obj->image ))) \Storage::disk('public')->delete($obj->image);
        });
    }
    /**
     * Route notifications for the authy channel.
     *
     * @return int
     */
    public function routeNotificationForAuthy()
    {
        /**
         * Notify the sms to user
         */
        // $this->notify(new \App\Notifications\PhoneVerificationNotification('sms', true));
        return $this->authy_id;
    }
    /**
     * Third party authentication
     */
    public function thirdPartyAuthentications(){
      return $this->hasMany(\App\ThirdPartyAuthentication::class,'user_id','id');
    }
    public function facebookAuthentication(){
        $this->thirdPartyAuthentications()->where('name',\App\ThirdPartyAuthentication::FACEBOOK)->first();
    }
    public function googleAuthentication(){
        $this->thirdPartyAuthentications()->where('name',\App\ThirdPartyAuthentication::GOOGLE)->first();
    }
    public function appleAuthentication(){
        $this->thirdPartyAuthentications()->where('name',\App\ThirdPartyAuthentication::APPLE)->first();
    }
    public function favoriteMatras(){
      return $this->belongsToMany( \App\Models\Law\Book\Matra::class , 'favorite_matras' , 'user_id' , 'matra_id' );
    }

}
