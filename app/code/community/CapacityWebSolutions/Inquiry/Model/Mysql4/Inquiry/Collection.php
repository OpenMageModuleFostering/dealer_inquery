<?php
/**
 * @copyright  Copyright (c) 2010 Capacity Web Solutions Pvt. Ltd  (http://www.capacitywebsolutions.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CapacityWebSolutions_Inquiry_Model_Mysql4_Inquiry_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
	{
  		parent::_construct();
        $this->_init('inquiry/inquiry');
	}
}

