<?php
  $blogcats = \DB::select("select * from  blog_categories  where status='active'   ");
  foreach ($blogcats as $blogcat)
  {
      $sql = "select count(id) as tong from blogs where cat_id = ".$blogcat->id;
      $re = \DB::select($sql);
      $blogcat->sobai = $re[0]->tong;
  }
  ?>
<div class="widget mt-[40px]">
        <h4 class="widget-title !mb-3">Danh mục</h4>
        <ul class="pl-0 list-none bullet-primary !text-inherit">
            <?php $__currentLoopData = $blogcats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blogcat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="relative pl-[1rem] before:absolute  before:top-[-0.15rem] before:text-[1rem] before:content-['\2022'] before:left-0 before:font-SansSerif">
                <a class="text-inherit nav_color" href="<?php echo e(route('front.category.view',$blogcat->slug)); ?>">
                    <?php echo e($blogcat->title); ?> (<?php echo e($blogcat->sobai); ?>)
                </a>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
        
        </ul>
</div><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/frontend_tp/layouts/blogcatmenu.blade.php ENDPATH**/ ?>