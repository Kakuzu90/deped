<?php

namespace App\Models;

use App\Traits\HasDeletedScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;

class Employee extends User
{
    use HasFactory, HasDeletedScope;

    protected $fillable = [
        "full_name", "username",
        "password", "position_id",
        "office_id", "deleted_at",
        "verified_at",
    ];

    protected $hidden = [
        "password", "remember_token",
        "deleted_at", "created_at",
        "updated_at", 
    ];

    protected $casts = [
        "deleted_at" => "date",
        "verified_at" => "date"
    ];

    public function setFullNameAttribute($value) {
        return $this->attributes["full_name"] = strtolower($value);
    }

    public function getFullNameAttribute($value) {
        return $this->attributes["full_name"] = ucwords($value);
    }

    public function setPasswordAttribute($value) {
        return $this->attributes["password"] = Hash::make($value);
    }

    public function position() {
        return $this->belongsTo(Position::class);
    }

    public function office() {
        return $this->belongsTo(Office::class);
    }

    public function items() {
        return $this->hasMany(EmployeeItem::class);
    }

    public function onHand() {
        return $this->items()->where("status", EmployeeItem::ON_HAND);
    }

    public function returned() {
        return $this->items()->where("status", EmployeeItem::RETURNED);
    }

    public function accountText() {
        if ($this->verified_at) {
            return "Verified";
        }
        return "Pending";
    }

    public function accountColor() {
        if ($this->verified_at) {
            return "success";
        }
        return "danger";
    }
}
