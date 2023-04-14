<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalVoucherUpload extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'journal_voucher_uploads';
    protected $fillable = ['file_name','file_location','status','queue','bank_id','database_id'];
    protected $increment = false;

    public function banks()
    {
        return $this->belongsTo(Bank::class,'bank_id');
    }

    public function database()
    {
        return $this->belongsTo(Database::class,'database_id');
    }
}
