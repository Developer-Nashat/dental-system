<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProblemTreatment extends Model
{
    /** @use HasFactory<\Database\Factories\ProblemTreatmentFactory> */
    use HasFactory, HasUuids;

    public $guarded = [];

    protected static function booted()
    {
        parent::boot();

        self::creating(function (ProblemTreatment $problemTreatment) {
            $problemTreatment->id = Str::uuid();
        });
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    public function problem()
    {
        return $this->belongsTo(ProblemCatalog::class);
    }

    public function tooth()
    {
        return $this->belongsTo(Tooth::class);
    }

    // public function patient()
    // {
    //     return $this->hasOneThrough(Patient::class, Visit::class);
    // }
}
