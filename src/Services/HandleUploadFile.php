<?php
namespace GGPHP\FileMedia\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class HandleUploadFile
{
    protected $driver;
    protected $folderStorage;
    protected $thumbnailStorage;
    
    public function __construct()
    {
        $this->driver = config('constants-fileMedia.disk_name');
        $this->folderStorage = config('constants-fileMedia.folder_save');
        $this->thumbnailStorage = $this->folderStorage . '/' . config('constants-fileMedia.thumbnail_storage');
    }
    
    public function uploadFile($file, $name)
    {
        $disk = Storage::disk($this->driver);
        
        $filePath = $this->folderStorage . '/' . $name;
        
        if (config('constants-fileMedia.thumbnail')) {
            $disk->putFileAs($this->thumbnailStorage, $file, $name);
            $this->createThumbnail($name);
        }
        if (config('constants-fileMedia.watermark')) {
            $this->insertWatermart($name);
        }
        $disk->putFileAs($this->folderStorage, $file, $name);
        $disk->setVisibility($filePath, 'public');
        
        if (config('constants-fileMedia.optimize_image')) {
            $this->optimizeImage($name);
        }
        
        return $disk->url($filePath);
    }
    
    public function createThumbnail($name)
    {
        $thumbnailpath = public_path('storage/' . $this->thumbnailStorage . '/' .  $name);
        
        $size = explode(',', config('constants-fileMedia.thumbnail_size'));
        $width = $size[0];
        $height = $size[1];
        
        $img = Image::make($thumbnailpath)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        
        $img->save($thumbnailpath);
    }
    
    public function insertWatermart($name)
    {
        $pathImage =  public_path('storage/' . $this->thumbnailStorage . '/' .  $name);
    }
    
    public function optimizeImage($name)
    {
        $path = public_path('storage/' . $this->folderStorage . '/' .  $name);
        $img = Image::make($path);
        $img->save($path, 10);
    }
}
