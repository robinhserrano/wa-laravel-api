<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function orderLine()
    {
        return $this->hasMany(OrderLine::class);
    }

    public function user()
    {
        // No foreign key, filter by name (one-to-one relationship)
        return $this->belongsTo(User::class, 'x_studio_sales_rep_1', 'name');
        // $salesRepName = $this->x_studio_sales_rep_1;
        // return $this->hasOne(User::class)->where('name', $salesRepName);
    }
}
