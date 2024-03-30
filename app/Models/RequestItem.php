<?php

namespace App\Models;

use App\Scopes\Deleted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
	use HasFactory;

	public const PENDING = 1;
	public const ACCEPTED = 2;
	public const REJECTED = 3;

	protected $fillable = [
		"request_id", "item_id", "quantity",
		"status"
	];

	protected $hidden = [
		"created_at", "updated_at"
	];

	public function request()
	{
		return $this->belongsTo(Request::class)->withoutGlobalScope(Deleted::class);
	}

	public function item()
	{
		return $this->belongsTo(Item::class)->withoutGlobalScope(Deleted::class);
	}

	public function statusText()
	{
		if ($this->status === RequestItem::PENDING) {
			return "Pending";
		}
		if ($this->status === RequestItem::ACCEPTED) {
			return "Accepted";
		}

		return "Rejected";
	}

	public function statusColor()
	{
		if ($this->status === RequestItem::PENDING) {
			return "warning";
		}
		if ($this->status === RequestItem::ACCEPTED) {
			return "success";
		}

		return "danger";
	}
}
