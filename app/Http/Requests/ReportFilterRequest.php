<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }
    
    public function getStartDate(): Carbon
    {
        return $this->filled('start_date') 
            ? Carbon::parse($this->start_date)->startOfDay() 
            : Carbon::now()->startOfMonth();
    }
    
    public function getEndDate(): Carbon
    {
        return $this->filled('end_date') 
            ? Carbon::parse($this->end_date)->endOfDay() 
            : Carbon::now()->endOfDay();
    }
}
