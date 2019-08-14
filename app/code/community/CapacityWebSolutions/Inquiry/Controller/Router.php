<?php 
/***************************************************************************
 Extension Name	: Dealer Inquiry
 Extension URL	: http://www.magebees.com/magento-dealer-inquiry-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class CapacityWebSolutions_Inquiry_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
	const CUSTOM_URL_KEY = 'inquiry/general/url_key';
	/**
	* Initialize Controller Router
	*
	* @param Varien_Event_Observer $observer
	*/
	public function initControllerRouters($observer)
	{
		/* @var $front Mage_Core_Controller_Varien_Front */
		$front = $observer->getEvent()->getFront();
		$front->addRouter('inquiry', $this);//replace router_name with a suitable name
	}

	/**
	* Validate and Match router with the Page and modify request
	*
	* @param Zend_Controller_Request_Http $request
	* @return bool
	*/
	public function match(Zend_Controller_Request_Http $request)
	{
		if (!Mage::isInstalled()) {
			Mage::app()->getFrontController()->getResponse()
			->setRedirect(Mage::getUrl('install'))
			->sendResponse();
			exit;
		}

		$identifier = trim($request->getPathInfo(), "/");
		
		$condition = new Varien_Object(array(
		'identifier' => $identifier,
		'continue' => true
		));
		Mage::dispatchEvent('inquiry_controller_router_match_before', array(
		'router' => $this,
		'condition' => $condition
		));

		$identifier = $condition->getIdentifier();

		if ($condition->getRedirectUrl()) {
			Mage::app()->getFrontController()->getResponse()
			->setRedirect($condition->getRedirectUrl())
			->sendResponse();
			$request->setDispatched(true);
			return true;
		}

		if (!$condition->getContinue()) {
			return false;
		}
			
		
		$custom_url = Mage::getStoreConfig(self::CUSTOM_URL_KEY);
	
		if($identifier==$custom_url){
			$request->setModuleName('inquiry')// replace modulename with the frontname of the router of your controller
			->setControllerName('index')// replace index with your controller name
			->setActionName('index'); // replace index with your action name
		}else{
			$request->setModuleName('inquiry')
			->setControllerName('index')
			->setActionName('noroute'); 
			
		}
		
		$request->setAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,$identifier);

		return true;
	}
}