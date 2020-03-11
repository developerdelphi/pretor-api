<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'origin'];

    // protected $dateFormat = 'd/m/Y H:';

    protected $casts = [
        'created_at' => 'date:d/m/Y H:m:s',
        'updated_at' => 'date:d/m/Y H:m:s',
    ];
}
