<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreScheduleRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        
        return [
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => [
                'required',
                'exists:users,id',
                Rule::unique('schedules', 'teacher_id')
                    ->where(fn ($query) => $query->where('pair', $this->pair)
                        ->where('week_day', $this->week_day)
                        ->where('date', $this->date))
            ],
            'group_id' => [
                'required',
                'exists:groups,id',
                Rule::unique('schedules', 'group_id')
                    ->where(fn ($query) => $query->where('pair', $this->pair)
                        ->where('week_day', $this->week_day)
                        ->where('date', $this->date))
            ],
            'room_id' => [
                'required',
                'exists:rooms,id',
                Rule::unique('schedules', 'room_id') // Fixed from 'group_id'
                ->where(fn ($query) => $query->where('pair', $this->pair)
                    ->where('week_day', $this->week_day)
                    ->where('date', $this->date))
            ],
            'pair' => 'required|integer|between:1,7',
            'week_day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'date' => 'required|date'
        ];
    }
}