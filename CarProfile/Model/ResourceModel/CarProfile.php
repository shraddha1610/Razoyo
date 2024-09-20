<?php
namespace Razoyo\CarProfile\Model\ResourceModel;

class CarProfile extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('razoyo_carprofile', 'id');
    }

}