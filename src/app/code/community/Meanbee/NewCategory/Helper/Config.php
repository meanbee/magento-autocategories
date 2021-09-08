<?php

class Meanbee_NewCategory_Helper_Config extends Mage_Core_Helper_Abstract {

    const XML_PATH_IS_ENABLED     = 'meanbee_autocategories/new_category/enabled';
    const XML_PATH_CATEGORY_ID    = 'meanbee_autocategories/new_category/category';
    const XML_PATH_USE_CREATED_AT = 'meanbee_autocategories/new_category/use_created_at';
    const XML_PATH_DAYS_NEW       = 'meanbee_autocategories/new_category/days';

    /**
     * Check if NewCategory is enabled.
     *
     * @return bool
     */
    public function isEnabled() {
        return (Mage::getStoreConfigFlag(self::XML_PATH_IS_ENABLED)) ? $this->checkDependencies() : false;
    }

    /**
     * Return use if is mode created_at enabled
     *
     * @return string
     */
    public function getUseCreatedAt() {
        return Mage::getStoreConfig(self::XML_PATH_USE_CREATED_AT);
    }

    /**
     * Return the id of the Magento category used for NewCategory.
     *
     * @return string
     */
    public function getCategoryId() {
        return Mage::getStoreConfig(self::XML_PATH_CATEGORY_ID);
    }

    /**
     * Return the number of days a product should stay in the "New" category
     * after it is created.
     *
     * @return int
     */
    public function getDaysNew() {
        return intval(Mage::getStoreConfig(self::XML_PATH_DAYS_NEW));
    }

    /**
     * Check if the dependencies required by NewCategory are enabled.
     *
     * @return bool
     */
    protected function checkDependencies() {
        // Check if core module is installed
        if (!Mage::helper('core')->isModuleEnabled('Meanbee_AutoCategories')) {
            Mage::logException(new Exception("Meanbee_NewCategory requires Meanbee_AutoCategories to be installed and enabled."));
            return false;
        }

        // Check if core module is enabled in system configuration
        if (!Mage::helper('meanbee_autocategories/config')->isEnabled()) {
            Mage::logException(new Exception("Meanbee_NewCategory requires Meanbee_AutoCategories to be enabled."));
            return false;
        }

        return true;
    }
}
