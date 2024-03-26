<?php

namespace App\Models;

use App\Traits\HasDeletedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory, HasDeletedScope;

    public const EQUIPMENT = 1;
    public const SUPPLY = 2;
    public const WORKING = 1;
    public const REPAIR = 2;
    public const DEFECTIVE = 3;

    public $incrementing = false;

    public static function booted() : void {
        static::creating(function (Item $item) {
            $item->id = str_pad(mt_rand(0, 99999999999), 11, '0', STR_PAD_LEFT);
            while (Item::where("id", $item->id)->exists()) {
                $item->id = str_pad(mt_rand(0, 99999999999), 11, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $fillable = [
        "id", "name", "serial_no",
        "model_no", "brand", "amount", "quantity",
        "item_type", "status", "purchased_at",
        "deleted_at"
    ];

    protected $hidden = [
        "deleted_at", "created_at",
        "updated_at"
    ];

    protected $casts = [
        "purchased_at" => "date",
        "deleted_at" => "date"
    ];

    public function setNameAttribute($value) {
        return $this->attributes["name"] = strtolower($value);
    }

    public function getNameAttribute($value) {
        return $this->attributes["name"] = ucwords($value);
    }

    public function scopeEquipment($query) {
        return $query->where("item_type", Item::EQUIPMENT);
    }

    public function scopeSupply($query) {
        return $query->where("item_type", Item::SUPPLY);
    }
    
    public function scopeWorking($query) {
        return $query->where("status", Item::WORKING);
    }

    public function scopeRepair($query) {
        return $query->where("status", Item::REPAIR);
    }

    public function scopeDefective($query) {
        return $query->where("status", Item::DEFECTIVE);
    }

    public function itemType() {
        if ($this->item_type === Item::EQUIPMENT) {
            return "Equipment";
        }
        return "Supply";
    }

    public function itemColor() {
        if ($this->item_type === Item::EQUIPMENT) {
            return "primary";
        }
        return "secondary";
    }

    public function itemStatus() {
        if ($this->status === Item::WORKING) {
            return "Working";
        }else if ($this->status === Item::REPAIR) {
            return "For Repair";
        }
        return "Defective";
    }

    public function itemStatusColor() {
        if ($this->status === Item::WORKING) {
            return "success";
        }else if ($this->status === Item::REPAIR) {
            return "warning";
        }
        return "danger";
    }

    public function moneyFormat() {
        return number_format($this->amount, 2, ".");
    }
}
