<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
class IntegerNet_Anonymizer_Model_Bridge_Iterator implements \IntegerNet\Anonymizer\Implementor\CollectionIterator
{
    /**
     * @var Mage_Eav_Model_Entity_Collection_Abstract|Mage_Core_Model_Resource_Db_Collection_Abstract
     */
    protected $_collection;
    /**
     * @var Mage_Core_Model_Resource_Iterator
     */
    protected $_iterator;
    /**
     * @var string[]
     */
    protected $_data;
    /**
     * @var int Iteration counter
     */
    protected $_iteration;
    /**
     * @var int Iteration counter offset for chunking
     */
    protected $_iterationOffset = 0;

    public function __construct(Varien_Data_Collection_Db $collection)
    {
        $this->_collection = $collection;
        $this->_iterator = Mage::getResourceModel('core/iterator');
    }

    /**
     * @return Mage_Core_Model_Resource_Db_Collection_Abstract|Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getCollection()
    {
        return $this->_collection;
    }

    /**
     * @param callable $callable
     * @return mixed
     */
    function walk(/*callable*/ $callable) // PHP 5.3
    {
        $self = $this; // PHP 5.3
        $this->_iterator->walk($this->_collection->getSelect(), array(
            function($args) use ($callable, $self) {
                $self->_setIteration($args['idx']);
                $self->_setRawData($args['row']);
                $callable($self);
            }
        ));
        $this->_afterWalk();
    }

    public function _setRawData($data)
    {
        $this->_data = $data;
    }
    public function _setIteration($iteration)
    {
        $this->_iteration = $iteration;
    }
    public function setIterationOffset($offset)
    {
        $this->_iterationOffset = $offset;
    }

    /**
     * Returns raw data from database
     *
     * @return mixed
     */
    function getRawData()
    {
        return $this->_data;
    }

    /**
     * Returns number of iteration
     *
     * @return int
     */
    function getIteration()
    {
        return $this->_iterationOffset + $this->_iteration;
    }

    /**
     * Returns total size of collection
     *
     * @return mixed
     */
    function getSize()
    {
        return $this->_collection->getSize();
    }

    /**
     * Additional processing at the end:
     *  - update grid tables
     *
     * @return void
     */
    protected function _afterWalk()
    {
        $entityResource = $this->_collection->getResource();
        if ($entityResource instanceof Mage_Sales_Model_Resource_Order_Abstract) {
            $entityResource->updateGridRecords($this->_collection->getAllIds());
        }
    }

}