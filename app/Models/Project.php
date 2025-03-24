<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Bavix\Wallet\Interfaces\WalletFloat;
use Illuminate\Database\Eloquent\Model;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\CanPay;

/**
 * Class Project.
 *
 * @property int         $id
 * @property string      $code
 * @property string      $name
 *
 * @method int getKey()
 */
class Project extends Model implements Wallet, WalletFloat, Customer
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasWalletFloat;
    use HasFactory;
    use CanPay;

    protected $fillable = [
        'code',
        'name'
    ];
}
