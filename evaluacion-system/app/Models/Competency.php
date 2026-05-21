<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'evaluator_role',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
