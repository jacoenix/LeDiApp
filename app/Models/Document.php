<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'folder_id', 'file_path', 'patient_id'];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

