<?php
	class Board extends DB_Connect{

		private $bName;

		private $bDate;

		public function __construct($dbo=NULL){

			parent::__construct($dbo);
			//保证从该系统获取的时间是首尔的当前时间
			date_default_timezone_set('Asia/Seoul');

			$this->bName = '家居存粮';

			$this->bDate = date('Y-m-d');

		}

		private function _loadItems($item_id=NULL){
			$sql = "SELECT
						item_id,item_name,item_volume,item_time,item_desc,item_location
					FROM items";
			if(!empty($item_id)){
				$sql.=" WHERE item_id =:id LIMIT 1";
			}
			if(empty($item_id)){
				$sql.= " ORDER BY item_location, item_time DESC";
			}
			

			try{
				$stmt = $this->dbo->prepare($sql);
				if(!empty($item_id)){
					$stmt->bindParam(":id",$item_id,PDO::PARAM_INT);
				}
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt->closeCursor();			
				$i = 0;
				$items = array();
				if(!empty($item_id)){
					try{	
							return new Item($results[0]);	
						}
					catch(Exception $e){
						die($e->getMessage());
					}
					
				}
				else{
					foreach($results as $item){
					//把返回的二维数组变成类的一维数组
						try{
							$items[$i] = new Item($item);	
							$i++;
						}
						catch(Exception $e){
							die($e->getMessage());
						}
					
					}
					return $items;
				}

			}
			catch(Exception $e){
				die($e->getMessage());
			}
		}

		public function buildBoard(){

			$html = "\n\t<h2>$this->bName</h2>";

			$items = $this->_loadItems();

			$volumes =count($items);

			$html .= "\n\t<h2>$this->bDate 物件总类：$volumes</h2>";

			$location_numbering = array(
			0 => "未定",
			1 => "冰箱上悬柜",
			2 => "厨房下橱柜",
			3 => "杂物间"
			);

			foreach ($items as $item) {
				# code...\
				$admin_edit = $this->_adminEntryOptions($item->id);
				$html .= "\n\t\t\t$admin_edit";
				$lables ="\n\t\t<ul class=location$item->location>" ;
				$html .= $lables . "\n\t\t\t<li>$item->name</li>";
				$html .= "\n\t\t\t<li>$item->volume</li>";
				$html .= "\n\t\t\t<li>$item->time</li>";
				$html .= "\n\t\t\t<li>$item->desc</li>";
				$current_location = $item->location;
				$html .= "\n\t\t\t<li>$location_numbering[$current_location]</li>";
				$html .="</ul>";
				
			}

			$admin = $this->_adminGeneralOptions();


			return $html.$admin;

		}

		public function showLocation($id){
			if(empty($id)) {return null;}

			$id=preg_replace('/[^0-9]/','', $_GET['location_id']);

			return "\n\t<img src=\"assets/pic/$id.png\">";
		}

		public function displayItem(){
			if(isset($_POST['item_id'])){
				$id=(int)$_POST['item_id'];
			}
			else{
				$id=NULL;
			}
			$submit="收粮入库";
			if(!empty($id)){
				$item = $this->_loadItems($id);
				if(!is_object($item)) {return NULL;}
				$submit="更新库藏";
			}
			else{
				$item=new Item();
			}

			$location_numbering = array(
			0 => "请选择..",
			1 => "冰箱上悬柜",
			2 => "厨房下橱柜",
			3 => "杂物间"
			);
			
			if(is_null($item->location)){
				$current_location = 0;
			}
			else{
				$current_location = $item->location;
			}
			return <<<FORM_MARKUP
			<form action="assets/inc/process.inc.php" method="post">
				<fieldset>
					<legend>{$submit}</legend>
					<label for="item_name">物件名称</lable>
					<input type="text" name="item_name" id="item_name" value="$item->name" />
					<label for="item_volume">物件数量</lable>
					<input type="text" name="item_volume" id="item_volume" value="$item->volume" />
					<label for="item_time">登记时间</lable>
					<input type="text" name="item_time" id="item_time" value="$item->time" />
					<label for="item_desc">物件描述</lable>
					<textarea name="item_desc" id="item_desc">$item->desc</textarea>
					<label for="item_location">存放地点</lable>
					<select name="item_location">
						<option selected="selected" value="$item->location">$location_numbering[$current_location]</option>
						<option value="1">冰箱上悬柜</option>
						<option value="2">厨房下橱柜</option>
						<option value="3">杂物间</option>
					</select>
					<input type="hidden" name="item_id" value="$item->id" />
					<input type="hidden" name="token" value="$_SESSION[token]" />
					<input type="hidden" name="action" value="item_edit" />
					<input type="submit" name="item_submit" value="$submit" />
					or <a href="./">cancel</a>
				</fieldset>
			</form>
FORM_MARKUP;
		}

		public function processItem(){
			if($_POST['action']!='item_edit'){
				return "The method processItem was accessed incorrectly";
			}

			$name = htmlentities($_POST['item_name'],ENT_QUOTES);
			$volume = htmlentities($_POST['item_volume'],ENT_QUOTES);
			$time = htmlentities($_POST['item_time'],ENT_QUOTES);
			$desc = htmlentities($_POST['item_desc'],ENT_QUOTES);
			$location = htmlentities($_POST['item_location'],ENT_QUOTES);

			if(empty($_POST['item_id'])){
				$sql = "INSERT INTO items
					(item_name,item_volume,item_time,item_desc,item_location)
					VALUES
					(:name,:volume,:time,:desc,:location)";
			}

			else{
				$id = (int)$_POST['item_id'];
				$sql = "UPDATE items
					SET
						item_name=:name,
						item_volume=:volume,
						item_time=:time,
						item_desc=:desc,
						item_location=:location 
					WHERE item_id=$id";
			}

			try{
				$stmt = $this->dbo->prepare($sql);
				$stmt->bindParam(":name",$name,PDO::PARAM_STR);
				$stmt->bindParam(":volume",$volume,PDO::PARAM_INT);
				$stmt->bindParam(":time",$time,PDO::PARAM_STR);
				$stmt->bindParam(":desc",$desc,PDO::PARAM_STR);
				$stmt->bindParam(":location",$location,PDO::PARAM_INT);
				$stmt->execute();
				$stmt->closeCursor();
				return TRUE;
			}
			catch(Exception $e){
				return $e->getMessage;
			}
		}

		public function confirmDelete($id){
			if(empty($id)){return NULL;}

			$id=preg_replace('/[^0-9]/','',$id);
			if(isset($_POST['confirm_delete']) && $_POST['token']==$_SESSION['token']){
				if($_POST['confirm_delete'] == "恩,确认了."){
					$sql = "DELETE FROM items
					WHERE item_id = :id LIMIT 1";
					try{
						$stmt = $this->dbo->prepare($sql);
						$stmt->bindParam(
							":id",
							$id,
							PDO::PARAM_INT);
						$stmt->execute();
						$stmt->closeCursor();
						header("Location: ./");
						return;
					}
					catch( Exception $e){
						return $e->getMessage();
					}
				}
				else{
					header("Location: ./");
					return;
				}
			}


			$item = $this->_loadItems($id);
			
			if(!is_object($item)){header("Location: ./");}
	

			return <<< CONFIRM_DELETE
			<form action="confirmDelete.php" method="post">
			<h2>真的不要"$item->name"了吧?</h2>
			<p>删了就<strong>不可恢复</strong>了哦.</p>
			<p>
			<input type="submit" name="confirm_delete" value="恩,确认了.">
			<input type="submit" name="confirm_delete" value="不小心,返回下">
			<input type="hidden" name="item_id" value="$item->id">
			<input type="hidden" name="token" value="$_SESSION[token]">
			</p>
			</form>
CONFIRM_DELETE;
		}



		private function _adminGeneralOptions(){
			if(isset($_SESSION['user'])){
			return <<<ADMIN_OPTIONS
			<a href="admin.php" class="admin">+ 收粮入库</a>
			<form action="assets/inc/process.inc.php" method="post">
			<div>
			<input type="submit" value="退出登录，只看看" class="admin">
			<input type="hidden" name="token" value="$_SESSION[token]">
			<input type="hidden" name="action" value="user_logout">
			</div>
			</form>
ADMIN_OPTIONS;
			}
			else{
				return <<<ADMIN_OPTIONS
				<a href="login.php" class="admin">登录成为管理者</a>
ADMIN_OPTIONS;
			}
		}

		private function _adminEntryOptions($id){
			if(isset($_SESSION['user'])){
			return <<<ADMIN_OPTIONS
			<div class="admin-options">
				<form action="admin.php" method="post">
					<p>
						<input type="submit" name="edit" value="更新" >
						<input type="hidden" name="item_id" value="$id" >
					</p>
				</form>
				<form action="confirmDelete.php" method="post">
					<p>
						<input type="submit" name="delete" value="删除" >
						<input type="hidden" name="item_id" value="$id" >
					</p>
				</form>
			</div>
ADMIN_OPTIONS;
			}
			else{
				return NULL;
			}
		
		}

	}
?>
