<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */ 
class IntegerNet_Anonymizer_Model_Bridge_Entity_Customer extends IntegerNet_Anonymizer_Model_Bridge_Entity_Abstract
{
    protected $_entityType = 'customer';

    protected $_formattersByAttribute = array(
        'email'      => 'safeEmail',
        'firstname'  => 'firstName',
        'lastname'   => 'lastName',
        'middlename' => 'firstName',
        'prefix'     => 'title',
        'suffix'     => 'suffix',
        'taxvat'     => 'randomNumber' // 'vat' provider is not implemented for most counries
    );
    protected $_uniqueAttributes = array(
        'email'
    );

    function __construct()
    {
        parent::__construct();
        $this->_entity = Mage::getModel('customer/customer');
    }

    function isAnonymizable()
    {
        return ! $this->excludedEmailDomains->matches($this->_entity->getData('email'));
    }

    protected function _setIdentifier()
    {
        $this->_identifier = $this->_entity->getId();
    }

    /**
     * Returns name of entity as translatable string
     *
     * @return string
     */
    function getEntityName()
    {
        return 'Customer';
    }


}