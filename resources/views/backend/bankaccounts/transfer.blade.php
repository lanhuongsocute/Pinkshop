@extends('backend.layouts.master')
@section('content')
<div class="content">
    @include('backend.layouts.notification')
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                       Chuyển khoản  
                    </h2>
                   
    </div>
     <!-- BEGIN: Form Layout -->   
     <form method="post" action="{{route('bankaccount.savetransfer')}}">
            @csrf   
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 lg:col-span-6">
                <div class="intro-y box p-5">
                    <div class="mt-3">
                       <label class="font-medium"> Tài khoản chuyển: </label>
                       <span>{{$bankaccount->title}}</span>
                       <input type ="hidden" name="firstbank_id" value="{{$bankaccount->id}}"/> 
                    </div>
                    <div class="mt-3">
                       <label class="font-medium"> Số tiền hiện có: </label>
                       <span>{{Number_format($bankaccount->total,0,'.',',')}}</span>
                    </div>
                    <div class="mt-3">
                       <label class="font-medium"> Số tiền chuyển: </label>
                       <input type="number" name="total" value="0"/>
                    </div>
                </div>
            </div>
            <div class="intro-y col-span-12 lg:col-span-6">
                <div class="intro-y box p-5">
                    <div class="mt-3">
                    <label class="font-medium"> Tài khoản hưởng thụ: </label>       
                    <select name="secondbank_id" class="form-select mt-2 sm:mr-2"   >
                           @foreach ($banklist as $bank)
                                <option value ="{{$bank->id}}"  >{{$bank->title}}</option>
                           @endforeach    
                    </select>
                    <div class="form-help mt-6">
                        * Kiểm tra số tiền, tài khoản trước khi lưu. Thông tin sẽ không được điều chỉnh sau khi lưu.
                    </div>
                
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5">
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
           
                <button type="submit" class="btn btn-primary w-24">Lưu</button>
           
        </div>                   
    </form>          
</div>

@endsection
@section('scripts')
<script>
   
</script>

@endsection
