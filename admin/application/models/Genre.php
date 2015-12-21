<?php
__autoloadDB('Db');
class Genre extends Db
{
  
  public function SaveGenre($dataForm)
	{
		global $mySession;
		$db=new Db();


		$chkQry=$db->runQuery("select * from ".GENRE." where genre_name='".$dataForm['genrename']."'");
		if($chkQry!="" and count($chkQry)>0)
		{
		  return 0;	
		}
		else
		{
				$dataInsert['genre_name']=$dataForm['genrename'];
				$dataInsert['genre_status']=$dataForm['genrestatus'];
				
				$db->save(GENRE,$dataInsert);
				return 1;	

	}
    }
}
