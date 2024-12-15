@extends('backend.layouts.master')
@section('content')

<div class="content">
@include('backend.layouts.notification')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách tồn kho  
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        
        

        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">SẢN PHẨM</th>
                        <th class="text-center whitespace-nowrap">KHO</th>
                        <th class="text-center whitespace-nowrap">SỐ LƯỢNG</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="intro-x">
                        <td>
                             {{$product->title }}  
                        </td>
                        <td>
                             {{\App\Models\Warehouse::where('id',$inventory->wh_id)->value('title')}}  
                        </td>
                        <td class='text-center'>
                             {{ $inventory->quantity}}  
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">phiếu nhập</th>
                        <th class="whitespace-nowrap">số lượng</th>
                        <th class="whitespace-nowrap">người nhập</th>
                        <th class="whitespace-nowrap">ngày nhập</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($wtps as $wtp )
                    <tr class="intro-x">
                        <td>
                             {{$wtp->code }}  
                        </td>
                        <td>
                             {{$wtp->quantity }}  
                        </td>
                        <td>
                            <?php
                                $user = \App\Models\User::find($wtp->vendor_id);
                            ?>
                             {{$user?$user->full_name:'không xác định' }}  
                        </td>
                        <td>
                             {{$wtp->created_at }}  
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">SERIES</th>
                        <th class="whitespace-nowrap">ngày nhập</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($series as $seri )
                    <tr class="intro-x">
                        <td>
                             {{$seri->seri }}  
                        </td>
                        <td>
                             {{$seri->created_at }}  
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- END: HTML Table Data -->
       
</div>
<!-- end content -->
  
   
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('backend/assets/vendor/js/bootstrap-switch-button.min.js')}}"></script>
<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });
     
</script>
  
@endsection