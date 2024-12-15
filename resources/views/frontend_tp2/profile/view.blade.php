<?php
 
  $setting =\App\Models\SettingDetail::find(1);
  $user = auth()->user();
  if($user)
  {
      $sql  = "select c.quantity, d.* from (SELECT * from shoping_carts where user_id = "
      .$user->id.") as c left join products as d on c.product_id = d.id where d.status = 'active'  ";
      $pro_carts =   \DB::select($sql ) ;
  }
  else
  {
      $pro_carts = [];
  }
  $cart_size= count($pro_carts);
?>
@extends('frontend_tp.layouts.master')
@section('head_css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<style>
    .image-fit {
    position: relative;
}
.image-fit > img {
    position: absolute;
    height: 100%;
    width: 100%;
    -o-object-fit: cover;
    object-fit: cover;
}
.rounded-full {
    border-radius: 9999px;
}
.w-12 {
    width: 3rem;
}

.h-12 {
    height: 3rem;
}
.p-5 {
    padding: 1.25rem;
}

.items-center {
    align-items: center;
}
.flex {
    display: flex;
}
.relative {
    position: relative;
}
.ml-4 {
    margin-left: 1rem;
}
.mr-auto {
    margin-right: auto;
}
.content h3{
    text-transform: uppercase;
    font-size:130%;
    color:black;
    font-weight:700;
}
.dropzone.dz-clickable {
    cursor: pointer;
}
.dropzone {
    border-style: dashed;
    border-color: rgb(var(--color-slate-200) / 0.6);
}

 
.dropzone, .dropzone * {
    box-sizing: border-box;
}
.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message * {
    cursor: pointer;
}

.dropzone .dz-message {
    text-align: center;
    margin: 2em 0;
}
.dropzone.dz-clickable * {
    cursor: default;
}
.dropzone, .dropzone * {
    box-sizing: border-box;
}
.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message * {
    cursor: pointer;
}

.dropzone.dz-clickable * {
    cursor: default;
}
.dropzone, .dropzone * {
    box-sizing: border-box;
}
.font-medium {
    font-weight: 500;
}

</style>  
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

@endsection
@section('content')
@include('frontend_tp.layouts.breadcrumb')
<section class="wrapper !bg-[#ffffff] py-[5rem] xl:!py-5 lg:!py-5 md:!py-5  ">
    <div class="container py-[0rem] xl:!py-0 lg:!py-0 md:!py-0">
        <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px]">
            <div class="  xl:w-9/12 lg:w-9/12 md:w-0/12 w-full flex-[0_0_auto] xl:px-[10px] lg:px-[5px] px-[5px] max-w-full xl:order-2 lg:order-2">
                <div class="row align-items-center">
                    <form method="POST" action = "{{route('front.profile.update')}}">
                        @csrf
                        <div class="flex flex-wrap mx-[-15px]">
                            <div class="xl:w-10/12 w-full flex-[0_0_auto] px-[15px] max-w-full !mx-auto">
                                <div class="flex flex-wrap mx-[-15px] mt-[-50px] xl:mx-[-35px] lg:mx-[-20px]">
                                    <div class="xl:w-8/12 lg:w-8/12 w-full flex-[0_0_auto] xl:px-[35px] lg:px-[20px] px-[15px] max-w-full mt-[50px]">
                                        <div class="flex flex-wrap mx-[-10px]">
                                            <div class="xl:w-6/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] px-[15px] max-w-full">
                                                <div class="form-floating relative !mb-4">
                                                    <input name="full_name"   type="text"  value="{{$profile->full_name}}" required class=" form-control  relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]" placeholder="họ tên" required="">
                                                    <label for="form_name1" class="text-[#959ca9] mb-2 inline-block text-[.75rem] absolute z-[2] h-full overflow-hidden text-start text-ellipsis whitespace-nowrap pointer-events-none border origin-[0_0] px-4 py-[0.6rem] border-solid border-transparent left-0 top-0 font-Manrope">Tên đầy đủ *</label>
                                                
                                                </div>
                                                <div class="form-floating relative !mb-4">
                                                    <input name="email" disabled  type="email"  value="{{$profile->email}}" required class=" form-control  relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]" placeholder="email" required="">
                                                    <label for="form_name1" class="text-[#959ca9] mb-2 inline-block text-[.75rem] absolute z-[2] h-full overflow-hidden text-start text-ellipsis whitespace-nowrap pointer-events-none border origin-[0_0] px-4 py-[0.6rem] border-solid border-transparent left-0 top-0 font-Manrope">Email *</label>
                                                
                                                </div>
                                            
                                            </div>
                                            <!-- /column -->
                                            <div class="xl:w-6/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] px-[15px] max-w-full">
                                                <div class="form-floating relative !mb-4">
                                                    <input name="phone"    type="text"  value="{{$profile->phone}}" required class=" form-control  relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]" placeholder="điện thoại" required="">
                                                    <label for="form_name1" class="text-[#959ca9] mb-2 inline-block text-[.75rem] absolute z-[2] h-full overflow-hidden text-start text-ellipsis whitespace-nowrap pointer-events-none border origin-[0_0] px-4 py-[0.6rem] border-solid border-transparent left-0 top-0 font-Manrope">Điện thoại *</label>
                                                
                                                </div>
                                                <div class="form-floating relative !mb-4">
                                                    <input name="address"   type="text"  value="{{$profile->address}}" required class=" form-control  relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]" placeholder="địa chỉ" required="">
                                                    <label for="form_name1" class="text-[#959ca9] mb-2 inline-block text-[.75rem] absolute z-[2] h-full overflow-hidden text-start text-ellipsis whitespace-nowrap pointer-events-none border origin-[0_0] px-4 py-[0.6rem] border-solid border-transparent left-0 top-0 font-Manrope">Địa chỉ *</label>
                                                
                                                </div>
                                            </div>
                                            <!-- /column -->
                                            
                                            <!-- /column -->
                                             
                                            <!-- /column -->
                                            <div class="w-full flex-[0_0_auto] px-[15px] max-w-full">
                                            <div class="form-floating relative !mb-4">
                                                    <input name="description"   type="text"  value="{{$profile->description}}" required class=" form-control  relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]" placeholder="mô tả" required="">
                                                    <label for="form_name1" class="text-[#959ca9] mb-2 inline-block text-[.75rem] absolute z-[2] h-full overflow-hidden text-start text-ellipsis whitespace-nowrap pointer-events-none border origin-[0_0] px-4 py-[0.6rem] border-solid border-transparent left-0 top-0 font-Manrope">Mô tả *</label>
                                                
                                                </div>
                                            </div>
                                            <div class="w-full flex-[0_0_auto] px-[15px] max-w-full">
                                                <input type="submit" class="btn btn-primary text-white !bg-[#3f78e0] border-[#3f78e0] hover:text-white hover:bg-[#3f78e0] hover:border-[#3f78e0] focus:shadow-[rgba(92,140,229,1)] active:text-white active:bg-[#3f78e0] active:border-[#3f78e0] disabled:text-white disabled:bg-[#3f78e0] disabled:border-[#3f78e0] !rounded-[50rem] btn-send !mb-3 hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.15)]" value="Cập nhật">
                                            </div>
                                        </div>
                                    </div>
                                    <!--/column -->
                                    <div class="xl:w-4/12 lg:w-4/12 w-full flex-[0_0_auto] xl:px-[35px] lg:px-[20px] px-[15px] max-w-full mt-[50px]">
                                        <div class="items-center">
                                            <img class="avatar !w-[5rem]" src="{{$profile->photo}}" alt="" />
                                        </div>
                                        <div class="px-4 pb-4 mt-5 flex items-center  cursor-pointer relative">
                                            <div data-single="true" id="mydropzone" class="dropzone"    url="{{route('upload.avatar')}}" >
                                                <div class="fallback"> 
                                                    <input name="file" type="file" /> 
                                                </div>
                                                <div class="dz-message" data-dz-message>
                                                    <div class=" font-medium">
                                                        Kéo thả hoặc chọn ảnh.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            
                                        <input type="hidden" id="photo" name="photo" value="{{$profile->photo}}"/>
                                        <div class="mx-auto cursor-pointer relative mt-5">
                                            Cập nhật ảnh đại diện. Bổ trống nếu bạn không muốn thay đổi.
                                        </div>
                                    </div>
                                <!--/column -->
                                </div>
                                <!--/.row -->
                            </div>
                            <!-- /column -->
                        </div>
                    </form>
                    
                </div>
            </div>
            <aside class="  xl:w-3/12 lg:w-3/12 md:w-0/12 w-full flex-[0_0_auto] xl:px-[5px] lg:px-[5px] px-[5px] max-w-full sidebar mt-0 xl:!mt-0 lg:!mt-0">
                @include('frontend_tp.layouts.leftaccount')
            </aside>
        </div>
    </div>
