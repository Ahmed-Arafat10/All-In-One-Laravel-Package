<?php

namespace AhmedArafat\AllInOne\Traits;

use App\Helpers\FileUploader;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

trait FileHelperTrait
{
    /**
     * @param $fileName
     * @return mixed
     * @throws Exception
     */
    private function getDataFromFile($fileName): mixed
    {
        if (!File::exists(public_path($fileName))) throw new Exception("File $fileName Not Found");
        return json_decode(
            File::get(
                public_path($fileName)
            ),
            true
        );
    }

    /**
     * @param $fileName
     * @return array
     * @throws Exception
     */
    public function getJsonContentFromFileAsArray($fileName): array
    {
        $data = $this->getDataFromFile($fileName);
        if (!is_array($data))
            throw new Exception(__("Problem While Reading File $fileName"));
        return $data;
    }

    /**
     * @param Request $request
     * @param string $inputName
     * @return array
     */
    public static function getUploadedFileData(Request $request, string $inputName = 'file'): array
    {
        $file = $request->file($inputName);
        return [
            'original_name' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
        ];
    }

    public function deleteOldFileWhileUpdatingModel(&$tempUploadedFile, Model $model = null, $path = null, $fileColName = null, $fileColOrgName = null)
    {
        if ($model && $tempUploadedFile) {
            FileUploader::deleteOldFile($path, $model->getRawOriginal($fileColName));
            return [
                $tempUploadedFile->file_name,
                $tempUploadedFile->file_original_name
            ];
        }
        return [
            $model?->getRawOriginal($fileColName) ?? $tempUploadedFile->file_name,
            $model?->getRawOriginal($fileColOrgName) ?? $tempUploadedFile->file_original_name
        ];
    }
}
