<?php

namespace Ndlovu28\Lbtc\Model;

use Illuminate\Database\Eloquent\Model;

class LbtcSendBtc extends Model
{
    protected $fillable = ['trx_id', 'amount', 'to_address', 'status'];
}
