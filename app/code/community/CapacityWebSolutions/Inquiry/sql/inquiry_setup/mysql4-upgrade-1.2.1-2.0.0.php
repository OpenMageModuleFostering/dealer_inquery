<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

$installer = $this;
$installer->startSetup();

$dealer_table = 'dealerinquiry';

$installer->run("
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `websiteid` varchar(100) NOT NULL;

");

$installer->endSetup();
