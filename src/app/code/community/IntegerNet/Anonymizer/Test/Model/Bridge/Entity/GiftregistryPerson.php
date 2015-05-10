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
class IntegerNet_Anonymizer_Test_Model_Bridge_Entity_GiftregistryPerson
    extends IntegerNet_Anonymizer_Test_Model_Bridge_Entity_Abstract
{
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
     * @param $personId
     * @test
     * @depends isEnterprise
     * @dataProvider dataProvider
     * @dataProviderFile testGiftregistryPersonBridge.yaml
     * @loadExpectation bridge.yaml
     * @loadFixture customers.yaml
     * @loadFixture enterprise.yaml
     */
    public function testGetValues($personId)
    {
        /** @var Enterprise_GiftRegistry_Model_Person $person */
        $person = Mage::getModel('enterprise_giftregistry/person')->load($personId);
        /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_Enterprise_GiftregistryPerson $bridge */
        $bridge = Mage::getModel('integernet_anonymizer/bridge_entity_enterprise_giftregistryPerson');
        $expected = $this->expected('giftregistry_person_%d', $personId);

        $this->_testGetValues($bridge, $person, $expected);
    }

    /**
     * @param $registryId
     * @test
     * @depends isEnterprise
     * @dataProvider dataProvider
     * @dataProviderFile testGiftregistryPersonBridge.yaml
     * @loadFixture customers.yaml
     * @loadFixture enterprise.yaml
     */
    public function testUpdateValues($registryId)
    {
        static $changedEmail = 'changed@email.com';

        /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_Enterprise_GiftregistryPerson $bridge */
        $bridge = Mage::getModel('integernet_anonymizer/bridge_entity_enterprise_giftregistryPerson');

        $dataProvider = Mage::getModel('enterprise_giftregistry/person');;
        $bridge->setRawData($dataProvider->load($registryId)->setId($registryId)->getData());
        $bridge->getValues()['email']->setValue($changedEmail);

        $this->_updateValues($bridge);

        $person = Mage::getModel('enterprise_giftregistry/person')->load($registryId);
        $this->assertEquals($changedEmail, $person->getEmail());
    }
}