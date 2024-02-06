<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    
    public function submitForm(Request $request)
    {
        // Validate request data if needed
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'location'=>'required'
        ]);


        $article = new Article([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone'=>$request->input('phone'),
            'location'=>$request->input('location'),
            'article'=>$request->input('article')
        ]);
    
        $article->save();

        // Process the form data, and return a response
        return response()->json(['message' => 'Article Added successfully']);
    }
}
