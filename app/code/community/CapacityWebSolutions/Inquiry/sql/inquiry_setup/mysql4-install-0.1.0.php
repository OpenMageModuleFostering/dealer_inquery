<?php
$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('dealerinquiry')};
CREATE TABLE IF NOT EXISTS `{$this->getTable('dealerinquiry')}` (
  `dealerid` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(25) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `company` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `taxvat` varchar(25) NOT NULL,
  `city` varchar(25) NOT NULL,
  `state` varchar(25) NOT NULL,
  `country` varchar(255) NOT NULL,
  `zip` varchar(6) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `storeid` varchar(100) NOT NULL,
  `website` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `iscustcreated` enum('0','1') NOT NULL,
  `status` enum('0','1') NOT NULL,
  `createddt` datetime NOT NULL,
  `updateddt` datetime NOT NULL,
  PRIMARY KEY (`dealerid`)
) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=0;


");

$installer->endSetup(); 