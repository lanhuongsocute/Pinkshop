<!-- start section -->
<div class="row">
    <div class="col-sm-12">
        <h3 class="pt-5 mb-5"> Hãy viết yêu cầu của bạn! </h3>
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
                
            <div class="form-row row">
                <div class="col-md-6">
                    <label for="name">Tên đầy đủ</label>
                    <input class="form-control required" value="{{$full_name}}" type="text" name="name" placeholder="Tên của bạn*">
                </div>
                
                <div class="col-md-6">
                    <label for="review">Email</label>
                    <input type="text" class=" form-control " 
                        value="{{$email}}" type="text" name="email" placeholder="Tên của bạn*">
                </div>
                <div class="col-md-12">
                    <label for="review">Nội dung</label>
                    <textarea class="form-control" placeholder=""
                    cols="40" rows="4" name="content"  ></textarea>
                </div>
                {!! NoCaptcha::display() !!}
                <div class="col-md-12">
                    <button class="btn btn-solid" type="submit">Gửi</button>
                </div>
            </div>
        </form>
    </div>
</div>