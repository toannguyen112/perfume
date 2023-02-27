<?php

namespace Jamstackvietnam\Support\Models;

use App\Models\Admin;
use Illuminate\Support\Facades\Storage;
use Jamstackvietnam\Support\Models\BaseModel;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File as FileFacades;

class File extends BaseModel
{
    use SearchableTrait;

    public $fillable = [
        'disk',
        'path',
        'filename',
        'extension',
        'mime',
        'size',
        'width',
        'height',
        'source_url',
        'creator_id',
        'creator_type',
        'editor_id',
        'editor_type',
        'created_at',
        'updated_at',
    ];

    public $deploy = false;

    protected $hidden = [];

    protected $appends = ['image_id', 'image_url', 'static_url'];

    protected $searchable = [
        'columns' => [
            'files.filename' => 10,
        ],
    ];

    protected static function booted()
    {
        static::deleted(function ($file) {
            $path = $file->getRawOriginal('path');
            Storage::disk($file->disk)->delete($path);
            Storage::deleteDirectory($file->disk . '/cache/' . $path);
        });
    }

    public function getImageIdAttribute()
    {
        return $this->id;
    }

    public function getImageUrlAttribute()
    {
        if (contains($this->path, 'http')) return $this->path;

        if (!is_null($this->path)) {
            $prefix = $this->disk === 'guest' ? 'guest' : $this->id;
            return $prefix . '/' . $this->path;
        }
        return null;
    }

    // public function getPathAttribute($path)
    // {
    //     if (!is_null($path)) {
    //         return Storage::disk($this->disk)->url($path);
    //     }
    // }

    public function getStaticUrlAttribute()
    {
        if (!is_null($this->image_url)) {
            return Storage::disk($this->disk)->url($this->image_url);
        }
        return null;
    }

    public static function storeFile($file, $uploader, $disk = 'uploads')
    {
        $filename = $file->getClientOriginalName();
        $path = Storage::disk($disk)->putFileAs(date('Y/m/d'), $file, $filename);
        $media = [
            'disk' => $disk,
            'path' => $path,
            'filename' => $filename,
            'extension' => $file->guessClientExtension(),
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'creator_id' => $uploader->id ?? 1,
            'creator_type' => $uploader ?  get_class($uploader) : 'App\Models\User',
            'editor_id' => $uploader->id ?? 1,
            'editor_type' => $uploader ? get_class($uploader) : 1
        ];

        if (
            substr($file->getMimeType(), 0, 5) == 'image' &&
            $media['extension'] !== 'svg'
        ) {
            $imageSize = getimagesize($file);
            $media['width'] = $imageSize[0];
            $media['height'] = $imageSize[1];
        }

        return static::create($media);
    }

    public static function storeFileFromUrl(string $url, object $config)
    {
        try {
            $uploader = $config->uploader ?? Admin::first();
            $isStoreFile = isset($config->isStoreFile) ? $config->isStoreFile : true;

            if ($isStoreFile) {
                $file = file_get_contents($url);
                $mime = (new \finfo(FILEINFO_MIME_TYPE))->buffer($file);
                $extension = explode('/', $mime)[1] ?? 'png';
                if (!in_array($extension, ['png', 'jpeg', 'jpg', 'webp', 'gif', 'tiff'])) {
                    logger('-------------------');
                    logger($url);
                    logger('-------------------');
                    return;
                }
                $filename = Str::slug($config->filename ?? urldecode(pathinfo($url)['filename'])) . '.' . $extension;
                $path = date('Y/m/d') . "/$filename";
                $fullpath = config('filesystems.disks.uploads.root') .  "/" . $path;

                $dirname = pathinfo($fullpath)['dirname'];
                if (!FileFacades::isDirectory($dirname)) {
                    FileFacades::makeDirectory($dirname, 0777, true, true);
                }
                file_put_contents($fullpath, $file);
            }

            $media = [
                'disk' => 'uploads',
                'path' => $path ?? $url,
                'source_url' => $url,
                'filename' => $filename ?? basename($url),
                'extension' => $extension ?? null,
                'mime' => $mime ?? null,
                'size' => null,
                'creator_id' => $uploader->id,
                'creator_type' => get_class($uploader),
                'editor_id' => $uploader->id,
                'editor_type' => get_class($uploader)
            ];

            return static::create($media);
        } catch (\Exception $e) {
            logger('-------- storeFileFromUrl --------');
            logger($e);
            // if (config('app.debug')) throw $e;
            return null;
        }
    }

    public static function storeFileFromUrls(array $urls, object $config)
    {
        $files = [];
        foreach ($urls as $url) {
            if (!empty($url)) {
                if ($file = static::where('source_url', $url)->first()) {
                    $files[] = $file;
                } else {
                    $files[] = static::storeFileFromUrl($url, $config);
                }
            }
        }
        return collect($files);
    }
}
