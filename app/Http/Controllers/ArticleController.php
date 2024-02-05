<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    
    public function submitForm(Request $request)
    {
        // Validate request data if needed
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        // Process the form data, and return a response
        return response()->json(['message' => 'Form submitted successfully']);
    }
}
