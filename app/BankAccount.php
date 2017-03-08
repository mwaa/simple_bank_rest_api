<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{

    protected $fillable = [
        'name', 'address', 'telephone', 'balance'
    ];


    /**
     * A bank account has many withdrawals
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'bank_account_id', 'id');
    }

    /**
     * A bank account has many deposits
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deposits()
    {
        return $this->hasMany(Deposit::class, 'bank_account_id', 'id');
    }
}
