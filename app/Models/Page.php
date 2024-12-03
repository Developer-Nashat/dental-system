<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Page extends Model
{
    use HasFactory;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'page_user');
    }

    // public function permissions(): BelongsToMany
    // {
    //     return $this->belongsToMany(User::class, 'page_permission_user');
    // }
}
