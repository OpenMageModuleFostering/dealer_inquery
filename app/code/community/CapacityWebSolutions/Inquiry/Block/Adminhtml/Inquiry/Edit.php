<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

class CapacityWebSolutions_Inquiry_Block_Adminhtml_Inquiry_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {		
        parent::__construct();
        $this->_removeButton('save');       
        $this->_removeButton('reset');       
        $this->_objectId = 'id';
        $this->_blockGroup = 'inquiry';
        $this->_controller = 'adminhtml_inquiry';
		$this->_addButton('adminhtml_inquiry', array(
			'label' => $this->__('Create Customer'),
			'onclick' => "setLocation('{$this->getUrl('*/adminhtml_inquiry/createCustomer', array('id' => Mage::registry('inquiry_data')->getId()))}')",
		));
        
        $this->_updateButton('delete', 'label', Mage::helper('inquiry')->__('Delete'));
		
		$this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('inquiry_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'inquiry_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'inquiry_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('inquiry_data') && Mage::registry('inquiry_data')->getId() ) {
            return Mage::helper('inquiry')->__("View Dealer '%s'", $this->htmlEscape(Mage::registry('inquiry_data')->getFirstname()));
        } else {
            return Mage::helper('inquiry')->__('Add Item');
        }
    }
}