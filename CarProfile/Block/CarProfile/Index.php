<?php

namespace Razoyo\CarProfile\Block\CarProfile;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $_customerSession; 
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $session,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_customerSession = $session;
    }
    
    public function getCollection(){
        $apiUrl = 'https://exam.razoyo.com/api/cars';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
         $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if($httpcode=='200'){
            $collection = json_decode($response,true);
            return $collection['cars'];
        }
        return '';
    }
    
    /**
     * @return boolean
     */
    public function isCustomerLoggedIn() {
        //return $this->_customerSession->isLoggedIn();
        if ($this->_customerSession->isLoggedIn()) {
            return true; 
        } else {
            return false;
        }
    }
}
