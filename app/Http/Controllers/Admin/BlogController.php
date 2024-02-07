<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\PatientExperts;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{

    public function index(Request $request){

        $patient_experts=PatientExperts::all();
        $blogs=Blog::where('category_id',3)->get();
        return view('blogs.index',compact('patient_experts','blogs'));
    }

    public function  PatientBlogView($id){
        $blogs=Blog::where('category_id',3)->get();

        $patient_blog=PatientExperts::where('id',$id)->first();

        return view('blogs.patient_view_blog',compact('blogs','patient_blog'));
    }

    public function blogView(){
        $blogs=Blog::where('category_id',3)->first();
        return view('blogs.view_blog',compact('blogs'));
    }

    public function allPatientExpertsView(){
        $all_patient_experts=PatientExperts::all();

        return view('blogs.all-patient-experts',compact('all_patient_experts'));
    }
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
            $images = $blog->blogImages->pluck('image_path')->toArray();

            $data[] = [
                'id' => $blog->id,
                'title' => $blog->title,
                'description' => $blog->description,
                'categories' => $categories,
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

    // public function index()
    // {
    //     $categories = Category::all(); // Fetch all categories

    //     return view('admin.blogs.add', compact('categories'));
    // }

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
                    $blog->blogImages()->create(['image_path' => $path]);
                }
            }

            if ($insert) {
                return $this->sendWebResponse(true, "Blog created successfully");
            } else {
                return $this->sendWebResponse(false, "Something went wrong, please try again");
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
        $blog->save();

        // Update category
        $categoryId = $request->input('category');
        $blog->categories()->sync([$categoryId]);

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
                $path = $image->store('blog_images', 'public');
                $uploadedImages[] = ['path' => $path];
            }

            $blog->blogImages()->createMany($uploadedImages);
        }

        return response()->json(['status' => true, 'message' => 'Blog updated successfully']);
    }

    // private function handleExistingTags(Request $request, Blog $blog)
    // {
    //     // Detach old tags and attach new tags
    //     $blog->tags()->detach();

    //     // Handle existing tags
    //     if ($request->has('tags')) {
    //         $tagIds = $request->input('tags');
    //         $blog->tags()->attach($tagIds);
    //     }
    // }

    // private function handleNewTags(Request $request, Blog $blog)
    // {
    //     // Handle new tags
    //     if ($request->has('new_tags')) {
    //         $newTagNames = explode(', ', $request->input('new_tags'));
    //         $newTagIds = [];

    //         foreach ($newTagNames as $tagName) {
    //             // Trim tag name
    //             $tagName = trim($tagName);

    //             // Find the tag by name
    //             $tag = Tag::firstOrNew(['name' => $tagName]);

    //             // Check if the tag already exists
    //             if (!$tag->exists) {
    //                 // If the tag is new, save it
    //                 $tag->save();
    //             }

    //             // Add the tag ID to the array
    //             $newTagIds[] = $tag->id;
    //         }

    //         // Attach new tags to the blog
    //         $blog->tags()->attach($newTagIds);
    //     }
    // }

    // private function handleNewImages(Request $request, Blog $blog)
    // {
    //     // Handle new images
    //     $images = $request->file('images');

    //     foreach ($images as $image) {
    //         $filename = $image->store('your_images_directory', 'public');

    //         // Create a new Image model instance and associate it with the blog
    //         $blog->images()->create(['filename' => $filename]);
    //     }
    // }

    public function deleteBlog(Request $request)
    {
        try {
            $blogId = $request->input('blog_id');

            $blog = Blog::findOrFail($blogId);

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
