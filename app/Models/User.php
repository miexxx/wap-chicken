<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tanmo\Search\Traits\Search;
use App\Models\Storehouse;

class User extends Authenticatable implements JWTSubject
{
    use Search,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'avatarUrl','nickname', 'gender', 'country', 'province', 'city', 'birthday', 'phone'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function authWechat()
    {
        return $this->hasOne(UserAuthWechat::class);
    }

    public function wallet(){
        return $this->hasOne(Wallet::class,'user_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany(Item::class, 'favorites', 'user_id', 'item_id');
    }

    public function coupons(){
        return $this->belongsToMany(Coupon::class,'user_coupons','user_id','coupon_id')->withPivot('end_time','status');
    }

    public function usecoupons(){
        return $this->belongsToMany(Coupon::class,'user_coupons','user_id','coupon_id')->where('user_coupons.end_time','>=',date('Y-m-d H:i:s'))->withPivot('end_time','status');
    }

    public function cats()
    {
        return $this->hasMany(Cat::class,'user_id','id');
    }

    public function eggs(){
        return $this->hasMany(Storehouse::class,'user_id','id')->where('type',Storehouse::EGG);
    }

    public  function chickens(){
        return $this->hasMany(Storehouse::class,'user_id','id')->where('type',Storehouse::CHINCKEN);
    }

    public function supports(){
        return $this->hasMany(Support::class,'user_id','id');
    }

    public function childrens()
    {
        return $this->hasMany(User::class,'parent_id','id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
        return [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userCode(){
        return $this->hasMany(UserCode::class,'create_uid','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userLevel() {
        return $this->belongsTo(UserLevel::class,'is_shared','level');
    }

    public function defaultAdd(){
        return $this->hasOne(Address::class,'user_id','id')->where('type',Address::DEFALUT_ADD);
    }

    public function userPromote() {
        return $this->hasMany(UserPromote::class,'user_id','id');
    }

    public function userInvite() {
        return $this->hasMany(UserInvite::class,'user_id','id');
    }

    public function parent() {
        return $this->belongsTo(User::class,'parent_id','id');
    }
}
