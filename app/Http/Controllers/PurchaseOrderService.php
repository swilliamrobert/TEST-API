<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PurchaseOrderProduct;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PurchaseOrderService extends Controller
{
    private $username = 'demo';
    private $password = 'pwd1234';

    private $carton_cloud_user_name = 'interview-test@cartoncloud.com.au';
    private $carton_cloud_password = 'test123456';

    private $result = null;

    /**
     * PurchaseOrderService constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the purchase order id and username, password for access the API
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$this->authenticate()) {
            return Response(array(
                'code' => 401,
                'message' => 'Unauthorized'
            ), 401);
        }

        $purchase_order_ids = $this->request->input('purchase_order_ids');
        $this->result = $this->getGuzzleHttp($purchase_order_ids);

        //TODO: Print the result in json format
        echo $this->jsonResponse($this->result);

    }

    /**
     * Verify the API Authentication and return the boolean.
     *
     * @return bool
     */
    private function authenticate()
    {
        $username = $this->request->input('username');
        $password = $this->request->input('password');
        if ($username == $this->username && $password == $this->password) {
            return true;
        }
        return false;
    }

    /**
     * GuzzleHTTP Request API Call to Carton Cloud
     *
     * @param $purchase_order_id
     * @return bool
     */
    private function getGuzzleHttp($purchase_order_id)
    {
        $client = new Client();
        $products = [];
        $url = "https://api.cartoncloud.com.au/CartonCloud_Demo/PurchaseOrders/$purchase_order_id?version=5&associated=true";
        $response = $client->request('GET', $url,
            ['auth' => [
                $this->carton_cloud_user_name, $this->carton_cloud_password]
            ]);
        if ($response->getStatusCode() == 200) {
            $product = $response->getBody();
            $products[] = json_decode($product->getContents());
        }
        $orders = new PurchaseOrderProduct();
        return $orders->getProducts($products);
    }

    /**
     * CalculateTotals
     *
     * @param $ids
     * @return bool
     */
    public function calculateTotals(array $ids)
    {
        return $this->getGuzzleHttp($ids);
    }

    /**
     * jsonResponse output
     *
     * @return string
     */
    public function jsonResponse()
    {
        if (empty($this->result)) {
            return json_encode(['result' => null]);
        }
        return json_encode(['result' => $this->result]);
    }
}
