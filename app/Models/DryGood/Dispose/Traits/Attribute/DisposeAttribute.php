<?php

namespace App\Models\DryGood\Dispose\Traits\Attribute;

use App\Models\DryGood\Stock\Stock;

trait DisposeAttribute
{

	public function getEditButtonAttribute(){
		return '<a href="'.route('admin.dry_good.dispose.edit', $this->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.edit').'"></i></a> ';
	}

	public function getDeleteButtonAttribute(){
		return '<a href="'.route('admin.dry_good.dispose.destroy', $this).'"
                 data-method="delete"
                 data-trans-button-cancel="'.trans('buttons.general.cancel').'"
                 data-trans-button-confirm="'.trans('buttons.general.crud.delete').'"
                 data-trans-title="'.trans('strings.backend.general.are_you_sure').'"
                 class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.delete').'"></i></a> ';
	}

	public function getActionButtonsAttribute(){
		return $this->delete_button;
	}
}