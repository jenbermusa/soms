<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MinorPenalty extends Model
{
    use HasFactory;

    protected $table = 'minor_penalties';

    protected $fillable = [
        'minor_penalties'
    ];
}
