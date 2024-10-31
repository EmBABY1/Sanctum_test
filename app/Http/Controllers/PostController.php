<?php
namespace App\Http\Controllers;

use App\Jobs\ForceDeleteOldPosts;
use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // List all posts of the authenticated user
    public function index()
    {
        $posts = auth()->user()->posts()->orderBy('pinned', 'desc')->get();  // Pinned posts first
        return response()->json($posts);
    }

    // Store a new post
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'required|image',
            'pinned' => 'required|boolean',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
        ]);
        // Handle file upload for cover image
        $coverImage = $request->file('cover_image')->store('covers', 'public');

        $post = auth()->user()->posts()->create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'cover_image' => $coverImage,
            'pinned' => $validated['pinned'],
        ]);

        // Attach tags to the post
        $post->tags()->attach($validated['tags']);

        return response()->json($post, 201);
    }

    // Show a single post of the authenticated user
    public function show(Post $post)
    {
        // Ensure the post belongs to the authenticated user
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($post);
    }

    // Update an existing post
    public function update(Request $request, Post $post)
    {
        // Ensure the post belongs to the authenticated user
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string',
            'cover_image' => 'sometimes|image',
            'pinned' => 'sometimes|required|boolean',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Handle file upload for cover image (if updating)
        if ($request->hasFile('cover_image')) {
            // Delete the old cover image
            Storage::disk('public')->delete($post->cover_image);

            // Store the new one
            $coverImage = $request->file('cover_image')->store('covers', 'public');
            $post->cover_image = $coverImage;
        }

        // Update the post fields
        $post->update($validated);

        // Update tags if provided
        if ($request->has('tags')) {
            $post->tags()->sync($validated['tags']);
        }

        return response()->json($post);
    }

    // Soft delete a post
    public function destroy(Post $post)
    {
        // Ensure the post belongs to the authenticated user
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();  // Soft delete
        return response()->json('post deleted successfully', 200);
    }

    // View all deleted posts of the authenticated user
    public function trashed()
    {
        $trashedPosts = auth()->user()->posts()->onlyTrashed()->get();
        return response()->json($trashedPosts);
    }

    // Restore a soft-deleted post
    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);

        // Ensure the post belongs to the authenticated user
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->restore();  // Restore the post
        return response()->json($post);
    }
}