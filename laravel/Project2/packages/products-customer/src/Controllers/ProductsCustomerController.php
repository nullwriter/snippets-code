<?php
/**
 * Created by PhpStorm.
 * User: cfeo
 * Date: 29/6/2016
 * Time: 11:47 AM
 */

namespace Galpa\ProductsCustomer\Controllers;
use App\Http\Controllers\Controller;
use App\Repositories\General\Product\ProductRepositoryContract;
use Illuminate\Http\Request;
use Log;


class ProductsCustomerController extends Controller
{

    protected $products;

    public function __construct(ProductRepositoryContract $products){
        $this->middleware('auth', ['only' => 'index']);
        $this->products = $products;
    }

    /**
     * Funcion que regresa el modal de acuerdo al tipo.
     *  'customer': modal que busca productos asociados a 1 cliente
     *  'product': modal que busca clientes asociados a 1 producto
     *
     * @param Request $request
     * @return mixed
     */
    public function validateItems(Request $request){
        $id = $request->get("id");
        $type = $request->get("type");

        if($type == "product"){
            $modal = "pcustomer::validate_product";
        }
        else {
            $modal = "pcustomer::validate_customer";
        }

        $returnHTML = view($modal)->with('id', $id)->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }


    /**
     * Buscador de productos por internal id.
     *
     * @param Request $request
     */
    public function search(Request $request){

        $returnHTML = view("pcustomer::search_result")->with('product', $this->products->getDetailProduct($request->get('texto')))->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

}