<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryBank extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'category_banks';
    protected $fillable = ['id','category_bank_name'];

    public $incrementing = false;
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
    public function scopeFindCategoryByName(Builder $query,string $name): void
    {
        $query->where('category_bank_name', $name);
    }
    public function banks()
    {
        return $this->hasMany(Bank::class);
    }
}
