<?php

namespace App\Events\Backend\Category;

use Illuminate\Queue\SerializesModels;

/**
* Class CategoryCreated.
*/
class CategoryCreated
{
    use SerializesModels;

    /**
     * @var
     */
    public $category;

    /**
     * @param $category
     */
    public function __construct($category)
    {
        $this->category = $category;
    }
}
