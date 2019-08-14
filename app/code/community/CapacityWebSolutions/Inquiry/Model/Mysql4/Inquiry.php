<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/
 
class CapacityWebSolutions_Inquiry_Model_Mysql4_Inquiry extends Mage_Core_Model_Mysql4_Abstract
{
  	public function _construct()
	{
  		$this->_init('inquiry/inquiry', 'dealerid');
	}
	
	/* protected function _getLoadSelect($field, $value, $object)
	{
		$select = parent::_getLoadSelect($field, $value, $object);
		$select->joinLeft(
		array('files' => 'dealerinquiry_files'),$this->getMainTable() . '.dealerid = files.dealerid');
		return $select;
	} */
	
	protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
		$inq_file_model = Mage::getModel('inquiry/inquiryfiles');
		if($object->getFile()){
			foreach($object->getFile() as $fname){
				$data['dealerid'] = $object->getId();
				$data['filename'] = $fname;
				$inq_file_model->setData($data);
				$inq_file_model->save();
			}
		}
		return parent::_afterSave( $object );
	}
}

