<!-- start section -->
<div  style="box-shadow: 0 0 10px 0 #ddd; padding: 24px "  class="">
    <h3 class="title h3"> Hãy viết nhận xét của bạn!</h3>
<form action="{{route('front.comment.save')}}" method="post" class="row  ">
    <?php 
        $full_name = "";
        $email = "";
        $user = auth()->user();
        if( $user)
        {
            $full_name = $user->full_name;
            $email = $user->email;
        }

    ?>
        @csrf   
        {!! NoCaptcha::renderJs() !!}

        @if ($errors->has('g-recaptcha-response'))
            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
        @endif
        <input type='hidden' name='url' value='{{"https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"}}'/>
        <div class="col-md-6 mb-30px">
            <input class="input-name border-radius-4px form-control required" value="{{$full_name}}" type="text" name="name" placeholder="Tên của bạn*">
        </div> 
        <div class="form-floating relative !mb-4">
            <input type="text" class=" form-control  relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]" 
            value="{{$full_name}}" type="text" name="name" placeholder="Tên của bạn*">
        </div>
        <div class="form-floating relative !mb-4">
            <input type="text" class=" form-control  relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]" 
            type="email" value="{{$email}}" name="email" placeholder="Email*">
        </div>  
        <div class="col-md-12 mb-30px">
            <textarea class=" form-control  relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]"  style="height: 150px"
            cols="40" rows="4" name="content" placeholder="Nội dung"></textarea>
        </div> 
        {!! NoCaptcha::display() !!}
        <div class="col-12">
            <input type="hidden" name="redirect" value="">
            <button type="submit" class="btn btn-primary text-white !bg-[#3f78e0] border-[#3f78e0] hover:text-white hover:bg-[#3f78e0] hover:border-[#3f78e0] focus:shadow-[rgba(92,140,229,1)] active:text-white active:bg-[#3f78e0] active:border-[#3f78e0] disabled:text-white disabled:bg-[#3f78e0] disabled:border-[#3f78e0] !rounded-[50rem] !mb-0 hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.15)]">Gửi bình luận</button>
            <div class="form-results mt-20px d-none"></div>
        </div>
</form>
</div>
 