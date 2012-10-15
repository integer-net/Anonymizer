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

    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Action for /admin/action/edit/
     * Edit action details
     *
     * @return void
     */
    public function editAction()
    {
        $this->loadLayout()
            ->_addContent($this->getLayout()->createBlock('sfp_banking/action_edit'))
            ->renderLayout();
    }

    /**
     * Action for saving an action
     *
     * @return void
     */
    public function saveAction()
    {
        $actionId = $this->getRequest()->getParam('id', false);

        if ($data = $this->getRequest()->getPost()) {

            /** @var $action Sfp_Banking_Model_Action */
            $action = Mage::getModel('sfp_banking/action');

            if ($actionId) {

                $action->load($actionId);
            }

            if (is_array($data['custom_prices'])) {
                $data['custom_prices'] = serialize($data['custom_prices']);
            }

            $action->addData($data);

            try {
                $this->_checkForExistingCode($action);
                $action->save();

                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('sfp_banking')->__('Action was saved successfully'));
                $this->getResponse()->setRedirect($this->getUrl('*/*/'));
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')
                    ->addError($e->getMessage());
            }
        }
        $this->_redirectReferer();
    }

    /**
     * Check if the given code already exists in any institute or action (different from the current one)
     *
     * @param Sfp_Banking_Model_Action $action
     */
    protected function _checkForExistingCode($action)
    {
        if (!$action->getCode()) return;

        $instituteCollection = Mage::getModel('sfp_banking/institute')
            ->getCollection()
            ->addFieldToFilter('code', $action->getCode());

        if ($instituteCollection->getSize() > 0) {

            Mage::throwException(Mage::helper('sfp_banking')->__('Code already exists.'));
        }

        $actionCollection = Mage::getModel('sfp_banking/action')
            ->getCollection()
            ->addFieldToFilter('code', $action->getCode());

        if ($actionId = $action->getId()) {
            $actionCollection->addFieldToFilter('action_id', array('neq' => $actionId));
        }

        if ($actionCollection->getSize() > 0) {

            Mage::throwException(Mage::helper('sfp_banking')->__('Code already exists.'));
        }
    }

    /**
     * Action for deleting an action
     *
     * @return void
     */
    public function deleteAction()
    {
        $actionId = $this->getRequest()->getParam('id', false);

        try {
            Mage::getModel('sfp_banking/action')->setId($actionId)->delete();
            Mage::getSingleton('adminhtml/session')
                ->addSuccess(Mage::helper('sfp_banking')
                ->__('Action successfully deleted'));
            $this->getResponse()->setRedirect($this->getUrl('*/*/'));

            return;
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirectReferer();
    }

}