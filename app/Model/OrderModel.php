<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    //
    protected $table="order";
    public $timestamps = false;
    protected $primaryKey="o_id";
}
