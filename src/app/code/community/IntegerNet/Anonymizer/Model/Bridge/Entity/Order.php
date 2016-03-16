<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
class IntegerNet_Anonymizer_Model_Bridge_Entity_Order extends IntegerNet_Anonymizer_Model_Bridge_Entity_Abstract
{
    protected $_entityType = 'order';

    protected $_attributesUsedForIdentifier = array(
        'customer_id', 'increment_id'
    );

    protected $_formattersByAttribute = array(
        'customer_email'      => 'safeEmail',
        'customer_firstname'  => 'firstName',
        'customer_lastname'   => 'lastName',
        'customer_middlename' => 'firstName',
        'customer_prefix'     => 'title',
        'customer_suffix'     => 'suffix',
        'customer_taxvat'     => 'randomNumber' // 'vat' provider is not implemented for most counries
    );
    protected $_uniqueAttributes = array(
        'customer_email'
    );

    function __construct()
    {
        $this->_entity = Mage::getModel('sales/order');
    }

    protected function _setIdentifier()
    {
        $this->_identifier = $this->_entity->getCustomerId();
    }

    /**
     * Returns name of entity as translatable string
     *
     * @return string
     */
    function getEntityName()
    {
        return 'Order';
    }

}