<?php

class Meanbee_AutoCategories_Helper_Config extends Mage_Core_Helper_Abstract {

    const XML_PATH_IS_ENABLED = "meanbee_autocategories/general/enabled";

    /**
     * Check if the AutoCategories module is enabled in System Configuration.
     *
     * @return bool
     */
    public function isEnabled() {
        return (Mage::getStoreConfig(self::XML_PATH_IS_ENABLED)) ? true : false;
    }
}
