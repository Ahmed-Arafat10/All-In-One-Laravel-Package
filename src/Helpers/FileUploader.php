<?php

namespace AhmedArafat\AllInOne\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FileUploader
{
    /**
     * @param $file
     * @param null $namePrefix
     * @return string
     */
    public static function getFileName($file, $namePrefix = null): string
    {
        $namePrefix = $namePrefix ? $namePrefix . '_' : '';
        $name = $file->hashName() ? $file->hashName() : mt_rand(1111111, 99999999);
        return $namePrefix . Carbon::now()->format('Y_m_d_h_i_s_') . $name;
    }

    /**
     * @param $file
     * @param string $fileName
     * @param string $path
     * @return void
     */
    public static function storeFile($file, string $fileName, string $path): void
    {
        $file->move(public_path($path), $fileName);
    }

    /**
     * @param string $path
     * @param string|null $oldImageName
     * @return int
     */
    public static function deleteOldFile(string $path, ?string $oldImageName): int
    {
        $path = public_path($path) . $oldImageName;
        if (File::exists($path)) {
            return File::delete($path);
        }
        return -1;
    }

    /**
     * @param $request
     * @param $fileInputName
     * @param string $path
     * @param string|null $oldImageName
     * @param int $w
     * @param int $h
     * @param null $namePrefix
     * @return array|null
     */
    public static function Uploader($request, $fileInputName, string $path, ?string $oldImageName, int $w = 0, int $h = 0, $namePrefix = null): ?array
    {
        # FLAG is used to handle multiple images input
        $flag = $fileInputName instanceof UploadedFile;
        if ($flag || $request->hasFile($fileInputName)) {
            $file = $flag ? $fileInputName : $request->file($fileInputName);
            $fileNewName = self::getFileName($file, $namePrefix);
            if (!$w && !$h) self::storeFile($file, $fileNewName, $path);
            else self::resize($file, $w, $h, $path, $fileNewName);
            if ($oldImageName != null) self::deleteOldFile($path, $oldImageName);
            return [$fileNewName, $file->getClientOriginalName()];
        }
        return null;
    }

    public static function resize($file, int $w, int $h, string $path, string $fileNewName)
    {
//        $manager = new ImageManager(new Driver());
//        $manager->read($file)
//            ->resize($w, $h)
//            ->toWebp(50)
//            ->save($path . $fileNewName);
    }

    # testing
    public static function changeImageFormat()
    {
        // $manager = new ImageManager(new Driver());
        // $manager->read(public_path('frontend/t.png'))->toWebp(50)
        //   ->save('frontend/t2.webp');
    }

    /**
     * @param $model
     * @param $filePath
     * @param $dbFileNameKey
     * @param array $files_ids
     * @param string $colNameToSearchWith
     * @return void
     */
    public static function handleDeleteFiles($model, $filePath, $dbFileNameKey, $files_ids, string $colNameToSearchWith = 'id'): void
    {
        // $model->getRawOriginal('file_name')
        if (is_array($files_ids) && !empty($files_ids)) {
            DB::transaction(function () use (&$files_ids, &$model, &$colNameToSearchWith, &$filePath, &$dbFileNameKey) {
                $objects = $model::whereIn($colNameToSearchWith, $files_ids);
                $objects->get()->each(function ($model) use (&$filePath, &$dbFileNameKey) {
                    FileUploader::deleteOldFile($filePath, $model->getRawOriginal($dbFileNameKey));
                });
                $objects->delete();
            });
        }
    }

}
