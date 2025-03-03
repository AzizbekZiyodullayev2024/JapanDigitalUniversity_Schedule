<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{

    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'name',
        ];
    protected $table = 'subjects';

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_subject', 'subject_id', 'group_id')
            ->withTimestamps();
    }
    public function teachers()
    {
        return $this->belongsToMany(Group::class, 'subject_teacher', 'user_id', 'subject_id')
            ->withTimestamps();
    }
}