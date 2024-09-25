<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductImportRequest;
use App\Services\ProductService;
use Illuminate\Support\Facades\Storage;

class ProductController
{
    public function __construct(protected ProductService $productService)
    {

    }

    public function import(ProductImportRequest $request)
    {
        $file = $request->file('import_csv');
        return $this->productService->importCSV($file);
    }
}
