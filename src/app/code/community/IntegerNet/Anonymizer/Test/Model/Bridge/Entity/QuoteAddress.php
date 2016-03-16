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
class IntegerNet_Anonymizer_Test_Model_Bridge_Entity_QuoteAddress
    extends IntegerNet_Anonymizer_Test_Model_Bridge_Entity_Abstract
{
    /**
     * @param $quoteAddressId
     * @test
     * @dataProvider dataProvider
     * @dataProviderFile testQuoteAddressBridge.yaml
     * @loadExpectation bridge.yaml
     * @loadFixture customers.yaml
     */
    public function testGetValues($quoteAddressId)
    {
        /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_Address_QuoteAddress $bridge */
        $bridge = Mage::getModel('integernet_anonymizer/bridge_entity_address_quoteAddress');
        /** @var Mage_Sales_Model_Quote_Address $quoteAddress */
        $quoteAddress = $this->_loadEntityByCollection('address_id', $quoteAddressId, $bridge);
        $expected = $this->expected('quote_address_%d', $quoteAddressId);

        $this->_testGetValues($bridge, $quoteAddress, $expected);
    }

    /**
     * @param $quoteAddressId
     * @test
     * @dataProvider dataProvider
     * @dataProviderFile testQuoteAddressBridge.yaml
     * @loadFixture customers.yaml
     */
    public function testUpdateValues($quoteAddressId)
    {
        static $changedMiddlename = 'trouble',
               $changedStreet = "New Street\nSecond Line";

        /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_Address_QuoteAddress $bridge */
        $bridge = Mage::getModel('integernet_anonymizer/bridge_entity_address_quoteAddress');

        $dataProvider = Mage::getModel('sales/quote_address');
        $bridge->setRawData($dataProvider->load($quoteAddressId)->getData());
        $bridge->getValues()['middlename']->setValue($changedMiddlename);
        $bridge->getValues()['street']->setValue($changedStreet);

        $this->_updateValues($bridge);

        $quoteAddresss = Mage::getModel('sales/quote_address')->load($quoteAddressId);
        $this->assertEquals($changedMiddlename, $quoteAddresss->getMiddlename());
        $this->assertEquals($changedStreet, $quoteAddresss->getStreetFull());
    }

}