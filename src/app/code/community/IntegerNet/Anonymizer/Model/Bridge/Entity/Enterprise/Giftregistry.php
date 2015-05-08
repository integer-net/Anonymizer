<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
class IntegerNet_Anonymizer_Model_Bridge_Entity_Enterprise_Giftregistry
    extends IntegerNet_Anonymizer_Model_Bridge_Entity_Abstract
{

    protected $_formattersByAttribute = array(
        'title'            => 'sentence',
        'message'          => 'paragraph',
        'shipping_address' => 'address',
        'custom_values'    => 'null',
    );


    function __construct()
    {
        $this->_entity = Mage::getModel('enterprise_giftregistry/entity');
    }

    /**
     * Sets identifier based on current entity
     *
     * @return void
     */
    protected function _setIdentifier()
    {
        $entityId = $this->_entity->getId();
        if ($entityId) {
            $this->_identifier = '_gift_registry_' . $entityId;
        } else {
            $this->_identifier = '';
        }
    }

    /**
     * Returns name of entity as translatable string
     *
     * @return string
     */
    function getEntityName()
    {
        return 'Gift Registry';
    }


}