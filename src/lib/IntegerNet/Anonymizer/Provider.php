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


use Faker\Factory;

class Provider
{
    const __CLASS = __CLASS__;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    private $salt;

    /**
     * Initializes Faker, generates new salt for RNG seeds
     *
     * @param string|null $locale
     * @return void
     */
    public function initialize($locale = null)
    {
        if ($locale === null) {
            $locale = Factory::DEFAULT_LOCALE;
        }
        $this->faker = Factory::create($locale);
        $this->salt = sha1(uniqid('', true));
    }

    /**
     * Resets the UniqueGenerator of Faker, this should be used after anonymizing a database table with
     * unique values to free memory and allow the same values in other tables
     *
     * @return void
     */
    public function resetUniqueGenerator()
    {
        $this->faker->unique(true);
    }

    /**
     * Return fake data from given Faker provider, always return the same data for each ($formatter, $identifier)
     * combination after initialized.
     *
     * @param $formatter
     * @param $identifier
     * @return mixed
     */
    public function getFakerData($formatter, $identifier, $unique = false)
    {
        $faker = $this->faker;
        if ($formatter === 'null') {
            return $faker->optional(0)->randomDigit;
        }
        if ($unique) {
            $faker = $faker->unique();
        }
        $this->seedRng($identifier);
        $result = $faker->format($formatter);
        $this->resetRng();
        return $result;
    }

    /**
     * @param $identifier
     */
    private function seedRng($identifier)
    {
        $this->faker->seed(hexdec(hash("crc32b", $identifier . $this->salt)));
    }

    private function resetRng()
    {
        //$this->faker->seed();
        //TODO use above as soon as pr 543 has been merged
        mt_srand();
    }
}