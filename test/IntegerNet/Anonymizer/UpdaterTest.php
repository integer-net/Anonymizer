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


use IntegerNet\Anonymizer\Mock\AnonymizableMock;
use IntegerNet\Anonymizer\Mock\CollectionMock;

class UpdaterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Anonymizer|\PHPUnit_Framework_MockObject_MockObject
     */
    private $anonymizerMock;
    /**
     * @var Updater
     */
    private $updater;

    protected function setUp()
    {
        $this->anonymizerMock = $this->getMock(Anonymizer::__CLASS, array('anonymize', 'resetUniqueGenerator'), array(), '', false);
        $this->updater = new Updater($this->anonymizerMock);
    }

    /**
     * @test
     * @dataProvider getCollectionData
     */
    public function testUpdater($collectionData)
    {
        $entityCount = count($collectionData);

        $this->anonymizerMock->expects($this->exactly(1))->method('resetUniqueGenerator');
        $this->anonymizerMock->expects($this->exactly($entityCount))->method('anonymize');

        $entityModel = $this->getEntityMock();
        foreach ($collectionData as $i => $entityData) {
            $entityModel->expects($this->at($i * 3))->method('setRawData')
                ->with($entityData);
            $entityModel->expects($this->at($i * 3 + 1))->method('updateValues');
            $entityModel->expects($this->at($i * 3 + 2))->method('clearInstance');
        }
        $entityModel->expects($this->exactly($entityCount))->method('setRawData');
        $entityModel->expects($this->exactly($entityCount))->method('updateValues');
        $entityModel->expects($this->exactly($entityCount))->method('clearInstance');

        $collectionIterator = new CollectionMock($collectionData);

        $this->updater->update($collectionIterator, $entityModel);
    }

    private function getEntityMock()
    {
        return $this->getMock(AnonymizableMock::__CLASS, array('setRawData', 'updateValues', 'clearInstance'));
    }

    /**
     * @test
     * @dataProvider getCollectionData
     */
    public function testOutputControl($collectionData)
    {
        $stream = fopen('php://temp', 'r+');
        $this->updater->setOutputStream($stream);
        $this->updater->update(new CollectionMock($collectionData), $this->getEntityMock());
        $actualOutput = stream_get_contents($stream, -1, 0);
        $this->assertContains('Updater started at', $actualOutput);
        $this->assertContains('Updating Mock: 1/5 ', $actualOutput);
        $this->assertContains('Updating Mock: 2/5 ', $actualOutput);
        $this->assertContains('Updating Mock: 3/5 ', $actualOutput);
        $this->assertContains('Updating Mock: 4/5 ', $actualOutput);
        $this->assertContains('Updating Mock: 5/5 ', $actualOutput);
        $this->assertContains('Updater finished at', $actualOutput);
        fclose($stream);

        $stream = fopen('php://temp', 'r+');
        $this->updater->setOutputStream($stream);
        $this->updater->setProgressSteps(2);
        $this->updater->update(new CollectionMock($collectionData), $this->getEntityMock());
        $actualOutput = stream_get_contents($stream, -1, 0);
        $this->assertContains('Updater started at', $actualOutput);
        $this->assertNotContains('Updating Mock: 1/5 ', $actualOutput);
        $this->assertContains('Updating Mock: 2/5 ', $actualOutput);
        $this->assertNotContains('Updating Mock: 3/5 ', $actualOutput);
        $this->assertContains('Updating Mock: 4/5 ', $actualOutput);
        $this->assertNotContains('Updating Mock: 5/5 ', $actualOutput);
        $this->assertContains('Updater finished at', $actualOutput);
        fclose($stream);

        $stream = fopen('php://temp', 'r+');
        $this->updater->setOutputStream($stream);
        $this->updater->setShowProgress(false);
        $this->updater->update(new CollectionMock($collectionData), $this->getEntityMock());
        $actualOutput = stream_get_contents($stream, -1, 0);
        $this->assertContains('Updater started at', $actualOutput);
        $this->assertNotContains('Updating ', $actualOutput);
        $this->assertContains('Updater finished at', $actualOutput);
        fclose($stream);

    }

    public static function getCollectionData()
    {
        return array(
            array(
                'collectionData' => array(
                    array('email' => 'death@example.com', 'name' => 'Death'),
                    array('email' => 'pestilence@example.com', 'name' => 'Pestilence'),
                    array('email' => 'famine@example.com', 'name' => 'Famine'),
                    array('email' => 'war@example.com', 'name' => 'War'),
                    array('email' => 'ronny@example.com', 'name' => 'Ronny')
                )
            )
        );
    }

}
