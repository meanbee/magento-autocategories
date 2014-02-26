<?php

class Meanbee_SaleCategory_Test_Config_Base extends EcomDev_PHPUnit_Test_Case_Config {

    /**
     * @test
     */
    public function testClassAlias() {
        $this->assertModelAlias('meanbee_salecategory/test', 'Meanbee_SaleCategory_Model_Test');
        $this->assertHelperAlias('meanbee_salecategory/test', 'Meanbee_SaleCategory_Helper_Test');
    }
}
