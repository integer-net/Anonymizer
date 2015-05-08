<?php
/**
 * integer_net Magento Module
 *
 * @category   IntegerNet
 * @package    
 * @copyright  Copyright (c) 2015 integer_net GmbH (http://www.integer-net.de/)
 * @author     Fabian Schmengler <fs@integer-net.de>
 */

namespace IntegerNet\Anonymizer;

use N98\Magento\Command\AbstractMagentoCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

class AnonymizeCommand extends AbstractMagentoCommand
{
    protected function configure()
    {
        $this
            ->setName('db:anonymize')
            ->setDescription('Anonymize customer data');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->detectMagento($output, true);
        if ($this->initMagento()) {
            $this->registerPsr0Autoloader();
            /** @var \IntegerNet_Anonymizer_Model_Anonymizer $anonymizer */
            $anonymizer = \Mage::getModel('integernet_anonymizer/anonymizer');
            if ($output instanceof StreamOutput) {
                $anonymizer->setOutputStream($output->getStream());
            } else {
                //TODO allow OutputInterface in Anonymizer?
                //TODO pass ansi/no-ansi configuration to Anonymizer and use colors
                ob_start();
                $anonymizer->setOutputStream(STDOUT);
                $output->write(ob_get_clean());
            }
            $anonymizer->anonymizeAll();
        }
    }
    private function registerPsr0Autoloader()
    {
        \Mage::getConfig()->init()->loadEventObservers('global');
        \Mage::app()->addEventArea('global');
        \Mage::dispatchEvent('add_spl_autoloader');
    }
}