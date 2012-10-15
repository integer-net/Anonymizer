<?php
/**
 * Controller for AdminHtml Form
 *
 * @category    IntegerNet
 * @package     IntegerNet_Anonymizer
 * @author      Andreas von Studnitz <avs@integer-net.de>
 */
class IntegerNet_Anonymizer_AnonymizerController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title(Mage::helper('anonymizer')->__('System'))
            ->_title(Mage::helper('adminhtml')->__('Tools'))
            ->_title(Mage::helper('adminhtml')->__('Anonymizer'));

        $this->loadLayout();
        $this->_setActiveMenu('system/tools/anonymizer');
        $this->_addBreadcrumb(Mage::helper('anonymizer')->__('Anonymizer'), Mage::helper('anonymizer')->__('Anonymizer'));

        $block = $this->getLayout()->createBlock(
            'anonymizer/anonymizer',
            'anonymizer'
        );

        $this->getLayout()->getBlock('content')->append($block);

        $this->renderLayout();
    }

    /**
     * Action for saving an action
     *
     * @return void
     */
    public function saveAction()
    {
        try {
            /** @var $anonymizer IntegerNet_Anonymizer_Model_Anonymizer */
            $anonymizer = Mage::getModel('anonymizer/anonymizer');
            $anonymizer->anonymizeAll();
            foreach ($anonymizer->getResults() as $resultLabel => $resultCount) {
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('anonymizer')->__('Anonymized %s %s.', $resultCount, $resultLabel)
                );
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirectReferer();
    }
}