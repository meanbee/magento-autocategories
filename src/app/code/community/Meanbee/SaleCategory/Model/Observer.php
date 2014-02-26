<?php

class Meanbee_SaleCategory_Model_Observer {

    /**
     * Consider a product for the Sale category.
     *
     * Observes: catalog_product_save_commit_after
     *
     * @param Varien_Event_Observer $observer
     */
    public function maintainProduct(Varien_Event_Observer $observer) {
        $product = $observer->getEvent()->getProduct();

        if ($product) {
            Mage::getModel('meanbee_salecategory/auto_category_sale')->maintain(array($product->getId()));
        }
    }
}
