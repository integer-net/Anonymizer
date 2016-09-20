<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2016 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
namespace IntegerNet\Anonymizer\Config;

/**
 * Can be used in entity bridge classes to exclude certain email addresses from anonymization
 */
class ExcludedEmailDomains
{
    /**
     * @var string[]
     */
    private $domains;
    public function __construct(array $domains)
    {
        $this->domains = $domains;
    }

    public function matches($email)
    {
        $emailDomain = preg_replace('{.*@(.*)}', '$1', $email);
        foreach ($this->domains as $domain) {
            if ($emailDomain === $domain) {
                return true;
            }
        }
        return false;
    }
}