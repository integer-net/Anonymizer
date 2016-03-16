<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */

namespace IntegerNet\Anonymizer\Mock;


use IntegerNet\Anonymizer\Implementor\AnonymizableEntity;
use IntegerNet\Anonymizer\AnonymizableValue;

/**
 * Dumb implementation of AnonymizableEntity that generates AnonymizableValue instances from test data
 *
 * @package IntegerNet\Anonymizer\Mock
 */
class AnonymizableMock implements AnonymizableEntity
{
    const __CLASS = __CLASS__;

    private $data = array();
    private $identifier = '';
    private $uniqueKey = null;

    /**
     * @param mixed[] $data data in the form [formatter => value], the first entry will be used as identifier
     * @param string|null $uniqueKey
     */
    function __construct($data = array(), $uniqueKey = null)
    {
        $this->uniqueKey = $uniqueKey;
        $this->setRawData($data);
    }

    /**
     * @return string
     */
    function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return AnonymizableValue[]
     */
    function getValues()
    {
        return $this->data;
    }

    /**
     * Sets raw data from database
     *
     * @param string[] $data
     * @return void
     */
    function setRawData($data)
    {
        foreach ($data as $key => $value) {
            $this->data[] = new AnonymizableValue($key, $value, $this->uniqueKey === $key);
        }
        if (!empty($this->data)) {
            $this->identifier = reset($this->data)->getValue();
        }
    }

    /**
     * Update values in database
     *
     * @return mixed
     */
    function updateValues()
    {
        // method stub. Should be mocked with PHPUnit to set expectations
    }

    /**
     * Reset to empty instance
     *
     * @return mixed
     */
    function clearInstance()
    {
        $this->data = array();
        $this->identifier = '';
    }

    /**
     * Returns name of entity as translatable string
     *
     * @return string
     */
    function getEntityName()
    {
        return 'Mock';
    }

}
