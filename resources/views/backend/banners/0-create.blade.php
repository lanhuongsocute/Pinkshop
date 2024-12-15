@extends('backend.layouts.master')
@section ('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.css" rel="stylesheet"> -->
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
<!-- <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script> -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
 
 
 
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
 

   
@endsection
@section('content')

<div class = 'content'>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thêm banner
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
        <div class='content'>
            
        <form data-single="true" action="{{route('fileupload')}}" id="documentdropzone" class="dropzone"> 
        @csrf
            <div class="fallback"> 
                <input name="file" type="file" /> </div> 
                <div class="dz-message" data-dz-message> 
                    <div class="text-lg font-medium">Drop files here or click to upload.</div> 
                    <div class="text-slate-500"> This is just a demo dropzone. Selected files are 
                        <span class="font-medium">not</span> actually uploaded. 
                    </div> 
                </div> 
        </form> 
            <!-- BEGIN: Form Layout -->
            <form method="post" action="{{route('banner.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="intro-y box p-5">
                    <div>
                        <label for="regular-form-1" class="form-label">Tiêu đề</label>
                        <input id="title" name="title" type="text" class="form-control" placeholder="title">
                    </div>
                     
                    <div class="mt-3">
                        <label for="" class="form-label">Photo</label>
                         
                            <div class="needsclick dropzone" id="documentdropzone2" url="{{route('banner.store')}}" >
                          

                            </div>
                            <div class="dropzone" id="documentdropzone3" url="{{route('fileupload')}}" >
</div>
                        <!-- </div> -->
                        <div id="holder" style="margin-top:15px;max-height:100px;">
                        </div>
                    </div>
                    <div class="mt-3">
                        
                        <label for="" class="form-label">Mô tả</label>
                       
                        <textarea class="editor" name="description" id="editor1"  >
                            {{old('description')}}
                        </textarea>
                    </div>
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label"  for="status">Tình trạng</label>
                           
                            <select name="status" class="form-select mt-2 sm:mr-2"   >
                                
                                <option value ="active" {{old('status')=='active'?'selected':''}}>Active</option>
                                <option value = "inactive" {{old('status')=='inactive'?'selected':''}}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:50px  " class="form-select-label" for="status">Vị trí</label><br/>
                            <select name="condition"  class="form-select mt-2 sm:mr-2"   >
                            
                                <option value ="banner" {{old('condition')=='banner'?'selected':''}}>Banner</option>
                                <option value = "promo" {{old('condition')=='promo'?'selected':''}}>Promo</option>
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
  
<script>
     var uploadedDocumentMap = {}
    
     alert('b');
     Dropzone.options.documentdropzone = { // The camelized version of the ID of the form element
            url: '/',
            // The configuration we've talked about above
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 100,
            maxFiles: 100,

            // The setting up of the dropzone
            init: function() {
            var myDropzone = this;
                alert('baba');
            // First change the button to actually tell Dropzone to process the queue.
            this.element.querySelector("button[type=submit]").addEventListener("click", function(e) {
                // Make sure that the form isn't actually being sent.
                e.preventDefault();
                e.stopPropagation();
                myDropzone.processQueue();
            });

            // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
            // of the sending event because uploadMultiple is set to true.
            this.on("sendingmultiple", function() {
                // Gets triggered when the form is actually being sent.
                // Hide the success button or the complete form.
            });
            this.on("successmultiple", function(files, response) {
                // Gets triggered when the files have successfully been sent.
                // Redirect user or notify of success.
            });
            this.on("errormultiple", function(files, response) {
                // Gets triggered when there was an error sending the files.
                // Maybe show form again, and notify user of error
            });
            }

        }

       
        // console.log(document.getElementById('documentdropzone'));
        Dropzone.options['documentdropzone3'] = {
        url: '{{route('banner.store') }}',
        maxFilesize: 2, // MB
        addRemoveLinks: true,
        headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function (file, response) {
        $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
        uploadedDocumentMap[file.name] = response.name
        },
        removedfile: function (file) {
        file.previewElement.remove()
        var name = ''
        if (typeof file.file_name !== 'undefined') {
            name = file.file_name
        } else {
            name = uploadedDocumentMap[file.name]
        }
        $('form').find('input[name="document[]"][value="' + name + '"]').remove()
        },
        init: function () {
        @if(isset($project) && $project->document)
            var files =
            {!! json_encode($project->document) !!}
            for (var i in files) {
            var file = files[i]
            this.options.addedfile.call(this, file)
            file.previewElement.classList.add('dz-complete')
            $('form').append('<input type="hidden" name="document[]" value="' + file.file_name + '">')
            }
        @endif
        }
    }
    Dropzone.instances[2].options.url ="/";
    // Dropzone.instances[2].options.addedfile= function (file ) {
    //         // Called when an error occurs during file upload
    //         console.error('Error uploading file!' );
    //     }
        Dropzone.instances[2].on("addedfile", function (file ) {
        // Example: Handle success event
        console.log('File uploaded successfully!' );
    });
     console.log(Dropzone.instances[2].options   );
 
    // console.log(Dropzone.optionsForElement);
</script>

    
@endsection