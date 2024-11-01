<?php

namespace AhmedArafat\AllInOne\Traits;

use Maatwebsite\Excel\Facades\Excel;

trait ExcelFormaterTrait
{
    public function excelFileExtractor(string $fileKey, bool $likeExcelCells = true)
    {
        $file = request()->file($fileKey);
        $excelFile = Excel::toArray(null, $file);
        return $likeExcelCells
            ? $this->excelFormater($excelFile[0])
            : $excelFile[0];
    }

    /**
     * @param $rows
     * @return array
     */
    protected function excelFormater($rows): array
    {
        $data = [];
        foreach ($rows as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $cellAddress = $this->getExcelCellAddress($rowIndex + 1, $colIndex + 1);
                $data[$cellAddress] = $value;
            }
        }
        return $data;
    }

    /**
     * @param $row
     * @param $col
     * @return string
     */
    private function getExcelCellAddress($row, $col): string
    {
        $letters = '';
        while ($col > 0) {
            $remainder = ($col - 1) % 26;
            $letters = chr(65 + $remainder) . $letters;
            $col = intval(($col - 1) / 26);
        }
        return $letters . $row;
    }
}
