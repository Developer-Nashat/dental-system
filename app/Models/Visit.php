<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visit extends Model
{
    use HasFactory, HasUuids;

    public $guarded = [];

    protected static function booted()
    {
        parent::boot();

        self::creating(function (Visit $visit) {
            $visit->id = Str::uuid();
        });
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function dentist()
    {
        return $this->belongsTo(User::class, 'dentist_id', 'id');
    }

    public function problemTreatments(): HasMany
    {
        return $this->hasMany(ProblemTreatment::class);
    }

    public function treatments()
    {
        return $this->hasManyThrough(Treatment::class, ProblemTreatment::class, 'visit_id', 'id', 'id', 'treatment_id');
    }

    public function teeth()
    {
        return $this->hasManyThrough(
            Tooth::class,
            ProblemTreatment::class,
            'visit_id', // Foreign key on ProblemTreatment table
            'id',       // Foreign key on Tooth table
            'id',       // Local key on Visit table
            'tooth_id'  // Local key on ProblemTreatment table
        );
    }

    public function problems()
    {
        return $this->hasManyThrough(
            ProblemCatalog::class,
            ProblemTreatment::class,
            'visit_id',
            'id',
            'id',
            'problem_id'
        );
    }
}
