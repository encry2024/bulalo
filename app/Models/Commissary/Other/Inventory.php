<?php

namespace App\Models\Commissary\Other;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'commissary_other_inventories';

    protected $fillable = ['name'];
}
