@extends('backend.layouts.master')
@section('content')

<div class="content">
@include('backend.layouts.notification')
<form action="{{route('kiot.updatebenefit')}}" method = "post">
        @csrf
        
        <button type="submit" class ="btn"> Cập nhật lợi nhuận </button>
    </form>
    <br/>
    <form action="{{route('kiot.categoryupdate')}}" method = "post">
        @csrf
        
        <button type="submit" class ="btn"> Cập nhật danh mục </button>
    </form>
    <br/>
    <form action="{{route('kiot.productupdate')}}" method = "post">
        @csrf
        
        <button type="submit" class ="btn"> Cập nhật sản phẩm </button>
    </form>
    <br/>
    <form action="{{route('kiot.customerupdate')}}" method = "post">
        @csrf
        
        <button type="submit" class ="btn"> Cập nhật khách hàng </button>
    </form>
    <br/>
    <form action="{{route('kiot.brancheupdate')}}" method = "post">
        @csrf
        
        <button type="submit" class ="btn"> Cập nhật danh sách kho </button>
    </form>
    <br/>
    <form action="{{route('kiot.userupdate')}}" method = "post">
        @csrf
        <button type="submit" class ="btn"> Cập nhật người dùng </button>
    </form>
    <br/>
    <form action="{{route('kiot.bankaccountupdate')}}" method = "post">
        @csrf
        <button type="submit" class ="btn"> Cập nhật tài khoản ngân hàng </button>
    </form>
    <br/>
    <form action="{{route('kiot.customergroupupdate')}}" method = "post">
        @csrf
        <button type="submit" class ="btn"> Cập nhật nhóm khách hàng </button>
    </form>
    <br/>
    <form action="{{route('kiot.warehouseinupdate')}}" method = "post">
        @csrf
        <button type="submit" class ="btn"> Cập nhật nhập hàng </button>
    </form>
    <br/>
    <form action="{{route('kiot.flowupdate')}}" method = "post">
        @csrf
        <button type="submit" class ="btn"> Cập nhật nhập sổ quỹ </button>
    </form>
    <br/>
    <form action="{{route('kiot.warehouseoutupdate')}}" method = "post">
        @csrf
        <button type="submit" class ="btn"> Cập nhật đơn bán hàng </button>
    </form>

</div>
@endsection
@section('scripts')
 
@endsection