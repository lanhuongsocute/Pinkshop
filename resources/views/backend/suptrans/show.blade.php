@extends('backend.layouts.master')
@section('css')
 
@endsection
@section('content')
<div class = 'content'>
@include('backend.layouts.notification')

      <div class="intro-y flex items-center mt-8">
          <h2 class="text-lg font-medium mr-auto">
              Thông tin công nợ 
          </h2>
      </div>
      <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
          <div class="lg:flex intro-y box py-5 px-5">
            <div class='relative'> 
              <div class= "mt-3">
                  <label class="font-medium"> Đối tác: </label>
                  {{$user->full_name}}
              </div>
              <div class= "mt-3">
                  <label class="font-medium"> Tổng công nợ: </label>
                  <span class="{{$user->budget > 0?'text-danger':'text-success'}}">{{Number_format($user->budget,0,'.',',')}}</span>
                  <br/><span class="form-help"> (-) đối tác nợ tiền , (+) cửa hàng nợ tiền </span>
              </div>
            </div>
            <div class="mt-3 lg:w-auto   lg:mt-0 ml-auto">
              <a href="{{route('user.usertostore',$user->id)}}" class="btn btn-primary shadow-md mr-2 primary-btn lg:w-auto   lg:mt-0 ml-auto" > nhận tiền từ đối tác </a>
              <a href="{{route('user.storetouser',$user->id)}}" class="btn btn-primary shadow-md mr-2 primary-btn lg:w-auto   lg:mt-0 ml-auto" > chuyển tiền cho đối tác </a>
           
            </div>
        </div>
        <div class="intro-y flex items-center mt-8">
            <h2 class="text-lg font-medium mr-auto">
                Chi tiết tài khoản đối tác
            </h2>
        </div>
        <div class=" timeline intro-y  ">
          <table class="table table-report -mt-2">
            <thead>
              <tr>
                  <th class="text-center whitespace-nowrap">Thời gian</th>
                  <th class="whitespace-nowrap">Loại</th>
                  <!-- <th class="text-center whitespace-nowrap">Chưa thanh toán</th> -->
                  
                  <th class="whitespace-nowrap">Tăng</th>
                  <th class="whitespace-nowrap">Giảm</th>
                  <th class="text-center whitespace-nowrap">Số dư</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($suptrans as $sp )
                <?php
                  if ($sp->total < 0)
                  {
                      $classname = "text-danger";
                  }
                  else
                  {
                      $classname = "text-primary";
                  }
                ?>
                <?php
                  $str_route = "";
                  $status = "";
                  $loai = "";
                  $doc_notpaid= 0;
                  $ptotal = 0;
                  $stotal = 0;
                  if($sp->operation > 0)
                    $ptotal = Number_format($sp->amount,0,'.',',');
                  else
                    $stotal = Number_format($sp->amount,0,'.',',');

                  if($sp->doc_type == 'mb' )
                  {
                      if($sp->operation == 1)
                      {
                          $loai = 'phiếu trả bảo hành';
                          $str_route = route('maintainback.show',$sp->doc_id);
                      }
                      else
                      {
                          $loai = 'phiếu nhận bảo hành ';
                          $tt = \App\Models\MaintenanceIn::find($sp->doc_id) ;
                          $str_route = route('maintainin.show',$sp->doc_id);
                      }
                      $tt = \App\Models\MaintainBack::find($sp->doc_id) ;
                      $doc_notpaid = Number_format($tt->final_amount - $tt->paid_amount,0,'.',',') ;
                  }
                  
                  if($sp->doc_type == 'wi')
                  {
                    $str_route = route('warehousein.show',$sp->doc_id);
                    if($sp->operation == 1)
                    {
                        $tt = \App\Models\WarehouseIn::find($sp->doc_id) ;
                        if ($tt)
                        {
                          $status = '';
                          if($tt->status != 'active')
                            $status = "(".$tt->status.")";
                          $loai = 'phiếu nhập ';
                          $doc_notpaid = Number_format($tt->final_amount - $tt->paid_amount,0,'.',',') ;
                        }
                    }
                  }
                  if($sp->doc_type == 'wo')
                  {
                      $str_route = route('warehouseout.show',$sp->doc_id);
                      if($sp->operation == -1)
                      {
                        $tt = \App\Models\Warehouseout::find($sp->doc_id) ;
                        if($tt)
                        {
                          $status = '';
                          if($tt->status != 'active')
                            $status = "(".$tt->status.")";
                          $loai = 'phiếu xuất';
                          $doc_notpaid = Number_format($tt->final_amount - $tt->paid_amount,0,'.',',') ;
                        }
                      }
                  }
                  if($sp->doc_type == 'fi')
                  {
                      $str_route = route('suptransaction.show',$sp->id);
                      $loai = 'Phiếu giao dịch';
                    
                  }
                  if($sp->doc_type == 'fo' )
                  {
                      $loai = "Phiếu giao dịch hủy/trả xuất hàng";
                      if($sp->operation > 0)
                        $ptotal = Number_format($sp->amount,0,'.',',');
                      else
                        $stotal = Number_format($sp->amount,0,'.',',');
                  }
                  if($sp->doc_type=="wir")
                  {
                      $str_route = route('warehousein.showold',$sp->doc_id);
                      $wir = \App\Models\DIn::find($sp->doc_id);
                      $loai = 'phiếu nhập cũ';
                  }
                  if($sp->doc_type=="wor")
                  {
                      $str_route = route('warehouseout.showold',$sp->doc_id);
                      $loai = 'phiếu xuất cũ';
                  }
               
                ?>
                
                <tr>
                  <td> {{$sp->created_at}}</td>
                  <td><a href="{{$str_route}}"> {{$loai}}  {{$sp->doc_id}}</a></td>
                  <!-- <td>{{$doc_notpaid}} </td> -->
                  <td> {{$ptotal}} </td>
                  <td> {{$stotal}} </td>
                  <td class='{{$classname}}'> {{Number_format($sp->total,0,'.',',') }} </td>
                </tr>
               
              @endforeach
            </tbody>
          </table>
          <div style='clear:both' class="  ">
          &nbsp;
          </div>
          <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            <nav class="w-full sm:w-auto sm:mr-auto">
                {{$suptrans->links('vendor.pagination.tailwind')}}
            </nav>
          </div>
        </div>
      </div>
     
</div>
@endsection

@section ('scripts')

 
@endsection
                                      