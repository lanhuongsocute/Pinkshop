@extends('backend.layouts.master')
@section('content')
<div class="content">
    @include('backend.layouts.notification')
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Hoàn tiền phiếu bán hàng số: {{$wo->id}}
                    </h2>
                   
                </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('warehouseout.savereturnall')}}">
                @csrf
                <div class="intro-y box p-5">
                    <div>
                        <input type="hidden" name="id" value = "{{$wo->id}}"/>
                        <label for="regular-form-1 " class="form-label font-medium">Tổng tiền hóa đơn :</label> 
                        {{number_format($wo->final_amount  ,0,'.',',')}} <br/>
                        <label for="regular-form-1" class="form-label font-medium">Tổng tiền đã thanh toán :</label> 
                        {{number_format($wo->paid_amount  ,0,'.',',')}} <br/>
                        <label for="regular-form-1" class="form-label font-medium">Số tiền cần hoàn lại :</label> 
                        {{number_format( $wo->paid_amount,0,'.',',')}} <br/>
                    </div>
                    <div>
                        <label for="regular-form-1" class="form-label">Số tiền </label>
                        <input id="paid_amount" name="paid_amount" value = "{{  $wo->paid_amount}}"
                            type="number" class="form-control" placeholder="">
                    </div>
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label"  for="status">Tài khoản</label>
                           
                            <select name="bank_id" class="form-select mt-2 sm:mr-2"   >
                                @foreach ($bankaccounts as $bank )
                                    <option value ="{{$bank->id}}">{{$bank->title}}</option>
                                @endforeach
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
        </div>
    </div>                   
                
    
</div>

@endsection
@section('scripts')
<script>
   
</script>

@endsection
