<?php



namespace App;



use Carbon\Carbon;

use Hash;

use Illuminate\Auth\Notifications\ResetPassword;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use Laravel\Passport\HasApiTokens;
use Auth;




class User extends Authenticatable

{

    use  Notifiable, HasApiTokens;

    //use SoftDeletes;



    public $table = 'users';



    protected $hidden = [

        'password',

        'remember_token',

    ];



    protected $dates = [

        'updated_at',

        'created_at',

        'deleted_at',

        'email_verified_at',

    ];



    protected $fillable = [

        'name',
        'first_name',
        'last_name',
        'dob',

        'email',

        'phone',

        'password',

        'region',

        'last_login',

        'created_at',

        'updated_at',

        'deleted_at',

        'remember_token',

        'customer_id',
        'avg_rating',
        'register_token',

        'email_verified_at',
        'domestic_postcode',

    ];

    public function setEmailVerifiedAtAttribute($value)
    {

        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }


    public function setCreatedtAttribute($value)
    {
        $this->attributes['created_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setupdatedatAttribute($value)
    {
        $this->attributes['updated_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {

            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;

        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function userDetails()
    {
        return $this->hasOne(UserDetail::class,"user_id");
    }

    public function cartData()
    {
        return $this->hasMany(Carts::class,"user_id");
    }

    public function getOrder()
    {
        return $this->hasMany("App\Models\admin\Order")->orderBy('id','desc');
    }

    public function billingAddress()
    {
        return $this->hasOne(BillingAddress::class,'user_id');
    }

    public function giftcard()
    {
        return $this->belongsTo(GiftCard::class,'product_id');

    } 

    public function plan()
    {
        return $this->hasMany(UserPlan::class,'user_id');

    } 


}

