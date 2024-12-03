<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visit extends Model
{
    /** @use HasFactory<\Database\Factories\VisitFactory> */
    use HasFactory, HasUuids;

    public $guarded = [];

    protected static function booted()
    {
        parent::boot();

        self::creating(function (Visit $visit) {
            $visit->id = Str::uuid();
        });
    }

    public function status(): HasOne
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }

    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class, 'id', 'patient_id');
    }


    public function dentist(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'dentist_id');
    }
}
