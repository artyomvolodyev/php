<?php
__autoloadDB('Db');
class Upload extends Db
{
  
  public function SaveImage($dataForm)
	{//echo "dafds"; die;	
		global $mySession;
		$db=new Db();
		$sql="select * from ".ADMINPOST." where image='".$dataForm['image']."'"; 
		$chkQry=$db->runQuery($sql);
		if($chkQry!="" and count($chkQry)>0)
		{
		return 0;
		}
		else
		{
				$dataInsert['image']=$dataForm['image'];
				$db->save(ADMINPOST,$dataInsert);
				return 1;	

		}
	}
	
	
}
