<?php

namespace App\Models;

use App\Scopes\Deleted;
use App\Traits\HasDeletedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemHistory extends Model
{
    use HasFactory, HasDeletedScope;

    public const BARROW = 1;
    public const RETURNED = 2;
    public const WORKING = 1;
    public const TO_REPAIR = 2;
    public const DEFECTIVE = 3;

    protected $fillable = [
        "employee_id", "item_id",
        "quantity", "type", "status",
        "deleted_at"
    ];

    protected $hidden = [
        "deleted_at",
        "created_at",
        "updated_at"
    ];

    protected $casts = [
        "deleted_at" => "date"
    ];

    public function employee() {
        return $this->belongsTo(Employee::class)->withoutGlobalScope([Deleted::class]);
    }

    public function item() {
        return $this->belongsTo(Item::class)->withoutGlobalScope([Deleted::class]);
    }

    public function itemType() {
        if ($this->type === ItemHistory::BARROW) {
            return "Borrowed";
        }
        return "Returned";
    }

    public function itemColor() {
        if ($this->type === ItemHistory::BARROW) {
            return "primary";
        }
        return "secondary";
    }

    public function itemStatus() {
        if ($this->status === ItemHistory::WORKING) {
            return "Working";
        }else if ($this->status === ItemHistory::TO_REPAIR) {
            return "To Repair";
        }
        return "Defective";
    }

    public function itemStatusColor() {
        if ($this->status === ItemHistory::WORKING) {
            return "success";
        }else if ($this->status === ItemHistory::TO_REPAIR) {
            return "warning";
        }
        return "danger";
    }
}
