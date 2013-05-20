<?
class thutucModel{
	
	static function save($id,$params){
		$parameters  = array();
		$parameters[] = $params["TEN"];
		$parameters[] = $params["ACTIVE"];
		$sql = "";
		if($id > 0){
			//cap nhat
			$sql = " update thutuc set TEN = ? , ACTIVE = ?  where ID_THUTUC = ? ";
			
			$parameters[] = $id;

		}else{
			//them moi
			$sql = " insert into thutuc (TEN,ACTIVE) values (?,?) ";
		}
		
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->prepare($sql);
			$stm->execute($parameters);
			return 1;
		}catch(Exception $ex){
			return 0;
		}

	}

	static function getLastInsertId(){
		$sql = " select max(ID_THUTUC) as last_id from thutuc ";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql);
			$re = $qr->fetch();
			return $re["last_id"];
		}catch(Exception $ex){
			return -1;
		}
	
	}

	static function saveloaihoso($id,$id_loaihoso,$id_loaihoso_old){
		
		$parameters  = array();
		
		$sql = "";
		if($id_loaihoso_old > 0){
			//cap nhat
			$sql = " update fk_thutucs_loaihoso set ID_THUTUC = ? , ID_LOAIHOSO = ?  where ID_THUTUC = ? and  ID_LOAIHOSO = ?";
			
			$parameters[] = $id;
			$parameters[] = $id_loaihoso;
			$parameters[] = $id;
			$parameters[] = $id_loaihoso_old;

		}else{
			//them moi
			$sql = " insert into fk_thutucs_loaihoso (ID_THUTUC,ID_LOAIHOSO) values (?,?) ";
			//echo $sql ; exit;
			$parameters[] = $id;
			$parameters[] = $id_loaihoso;
		}
		
		//try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->prepare($sql);
			$stm->execute($parameters);
			return 1;
		//}catch(Exception $ex){
			return 0;
		//}

	}
	
	static function getAll(){
	
		$sql = "select tt.* , lhs.TEN as TENLOAIHOSO , fk.ID_LOAIHOSO from thutuc tt
		inner join fk_thutucs_loaihoso fk on  tt.ID_THUTUC = fk.ID_THUTUC
		inner join loaihoso lhs on fk.ID_LOAIHOSO = lhs.ID_LOAIHOSO
		";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql);
			return $qr->fetchAll();
		}catch(Exception $ex){
			return array();
		}
	}

	static function getAllWithFilter($params){
	
		$parameter = array();
		$where = " where (1=1) ";
		if($params["ID_LOAIHOSO"]){
			$where .= "   and fk.ID_LOAIHOSO = ? ";
			$parameter[] = $params["ID_LOAIHOSO"];
		}
		$sql = "select tt.* , lhs.TEN as TENLOAIHOSO , fk.ID_LOAIHOSO from thutuc tt
		inner join fk_thutucs_loaihoso fk on  tt.ID_THUTUC = fk.ID_THUTUC
		inner join loaihoso lhs on fk.ID_LOAIHOSO = lhs.ID_LOAIHOSO
		$where
		";
		//try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,$parameter);
			return $qr->fetchAll();
		//}catch(Exception $ex){
			return array();
		//}
	}

	static function getById($id){
		$sql = "select tt.*, fk.ID_LOAIHOSO from thutuc tt
		inner join fk_thutucs_loaihoso fk on  tt.ID_THUTUC = fk.ID_THUTUC
		where tt.ID_THUTUC=? ";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$qr = $dbAdapter->query($sql,array($id));
			return $qr->fetch();
		}catch(Exception $ex){
			return array();
		}
	}

	static function deleteById($id){
		$sql = " delete from thutuc where ID_THUTUC=?  ";
		try{
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$stm = $dbAdapter->prepare($sql);
			$stm->execute(array($id));
			return 1;
		}catch(Exception $ex){
			return 0;
		}
	}

	static function toCombo($id_sel){
		$data_lv = linhvucModel::getAll();
		echo "<option value=0> -- Chọn một thủ tục -- </option>";
		foreach($data_lv as $item){
			echo "<option value='".$item["ID_THUTUC"]."' " . ($item["ID_THUTUC"]==$id_sel?"selected":"")  .  ">".$item["TEN"]."</option>";	
		}
	}
}