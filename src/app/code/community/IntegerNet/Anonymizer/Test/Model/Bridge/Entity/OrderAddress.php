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
class IntegerNet_Anonymizer_Test_Model_Bridge_Entity_OrderAddress
    extends IntegerNet_Anonymizer_Test_Model_Bridge_Entity_Abstract
{
    /**
     * @param $orderAddressId
     * @test
     * @dataProvider dataProvider
     * @dataProviderFile testOrderAddressBridge.yaml
     * @loadExpectation bridge.yaml
     * @loadFixture customers.yaml
     */
    public function testGetValues($orderAddressId)
    {
        /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_Address_QuoteAddress $bridge */
        $bridge = Mage::getModel('integernet_anonymizer/bridge_entity_address_orderAddress');
        /** @var Mage_Sales_Model_Quote_Address $orderAddress */
        $orderAddress = $this->_loadEntityByCollection('entity_id', $orderAddressId, $bridge);
        $expected = $this->expected('order_address_%d', $orderAddressId);

        $this->_testGetValues($bridge, $orderAddress, $expected);
    }

    /**
     * @param $orderAddressId
     * @test
     * @dataProvider dataProvider
     * @dataProviderFile testOrderAddressBridge.yaml
     * @loadFixture customers.yaml
     */
    public function testUpdateValues($orderAddressId)
    {
        static $changedMiddlename = 'trouble',
               $changedStreet = "New Street\nSecond Line";

        /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_Address_OrderAddress $bridge */
        $bridge = Mage::getModel('integernet_anonymizer/bridge_entity_address_orderAddress');

        $dataProvider = Mage::getModel('sales/order_address');
        $bridge->setRawData($dataProvider->load($orderAddressId)->getData());
        $bridge->getValues()['middlename']->setValue($changedMiddlename);
        $bridge->getValues()['street']->setValue($changedStreet);

        $this->_updateValues($bridge);

        $orderAddress = Mage::getModel('sales/order_address')->load($orderAddressId);
        $this->assertEquals($changedMiddlename, $orderAddress->getMiddlename());
        $this->assertEquals($changedStreet, $orderAddress->getStreetFull());
    }
}