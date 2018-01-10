<?php

namespace App\Repositories\Backend\Category;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category\Category;
use App\Events\Backend\Category\CategoryCreated;
use App\Events\Backend\Category\CategoryDeleted;
use App\Events\Backend\Category\CategoryPermanentlyDeleted;
use App\Events\Backend\Category\CategoryRestored;
use App\Events\Backend\Category\CategoryUpdated;

class CategoryRepository extends BaseRepository
{
	const MODEL = Category::class;

	public function getForDataTable()
    {
		return $this->query();
	}

    public function create($input)
    {
        $data = $input['data'];

        $model = $this->createCategoryStub($data);

        DB::transaction(
            function () use($data, $model) {
                $model = Category::firstOrNew(['name' => $data['name']]);

                if ($model->exists) {
                    throw new GeneralException('Category already exists.');
                } else {
                    if($model->save()) {
                        event(new CategoryCreated($model));
                    }
                }
            }

            hrow new GeneralException('There was an error on creating the category.');
        )
    }

    protected function createCategoryStub($input)
    {
        $model = self::MODEL;
        $model = new $model;
        $model->name = $input['name'];

        return $model;
    }
}