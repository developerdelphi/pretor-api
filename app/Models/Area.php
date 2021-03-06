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

    public function kinds()
    {
        return $this->hasMany(Kind::class);
    }

    /**
     * Scope a query to only limit fields to response.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMinSelect($query)
    {
        return $query->select('id', 'name', 'origin');
    }
}
