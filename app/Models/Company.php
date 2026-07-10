<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Company extends Model
{
    protected $fillable = [ 'name', 'website', 'industry', 'phone', 'address', 'user_id'];

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
