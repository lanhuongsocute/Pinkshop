@extends('backend.layouts.master')
@section('content')

<div class = 'content'>
@include('backend.layouts.notification')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
           Cập nhật hệ thống
        </h2>
         
        
    </div>
    <div class="mt-3">
        <form action="{{route('setting.updateinvpro')}}" method = "post">
         @csrf
        
        <button type="submit" class ="btn"> Cập nhật chương trình </button>
        </form>
    </div>
    <div class="mt-3">
        <form action="{{route('setting.updatesitemap')}}" method = "post">
         @csrf
        
        <button type="submit" class ="btn"> Cập nhật sitemap </button>
        </form>
    </div>
    <div class="mt-3">
        <form action="{{route('setting.kiemtracongno')}}" method = "post">
         @csrf
        
        <button type="submit" class ="btn"> Kiểm tra công nợ hệ thống </button>
        </form>
    </div>
    <div class="mt-3">
        <form action="{{route('setting.cnsp_brand')}}" method = "post">
         @csrf
        <input type="text" name="brand_id" placeholder="brand_id"/>
        <button type="submit" class ="btn"> Cập nhật sản phẩm theo danh mục </button>
        </form>
    </div>
    <div class="mt-3">
        <form action="{{route('setting.getbrand')}}" method = "post">
         @csrf
        <button type="submit" class ="btn">Xem danh mục</button>
        </form>
    </div>
    <div class="mt-3">
        <form action="{{route('setting.testapi')}}" method = "post">
         @csrf
        <button type="submit" class ="btn">Test api</button>
        </form>
    </div>
</div>
@endsection

@section ('scripts')

 
@endsection