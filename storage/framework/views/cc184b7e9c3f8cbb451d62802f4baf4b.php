<?php
      $banners = \DB::select("SELECT * from banners where   status = 'active' and `condition`='promo'  order by rand() LIMIT 1");;
    if(count($banners) > 0)
        $banner = $banners[0];
?>
<?php if(isset($banner)): ?>
<div class="container">
<figure class="w-100 w-full !mb-6">
        <img src="<?php echo e($banner->photo); ?>"    >
</figure>
</div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\shop4\resources\views/frontend_tp/layouts/bannerpromo.blade.php ENDPATH**/ ?>