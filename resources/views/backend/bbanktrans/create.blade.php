@extends('backend.layouts.master')
@section('content')

<div class = 'content'>
@include('backend.layouts.notification')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thêm nhập quỹ đầu kỳ
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('bbanktrans.store')}}">
                @csrf
                <div class="intro-y box p-5">
                    <div class="mt-6">
                        <label class="font-medium"> Tài khoản: </label>       
                        <select name="bank_id" class="form-select mt-2 sm:mr-2"   >
                            @foreach ($banklist as $bank)
                                    <option value ="{{$bank->id}}"  >{{$bank->title}}</option>
                            @endforeach    
                        </select>
                       
                    </div>
                    <div>
                        <label for="regular-form-1" class="form-label">Số tiền ban đầu</label>
                        <input id="amount" name="amount" type="text" class="form-control" value="0" >
                        
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
                    <div class="form-help mt-8">
                            * Kiểm tra số tiền, tài khoản trước khi lưu. Thông tin sẽ không được điều chỉnh sau khi lưu.
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