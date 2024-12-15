@extends('backend.layouts.master')
@section ('scriptop')
 
@section('content')

<div class = 'content'>
@include('backend.layouts.notification')
 
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
           Thông tin sản phẩm
        </h2>
    </div>
                <div class="grid grid-cols-12 gap-6">
                    <!-- BEGIN: Profile Menu -->
                    <div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
                    <?php
                                $photos = explode( ',', $product->photo);
                    ?>
                        <div class="intro-y box mt-5">
                            <div class="relative flex items-center p-5">
                            <div class="mx-6"> 
                                <div class="single-item"> 
                                     @foreach ($photos as $photo)
                                        <div class="h-32 px-2"> 
                                            <div class="h-full bg-slate-100 dark:bg-darkmode-400 rounded-md"> 
                                            <img    src="{{$photo}}"/>
                                            </div> 
                                        </div> 
                                    @endforeach
                                </div> 
                            </div> 
                          
                            </div>
                        </div>
                    </div>
                    <!-- END: Profile Menu -->
                    <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
                        <!-- BEGIN: Display Information -->
                        <div class="intro-y box lg:mt-5">
                            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                <h2 class="font-medium text-base mr-auto">
                                    {{$product->title}}
                                </h2>
                            </div>
                            <div class="p-5">
                                <div class="flex flex-col-reverse xl:flex-row flex-col">
                                    <div class="flex-1 mt-6 xl:mt-0">
                                        <div class="grid grid-cols-12 gap-x-5">
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium  form-label">Tồn kho:</label>
                                                    {{$product->stock}}
                      
                                                </div>
                                                
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium  form-label">Giá nhập tb:</label>
                                                    {{number_format($product->price_in,0,".",",")}} 
                      
                                                </div>
                                                
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium  form-label">Giá vốn:</label>
                                                     {{number_format($product->price_avg,0,".",",")}}
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label  class="font-medium  form-label">Giá bán tb:</label>
                                                    {{number_format($product->price_out,0,".",",")}}
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label  class="font-medium  form-label">Giá niêm yết website:</label>
                                                    {{number_format($product->price,0,".",",")}}  
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label  class="font-medium  form-label">Bảo hành: </label>
                                                    {{$product->expired}}
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label   class=" font-medium  form-label">Loại sản phẩm: </label>
                                                   {{ $product->type}}
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label class="font-medium  form-label">Trạng thái:</label>
                                                   {{ $product->status}}
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-6">
                                                <div>
                                                    <label   class="font-medium form-label">Danh mục: </label>
                                                    {{$product->cat_id!=null ? \App\Models\Category::where('id',$product->cat_id)->value('title'):''}}   
                     
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-6">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium  form-label">Nhà sản xuất:</label>
                                                    {{$product->brand_id!=null ? \App\Models\Brand::where('id',$product->brand)->value('title'):''}}   
                     
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-12">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium  form-label">Mô tả ngắn:</label>
                                                    <p>
                                                    <?php echo $product->summary ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-12">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium  form-label">Mô tả:</label>
                                                    <p>
                                                    <?php echo $product->description?>
                                                     
                                                    </p>
                                                </div>
                                            </div>
                                           
                                        </div>
                                         
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- END: Display Information -->
                        <!-- BEGIN: Personal Information -->
                     
                        <!-- END: Personal Information -->
                    </div>
                </div>
        
     
</div>
@endsection

@section ('scripts')
  
 
@endsection