<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/
 
class CapacityWebSolutions_Inquiry_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function updateDetails(){
		$inquiry_model = Mage::getModel('inquiry/inquiry');
		$collection = $inquiry_model->getCollection();
		foreach($collection as $data){
			if(!$data->getWebsiteid()){
				$websiteid = Mage::getModel('core/store')->load($data->getStoreid())->getWebsiteId();
				$inquiry_model->load($data->getDealerid())
				->setData('websiteid',$websiteid)
				->save();
				
				$customer = Mage::getModel("customer/customer"); 
				$customer->setWebsiteId($websiteid); 
				$customer->loadByEmail($data->getEmail());
				
				if($customer->getId()){
					$dealerbyid = $inquiry_model->load($data->getDealerid())
					->setData('iscustcreated','1')
					->save();
				}
			}
		}
	}
		
	public function getOwnerEmail($store_id=null){
		$send_to = Mage::getStoreConfig('inquiry/admin_email/send_to',$store_id);
		if($send_to == "custom"){
			$adminEmail = Mage::getStoreConfig('inquiry/admin_email/owner_email',$store_id);
			if(empty($adminEmail)){
				$adminEmail = Mage::getStoreConfig('trans_email/ident_general/email',$store_id);
			}
		}else{
			$adminEmail = Mage::getStoreConfig('trans_email/ident_general/email',$store_id);
		}
		return $adminEmail;
	}
	
	public function createCaptchaImage(){
		$word="";
		$image = imagecreatetruecolor(130, 50);
		$background_color = imagecolorallocate($image, 255, 255, 255);  
		imagefilledrectangle($image,0,0,200,50,$background_color);
		$line_color = imagecolorallocate($image, 64,64,64); 
		for($i=0;$i<10;$i++) {
			imageline($image,0,rand()%50,200,rand()%50,$line_color);
		} 
		$pixel_color = imagecolorallocate($image, 0,0,255);
		for($i=0;$i<1000;$i++) {
			imagesetpixel($image,rand()%200,rand()%50,$pixel_color);
		} 
		$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
		$len = strlen($letters);
		$letter = $letters[rand(0, $len-1)];
		$font = $this->getFontUrl();;
		$text_color = imagecolorallocate($image, 0,0,0);

		for ($i = 0; $i< 4;$i++) {
			$letter = $letters[rand(0, $len-1)];
			imagettftext($image, 25, 0, 5+($i*32), 38, $text_color, $font, $letter);
			$word.=$letter;
		}
		
		Mage::getSingleton('core/session')->setCaptcha($word);
		$path = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA).DS."inquiry".DS."captcha";
		
		$files = glob($path.DS.'*.jpeg');
		foreach($files as $file) {
			unlink($file);
		}
		
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}
		
		$random = rand();
		$image_name = "captcha-".$random.".jpeg";
		imagepng($image, $path.DS.$image_name);
		return $image_name;
	}
	
	public function getFontUrl(){
		 return Mage::getBaseDir('media').DS."inquiry".DS."fonts".DS."arial.ttf";
	}
	
	public function getDealerPageLayout(){
		$page_layout = Mage::getStoreConfig('inquiry/general/page_layout');
		$res = "1column.phtml";
		switch($page_layout){
			case "empty":
				$res = "empty.phtml";
				break;
			case "one_column":
				$res = "1column.phtml";
				break;
			case "two_columns_left":
				$res = "2columns-left.phtml";
				break;
			case "two_columns_right":
				$res = "2columns-right.phtml";
				break;
			
			case "three_columns":
				$res = "3columns.phtml";
				break;
		}
		//echo $res;exit;
			return $res;
		
	}
}
