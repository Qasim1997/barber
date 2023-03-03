<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    protected $fillable = ['day', 'start_time', 'end_time', 'time_id'];

    protected $with = ['time'];

    public function time()
    {
        return $this->belongsTo(Time::class);
    }
}
