<?php

namespace App\Models\DryGood\History;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'drygood_history';

    protected $fillable = ['id', 'product_id', 'description'];
}
