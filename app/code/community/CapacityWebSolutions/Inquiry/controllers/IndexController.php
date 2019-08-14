<?php
/**
 * @copyright  Copyright (c) 2010 Capacity Web Solutions Pvt. Ltd  (http://www.capacitywebsolutions.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CapacityWebSolutions_Inquiry_IndexController extends Mage_Core_Controller_Front_Action
{	
    public function indexAction()
    {
		$this->loadLayout(array('default'));
		$this->renderLayout();
		
	}
	
	public function delAction()
	{
		$getUrl=Mage::getSingleton('adminhtml/url')->getSecretKey("adminhtml_mycontroller","delAction");
		$delid = $this->getRequest()->getParam('delid');
		if(!empty($delid))
		{
			$collection = Mage::getModel("inquiry/inquiry")->load($delid);
			
			if($collection->delete())
			{
					
			}
			else
			{
				Mage::getSingleton('core/session')->addError("Sorry inquiry is not deleted.");
			}
		}
		$this->_redirectReferer();
	}
	
	public function thanksAction()
    {
		$this->loadLayout(array('default'));
		$this->renderLayout();
		if($_POST['SUBMIT']=='SUBMIT')
		{
		
		$captcha =  $this->getRequest()->getParam("captcha");
		$captcha_code =  $this->getRequest()->getParam("captcha_code");
		if($captcha == $captcha_code)
		{		 
	
		$fname =  $this->getRequest()->getParam("fname");
		$lname =  $this->getRequest()->getParam("lname");
		$company =  $this->getRequest()->getParam("company");
		$taxvat =  $this->getRequest()->getParam("account_taxvat"); 
		$address =  $this->getRequest()->getParam("address");
		$city =  $this->getRequest()->getParam("city");
		$state =  $this->getRequest()->getParam("state_id");
		$country =  $this->getRequest()->getParam("country");
		$zip =  $this->getRequest()->getParam("zip");
		$phone =  $this->getRequest()->getParam("phone");
		$email =  $this->getRequest()->getParam("email");
		$storeid = Mage::app()->getStore()->getStoreId();
		$website =  $this->getRequest()->getParam("website");
		$bdesc =  addslashes($this->getRequest()->getParam("bdesc"));
		$headers = "";
		$country1 = explode('$$$',$country);

		$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$query = 'SELECT * FROM ' . $resource->getTableName('dealerinquiry')." where email='".$email."' and storeid='".$storeid."'";

        $results = $readConnection->fetchRow($query);
   
		  if($results == false)
		  {
		
		$insertArr = array("firstname"=>$fname,"lastname"=>$lname,"company"=>$company,"address"=>$address,"taxvat"=>$taxvat,"city"=>$city,"state"=>$state,"country"=>$country,"zip"=>$zip,"phone"=>$phone,"email"=>$email,"storeid"=>$storeid,"website"=>$website,"desc"=>$bdesc,"iscustcreated"=>0,"status"=>1,"createddt"=>date('Y-m-d H:i:s')); 
	   	
		$collection = Mage::getModel("inquiry/inquiry");
		
		$collection->setData($insertArr); 
		
		$collection->save();
		
		
		$adminContent = '<table border="1">
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
												<p>Mr/Ms. '.$fname.' '.$lname.' have filled dealer inquiry form and details are below.</p>
											</td>
										</tr>
										<tr>
											<td>
												<table border="0">
													<tr>
														<td><label>First Name:</label></td>
														<td><label>'.$fname.'</label></td>
													</tr>
													<tr>
														<td><label>Last Name:</label></td>
														<td><label>'.$lname.'</label></td>
													</tr>
													<tr>
														<td><label>Company:</label></td>
														<td><label>'.$company.'</label></td>
													</tr>
													<tr>
														<td><label>TAX/VAT Number:</label></td>
														<td><label>'.$taxvat.'</label></td>
													</tr>
													<tr>
														<td><label>Address:</label></td>
														<td><label>'.$address.'</label></td>
													</tr>
													<tr>
														<td><label>City:</label></td>
														<td><label>'.$city.'</label></td>
													</tr>
													<tr>
														<td><label>State/Province:</label></td>
														<td><label>'.$state.'</label></td>
													</tr>
													<tr>
														<td><label>Country:</label></td>
														<td><label>'.$country1[1].'</label></td>
													</tr>
													<tr>
														<td><label>ZIP/Postal Code:</label></td>
														<td><label>'.$zip.'</label></td>
													</tr>
													<tr>
														<td><label>Contact Phone Number:</label></td>
														<td><label>'.$phone.'</label></td>
													</tr>
													<tr>
														<td><label>Email:</label></td>
														<td><label>'.$email.'</label></td>
													</tr>
													<tr>
														<td><label>Website:</label></td>
														<td><label>'.$website.'</label></td>
													</tr>
													<tr>
														<td valign="top" width="15%"><label>Business Description:</label></td>
														<td><label>'.$bdesc.'</label></td>
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
		$headers  .= 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From:'. $adminName.' <'.$adminEmail.'>';
		mail($adminEmail,$adminSubject,$adminContent,$headers);
		
		$email_logo = Mage::getStoreConfig('design/email/logo');
		$subject_title = Mage::getStoreConfig('inquiry/customer_email/heading');
		$email_desc = Mage::getStoreConfig('inquiry/customer_email/description');
		$store_name = Mage::getStoreConfig('general/store_information/name');
	
		$img_media =  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'email/logo/'; 
	
		$img_logo_final = $img_media.$email_logo;
		$default_logo =  Mage::getStoreConfig('design/header/logo_src');	
		$logo_default = Mage::getDesign()->getSkinUrl().$default_logo; 
				
			
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
												<Td><p style="Font-size:22px;"></b>Hello '.$fname.' '.$lname.',</b></p></Td>
											</tr>
											<tr>
												<td colspan="2">&nbsp;</td></tr>
											<tr>
												<Td><p>'.$email_desc.'. </p></Td>
											</tr>
											<tr>
												<td colspan="2">&nbsp;</td></tr>
											<tr>
												<td colspan="2"><p style="text-align:center;">Thank You,'.$store_name.'</p></td>
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
		
		mail($email,$custSubject,$customerContent,$headers);
			}
			else
			{
			$message = "email_wrong";  
			Mage::getSingleton('core/session')->setSomeSessionVar($message);			
			$this->_redirectReferer();
			
			}
    	}
        else
		{
		$message = "wrong";  
		Mage::getSingleton('core/session')->setSomeSessionVar($message);			
		$this->_redirectReferer();
		}
		}		
	}
}	
?>
