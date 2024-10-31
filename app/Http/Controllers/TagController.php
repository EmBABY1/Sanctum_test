<?php
namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return Tag::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:tags',
        ]);

        $tag = Tag::create($validated);
        return response()->json($tag, 201);
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:tags,name,' . $tag->id,
        ]);

        $tag->update($validated);
        return response()->json($tag);
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->json("Tag deleted successfully", 200);
    }
}