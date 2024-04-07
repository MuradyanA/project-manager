<div class="mt-5">
    <form wire:submit="save()" class="flex flex-col space-y-2">
        <textarea wire:model="form.comment"
            class="w-full mx-auto h-32 px-3 py-1 rounded-lg border border-gray-300 focus:outline-none focus:border-indigo-500"
            placeholder="Write your comment here..."></textarea>
        <div class="flex justify-start">
            <x-BlueSubmitButton />
        </div>
    </form>
    @if ($openLikersForm == true)
        <div class="w-[20%] fixed left-[40%] bottom-[40%] z-10 bg-gray-100 p-2 border-2 border-blue-400 rounded-md ">
            <div class="h-fit"><button wire:click="closeShowLikers()"><svg xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
                <hr class="my-2">
            </div>
            <div>
                @if (!empty($likers))
                    <p class="text-2xl italic text-center font-semibold">Likers</p>
                    <hr class="my-2">

                    @foreach ($likers as $liker)
                        <div class="w-full rounded-sm p-2 bg-gray-200">
                            <p class='font-semibold italic text-xl'>{{ $liker }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endif
    @foreach ($mainComments as $mainComment)
        <div class="mt-[1%] space-y-2">
            @if ($mainComment->commentable_id == $commentableId)
                <div class="flex flex-col bg-white rounded-md p-2 shadow-md">
                    <div class="flex items-center">
                        <p class="text-md flex gap-2 text-gray-800 font-semibold"><svg
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            {{ $mainComment->commenterName }}</p>
                    </div>
                    @foreach ($likesDislikes as $elem)
                        @if ($elem['commentId'] == $mainComment->id && $mainComment->main_comment == null)
                            <div class="flex items-center">
                                <button wire:click="likeComment({{ $mainComment->id }})" wire:loading.attr="disabled"
                                    class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                                    </svg>
                                </button>
                                <span class="text-xl">{{ $elem['dislikes'] }}</span>
                                <button class="mr-2" wire:click="dislikeComment({{ $mainComment->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7.5 15h2.25m8.024-9.75c.011.05.028.1.052.148.591 1.2.924 2.55.924 3.977a8.96 8.96 0 01-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398C20.613 14.547 19.833 15 19 15h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 00.303-.54m.023-8.25H16.48a4.5 4.5 0 01-1.423-.23l-3.114-1.04a4.5 4.5 0 00-1.423-.23H6.504c-.618 0-1.217.247-1.605.729A11.95 11.95 0 002.25 12c0 .434.023.863.068 1.285C2.427 14.306 3.346 15 4.372 15h3.126c.618 0 .991.724.725 1.282A7.471 7.471 0 007.5 19.5a2.25 2.25 0 002.25 2.25.75.75 0 00.75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 002.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    @endforeach
                    <p class="text-md text-gray-800">{{ $mainComment->comment }}</p>
                    <hr class="my-[1%]">
                    <span class="text-sm text-gray-500 mt-[1%]">{{ $mainComment->created_at }}</span>
                    <div class="flex justify-end">
                        @if (!in_array($mainComment->id, $openedReplyForms))
                            <button wire:click="openReplyForm({{ $mainComment->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                </svg>
                            </button>
                        @else
                            <button wire:click="closeReplyForm({{ $mainComment->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                    </div>
                    <button wire:click="getCommentReplies({{ $mainComment->id }})"
                        class="w-fit text-start text-md font-semibold hover:text-gray-600 duration-300">
                        <span>
                            @if (!in_array($mainComment->id, $showRepliedComments))
                                Show Replies <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 pb-1 h-5 inline">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                                </svg>
                            @elseif(in_array($mainComment->id, $showRepliedComments))
                                Close Replies <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 pb-1 h-5 inline">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.5 12.75l7.5-7.5 7.5 7.5m-15 6l7.5-7.5 7.5 7.5" />
                                </svg>
                            @endif
                        </span>
                    </button>
            @endif
            <div class="replied-comments mt-4">
                @php
                    if ($repliedComments != null) {
                        $replies = $repliedComments->filter(fn($value) => $mainComment->id == $value->main_comment);
                    }
                @endphp
                @if (in_array($mainComment->id, $showRepliedComments))
                    {!! count($repliedComments) == 0
                        ? '<h3 class="text-md font-semibold mb-2">No Replies</h3>'
                        : '<h3 class="text-md font-semibold mb-2">Replied Comments:</h3>' !!}
                    @foreach ($replies as $repliedComment)
                        @if ($repliedComment->commentable_id == $commentableId)
                            <div class="bg-gray-100 rounded-md p-2 mb-2">
                                <p class="italic text-lg">Replied To: <span
                                        class=" font-semibold">{{ $mainComment->comment }}</span></p>
                                <p class="text-lg font-semibold">{{ $repliedComment->commenterName }}</p>
                                <p class="text-md text-gray-800">{{ $repliedComment->comment }}
                                </p>
                                <span class="text-sm text-gray-500 mt-1">{{ $repliedComment->created_at }}
                                </span>
                                @if (in_array($repliedComment->id, $openedReplyReplingForms))
                                    <button title="Close Reply Form" class="ml-[3%]"
                                        wire:click="closeReplyReplingForm({{ $repliedComment->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                @endif
                                <hr class="w-[99%] mx-auto mt-4 border-t border-gray-300" />
                                {{-- <p>{{ dd($repliedComment) }}</p> --}}
                                @if (in_array($repliedComment->id, $openedReplyReplingForms))
                                    <hr class="w-[90%] mx-auto mt-4 border-t border-gray-300">
                                    <div class="ml-[5%] mt-4">
                                        <p class="text-lg font-semibold mb-2">Reply To Comment</p>
                                        <form action="">
                                            <textarea wire:model.live="repliedComment"
                                                class="w-full h-20 px-4 py-2 border rounded-md focus:outline-none focus:border-indigo-500 resize-none bg-gray-100"
                                                name="" id="" cols="30" rows="5" placeholder="Write your reply here..."></textarea>
                                            <button wire:click.prevent="reply( {{ $repliedComment->id }} )"
                                                type="submit"
                                                class="mt-2 bg-green-700 text-white px-4 py-2 rounded-md hover:bg-green-800 transition duration-300">
                                                Submit Reply
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            @if (in_array($mainComment->id, $openedReplyForms))
                <hr class="w-[90%] mx-auto mt-4 border-t border-gray-300">
                <div class="ml-[5%] mt-4">
                    <p class="text-lg font-semibold mb-2">Reply To Comment</p>
                    <form action="">
                        <textarea wire:model.live="repliedComment"
                            class="w-full h-20 px-4 py-2 border rounded-md focus:outline-none focus:border-indigo-500 resize-none bg-gray-100"
                            name="" id="" cols="30" rows="5" placeholder="Write your reply here..."></textarea>
                        <button wire:click.prevent="submitReply( {{ $mainComment->id }} )" type="submit"
                            class="mt-2 bg-green-700 text-white px-4 py-2 rounded-md hover:bg-green-800 transition duration-300">
                            Submit Reply
                        </button>
                    </form>
                </div>
            @endif
        </div>
    @endforeach
</div>
