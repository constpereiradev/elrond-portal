<?php

namespace App\Models;

use App\Policies\KingdomPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(KingdomPolicy::class)]
class Kingdom extends Model
{
    protected $table = "kindoms";

    protected $fillable = [
        'name',
        'description',
        'status'
    ];
}
