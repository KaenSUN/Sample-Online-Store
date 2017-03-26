<html>
<head>
	<title>Purchase Success</title>
	<style>
		table, th, td {
			border: 1px solid black;
		}
	</style>
</head>
<body>


<?php session_start(); ?>
<?php
	$total=0;
	echo "<h1>Dear ".$_GET['Name'].", You have successfully purchase: </h1>";
	if(isset($_SESSION['shoppingCart']) && isset($_SESSION['currentQty'])){
		echo "<table>";
		echo "<thead>
				<th>Name</th>
				<th>Description</th>
				<th>Image</th>
				<th>Individual price</th>
				<th>Quantity</th>
				<th>Subtotal</th>
			  </thead>";
		for($i=0;$i<count($_SESSION['shoppingCart']);$i++){
			$productID=$_SESSION['shoppingCart'][$i];
			$number=$_SESSION['currentQty'][$productID];
			
			if($number!=0){
				$conn=mysqli_connect('sophia.cs.hku.hk','wlchan','fGUXiGku') or die ('Failed to Connect '.mysqli_error($conn));
				mysqli_select_db($conn,'wlchan') or die ('Failed to Access DB'.mysqli_error($conn));
				$query = 'select itemID, itemName, itemDescription, itemImage, itemPrice from catalog where itemID = '.$productID;
				$result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
				while($row=mysqli_fetch_array($result)) {
					$subtotal=$number*$row['itemPrice'];
					$total=$total+$subtotal;
					//echo $subtotal;
					//$obj=array('itemID'=>$row['itemID'], 'itemName'=>$row['itemName'], 'itemDescription'=>$row['itemDescription'],'itemImage'=>$row['itemImage'],'itemPrice'=>$row['itemPrice']);
					/*echo "<tr>
								<td>$row['itemName']</td>
								<td>$row['itemDescription']</td>
								<td><img src=\"$row['itemImage']\"/></td>
								<td>$row['itemPrice']</td>
								<td>$number</td>
								<td>$subtotal</td>
							</tr>";*/
					echo "<tr>";
					echo "<td>".$row['itemName']."</td>";
					echo "<td>".$row['itemDescription']."</td>";
					echo "<td>"."<img src=\"".$row['itemImage']."\" width=\"160\" height=\"130\"/>"."</td>";
					echo "<td>$".$row['itemPrice']."USD</td>";
					echo "<td>".$number."</td>";
					echo "<td>$".$subtotal."USD</td>";
					echo "</tr>";
				}
			}
		}
		echo "<tfoot><tr><td></td><td></td><td></td><td></td><td>Total</td><td>$".$total."USD</td></tr></tfoot>";
		echo "</table>";
		//echo "<h2>Payment Information</h2>"
		echo "<h1>by ".$_GET['PaymentMethod']."</h1>";
		echo "<h1>We may contact you via ".$_GET['Email']."</h1>";
		echo "<p>Thank you for your purchase.</p>";
		
	}else{
		echo "<h2><u>Nothing</u></h2>";
	}
?>
</body>
</html>