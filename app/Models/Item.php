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

	protected $fillable = [
		"id", "name", "stock_no", "unit",
		"place_origin", "brand", "amount", "quantity",
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

	public function equipItems()
	{
		return $this->hasMany(EmployeeItem::class);
	}

	public function histories()
	{
		return $this->hasMany(ItemHistory::class);
	}

	public function quantity()
	{
		$calculate = $this->quantity - $this->equipItems()->sum("quantity");
		if ($this->isSupply()) {
			return $calculate > 0 ? $calculate : 0;
		}
		return $this->quantity;
	}

	public function scopeEquipment($query)
	{
		return $query->where("item_type", Item::EQUIPMENT);
	}

	public function scopeSupply($query)
	{
		return $query->where("item_type", Item::SUPPLY);
	}

	public function scopeWorking($query)
	{
		return $query->where("status", Item::WORKING);
	}

	public function scopeRepair($query)
	{
		return $query->where("status", Item::REPAIR);
	}

	public function scopeDefective($query)
	{
		return $query->where("status", Item::DEFECTIVE);
	}

	public function itemType()
	{
		if ($this->item_type === Item::EQUIPMENT) {
			return "Equipment";
		}
		return "Supply";
	}

	public function itemColor()
	{
		if ($this->item_type === Item::EQUIPMENT) {
			return "blue";
		}
		return "dark";
	}

	public function itemStatus()
	{
		if ($this->status === Item::WORKING) {
			return "Working";
		} else if ($this->status === Item::REPAIR) {
			return "For Repair";
		}
		return "Defective";
	}

	public function itemStatusColor()
	{
		if ($this->status === Item::WORKING) {
			return "success";
		} else if ($this->status === Item::REPAIR) {
			return "warning";
		}
		return "danger";
	}

	public function isEquipment()
	{
		return $this->item_type === Item::EQUIPMENT;
	}

	public function isSupply()
	{
		return $this->item_type === Item::SUPPLY;
	}

	public function isWorking()
	{
		return $this->status === Item::WORKING;
	}

	public function isRepair()
	{
		return $this->status === Item::REPAIR;
	}

	public function isDefective()
	{
		return $this->status === Item::DEFECTIVE;
	}

	public function moneyFormat()
	{
		return number_format($this->amount, 2, ".");
	}
}
