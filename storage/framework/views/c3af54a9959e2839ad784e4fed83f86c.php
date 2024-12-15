<div class="row"> 
    <h4> Từ khóa: </h4>
    <div>
    <?php
        $hit = 0;
        if(count($tags)> 0)
        {
            foreach($tags as $tag)
            {
                $hit += $tag->hit;
            }
            $hit = $hit / count($tags);
            foreach($tags as $tag)
            {
                if($tag->hit < $hit/2)
                {
                        $tagclass="tag_1";
                }
                else
                if($tag->hit < $hit )
                {
                        $tagclass="tag_2";
                }
                else
                if($tag->hit < $hit*2/3 )
                {
                        $tagclass="tag_3";
                }
                else
                {
                    $tagclass="tag_4";
                }
                echo '<a type="button" href="'.route('front.tag.view', $tag->slug).'" class="'.$tagclass.' ">'.$tag->title.'</a>';
            }
        }
    ?>
    </div>
</div><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/frontend/layouts/tag.blade.php ENDPATH**/ ?>