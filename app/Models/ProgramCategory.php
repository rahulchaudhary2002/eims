<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramCategory extends Model
{
    protected $table = 'program_categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function programs()
    {
        return $this->hasMany(Program::class, 'category_id');
    }
}
