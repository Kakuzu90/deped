<?php

namespace App\Models;

use App\Scopes\Deleted;
use App\Traits\HasDeletedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Office extends Model
{
    use HasFactory, HasDeletedScope;

    protected $fillable = [
        "name", "deleted_at"
    ];

    protected $hidden = [
        "deleted_at",
        "created_at",
        "updated_at"
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

    public function employees() {
        return $this->hasMany(Employee::class)->withoutGlobalScope([Deleted::class]);
    }
}
