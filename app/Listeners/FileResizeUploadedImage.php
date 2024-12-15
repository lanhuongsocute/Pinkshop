<?php

namespace App\Listeners;
use Alexusmai\LaravelFileManager\Events\FilesUploading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use Image;
class FileResizeUploadedImage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //
       
        $disk = $event->disk();
        $files = $event->files();
        \Log::info('files:',$files);
       
        foreach($files as $file)
        {
            // $path = Storage::disk($disk)->path($file);
            $storagePath = Storage::disk('local')->getAdapter()->getPathPrefix();
            \Log::info('path:',$storagePath);
            if($file['extension'] == "png" ||$file['extension'] == "jpg" )
            {
                
                $watermark = Image::make('watermark.png');
                // $watermark->resize(50, 50);
                // $watermark->save($path);
                // return;
                // $image = Image::make( $path . $file['path']);
                // $image->insert($watermark, 'center');
        
                // $image->insert(asset('wofttp-300.png'));
                // $image->text('them chu vao anh');
               
                // if($image->height() > 400) {
                //     $image->resize(null, 400, function ($constraint) {
                //         $constraint->aspectRatio();
                //     })->save($path);
                // }
                
                // $image->text(env("TEXT_ON_IMAGE", ""), 50, 50, function($font) {
                  
                //     $font->size(74);
                //     $font->color('#fdf6e3');
                //     $font->align('center');
                //     $font->valign('top');
                //     // $font->angle(45);
                // });
        
                // $image->save($path);
            }

        }
       
    }
}
