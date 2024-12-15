<div class="breadcrumb-section">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="page-title">
                            <h2>{{isset($pagetitle)?$pagetitle:""}} </h2>
                        </div>
                    </div>
                    <div class="col-sm-8">
                         
                        <nav aria-label="breadcrumb" class="theme-breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Trang chủ</a></li> 
                              @if (isset($links))
                                @foreach ($links as $link )
                                    <li class="breadcrumb-item"><a href="{{$link->url}}">{{$link->title}}</a></li> 
                                @endforeach
                                
                              @endif
                               
                                
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>