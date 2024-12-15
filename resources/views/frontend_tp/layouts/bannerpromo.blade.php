<?php
      $banners = \DB::select("SELECT * from banners where   status = 'active' and `condition`='promo'  order by rand() LIMIT 1");;
    if(count($banners) > 0)
        $banner = $banners[0];
?>
@if (isset($banner))
<div class="container">
<figure class="w-100 w-full !mb-6">
        <img src="{{$banner->photo}}"    >
</figure>
</div>
@endif
