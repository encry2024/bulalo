<?php

namespace App\Models\DryGood\GoodsReturn;

use Illuminate\Database\Eloquent\Model;
use App\Models\DryGood\GoodsReturn\Traits\Attribute\GoodsReturnAttribute;
use App\Models\DryGood\GoodsReturn\Traits\Relationship\GoodsReturnRelationship;

class GoodsReturn extends Model
{
	use GoodsReturnAttribute, GoodsReturnRelationship;

	protected $table = 'drygoods_returns';

    protected $fillable = ['id','inventory_id','date','quantity','cost','total_cost','reason','witness'];
}
