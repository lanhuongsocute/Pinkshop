@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>

<link href="{{asset('/js/css/tom-select.min.css')}}" rel="stylesheet">
<script src="{{ asset('/js/js/tom-select.complete.min.js') }}"></script>
@endsection
@section('content')
@include('backend.layouts.notification')
<div class = 'content'>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thêm bình luận
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('comment.store')}}">
                @csrf
                <div class="intro-y box p-5">
                    <div>
                        <label for="regular-form-1" class="form-label">URL</label>
                        <input id="url" name="url" type="text" class="form-control" placeholder="url bình luận" required>
                    </div>
                     
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Tên</label>
                        <input id="name" name="name" type="text" class="form-control" placeholder="tên người đăng" required>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Email</label>
                        <input id="email" name="email" type="text" class="form-control" placeholder="email" required>
                    </div>
                    <div class="mt-3">
                        
                        <label for="" class="form-label">Nội dung</label>
                       
                        <textarea class="form-control"   id="editor1" name="content" >{{old('summary')}}</textarea>
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

@section ('scripts')

 
@endsection