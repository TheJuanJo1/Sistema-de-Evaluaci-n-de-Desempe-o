<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImprovementPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'aspects_to_improve',
        'worker_commitment',
        'status',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }
}
