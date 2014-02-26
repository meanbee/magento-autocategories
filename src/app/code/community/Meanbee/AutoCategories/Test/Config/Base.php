<?php

class Meanbee_AutoCategories_Test_Config_Base extends EcomDev_PHPUnit_Test_Case_Config {

    /**
     * @test
     */
    public function testClassAlias() {
        $this->assertModelAlias('meanbee_autocategories/test', 'Meanbee_AutoCategories_Model_Test');
        $this->assertHelperAlias('meanbee_autocategories/test', 'Meanbee_AutoCategories_Helper_Test');
    }
}
