<?php

namespace App\Services\JournalVoucherUpload;

use App\Exceptions\handleDatabaseException;
use App\Exports\JournalVoucherUploadExport;
use App\Helpers\errorCodes;
use App\Imports\JournalVoucherUploadImport;
use App\Interfaces\Accurate\AccurateDatabaseInterfaces;
use App\Interfaces\Bank\AccountBankTypeInterfaces;
use App\Interfaces\Bank\BankInterfaces;
use App\Interfaces\JournalVoucherUpload\JournalVoucherUploadInterfaces;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PDOException;

class JournalVoucherUploadServices
{
    use ApiResponse;

    protected $bankInterfaces;
    protected $journalVoucherUploadInterfaces;
    protected $accurateDatabaseInterfaces;

    public function __construct(BankInterfaces $bankInterfaces, JournalVoucherUploadInterfaces $journalVoucherUploadInterfaces, AccurateDatabaseInterfaces $accurateDatabaseInterfaces)
    {
        $this->bankInterfaces = $bankInterfaces;
        $this->journalVoucherUploadInterfaces = $journalVoucherUploadInterfaces;
        $this->accurateDatabaseInterfaces = $accurateDatabaseInterfaces;
    }

    public function getAll()
    {
        try {
            $journalVoucherUpload = $this->journalVoucherUploadInterfaces->getAll();
            return $this->successResponse($journalVoucherUpload, 200, 'Journal Voucher Upload List Get Successfully');
        } catch (PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
    public function upload($request)
    {
        try {
            if ($file = $request->file('file')) {

                $name = $file->getClientOriginalName();
                $splitName = explode(".", $name);
                $newFileName = "(" . date('Y-m-d his') . ")" . $splitName[0] . '.csv';

                $delimiter = $this->detectDelimiter($file);

                $import = new JournalVoucherUploadImport($delimiter);
                $data = Excel::toArray($import, $request->file('file'));

                $getCategoryBank = $this->bankInterfaces->getCategoryBankName($request->bank_id);

                switch ($getCategoryBank) {
                    case 'Mandiri':
                        if (!str_contains($newFileName, 'MDR') || !str_contains($newFileName, 'MANDIRI')) {
                            return $this->errorResponse('File yang diupload bukan file dari Mandiri', 400, errorCodes::CODE_WRONG_ERROR);
                        }
                        $export = new JournalVoucherUploadExport($data, ";");
                        $file = 'assets/files_bca/dirpoll/request/' . $newFileName;
                        Excel::store($export, $newFileName, 'files_mandiri');

                        $request->merge(['file_name' => $newFileName, 'file_location' => $file, 'database_id'=>$this->accurateDatabaseInterfaces->getDatabaseByCodeDatabase($request->code_database)]);
                        $this->journalVoucherUploadInterfaces->create($request->all());
                        break;
                    case 'BCA':
                        if (!str_contains($newFileName, 'BCA')) {
                            return $this->errorResponse('File yang diupload bukan file dari BCA', 400, errorCodes::CODE_WRONG_ERROR);
                        }
                        $export = new JournalVoucherUploadExport($data, ",");
                        $file = 'assets/files_bca/dirpoll/request/' . $newFileName;
                        Excel::store($export, $newFileName, 'files_bca');
                        $request->merge(['file_name' => $newFileName, 'file_location' => $file, 'database_id'=>$this->accurateDatabaseInterfaces->getDatabaseByCodeDatabase($request->code_database)]);
                        $this->journalVoucherUploadInterfaces->create($request->all());
                        break;
                    case 'BNI':
                        if (!str_contains($newFileName, 'BNI')) {
                            return $this->errorResponse('File yang diupload bukan file dari BNI', 400, errorCodes::CODE_WRONG_ERROR);
                        }
                        $export = new JournalVoucherUploadExport($data, ",");
                        $file = 'assets/files_bca/dirpoll/request/' . $newFileName;
                        Excel::store($export, $newFileName, 'files_bni');
                        $request->merge(['file_name' => $newFileName, 'file_location' => $file, 'database_id'=>$this->accurateDatabaseInterfaces->getDatabaseByCodeDatabase($request->code_database)]);
                        $this->journalVoucherUploadInterfaces->create($request->all());
                        break;
                    case 'BRI':
                        if (!str_contains($newFileName, 'BRI')) {
                            return $this->errorResponse('File yang diupload bukan file dari BRI', 400, errorCodes::CODE_WRONG_ERROR);
                        }
                        $export = new JournalVoucherUploadExport($data, ";");
                        $file = 'assets/files_bca/dirpoll/request/' . $newFileName;
                        Excel::store($export, $newFileName, 'files_bri');
                        $request->merge(['file_name' => $newFileName, 'file_location' => $file, 'database_id'=>$this->accurateDatabaseInterfaces->getDatabaseByCodeDatabase($request->code_database)]);
                        $this->journalVoucherUploadInterfaces->create($request->all());
                        break;
                    default:
                        throw new Exception('Category Bank Name Not Found');
                }

            }
            return $this->successResponse('', 200, 'Upload Journal Voucher Successfully');
        } catch (PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $journalVoucherUpload = $this->journalVoucherUploadInterfaces->getById($id);
            $this->journalVoucherUploadInterfaces->delete($id);
            return $this->successResponse($journalVoucherUpload, 200, 'Journal Voucher Upload Delete Successfully');
        } catch (PDOException $e) {
            Log::debug($e->getMessage());
            throw new handleDatabaseException($e->errorInfo, $e->getMessage());
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return $this->errorResponse($e->getMessage(), 500, errorCodes::CODE_WRONG_ERROR);
        }
    }
    public function detectDelimiter($csvFile)
    {
        $delimiters = [";" => 0, "," => 0, "\t" => 0, "|" => 0];

        $handle = fopen($csvFile, "r");
        $firstLine = fgets($handle);
        fclose($handle);
        foreach ($delimiters as $delimiter => &$count) {
            $count = count(str_getcsv($firstLine, $delimiter));
        }

        return array_search(max($delimiters), $delimiters);
    }
}
