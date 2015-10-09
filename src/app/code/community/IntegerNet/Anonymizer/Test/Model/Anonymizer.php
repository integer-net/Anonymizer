<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */

class IntegerNet_Anonymizer_Test_Model_Anonymizer extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @test
     * @loadFixture customers.yaml
     */
    public function testAnonymizeAllCommunity()
    {
        $this->_testAnonymizeAll();
    }

    /**
     * @test
     */
    public function isEnterprise()
    {
        if (Mage::getEdition() !== Mage::EDITION_ENTERPRISE) {
            $this->markTestSkipped('Skipping test for Magento Enterprise');
        }
    }
    /**
     * @test
     * @depends isEnterprise
     * @loadFixture customers.yaml
     * @loadFixture enterprise.yaml
     */
    public function testAnonymizeAllEnterprise()
    {
        $this->_testAnonymizeAll();
        //TODO EE assertions
        $this->markTestIncomplete();
    }

    /**
     *
     */
    protected function _testAnonymizeAll()
    {
        // TEST PRE CONDITIONS

        /** @var Mage_Sales_Model_Resource_Order_Grid_Collection $orderGridCollection */
        $orderGridCollection = Mage::getResourceModel('sales/order_grid_collection');
        $orderGridData = $orderGridCollection->addFieldToFilter('entity_id', 1)->getFirstItem();
        $this->assertEquals('Testname Testname', $orderGridData->getShippingName());
        $this->assertEquals('Testname Testname', $orderGridData->getBillingName());

        // RUN ANONYMIZER

        /** @var IntegerNet_Anonymizer_Model_Anonymizer $anonymizer */
        $anonymizer = Mage::getModel('integernet_anonymizer/anonymizer');
        $anonymizer->anonymizeAll();

        // TEST POST CONDITIONS

        $customer = Mage::getModel('customer/customer')->load(2);
        $this->assertNotEquals('test2@test.de', $customer->getData('email'));
        $this->assertNotEquals('Testname', $customer->getData('firstname'));
        $this->assertNotEquals('Testname', $customer->getData('lastname'));

        $customerAddress = $customer->getDefaultBillingAddress();
        $this->assertNotEquals('Testname', $customerAddress->getData('firstname'));
        $this->assertNotEquals('Testname', $customerAddress->getData('lastname'));
        $this->assertNotEquals('ACME GmbH', $customerAddress->getData('company'));
        $this->assertNotEquals('Buxtehude', $customerAddress->getData('city'));
        $this->assertNotEquals('Am Arm 1', $customerAddress->getData('street'));
        $this->assertNotEquals('555-12345', $customerAddress->getData('telephone'));
        $this->assertNotEquals('555-12345', $customerAddress->getData('fax'));
        $this->assertNotEquals('DE 987654321', $customerAddress->getData('vat_id'));
        $this->assertEquals('67890', $customerAddress->getData('postcode'));
        $this->assertEquals('DE', $customerAddress->getCountryId());

        $quote = Mage::getModel('sales/quote')->load(1);
        $quoteAddress = $quote->getBillingAddress();
        $this->assertEquals($customerAddress->getCustomerId(), $quoteAddress->getCustomerId(),
            'Quote address and customer address refer to the same customer');
        $this->assertNotEquals($customerAddress->getData('lastname'), $quoteAddress->getData('lastname'),
            'Different values stay different');
        $this->assertNotEquals($customerAddress->getData('lastname'), $quoteAddress->getData('lastname'),
            'Different values stay different');
        $this->assertEquals($customerAddress->getData('company'), $quoteAddress->getData('company'),
            'Same values stay the same');

        $this->assertNotEquals('Somebody', $quoteAddress->getData('firstname'));
        $this->assertNotEquals('Else', $quoteAddress->getData('lastname'));
        $this->assertNotEquals('ACME GmbH', $quoteAddress->getData('company'));
        $this->assertNotEquals('Buxtehude', $quoteAddress->getData('city'));
        $this->assertNotEquals('Am Arm 1', $quoteAddress->getData('street'));
        $this->assertNotEquals('555-12345', $quoteAddress->getData('telephone'));
        $this->assertNotEquals('555-12345', $quoteAddress->getData('fax'));
        $this->assertNotEquals('DE 987654321', $quoteAddress->getData('vat_id'));
        $this->assertEquals('67890', $quoteAddress->getData('postcode'));
        $this->assertEquals('DE', $quoteAddress->getCountryId());

        /** @var Mage_Sales_Model_Order $order */
        $order = Mage::getModel('sales/order')->load(1);
        $this->assertNotEquals('test2@test.de', $order->getCustomerEmail());
        $this->assertNotEquals('Testname', $order->getCustomerFirstname());
        $this->assertNotEquals('J', $order->getCustomerMiddlename());
        $this->assertNotEquals('Testname', $order->getCustomerLastname());
        $this->assertNotEquals('Kaiser', $order->getCustomerPrefix());
        $this->assertNotEquals('der Große', $order->getCustomerSuffix());

        $orderAddress = $order->getBillingAddress();
        $this->assertEquals($quoteAddress->getCustomerId(), $orderAddress->getCustomerId(),
            'Quote address and customer address refer to the same customer');
        $this->assertNotEquals($quoteAddress->getData('lastname'), $orderAddress->getData('lastname'),
            'Different values stay different');
        $this->assertNotEquals($quoteAddress->getData('lastname'), $orderAddress->getData('lastname'),
            'Different values stay different');
        $this->assertEquals($quoteAddress->getData('company'), $orderAddress->getData('company'),
            'Same values stay the same');

        $this->assertNotEquals('Testname', $orderAddress->getData('firstname'));
        $this->assertNotEquals('Testname', $orderAddress->getData('lastname'));
        $this->assertNotEquals('ACME GmbH', $orderAddress->getData('company'));
        $this->assertNotEquals('Buxtehude', $orderAddress->getData('city'));
        $this->assertNotEquals('Am Arm 1', $orderAddress->getData('street'));
        $this->assertNotEquals('555-12345', $orderAddress->getData('telephone'));
        $this->assertNotEquals('555-12345', $orderAddress->getData('fax'));
        $this->assertNotEquals('DE 987654321', $orderAddress->getData('vat_id'));
        $this->assertEquals('67890', $orderAddress->getData('postcode'));
        $this->assertEquals('DE', $orderAddress->getCountryId());

        /** @var Mage_Sales_Model_Resource_Order_Grid_Collection $orderGridCollection */
        $orderGridCollection = Mage::getResourceModel('sales/order_grid_collection');
        $orderGridData = $orderGridCollection->addFieldToFilter('entity_id', 1)->getFirstItem();
        $this->assertNotEquals('Testname Testname', $orderGridData->getShippingName());
        $this->assertNotEquals('Testname Testname', $orderGridData->getBillingName());
        $this->assertEquals($orderAddress->getName(), $orderGridData->getBillingName());

        $subscriber = Mage::getModel('newsletter/subscriber')->load(1);
        $this->assertNotEquals('guest1@example.com', $subscriber->getSubscriberEmail());


        $guestQuote = Mage::getModel('sales/quote')->load(2);
        $guestQuoteAddress = $guestQuote->getBillingAddress();
        $otherGuestQuote = Mage::getModel('sales/quote')->load(3);
        $otherGuestQuoteAddress = $otherGuestQuote->getBillingAddress();

        $this->assertNotEquals($guestQuoteAddress->getData('firstname'), $otherGuestQuoteAddress->getData('firstname'));
        $this->assertNotEquals($guestQuoteAddress->getData('lastname'), $otherGuestQuoteAddress->getData('lastname'));
        $this->assertNotEquals($guestQuoteAddress->getData('company'), $otherGuestQuoteAddress->getData('company'));
        $this->assertNotEquals($guestQuoteAddress->getData('city'), $otherGuestQuoteAddress->getData('city'));
        $this->assertNotEquals($guestQuoteAddress->getData('street'), $otherGuestQuoteAddress->getData('street'));
        $this->assertNotEquals($guestQuoteAddress->getData('telephone'), $otherGuestQuoteAddress->getData('telephone'));
        $this->assertNotEquals($guestQuoteAddress->getData('fax'), $otherGuestQuoteAddress->getData('fax'));
        $this->assertNotEquals($guestQuoteAddress->getData('vat_id'), $otherGuestQuoteAddress->getData('vat_id'));

    }
}