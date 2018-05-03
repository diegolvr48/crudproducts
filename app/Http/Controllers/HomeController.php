<?php

namespace App\Http\Controllers;

use \App\Product;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use \Laracsv\Export;
use Schema;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Export products in CSV.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $products = Product::with('category')->get();
        $csvExporter = new Export();
        $csvExporter->beforeEach(function ($product) {
            $product->category = $product->category->name; 
        });
        $csvExporter->build($products, [
            'id',
            'name',
            'description',
            'price',
            'category',
            'created',
            'modified'
            ])->download();
    }

    /**
     * Delete Selected Rows.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteSelected(Request $request)
    {
        return Product::destroy($request->products);
    }
}
