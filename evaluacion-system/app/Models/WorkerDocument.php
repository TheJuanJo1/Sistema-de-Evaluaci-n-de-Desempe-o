<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'name',
        'path',
        'type',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
