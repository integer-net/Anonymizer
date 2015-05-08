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
class IntegerNet_Anonymizer_Test_Model_Bridge_Entity_NewsletterSubscriber
    extends IntegerNet_Anonymizer_Test_Model_Bridge_Entity_Abstract
{
    /**
     * @param $subscriberId
     * @test
     * @dataProvider dataProvider
     * @dataProviderFile testNewsletterSubscriberBridge.yaml
     * @loadExpectation bridge.yaml
     * @loadFixture customers.yaml
     */
    public function testGetValues($subscriberId)
    {
        /** @var Mage_Newsletter_Model_Subscriber $subscriber */
        $subscriber = Mage::getModel('newsletter/subscriber')->load($subscriberId);
        /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_NewsletterSubscriber $bridge */
        $bridge = Mage::getModel('integernet_anonymizer/bridge_entity_newsletterSubscriber');
        $expected = $this->expected('newsletter_subscriber_%d', $subscriberId);

        $this->_testGetValues($bridge, $subscriber, $expected);
    }

    /**
     * @param $subscriberId
     * @test
     * @dataProvider dataProvider
     * @dataProviderFile testNewsletterSubscriberBridge.yaml
     * @loadFixture customers.yaml
     */
    public function testUpdateValues($subscriberId)
    {
        static $changedEmail = 'changed@example.com';

        /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_NewsletterSubscriber $bridge */
        $bridge = Mage::getModel('integernet_anonymizer/bridge_entity_newsletterSubscriber');

        $dataProvider = Mage::getModel('newsletter/subscriber');
        $bridge->setRawData($dataProvider->load($subscriberId)->getData());
        $bridge->getValues()['subscriber_email']->setValue($changedEmail);

        $this->_updateValues($bridge);

        $subscriber = Mage::getModel('newsletter/subscriber')->load($subscriberId);
        $this->assertEquals($changedEmail, $subscriber->getSubscriberEmail());
    }
}