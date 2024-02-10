<div class="bg-white p-6 rounded shadow">
    <div class="text-center mb-6">
        <h2 class="text-3xl font-extrabold text-blue-600 leading-tight">Reply to Replied Comment</h2>
        <hr class="my-2">
    </div>
    
    <!-- Display the main comment -->
    <div class="mb-6 bg-gray-100 p-4 rounded-md shadow-md">
        <p class="text-xl font-semibold text-gray-800 leading-tight">{{$replier->name}}</p>
        <div class="text-sm text-gray-600 mt-2">
            <span class="italic text-lg">{{ $replied_comment->repliedComment }}</span>
        </div>
    </div>
    
    

    <!-- Show existing replies -->
    <div class="mb-4">
        <p class="text-gray-600 text-sm mb-2">Replies:</p>
        <div class="border-l-2 pl-4 mb-2">
            <p class="text-sm">aa</p>
        </div>
        {{-- @foreach ($replies as $reply)
        @endforeach --}}
    </div>

    <!-- Form to add a new reply -->
    <form>
        <div class="mb-4">
            <textarea wire:model.live="comment" placeholder="Write your reply here..." rows="3" class="w-full px-4 py-2 border rounded-md"></textarea>
            @error('newReply') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <button wire:click.prevent="addReply" type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Add Reply</button>
        </div>
    </form>
</div>