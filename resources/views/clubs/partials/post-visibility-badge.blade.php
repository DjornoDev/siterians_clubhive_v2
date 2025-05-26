@if($post->post_visibility === 'CLUB_ONLY')
    <span class="inline-flex items-center text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full text-xs">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
        </svg>
        Club Only
    </span>
@else
    {{ $post->post_visibility }}
@endif
