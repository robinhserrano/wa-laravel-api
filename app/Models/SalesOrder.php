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
        // Define the relationship with the User model
        return $this->belongsTo(User::class, 'x_studio_sales_rep_1', 'id');
    }
}
