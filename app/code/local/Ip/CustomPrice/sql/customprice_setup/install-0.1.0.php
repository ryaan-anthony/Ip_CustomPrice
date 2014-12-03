<?php
$this->startSetup();

$orig_store = Mage::app()->getStore();

Mage::app()->setUpdateMode(false);
Mage::register('isSecureArea', true);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

try{

    /** @var Mage_Catalog_Model_Product $product */
    $product = Mage::getModel('catalog/product');

    $product->addData([
        'store_id'           => Mage_Core_Model_App::ADMIN_STORE_ID,
        'website_ids'        => [1],
        'attribute_set_id'   => 9,
        'type_id'            => Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
        'sku'                => Ip_CustomPrice_Model_Observer::SKU,
        'name'               => 'Custom Price',
        'weight'             => 0,
        'tax_class_id'       => 0,
        'price'              => 0,
        'status'             => Mage_Catalog_Model_Product_Status::STATUS_ENABLED,
        'visibility'         => Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG,
        'description'        => 'Enter a price in the product option field.',
        'short_description'  => 'Pay any price!',
        'stock_data'         => [
            'use_config_manage_stock' => 0,
            'manage_stock'            => 1,
        ],
        'has_options'        => true,
        'required_options'   => true,
        'initial_setup_flag' => true,
        'created_at'         => strtotime('now')
    ]);

    /** @var Mage_Catalog_Model_Product_Option $option */
    $option = $product->getOptionInstance()->unsetOptions();

    $option->addOption([
        'title'      => 'Amount',
        'type'       => Mage_Catalog_Model_Product_Option::OPTION_TYPE_FIELD,
        'is_require' => 1,
        'sort_order' => 0,
    ]);

    $option->setProduct($product);

    $product->save();

    $product = Mage::getModel('catalog/product')->load($product->getId());

    foreach($product->getOptions() as $option){
        $config = Mage::getModel('core/config');
        $config->saveConfig(Ip_CustomPrice_Model_Observer::OPTION_PATH, $option->getId());
        break;
    }

} catch(Exception $e){

    Mage::throwException($e->getMessage());

}

Mage::app()->setUpdateMode(true);
Mage::unregister('isSecureArea');
Mage::app()->setCurrentStore($orig_store);

$this->endSetup();