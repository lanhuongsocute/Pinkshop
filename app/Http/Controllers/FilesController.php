<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
class FilesController extends Controller
{
    //
    public function ckeditorUpload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
        ]);
    
        if ($request->hasFile('upload')) {

            $file = $request->file('upload');
            // $originName = $request->file('upload')->getClientOriginalName();
             
            // $extension = $request->file('upload')->getClientOriginalExtension();
            // $fileName = $originName . '_' . time() . '.' . $extension;

            $filename = $request->file('upload')->getClientOriginalName();
            $ext = '.'.$request->file('upload')->getClientOriginalExtension();
           
            $filename =  str_replace(  $ext , '',$filename). '_'.Str::random(5) ;

            // return $filename;
            $url  = $file->storeAs(
                'avatar',
                $filename . "." . $file->getClientOriginalExtension(),
                's3'
            );
            // $request->file('upload')->move(public_path('media'), $fileName);
    
            $url = Storage::disk('s3')->url($url);
            return response()->json(['fileName' => $filename, 'uploaded'=> 1, 'url' => $url]);
            
    
        }
        
        return response()->json($response);

       
    }
    public function avartarUpload(Request $request)
    {
        $filename = $request->file('file')->getClientOriginalName();
        $ext = '.'.$request->file('file')->getClientOriginalExtension();
       
        $filename =  str_replace(  $ext , '',$filename). '_'.Str::random(5) ;
        
       
        $link = $request->hasFile('file') ? $this->store($request->file('file'), 'avatar',$filename) : null;
        $link = Storage::disk('s3')->url($link);
        return response()->json(['status'=>'true','link'=>$link]);
    }
    public function productUpload(Request $request)
    {
        
        $filename = $request->file('file')->getClientOriginalName();
        $ext = '.'.$request->file('file')->getClientOriginalExtension();
        $filename =  str_replace(  $ext , '',$filename) .'_'.Str::random(5) ;
       

        $link = $request->hasFile('file') ? $this->store($request->file('file'), 'products', $filename) : null;
        $link = Storage::disk('s3')->url($link);
        return response()->json(['status'=>'true','link'=>$link]);
    }
    public function blogimageUpload($url)
    {
        $url = str_replace(" ", "%20", $url);
        $tempImagePath = tempnam(sys_get_temp_dir(), 'image');
        $ch = curl_init($url);

        // Set options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        curl_setopt($ch, CURLOPT_HEADER, 0); // Don't include headers in the output
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects if any
        
        // Execute cURL request
        $imageData = curl_exec($ch);
        
        // Check for errors
        if(curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        } else {
            // Save the image data to a file
            file_put_contents($tempImagePath, $imageData);
            echo 'Image downloaded successfully!';
        }
        
        // Close the cURL session
        curl_close($ch);
        // file_put_contents($tempImagePath, $imageContent);
        // Check if the file is an image
        $imageInfo = @getimagesize($tempImagePath);
        if (!$imageInfo) {
            // Delete the temporary file and return false
            unlink($tempImagePath);
            return false;
        }
        // Check if the file size exceeds 0.5 MB
        $fileSize = filesize($tempImagePath);
        // if ($fileSize > 0.5 * 1024 * 1024) { // Convert MB to bytes
        //     // Compress the image
        //     $this->compressImage($tempImagePath, $imageInfo['mime']);
        // }
        $s3Path = "blogs";
        // Upload the temporary file to S3
        $s3Path = Storage::disk('s3')->putFile($s3Path, new File($tempImagePath), 'public');
        $s3Path = Storage::disk('s3')->url( $s3Path);
        // Delete the temporary file
        unlink($tempImagePath);
        return $s3Path;
    }
    public function blogimageUpload_old($url)
    {
        $imageContent = file_get_contents($url);
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
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($imagePath);
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

    public function FileUpload(Request $request)
    {
        $filename = $request->file('file')->getClientOriginalName();
        $ext = '.'.$request->file('file')->getClientOriginalExtension();
        $filename =  str_replace(  $ext , '',$filename);

        $link = $request->hasFile('file') ? $this->store($request->file('file'), 'Categories',$filename) : null;
        $link = Storage::disk('s3')->url($link);
        return response()->json(['success'=>$link]);
    }
    public function store(UploadedFile $file, $folder = null, $filename = null)
    {
        $name = !is_null($filename) ? $filename.'_'.Str::random(5) : Str::random(25);
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
