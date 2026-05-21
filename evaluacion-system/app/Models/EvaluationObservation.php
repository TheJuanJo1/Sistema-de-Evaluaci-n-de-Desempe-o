<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationObservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'user_id',
        'observation',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
