<?php

namespace App\Http\Controllers\Backend\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Http\Requests\Backend\Category\ManageRequest;
use App\Repositories\Backend\Category\CategoryRepository;


class CategoryController extends Controller
{
    protected $categories;

    public function __construct(CategoryRepository $categories)
    {
        $this->categories = $categories;
    }

    public function index()
    {
    	$categories = Category::all();

    	return view('backend.category.index', compact('categories'));
    }

    public function create()
    {
    	return view('backend.category.create');
    }

    public function store(ManageRequest $request)
    {
        $this->categories->create
    	Category::create($request->all());

    	return redirect()->route('admin.category.index')->withFlashSuccess('Category has been Added!');
    }

    public function destroy(Category $category)
    {
    	$category->delete();

    	return redirect()->route('admin.category.index')->withFlashDanger('Category has been Deleted!');
    }
}
