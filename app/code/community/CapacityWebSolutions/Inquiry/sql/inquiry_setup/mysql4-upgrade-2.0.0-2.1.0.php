<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE `{$this->getTable('dealerinquiry')}` ENGINE=InnoDB;
ALTER TABLE `{$this->getTable('dealerinquiry')}` CHANGE `zip` `zip` varchar(255) NOT NULL;
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `date_time` datetime;
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `extra_field_one` varchar(255) NOT NULL;
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `extra_field_two` varchar(255) NOT NULL;
ALTER TABLE  `{$this->getTable('dealerinquiry')}`  ADD `extra_field_three` varchar(255) NOT NULL;

CREATE TABLE IF NOT EXISTS `{$this->getTable('dealerinquiry_files')}` (
  `dealer_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `dealerid` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY (`dealer_file_id`),
  KEY `IDX_DEALERINQUIRY_FILES_DEALER_ID` (`dealerid`)
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=0;

ALTER TABLE {$this->getTable('dealerinquiry_files')}
  ADD CONSTRAINT `FK_DEALERINQUIRY_FILES_DEALER_ID` FOREIGN KEY (`dealerid`) REFERENCES {$this->getTable('dealerinquiry')} (`dealerid`) ON DELETE CASCADE ON UPDATE CASCADE;
");

$installer->endSetup();
