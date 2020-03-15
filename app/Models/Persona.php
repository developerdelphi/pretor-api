<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'qualifications', 'address', 'phones'];

    protected $casts = [
        'qualifications' => 'json',
        'address' => 'json',
        'phones' => 'json',

    ];
    // TODO: Pesquisar sobre overflow em filds json: https://github.com/CraftLogan/Laravel-Overflow

    public function scopeMinSelect($query)
    {
        return $query->select('id', 'name', 'qualifications', 'address', 'phones');
    }
}
