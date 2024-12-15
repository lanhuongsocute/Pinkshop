@extends('frontend.layouts.master')
@section('css')
    
     
@endsection
@section('content')
 
    <section class="p-0">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="error-section">
                        <h1>404</h1>
                        <h2>page not found</h2>
                        <a href="{{route('home')}}" class="btn btn-solid">back to home</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
@endsection