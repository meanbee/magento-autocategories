<?php

class Meanbee_AutoCategories_Test_Model_Auto_Category_Abstract extends EcomDev_PHPUnit_Test_Case {

    /**
     * @test
     * @loadFixture
     * @doNotIndexAll
     */
    public function testMaintain() {
        $category_id = 3;
        $matching_product_id = 1;
        $non_matching_product_id = 2;

        $product_collection = Mage::getModel('catalog/product')->getCollection();
        $product_collection->addIdFilter($matching_product_id);

        $auto_category = $this->getMockAutoCategory($category_id, $product_collection);

        $this->assertNotContains($category_id, $this->loadProduct($matching_product_id)->getCategoryIds());
        $this->assertContains($category_id, $this->loadProduct($non_matching_product_id)->getCategoryIds());

        $auto_category->maintain();

        $this->assertContains($category_id, $this->loadProduct($matching_product_id)->getCategoryIds());
        $this->assertNotContains($category_id, $this->loadProduct($non_matching_product_id)->getCategoryIds());
    }

    /**
     * @test
     * @loadFixture
     * @doNotIndexAll
     */
    public function testMaintainSpecificProducts() {
        $category_id = 3;
        $matching_product_id = 1;
        $non_matching_product_id = 2;
        $non_maintained_product = 3;

        $product_collection = Mage::getModel('catalog/product')->getCollection();
        $product_collection->addIdFilter($matching_product_id);

        $auto_category = $this->getMockAutoCategory($category_id, $product_collection);

        $this->assertEquals(array(2, 3), array_keys($this->loadCategory($category_id)->getProductsPosition()));

        $auto_category->maintain(array(1, 2));

        $this->assertEquals(array(1, 3), array_keys($this->loadCategory($category_id)->getProductsPosition()));
    }

    /**
     * @test
     * @loadFixture
     * @doNotIndexAll
     */
    public function testMaintainOnlyAdd() {
        $category_id = 3;

        $product_collection = Mage::getModel('catalog/product')->getCollection();
        $product_collection->addIdFilter(1);

        $auto_category = $this->getMockAutoCategory($category_id, $product_collection);

        $this->assertEquals(0, $this->loadCategory($category_id)->getProductCount());

        $auto_category->maintain();

        $this->assertGreaterThan(0, $this->loadCategory($category_id)->getProductCount());
    }

    /**
     * @test
     * @loadFixture
     * @doNotIndexAll
     */
    public function testMaintainOnlyRemove() {
        $category_id = 3;

        $product_collection = Mage::getModel('catalog/product')->getCollection();
        $product_collection->addIdFilter(2);

        $auto_category = $this->getMockAutoCategory($category_id, $product_collection);

        $this->assertGreaterThan(0, $this->loadCategory($category_id)->getProductCount());

        $auto_category->maintain();

        $this->assertEquals(0, $this->loadCategory($category_id)->getProductCount());
    }

    /**
     * @test
     * @loadFixture testMaintain.yaml
     * @doNotIndexAll
     */
    public function testMaintainDisabled() {
        $category_id = 3;

        $auto_category = $this->getModelMock(
            'meanbee_autocategories/auto_category_abstract',
            array('isEnabled', 'getCategoryid', 'applyFilter'),
            true
        );
        $auto_category->expects($this->any())->method('isEnabled')->will($this->returnValue(false));
        $auto_category->expects($this->any())->method('getCategoryId')->will($this->returnValue($category_id));

        $product_collection = Mage::getModel('catalog/product')->getCollection();
        $product_collection->addIdFilter(1);

        $auto_category->setProductCollection($product_collection);

        $this->assertEquals(array(2), array_keys($this->loadCategory($category_id)->getProductsPosition()));

        $auto_category->maintain();

        $this->assertEquals(array(2), array_keys($this->loadCategory($category_id)->getProductsPosition()));
    }

    protected function loadProduct($id) {
        return Mage::getModel('catalog/product')->load($id);
    }

    protected function loadCategory($id) {
        return Mage::getModel('catalog/category')->load($id);
    }

    /**
     * Return a Mock of meanbee_autocategories/auto_category_abstract object.
     *
     * @param int                                                       $category_id         Category id to use.
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $product_collection  Product collection to use for filtering.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockAutoCategory($category_id, $product_collection) {
        $auto_category = $this->getModelMock(
            'meanbee_autocategories/auto_category_abstract',
            array('isEnabled', 'getCategoryid', 'applyFilter'),
            true
        );
        $auto_category->expects($this->any())->method('isEnabled')->will($this->returnValue(true));
        $auto_category->expects($this->any())->method('getCategoryId')->will($this->returnValue($category_id));

        $auto_category->setProductCollection($product_collection);

        return $auto_category;
    }
}
