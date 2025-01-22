<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserModel extends Authenticatable implements JWTSubject
{
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    use HasFactory;

    protected $table = 'm_user'; //mendefinisikan nama tabel yang digunakan oleh model
    protected $primaryKey = 'user_id'; //mendefinisikan primary key dari table yang digunakan

    protected $fillable = ['level_id', 'username', 'nama', 'password', 'avatar', 'created_at', 'updated_at'];

    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed'];

    protected function avatar(){
        return Attribute::make(
            get: fn ($avatar) => url('images/avatars/'.$avatar)
        );
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(levelmodel::class, 'level_id', 'level_id');
    }

    public function getRoleName()
    {
        return $this->level->level_nama;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function hasRole($role)
    {
        return $this->level->level_kode == $role;
    }

    public function getRole()
    {
        return $this->level->level_kode;
    }
}