<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function bank() {
    	return $this->belongsTo(BankInfo::class);
    }
}
