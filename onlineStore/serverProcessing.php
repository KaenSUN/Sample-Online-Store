<?php session_start(); ?>
<?php
	if($_GET['action']=="load"){
		load($_GET['lastRecord']);
	}else if($_GET['action']=="add"){
		addToCart($_GET['itemID']);
		updateQty($_GET['itemID'],1);
	}else if($_GET['action']=="reloadCart"){
		reloadCart();
	}else if($_GET['action']=="unset"){
		init();
	}else if($_GET['action']=="update"){
		updateQty($_GET['itemID'],$_GET['Qty']);
	}
	
	function load($lastRecord){
		$conn=mysqli_connect('sophia.cs.hku.hk','wlchan','fGUXiGku') or die ('Failed to Connect '.mysqli_error($conn));
		mysqli_select_db($conn,'wlchan') or die ('Failed to Access DB'.mysqli_error($conn));
		$count_query='SELECT COUNT(*) FROM catalog';
		$total = mysqli_query($conn, $count_query) or die ('Failed to query '.mysqli_error($conn));
		if($lastRecord>=$total){
			$lastRecord=$total-2;
		}
		$query = 'select * from catalog limit '.$lastRecord.', 2';
		$result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));

		$json = array();
		while($row=mysqli_fetch_array($result)) {
			$json[]=array('itemID'=>$row['itemID'], 'itemName'=>$row['itemName'], 'itemDescription'=>$row['itemDescription'],
						  'itemImage'=>$row['itemImage'],'itemPrice'=>$row['itemPrice']);
		}
		print json_encode(array('Catalog'=>$json));
	}	
	
	function addToCart($itemID){
		/* var cart[]=array();
		$conn=mysqli_connect('sophia.cs.hku.hk','wlchan','fGUXiGku') or die ('Failed to Connect '.mysqli_error($conn));
		mysqli_select_db($conn,'wlchan') or die ('Failed to Access DB'.mysqli_error($conn));
		if (!isset($_SESSION['shoppingCart'])){
			$_SESSION['shoppingCart']= array();
		}
		if (!in_array($itemID,$_SESSION['shoppingCart'])) array_push($_SESSION['shoppingCart'], $itemID);
		
		foreach($_SESSION['shoppingCart'] as $id) {
			$query = 'select * from catalog where itemID= '.$id;
			$result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
			while($row=mysqli_fetch_array($result)) {
				$temp[]=array('itemID'=>$row['itemID'], 'itemName'=>$row['itemName'], 'itemDescription'=>$row['itemDescription'],'itemImage'=>$row['itemImage'],'itemPrice'=>$row['itemPrice']);
				echo $temp['itemID'];
			}
			$cart=array_unique(array_merge($cart,$temp));
		} 
		print json_encode(array('cart'=>$cart)); */ 
		if (!isset($_SESSION['shoppingCart'])){
			$_SESSION['shoppingCart']= array();
		}
		if (!in_array($itemID,$_SESSION['shoppingCart'])){
			array_push($_SESSION['shoppingCart'], $itemID);
			//echo "added item with id $itemID";
		}
	}
	
	function reloadCart(){
		//var json[]=array();
		if (isset($_SESSION['response'])) unset($_SESSION['response']);					//initialize session
		$_SESSION['response']=array();
		
		foreach($_SESSION['shoppingCart'] as $id) {
			$conn=mysqli_connect('sophia.cs.hku.hk','wlchan','fGUXiGku') or die ('Failed to Connect '.mysqli_error($conn));
			mysqli_select_db($conn,'wlchan') or die ('Failed to Access DB'.mysqli_error($conn));
			$query = 'select itemID, itemName, itemDescription, itemImage, itemPrice from catalog where itemID = '.$id;
			//echo "$query<br>";
			$result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
			while($row=mysqli_fetch_array($result)) {
				$obj=array('itemID'=>$row['itemID'], 'itemName'=>$row['itemName'], 'itemDescription'=>$row['itemDescription'],'itemImage'=>$row['itemImage'],'itemPrice'=>$row['itemPrice'], 'Qty'=>$_SESSION['currentQty'][$row['itemID']]);
				array_push($_SESSION['response'],$obj);
			}
		}
		//echo "$cart['itemID']";
		print json_encode(array('cart'=>$_SESSION['response']));
	}
	
	function updateQty($itemID,$Qty){
		if (!isset($_SESSION['currentQty'])){
			$_SESSION['currentQty']= array();
		}
		//if (!in_array($itemID,$_SESSION['currentQty'])){
			$_SESSION['currentQty'][$itemID]=$Qty;
			if($Qty==0){
				unset($_SESSION['currentQty'][$itemID]);
				if(($key = array_search($itemID, $_SESSION['shoppingCart'])) !== false) {
					unset($_SESSION['shoppingCart'][$key]);
				}
			}
		//}
		//print_r($_SESSION['currentQty']);
	}
	
	function init(){
		if (isset($_SESSION['response'])) unset($_SESSION['response']);
		if (isset($_SESSION['shoppingCart'])) unset($_SESSION['shoppingCart']);
		if (isset($_SESSION['currentQty'])) unset($_SESSION['currentQty']);
	}
?>