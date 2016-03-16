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
     * @param $orderId
     * @param $customerId
     * @test
     * @dataProvider dataProvider
     * @dataProviderFile testOrderBridge.yaml
     * @loadFixture customers.yaml
     */
    public function testUpdateValues($orderId, $customerId)
    {
        static $changedEmail = 'changed@example.com',
               $changedMiddlename = 'trouble';

        /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_Order $bridge */
        $bridge = Mage::getModel('integernet_anonymizer/bridge_entity_order');

        $bridge->setRawData(array(
            'entity_id' => $orderId,
            'increment_id' => '1000000001',
            'customer_id' => $customerId,
            'customer_email'     => $changedEmail,
            'customer_middlename' => $changedMiddlename
            ));

        $this->_updateValues($bridge);

        $order = Mage::getModel('sales/order')->load($orderId);
        $this->assertEquals($changedMiddlename, $order->getCustomerMiddlename());
        $this->assertEquals($changedEmail, $order->getCustomerEmail());
        $this->assertNotEmpty($order->getIncrementId(), 'Increment ID should not be empty');
    }

}