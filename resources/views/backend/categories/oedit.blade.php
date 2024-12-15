@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
@endsection
@section('content')

<div class = 'content'>
@include('backend.layouts.notification')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Điều chỉnh danh mục
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('category.update',$category->id)}}">
                @csrf
                @method('patch')
                <div class="intro-y box p-5">
                    <div>
                        <label for="regular-form-1" class="form-label">Tiêu đề</label>
                        <input id="title" name="title" type="text" value ="{{$category->title}}" class="form-control" placeholder="title">
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Photo</label>
                    
                        <div class="input-group">
                            <span class="input-group-btn">
                                <a id="button-image" data-input="button-image" data-preview="holder" class="btn btn-primary">
                                <i class="fa fa-picture-o"></i> Choose
                                </a>
                            </span>
                            <input id="image_label" class="form-control" type="text" name="photo">
                        </div>
                        <div id="holder" style="margin-top:15px;max-height:100px;">
                            <img src=" {{$category->photo}}"style="margin-top:15px;max-height:100px;"/>
                        </div>
                    </div>
                    <div class="mt-3">
                        
                        <label for="" class="form-label">Mô tả</label>
                       
                        <textarea class="editor" name="summary" id="editor1"  >
                            <?php echo $category->summary?>
                        </textarea>
                    </div>
                    <div class="mt-3">  
                        <div class="flex flex-col sm:flex-row mt-2">
                            <div class="form-check mr-2"> 
                                <input id="is_parent" name="is_parent" id="checkbox-switch-4" 
                                {{$category->is_parent==1? 'checked': ''}}
                                class="form-check-input" type="checkbox" value="1"> 
                                <label class="form-check-label" for="checkbox-switch-4">là danh mục cha</label> 
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div id="div_parent_id" class="  flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label" for="status">Danh mục cha</label>
                           
                            <select name="parent_id" class="form-select mt-2 sm:mr-2" aria-label="Default select example"   >
                                <option value =""> --chọn danh mục cha-- </option>
                                @foreach ($parent_cats as $pcat)
                                    <option value="{{$pcat->id}}" 
                                    <?php
                                        if($category->is_parent == 0)
                                        {
                                            if($category->parent_id != null && $pcat->id == $category->parent_id)
                                                echo 'selected';
                                        }
                                        ?>
                                     >{{$pcat->title}}</option>
                                    
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label" for="status">Tình trạng</label>
                           
                            <select name="status" class="form-select mt-2 sm:mr-2" aria-label="Default select example"   >
                                <option value =""> --tình trạng-- </option>
                                <option value ="active" {{$category->status =='active'?'selected':''}}>Active</option>
                                <option value = "inactive" {{$category->status =='inactive'?'selected':''}}>Inactive</option>
                            </select>
                        </div>
                    </div>
                
                    <div class="mt-3">
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>    {{$error}} </li>
                                    @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section ('scripts')


<!-- <script src="{{asset('backend/assets/dist/js/ckeditor-classic.js')}}"></script> -->
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
    $('#lfm').filemanager('image');
</script>
<script src="{{asset('js/js/ckeditor.js')}}"></script>
<script>
     
        // CKSource.Editor
        ClassicEditor.create( document.querySelector( '#editor2' ), 
        {
            ckfinder: {
                uploadUrl: '{{route("upload.ckeditor")."?_token=".csrf_token()}}'
                }
                ,
                mediaEmbed: {previewsInData: true}

        })

        .then( editor => {
            console.log( editor );
        })
        .catch( error => {
            console.error( error );
        })

</script>
<script>
    var is_checked = $('#is_parent').prop('checked');
        // alert(is_checked);
        if(is_checked){
            $('#div_parent_id').addClass('md:hidden');
        }
        else
        {
            $('#div_parent_id').removeClass('md:hidden');
        }
        
    $('#is_parent').change(function(e){
        e.preventDefault();
        var is_checked = $('#is_parent').prop('checked');
        // alert(is_checked);
        if(is_checked){
            $('#div_parent_id').addClass('md:hidden');
        }
        else
        {
            $('#div_parent_id').removeClass('md:hidden');
        }
    });

</script>

<script>
        document.addEventListener("DOMContentLoaded", function() {

        document.getElementById('button-image').addEventListener('click', (event) => {
        event.preventDefault();

        window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
        });
        });

        // set file link
        function fmSetLink($url) {
        document.getElementById('image_label').value = $url;
        document.getElementById('holder').innerHTML = '<img src = "'+ $url +'" width ="100"/>';
        }
</script>
@endsection