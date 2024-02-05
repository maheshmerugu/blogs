<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
<<<<<<< HEAD
use Illuminate\Http\Request;
use App\Models\Tag;

class BlogController extends Controller
{

    public function getBlogView()
    {
        return view('admin.blogs.list');
=======
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
>>>>>>> origin/venkatesh
    }

    public function getBlogLists(Request $request)
    {
<<<<<<< HEAD
        // Assuming you have an admin guard check here...

        // Get search value
        $search = $request->input('search.value');

        // Set default limit and offset values
        $limit = $request->input('length', 25);
        $offset = $request->input('start', 0);

        // Set default order type and column
        $orderType = $request->input('order.0.dir', 'asc');
        $nameOrder = $request->input('columns.' . $request->input('order.0.column') . '.name', 'id');

        // Query to get paginated blogs with search and order
        $blogsQuery = Blog::query();
=======
        Log::info('Reached here ');
        Log::info('Blog Lists Request Data: ' . json_encode($request->all()));
        $search = $request->input('search.value');
        $limit = $request->input('length', 25);
        $offset = $request->input('start', 0);
        $orderType = $request->input('order.0.dir', 'asc');
        $nameOrder = $request->input('columns.' . $request->input('order.0.column') . '.name', 'id');

        $blogsQuery = Blog::with(['categories', 'tags', 'blogImages'])
            ->select(['blogs.*']);

>>>>>>> origin/venkatesh
        if ($search) {
            $blogsQuery->where('title', 'like', '%' . $search . '%');
        }

        $total = $blogsQuery->count();

        $blogs = $blogsQuery->orderBy($nameOrder, $orderType)
            ->offset($offset)
            ->limit($limit)
            ->get();
<<<<<<< HEAD
        info($blogs);
        $data = [];
        foreach ($blogs as $key => $blog) {
=======

        $data = [];
        foreach ($blogs as $key => $blog) {
            $categories = $blog->categories->pluck('name')->toArray();
            $tags = $blog->tags->pluck('name')->toArray();
            $images = $blog->blogImages->pluck('image_path')->toArray();

>>>>>>> origin/venkatesh
            $data[] = [
                'id' => $blog->id,
                'title' => $blog->title,
                'description' => $blog->description,
<<<<<<< HEAD
                'categories' => $blog->categories->pluck('name')->implode(', '),
                'tags' => $blog->tags->pluck('name')->implode(', '),
                'images' => $blog->images->pluck('image_path')->implode(', '),
                'actions' => '<a href="javascript::void(0)" class="deleteBlog" data-id="' . $blog->id . '"><i class="fa fa-trash text-danger"></i></a> | <button class="editBlog" data-id="' . $blog->id . '"><i class="fa fa-edit"></i></button>',
            ];
        }

=======
                'categories' => $categories, // Use array directly, no need to implode
                'tags' => $tags, // Use array directly, no need to implode
                'images' => $images, // Use array directly, no need to implode
                'action' => '<a href="javascript::void(0)" class="deleteBlog" data-id="' . $blog->id . '"><i class="fa fa-trash text-danger"></i></a> | <button class="editBlog" data-id="' . $blog->id . '"><i class="fa fa-edit"></i></button>',
            ];
        }

        $records['draw'] = $request->input('draw');
>>>>>>> origin/venkatesh
        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] = $total;
        $records['data'] = $data;

        return response()->json($records);
    }

<<<<<<< HEAD
    // Other methods (create, update, delete) can remain the same as in your original code...

=======
>>>>>>> origin/venkatesh
    public function index()
    {
        $categories = Category::all(); // Fetch all categories

        return view('admin.blogs.add', compact('categories'));
    }

    public function create(Request $request)
    {
<<<<<<< HEAD
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'categories' => 'required|array',
            'tags' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Create a new blog instance
        $blog = Blog::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);
        
        $lastInsertedId = $blog->id;
        // Save the blog to the database
        // $blog->save();

        // Attach categories to the blog
        // $blog->categories()->sync($request->input('categories'));

        // Handle and attach tags
        if ($request->has('tags')) {
            $tagNames = explode(',', $request->input('tags'));

            foreach ($tagNames as $tagName) {
                // Find or create the tag
                $tag = Tag::Create(['name' => trim($tagName),'blog_id'=>$lastInsertedId]);

                // Attach the tag to the blog
                $blog->tags()->attach($tag->id);
            }
        }

        // Handle and store images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Generate a unique filename for the image
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Move the image to the public/images/blogs folder
                $path = $image->storeAs('public/images/blogs', $filename);

                // Create an Image model and associate it with the blog
                $blog->images()->create(['image_path' => $filename]);
            }
        }

        return response()->json(['status' => true, 'message' => 'Blog created successfully']);
    }

    public function getEdit($id)
    {
        if (auth()->check()) {
            $blog = Blog::find($id);
            $categories = BlogCategory::all();
            $tags = BlogTag::all();
            return view('admin.blogs.edit', compact('blog', 'categories', 'tags'));
        } else {
            // Handle authentication failure (e.g., redirect to logout)
        }
    }

    public function deleteBlog(Request $request)
    {
        try {
            $blog = Blog::findOrFail($request->blog);

            $delete = $blog->delete();

            if ($delete) {
                $this->sendWebResponse(true, "Successfully Deleted");
            } else {
                $this->sendWebResponse(false, "Something went wrong, please try again");
            }
        } catch (Exception $e) {
            $this->sendWebResponse(false, "Something went wrong, please try again");
        }
    }

    //for create category
    public function createCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        // Create a new category instance
        $category = new Category();
        $category->name = $request->input('name');

        // Save the category to the database
        $category->save();

        return response()->json(['status' => true, 'message' => 'Category created successfully']);
=======
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
>>>>>>> origin/venkatesh
    }
}
