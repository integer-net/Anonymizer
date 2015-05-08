<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
class IntegerNet_Anonymizer_Model_Bridge_Entity_Address_QuoteAddress
    extends IntegerNet_Anonymizer_Model_Bridge_Entity_Address_Abstract
{
    function __construct()
    {
        $this->_entity = Mage::getModel('sales/quote_address');
    }

    /**
     * Returns name of entity as translatable string
     *
     * @return string
     */
    function getEntityName()
    {
        return 'Quote Address';
    }


}