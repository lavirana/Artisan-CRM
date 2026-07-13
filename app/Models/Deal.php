<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deal extends Model
{
    protected $fillable = [
      'title', 'value', 'stage', 'contact_id', 'company_id', 'user_id', 'closed_at', 'expected_close_date', 'status'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'closed_at' => 'datetime',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function assignee(): HasMany
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
