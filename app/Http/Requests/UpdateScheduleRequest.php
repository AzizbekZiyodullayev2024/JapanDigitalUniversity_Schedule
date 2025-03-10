<?php

namespace App\Http\Requests;

use App\Models\Schedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateScheduleRequest extends FormRequest
{
    
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $schedule=$this->route('schedule');
        return [
            'subject_id'=>'required|exists:subjects,id',
            'teacher_id'=>[
                'required',
                'exists:users,id',
                Rule::unique('schedules', 'teacher_id')
                    ->where(fn ($query) => $query->where('pair', $this->pair)
                        ->where('week_day', $this->week_day)
                            ->where('date', $this->date))
                            ->ignore($schedule->id)
            ],
            'group_id'=>[
                'required',
                'exists:groups,id',
                Rule::unique('schedules', 'group_id')
                    ->where(fn ($query) => $query->where('pair', $this->pair)
                        ->where('week_day', $this->week_day)
                        ->where('date', $this->date))
                    ->ignore($schedule->id)
            ],
            'room_id'=>[
                'required',
                'exists:rooms,id',
                Rule::unique('schedules', 'group_id')
                    ->where(fn ($query) => $query->where('pair', $this->pair)
                        ->where('week_day', $this->week_day)
                        ->where('date', $this->date))
                    ->ignore($schedule->id)
            ],
            'pair'=>'required|integer|between:1,7',
            'week_day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'date'=>'required|date'
        ];

    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasDublicateSchedule()) {
                $validator->errors()->add('schedule', 'Schedule already exists');
            }
        });
    }

    public function hasDublicateSchedule(): bool
    {
        $schedule = $this->route('schedule');
        return Schedule::query()
            ->where('subject_id', $this->subject_id)
            ->where('teacher_id', $this->teacher_id)
            ->where('group_id', $this->group_id)
            ->where('room_id', $this->room_id)
            ->where('pair', $this->pair)
            ->where('week_day', $this->week_day)
            ->where('date', $this->date)
            ->where('id', '!=', $schedule ? $schedule->id : null)
            ->exists();
    }
}