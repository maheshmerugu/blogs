<?php

namespace App\Http\Livewire\Admin;

use App\Models\Blog;
use Livewire\Component;
use Livewire\WithPagination;

class Blogs extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $query = Blog::with(['category', 'tags', 'blogImages']);
        return view('livewire.admin.blogs', [
            'blogs' => $query->latest()->paginate(10),
        ]);
    }
}
