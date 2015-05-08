<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */

namespace IntegerNet\Anonymizer\Mock;


use IntegerNet\Anonymizer\Implementor\CollectionIterator;

/**
 * Dumb implementation of CollectionIterator that iterates over an array of data
 *
 * @package IntegerNet\Anonymizer\Mock
 */
class CollectionMock extends \ArrayIterator implements CollectionIterator
{
    const __CLASS = __CLASS__;

    /**
     * @param callable $callable
     * @return mixed
     */
    function walk(/*callable*/ $callable)
    {
        foreach ($this as $row) {
            $callable($this);
        }
    }

    /**
     * Returns raw data from database
     *
     * @return mixed
     */
    function getRawData()
    {
        return $this->current();
    }

    /**
     * Returns number of iteration
     *
     * @return int
     */
    function getIteration()
    {
        return $this->key();
    }

    /**
     * Returns total size of collection
     *
     * @return mixed
     */
    function getSize()
    {
        return $this->count();
    }

}