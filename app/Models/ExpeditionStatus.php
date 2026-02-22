<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpeditionStatus extends Model
{
    protected $table = "expedition_status";
    protected $fillable = ['status', 'slug', 'description'];
}
