 
@if(session('success'))
<div class="alert alert-success !text-[#308970] !bg-[#edf9f6] !border-[#308970] !p-[1rem]  alert-icon !pl-10 alert-dismissible fade show !border-0 !shadow-none" role="alert">
     
    {{session('success')}}
    <button type="button" class="btn-close !text-[#308970]" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger !text-[#9e454b] !bg-[#fcf0f1] !border-[#9e454b] !p-[1rem]  alert-icon !pl-10 alert-dismissible fade show !border-0 !shadow-none" role="alert">
     
    {{session('error')}}
    <button type="button" class="btn-close !text-[#308970]" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
 
@endif
@if($errors->any())
<div class="alert alert-danger !text-[#9e454b] !bg-[#fcf0f1] !border-[#9e454b] !p-[1rem]  alert-icon !pl-10 alert-dismissible fade show !border-0 !shadow-none" role="alert">
     
    <ul>
            @foreach ($errors->all() as $error)
                <li>    {{$error}} </li>
            @endforeach
    </ul>
    <button type="button" class="btn-close !text-[#308970]" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
 
@endif
                
 