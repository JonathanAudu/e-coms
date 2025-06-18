<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FrontendController extends Controller
{

    public function home()
    {
        $products = Product::orderBy('id', 'DESC')->limit(8)->get();
        $categories = Category::orderBy('title', 'DESC')->limit(5)->get();
        $catProducts = Product::latest()->take(12)->get();
        $catgroup = Category::whereHas('products')->with('products')->orderBy('title')->get();
        $relatedProducts = Product::with('category')
        ->inRandomOrder()
        ->limit(10)
        ->get();

        $currency = session('currency', 'NGN');

        return view('frontend.index', compact('products', 'categories', 'catProducts', 'catgroup', 'relatedProducts', 'currency'));
    }

    public function changeCurrency(Request $request)
    {
        $request->validate([
            'currency' => 'required|in:NGN,USD,GBP',
        ]);

        session(['currency' => $request->currency]);

        return redirect()->back();
    }

    public function aboutUs()
    {
        $categories = Category::orderBy('title', 'DESC')->limit(5)->get();
        return view('frontend.pages.about-us', compact('categories'));
    }

    public function contact()
    {
        $categories = Category::orderBy('title', 'DESC')->limit(5)->get();
        return view('frontend.pages.contact-us', compact('categories'));
    }


    public function login(){
        $categories = Category::orderBy('title', 'DESC')->limit(5)->get();
        return view('frontend.pages.login', compact('categories'));
    }
    public function loginSubmit(Request $request){
        $data= $request->all();
        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])){
            Session::put('user',$data['email']);
            request()->session()->flash('success','Successfully login');
            return redirect()->route('home');
        }
        else{
            request()->session()->flash('error','Invalid email and password pleas try again!');
            return redirect()->back();
        }
    }

    public function logout(){
        Session::forget('user');
        Auth::logout();
        request()->session()->flash('success','Logout successfully');
        return back();
    }


    public function register(){
        $categories = Category::orderBy('title', 'DESC')->limit(5)->get();
        return view('frontend.pages.register', compact('categories'));
    }

    public function registerSubmit(Request $request){
        // return $request->all();
        $this->validate($request,[
            'name'=>'string|required|min:2',
            'email'=>'string|required|unique:users,email',
            'password'=>'required|min:6|confirmed',
        ]);
        $data=$request->all();
        // dd($data);
        $check=$this->create($data);
        Session::put('user',$data['email']);
        if($check){
            request()->session()->flash('success','Successfully registered');
            return redirect()->route('home');
        }
        else{
            request()->session()->flash('error','Please try again!');
            return back();
        }
    }

    public function categoryProducts(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $productQuery = Product::where('category_id', $category->id);

        if ($request->filled('min_price')) {
            $productQuery->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $productQuery->where('price', '<=', $request->max_price);
        }

        if ($request->filled('keyword')) {
            $productQuery->where('name', 'like', '%' . $request->keyword . '%');
        }

        $products = $productQuery->paginate(6);

        $categories = Category::withCount('products')->orderBy('title', 'ASC')->limit(8)->get();

        $featuredProducts = Product::inRandomOrder()->limit(3)->get();

        return view('frontend.pages.category-products', compact(
            'category', 'products', 'categories', 'featuredProducts'
        ));
    }


    public function productDetail($slug)
    {
        $product_detail = Product::where('slug', $slug)->with('category')->firstOrFail();
        $categories = Category::orderBy('title', 'DESC')->limit(5)->get();
        $featuredProducts = Product::inRandomOrder()->limit(3)->get();
        $catwithCount = Category::withCount('products')->orderBy('title', 'ASC')->limit(8)->get();
        $relatedProducts = Product::with('category')
                            ->where('id', '!=', $product_detail->id)
                            ->inRandomOrder()
                            ->limit(10)
                            ->get();

        return view('frontend.pages.product_detail', compact('product_detail', 'categories','featuredProducts', 'catwithCount', 'relatedProducts'));
    }







    public function create(array $data){
        return User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
            'status'=>'active'
            ]);
    }


    public function showResetForm(){
        return view('auth.passwords.old-reset');
    }
}
