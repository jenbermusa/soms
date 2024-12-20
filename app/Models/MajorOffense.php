<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MajorOffense extends Model
{
    use HasFactory;

    protected $table = 'major_offenses';

    protected $fillable = [
        'major_offenses'
    ];
}
