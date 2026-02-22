<?php

namespace App\Models;

use App\Policies\ExpeditionPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UsePolicy(ExpeditionPolicy::class)]
class Expedition extends Model
{
    protected $fillable = [
        'kingdom_id',
        'status_id',
        'user_id',
        'start_date',
        'artifacts',
        'note',
        'rejection_reason'
    ];


    public function status(): BelongsTo
    {
        return $this->belongsTo(ExpeditionStatus::class);
    }

    public function kingdom(): BelongsTo
    {
        return $this->belongsTo(Kingdom::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
