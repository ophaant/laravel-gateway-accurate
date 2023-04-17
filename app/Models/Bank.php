<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'banks';
    protected $fillable = ['account_id', 'account_number', 'account_name', 'category_bank_id', 'account_type_id'];

    protected $hidden = ['account_type_id', 'category_bank_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function category()
    {
        return $this->belongsTo(CategoryBank::class, 'category_bank_id');
    }

    public function accountType()
    {
        return $this->belongsTo(AccountBankType::class, 'account_type_id');
    }

    public function journalVoucherUploads()
    {
        return $this->hasMany(JournalVoucherUpload::class);
    }
}
