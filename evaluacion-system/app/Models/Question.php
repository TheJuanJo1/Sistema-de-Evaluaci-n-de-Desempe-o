<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'competency_id',
        'text',
    ];

    public function competency()
    {
        return $this->belongsTo(Competency::class);
    }
}
