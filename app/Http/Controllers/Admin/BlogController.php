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
        Log::info('Reached here ');
        Log::info('Blog Lists Request Data: ' . json_encode($request->all()));
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
            $images = $blog->blogImages->pluck('image_path')->toArray();

            $data[] = [
                'id' => $blog->id,
                'title' => $blog->title,
                'description' => $blog->description,
                'categories' => $categories, // Use array directly, no need to implode
                'tags' => $tags, // Use array directly, no need to implode
                'images' => $images, // Use array directly, no need to implode
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
            $blog->categories()->attach($categoryId);

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
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('public/images/blogs', $filename);
                    $blog->blogImages()->create(['image_path' => $filename]);
                }
            }

            if ($insert) {
                $this->sendWebResponse(true, "Blog created successfully");
            } else {
                $this->sendWebResponse(false, "Something went wrong, please try again");
            }
        } catch (Exception $ex) {
            Log::error($ex);
            return $this->sendWebResponse(false, "Something went wrong, please try again");
        }
    }

    public function edit(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.blogs.edit', compact('blog', 'categories', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $rule = [
            'title' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|exists:categories,id',
            'tags' => 'nullable|string',
            // Add more validation rules as needed
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
            $blog = Blog::findOrFail($id);
            $blog->title = $request->input('title');
            $blog->description = $request->input('description');

            $update = $blog->save();

            // Detach old categories and attach new category
            $blog->categories()->detach();
            $categoryId = $request->input('category');
            $blog->categories()->attach($categoryId);

            // Detach old tags and attach new tags
            $blog->tags()->detach();
            if ($request->has('tags')) {
                $tagNames = explode(',', $request->input('tags'));
                $tagIds = [];

                foreach ($tagNames as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
                    $tagIds[] = $tag->id;
                }

                $blog->tags()->attach($tagIds);
            }

            if ($update) {
                return $this->sendWebResponse(true, "Blog updated successfully");
            } else {
                return $this->sendWebResponse(false, "Something went wrong, please try again");
            }
        } catch (Exception $ex) {
            return $this->sendWebResponse(false, "Something went wrong, please try again");
        }
    }

    public function delete(Request $request)
    {
        $blogId = $request->input('blog_id');

        $blog = Blog::findOrFail($blogId);

        $blog->categories()->detach();
        $blog->tags()->detach();

        $delete = $blog->delete();

        if ($delete) {
            return $this->sendWebResponse(true, 'Blog deleted successfully');
        } else {
            return $this->sendWebResponse(false, 'Something went wrong, please try again');
        }
    }
}
