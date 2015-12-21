<?php
__autoloadDB('Db');
class Price extends Db
{
	
	public function Updateprice($dataForm,$sno)
	{
		global $mySession;
		$db=new Db();

		$dataUpdate['base_price']=$dataForm['base_price'];
		$dataUpdate['shipping_price']=$dataForm['shippingprice'];
		//$dataUpdate['page_position']=$dataForm['pageposition'];
		$conditionUpdate="sno='".$sno."'";
		
		$db->modify(TSHIRT_PRICE,$dataUpdate,$conditionUpdate);
		
		return 1;	
		
	}

	public function Updatediscount($dataForm,$sno)
	{
		global $mySession;
		$db=new Db();

		$dataUpdate['no_of_tee']=$dataForm['nooftee'];
		$dataUpdate['discount_per']=$dataForm['per_discount'];
		//$dataUpdate['page_position']=$dataForm['pageposition'];
		$conditionUpdate="sno='".$sno."'";
		//prd($dataUpdate);
		
		$db->modify(TSHIRT_DISCOUNT,$dataUpdate,$conditionUpdate);
		
		return 1;	
		
	}
	
	
		public function SaveIcons($dataForm)
	 
	 {
		 global $mySession;
		$db=new Db();
		
			$profileImage=$dataForm['image'];
			if($dataForm['image']!="")
			{
			$profileImage=time()."_".$profileImage;
			@rename(SITE_ROOT.'images/tshirt-icons/'.$dataForm['image'],SITE_ROOT.'images/tshirt-icons/'.$profileImage);
			}
		
		$dataInsert['icon']=$profileImage;
		$dataInsert['title']=$dataForm['title'];
		$dataInsert['status']=1;
		$dataInsert['date_added']=date('y-m-d');
		//prd($dataInsert);
		$db->save(TSHIRT_ICONS,$dataInsert);
		return 1;
		
	   }
	   

    public function Updatenewicon($dataForm,$id)
    {
		 global $mySession;
		$db=new Db();
		if($dataForm['image'] != "" && $dataForm['oldicon'] != "") {
			unlink(SITE_ROOT.'images/tshirt-icons/'.$dataForm['oldicon']);
		}
		$profileImage = $dataForm['oldicon'];
		if($dataForm['image'] != "") {
			$profileImage = time()."_".$dataForm['image'];
			@rename(SITE_ROOT.'images/tshirt-icons/'.$dataForm['image'],SITE_ROOT.'images/tshirt-icons/'.$profileImage);
		}
		
		$dataInsert['icon'] = $profileImage;
		$dataInsert['title'] = $dataForm['title'];
        $dataInsert['colorcode'] = $dataForm['colorcode'];
	
		$conditionUpdate="id='".$id."'";
		$db->modify(TSHIRT_ICONS,$dataInsert,$conditionUpdate);
		return 1;
    }

}
?>