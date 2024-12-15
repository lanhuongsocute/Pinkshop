<div id="divprint" class="intro-y box overflow-hidden  px-10">
                    <div class="border-b border-slate-200/60 dark:border-darkmode-400 text-center sm:text-left">
                        <div class="px-1 py-5 sm:px-1 sm:py-2">
                            <style>
                                .divclass_p {
                                    display: flex;
                                    justify-content: center;
                                }
                                .header_p {
                                display: flex;
                                align-items: center; /* Vertically aligns the content */
                                justify-content: space-between; /* Adds space between the logo and text */
                                }

                                .logo_p {
                                max-width: 100%; /* Ensures logo doesn't exceed its container */
                                max-height: 80px; /* Set a reasonable max height for the logo */
                                height: auto; /* Maintain aspect ratio */
                                }

                                .company-info_p {
                                margin-left: 10px; /* Adds some space between the logo and text */
                                flex: 1; /* Allows the text to take the remaining available space */
                                text-align: right; /* Aligns the text to the right */
                                }

                                .company-name_p  {
                                display: block; /* Ensures text is displayed in block */
                                font-size: 24pt; /* Adjust font size as needed */
                               
                                font-weight:600;
                                }
                                .telephone-number_p {
                                display: block; /* Ensures text is displayed in block */
                                font-size: 18pt; /* Adjust font size as needed */
                                
                                }
                                .customer_p {
                                    display: block; /* Ensures text is displayed in block */
                                    font-size: 24pt; /* Adjust font size as needed */
                                
                                }
                                .customer_tel_p {
                                    display: block; /* Ensures text is displayed in block */
                                    font-size: 28pt; /* Adjust font size as needed */
                                }
                                @media print {
                                    .company-name_p {
                                        font-weight:600;
                                        font-size: clamp(18pt, 2vw, 24pt); /* Min 12pt, scales with viewport, max 18pt */
                                    }

                                    .telephone-number_p {
                                        font-size: clamp(16pt, 1.5vw, 24pt); /* Min 10pt, scales with viewport, max 16pt */
                                    }
                                    .customer_p {
                                        font-size: clamp(24pt, 1.5vw, 28pt); /* Min 10pt, scales with viewport, max 16pt */
                                    }
                                    .customer_tel_p {
                                        font-size: clamp(28pt, 1.5vw, 32pt); /* Min 10pt, scales with viewport, max 16pt */
                                    }
                                }
                            </style>
                             <?php $detail = \App\Models\SettingDetail::find(1); ?>
                            
                            <?php
                                $date_object = new DateTime($warehouseout->created_at);
                                $formatted_date = strftime('ngày %d tháng %m năm %Y', $date_object->getTimestamp());

                            ?>
                            <div class="header_p">
                                <img src="<?php echo e($detail->logo); ?>"  class="logo_p">
                                <div class="company-info">
                                    <span class="company-name_p"><?php echo e($detail->company_name); ?></span>
                                    <span class=" mt-4 telephone-number_p"><?php echo e($detail->phone); ?></span>
                                    <span class=" mt-4 telephone-number_p"><?php echo e($detail->address); ?></span>
                                </div>
                            </div>
                            
                            <div style="clear:both">&nbsp;<br/></div>
                             
                            <div style="text-align:center" class="text-primary font-semibold text-2xl">HÓA ĐƠN BÁN HÀNG</div>
                            <div style="text-align:center" class="mt-1 text-xl"> <?php echo e($formatted_date); ?></div>
                            <div style="clear:both">&nbsp;<br/></div>
                            <div style="text-align:center; " class=" mt-4 customer_p">
                                 Khách hàng: <?php echo e(\App\Models\User::where('id',$warehouseout->customer_id)->value('full_name')); ?>

                            </div>
                            <div style="text-align:center " class=" mt-6  customer_tel_p">SĐT: <?php echo e(\App\Models\User::where('id',$warehouseout->customer_id)->value('phone')); ?></div>
                            <div style="text-align:center " class=" mt-4 telephone-number_p">Địa chỉ: <?php echo e(\App\Models\User::where('id',$warehouseout->customer_id)->value('address')); ?></div>
                            
                             
                           
                            
                        </div>
                    </div>
                    <div class="px-1 py-2 sm:px-1 sm:py-2">
                        <div class="overflow-x-auto">
                            <table class="table" style="margin-bottom:10px">
                                <thead>
                                    <tr>
                                        <th  style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "> STT </th>
                                        <th style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="border-b-2 dark:border-darkmode-400  ">Hàng hóa</th>
                                        <th style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="border-b-2 dark:border-darkmode-400 text-right ">Số lượng</th>
                                        <th style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="border-b-2 dark:border-darkmode-400 text-right  ">Đơn giá</th>
                                        <th style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="border-b-2 dark:border-darkmode-400 text-right  ">Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;?>
                                    <?php $__currentLoopData = $wo_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "> 
                                            <?php echo $i; $i ++; ?>
                                        </td>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="border-b dark:border-darkmode-400">
                                            <?php
                                                $product= \App\Models\Product::find( $wi->product_id);
                                            ?>
                                            <div class="  ">
                                                <a  href="<?php echo e(route('inventory.viewproduct',$product->id)); ?>" > 
                                                    <?php echo e($product-> title); ?> 
                                                </a>

                                            </div>
                                            <div class="form-help">
                                            <?php echo e($product->expired != null ? 'bảo hành: '. $product->expired.' tháng':''); ?> 
                                            </div>
                                        </td>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right border-b dark:border-darkmode-400 ">
                                            <?php echo e($wi->quantity); ?>

                                        </td>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right border-b dark:border-darkmode-400 ">
                                            <?php echo e(number_format($wi->price, 0, '.', ',')); ?>

                                        </td>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " class="text-right border-b dark:border-darkmode-400   ">
                                        <?php echo e(number_format(($wi->quantity*$wi->price), 0, '.', ',')); ?>

                                        </td>
                                    </tr>
                                    <?php if($wi->series != ''): ?>
                                        <tr><td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " ></td><td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; " colspan="4">số seri:<?php echo e($wi->series); ?></td></tr> 
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                   
                                   
                                </tbody>
                                <tfooter>
                                    <tr>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  colspan="2">
                                            <span class="font-medium "> 
                                                Giảm giá: - <?php echo e(number_format($warehouseout->discount_amount, 0, '.', ',')); ?>

                                            </span> 
                                            <br/>
                                            <span class="font-medium "> 
                                                Phí vận chuyển: + <?php echo e($warehouseout->shiptrans_id? number_format(\App\Models\Freetransaction::find($warehouseout->shiptrans_id)->total,  0, '.', ','):'0'); ?>

                                            </span> 
                                        </td>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  colspan="2" class="text-right font-medium ">
                                            Tổng tiền hàng:
                                        </td>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right font-medium">
                                            <?php echo e(number_format($warehouseout->final_amount, 0, '.', ',')); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right" colspan="3">
                                             Nợ cũ:
                                        </td>
                                        
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right  " colspan="2">
                                            <?php echo e(number_format(-1*($amount_before_trans), 0, '.', ',')); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right" colspan="3">
                                           Phải thanh toán:
                                        </td>
                                        
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right  " colspan="2">
                                            <?php echo e(number_format(-1*($amount_before_paid ), 0, '.', ',')); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right" colspan="3">
                                           Đã thanh toán:
                                        </td>
                                        
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right  " colspan="2">
                                            <?php echo e(number_format($warehouseout->paid_amount, 0, '.', ',')); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right" colspan="3">
                                           Nợ hiện tại:
                                        </td>
                                        
                                        <td style="padding:2px !important; padding-top:6px !important; padding-bottom:6px !important; "  class="text-right " colspan="2">
                                            <?php echo e(number_format(-1*($amount_after_trans ), 0, '.', ',')); ?>

                                            <br/>
                                        </td>
                                    </tr>
                                </tfooter>
                            </table>
                          

                        </div>
                    </div>
                    <div class="px-1 py-2 sm:px-1 sm:py-2">
                        <table style="width:100%">
                            <tr>
                                <td style="width:50%">
                                    <div class="text-center sm:text-left mt-1 sm:mt-0">
                                        <div class="text-base text-slate-500">Người lập</div>
                                        <div class="mt-1">
                                            <br/>
                                            <br/>
                                            <br/>
                                        <?php echo e(\App\Models\User::where('id',auth()->user()->id)->value('full_name')); ?>

                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center sm:text-right sm:ml-auto" >
                                        <div class="text-base text-slate-500"> Đơn vị vận chuyển </div>
                                            <div class="text-xl text-primary font-medium mt-1">
                                               <?php echo e($warehouseout->delivery_id? \App\Models\User::find($warehouseout->delivery_id)->full_name:''); ?>


                                            </div>
                                        
                                        </div>
                                
                                </td>
                            </tr>
                        </table>
                    </div>
                </div><?php /**PATH D:\xampp\htdocs\shop4\resources\views/backend/warehouseouts/show_p.blade.php ENDPATH**/ ?>