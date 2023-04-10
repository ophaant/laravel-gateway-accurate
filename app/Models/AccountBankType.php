<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountBankType extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'account_bank_types';
    protected $fillable = ['account_type_name'];
    protected $increment = false;
}
