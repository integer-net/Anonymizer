<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    IntegerNet_Anonymizer
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */

require_once 'abstract.php';
require_once 'autoloader_initializer.php';

class IntegerNet_Anonymizer_Shell extends AutoloaderInitializer
{
    /**
     * Run script
     */
    public function run()
    {
        ini_set('memory_limit', '1024M');
        /** @var IntegerNet_Anonymizer_Model_Anonymizer $anonymizer */
        $anonymizer = Mage::getModel('integernet_anonymizer/anonymizer');
        $anonymizer->setOutputStream(STDOUT);
        $anonymizer->setShowProgress((bool) $this->getArg('progress'));
        $anonymizer->setProgressSteps((int) $this->getArg('progress'));
        $anonymizer->anonymizeAll();
    }

    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f anonymizer.php -- [options]

  --progress [steps]    Show progress in real time (steps defines after how many updated entities status should be refreshed)
  -h | --help           This help
USAGE;
    }

}

$shell = new IntegerNet_Anonymizer_Shell();
$shell->run();