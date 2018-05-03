<?php

namespace App\Http\Controllers;

use \App\Product;
use \App\Category;

use Illuminate\Http\Request;
use DataTables;
use Yajra\DataTables\Html\Builder;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            return DataTables::eloquent(Product::query())
                    ->addColumn('category', function(Product $product) {
                        return $product->category->name;
                    })->toJson();
        }
        
        $html = $builder->addCheckbox(['data'=>'id', 'render'=>'\'<input type="checkbox" name="products[]" value="\'+data+\'">\''])
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Name'])
            ->addColumn(['data' => 'price', 'name' => 'price', 'title' => 'Price'])
            ->addColumn(['data' => 'description', 'name' => 'description', 'title' => 'Description'])
            ->addColumn(['data' => 'category', 'name' => 'category', 'title' => 'Category'])
            ->addColumn(['data' => 'created', 'name' => 'created', 'title' => 'Created'])
            ->addAction([
                'data'=>'id',
                'render'=>'\'<a class="btn btn-info" href="'.
                url('products').'/\'+data+\'/edit">'.
                '<span class="glyphicon glyphicon-edit"></span> Edit'.
                '</a>'.
                '<a class="btn btn-success" href="'.
                url('products').'/\'+data+\'"><span class="glyphicon glyphicon-eye-open"></span> Show</a>'.
                '<a class="btn btn-danger btn-delete-row" href="#"><span class="glyphicon glyphicon-remove"></span> Delete</a>\'']);

        return view('products.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create', ['categories' => Category::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'category' => 'required',
        ]);
        
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->category_id = $request->category;
        $product->description = $request->description;

        $product->save();

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('products.show', ['product' => Product::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('products.edit', [
            'product' => Product::find($id),
            'categories' => Category::all()
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'category' => 'required',
        ]);
        
        $product = Product::find($id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->category_id = $request->category;
        $product->description = $request->description;

        $product->save();

        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return $product;
    }

}
