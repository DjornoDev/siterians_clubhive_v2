<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Post;
use App\Models\User;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'file_attachment' => 'nullable|file|mimes:pdf,doc,docx,txt,ppt,pptx,xls,xlsx,zip,rar|max:10240', // Max 10MB
            'visibility' => 'required|in:PUBLIC,CLUB_ONLY',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('openCreatePostModal', true);
        }

        $postData = [
            'post_caption' => $request->post_caption,
            'author_id' => Auth::id(),
            'post_visibility' => $request->visibility,
            'post_date' => now(),
        ];

        // Handle file attachment
        if ($request->hasFile('file_attachment')) {
            $file = $request->file('file_attachment');
            $path = $file->store('post-attachments', 'public');

            $postData['file_attachment'] = $path;
            $postData['file_original_name'] = $file->getClientOriginalName();
            $postData['file_mime_type'] = $file->getMimeType();
            $postData['file_size'] = $file->getSize();
        }

        $post = $club->posts()->create($postData);

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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'file_attachment' => 'nullable|file|mimes:pdf,doc,docx,txt,ppt,pptx,xls,xlsx,zip,rar|max:10240', // Max 10MB
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:tbl_post_images,image_id',
            'remove_file_attachment' => 'nullable|boolean',
        ]);

        $updateData = [
            'post_caption' => $validated['post_caption'],
            'post_visibility' => $validated['visibility'],
        ];

        // Handle file attachment removal
        if (!empty($validated['remove_file_attachment'])) {
            if ($post->file_attachment) {
                Storage::disk('public')->delete($post->file_attachment);
            }
            $updateData['file_attachment'] = null;
            $updateData['file_original_name'] = null;
            $updateData['file_mime_type'] = null;
            $updateData['file_size'] = null;
        }

        // Handle new file attachment
        if ($request->hasFile('file_attachment')) {
            // Delete old file if exists
            if ($post->file_attachment) {
                Storage::disk('public')->delete($post->file_attachment);
            }

            $file = $request->file('file_attachment');
            $path = $file->store('post-attachments', 'public');

            $updateData['file_attachment'] = $path;
            $updateData['file_original_name'] = $file->getClientOriginalName();
            $updateData['file_mime_type'] = $file->getMimeType();
            $updateData['file_size'] = $file->getSize();
        }

        $post->update($updateData);

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

        return redirect()->back()->with('success', 'Post updated successfully');
    }

    public function destroy(Club $club, Post $post)
    {
        $this->authorize('delete', $post);

        // Delete all images associated with the post
        foreach ($post->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // Delete file attachment if exists
        if ($post->file_attachment) {
            Storage::disk('public')->delete($post->file_attachment);
        }

        $post->delete();
        return redirect()->route('clubs.show', $club)
            ->with('success', 'Post deleted successfully');
    }
}
