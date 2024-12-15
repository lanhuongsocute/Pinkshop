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
                        <a href="{{route('product.show',$product->id)}}"> {{$product->title }}  </a>
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
                        <th class="whitespace-nowrap">SERIES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($series as $seri )
                    <tr class="intro-x">
                        <td>
                             {{$seri->seri }}  
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
                        <th class="whitespace-nowrap">Mã phiếu</th>
                        <th class="whitespace-nowrap">Nhà cung cấp</th>
                        <th class="whitespace-nowrap">Số lượng</th>
                        <th class="whitespace-nowrap">Đơn giá</th>
                        <th class="whitespace-nowrap">Tồn kho</th>
                        <th class="whitespace-nowrap">Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail_ins as $detail_in )
                        @if(/*$detail_in->doc_id != 0*/1)
                            <?php
                                
                                $class_name = $detail_in->quantity < 0?'text-danger':'text-primary';

                            ?>
                            <tr class="intro-x {{ $class_name}}">
                                <td> 
                                    <?php
                                    if($detail_in->doc_id == 0)
                                    {
                                        echo 'Giao dịch hủy';
                                    }
                                    else
                                    {
                                        if($detail_in->doc_type=="wi")
                                        {
                                            $url = route('warehousein.show',$detail_in->doc_id);
                                        }
                                        if($detail_in->doc_type=="wo")
                                        {
                                            $url = route('warehouseout.show',$detail_in->doc_id);
                                        }
                                        if($detail_in->doc_type=="ic")
                                        {
                                            $url = route('inventorycheck.show',  $detail_in->doc_id);
                                        }
                                        if($detail_in->doc_type=="wi" || $detail_in->doc_type=="wo")
                                            echo '<a href="'.$url.'">'. $detail_in->code .'</a>';
                                        else
                                        {
                                            if($detail_in->doc_type=="ic")
                                            {
                                                echo '<a href="'.$url.'"> phiếu kiểm kho </a>';
                                            }
                                            else
                                            {
                                                if($detail_in->doc_type =='mi')
                                                {
                                                    echo 'phiếu chuyển kho bảo hành';
                                                }  
                                                else
                                                {
                                                    if($detail_in->doc_type =='mi')
                                                    {
                                                        echo 'kho bảo hành chuyển kho bán';
                                                        
                                                    }  
                                                    else
                                                    {
                                                        if($detail_in->doc_type =='wm')
                                                        {
                                                            echo 'kho bán chuyển kho bảo hành';
                                                        }  
                                                        else
                                                        {
                                                            if($detail_in->doc_type =='wd')
                                                            {
                                                                echo 'kho bán chuyển kho hủy';
                                                            }  
                                                            else
                                                            {
                                                                if($detail_in->doc_type =='wp')
                                                                {
                                                                    echo 'kho bán chuyển kho tài sản';
                                                                }  
                                                                else
                                                                {
                                                                    if($detail_in->doc_type =='pi')
                                                                    {
                                                                        echo 'kho tài sản chuyển kho bán';
                                                                    }  
                                                                    else
                                                                    {
                                                                        if($detail_in->doc_type =='ti')
                                                                        {
                                                                            echo '<a href="'.route('warehousetransfer.show',$detail_in->doc_id).'">Phiếu chuyển kho</a>';
                                                                        }  
                                                                        else
                                                                        {
                                                                            if($detail_in->doc_type =='co')
                                                                            {
                                                                                echo '<a href="'.route('combocreation.show',$detail_in->doc_id).'">Phiếu tạo combo</a>';
                                                                            }  
                                                                            else
                                                                            {
                                                                                echo $detail_in->doc_type;
                                                                            } 
                                                                        } 
                                                                    } 
                                                                }
                                                            } 
                                                        } 
                                                    } 
                                                } 
                                            }
                                        }
                                    }
                                    
                                        ?>
                                </td>
                                <td>
                                
                                    @if ($detail_in->doc_type=="wi" || $detail_in->doc_type=="wo")
                                    <?php
                                    if (isset($detail_in->user_id))
                                        $url_user = route('user.showsup',$detail_in->user_id);
                                    else
                                        $url_user = "";
                                    ?>
                                    <a href="{{$url_user}}">  {{ $detail_in->user_id?\App\Models\User::find($detail_in->user_id)->full_name:''  }}  </a>
                                    @endif
                                    
                                </td>
                                <td>
                                    {{$detail_in->quantity }}  
                                </td>
                                <td>
                                    {{$detail_in->price }}  
                                </td>
                                <td>
                                    {{$detail_in->prebalance  + $detail_in->quantity }}  
                                </td>
                                <td>
                                    {{$detail_in->created_at}}
                                </td>
                            </tr>
                        @endif
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