<?php

class Meanbee_NewCategory_Test_Config_Base extends EcomDev_PHPUnit_Test_Case_Config {

    /**
     * @test
     */
    public function testClassAlias() {
        $this->assertModelAlias('meanbee_newcategory/test', 'Meanbee_NewCategory_Model_Test');
        $this->assertHelperAlias('meanbee_newcategory/test', 'Meanbee_NewCategory_Helper_Test');
    }
}
