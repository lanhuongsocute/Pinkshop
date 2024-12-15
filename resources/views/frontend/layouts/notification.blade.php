<div id="icon-dismiss-alert" class="p-5">
@if(session('success'))
    <div class="alert alert-primary alert-dismissible show flex items-center mb-2" role="alert"> 
        <i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i> 
        {{session('success')}}
        <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> 
            <i data-lucide="x" class="w-4 h-4"> </i> 
        </button> 
    </div>
 
@endif


@if(session('error'))
<div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert"> 
    <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> 
    {{session('error')}}
    <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> 
        <i data-lucide="x" class="w-4 h-4"></i> 
    </button> 
</div>
   
@endif

</div>