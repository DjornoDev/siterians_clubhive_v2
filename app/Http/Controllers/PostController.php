<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;
    public function create(Club $club)
    {
        $this->authorize('create', [Post::class, $club]);
        return view('clubs.posts.create', compact('club'));
    }

    public function store(Request $request, Club $club)
    {
        $this->authorize('create', [Post::class, $club]);

        $validator = Validator::make($request->all(), [
            'post_caption' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'visibility' => 'required|in:PUBLIC,CLUB_ONLY',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('openCreatePostModal', true);
        }

        $post = $club->posts()->create([
            'post_caption' => $request->post_caption,
            'author_id' => auth()->id(),
            'post_visibility' => $request->visibility,
            'post_date' => now(),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('post-images', 'public');
                $post->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('clubs.show', $club);
    }

    public function edit(Club $club, Post $post)
    {
        $this->authorize('update', $post);
        return view('clubs.posts.partials.edit-modal', compact('club', 'post'));
    }

    public function update(Request $request, Club $club, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'post_caption' => 'required|string',
            'visibility' => 'required|in:PUBLIC,CLUB_ONLY',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:tbl_post_images,image_id',
        ]);

        $post->update([
            'post_caption' => $validated['post_caption'],
            'post_visibility' => $validated['visibility'],
        ]);

        // Delete selected images
        if (!empty($validated['delete_images'])) {
            $imagesToDelete = PostImage::whereIn('image_id', $validated['delete_images'])->get();
            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('post-images', 'public');
                $post->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('clubs.show', $club);
    }

    public function destroy(Club $club, Post $post)
    {
        $this->authorize('delete', $post);

        foreach ($post->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $post->delete();
        return redirect()->route('clubs.show', $club)
            ->with('success', 'Post deleted successfully');
    }
}
