<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'patient_number',
        'address',
        'birth_date',
        'therapy_for',
        'information',
        'phone_number',
        'mothers_name',
        'fathers_name',
        'parents_email',
        'parents_work',
        'status',
        'school_type',
        'grade',
        'school_postcode'
    ];
    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function diagnoses()
    {
        return $this->belongsToMany(Diagnosis::class);
    }
    public function sessions()
    {
        return $this->hasMany(PatientSession::class);
    }
}
