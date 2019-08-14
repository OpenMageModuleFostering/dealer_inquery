<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

class CapacityWebSolutions_Inquiry_IndexController extends Mage_Core_Controller_Front_Action
{	
    public function indexAction()
    {
		$this->loadLayout(array('default'));
		$this->renderLayout();
	}
		
	public function thanksAction()
    {
		if($this->getRequest()->getPost())
		{
			$data = $this->getRequest()->getPost();
		 
			$captcha =  $this->getRequest()->getParam("captcha");
			$captcha_code =  $this->getRequest()->getParam("captcha_code");
			if($captcha == $captcha_code)
			{		 
				$storeid = Mage::app()->getStore()->getStoreId();
				$websiteid = Mage::app()->getWebsite()->getId(); 
				
				$country_name = explode('$$$',$data['country']);
				$data['storeid']=$storeid;
				$data['websiteid']=$websiteid;
				
				$model = Mage::getModel("inquiry/inquiry");
				$collection = $model->getCollection()
										->addFieldToFilter('email',$data['email'])
										->addFieldToFilter('storeid',$storeid);
					   
				if(!$collection->getSize())
				{
					$data['createddt']=Mage::getModel('core/date')->date('Y-m-d H:i:s');
					$customer = Mage::getModel("customer/customer"); 
					$customer->setWebsiteId($data['websiteid']); 
					$customer->loadByEmail($data['email']);
					
					if($customer->getId()){
						$data['iscustcreated']=1;
							
					}
					$model->setData($data);
					$model->save();
		
					$config_change_label = Mage::getStoreConfig('inquiry/change_label');
					
					$first_name = $config_change_label['f_name'];
					if($first_name){
						$first_name = $config_change_label['f_name'];
					}else {
						$first_name = "First Name";
					}
					
					$last_name = $config_change_label['l_name']; 
					if($last_name){
						$last_name = $config_change_label['l_name'];
					}else {
						$last_name = "Last Name";
					}
					
					$company_name = $config_change_label['company_name']; 
					if($company_name){
						$company_name = $config_change_label['company_name'];
					}else{
						$company_name = "Company Name";
					}
					
					$vat_number = $config_change_label['vat_number'];
					if($vat_number){
						$vat_number = $config_change_label['vat_number'];
					}else{
						$vat_number = "TAX/VAT Number";
					} 
				
					$address_name = $config_change_label['address']; 
					if($address_name){
						$address_name = $config_change_label['address'];
					}else{
						$address_name = "Address";
					} 

					$city_name = $config_change_label['city']; 
					if($city_name){
						$city_name = $config_change_label['city'];
					}else{
						$city_name = "City";
					} 

					$state_name = $config_change_label['state']; 
					if($state_name){
						$state_name = $config_change_label['state'];
					}else{
						$state_name = "State/Province";
					} 
					
					$country = $config_change_label['country']; 
					if($country){
						$country = $config_change_label['country'];
					}else{
						$country = "Country";
					} 
					$postal_code = $config_change_label['postal_code']; 
					if($postal_code){
						$postal_code = $config_change_label['postal_code'];
					}else{
						$postal_code = "ZIP/Postal Code";
					} 
							
					$contact_number = $config_change_label['contact_number']; 
					if($contact_number){
						$contact_number = $config_change_label['contact_number'];
					}else{
						$contact_number = "Contact Number";
					} 
								
					$email_name = $config_change_label['email']; 
					if($email_name){
						$email_name = $config_change_label['email'];
					}else{
						$email_name = "Email";
					} 
							
					$website_name = $config_change_label['website'];
					if($website_name){
						$website_name = $config_change_label['website'];
					}else{
						$website_name = "Website";
					} 
					$description = $config_change_label['description']; 
					if($description){
						$description = $config_change_label['description'];
					}else{
						$description = "Business Description";
					} 
		
					$adminContent = '<table border="0">
										<tr>
											<td>
												<table border="0">
													<tr>
														<Td>
															<label><p style="Font-size:22px;"><b>Hello Administrator,</b></p></label>
														</Td>
													</tr>
													<tr>
														<Td>
															<p>Mr/Ms. '.$data['firstname'].' '.$data['lastname'].' have filled dealer inquiry form and details are below.</p>
														</td>
													</tr>
													<tr>
														<td>
														<table border="0">
																<tr>
																	<td><label>'.$first_name.':</label></td>
																	<td><label>'.$data['firstname'].'</label></td>
																</tr>
																<tr>
																	<td><label>'.$last_name.':</label></td>
																	<td><label>'.$data['lastname'].'</label></td>
																</tr>
																<tr>
																	<td><label>'.$company_name.':</label></td>
																	<td><label>'.$data['company'].'</label></td>
																</tr>
																<tr>
																	<td><label>'.$vat_number.':</label></td>
																	<td><label>'.$data['taxvat'].'</label></td>
																</tr>
																<tr>
																	<td><label>'.$address_name.':</label></td>
																	<td><label>'.$data['address'].'</label></td>
																</tr>
																<tr>
																	<td><label>'.$city_name.':</label></td>
																	<td><label>'.$data['city'].'</label></td>
																</tr>
																<tr>
																	<td><label>'.$state_name.':</label></td>
																	<td><label>'.$data['state'].'</label></td>
																</tr>
																<tr>
																	<td><label>'.$country.':</label></td>
																	<td><label>'.$country_name[1].'</label></td>
																</tr>
																<tr>
																	<td><label>'.$postal_code.':</label></td>
																	<td><label>'.$data['zip'].'</label></td>
																</tr>
																<tr>
																	<td><label>'.$contact_number.':</label></td>
																	<td><label>'.$data['phone'].'</label></td>
																</tr>
																<tr>
																	<td><label>'.$email_name.':</label></td>
																	<td><label>'.$data['email'].'</label></td>
																</tr>
																<tr>
																	<td><label>'.$website_name.':</label></td>
																	<td><label>'.$data['website'].'</label></td>
																</tr>
																<tr>
																	<td valign="top" width="15%"><label>'.$description.':</label></td>
																	<td><label>'.$data['desc'].'</label></td>
																</tr>
																<tr>
																	<td colspan="2">&nbsp;</td></tr>
																<tr>
																	<td colspan="2"><label>Thank You.</label></td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>';
							
					$adminSubject = "New Dealer Inquiry from dealer";
					$adminName = Mage::getStoreConfig('trans_email/ident_general/name'); //sender name
					$adminEmail = Mage::getStoreConfig('trans_email/ident_general/email');
					$headers="";
					$headers  .= 'MIME-Version: 1.0'."\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From:'. $adminName.' <'.$adminEmail.'>';
					mail($adminEmail,$adminSubject,$adminContent,$headers);
					
					$email_logo = Mage::getStoreConfig('design/email/logo');
					$subject_title = Mage::getStoreConfig('inquiry/customer_email/heading');
					$email_desc = Mage::getStoreConfig('inquiry/customer_email/description');
					$email_desc = str_replace("{{Name}}",$data['firstname'].$data['lastname'],$email_desc);
					$store_name = Mage::getStoreConfig('general/store_information/name');

					$img_media =  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'email/logo/'; 

					$img_logo_final = $img_media.$email_logo;
					$default_logo =  Mage::getStoreConfig('design/header/logo_src');	
					$logo_default = Mage::getDesign()->getSkinUrl().$default_logo; 
					$email_desc = str_replace("{{Storename}}",$store_name,$email_desc);		
					
					if($img_logo_final == $img_media)
					{
						$logo_img = "<img src='$logo_default'/>"; 
					}
					else
					{
						$logo_img =   "<img src='$img_logo_final'/>";
					}
			
					$customerContent = '<table border="0">
											<tr>
												<td>
													<table border="0">
														<tr>
															<Td>'.$logo_img.'</Td>
														</tr>
														<tr>
															<td colspan="2">&nbsp;</td></tr>
														<tr>
															<Td><p>'.$email_desc.'. </p></Td>
														</tr>
														
													</table>
												</td>
											</tr>
										</table>';
					$headers = "";
					$adminName = Mage::getStoreConfig('trans_email/ident_general/name'); //sender name
					$custSubject = $subject_title;
					$headers  .= 'MIME-Version: 1.0'."\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From:'. $adminName.' <'.$adminEmail.'>';
				
					mail($data['email'],$custSubject,$customerContent,$headers);
				}
				else
				{
					Mage::getSingleton('core/session')->addError(Mage::helper('inquiry')->__('Email id already exits !'));
					$this->_redirect('*/*/');return;
				}
			}
			else
			{
				Mage::getSingleton('core/session')->addError(Mage::helper('inquiry')->__('Captcha code does not match!'));
				$this->_redirect('*/*/');return;
			}
		}
		$this->_redirect('*/*/success');
	}
	
	public function successAction(){
		$this->loadLayout(array('default'));
		$this->renderLayout();
	}
}	
?>
