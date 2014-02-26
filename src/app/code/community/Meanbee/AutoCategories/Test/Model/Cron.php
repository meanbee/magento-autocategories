<?php

class Meanbee_AutoCategories_Test_Model_Cron extends EcomDev_PHPUnit_Test_Case {

    /**
     * @test
     * @loadFixture
     */
    public function testMaintainCategories() {
        $model = $this->getModelMock('meanbee_autocategories/auto_category_abstract', array('maintain'), true);

        $model
            ->expects($this->once())
            ->method('maintain');

        $this->replaceByMock('model', 'meanbee_autocategories/auto_category_abstract', $model);

        Mage::getModel('meanbee_autocategories/cron')->maintainCategories();
    }
}
