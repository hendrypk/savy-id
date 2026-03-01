<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Wallet extends Model
{
    protected $fillable = ['uuid', 'user_id', 'name', 'type', 'balance', 'color'];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public const TYPES = [
        ['id' => 'cash', 'name' => 'Tunai'],
        ['id' => 'bank', 'name' => 'Rekening Bank'],
        ['id' => 'kartu kredit', 'name' => 'Kartu Kredit'],
        ['id' => 'e wallet', 'name' => 'E-Wallet'],
];

    protected static function booted() {
        static::creating(fn ($wallet) => $wallet->uuid = (string) Str::uuid());
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
        
    public function transactions() {
        return $this->hasMany(WalletTransaction::class);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
