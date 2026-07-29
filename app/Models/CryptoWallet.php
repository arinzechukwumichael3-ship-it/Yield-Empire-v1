<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoWallet extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('user_id');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    protected $casts = [
        'is_active' => 'boolean',
        'color' => 'string',
    ];

    protected $appends = [
        'editData',
        'logo_image',
    ];

    public function getLogoImageAttribute()
    {
        if ($this->logo) {
            return asset('backend/images/crypto-logos/'.$this->logo);
        }

        return null;
    }

    public function getEditDataAttribute()
    {
        $data = [
            'id' => $this->id,
            'coin_name' => $this->coin_name,
            'symbol' => $this->symbol,
            'network' => $this->network,
            'wallet_address' => $this->wallet_address,
            'color' => $this->color ?? '#1D4ED8',
            'logo' => $this->logo,
            'purpose' => $this->purpose,
            'is_active' => $this->is_active,
        ];

        return json_encode($data);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
