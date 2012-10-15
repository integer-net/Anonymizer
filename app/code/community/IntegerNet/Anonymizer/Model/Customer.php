<?php
/**
 * Magento Anonymizer Script
 *
 * @category    IntegerNet
 * @package     IntegerNet_Anonymizer
 * @author      Andreas von Studnitz <avs@integer-net.de>
 */
class IntegerNet_Anonymizer_Model_Customer
{
    protected $_unusedCustomerData = array();
    protected $_anonymizedOrders = array();
    protected $_anonymizedQuotes = array();
    protected $_anonymizedNewsletterSubscribers = array();


    public function anonymizeAll()
    {
        /** @var $customers Mage_Customer_Model_Resource_Customer_Collection */
        $customers = Mage::getModel('customer/customer')
            ->getCollection()
            ->addAttributeToSelect(array('prefix', 'firstname', 'lastname', 'suffix'));

        $customerCount = $customers->getSize();

        $this->_fetchRandomCustomerData($customerCount);

        $this->_anonymizeCustomers($customers);
    }

    /**
     * @param Mage_Customer_Model_Resource_Customer_Collection $customers
     */
    protected function _anonymizeCustomers($customers)
    {
        foreach ($customers as $customer) {

            $this->_anonymizeCustomer($customer);
        }
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeCustomer($customer)
    {
        $randomData = $this->_getRandomData();

        foreach ($this->_getCustomerMapping() as $customerKey => $randomDataKey) {
            if (!$customer->getData($customerKey)) {
                continue;
            }

            if (strlen($randomDataKey)) {
                $customer->setData($customerKey, $randomData[$randomDataKey]);
            } else {
                $customer->setData($customerKey, '');
            }
        }

        $customer->getResource()->save($customer);

        $this->_anonymizeCustomerAddresses($customer, $randomData);
    }

    /**
     * @return array
     */
    protected function _getRandomData()
    {
        $randomData = array_pop($this->_unusedCustomerData);
        if (is_null($randomData)) {
            $this->_fetchRandomCustomerData(100);
            $randomData = array_pop($this->_unusedCustomerData);
        }
        return $randomData;
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     * @param array $randomData
     */
    protected function _anonymizeCustomerAddresses($customer, $randomData)
    {
        $customerAddresses = $customer->getAddressesCollection()
            ->addAttributeToSelect(array('prefix', 'firstname', 'lastname', 'suffix'));

        foreach($customerAddresses as $customerAddress) {

            /** @var $customerAddress Mage_Customer_Model_Address */
            if ($customerAddress->getFirstname() == $customer->getOrigData('firstname')
                && $customerAddress->getLastname() == $customer->getOrigData('lastname')) {

                $newRandomData = $randomData;
            } else {
                $newRandomData = $this->_getRandomData();
            }

            foreach ($this->_getAddressMapping() as $addressKey => $randomDataKey) {
                if (!$customerAddress->getData($addressKey)) {
                    continue;
                }

                if (strlen($randomDataKey)) {
                    $customerAddress->setData($addressKey, $newRandomData[$randomDataKey]);
                } else {
                    $customerAddress->setData($addressKey, '');
                }
            }

            $customerAddress->getResource()->save($customerAddress);
        }
    }

    /**
     * @return array
     */
    protected function _getCustomerMapping()
    {
        return array(
            'prefix' => 'prefix',
            'firstname' => 'first_name',
            'middlename' => '',
            'lastname' => 'last_name',
            'suffix' => 'suffix',
            'email' => 'email',
        );
    }

    /**
     * @return array
     */
    protected function _getAddressMapping()
    {
        return array(
            'prefix' => 'prefix',
            'firstname' => 'first_name',
            'middlename' => '',
            'lastname' => 'last_name',
            'suffix' => 'suffix',
            'company' => 'bs',
            'street' => 'street_address',
            'telephone' => 'zip_code',
            'fax' => '',
            'vat_id' => '',
        );
    }

    /**
     * @param int $count
     * @return array
     */
    protected function _fetchRandomCustomerData($count)
    {
        $url = "http://fakester.biz/json?n=$count";
        $json = file_get_contents($url);
        $this->_unusedCustomerData = Zend_Json::decode($json);

        /*
         * Fakester return these fields for customers:
         *
         *   [name] => Johnson, Kreiger and Jenkins
         *   [first_name] => Citlalli
         *   [last_name] => Gorczany
         *   [prefix] => Dr.
         *   [suffix] => Inc
         *   [city] => Loisshire
         *   [city_prefix] => Lake
         *   [city_suffix] => bury
         *   [country] => United Arab Emirates
         *   [secondary_address] => Suite 720
         *   [state] => Wyoming
         *   [state_abbr] => OK
         *   [street_address] => 61204 Lang Garden
         *   [street_name] => Lakin Unions
         *   [street_suffix] => Dam
         *   [zip_code] => 38126-1906
         *   [bs] => unleash world-class technologies
         *   [catch_phrase] => Vision-oriented grid-enabled throughput
         *   [domain_name] => mayer.org
         *   [domain_suffix] => info
         *   [domain_word] => hoppe
         *   [email] => jefferey@baileysimonis.name
         *   [free_email] => emmitt@hotmail.com
         *   [ip_v4_address] => 163.49.36.30
         *   [ip_v6_address] => 61b4:5b6:7d1d:db11:ab29:e003:eb4:161f
         *   [user_name] => meghan
         *
         */

        return $this->_unusedCustomerData;
    }
}