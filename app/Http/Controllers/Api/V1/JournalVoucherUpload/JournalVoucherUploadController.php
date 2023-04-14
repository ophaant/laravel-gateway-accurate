<?php

namespace App\Http\Controllers\Api\V1\JournalVoucherUpload;

use App\Http\Controllers\Controller;
use App\Http\Requests\JournalVoucherUploadRequest;
use App\Services\JournalVoucherUpload\JournalVoucherUploadServices;
use Illuminate\Http\Request;

class JournalVoucherUploadController extends Controller
{
    protected $journalVoucherUploadServices;

    public function __construct(JournalVoucherUploadServices $journalVoucherUploadServices)
    {
        $this->journalVoucherUploadServices = $journalVoucherUploadServices;
    }
    public function store(JournalVoucherUploadRequest $request)
    {
        return $this->journalVoucherUploadServices->upload($request);
    }
}
