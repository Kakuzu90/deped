<?php

namespace App\Models;

use App\Scopes\Deleted;
use App\Traits\HasDeletedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeItem extends Model
{
    use HasFactory, HasDeletedScope;

    public const ON_HAND = 1;
    public const RETURNED = 2;
    public const TO_REPAIR = 3;

    protected $fillable = [
        "employee_id", "item_id",
        "status", "returned_at",
        "deleted_at"
    ];

    protected $hidden = [
        "deleted_at",
        "created_at",
        "updated_at"
    ];

    protected $casts = [
        "returned_at" => "date",
        "deleted_at" => "date"
    ];

    public function employee() {
        return $this->belongsTo(Employee::class)->withoutGlobalScope(Deleted::class);
    }

    public function item() {
        return $this->belongsTo(Item::class)->withoutGlobalScope(Deleted::class);
    }

    public function statusText() {
        if ($this->status === EmployeeItem::ON_HAND) {
            return "On Hand";
        }
        if ($this->status === EmployeeItem::RETURNED) {
            return "Returned";
        }
        return "To Repair";
    }

    public function statusColor() {
        if ($this->status === EmployeeItem::ON_HAND) {
            return "success";
        }
        if ($this->status === EmployeeItem::RETURNED) {
            return "warning";
        }
        return "danger";
    }
}
