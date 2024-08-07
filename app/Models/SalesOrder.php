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
        // return $this->belongsTo(User::class, 'x_studio_sales_rep_1', 'name'); ///ORIG WORKING
        //return $this->hasOne(User::class, 'name', 'x_studio_sales_rep_1')->where('name', 'like', '%$x_studio_sales_rep_1%');
        // $salesRepName = $this->x_studio_sales_rep_1;
        // return $this->hasOne(User::class)->where('id', 'user_id');
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // public function getMatchingUser()
    // {
    //     $salesRepName = $this->x_studio_sales_rep_1;
    //     return User::where('name', 'like', "%$salesRepName%")->first();
    // }
}
