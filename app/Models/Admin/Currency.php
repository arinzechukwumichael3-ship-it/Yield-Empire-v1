<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = [
        'both',
        'senderCurrency',
        'receiverCurrency',
        'editData',
    ];

    protected $casts = [
        'id'        => 'integer',
        'admin_id' => 'integer',
        'country'  => 'string',
        'name'     => 'string',
        'code'     => 'string',
        'symbol'   => 'string',
        'flag'     => 'string',
        'rate'     => 'decimal:16',
        'sender'   => 'integer',
        'receiver' => 'integer',
        'default'  => 'integer',
        'status'   => 'integer',
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    public function getBothAttribute() {
        if($this->sender == true && $this->receiver == true) {
            return true;
        }
        return false;
    }

    public function getSenderCurrencyAttribute() {
        if($this->sender == true) {
            return true;
        }
        return false;
    }

    public function getReceiverCurrencyAttribute() {
        if($this->receiver == true) {
            return true;
        }
        return false;
    }

    public function getEditDataAttribute() {
        $role = "";
        if($this->sender == true && $this->receiver == true) {
            $role = "both";
        }else if($this->sender == true && $this->receiver == false) {
            $role = "sender";
        }else if($this->receiver == true && $this->sender == false) {
            $role = "receiver";
        }
        $data = [
            'name'      => $this->name,
            'code'      => $this->code,
            'flag'      => $this->flag,
            'role'      => $role,
            'option'    => ($this->default == true) ? 1 : 0,
            'symbol'    => $this->symbol,
            'type'      => $this->type,
            'rate'      => get_amount($this->rate),
            'country'   => $this->country,
        ];

        return json_encode($data);
    }


    public function scopeDefault() {
        return $this->where('default', 1)->first() ?? false;
    }

    public function isDefault() {
        return $this->default == 1;
    }

    public function scopeActive($query) {
        return $query->where("status", 1);
    }

    public function scopeSender($query) {
        return $query->where("sender", 1);
    }

    public function scopeReceiver($query) {
        return $query->where("receiver", 1);
    }

    public function scopeRoleBoth($query) {
        return $query->where("sender", 1)->where('receiver', 1);
    }

    public function scopeRoleHasOne($query) {
        return $query->where(function($q) {
            $q->where('sender', 1);
        })->orWhere('receiver', 1);
    }

    public function getCurrencyCodeAttribute() {
        return $this->code;
    }
}