</section>
@endsection

@section('scripts')
 
 
<script>
 Dropzone.autoDiscover = false;
    
    // Dropzone class:
    var myDropzone = new Dropzone("div#mydropzone", { url: "{{route('front.upload.avatar')}}"});
        // previewsContainer: ".dropzone-previews",
        // Dropzone.instances[0].options.url = "{{route('upload.avatar')}}";
        Dropzone.instances[0].options.multiple = false;
        Dropzone.instances[0].options.autoQueue= true;
        Dropzone.instances[0].options.maxFilesize =  1; // MB
        Dropzone.instances[0].options.maxFiles =1;
        Dropzone.instances[0].options.dictDefaultMessage = 'Drop images anywhere to upload (6 images Max)';
        Dropzone.instances[0].options.acceptedFiles= "image/jpeg,image/png,image/gif";
        Dropzone.instances[0].options.previewTemplate =  '<div class=" d-flex flex-column  position-relative">'
                                        +' <img    data-dz-thumbnail >'
                                        
                                    +' </div>';
        // Dropzone.instances[0].options.previewTemplate =  '<li><figure><img data-dz-thumbnail /><i title="Remove Image" class="icon-trash" data-dz-remove ></i></figure></li>';      
        Dropzone.instances[0].options.addRemoveLinks =  true;
        Dropzone.instances[0].options.headers= {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};

        Dropzone.instances[0].on("addedfile", function (file ) {
        // Example: Handle success event
        console.log('File addedfile successfully!' );
        });
        Dropzone.instances[0].on("success", function (file, response) {
        // Example: Handle success event
        // file.previewElement.innerHTML = "";
        if(response.status == "true")
        $('#photo').val(response.link);
        console.log('File success successfully!' +response.link);
        });
        Dropzone.instances[0].on("removedfile", function (file ) {
        $('#photo').val('');
        console.log('File removed successfully!'  );
        });
        Dropzone.instances[0].on("error", function (file, message) {
        // Example: Handle success event
        file.previewElement.innerHTML = "";
        console.log(file);

        console.log('error !' +message);
        });
        console.log(Dropzone.instances[0].options   );

        // console.log(Dropzone.optionsForElement);

</script>

 
@endsection