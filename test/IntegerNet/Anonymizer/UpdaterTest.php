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
     * @test
     * @dataProvider getCollectionData
     */
    public function testUpdater($collectionData)
    {
        $entityCount = count($collectionData);

        $anonymizer = $this->getMock(Anonymizer::__CLASS, array('anonymize', 'resetUniqueGenerator'), array(), '', false);
        $anonymizer->expects($this->exactly(1))->method('resetUniqueGenerator');
        $anonymizer->expects($this->exactly($entityCount))->method('anonymize');

        $entityModel = $this->getMock(AnonymizableMock::__CLASS, array('setRawData', 'updateValues', 'clearInstance'));
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

        $updater = new Updater($anonymizer);
        $updater->update($collectionIterator, $entityModel);
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
