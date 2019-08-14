<?php
/***************************************************************************
 Extension Name	: Dealer Inquiry
 Extension URL	: http://www.magebees.com/magento-dealer-inquiry-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class CapacityWebSolutions_Inquiry_Block_Adminhtml_Inquiry_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct(){
		parent::__construct();
		$this->setId('inquiryGrid');
		$this->setDefaultSort('dealerid');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()	{
		$collection = Mage::getModel('inquiry/inquiry')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns(){
	  $this->addColumn('dealerid', array(
		  'header'    => Mage::helper('inquiry')->__('ID'),
		  'align'     =>'right',
		  'width'     => '50px',
		  'index'     => 'dealerid',
	  ));

	  $this->addColumn('firstname', array(
		  'header'    => Mage::helper('inquiry')->__('First Name'),
		  'index'     => 'firstname',
	  ));

		$this->addColumn('lastname', array(
		  'header'    => Mage::helper('inquiry')->__('Last Name'),
		  'index'     => 'lastname',
		));
		
		$this->addColumn('email', array(
		  'header'    => Mage::helper('inquiry')->__('Email'),
		  'index'     => 'email',
		));
		
		$this->addColumn('iscustcreated', array(
		  'header'    => Mage::helper('inquiry')->__('Is Customer Created'),
		  'index'     => 'iscustcreated',
		  'frame_callback' => array($this, 'isCustCreated'),
		  'type'      => 'options',
		   'options'   => array('1' => Mage::helper('adminhtml')->__('Created'), '0' => Mage::helper('adminhtml')->__('Not Created')),
		  'width'     => '100px',
		));
		
		$this->addColumn('storeid', array( 
			'header' => Mage::helper('inquiry')->__('Store View'), 
			'index' => 'storeid', 
			'type' => 'store', 
			
		
		)); 
		
		$this->addColumn('createddt', array(
		  'header'    => Mage::helper('inquiry')->__('Created Date'),
		  'format' => 'dd/MM/yyyy hh:mm:ss',
		  'index'     => 'createddt',
		  'type' => 'date',
		));
			  
		$this->addColumn('action', array(
			'header'    => Mage::helper('inquiry')->__('Action'),
			'align'     =>'center',
			'index'		=> 'action',
			'renderer'  => 'CapacityWebSolutions_Inquiry_Block_Adminhtml_Inquiry_Renderer_Image',
			'filter' => false,		  
		));
			
		return parent::_prepareColumns();
	}

	public function isCustCreated($value, $row, $column, $isExport)	{
		if ($value=="Created") {
			$cell = '<span class="grid-severity-notice"><span>Created</span></span>';
		} else {
			$cell = '<span class="grid-severity-major"><span>Not Created</span></span>';
		}
		return $cell;
	}

	protected function _prepareMassaction()	{
		$this->setMassactionIdField('dealer_id');
		$this->getMassactionBlock()->setFormFieldName('dealer');

		$this->getMassactionBlock()->addItem('delete', array(
			 'label'    => Mage::helper('inquiry')->__('Delete'),
			 'url'      => $this->getUrl('*/*/massDelete'),
			 'confirm'  => Mage::helper('inquiry')->__('Are you sure?')
		));
		return $this;
	}

	public function getRowUrl($row)	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}

}