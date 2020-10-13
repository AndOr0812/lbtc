<?php

namespace Ndlovu28\Lbtc\Model;

use Illuminate\Database\Eloquent\Model;

class Lbtc extends Model
{
    protected $fillable = [
    	'trx_id', 'ad_id', 'contact_id', 'amount', 'currency', 'status'
    ];
}
