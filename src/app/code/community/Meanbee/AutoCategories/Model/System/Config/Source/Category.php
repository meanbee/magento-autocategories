<?php

class Meanbee_AutoCategories_Model_System_Config_Source_Category {

    const PREFIX = "--";

    public function toOptionArray() {
        $root_category = Mage::getModel('catalog/category')->loadByAttribute('parent_id', 0);

        $options = $this->getCategoryTreeAsOptionArray($root_category);

        return $options;
    }

    /**
     * Recursively traverse the category tree and return all categories (apart from the root)
     * as an option array.
     *
     * @param     $root
     * @param int $level
     *
     * @return array
     */
    protected function getCategoryTreeAsOptionArray($root, $level = 0) {
        $options = array();

        if ($root->hasChildren()) {
            foreach ($root->getChildrenCategories() as $child) {
                $options[] = array(
                    'label' => sprintf("%s %s", str_repeat(self::PREFIX, $level), $child->getName()),
                    'value' => $child->getId()
                );

                $options = array_merge($options, $this->getCategoryTreeAsOptionArray($child, $level + 1));
            }
        }

        return $options;
    }
}
