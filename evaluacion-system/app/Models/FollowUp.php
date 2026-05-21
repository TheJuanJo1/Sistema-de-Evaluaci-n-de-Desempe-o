<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'improvement_plan_id',
        'user_id',
        'comments',
        'follow_up_date',
    ];

    protected $casts = [
        'follow_up_date' => 'datetime',
    ];

    public function plan()
    {
        return $this->belongsTo(ImprovementPlan::class, 'improvement_plan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
