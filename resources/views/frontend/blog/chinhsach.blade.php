@extends('frontend.layouts.master')
@section('css')
    
     
@endsection
@section('content')
<section class="blog-detail-page section-b-space ratio2_3">
        <div class="container blog-container">
            <div class="row">
                <div class="col-sm-12 blog-detail">
                    
                    <h3>{{$blog->title}}</h3>
                    
                    <p> 
                        <?php 
                        echo $blog->content;
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </section>
    
@endsection
@section('scripts')
@endsection