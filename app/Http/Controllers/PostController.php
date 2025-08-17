<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Post;
use App\Models\PostDocument;
use App\Models\User;
use App\Models\PostImage;
use App\Models\ActionLog;
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
            'file_attachments' => 'nullable|array|max:5', // Max 5 files
            'file_attachments.*' => 'file|mimes:pdf,doc,docx,txt,ppt,pptx,xls,xlsx,zip,rar|max:10240', // Max 10MB per file
            'file_attachment' => 'nullable|file|mimes:pdf,doc,docx,txt,ppt,pptx,xls,xlsx,zip,rar|max:10240', // Keep for backward compatibility
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

        // Handle single file attachment (backward compatibility)
        if ($request->hasFile('file_attachment')) {
            $file = $request->file('file_attachment');
            $path = $file->store('post-attachments', 'public');

            $postData['file_attachment'] = $path;
            $postData['file_original_name'] = $file->getClientOriginalName();
            $postData['file_mime_type'] = $file->getMimeType();
            $postData['file_size'] = $file->getSize();
        }

        // Handle multiple file attachments
        $uploadedDocuments = [];
        if ($request->hasFile('file_attachments')) {
            foreach ($request->file('file_attachments') as $file) {
                $filePath = $file->store('post-documents', 'public');
                $uploadedDocuments[] = [
                    'document_path' => $filePath,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'uploaded_at' => now(),
                ];
            }
        }

        $post = $club->posts()->create($postData);

        // Save uploaded documents to the new documents table
        if (!empty($uploadedDocuments)) {
            foreach ($uploadedDocuments as $documentData) {
                $post->documents()->create($documentData);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('post-images', 'public');
                $post->images()->create(['image_path' => $path]);
            }
        }

        // Log post creation
        ActionLog::create_log(
            'post_management',
            'created',
            "Created new post in club: {$club->club_name}",
            [
                'post_id' => $post->post_id,
                'club_id' => $club->club_id,
                'club_name' => $club->club_name,
                'post_visibility' => $post->post_visibility,
                'has_images' => $request->hasFile('images'),
                'has_documents' => !empty($uploadedDocuments),
                'has_file_attachment' => !empty($postData['file_attachment'])
            ]
        );

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
            'file_attachment' => 'nullable|file|mimes:pdf,doc,docx,txt,ppt,pptx,xls,xlsx,zip,rar|max:10240', // Legacy single file
            'file_attachments' => 'nullable|array|max:5', // Max 5 files
            'file_attachments.*' => 'file|mimes:pdf,doc,docx,txt,ppt,pptx,xls,xlsx,zip,rar|max:10240', // Max 10MB per file
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:tbl_post_images,image_id',
            'remove_file_attachment' => 'nullable|boolean',
            'remove_documents' => 'nullable|array',
            'remove_documents.*' => 'exists:tbl_post_documents,id',
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

        // Handle multiple document removal
        if (!empty($validated['remove_documents'])) {
            $documentsToDelete = PostDocument::whereIn('id', $validated['remove_documents'])->get();
            foreach ($documentsToDelete as $document) {
                Storage::disk('public')->delete($document->document_path);
                $document->delete();
            }
        }

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

        // Handle multiple file attachments
        if ($request->hasFile('file_attachments')) {
            foreach ($request->file('file_attachments') as $file) {
                $path = $file->store('post-documents', 'public');

                PostDocument::create([
                    'post_id' => $post->post_id,
                    'document_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'uploaded_at' => now(),
                ]);
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

    public function downloadDocument(PostDocument $document)
    {
        $post = $document->post;

        // Check if user can view this post
        if (!$post->canBeViewedBy(Auth::user())) {
            abort(403, 'Unauthorized.');
        }

        if (!Storage::disk('public')->exists($document->document_path)) {
            abort(404, 'Document not found.');
        }

        return response()->download(
            storage_path('app/public/' . $document->document_path),
            $document->original_name
        );
    }
}
