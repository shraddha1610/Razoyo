<?php

namespace Razoyo\CarProfile\Block\CarProfile;

class MyCars extends \Magento\Framework\View\Element\Template
{
    protected $_customerSession; 
    protected $carsCollectionFactory;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $session,
        \Razoyo\CarProfile\Model\ResourceModel\CarProfile\Collection $carsCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_customerSession = $session;
        $this->carsCollectionFactory = $carsCollectionFactory;
    }
    
    public function getCollection(){
        $customeId ='';
        if($this->_customerSession->getCustomer()){
            $customeId = $this->_customerSession->getCustomer()->getId();
        }
        $collection = $this->carsCollectionFactory;
        $collection->addFieldToFilter('customer_id', $customeId);
        
        if($collection){
            foreach($collection as $col){
                $carsIds = $col['cars_ids'];    
            }
            $cars_Ids = explode(',', $carsIds);
            return $cars_Ids;
    }
}
    public function getCarsDetails($id){
       
        $apiUrl = 'https://exam.razoyo.com/api/cars';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if($httpcode=='200'){
        curl_close($curl);
        curl_setopt($curl, CURLOPT_HEADERFUNCTION,
             function($curl, $header) use (&$headers)
            {
              $len = strlen($header);
              $header = explode(':', $header, 2);
              if (count($header) < 2) // ignore invalid headers
              return $len;

              $headers[strtolower(trim($header[0]))][] = trim($header[1]);
    
             return $len;
            }
            );
            curl_exec($curl);
        if($headers['your-token'][0]){
            $carapiUrl = 'https://exam.razoyo.com/api/cars/'.$id;
            $curl = curl_init();
            $authorization = 'Authorization: Bearer '.$headers['your-token'][0];
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization )); 
            curl_setopt($curl, CURLOPT_URL, $carapiUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if($httpcode=='200'){
            $collection = json_decode($response,true);
            return $collection['car'];
        }
        return '';
        }
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
