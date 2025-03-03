<?php

namespace App\Models;

use Database\Factories\ScheduleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /** @use HasFactory<ScheduleFactory> */
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'subject_id',
        'teacher_id',
        'group_id',
        'room_id',
        'pair',
        'week_day',
        'date',
        ];
}