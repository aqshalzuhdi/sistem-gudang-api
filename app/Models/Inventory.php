<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['deleted_at'];

    public function product() {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function status() {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }
}
