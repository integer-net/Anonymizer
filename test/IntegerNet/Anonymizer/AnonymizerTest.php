<?php
/**
 * Created by PhpStorm.
 * User: fs
 * Date: 16.03.2015
 * Time: 18:33
 */

namespace IntegerNet\Anonymizer;

use IntegerNet\Anonymizer\Mock\AnonymizableMock;

class AnonymizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param AnonymizableMock[] $inputData
     * @param string[] $expectedValues
     * @dataProvider getAnonymizableData
     */
    public function testAnonymizer(array $inputData, $expectedValues)
    {
        $provider = $this->getMock(Provider::__CLASS);
        $provider->expects($this->once())->method('initialize');
        $provider->expects($this->exactly(count($expectedValues)))->method('getFakerData')
            ->willReturnCallback(function($formatter, $identifier) {
                return sprintf('%s_%s', $formatter, explode('|', $identifier, 2)[0]);
            });

        $anonymizer = new Anonymizer($provider, 'de_DE');
        $anonymizer->anonymize($inputData);

        reset($expectedValues);
        /** @var AnonymizableMock $anonymizedEntity */
        foreach ($inputData as $anonymizedEntity) {
            foreach ($anonymizedEntity->getValues() as $anonymizedValue) {
                $this->assertEquals(current($expectedValues), $anonymizedValue->getValue());
                next($expectedValues);
            }
        }
    }

    /**
     * Provider::getFakerData() should be called with
     * - identifier as combination of entity identifier and current value
     * - unique key parameter for values marked as unique
     *
     * @test
     */
    public function testGetFakerDataParameters()
    {
        $provider = $this->getMock(Provider::__CLASS);
        $provider->expects($this->once())->method('initialize');
        $provider->expects($this->exactly(2))->method('getFakerData')
            ->withConsecutive(
                array('email', 'email@example.com|email@example.com', true),
                array('name',  'email@example.com|Mr. Email',         false));

        $anonymizer = new Anonymizer($provider, 'de_DE');
        $anonymizer->anonymize([new AnonymizableMock(['email' => 'email@example.com', 'name' => 'Mr. Email'], 'email')]);

    }

    public static function getAnonymizableData()
    {
        return array(
            array(
                'inputData' => array(
                    new AnonymizableMock(['email' => 'max.mustermann@example.com',  'firstName' => 'Max',  'lastName' => 'Mustermann']),
                    new AnonymizableMock(['email' => 'max.mustermann2@example.com', 'firstName' => 'Max',  'lastName' => 'Mustermann']),
                    new AnonymizableMock(['email' => 'maxi.musterfrau@example.com', 'firstName' => 'Maxi', 'lastName' => 'Musterfrau']),
                    new AnonymizableMock(['email' => 'max.mustermann@example.com',  'firstName' => 'Max',  'lastName' => 'Mustermann', 'streetAddress' => 'Musterstraße 42']),
                ),
                'expectedValues' => array(
                    'email_max.mustermann@example.com',  'firstName_max.mustermann@example.com',  'lastName_max.mustermann@example.com',
                    'email_max.mustermann2@example.com', 'firstName_max.mustermann2@example.com', 'lastName_max.mustermann2@example.com',
                    'email_maxi.musterfrau@example.com', 'firstName_maxi.musterfrau@example.com', 'lastName_maxi.musterfrau@example.com',
                    'email_max.mustermann@example.com',  'firstName_max.mustermann@example.com',  'lastName_max.mustermann@example.com', 'streetAddress_max.mustermann@example.com'
                )
            )
        );
    }
}
