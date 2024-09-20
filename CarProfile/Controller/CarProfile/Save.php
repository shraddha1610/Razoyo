<?php
namespace Razoyo\CarProfile\Controller\CarProfile;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory; 

class Save extends \Magento\Framework\App\Action\Action
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
            $model = $this->carprofile;
            $customeId ='';
            if($this->_customerSession->getCustomer()){
                $customeId = $this->_customerSession->getCustomer()->getId();
            }
            $currentcars = "";
            $collection = $this->carsCollectionFactory;
            $collection->addFieldToFilter('customer_id', $customeId);
            if ($collection) {
                foreach($collection as $col){
                    $id = $col['id'];    
                }
                $model->load($id);
                $currentcars = explode(",",$model->getCarsIds());
            }
            if($customeId){
                unset($data['form_key']);
                if($currentcars){
                    $data = array_unique(array_merge($currentcars,$data));
                }
                $string = implode(',', $data);
                $model->setCustomerId($customeId);
                $model->setCarsIds($string);
            }
            try {
                $model->save();
                $this->messageManager->addSuccess(__('The Cars Has been Saved.'));
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the cars.'));
            }
            $this->_redirect('*/*/');
            return;
        }
        $this->_redirect('*/*/');
    }
}