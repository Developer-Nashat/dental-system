<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProblemTreatment extends Model
{
    /** @use HasFactory<\Database\Factories\ProblemTreatmentFactory> */
    use HasFactory, HasUuids;

    public $guarded = [];

    public function visit()
    {
        return $this->hasOne(Visit::class);
    }

    public function treatment()
    {
        return $this->hasOne(Treatment::class);
    }

    public function problem()
    {
        return $this->hasOne(ProblemCatalog::class);
    }

    public function tooth()
    {
        return $this->hasOne(Tooth::class);
    }

    public function patient()
    {
        return $this->hasOneThrough(Patient::class, Visit::class);
    }
}
