<?php

class Meanbee_NewCategory_Model_Auto_Category_New extends Meanbee_AutoCategories_Model_Auto_Category_Abstract {

    /**
     * Check if the auto category is enabled.
     *
     * @return boolean
     */
    public function isEnabled() {
        return $this->getConfig()->isEnabled();
    }

    /**
     * Return the id of the Category model used for this auto category.
     *
     * @return int
     */
    protected function getCategoryId() {
        return $this->getConfig()->getCategoryId();
    }

    /**
     * Apply a filter to the given product collection to only select the
     * products which should be in this auto category.
     *
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
     *
     * @return $this
     */
    protected function applyFilter($collection) {
        if($this->getConfig()->getUseCreatedAt()){
            $cutoff_date = $this->getCutoffDate()->toString('YYYY-MM-dd HH:mm:ss');

            $collection->addAttributeToFilter('created_at', array('gteq' => $cutoff_date));
        
        }else{
            $now = $this->getCurrentDate()->toString('YYYY-MM-dd HH:mm:ss');        
            $collection->addAttributeToFilter("news_from_date", array(
                array("notnull" => false),
                array("lteq" => $now)
            ), "left")
            ->addAttributeToFilter("news_to_date", array(
                array("null" => true),
                array("gteq" => $now)
            ), "left");

        }
    }

    /**
     * Return the current date.
     *
     * @return Zend_Date
     */
    protected function getCurrentDate() {
        return Mage::app()->getLocale()->storeDate(Mage::app()->getStore(), null, true);
    }

    /**
     * Return the cut-off date for products considered to be new.
     *
     * @return Zend_Date
     */
    protected function getCutoffDate() {
        $date = Mage::app()->getLocale()->storeDate(Mage::app()->getStore(), null, true);
        $date->subDay($this->getConfig()->getDaysNew());

        return $date;
    }

    /**
     * Return the configuration helper.
     *
     * @return Meanbee_NewCategory_Helper_Config
     */
    protected function getConfig() {
        return Mage::helper('meanbee_newcategory/config');
    }
}
