<?php

namespace App\Http\Controllers;

class PurchaseOrderProduct
{
    const
        PRODUCT_TYPE_ONE = 1,
        PRODUCT_TYPE_TWO = 2,
        PRODUCT_TYPE_THREE = 3;

    /**
     * Get Products
     *
     * @param $products
     * @return array
     */
    public function getProducts($products)
    {
        $output = [];
        foreach ($products as $product) {
            $results = $product->data->PurchaseOrderProduct;
            foreach ($results as $key => $item) {
                switch ($item->product_type_id) {
                    case self::PRODUCT_TYPE_ONE:
                    case self::PRODUCT_TYPE_THREE:
                        $output[] = $this->productCalculation($item, $item->Product->weight);
                        break;

                    case self::PRODUCT_TYPE_TWO:
                        $output[] = $this->productCalculation($item, $item->Product->volume);
                        break;
                    default:
                        break;
                }
            }
        }

        return $output;
    }

    /**
     * Product calculation based upon the type
     *
     * @param $item
     * @param $type
     * @return array
     */
    private function productCalculation($item, $type)
    {
        $sum = 0;
        $sum += $item->unit_quantity_initial + $type;
        return ['product_type_id' => $item->product_type_id, 'total' => $sum];
    }

}