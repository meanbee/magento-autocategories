<?php

class Meanbee_AutoCategories_Model_Cron {

    const XML_PATH_AUTO_CATEGORIES = "global/meanbee_autocategories";

    /**
     * Call the maintain() method on each of the registered categories.
     */
    public function maintainCategories() {
        if (!Mage::helper('meanbee_autocategories/config')->isEnabled()) {
            return;
        }

        $config = Mage::getConfig()->getNode(self::XML_PATH_AUTO_CATEGORIES);
        foreach ($config->children() as $auto_category) {
            if ($auto_category->model) {
                Mage::getModel((string)$auto_category->model)->maintain();
            }
        }
    }
}
