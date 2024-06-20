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
        $salesRepName = $this->x_studio_sales_rep_1;
        return $this->hasOne(User::class, 'name', 'x_studio_sales_rep_1')->where('name', $salesRepName);
    }
}
