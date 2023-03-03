<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'username', 'gender', 'age', 'experience', 'location', 'status', 'created_by'];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
