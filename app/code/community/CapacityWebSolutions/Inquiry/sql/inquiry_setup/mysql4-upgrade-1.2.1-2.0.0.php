<?php

$installer = $this;
$installer->startSetup();

$installer->run("

ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `websiteid` varchar(100) NOT NULL;

 ");

$installer->endSetup();
