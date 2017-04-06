<?php
/**
 * Created by PhpStorm.
 * User: cfeo
 * Date: 29/6/2016
 * Time: 11:47 AM
 */

namespace Galpa\ProductsCustomer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;


class ProductsCustomerController extends Controller
{

    public function __construct(){
        $this->middleware('auth', ['only' => 'index']);
    }

    public function view(Request $request){

        $idCustomer = $request->get("id");

        $returnHTML = view('pcustomer::products')->with('idCustomer', $idCustomer)->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

}