<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtraService extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'price', 'img_name',
    ];
}
