<?php

namespace App\Models;

use App\Scopes\Deleted;
use App\Traits\HasDeletedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Request extends Model
{
	use HasFactory, HasDeletedScope;

	public const TO_REPAIR = 1;
	public const TO_BARROW = 2;
	public const TO_RETURN = 3;
	public const PENDING = 1;
	public const ACCEPTED = 2;
	public const REJECTED = 3;

	protected $fillable = [
		"employee_id",
		"request_type", "accepted_by", "released_by",
		"status", "accepted_at", "released_at",
		"deleted_at",
	];

	protected $hidden = [
		"deleted_at", "created_at",
		"updated_at",
	];

	protected $casts = [
		"accepted_at" => "date",
		"released_at" => "date",
		"deleted_at" => "date"
	];

	public function employee()
	{
		return $this->belongsTo(Employee::class)->withoutGlobalScope(Deleted::class);
	}

	public function items()
	{
		return $this->hasMany(RequestItem::class);
	}

	public function acceptedBy()
	{
		return $this->belongsTo(Admin::class, "accepted_by", "id")->withoutGlobalScope(Deleted::class);
	}

	public function releasedBy()
	{
		return $this->belongsTo(Admin::class, "released_by", "id")->withoutGlobalScope(Deleted::class);
	}

	public function scopeMyRequest($query)
	{
		return $query->where("employee_id", Auth::guard("employee")->id());
	}

	public function scopePending($query)
	{
		return $query->where("status", Request::PENDING);
	}

	public function scopeAccepted($query)
	{
		return $query->where("status",  Request::ACCEPTED);
	}

	public function scopeRejected($query)
	{
		return $query->where("status",  Request::REJECTED);
	}

	public function scopeBarrow($query)
	{
		return $query->where("request_type",  Request::TO_BARROW);
	}

	public function scopeRepair($query)
	{
		return $query->where("request_type",  Request::TO_REPAIR);
	}

	public function scopeReturned($query)
	{
		return $query->where("request_type",  Request::TO_RETURN);
	}

	public function requestType()
	{
		if ($this->request_type === Request::TO_REPAIR) {
			return "To Repair";
		} else if ($this->request_type === Request::TO_RETURN) {
			return "To Return";
		}
		return "To Barrow";
	}

	public function requestTypeColor()
	{
		if ($this->request_type === Request::TO_REPAIR) {
			return "danger";
		} else if ($this->request_type === Request::TO_RETURN) {
			return "warning";
		}
		return "success";
	}

	public function requestStatus()
	{
		if ($this->status === Request::PENDING) {
			return "Pending";
		} else if ($this->status === Request::ACCEPTED) {
			return "Accepted";
		}
		return "Rejected";
	}

	public function requestStatusColor()
	{
		if ($this->status === Request::PENDING) {
			return "warning";
		} else if ($this->status === Request::ACCEPTED) {
			return "success";
		}
		return "danger";
	}

	public function isPending()
	{
		return $this->status === Request::PENDING;
	}

	public function isAccepted()
	{
		return $this->status === Request::ACCEPTED;
	}

	public function isRejected()
	{
		return $this->status === Request::REJECTED;
	}

	public function isToBarrow()
	{
		return $this->request_type === Request::TO_BARROW;
	}

	public function isToRepair()
	{
		return $this->request_type === Request::TO_REPAIR;
	}

	public function isToReturn()
	{
		return $this->request_type === Request::TO_RETURN;
	}
}
