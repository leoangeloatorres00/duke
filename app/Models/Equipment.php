<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['serial_number', 'description', 'condition', 'user_id'])]
class Equipment extends Model
{
    protected $table = 'equipment';
    
    protected $primaryKey = 'equipment_id';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function registeredEquipment(): HasOne
    {
        return $this->hasOne(RegisteredEquipment::class, 'equipment_id', 'equipment_id');
    }
}
