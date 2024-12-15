<?php
    $cats = \App\Models\Category::where('status','active')->where('parent_id',null)->orderBy('title','asc')->get();
?>
<?php
  
  foreach ($cats as $cat)
  {
      $sql = "select count(id) as tong from products where cat_id = ".$cat->id;
      $re = \DB::select($sql);
      $cat->sobai = $re[0]->tong;
  }
  ?>
<div class="widget mt-[40px]">
        <h4 class="widget-title !mb-3">Danh mục</h4>
        <ul class="pl-0 list-none bullet-primary !text-inherit">
            <?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="relative pl-[1rem] before:absolute  before:top-[-0.15rem] before:text-[1rem] before:content-['\2022'] before:left-0 before:font-SansSerif">
                <a class="text-inherit nav_color" href="<?php echo e(route('front.product.cat',$cat->slug)); ?>">
                    <?php echo e($cat->title); ?> (<?php echo e($cat->sobai); ?>)
                </a>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
        
        </ul>
</div><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/frontend_tp/layouts/catpromenu.blade.php ENDPATH**/ ?>