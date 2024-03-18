<?php

namespace App\Models;

use App\Traits\HasDeletedScope;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends User
{
    use HasFactory, HasDeletedScope;

    protected $fillable = [
        "name",
        "username",
        "password",
        "deleted_at"
    ];

    protected $hidden = [
        "created_at",
        "update_at",
        "deleted_at",
        "password",
        "remember_token"
    ];

    protected $casts = [
        "deleted_at" => "date"
    ];

    public function setNameAttribute($value) {
        return $this->attributes["name"] = strtolower($value);
    }

    public function getNameAttribute($value) {
        return $this->attributes["name"] = ucwords($value);
    }

    public function setPasswordAttribute($password) {
        return $this->attributes["password"] = Hash::make($password);
    }
}
