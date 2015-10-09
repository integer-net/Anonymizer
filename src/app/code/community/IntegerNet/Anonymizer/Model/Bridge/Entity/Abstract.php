<?php
use IntegerNet\Anonymizer\AnonymizableValue;

/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */
abstract class IntegerNet_Anonymizer_Model_Bridge_Entity_Abstract
    implements IntegerNet\Anonymizer\Implementor\AnonymizableEntity
{
    const ROWS_PER_QUERY = 50000;
    /**
     * @var string
     */
    protected $_identifier;
    /**
     * @var Mage_Core_Model_Abstract
     */
    protected $_entity;
    /**
     * @var string|null is overridden with entity type code if the model is a EAV entity
     */
    protected $_entityType = null;
    /**
     * @var string[]
     */
    protected $_formattersByAttribute = array();
    /**
     * @var string[]
     */
    protected $_attributesUsedForIdentifier = array();
    /**
     * @var string[]
     */
    protected $_uniqueAttributes = array();
    /**
     * @var AnonymizableValue[]
     */
    protected $_values;
    /**
     * @var int Current page of collection for chunking
     */
    protected $currentPage = 0;

    /**
     * Returns identifier, for example the customer email address. Entities with the same identifier will get the same
     * anonymized values.
     *
     * Important: The return value must not be affected by anonymization!
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }

    /**
     * Sets identifier based on current entity
     *
     * @return void
     */
    abstract protected function _setIdentifier();

    /**
     * @return AnonymizableValue[]
     */
    public function getValues()
    {
        if ($this->_values === null) {
            $this->_values = array();
            foreach ($this->_formattersByAttribute as $attribute => $formatter) {
                $this->_values[$attribute] = new AnonymizableValue(
                    $formatter, $this->_entity->getDataUsingMethod($attribute),
                    in_array($attribute, $this->_uniqueAttributes));
            }
        }
        return $this->_values;
    }

    /**
     * Sets raw data from database
     *
     * @param string[] $data
     * @return void
     */
    public function setRawData($data)
    {
        $this->_entity->addData($data);
        // reset derived attributes:
        $this->_setIdentifier();
        $this->_values = null;
    }

    /**
     * Update values in database
     *
     * @return void
     */
    public function updateValues()
    {
        $resource = $this->_entity->getResource();
        $staticAttributes = array();
        foreach ($this->getValues() as $attributeCode => $value) {
            if (!$resource instanceof Mage_Eav_Model_Entity_Abstract) {
                $staticAttributes[$attributeCode] = $value->getValue();
                continue;
            }
            $attribute = Mage::getSingleton('eav/config')->getAttribute($this->_entityType, $attributeCode);
            if ($attribute->isStatic()) {
                $staticAttributes[$attributeCode] = $value->getValue();
                continue;
            }
            $this->_entity->setData($attributeCode, $value->getValue());
            $resource->saveAttribute($this->_entity, $attributeCode);
        }
        if (!empty($staticAttributes)) {
            $this->_entity->addData($staticAttributes);
            if ($resource instanceof Mage_Eav_Model_Entity_Abstract) {
                $resource->isPartialSave(true);
            }
            try {
                $resource->save($this->_entity);
            } catch (Mage_Customer_Exception $e) {
                // 'This customer email already exists'
                //TODO instead exclude all customers with @example.* email addresses from the customer collection
                //     AND from associated collections as well
            }
        }
    }

    /**
     * Reset to empty instance
     *
     * @return void
     */
    public function clearInstance()
    {
        $this->_entity->setData(array());
        $this->_entity->clearInstance();
        // reset derived attributes
        $this->setRawData(array());
    }

    /**
     * @return IntegerNet_Anonymizer_Model_Bridge_Iterator|null
     */
    public function getCollectionIterator()
    {
        /** @var Varien_Data_Collection_Db $collection */
        $collection = $this->_entity->getCollection();
        //TODO add columns used by identifier to select
        $fields = array_unique(array_merge(array_keys($this->_formattersByAttribute), $this->_attributesUsedForIdentifier));
        if ($collection instanceof Mage_Eav_Model_Entity_Collection_Abstract) {
            $collection->addAttributeToSelect($fields, 'left');
        } elseif ($collection instanceof Mage_Core_Model_Resource_Db_Collection_Abstract) {
            $collection->addFieldToSelect($fields);
        }
        $iterationOffset = $this->currentPage * self::ROWS_PER_QUERY;
        if ($collection->getSize() <= $iterationOffset) {
            return null;
        }
        $this->currentPage++;
        $collection->getSelect()->limitPage($this->currentPage, self::ROWS_PER_QUERY);
        /** @var IntegerNet_Anonymizer_Model_Bridge_Iterator $iterator */
        $iterator = Mage::getModel('integernet_anonymizer/bridge_iterator', $collection);
        $iterator->setIterationOffset($iterationOffset);
        return $iterator;
    }

}