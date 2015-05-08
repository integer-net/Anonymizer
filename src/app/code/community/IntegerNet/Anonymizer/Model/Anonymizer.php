<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */

class IntegerNet_Anonymizer_Model_Anonymizer
{
    /**
     * @var \IntegerNet\Anonymizer\Updater
     */
    protected $_updater;

    public function __construct()
    {
        $anonymizer = new \IntegerNet\Anonymizer\Anonymizer();
        $this->_updater = new \IntegerNet\Anonymizer\Updater($anonymizer);
    }
    protected function _getEntityModels()
    {
        return array(
            'integernet_anonymizer/bridge_entity_customer',
            'integernet_anonymizer/bridge_entity_address_customerAddress',
            'integernet_anonymizer/bridge_entity_address_quoteAddress',
            'integernet_anonymizer/bridge_entity_address_orderAddress',
            'integernet_anonymizer/bridge_entity_newsletterSubscriber',
            'integernet_anonymizer/bridge_entity_enterprise_giftregistry',
            'integernet_anonymizer/bridge_entity_enterprise_giftregistryPerson',
        );
    }

    public function setOutputStream($stream)
    {
        $this->_updater->setOutputStream($stream);
    }

    public function anonymizeAll()
    {
        foreach ($this->_getEntityModels() as $entityModelAlias) {
            /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_Abstract $entityModel */
            $entityModel = Mage::getModel($entityModelAlias);
            while ($collectionIterator = $entityModel->getCollectionIterator()) {
                $this->_updater->update($collectionIterator, $entityModel);
            }
        }
    }
    public function anonymizeStore()
    {
        //TODO different locales per store
    }
    protected function _clearPaymentData()
    {
        //TODO UPDATE sales_flat_order_payment SET additional_data=NULL, additional_information=NULL
    }
}