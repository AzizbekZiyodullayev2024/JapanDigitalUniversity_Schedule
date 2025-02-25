<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public $timestamps = true;

    protected $table = 'groups';

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'group_subject', 'group_id', 'subject_id')
            ->withTimestamps();
    }
    public function students()
    {
        return $this->belongsToMany(Subject::class, 'group_student', 'group_id', 'user_id')
            ->withTimestamps();
    }
}