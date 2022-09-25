<?php
namespace App\Traits;

use Illuminate\Support\Facades\Config;

trait AssetUrl
{
    public function getAssetUrl($path)
    {
        $driver = Config::get('filesystems.default');

        if ($driver == 'public') {
            return url('storage/'.$path);
        } else {
            return '';
        }
    }
}
