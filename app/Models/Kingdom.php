<?php

namespace App\Models;

use App\Policies\KingdomPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UsePolicy(KingdomPolicy::class)]
class Kingdom extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status'
    ];
}
