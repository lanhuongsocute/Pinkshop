@extends('backend.layouts.master')
@section('css')
<style>
* {
  box-sizing: border-box;
}

/* Set a background color */
 

/* The actual timeline (the vertical ruler) */
.timeline {
  position: relative;
  max-width: 1200px;
  margin: 0 auto;
}

/* The actual timeline (the vertical ruler) */
.timeline::after {
  content: '';
  position: absolute;
  width: 6px;
  background-color: white;
  top: 0;
  bottom: 0;
  left: 50%;
  margin-left: -3px;
}

/* Container around content */
.container {
  padding: 10px 40px;
  position: relative;
  background-color: inherit;
  width: 50%;
}

/* The circles on the timeline */
.container::after {
  content: '';
  position: absolute;
  width: 25px;
  height: 25px;
  right: -17px;
  background-color: white;
  border: 4px solid #FF9F55;
  top: 15px;
  border-radius: 50%;
  z-index: 1;
}

/* Place the container to the left */
.left {
  left: -5px;
}

/* Place the container to the right */
 

/* Add arrows to the left container (pointing right) */
.left::before {
  content: " ";
  height: 0;
  position: absolute;
  top: 22px;
  width: 0;
  z-index: 1;
  right: 30px;
  border: medium solid white;
  border-width: 10px 0 10px 10px;
  border-color: transparent transparent transparent white;
}

/* Add arrows to the right container (pointing left) */
.right::before {
  content: " ";
  height: 0;
  position: absolute;
  top: 22px;
  width: 0;
  z-index: 1;
  left: 30px;
  border: medium solid white;
  border-width: 10px 10px 10px 0;
  border-color: transparent white transparent transparent;
}

/* Fix the circle for containers on the right side */
.right::after {
  left: -12px;
}

/* The actual content */
.content_time {
  padding: 20px 30px;
  background-color: white;
  position: relative;
  border-radius: 6px;
}

