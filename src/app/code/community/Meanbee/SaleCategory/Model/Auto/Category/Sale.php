<?php

class Meanbee_SaleCategory_Model_Auto_Category_Sale extends Meanbee_AutoCategories_Model_Auto_Category_Abstract {

    public function isEnabled() {
        return Mage::helper('meanbee_salecategory/config')->isEnabled();
    }

    protected function getCategoryId() {
        return Mage::helper('meanbee_salecategory/config')->getCategoryId();
    }

    protected function applyFilter(Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection) {
        $date = new DateTime('now');
        $now = $date->format('Y-m-d H:i:s');

        $collection
            ->addAttributeToFilter("special_price", array("notnull" => true))
            ->addAttributeToFilter("special_from_date", array(
                array("null" => true),
                array("lteq" => $now)
            ), "left")
            ->addAttributeToFilter("special_to_date", array(
                array("null" => true),
                array("gteq" => $now)
            ), "left");

        return $this;
    }

}