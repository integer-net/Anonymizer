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
class IntegerNet_Anonymizer_Test_Model_Bridge_Entity_Giftregistry
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
     * @param $registryId
     * @test
     * @depends isEnterprise
     * @dataProvider dataProvider
     * @dataProviderFile testGiftregistryBridge.yaml
     * @loadExpectation bridge.yaml
     * @loadFixture customers.yaml
     * @loadFixture enterprise.yaml
     */
    public function testGetValues($registryId)
    {
        /** @var Enterprise_GiftRegistry_Model_Entity $registry */
        $registry = Mage::getModel('enterprise_giftregistry/entity')->load($registryId)->setId($registryId);
        /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_Enterprise_Giftregistry $bridge */
        $bridge = Mage::getModel('integernet_anonymizer/bridge_entity_enterprise_giftregistry');
        $expected = $this->expected('giftregistry_%d', $registryId);

        $this->_testGetValues($bridge, $registry, $expected);
    }

    /**
     * @param $registryId
     * @test
     * @dataProvider dataProvider
     * @dataProviderFile testGiftregistryBridge.yaml
     * @loadFixture customers.yaml
     * @loadFixture enterprise.yaml
     */
    public function testUpdateValues($registryId)
    {
        static $changedTitle = 'Changed Gift Registry Title';

        /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_Enterprise_Giftregistry $bridge */
        $bridge = Mage::getModel('integernet_anonymizer/bridge_entity_enterprise_giftregistry');

        $dataProvider = Mage::getModel('enterprise_giftregistry/entity');
        $bridge->setRawData($dataProvider->load($registryId)->setId($registryId)->getData());
        $bridge->getValues()['title']->setValue($changedTitle);

        $this->_updateValues($bridge);

        $registry = Mage::getModel('enterprise_giftregistry/entity')->load($registryId);
        $this->assertEquals($changedTitle, $registry->getTitle());
    }
}