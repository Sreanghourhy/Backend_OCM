<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThirdPartyAuthentication extends Model
{
    use SoftDeletes;
    const FACEBOOK = "FACEBOOK" ;
    const GOOGLE = "GOOGLE" ;
    const APP = "APPLE" ;
}
