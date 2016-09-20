<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
class IntegerNet_Anonymizer_Model_Bridge_Entity_NewsletterSubscriber
    extends IntegerNet_Anonymizer_Model_Bridge_Entity_Abstract
{

    protected $_attributesUsedForIdentifier = array(
        'customer_id'
    );
    protected $_formattersByAttribute = array(
        'subscriber_email' => 'safeEmail',
    );

    function __construct()
    {
        parent::__construct();
        $this->_entity = Mage::getModel('newsletter/subscriber');
    }

    function isAnonymizable()
    {
        return ! $this->excludedEmailDomains->matches($this->_entity->getData('subscriber_email'));
    }

    /**
     * Sets identifier based on current entity
     *
     * @return void
     */
    protected function _setIdentifier()
    {
        $this->_identifier = $this->_entity->getCustomerId() ?: $this->_entity->getEmail();
    }

    /**
     * Returns name of entity as translatable string
     *
     * @return string
     */
    function getEntityName()
    {
        return 'Newsletter Subscriber';
    }


}