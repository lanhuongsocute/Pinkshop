@extends('frontend.layouts.master')
@section('css')
     
@endsection
@section('content')

    <!--  dashboard section start -->
    <section class="dashboard-section section-b-space user-dashboard-section">
        <div class="container">
            <div class="row">
                <!-- left side bar -->
                @foreach ($orders as $order )
                    <a  href="javascript:void(0)" onclick="show({{$order->id}})" class="card mb-4 lift">
                        <div class="card-body p-5">
                            <span class="flex flex-wrap row">
                                <span class="col ">
                                  
                                <span class=" col ">{{$order->id}}</span> 
                                    {{substr($order->created_at,0,10)}}
                                </span>
                                <span class="col ">
                                    {{number_format($order->final_amount,0,'.',',')}}
                                </span>
                                <span class=" col">
                                    {{$order->status}} 
                                </span>
                                
                            </span>
                            <div id="order{{$order->id}}" style="background: #eee; display:none; padding-left: 10px; padding-top:10px">
                                @foreach ($order->details as $orderdetail )
                                    <div class="card-body p-2">
                                        <span class="flex flex-wrap row  ">
                                            <span class="  col">
                                                {{$orderdetail->title}}
                                            </span>
                                            <span class=" col">
                                                {{number_format($orderdetail->price,0,'.',',')}}
                                            </span>
                                            <span class= " col ">
                                                {{$orderdetail->quantity}} 
                                            </span>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </a>
                @endforeach
                 <!-- right side content -->
            </div>
        </div>
    </section>
    <!--  dashboard section end -->
   
 
 
@endsection
@section('scripts')
<script>
function show(id)
{
    $("#order" + id).toggle();
}
</script>
@endsection
