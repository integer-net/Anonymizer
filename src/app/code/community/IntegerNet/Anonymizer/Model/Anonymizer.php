<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */

class IntegerNet_Anonymizer_Model_Anonymizer
{
    /**
     * @var \IntegerNet\Anonymizer\Updater
     */
    protected $_updater;

    public function __construct()
    {
        $anonymizer = new \IntegerNet\Anonymizer\Anonymizer();
        $this->_updater = new \IntegerNet\Anonymizer\Updater($anonymizer);
    }
    protected function _getEntityModels()
    {
        $entityModelsConfigXml = Mage::getConfig()->getNode('global/integernet_anonymizer/entity_models');

        $entityModelsConfigArray = $entityModelsConfigXml->asArray();
        $entityModelsConfigArray = $this->_sortEntityModelsConfig($entityModelsConfigArray);

        $entityModels = [];

        foreach ($entityModelsConfigArray as $entityModelsConfig) {
            $entityModel = Mage::getModel($entityModelsConfig['class']);
            if ($entityModel instanceof IntegerNet_Anonymizer_Model_Bridge_Entity_Abstract
                && $entityModel->entityExists()) {
                $entityModels[] = $entityModelsConfig['class'];
            }
        }

        return $entityModels;
    }
    protected function _sortEntityModelsConfig($entityModelsConfig)
    {
        usort(
            $entityModelsConfig,
            function ($entityModel1, $entityModel2) {
                return strcmp($entityModel1['sort'], $entityModel2['sort']);
            }
        );
        return $entityModelsConfig;
    }
    /**
     * @param resource $stream stream resource used for output (for example opened file pointer or STDOUT)
     */
    public function setOutputStream($stream)
    {
        $this->_updater->setOutputStream($stream);
    }
    /**
     * @param boolean $showProgress True if progress should be output (default is true)
     */
    public function setShowProgress($showProgress)
    {
        $this->_updater->setShowProgress($showProgress);
    }
    /**
     * @param $steps How often progress output should be refreshed (default is 1 = after every entity update; example: 10 = every 10 entity updates)
     */
    public function setProgressSteps($steps)
    {
        $this->_updater->setProgressSteps($steps);
    }

    public function anonymizeAll()
    {
        /** @var Varien_Db_Adapter_Interface $connection */
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $connection->beginTransaction();
        try {
            foreach ($this->_getEntityModels() as $entityModelAlias) {
                /** @var IntegerNet_Anonymizer_Model_Bridge_Entity_Abstract $entityModel */
                $entityModel = Mage::getModel($entityModelAlias);
                while ($collectionIterator = $entityModel->getCollectionIterator()) {
                    $this->_updater->update($collectionIterator, $entityModel);
                }
            }
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            throw $e;
        }
    }
    public function anonymizeStore()
    {
        //TODO different locales per store
    }
    protected function _clearPaymentData()
    {
        //TODO UPDATE sales_flat_order_payment SET additional_data=NULL, additional_information=NULL
    }
}