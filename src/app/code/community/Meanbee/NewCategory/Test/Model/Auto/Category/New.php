<?php

class Meanbee_NewCategory_Test_Model_Auto_Category_New extends EcomDev_PHPUnit_Test_Case {

    /**
     * @test
     * @loadFixture
     * @doNotIndexAll
     */
    public function testMaintain() {
        $category_id = 3;
        $date = Mage::app()->getLocale()->storeDate(Mage::app()->getStore(), "2014-02-27 12:00:00", true);

        $auto_category = $this->getModelMock('meanbee_salecategory/auto_category_new', array('getCurrentDate'));
        $auto_category
            ->expects($this->any())
            ->method('getCurrentDate')
            ->will($this->returnValue($date));

        $this->assertEquals(array(3, 4), array_keys($this->loadCategory($category_id)->getProductsPosition()));

        $auto_category->maintain();

        $this->assertEquals(array(2, 4), array_keys($this->loadCategory($category_id)->getProductsPosition()));
    }

    protected function loadCategory($id) {
        return Mage::getModel('catalog/category')->load($id);
    }
}
