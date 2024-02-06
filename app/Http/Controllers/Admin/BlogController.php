<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function fetchCategories()
    {
        $categories = Category::all();
        return response()->json(['categories' => $categories]);
    }

    public function getBlogView()
    {
        return view('admin.blogs.blog_list');
    }

    public function getBlogLists(Request $request)
    {
        $search = $request->input('search.value');
        $limit = $request->input('length', 25);
        $offset = $request->input('start', 0);
        $orderType = $request->input('order.0.dir', 'asc');
        $nameOrder = $request->input('columns.' . $request->input('order.0.column') . '.name', 'id');

        $blogsQuery = Blog::with(['categories', 'tags', 'blogImages'])
            ->select(['blogs.*']);

        if ($search) {
            $blogsQuery->where('title', 'like', '%' . $search . '%');
        }

        $total = $blogsQuery->count();

        $blogs = $blogsQuery->orderBy($nameOrder, $orderType)
            ->offset($offset)
            ->limit($limit)
            ->get();

        $data = [];
        foreach ($blogs as $key => $blog) {
            $categories = $blog->categories->pluck('name')->toArray();
            $tags = $blog->tags->pluck('name')->toArray();
            $images = [];
            foreach ($blog->blogImages as $image) {
                $images[] = $image->getImageUrl();
            }
            $data[] = [
                'id' => $blog->id,
                'title' => $blog->title,
                'description' => $blog->description,
                'category' => $blog->category->name,
                'tags' => $tags,
                'images' => $images,
                'action' => '<a href="javascript::void(0)" class="deleteBlog" data-id="' . $blog->id . '"><i class="fa fa-trash text-danger"></i></a> | <button class="editBlog" data-id="' . $blog->id . '"><i class="fa fa-edit"></i></button>',
            ];
        }

        $records['draw'] = $request->input('draw');
        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] = $total;
        $records['data'] = $data;

        return response()->json($records);
    }

    public function index()
    {
        $categories = Category::all(); // Fetch all categories

        return view('admin.blogs.add', compact('categories'));
    }

    public function create(Request $request)
    {
        $rule = [
            'title' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|exists:categories,id',
            'tags' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ];

        $custom = [
            'title.required' => 'Title is required',
            'description.required' => 'Description is required',
            'category.required' => 'Category is required',
        ];

        $validator = Validator::make($request->all(), $rule, $custom);

        if ($validator->fails()) {
            return $this->sendWebResponse(false, $validator->errors()->first());
        }

        try {
            $blog = new Blog();
            $blog->title = $request->input('title');
            $blog->description = $request->input('description');

            $insert = $blog->save();

            $categoryId = $request->input('category');
            $blog->category_id = $categoryId;

            $blog->save();

            if ($request->has('tags')) {
                $tagNames = explode(',', $request->input('tags'));
                $tagIds = [];

                foreach ($tagNames as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
                    $tagIds[] = $tag->id;
                }

                $blog->tags()->attach($tagIds);
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    $filename = time() . '_' . uniqid() . '_' . $key . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('public/blog_images', $filename);
                    $blog->blogImages()->create(['image_path' => 'blog_images/' . $filename]);
                }
            }

            if ($insert) {
                return redirect()->route('admin.blogs.list.view')->with([
                    'status' => true,
                    'message' => 'Blog created successfully',
                ]);
            } else {
                return redirect()->route('admin.blogs.add')->with([
                    'status' => false,
                    'message' => 'Something went wrong, please try again',
                ]);
            }
        } catch (Exception $ex) {
            Log::error($ex);
            return $this->sendWebResponse(false, "Something went wrong, please try again");
        }
    }

    public function getEdit(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.blogs.edit', compact('blog', 'categories', 'tags'));
    }

    public function postUpdate(Request $request, Blog $blog)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|exists:categories,id',
            'tags' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Update blog data
        $blog->title = $request->input('title');
        $blog->description = $request->input('description');

        // Update category
        $categoryId = $request->input('category');
        $blog->category_id = $categoryId;

        $blog->save();

        // Update tags
        if ($request->filled('tags')) {
            $tagNames = explode(',', $request->input('tags'));
            $tagIds = [];

            foreach ($tagNames as $tagName) {
                $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
                $tagIds[] = $tag->id;
            }

            $blog->tags()->sync($tagIds);
        }

        // Update images
        if ($request->hasFile('images')) {
            $uploadedImages = [];

            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('public/blog_images', $filename);
                $uploadedImages[] = ['image_path' => 'blog_images/' . $filename];
            }

            $blog->blogImages()->createMany($uploadedImages);
        }

        return redirect()->route('admin.blogs.list.view')->with([
            'status' => true,
            'message' => 'Blog updated successfully',
        ]);
    }

    public function deleteBlog(Request $request)
    {
        try {
            $blogId = $request->input('blog_id');

            $blog = Blog::findOrFail($blogId);

            // Detach categories and tags
            $blog->categories()->detach();
            $blog->tags()->detach();

            $delete = $blog->delete();

            if ($delete) {
                return response()->json([
                    'status' => true,
                    'message' => 'Blog deleted successfully',
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

}
