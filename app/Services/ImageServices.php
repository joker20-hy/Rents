<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;

class ImageServices
{
    protected $driver;
    protected $storageBase;

    public function __construct()
    {
        $this->driver = env('FILE_DISK');
        $this->storageBase = env('FILE_STORAGE_BASE');
    }

    public function store(array $images, $folder = 'images', $prefix = null)
    {
        $folder = $this->getFolder($folder);
        $prefix = is_null($prefix) ? "" : "$prefix-";
        $urls = [];
        try {
            foreach($images as $image) {
                $filename = $prefix.time().".".$image->getClientOriginalExtension();
                $path = "$folder/$filename";
                $rs = Storage::disk($this->driver)->put($path, $image);
                array_push($urls, Storage::url($rs));
            }
        } catch (Exception $e) {
            return [];
        }
        return $urls;
    }

    private function getFolder($folder)
    {
        return implode("/", [$this->storageBase, $folder]);
    }

    public function getUrl($filepath)
    {
        //
    }
}
