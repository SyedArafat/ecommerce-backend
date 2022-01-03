<?php

namespace App\Ecommerce\Traits;

use Illuminate\Http\Request;

trait FileUpload
{
    /**
     * This method is used to store files inside the project and return the name
     *
     * @param $file
     * @param $key
     * @param $folder_name
     * @return string
     */
    protected function storeFile($file, $key, $folder_name): string
    {
        $name=time().".".$file->getClientOriginalExtension();
        $directory = ("resources"."/$folder_name");
        $file->move($directory, $name);
        return $directory."/".$name;
    }

    protected function deleteFile($file_path)
    {
        if (file_exists($file_path)) unlink($file_path);
    }
}
