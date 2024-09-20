<?php
namespace Razoyo\CarProfile\Model\ResourceModel\CarProfile;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Razoyo\CarProfile\Model\CarProfile', 'Razoyo\CarProfile\Model\ResourceModel\CarProfile');
    }
}