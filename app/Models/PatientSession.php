<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientSession extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'title', 'session_date', 'start_time', 'end_time', 'description'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

