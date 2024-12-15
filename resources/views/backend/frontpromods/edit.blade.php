@extends('backend.layouts.master')
 
@section('content')

<div class = 'content'>
@include('backend.layouts.notification')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Điều chỉnh mod
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('modpro.update',$frontmod->id)}}">
                @csrf
                @method('patch')
                <div class="intro-y box p-5">
                    <div>
                        <label for="regular-form-1" class="form-label">Tiêu đề</label>
                        <input value="{{$frontmod->title}}" id="title" name="title" type="text" class="form-control" placeholder="tên">
                    </div>
                     
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Vị trí</label>
                        <input id="order_id"  value="{{$frontmod->order_id}}" name="order_id" type="number" class="form-control" placeholder="vị trí sắp xếp">
                        
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Loại (0 - hotsale)</label>
                        <input id="type_id" name="mod_type"   value="{{$frontmod->mod_type}}" type="number" class="form-control" placeholder="loại">
                    </div>
                    
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Loại (0 - hotsale)</label>
                        <input id="op_type" name="op_type"   value="{{$frontmod->op_type}}" type="number" class="form-control" placeholder="loại">
                    </div>
                    
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label"  for="status">Tình trạng</label>
                           
                            <select name="status" class="form-select mt-2 sm:mr-2"   >
                              
                                <option value ="active" {{$frontmod->status=='active'?'selected':''}}>Active</option>
                                <option value = "inactive" {{$frontmod->status =='inactive'?'selected':''}}>Inactive</option>
                            </select>
                        </div>
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
            <!-- end form layout -->
        </div>
    </div>
</div>
@endsection

@section ('scripts')

 
@endsection