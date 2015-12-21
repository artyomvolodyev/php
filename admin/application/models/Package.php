<?php
__autoloadDB('Db');
class Package extends Db
{
  
  public function SavePackage($dataForm)
	{
		global $mySession;
		$db=new Db();
				$dataInsert['plan_name']=$dataForm['plan_name'];
				$dataInsert['plan_type']=$dataForm['option'];
				$dataInsert['plan_duration']=$dataForm['plan_duration'];
				$dataInsert['plan_price']=$dataForm['plan_price'];
				$dataInsert['plan_status']=$dataForm['plan_status'];
				$dataInsert['package_date']=date('Y-m-d H:i:s');
				$db->save(PACKAGES,$dataInsert);
				return 1;	

	}
	
	public function UpdatePackage($dataForm,$packageid)
	{
		global $mySession;
		$db=new Db();
		
				$dataUpdate['plan_name']=$dataForm['plan_name'];
				$dataUpdate['plan_type']=$dataForm['option'];
				$dataUpdate['plan_duration']=$dataForm['plan_duration'];
				$dataUpdate['plan_price']=$dataForm['plan_price'];
				$dataUpdate['plan_status']=$dataForm['plan_status'];
				
				$conditionUpdate="package_id='".$packageid."'";
				//prd($dataUpdate);
				$db->modify(PACKAGES,$dataUpdate,$conditionUpdate);;
				return 1;	
		
	}


}
