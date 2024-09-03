<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['deleted_at'];

    public function inventory() {
        return $this->hasOne(Inventory::class, 'id', 'inventory_id');
    }

    public function transaction_category() {
        return $this->hasOne(TransactionCategory::class, 'id', 'transaction_category_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
