<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet
 * @copyright  Copyright (c) 2016 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */

namespace IntegerNet\Anonymizer\Config;


class ExcludedEmailDomainsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataIsExcluded
     */
    public function testIsExcluded($domains, $excluded, $notExcluded)
    {
        $excludedEmailDomains = new ExcludedEmailDomains($domains);
        foreach ($excluded as $ex) {
            $this->assertTrue($excludedEmailDomains->matches($ex));
        }
        foreach ($notExcluded as $notEx) {
            $this->assertFalse($excludedEmailDomains->matches($notEx));
        }
    }
    public static function dataIsExcluded()
    {
        return [
            [
                'domains' => ['project.tld', 'agency.tld', 'subdomain.domain.tld'],
                'excluded' => ['somebody@project.tld', 'adm1n.us3r@agency.tld', 'x@subdomain.domain.tld'],
                'not_excluded' => ['somebody@notproject.tld', 'x@domain.tld', 'adm1n.us3r@agency.tld.evil.org', 'sub@subdomain.project.tld'],
            ],
        ];
    }
}
