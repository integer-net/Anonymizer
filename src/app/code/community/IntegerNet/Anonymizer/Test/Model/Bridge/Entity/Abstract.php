<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
class IntegerNet_Anonymizer_Test_Model_Bridge_Entity_Abstract extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @param $bridge
     * @param $expected
     */
    protected function _testGetValues(IntegerNet_Anonymizer_Model_Bridge_Entity_Abstract $bridge,
                                      Mage_Core_Model_Abstract $model, Varien_Object $expected)
    {
        $bridge->setRawData($model->getData());
        $this->assertEquals($expected['identifier'], $bridge->getIdentifier(), 'Identifier');
        $actualValues = $bridge->getValues();
        reset($actualValues);
        foreach ($expected['values'] as $expectedValue) {
            $this->assertInstanceOf(IntegerNet\Anonymizer\AnonymizableValue::__CLASS,
                current($actualValues), 'Value instance');
            $this->assertEquals($expectedValue['formatter'],
                current($actualValues)->getFakerFormatter(), 'Value formatter');
            $this->assertEquals($expectedValue['value'],
                current($actualValues)->getValue(), 'Value');
            if (!empty($expectedValue['unique'])) {
                $this->assertTrue(current($actualValues)->isUnique(), 'Unique');
            }
            next($actualValues);
        }
    }

    /**
     * @param $bridge
     */
    protected function _updateValues($bridge)
    {
        $bridge->updateValues();

        $this->assertNotEquals('', $bridge->getIdentifier());
        $bridge->clearInstance();
        $this->assertEquals('', $bridge->getIdentifier());
        foreach ($bridge->getValues() as $value) {
            $this->logicalOr(
                $this->isEmpty($value->getValue()),
                $this->equalTo(array(''))
            );
        }
    }
}