<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */

/**
 * @group IntegerNet_Anonymizer
 */
class IntegerNet_Anonymizer_Test_Model_Bridge_Entity_Order
    extends IntegerNet_Anonymizer_Test_Model_Bridge_Entity_Abstract
{
    /**
     * @param $orderId
     * @test
     * @dataProvider dataProvider
     * @dataProviderFile testOrderBridge.yaml
     * @loadExpectation bridge.yaml
     * @loadFixture customers.yaml
     */
    public function testGetValues($orderId)
    {
        /** @var IntegerNet_Anonymizer_Test_Model_Bridge_Entity_Order $bridge */
        $bridge = Mage::getModel('integernet_anonymizer/bridge_entity_order');
        /** @var Mage_Sales_Model_Order $order */
        $order = $this->_loadEntityByCollection('entity_id', $orderId, $bridge);
        $expected = $this->expected('order_%d', $orderId);

        $this->_testGetValues($bridge, $order, $expected);
    }

    /**
     * @param $customerId
     * @test
     * @dataProvider dataProvider
     * @dataProviderFile testCustomerBridge.yaml
     * @loadFixture customers.yaml
     */
    public function testUpdateValues($customerId)
    {
        static $changedEmail = 'changed@example.com',
               $changedMiddlename = 'trouble';

        /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_Customer $bridge */
        $bridge = Mage::getModel('integernet_anonymizer/bridge_entity_customer');

        $bridge->setRawData(array(
            'entity_id' => $customerId,
            'email'     => $changedEmail,
            'middlename' => $changedMiddlename
            ));

        $this->_updateValues($bridge);

        $customer = Mage::getModel('customer/customer')->load($customerId);
        $this->assertEquals($changedMiddlename, $customer->getMiddlename());
        $this->assertEquals($changedEmail, $customer->getEmail());
    }

}