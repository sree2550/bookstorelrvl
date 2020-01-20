<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\LoginModel;
use App\DistrictModel;
use App\UserAccountModel;
use App\CategoryModel;
use App\SubCategoryModel;
use App\BookModel;
use App\CartModel;
use App\PaymentModel;


//use DB;

use App\Http\Resources\books as BooksResource;
use Illuminate\Support\Facades\DB;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $signin = new LoginModel();
        $signin-> email = $request->input('email');
        $signin-> password = $request->input('password');
        $user = $signin-> usertype = "User";
        $user = $signin-> user_status = "1";


        $signin->save();

        if ($signin-> id > 0) {
            $signup = new UserAccountModel();
            $signup-> username = $request->input('fullname');
            $signup-> gender = $request->input('customRadio');
            $signup-> address = $request->input('address');
            $signup-> country = $request->input('country');
            $signup-> state = $request->input('state');
            $signup-> district = $request->input('district');
            $signup-> pincode = $request->input('zip');
            $signup-> contact_no = $request->input('contactno');
            $signup-> status = "1";
            $signup-> fk_login_id = $signin-> id;
            $signup->save();

            if ($signup-> id > 0) {
                echo '1';
            }
        }        
    }
    
    public function loginfunction(Request $request)
    {
        $uname = $request->input('email');
        $pwd = $request->input('password'); 
        $con = DB::table('user_login')->where('email', $uname)->first();

        if ($con === null) {
            $response = [
                "status" => false,
                "msg" => "no user exists",
            ];
            return response($response);
        } else {
            if ($con -> password == $pwd) {
                if ($con -> usertype == 'admin') {
                    $response = [
                        "status" => true,
                        "userId" => $con -> login_id,
                        "userType" => 'admin'
                    ];
                    return response($response);
                }elseif($con -> user_status == 0) {
                    $response = [
                        "status" => false,
                        "userId" => $con -> login_id,
                        "userStatus" => 0
                    ];
                    return response($response);
                }
                
                else {
                    $response = [
                        "status" => true,
                        "userId" => $con -> login_id,
                        "userType" => 'user'
                    ];
                    return response($response);
                }
            } else {
                $response = [
                    "status" => false,
                    "msg" => 'incorrect password'
                ];
                return response($response);
            }
            
        }
    }

    public function fetchDistricts(Request $request)
    {
        $districts = DistrictModel::all();
        return json_encode($districts);
    }

    public function fetchCategory(Request $request)
    {
        $category = CategoryModel::all();
        return json_encode($category);
    }

    public function districtstore(Request $request)
    {
        $districtstore = new DistrictModel();
        $districtstore-> district_name = $request->input('districtname');
        $districtstore->save();        
    }

    public function categorystore(Request $request)
    {
        $categorystore = new CategoryModel();
        $categorystore-> category_name = $request->input('categoryname');
        $categorystore->save();

        if ($categorystore-> id > 0) {
            echo '1';
        }
    }

  public function storebook(Request $request)
  {
        $book = new BookModel();
        $book-> book_name = $request->input('bookname');
        $book-> cat_id = $request->input('bookcat');
        $book-> description = $request->input('description');
        $book-> pub_name = $request->input('pubname');
        $book-> price = $request->input('price');
        $book-> quantity = $request->input('quantity');
        $book-> discount = $request->input('discount');
        $book-> stock = "In Stock";

        //image upload

        if($request->hasFile('image')){
            $file = $request->file('image');
            $extension = time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('assets/images/computer');
            $file->move($destinationPath,$extension);
            $book-> book_image=$extension;

        }
        else{
            //return request
            $book-> book_image='';
        }

        $book->save();

        if ($book-> id > 0) {
            echo '1';
        }

  }


  public function viewbook(Request $request)
  {
    $books = BookModel::all();
    

        //  $books = DB::table('book')
        //     ->join('category', 'book.category_name', '=', 'category.cat_id')
        //     ->select('book.book_id','book.book_name','book.pub_name','book.price','book.quantity','book.discount','category.category_name','category.cat_id')
        //     ->first();     
            

    foreach ($books as $book){
        $book->book_image = asset("assets/images/computer/".$book->book_image);
    }    
    return response()->json($books, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  }

  public function viewuser(Request $request)
  {


    
    $users = UserAccountModel::where('status',1)
    ->join('district', 'user_account.district', '=', 'district.district_id')
    ->select('user_account.*','district.district_name','district.district_id as district')
    ->get(); 
    
    return response($users);
   

  }
   

  public function listbooks(Request $request)
  {

    $books = BookModel::get('book_image');
      
    //$books = BookModel::all();
    
    foreach ($books as $book){
       
        $book->book_image = asset("assets/images/computer/".$book->book_image);
        
       
    }
        
         return response()->json($books, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
        // return response($books);
  }
   

        public function listselbooks($cat_id)
        {
            // $books = BookModel::get()->where('cat_id',$cat_id);

            $books =  BookModel::where('category.cat_id','=',$cat_id)
            ->join('category', 'book.cat_id', '=', 'category.cat_id')
            ->select('book.book_id','book.book_name','book.pub_name','book.price','book.quantity','book.discount','book_image','stock','category.category_name','category.cat_id')
            ->get();     
            
        foreach ($books as $book){
    
                $book->book_image = asset("assets/images/computer/".$book->book_image);
    
        }
    
            return response()->json($books, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
        }
        
        public function listchoosebooks($cat_id)
        {
            // echo $id;
            // return response()->json(BookModel::get()->where('cat_id',$cat_id));
            $books = BookModel::get()->where('cat_id',$cat_id);
            foreach ($books as $book){
                $book->book_image = asset("assets/images/computer/".$book->book_image);
            }
            return response()->json($books, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
        }
        
           
        
            public function booksedit($id)
            {    
                

                
                //  $books = BookModel::get()->where('book_id',$id);
                 $books = BookModel::where('book_id', $id)
                 ->join('category', 'book.cat_id', '=', 'category.cat_id')
                 ->select('book.book_id','book.book_name','book.pub_name','book.price','book.quantity','book.discount','book_image','stock','category.category_name','category.cat_id')
                 ->get();   
               // $books = BookModel::all();
    
                foreach ($books as $book){
           
                     $book->book_image = asset("assets/images/computer/".$book->book_image);
            
                }
            
                 return response()->json($books);
                
            }
            
           public function editdata(Request $request ,$id){


            $bookname = $request-> book_name;
            $bookauth = $request-> pub_name;
            $bookprice = $request-> price;
            $bookqty =  $request-> quantity;
            $bookdis = $request-> discount;
            $bookstatus = $request-> stock;



            $res= DB::table('book')->where('book_id',$id)
            ->update(['book_name' => $bookname,'pub_name' => $bookauth,'price' => $bookprice,'quantity' => $bookqty,'discount','stock' => $bookstatus]);
            
                    // $res = BookModel::find($id);
                    // $res->update($request->all()); 
                    return response()->json($res);
        }

           public function deletebooks($id)
           {
           // $product = BookModel::find($id);
           // $product->delete();
           $product= DB::table('book')->where('book_id',$id)->delete();
            return response()->json($product);
           }

           public function userreject($id)
           {

            //$user = UserAccountModel::all();

            $user = DB::table('user_account')->where('fk_login_id', $id)->update(['status' => 0]); 
            $user = DB::table('user_login')->where('login_id', $id)->update(['user_status' => 0]); 
            return response()->json($user);

           }

           public function addtocart(Request $request)
           {

           
        $id =  $request['book_id'];
            
            $book = DB::table('book')
                     ->select('quantity')
                     ->where('book_id', '=' ,$id)
                     ->first();
                $bqty = $book->quantity;

                if($bqty>0)
                {
                    $cartstore = new CartModel();
                    $cartstore-> fk_book_id = $request->input('book_id');
                    $cartstore-> fk_user_id = $request->input('userId');
                    $cartstore-> quantity = $request->input('quantity');
                    $cartstore-> status = 'To Cart';
        
                    $cartstore->save();
                    $response = [
                        'status' => true
                    ];
                    return Response($response);
                }
                else{
                    return Response("no Stock");
                }

        }

            //------------------
        //    }
        //    elseif($book['quantity'] == 0){

//             $response = [
//                 "status" => true,
//                 "msg" => 'No Stock'
//             ];
            // echo "<script>";
            // echo alert("Out of Stock");
            // echo "</script>";
        //    }

           public function fn_incre_item_qty(Request $req)
           {
               $cart_item_obj = CartModel::where('fk_book_id', $req->input('itemId'))->first();
               $cart_item_obj->quantity += 1;
               $cart_item_obj->save();
               $response = [
                   'status' => true
               ];
               return Response($response);
           }

    public function fn_decrmnt_qty(Request $req)
    {
            $cart_item_obj = CartModel::where('fk_book_id', $req->input('itemId'))->first();
            $cart_item_obj->quantity -= 1;
            $cart_item_obj->save();
            $response = [
                'status' => true
            ];
            return Response($response);
    }
           
    public function fn_view_cart(Request $request)
    {

           // $cart = CartModel::all();

           $cart = DB::table('cart')
                   ->join('book','cart.fk_book_id', '=' ,'book.book_id')
                   ->select('book.*','cart.quantity',)
                   ->where('cart.fk_user_id', $request->input('userId'))
                   ->where('cart.status','=','To Cart')
                   ->get();
                   foreach ($cart as $book){        
                    $book->book_image = asset("assets/images/computer/".$book->book_image);      
               }        
            return response()->json($cart);
    }

    
    public function fn_delete_cart($id)
    {
        $cartproduct= DB::table('cart')->where('fk_book_id',$id)->delete();
     return response()->json($cartproduct);
    }

    
    public function fn_payment_add(Request $request)  
    {
        //inesert payment
            $userid = $request->input('userId');

            $pay = new PaymentModel();
            $pay-> fk_user_id = $request->input('userId');
            $pay-> total_amt = $request->input('total');
            $pay-> payment_type = $request->input('pay_type');
            $pay->save(); 

            //update status
            $statusupdate = DB::table('cart')
                            ->where('fk_user_id', $userid)
                            ->where('status', 'To Cart')
                            ->update(['status' => 'Purchased']);
            
           //get product id

           $users = CartModel::select('fk_book_id','quantity')
                    ->where('status', '=', 'Purchased')
                    ->where('fk_user_id', '=' ,$userid)
                    ->get();
                  echo $users;
                  //exit;

            foreach ($users as $value){ 
                //cart book id
                $qty =  $value['fk_book_id'];
                //cart quantity
                $qtyy = $value['quantity'];

                         $getdata = BookModel::select('quantity')
                         ->where('book_id', '=' ,$qty)
                         ->get(); 
                       
                        foreach ($getdata as $dvalue){ 
                            $totalquantity = $dvalue['quantity'] - $value['quantity'];
                            $qtyupdate = DB::table('book')->where('book_id', $qty)
                            ->update(['quantity' =>  $totalquantity]);
            // if($totalquantity == 0){
                    $book = DB::table('book')->where('quantity', 0)->update(['stock' => 'Out of Stock']); 
            // }
                        }

            }
        
            return Response($pay);
           
    }


    public function fn_pay_delivery(Request $request)
    {
        $userid = $request->input('userId');
        $pay = new PaymentModel();
            $pay-> fk_user_id = $request->input('userId');
            $pay-> total_amt = $request->input('total');
            $pay-> payment_type = $request->input('pay_type');
            $pay->save();  
 
            //update status
            $statusupdate = DB::table('cart')
                                ->where('fk_user_id', $userid)
                                ->where('status', 'To Cart')
                                ->update(['status' => 'Purchased']);
            //get product id
           $users = CartModel::select('fk_book_id','quantity')
           ->where('status', '=', 'Purchased')
           ->where('fk_user_id', '=' ,$userid)
           ->get();
           foreach ($users as $value){ 
            //cart book id
            $qty =  $value['fk_book_id'];
            //cart quantity
            $qtyy = $value['quantity'];

                     $getdata = BookModel::select('quantity')
                     ->where('book_id', '=' ,$qty)
                     ->get(); 
                   
                    foreach ($getdata as $dvalue){ 
                        $totalquantity = $dvalue['quantity'] - $value['quantity'];
                        $qtyupdate = DB::table('book')->where('book_id', $qty)
                        ->update(['quantity' =>  $totalquantity]);
        // if($totalquantity == 0){
                $book = DB::table('book')->where('quantity', 0)->update(['stock' => 'Out of Stock']); 
        // }
                    }

        }
            
        
            return Response($pay);

           }

           public function fn_vieworder(Request $request)
           {
            $order = DB::table('cart')->select( DB::raw('DISTINCT(fk_user_id)') )->where('status', '=', 'Purchased')->groupBy('fk_user_id')->get();

            // $order = DB::table('cart')->distinct()->get(['fk_user_id'])->where('status','=','Purchased
            // ');
            // ->where('cart.status','=','To Cart')

            // $order = DB::table('cart')
            // ->join('book', 'cart.fk_book_id','=','book.book_id')
            // ->join('table_payment','cart.fk_user_id','=','table_payment.fk_user_id')
            // ->select('cart.*','book.book_id','book.book_name','table_payment.total_amt','table_payment.payment_type')

            // ->get();     
            
            //  $order = CartModel::all();            
             return response($order);
            
         
           }
           public function  fn_adminviewcustomer(Request $request,$id)
           {

                $user= DB::table('user_account')->where('fk_login_id',$id)->get();
                
                 // $res = BookModel::find($id);
               return response()->json($user);


           }


           public function fn_adminviewbook(Request $request,$id)
           {

                $user= DB::table('cart')

            ->join('book', 'cart.fk_book_id','=','book.book_id')
            ->select('cart.*','book.book_id','book.book_name','book.price')
            ->where('cart.fk_user_id',$id)
            ->get();
                
                 // $res = BookModel::find($id);
               return response()->json($user);


           }

           
           public function fn_adminviewpayment(Request $request,$id)
           {

                $user= DB::table('table_payment')->where('fk_user_id',$id)->get();
                
               return response()->json($user);


           }


           
        
           public function fn_orderconfirm($id)
           {

            //$user = UserAccountModel::all();
            $statusupdate = DB::table('cart')->where('fk_user_id', $id)
            ->update(['status' => 'Confirm']);

            // if($statusupdate){
            //     $statusupdate = DB::table('cart')->where('status', '=', 'Confirm')
            //     ->delete();
            // }


            // $ordeconfirm= DB::table('cart')->where('status','Confirm')->update(); 

            // $orderreject = DB::table('cart')
            // ->join('book', 'cart.fk_book_id','=','book.book_id')
            // ->join('table_payment','cart.fk_user_id','=','table_payment.fk_user_id')
            // ->select('cart.*','book.book_id','book.book_name','table_payment.total_amt','table_payment.payment_type')
            // ->where('fk_book_id',$id)
            // ->delete();
            return response()->json($statusupdate);

           }
         
           public function fn_usermessage(Request $request,$id)
           {

           
            $users = UserAccountModel::select('username')
                        ->where('fk_login_id',$id)
                        ->first ();

                        return response()->json($users);


           }
           

    }

    

