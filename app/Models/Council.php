<?php

namespace App\Models;

use App\Policies\CouncilPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(CouncilPolicy::class)]
class Council extends Model
{
    protected $table = "councils";

    protected $fillable = [
        'name',
        'description',
        'status'
    ];
}
