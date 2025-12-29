<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminAuditLog extends Model
{
    protected $fillable = [
        'admin_id',
        'target_user_id',
        'action',
        'entity_type',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'notes'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public static function logAction(string $action, ?User $targetUser = null, array $oldValues = [], array $newValues = [], string $notes = null)
    {
        $request = request();
        
        return static::create([
            'admin_id' => auth()->id(),
            'target_user_id' => $targetUser?->id,
            'action' => $action,
            'entity_type' => 'user',
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'notes' => $notes
        ]);
    }
}
