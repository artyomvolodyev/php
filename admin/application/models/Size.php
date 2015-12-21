<?php
__autoloadDB('Db');
class Size extends Db
{
	public function saveSize($dataForm, $sno=0)
	{
		global $mySession;
		$db=new Db();

		$dataUpdate['size'] = $dataForm['sizes'];
		$dataUpdate['size_inch'] = $dataForm['ininches'];

        $res = false;
        if($sno && (int)$sno > 0){
            $condition = "sizeid='".(int)$sno."'";
            $res = $db->modify(TSHIRT_SIZE, $dataUpdate, $condition);
        }else{
            $res = $db->save(TSHIRT_SIZE, $dataUpdate);
        }

		return $res;
	}

    public function removeSize($sno){
        $db=new Db();
        $res = false;
        if($sno && (int)$sno > 0){
            error_log('removeSize sno: '.$sno);
            $condition = "sizeid='".(int)$sno."'";
            $res = $db->delete(TSHIRT_SIZE, $condition);
        }
        return $res;
    }
}
?>