<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'page_permission_user');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'page_permission_user');
    }
}
