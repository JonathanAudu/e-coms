<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('created_at', 'asc')->paginate(10);
        return view('backend.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('title', 'ASC')->get();
        return view('backend.category.create', compact('categories'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|unique:categories,title',
        ], [
            'title.unique' => 'This category title already exists.',
        ]);

        $validatedData['slug'] = Str::slug($request->title);

        $category = Category::create($validatedData);

        $message = $category
            ? 'Category successfully added'
            : 'Error occurred, please try again!';

        return redirect()->route('category.index')->with(
            $category ? 'success' : 'error',
            $message
        );
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Implement if needed
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('backend.category.edit', compact('category'));
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
    $category = Category::findOrFail($id);

    $validatedData = $request->validate([
        'title' => 'required|string|unique:categories,title,' . $id,
    ]);

    $validatedData['slug'] = Str::slug($request->title);

    $status = $category->update($validatedData);

    $message = $status
        ? 'Category successfully updated'
        : 'Error occurred, Please try again!';

    return redirect()->route('category.index')->with(
        $status ? 'success' : 'error',
        $message
    );
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $status = $category->delete();
        $message = $status
            ? 'Category successfully deleted'
            : 'Error while deleting category';

        return redirect()->route('category.index')->with(
            $status ? 'success' : 'error',
            $message
        );
    }

}
