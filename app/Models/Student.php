<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'unique_id',
        ];
    protected $table = 'users';

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_student', 'user_id', 'group_id');
    }
}