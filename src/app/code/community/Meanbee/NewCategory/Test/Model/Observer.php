<?php

class Meanbee_NewCategory_Test_Model_Observer extends EcomDev_PHPUnit_Test_Case {

    public function tearDown() {
        // Clear out all products
        Mage::getModel('catalog/product')->getCollection()->delete();

        parent::tearDown();
    }

    /**
     * @test
     * @loadFixture
     * @dataProvider dataProvider
     */
    public function testObserveNewProduct($product_data) {
        $product = Mage::getModel('catalog/product')->setData($product_data);
        try {
            $product->save();
        } catch (Exception $e) {
            Mage::throwException("Failed to save product: " . $e->getMessage());
        }

        $product = Mage::getModel('catalog/product')->load($product->getId());

        $this->assertContains(3, $product->getCategoryIds());
    }
}
