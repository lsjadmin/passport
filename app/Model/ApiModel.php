<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ApiModel extends Model
{
    //
    protected $table="p_api";
    public $timestamps = false;
    protected $primaryKey="api_id";
}

