<?php
__autoloadDB('Db');
class Sitedesc extends Db
{
	public function Updatedesc($dataForm,$desc_id)
	{
	

		global $mySession;
		$db=new Db();
		$dataUpdate['site_desc']=$dataForm['site_desc'];
	 	$conditionUpdate="desc_id='".$desc_id."'";
		$db->modify(SITE_DESC,$dataUpdate,$conditionUpdate);
		return 1;
		
	}
 
}


?>