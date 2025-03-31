<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN = 1;
    const ROLE_STAFF = 2;
    const ROLE_CUSTOMER = 3;



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'email',
        'phone_number',
        'birthday',
        'password',
        'gender',
        'avatar',
        'role_id',
        'status',
        'email_verified_at',
        'verified_at',
        'google_id',
        'loyalty_points'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function review()
    {
        return $this->hasMany(Review::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
    public function doctor()
    {
        return $this->hasMany(Doctor::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function bill()
    {
        return $this->hasMany(Bill::class);
    }
    public function appoinment()
    {
        return $this->hasMany(Appoinment::class);
    }
    public function appoinmentHistory()
    {
        return $this->hasMany(AppoinmentHistory::class);
    }
    public function address()
    {
        return $this->hasOne(UserAddress::class, 'user_id', 'id');
    }
}
