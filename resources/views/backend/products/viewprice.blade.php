@extends('backend.layouts.master')
@section ('scriptop')

 
 
@endsection
@section('content')

<div class = 'content'>
@include('backend.layouts.notification')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Điều chỉnh giá
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('product.priceupdate')}}">
                @csrf
                <input type="hidden" name="id" value = "{{$product->id}}" />

                <div class="intro-y box p-5">
                     @foreach ($group_prices as $gprice )
                     <div class="mt-3">
                        <label for="regular-form-1" class="form-label">{{$gprice->title}}</label>
                        <input id="weight" name="gp{{$gprice->id}}" value="{{$gprice->price}}" type="text" class="form-control"  >
                    </div>
                    
                     @endforeach
                     <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Giá trước khuyến mãi</label>
                        <input id="old_price" name="old_price" value="{{$productextend->old_price}}" type="text" class="form-control"  >
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Giá chung hiện tại</label>
                        <input id="price" name="price" value="{{$product->price}}" type="text" class="form-control"  >
                    </div>
                    <div class="mt-3">
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>    {{$error}} </li>
                                    @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Lưu</button>
                    </div>
                </div>
            </form>
             
        </div>
    </div>
</div>
@endsection
 