<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2016 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */ 
class IntegerNet_Anonymizer_Helper_Data extends Mage_Core_Helper_Abstract
{
    const ALIAS = 'integernet_anonymizer';
    const CONFIG_EXCLUDED_EMAIL_DOMAINS = 'dev/integernet_anonymizer/excluded_email_domains';

    public function excludedEmailDomains()
    {
        return new \IntegerNet\Anonymizer\Config\ExcludedEmailDomains(
            array_map(
                'trim',
                explode(',', Mage::getStoreConfig(self::CONFIG_EXCLUDED_EMAIL_DOMAINS)) ?: []
            )
        );
    }
}