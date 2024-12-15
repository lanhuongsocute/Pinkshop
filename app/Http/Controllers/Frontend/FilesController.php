<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
class FilesController extends Controller
{
    public function __construct( )
    {
        $this->middleware('auth');
    }
    public function adimgUpload(Request $request)
    {
        // Validate the request
        $request->validate([
            'file' => 'required|image|max:2048',
        ]);

        // Get the uploaded image
        $image = $request->file('file');
        $filename = time() . '.' . $image->getClientOriginalExtension();

        // Resize the image
        $resizedImage = Image::make($image)->resize(800, 600, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->stream();

        // Define the S3 path
        $s3Path = 'ads-images/' . $filename;

        // Save the resized image to S3
        Storage::disk('s3')->put($s3Path, $resizedImage->__toString());
        return response()->json(['status'=>'true','link'=>Storage::disk('s3')->url($s3Path)]);
       
    }
    public function blogimageUpload($url)
    {
        $url = str_replace(" ", "%20", $url);
       
        if (
            $url != 'http://silicom.com.vn/upload/hinhanh/1447405520849_1691581.jpg'
            && $url != 'https://s.alicdn.com/@img/imgextra/i3/6000000000291/O1CN01NfgGGw1E1K6MZku2N_!!6000000000291-0-tbvideo.jpg'
            
            )
        
        {
             $userAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36';
             $context = stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                    'http' => [
                        'method' => 'GET',
                        'header' => "User-Agent: $userAgent\r\n"
                    ]
                ]);
                // $imageContent = file_get_contents($url);
                try{
                    $imageContent = file_get_contents( $url , false, $context);

                }
                catch(e)
                {
                    return '';
                }
           
        }   
        else
            return "";
        // if (file_exists($url)) {
        //     // File exists, you can proceed to fetch its contents
        //     $imageContent = file_get_contents($url);
           
        // } else {
        //     // File does not exist
        //    return $url;
        // }
        // Save the image content to a temporary file
        $tempImagePath = tempnam(sys_get_temp_dir(), 'image');
        file_put_contents($tempImagePath, $imageContent);
        // Check if the file is an image
        $imageInfo = @getimagesize($tempImagePath);
        if (!$imageInfo) {
            // Delete the temporary file and return false
            unlink($tempImagePath);
            return false;
        }
        // Check if the file size exceeds 0.5 MB
        $fileSize = filesize($tempImagePath);
        if ($fileSize > 0.5 * 1024 * 1024) { // Convert MB to bytes
            // Compress the image
            $this->compressImage($tempImagePath, $imageInfo['mime']);
        }
        $s3Path = "blogs";
        // Upload the temporary file to S3
        $s3Path = Storage::disk('s3')->putFile($s3Path, new File($tempImagePath), 'public');
        $s3Path = Storage::disk('s3')->url( $s3Path);
        // Delete the temporary file
        unlink($tempImagePath);
        return $s3Path;
    }

    private function compressImage($imagePath, $mimeType)
    {
        // Load the image based on the MIME type
        switch ($mimeType) {
            case 'image/jpeg':
                $image =  @imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $image =  @imagecreatefrompng($imagePath);
                break;
            case 'image/gif':
                $image =  @imagecreatefromgif($imagePath);
                break;
            default:
                // Unsupported image format
                return;
        }
        // Compress the image and overwrite the original file
        imagejpeg($image, $imagePath, 70); // Adjust compression quality as needed
        // Free up memory
        imagedestroy($image);
    }

    //
    public function galleryUpload(Request $request)
    {
        $link = $request->hasFile('file') ? $this->store($request->file('file'), 'gallery') : null;
        $link = Storage::disk('s3')->url($link);
        return response()->json(['status'=>'true','link'=>$link]);
    }
    public function brandUpload(Request $request)
    {
        $link = $request->hasFile('file') ? $this->store($request->file('file'), 'brand') : null;
        $link = Storage::disk('s3')->url($link);
        return response()->json(['status'=>'true','link'=>$link]);
    }
    public function avartarUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:1048', // Adjust the validation rules as needed
        ]);
        $link = $request->hasFile('file') ? $this->store($request->file('file'), 'avatar') : null;
        $link = Storage::disk('s3')->url($link);
        return response()->json(['status'=>'true','link'=>$link]);
    }
    public function bannerUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);
        $link = $request->hasFile('file') ? $this->store($request->file('file'), 'avatar') : null;
        $link = Storage::disk('s3')->url($link);
        return response()->json(['status'=>'true','link'=>$link]);
    }
    public function ckeditorUpload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);
    
        if ($request->hasFile('upload')) {

            $file = $request->file('upload');
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $url  = $file->storeAs(
                'avatar',
                $originName . "." . $file->getClientOriginalExtension(),
                's3'
            );
            // $request->file('upload')->move(public_path('media'), $fileName);
    
            $url = Storage::disk('s3')->url($url);
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
            
    
        }
        
        return response()->json($response);

       
    }

    public function productUpload(Request $request)
    {
        $link = $request->hasFile('file') ? $this->store($request->file('file'), 'products') : null;
        $link = Storage::disk('s3')->url($link);
        return response()->json(['status'=>'true','link'=>$link]);
    }
    public function FileUpload(Request $request)
    {
        $link = $request->hasFile('file') ? $this->store($request->file('file'), 'Categories') : null;
        $link = Storage::disk('s3')->url($link);
        return response()->json(['success'=>$link]);
    }
    public function store(UploadedFile $file, $folder = null, $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);
        return   $file->storeAs(
            $folder,
            $name . "." . $file->getClientOriginalExtension(),
            's3'
        );
        // $image = $request->file('file');
        // $imageName = time().'.'.$image->extension();
        // $image->move(public_path('images'),$imageName);
        
    }
  
}
