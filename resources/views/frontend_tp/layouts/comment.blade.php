<?php
            $cur_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $comments = \DB::select("select * from comments where url ='".$cur_url."' and status = 'active'");
          ?>
    <hr>
    <div id="comments" class="relative mb-10 ">
        <h3 class="!mb-6">{{count($comments)}} bình luận</h3>
        @if (count($comments) > 0)
            <ol id="singlecomments" class="commentlist m-0 p-0 list-none">
                @foreach ($comments as $comment )
                <li class="comment mt-8">
                    <div class="comment-header xl:flex lg:flex md:flex items-center !mb-[.5rem]">
                        <div class="flex items-center">
                            <figure class="w-12 h-12 !relative !mr-4 rounded-[100%]">
                                <img class="rounded-[50%]" alt="image" src="https://via.placeholder.com/130x130">
                            </figure>
                            <div>
                                <h6 class=" m-0 mb-[0.2rem]"><a href="#" class="title_color">{{$comment->name}}</a></h6>
                                <ul class="text-[0.7rem] primarytextcolor m-0 p-0 list-none">
                                    <li><i class="uil uil-calendar-alt pr-[0.2rem] align-[-.05rem] before:content-['\e9ba']"></i>{{$comment->created_at}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <p>{{$comment->content}}</p>
                </li>
                @endforeach
            </ol>
        @endif
    </div>