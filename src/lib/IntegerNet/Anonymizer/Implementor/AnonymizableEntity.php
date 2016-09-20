<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */

namespace IntegerNet\Anonymizer\Implementor;


use IntegerNet\Anonymizer\AnonymizableValue;

interface AnonymizableEntity
{
    /**
     * Returns name of entity as translatable string
     *
     * @return string
     */
    function getEntityName();

    /**
     * Returns true if entity is anonymizable, false if it should be left unchanged
     *
     * @return bool
     */
    function isAnonymizable();
    /**
     * Returns identifier, for example the customer email address. Entities with the same identifier will get the same
     * anonymized values.
     *
     * Important: The return value must not be affected by anonymization!
     *
     * @return string
     */
    function getIdentifier();

    /**
     * Returns anonymizable value objects as array with attribute codes as keys
     *
     * @return AnonymizableValue[]
     */
    function getValues();

    /**
     * Sets raw data from database
     *
     * @param string[] $data
     * @return void
     */
    function setRawData($data);

    /**
     * Update values in database
     *
     * @return void
     */
    function updateValues();

    /**
     * Reset to empty instance
     *
     * @return void
     */
    function clearInstance();

}