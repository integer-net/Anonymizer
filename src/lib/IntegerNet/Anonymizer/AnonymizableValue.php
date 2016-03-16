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


class AnonymizableValue
{
    const __CLASS = __CLASS__;
    /**
     * @var string
     */
    private $formatter;
    /**
     * @var string
     */
    private $value;
    /**
     * @var bool
     */
    private $unique;

    public function __construct($formatter, $value, $unique = false)
    {
        $this->formatter = $formatter;
        $this->value = $value;
        $this->unique = (bool) $unique;
    }
    /**
     * @return string
     */
    public function getFakerFormatter()
    {
        return $this->formatter;
    }

    /**
     * If the value is optional, returns probability of presence.
     *   1.0 = not optional
     *   0.0 = always empty
     *
     * @return float
     */
    public function getOptionalWeight()
    {
        //TODO use this
        //TODO or add a FakerFormatterOptions property for more customizability
        return 1.0;
    }

    public function isUnique()
    {
        return $this->unique;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}