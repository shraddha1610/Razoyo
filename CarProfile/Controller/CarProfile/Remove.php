<?php
namespace Razoyo\CarProfile\Controller\CarProfile;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory; 

class Remove extends \Magento\Framework\App\Action\Action
{   
    protected $carprofile;
    protected $_customerSession;
    protected $resultPageFactory; 
    protected $carsCollectionFactory;
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Razoyo\CarProfile\Model\CarProfile $carprofile,
        \Razoyo\CarProfile\Model\ResourceModel\CarProfile\Collection $carsCollectionFactory,
        \Magento\Customer\Model\Session $session
    ) {
        $this->carprofile = $carprofile;
        $this->carsCollectionFactory = $carsCollectionFactory;
        $this->_customerSession = $session;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {

        $data = $this->getRequest()->getParams();
        if ($data) {
            $customeId ='';
            if($this->_customerSession->getCustomer()){
                $customeId = $this->_customerSession->getCustomer()->getId();
            }
            $collection = $this->carsCollectionFactory;
            $collection->addFieldToFilter('customer_id', $customeId);
            $model = $this->carprofile;
            $carId = $this->getRequest()->getParam('carid');
            if ($collection) {
                foreach($collection as $col){
                    $id = $col['id'];    
                }
                $model->load($id);
            }
            if($customeId){
                $currentcars = explode(",",$model->getCarsIds());
                if (($key = array_search($carId, $currentcars)) !== false) {
                    unset($currentcars[$key]);
                }
                $string = implode(',', $currentcars);
                $model->setCustomerId($customeId);
                $model->setCarsIds($string);
            }
            try {
                $model->save();
                $this->messageManager->addSuccess(__('The Cars Has been Saved.'));
                $this->_redirect('*/*/mycars');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the cars.'));
            }
            $this->_redirect('*/*/mycars');
            return;
        }
        $this->_redirect('*/*/mycars');
    }
}