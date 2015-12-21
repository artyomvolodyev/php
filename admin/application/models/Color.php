<?php
__autoloadDB('Db');
class Color extends Db
{
  
  public function SaveColor($dataForm)
	{
		global $mySession;
		$db=new Db();


		$chkQry=$db->runQuery("select * from ".COLOR." where color_code='".$dataForm['color_code']."'");
		if($chkQry!="" and count($chkQry)>0)
		{
		  return 0;	
		}
		else
		{
				$dataInsert['color_name']=$dataForm['colorname'];
				$dataInsert['color_code']=$dataForm['colorcode'];
				$dataInsert['color_status']=$dataForm['colorstatus'];
				
				$db->save(COLOR,$dataInsert);
				return 1;	

	}
    }
}
