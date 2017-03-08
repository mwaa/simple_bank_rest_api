<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = [
        'bank_account_id', 'reason', 'amount'
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function($model) { //Update bank balance once deposit is created
            $account = BankAccount::find(1);
            $account->balance += $model->amount;
            $account->save();
        });
    }

    /**
     * Each Deposit belongs to particular bank account
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }
}
