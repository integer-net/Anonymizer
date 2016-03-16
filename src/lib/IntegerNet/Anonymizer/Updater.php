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

use IntegerNet\Anonymizer\Implementor\AnonymizableEntity;
use IntegerNet\Anonymizer\Implementor\CollectionIterator;

class Updater
{
    const __CLASS = __CLASS__;

    /**
     * @var Anonymizer
     */
    private $anonymizer;
    /**
     * @var null|resource
     */
    private $outputStream = null;
    /**
     * @var bool
     */
    private $showProgress = true;
    /**
     * @var int
     */
    private $progressSteps = 1;

    /**
     * @var AnonymizableEntity
     */
    private $entityModel;

    public function __construct(Anonymizer $anonymizer)
    {
        $this->anonymizer = $anonymizer;
    }
    /**
     * @param null|resource $stream writable stream resource or null if no output required
     */
    public function setOutputStream($stream)
    {
        $this->outputStream = $stream;
    }

    /**
     * @param boolean $showProgress
     */
    public function setShowProgress($showProgress)
    {
        $this->showProgress = $showProgress;
    }

    /**
     * @param int $progressSteps
     */
    public function setProgressSteps($progressSteps)
    {
        $this->progressSteps = $progressSteps;
    }

    public function update(CollectionIterator $iterator, AnonymizableEntity $entityModel)
    {
        $this->entityModel = $entityModel;
        $this->outputStart();
        $iterator->walk(array($this, 'updateCurrentRow'));
        $this->anonymizer->resetUniqueGenerator();
        $this->outputDone();
        $this->entityModel = null;
    }

    /**
     * @param CollectionIterator $iterator
     */
    public function updateCurrentRow(CollectionIterator $iterator)
    {
        $this->entityModel->setRawData($iterator->getRawData());
        $this->anonymizer->anonymize(array($this->entityModel));
        $this->entityModel->updateValues();
        $this->entityModel->clearInstance();
        $this->outputIteratorStatus($iterator);
    }

    private function outputStart()
    {
        if (is_resource($this->outputStream) && get_resource_type($this->outputStream) === 'stream') {
            fwrite($this->outputStream, sprintf("Updater started at %s.\n", date('H:i:s')));
        }
    }

    /**
     * @param CollectionIterator $iterator
     */
    private function outputIteratorStatus(CollectionIterator $iterator)
    {
        if ($this->showProgress && (($iterator->getIteration() + 1) % $this->progressSteps === 0)
            && is_resource($this->outputStream) && get_resource_type($this->outputStream) === 'stream'
        ) {
            fwrite($this->outputStream, sprintf("\rUpdating %s: %s/%s - Memory usage %s Bytes",
                $this->entityModel->getEntityName(),
                str_pad($iterator->getIteration() + 1, strlen($iterator->getSize()), ' '), $iterator->getSize(),
                number_format(memory_get_usage())));
        }
    }

    private function outputDone()
    {
        if (is_resource($this->outputStream) && get_resource_type($this->outputStream) === 'stream') {
            fwrite($this->outputStream, sprintf("\nUpdater finished at %s.\n\n", date('H:i:s')));
        }
    }
}