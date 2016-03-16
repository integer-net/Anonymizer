<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
abstract class IntegerNet_Anonymizer_Model_Bridge_Entity_Address_Abstract
    extends IntegerNet_Anonymizer_Model_Bridge_Entity_Abstract
{
    protected $_formattersByAttribute = array(
        'firstname' => 'firstName',
        'lastname' => 'lastName',
        'middlename' => 'firstName',
        'prefix' => 'title',
        'suffix' => 'suffix',
        'company' => 'company',
        'city' => 'city',
        'street' => 'streetAddress',
        'telephone' => 'phoneNumber',
        'fax' => 'phoneNumber',
        'vat_id' => 'randomNumber' // 'vat' provider is not implemented for most counries
    );

    /**
     * Sets identifier based on current entity
     *
     * @return void
     */
    protected function _setIdentifier()
    {
        $customerId = $this->_entity->getCustomerId();
        if ($customerId) {
            $this->_identifier = $customerId;
            return;
        }
        $entityId = $this->_entity->getId();
        if ($entityId) {
            $this->_identifier = $this->getEntityName() . $entityId;
            return;
        }
        $this->_identifier = null;
    }

}