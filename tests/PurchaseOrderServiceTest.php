<?php

// RUN command : call vendor\bin\phpunit

require('vendor/autoload.php');

use App\Http\Controllers\PurchaseOrderProduct;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PurchaseOrderServiceTest extends PHPUnit_Framework_TestCase
{
    protected $client;

    private $username = 'demo';
    private $password = 'pwd1234';

    private $base_url = 'https://api.cartoncloud.com.au/CartonCloud_Demo/PurchaseOrders/';
    private $carton_cloud_user_name = 'interview-test@cartoncloud.com.au';
    private $carton_cloud_password = 'test123456';

    private $response = null;

    protected function setUp()
    {
        $purchase_order_id = 2344;
        $client = new Client();
        $url = $this->base_url."$purchase_order_id?version=5&associated=true";
        $this->response = $client->request('GET', $url,
            ['auth' => [
                $this->carton_cloud_user_name, $this->carton_cloud_password]
            ]);
    }

    public function testGet_ValidInput_BookObject()
    {
        $products = [];

        $this->assertEquals(200, $this->response->getStatusCode());
        $product = $this->response->getBody();
        $products[] = json_decode($product->getContents());

        $orders = new PurchaseOrderProduct();
        $result =  $orders->getProducts($products);

        $this->assertEquals(1, $result[0]['product_type_id']);
        $this->assertEquals(11.5, $result[0]['total']);

        $this->assertEquals(2, $result[1]['product_type_id']);
        $this->assertEquals(15.6, $result[1]['total']);

        $this->assertEquals(3, $result[2]['product_type_id']);
        $this->assertEquals(7.5, $result[2]['total']);

    }
}