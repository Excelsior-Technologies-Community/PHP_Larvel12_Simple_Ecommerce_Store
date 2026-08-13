<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;

class ImportExportController extends Controller
{
    public function export()
    {
        return Excel::download(new ProductsExport, 'products_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        Excel::import(new ProductsImport, $request->file('file'));

        return back()->with('success', 'Products imported successfully');
    }
}
