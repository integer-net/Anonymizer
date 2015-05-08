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


interface CollectionIterator
{

    /**
     * @param callable $callable
     * @return mixed
     */
    function walk(/*callable*/ $callable);

    /**
     * Returns raw data from database
     *
     * @return mixed
     */
    function getRawData();

    /**
     * Returns number of iteration
     *
     * @return int
     */
    function getIteration();

    /**
     * Returns total size of collection
     *
     * @return mixed
     */
    function getSize();
}