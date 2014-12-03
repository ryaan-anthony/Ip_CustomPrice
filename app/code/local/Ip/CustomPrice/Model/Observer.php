<?php

class Ip_CustomPrice_Model_Observer
{

    //the sku we use to CREATE and IDENTIFY a custom price product
    const SKU = 'custom-price';

    //the config path we saved during install used to identify the custom price option
    const OPTION_PATH = 'customprice/option/id';


    public function apply_payment(Varien_Event_Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();

        if($product->getSku() == self::SKU){

            $productOptions = $product->getTypeInstance(true)->getOrderOptions($product);

            if(!empty($productOptions['options'])){

                foreach($productOptions['options'] as $opt){

                    if($opt['option_id'] == Mage::getStoreConfig(self::OPTION_PATH)){

                        $product->setFinalPrice(floatval($opt['value']));

                    }
                }

            }

        }

        return $this;
    }

}