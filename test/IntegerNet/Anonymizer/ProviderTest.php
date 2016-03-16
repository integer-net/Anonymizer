<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */

namespace IntegerNet\Anonymizer;


class ProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testDeterministicValues()
    {
        $provider = new Provider();
        $provider->initialize('de_DE');
        $aName = $provider->getFakerData('name', 'a@example.com');
        $aRandomNumber = mt_rand();
        $aDifferentName = $provider->getFakerData('name', 'b@example.com');
        $aSameName = $provider->getFakerData('name', 'a@example.com');
        $this->assertNotEquals($aName, $aDifferentName, 'Name should be different for different identifier (email address)');
        $this->assertEquals($aName, $aSameName, 'Name should be equal for same identifier (email address)');
        $this->assertNotEquals($aRandomNumber, mt_rand(), 'The provider should not have side effects on mt_rand.');

        $provider->initialize('de_DE');
        $aNameAfterReinitialize = $provider->getFakerData('name', 'a@example.com');
        $this->assertNotEquals($aName, $aNameAfterReinitialize, 'Names should be different each anonymization process.');
    }
    public function testNullFormatter()
    {
        $provider = new Provider();
        $provider->initialize('de_DE');
        $nulledData = $provider->getFakerData('null', 'a@example.com');
        $this->assertEquals(null, $nulledData);
    }
    public function testUniqueData()
    {
        $provider = new Provider();
        $provider->initialize('de_DE');
        $randomDigits = array();
        for ($d = 0; $d < 10; ++$d) {
            $randomDigits[] = $provider->getFakerData('randomDigit', 'a@example.com', true);
        }
        sort($randomDigits);
        $this->assertEquals(range(0,9), $randomDigits, '10 unique digits should be all from 0..9');
    }
}
