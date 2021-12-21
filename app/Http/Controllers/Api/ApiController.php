<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Exception;
use Illuminate\Http\Request;
use Validator;

class ApiController extends Controller {

	public function index() {

		return 'Test okay';
	}

	public function create(Request $request) {

		$validator = Validator::make($request->all(), [
			'sku' => 'required',
			'qty' => 'required|numeric',
			'product_id' => 'required|numeric',
		], [
			'sku.required' => 'sku is required',
			'qty.required' => 'qty is required',
			'product_id.required' => 'Product ID is required',
		]);

		try {

			if ($validator->fails()) {
				$errors = $validator->errors();
				$err = '';
				foreach ($errors->all() as $message) {
					$err .= $message;
				}
				$err .= '';
				throw new Exception($err, 1);
			}

			$product_id = $request['product_id'];
			$prod_exist = Inventory::where('product_id', $product_id)->first();

			if($prod_exist){

				$response = array();
				$response['message'] = "Product ID already exist!";
				return response()->json($response, $status = 406, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			}

			$qty = $request['qty'];
			$operation = substr($qty, 0, 1);

			if($operation == "-"){

				$response = array();
				$response['message'] = "Invalid quantity to add";
				return response()->json($response, $status = 405, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
				
			}

			$post = Inventory::create($request->all());
        	return response()->json($post, 201);

		} catch (Exception $e) {

			$response = array();
			$response['message'] = $e->getMessage();

			return response()->json($response, $status = 500, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

		}
	}

	public function update(Request $request) {

		$validator = Validator::make($request->all(), [
			'sku' => 'required',
			'qty' => 'required|numeric',
			'product_id' => 'required|numeric',
		], [
			'sku.required' => 'sku is required',
			'qty.required' => 'qty is required',
			'product_id.required' => 'Product ID is required',
		]);

		try {

			if ($validator->fails()) {
				$errors = $validator->errors();
				$err = '';
				foreach ($errors->all() as $message) {
					$err .= $message;
				}
				$err .= '';
				throw new Exception($err, 1);
			}

			$product_id = $request['product_id'];
			$sku = $request['sku'];
			$qty = $request['qty'];

			$operation = substr($qty, 0, 1);


			$exist = Inventory::where('product_id', $product_id)->first();


			if(!$exist){

				$response = array();
				$response['message'] = "Invalid Product ID !";
				return response()->json($response, $status = 406, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			}

			$exist_quantity = $exist->qty;

			if($operation == "+"){
				$update_quantity = $exist_quantity + ltrim($qty, $qty[0]);
			}else{
		    	$update_quantity = $exist_quantity - ltrim($qty, $qty[0]);
			}

			$update = Inventory::where('product_id', $product_id)->update(['sku' => $sku, 'qty' => $update_quantity]);

			if($update){

				$response = array();
				$response['message'] = "Product updated Successfully !";
				return response()->json($response, $status = 200, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			}else{

				$response = array();
				$response['message'] = "Something went wrong !";
				return response()->json($response, $status = 400, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			}

		} catch (Exception $e) {

			$response = array();
			$response['status'] = false;
			$response['message'] = $e->getMessage();

			return response()->json($response, $status = 500, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

		}
	}

	public function list() {

		$data = Inventory::orderBy('id', 'desc')->select('sku', 'qty')->get();

		if(count($data) > 0){

			$response = array();
			$response['data'] = $data;
			return response()->json($response, $status = 200, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		}else{

			$response = array();
			$response['message'] = 'No records found !';
			return response()->json($response, $status = 404, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		}	
	}

	public function delete(Request $request){

		$validator = Validator::make($request->all(), [
			'product_id' => 'required',
		], [
			'product_id.required' => 'Product ID is required',
		]);

		try {

			if ($validator->fails()) {
				$errors = $validator->errors();
				$err = '';
				foreach ($errors->all() as $message) {
					$err .= $message;
				}
				$err .= '';
				throw new Exception($err, 1);
			}

				$product_id = $request['product_id'];

				$data = Inventory::where('product_id',$product_id)->delete();

				if($data){
					$response = array();
					$response['message'] = 'Product Deleted successfully';
					return response()->json($response, $status = 204, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
				}else{
					$response = array();
					$response['message'] = 'Please try again';
					return response()->json($response, $status = 400, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
				}

		} catch (Exception $e) {

			$response = array();
			$response['status'] = false;
			$response['message'] = $e->getMessage();
			return response()->json($response, $status = 500, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		}
	}

	public function get(Request $request){

		$validator = Validator::make($request->all(), [
			'product_id' => 'required',
		], [
			'product_id.required' => 'Product ID is required',
		]);

		try {

			if ($validator->fails()) {
				$errors = $validator->errors();
				$err = '';
				foreach ($errors->all() as $message) {
					$err .= $message;
				}
				$err .= '';
				throw new Exception($err, 1);
			}

				$product_id = $request['product_id'];

				$data = Inventory::where('product_id',$product_id)->first();

				if($data){
					$response = array();
					$response['data'] = $data;
					return response()->json($response, $status = 200, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
				}else{
					$response = array();
					$response['message'] = 'Please try again';
					return response()->json($response, $status = 400, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
				}

		} catch (Exception $e) {

			$response = array();
			$response['status'] = false;
			$response['message'] = $e->getMessage();
			return response()->json($response, $status = 500, $headers = [], $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		}
	}
}