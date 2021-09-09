<?php

abstract class Meanbee_AutoCategories_Model_Auto_Category_Abstract extends Mage_Core_Model_Abstract {

    /**
     * Check if the auto category is enabled.
     *
     * @return boolean
     */
    abstract public function isEnabled();

    /**
     * Return the id of the Category model used for this auto category.
     *
     * @return int
     */
    abstract protected function getCategoryId();

    /**
     * Apply a filter to the given product collection to only select the
     * products which should be in this auto category.
     *
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
     *
     * @return $this
     */
    abstract protected function applyFilter($collection);

    /**
     * Return module name of this auto category.
     *
     * @return string
     */
    public function getModuleName() {
        $module = $this->getData('module_name');
        if (is_null($module)) {
            $class = get_class($this);
            $module = substr($class, 0, strpos($class, '_Model'));
            $this->setData('module_name', $module);
        }
        return $module;
    }

    /**
     * Return the Category model used for this auto category.
     *
     * @return Mage_Catalog_Model_Category
     */
    public function getCategory() {
        $category = $this->getData('category');
        if (is_null($category)) {
            $category = Mage::getModel('catalog/category')->load($this->getCategoryId());
            $this->setData('category', $category);
        }
        return $category;
    }

    /**
     * Return a product collection with filters for products matching this auto category.
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    public function getProductCollection() {
        $collection = $this->getData('product_collection');
        if (is_null($collection)) {
            $collection = Mage::getResourceModel('catalog/product_collection');

            $this->applyFilter($collection);

            $this->setData('product_collection', $collection);
        }
        return $collection;
    }

    /**
     * Process the given product ids against the category, adding them if they should be in it
     * or removing them if they no longer match the filter. If no products are specified, process
     * all products.
     *
     * @param array $products
     */
    public function maintain($products = array()) {
        if (!$this->isEnabled()) {
            return;
        }

        $category_id = $this->getCategoryId();
        if (!$category_id) {
            Mage::logException(new Exception(sprintf("%s doesn't have a category id set.", $this->getModuleName())));
            return;
        }

        $originalStore = Mage::app()->getStore();
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        $collection = clone $this->getProductCollection();

        // Remove products not matching the filter anymore
        $select = clone $collection->getSelect();
        $select
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns(array('product_id' => 'entity_id'));
        $where = array(
            'category_id = ?' => $category_id,
            'product_id NOT IN (?)' => $select
        );
        if (!empty($products)) {
            $where['product_id IN (?)'] = $products;
        }

        $this->getConnection()->delete($this->getCategoryProductTable(), $where);

        // Add products if they match the filter
        if (!empty($products)) {
            $collection->addIdFilter($products);
        }
        $select = clone $collection->getSelect();
        $select
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns(array(
                'category_id' => new Zend_Db_Expr($category_id),
                'product_id'  => 'entity_id',
                'position'    => $this->getPositionExpr()
            ));
        $insert = $select->insertIgnoreFromSelect($this->getCategoryProductTable(), array('category_id', 'product_id', 'position'));

        $this->getConnection()->query($insert);

        if(Mage::helper('catalog/category_flat')->isEnabled()){
            Mage::getSingleton('index/indexer')->getProcessByCode('catalog_category_product')->reindexEverything();
        }

        Mage::app()->setCurrentStore($originalStore);
    }

    /**
     * Return the database expression for the product position in the category.
     *
     * @return Zend_Db_Expr
     */
    protected function getPositionExpr() {
        return new Zend_Db_Expr(0);
    }

    /**
     * Get a database connection (with write permissions).
     *
     * @return Zend_Db_Adapter_Abstract
     */
    protected function getConnection() {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }

    /**
     * Return the name of the table used to store products in categories.
     *
     * @return string
     */
    protected function getCategoryProductTable() {
        return Mage::getModel('core/resource')->getTableName('catalog/category_product');
    }
}
