<?php

class Meanbee_NewCategory_Model_Observer {

    /**
     * Consider a product for the "New" category when it is saved.
     *
     * Observes: catalog_product_save_commit_after
     *
     * @param Varien_Event_Observer $observer
     */
    public function maintainProduct(Varien_Event_Observer $observer) {
        /** @var Mage_Catalog_Model_Product $product */
        $product = $observer->getEvent()->getProduct();

        if ($product) {
            Mage::getModel('meanbee_newcategory/auto_category_new')->maintain(array($product->getId()));
        }
    }
}
