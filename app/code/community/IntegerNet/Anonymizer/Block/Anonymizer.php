<?php
/**
 * General Helper
 *
 * @category    IntegerNet
 * @package     IntegerNet_Anonymizer
 * @author      Andreas von Studnitz <avs@integer-net.de>
 */
class IntegerNet_Anonymizer_Block_Anonymizer extends Mage_Adminhtml_Block_Widget
{
    public function __construct()
    {
        parent::__construct();
        $this->setTitle($this->__('Anonymizer'));
        $this->setTemplate('anonymizer/form.phtml');
    }

    /**
     * Retrieve the POST URL for the form
     *
     * @return string URL
     */
    public function getPostActionUrl()
    {
        return $this->getUrl('*/*/save');
    }
}
