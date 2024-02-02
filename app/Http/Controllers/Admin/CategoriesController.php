<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    // Categories List View
    public function getCategoriesView()
    {
        return view('admin.categories.category_list');
    }

    // Categories List AJAX
    public function getCategoriesList(Request $request)
    {
        if (isset($_GET['search']['value'])) {
            $search = $_GET['search']['value'];
        } else {
            $search = '';
        }
        if (isset($_GET['length'])) {
            $limit = $_GET['length'];
        } else {
            $limit = 10;
        }
        if (isset($_GET['start'])) {
            $offset = $_GET['start'];
        } else {
            $offset = 0;
        }
        $orderType = $_GET['order'][0]['dir'];
        $nameOrder = $_GET['columns'][$_GET['order'][0]['column']]['name'];
        $total = Category::count();
        $categories = Category::where('name', 'like', '%' . $search . '%')
            ->offset($offset)->limit($limit)
            ->orderBy('id', $orderType)
            ->get();

        $i = 1 + $offset;
        $data = [];
        foreach ($categories as $key => $category) {
            $data[] = array(
                $key + 1,
                $category->name,
                '<a href="javascript::void(0)" class="deleteCategory" data-id="' . $category->id . '"><i class="fa fa-trash text-danger"></i></a>| <a href="' . route('admin.category.detail', [$category->id]) . '" class="detailCategory" data-detail-id="' . $category->id . '"><i class="fa fa-eye"></i></a>| <a href="' . route('admin.category.edit', [$category->id]) . '" class="editCategory" data-detail-id="' . $category->id . '"><i class="fa fa-edit"></i></a>',
            );
        }
        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] = $total;
        $records['data'] = $data;
        return response()->json($records);
    }

    // Display the form to add a new category
    public function index()
    {
        return view('admin.categories.add');
    }

    // Store categories
    public function create(Request $request)
    {
        // Validation rules, you can customize these based on your requirements
        $rules = [
            'name' => 'required|string|max:255',
        ];

        // Validate the request data
        $request->validate($rules);

        $category = new Category();

        $category->name = $request->input('name');

        // Save the category to the database
        $category->save();

        return response()->json([
            'status' => true,
            'message' => 'Category added successfully.',
        ]);
    }

    // Edit Category View
    public function categoryEditView(Request $request)
    {
        $category = Category::findOrFail($request->category_id);

        $data = [
            'category' => $category,
        ];

        return view('admin.categories.edit', $data);
    }

    // Update Category
    public function updateCategory(Request $request)
    {
        $rules = [
            'name' => 'required',
            // Add more validation rules as needed
        ];

        $messages = [
            'name.required' => 'Category name is required',
            // Add more custom messages as needed
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $category = Category::find($request->id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found',
            ]);
        }

        $category->name = $request->name;
        // Update other fields as needed

        if ($category->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Category successfully updated',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update category',
            ]);
        }
    }

    // Delete Category
    public function deleteCategory(Request $request)
    {
        try {
            $category = Category::findOrFail($request->category);

            $delete = $category->delete();

            if ($delete) {
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully Deleted',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong, please try again',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, please try again',
            ]);
        }
    }

    // Category detail view
    public function categoryDetail(Request $request)
    {
        // Your implementation for displaying category details
    }
}
