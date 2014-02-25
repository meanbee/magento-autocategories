<?php

class Meanbee_SaleCategory_Helper_Config extends Mage_Core_Helper_Abstract {

    const XML_PATH_IS_ENABLED  = 'meanbee_autocategories/sale_category/enabled';
    const XML_PATH_CATEGORY_ID = 'meanbee_autocategories/sale_category/category';

    /**
     * Check if SaleCategory is enabled.
     *
     * @return bool
     */
    public function isEnabled() {
        return (Mage::getStoreConfigFlag(self::XML_PATH_IS_ENABLED)) ? $this->checkDependencies() : false;
    }

    /**
     * Return the id of the Magento category used for SaleCategory.
     *
     * @return string
     */
    public function getCategoryId() {
        return Mage::getStoreConfig(self::XML_PATH_CATEGORY_ID);
    }

    /**
     * Check if the dependencies required by SaleCategory are enabled.
     *
     * @return bool
     */
    protected function checkDependencies() {
        // Check if core module is installed
        if (!Mage::helper('core')->isModuleEnabled('Meanbee_AutoCategories')) {
            Mage::logException(new Exception("Meanbee_SaleCategory requires Meanbee_AutoCategories to be installed and enabled."));
            return false;
        }

        // Check if core module is enabled in system configuration
        if (!Mage::helper('meanbee_autocategories/config')->isEnabled()) {
            Mage::logException(new Exception("Meanbee_SaleCategory requires Meanbee_AutoCategories to be enabled."));
            return false;
        }

        return true;
    }
}
