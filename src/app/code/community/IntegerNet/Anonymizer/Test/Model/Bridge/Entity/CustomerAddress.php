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
class IntegerNet_Anonymizer_Test_Model_Bridge_Entity_CustomerAddress
    extends IntegerNet_Anonymizer_Test_Model_Bridge_Entity_Abstract
{
    /**
     * @param $customerAddressId
     * @test
     * @dataProvider dataProvider
     * @dataProviderFile testCustomerAddressBridge.yaml
     * @loadExpectation bridge.yaml
     * @loadFixture customers.yaml
     */
    public function testGetValues($customerAddressId)
    {
        /** @var Mage_Customer_Model_Address $customerAddress */
        $customerAddress = Mage::getModel('customer/address')->load($customerAddressId);
        /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_CustomerAddress $bridge */
        $bridge = Mage::getModel('integernet_anonymizer/bridge_entity_address_customerAddress');
        $expected = $this->expected('customer_address_%d', $customerAddressId);

        $this->_testGetValues($bridge, $customerAddress, $expected);
    }

    /**
     * @param $customerAddressId
     * @test
     * @dataProvider dataProvider
     * @dataProviderFile testCustomerAddressBridge.yaml
     * @loadFixture customers.yaml
     */
    public function testUpdateValues($customerAddressId)
    {
        static $changedMiddlename = 'trouble',
               $changedStreet = "New Street\nSecond Line";

        /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_Address_CustomerAddress $bridge */
        $bridge = Mage::getModel('integernet_anonymizer/bridge_entity_address_customerAddress');

        /** @var Mage_Customer_Model_Address $customerAddress */
        $dataProvider = Mage::getModel('customer/address')->load($customerAddressId);
        $bridge->setRawData($dataProvider->load($customerAddressId)->getData());
        $bridge->getValues()['middlename']->setValue($changedMiddlename);
        $bridge->getValues()['street']->setValue($changedStreet);

        $this->_updateValues($bridge);

        $customerAddress = Mage::getModel('customer/address')->load($customerAddressId);
        $this->assertEquals($changedMiddlename, $customerAddress->getMiddlename());
        $this->assertEquals($changedStreet, $customerAddress->getStreetFull());
    }


}