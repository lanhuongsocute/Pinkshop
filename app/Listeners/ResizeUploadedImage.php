<?php

namespace App\Listeners;
use UniSharp\LaravelFilemanager\Events\ImageWasUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Image;
class ResizeUploadedImage
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
        $path = $event->path();
        // \Log::info('FilesUploading:', );
        $image = Image::make($path);
        $watermark = Image::make('watermark.png');
        // $watermark->resize(50, 50);
        // $watermark->save($path);
        // return;
        $image->insert($watermark, 'center');

        // $image->insert(asset('wofttp-300.png'));
        // $image->text('them chu vao anh');
       
        if($image->height() > 400) {
            $image->resize(null, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path);
        }
        
        $image->text(env("TEXT_ON_IMAGE", ""), 50, 50, function($font) {
          
            $font->size(74);
            $font->color('#fdf6e3');
            $font->align('center');
            $font->valign('top');
            // $font->angle(45);
        });

        $image->save($path);
    }
}