/* Media queries - Responsive timeline on screens less than 600px wide */
@media screen and (max-width: 600px) {
/* Place the timelime to the left */
  .timeline::after {
    left: 31px;
  }

/* Full-width containers */
  .container {
    width: 100%;
    padding-left: 70px;
    padding-right: 25px;
  }

/* Make sure that all arrows are pointing leftwards */
  .container::before {
    left: 60px;
    border: medium solid white;
    border-width: 10px 10px 10px 0;
    border-color: transparent white transparent transparent;
  }

/* Make sure all circles are at the same spot */
  .left::after, .right::after {
    left: 15px;
  }

/* Make all right containers behave like the left ones */
  .right {
    left: 0%;
  }
}
</style>
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
                    {{$supplier->full_name}}
                </div>
                <div class= "mt-3">
                    <label class="font-medium"> Tổng công nợ: </label>
                    <span class="{{$supplier->budget > 0?'text-danger':'text-success'}}">{{Number_format($supplier->budget,0,'.',',')}}</span>
                    <br/><span class="form-help"> (-) đối tác nợ tiền , (+) cửa hàng nợ tiền </span>
                </div>
              </div>
              <div class="mt-3 lg:w-auto   lg:mt-0 ml-auto">
                <a href="{{route('supplier.received',$supplier->id)}}" class="btn btn-primary shadow-md mr-2 primary-btn lg:w-auto   lg:mt-0 ml-auto" > nhận tiền từ đối tác </a>
                
                <a href="{{route('supplier.paid',$supplier->id)}}" class="btn btn-primary shadow-md mr-2 primary-btn lg:w-auto   lg:mt-0 ml-auto" > chuyển tiền cho đối tác </a>
                <a href="{{route('supplier.balance',$supplier->id)}}" class="btn btn-primary shadow-md mr-2 primary-btn lg:w-auto   lg:mt-0 ml-auto" > khấu trừ tiền công nợ </a>
             
              </div>
               
            </div>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Chi tiết công nợ 
        </h2>
    </div>
    <div class=" timeline intro-y  ">
        @foreach ($suptrans as $sp )
            <div  style='clear:both'  >
                <div class="container {{$sp->operation==-1?'right':'left'}}"style="float:{{$sp->operation==-1?'right':'left'}}">
                    <div class="content_time">
                      <div class="flex items-center">
                          <div class="font-medium  ">{{$sp->created_at}}</div>
                          <?php
                            $str_route = "";
                            if($sp->doc_type == 'mi' && $sp->operation > 0)
                            {
                                $str_route = route('maintainback.show',$sp->doc_id);
                            }
                            if($sp->doc_type == 'mi' && $sp->operation < 0)
                            {
                                $str_route = route('maintainin.show',$sp->doc_id);
                            }
                            if($sp->doc_type == 'wi')
                            {
                                $str_route = route('warehousein.show',$sp->doc_id);
                            }
                            if($sp->doc_type == 'wo')
                            {
                                $str_route = route('warehouseout.show',$sp->doc_id);
                            }
                            if($sp->doc_type == 'fi')
                            {
                                $str_route = route('suptransaction.show',$sp->id);
                            }
                            if($sp->doc_type=="wir")
                            {
                                $str_route = route('warehousein.showold',$sp->doc_id);
                            }
                            if($sp->doc_type=="wor")
                            {
                                $str_route = route('warehouseout.showold',$sp->doc_id);
                            }
                            echo ' <a href="'.$str_route.'" class="btn text-xs bg-white px-1 rounded-md text-slate-700 ml-auto">Print</a>';
                          ?>
                        
                      </div>
                    <p>
                        
                        <?php
                                if($sp->doc_type == 'mi')
                                {
                                    
                                  if($sp->operation == 1)
                                  {
                                    echo '<a class="font-medium" href ="'.route('maintainback.show',$sp->doc_id )
                                    .'"> phiếu trả bảo hành: '.$sp->doc_id .'</a> : '.  Number_format($sp->amount,0,'.',',');
                                    $tt = \App\Models\MaintainBack::find($sp->doc_id) ;
                                    if($tt->paid_amount == $tt->final_amount)
                                    {
                                      echo ' <span class="text-success">Đã hoàn thành </span>';
                                    }
                                    else
                                    {
                                      echo '<br/> <span class="text-danger">Số tiền chưa thanh toán: '.Number_format($tt->final_amount - $tt->paid_amount,0,'.',',').' </span>';
                                    }
                                  }
                                  else
                                  {
                                    echo '<a class="font-medium" href ="'.route('maintainin.show',$sp->doc_id )
                                    .'"> phiếu nhận bảo hành: '.$sp->doc_id .'</a> : '.  Number_format($sp->amount,0,'.',',');
                                    $tt = \App\Models\MaintenanceIn::find($sp->doc_id) ;
                                    if($tt->paid_amount == $tt->final_amount)
                                    {
                                      echo ' <span class="text-success">Đã hoàn thành </span>';
                                    }
                                    else
                                    {
                                      echo '<br/> <span class="text-danger">Số tiền chưa thanh toán: '.Number_format($tt->final_amount - $tt->paid_amount,0,'.',',').' </span>';
                                    }
                                  }
                                }
                                if($sp->doc_type == 'wir')
                                {
                                  $wir = \App\Models\DIn::find($sp->doc_id);
                                  echo '<a class="font-medium text-warning" href ="'.route('warehousein.showold',$sp->doc_id )
                                  .'">phiếu nhập cũ: '.$wir->inid .'</a> : '.  Number_format($sp->amount,0,'.',',');
                                  
                                }
                                if($sp->doc_type == 'wi')
                                {
                                    if($sp->operation == 1)
                                    {
                                        $tt = \App\Models\WarehouseIn::find($sp->doc_id) ;
                                        $status = '';
                                        if($tt->status != 'active')
                                          $status = "(".$tt->status.")";
                                        echo '<a class="font-medium" href ="'.route('warehousein.show',$sp->doc_id )
                                        .'"> phiếu nhập '.  $status.' '.$sp->doc_id .'</a> : '.  Number_format($sp->amount,0,'.',',');
                                       
                                        if( $tt && $tt->is_paid)
                                        {
                                          echo ' <span class="text-success">Đã hoàn thành </span>';
                                        }
                                        if( $tt && !$tt->is_paid)
                                        {
                                          echo '<br/> <span class="text-danger">Số tiền chưa thanh toán: '.Number_format($tt->final_amount - $tt->paid_amount,0,'.',',').' </span>';
                                        }
                                        if( !$tt )
                                        {
                                          echo 'ki cuc'.$sp->doc_id;
                                        }
                                    }
                                    
                                   
                                }
                                if($sp->doc_type == 'wor')
                                {
                                  $wor = \App\Models\DOut::find($sp->doc_id);
                                  echo '<a class="font-medium text-warning" href ="'.route('warehouseout.showold',$sp->doc_id )
                                  .'"> phiếu xuất cũ: '. $wor->outid .'</a> : '.  Number_format($sp->amount,0,'.',',');
                                  
                                }
                                if($sp->doc_type == 'wo')
                                {
                                    if($sp->operation == -1)
                                    {
                                        $tt = \App\Models\Warehouseout::find($sp->doc_id) ;
                                        $status = '';
                                        if($tt->status != 'active')
                                          $status = "(".$tt->status.")";
                                        echo '<a class="font-medium" href ="'.route('warehouseout.show',$sp->doc_id )
                                        .'"> phiếu xuất '.$status.' '.$sp->doc_id .'</a>  '.  Number_format($sp->amount,0,'.',',');
                                       
                                        if($tt->is_paid)
                                        {
                                          echo ' <span class="text-success">Đã hoàn thành </span>';
                                        }
                                        else
                                        {
                                          echo '<br/> <span class="text-danger">Số tiền chưa thanh toán: '.Number_format($tt->final_amount - $tt->paid_amount,0,'.',',').' </span>';
                                        }
                                    }
                                    // else
                                    // {
                                    //   echo '<a class="font-medium text-warning" href ="'.route('warehouseout.show',$sp->doc_id )
                                    //     .'"> phiếu trả hàng: '.$sp->doc_id .'</a> : '.  Number_format($sp->amount,0,'.',',');
                                      
                                    // }
                                   
                                }
                                if($sp->doc_type == 'fi' &&  $sp->doc_id!= 0)
                                {
                                 
                                    echo '<a class="font-medium" href ="'.route('suptransaction.show',$sp->id)
                                    .'"> Phiếu giao dịch: '.$sp->id.' </a> ';
                                    if($sp->content != null)
                                      echo '<span>('.$sp->content.')</span>';
                                   
                                    echo ' với số tiền: '.Number_format($sp->amount,0,'.',',');
                                
                                }
                                if($sp->doc_type == 'fo' )
                                {
                                  echo "hoàn phiếu nộp tiền: " . Number_format($sp->amount,0,'.',',');
                                }
                            ?>
                           
                        <br/>
                        công nợ: <span class="{{$sp->total <= 0?'text-success':''}}">{{Number_format($sp->total,0,'.',',')}}<span>
                    </p>
                    <p></p>
                    </div>
                </div>
            </div>
            
        @endforeach

           
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
</div>
@endsection

@section ('scripts')

 
@endsection
                                      