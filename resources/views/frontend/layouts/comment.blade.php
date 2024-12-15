<?php
            $cur_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $comments = \DB::select("select * from comments where url ='".$cur_url."' and status = 'active'");
          ?>
          
    <div id="comments" class="relative mb-2">
        <div class="row pt-5 ">
            <hr class="pt-5">
            <center>
                <h3 class="!mb-6">{{count($comments)}} bình luận</h3>
            </center>
        </div>
        @if (count($comments) > 0)
            <ul style=" list-style-type: none;" id="singlecomments" class="commentlist m-0 p-0 list-none">
                @foreach ($comments as $comment )
                <li class="comment mt-8" style="display:list-item;">
                    <div class="comment-header xl:flex lg:flex md:flex items-center !mb-[.5rem]">
                        <div class="flex items-center">
                            <div>
                                <h6 class=" m-0 mb-[0.2rem]"><a style="color: var(--theme-color);"  type="button">{{$comment->name}}</a></h6>
                                <ul class="text-[0.7rem] primarytextcolor m-0 p-0 list-none">
                                    <li><i class="uil uil-calendar-alt pr-[0.2rem] align-[-.05rem] before:content-['\e9ba']"></i>{{$comment->created_at}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <p>{{$comment->content}}</p>
                </li>
                @endforeach
            </ul>
        @endif
    </div>