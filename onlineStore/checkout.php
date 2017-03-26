<html>
<head>
	<title>Check out Page</title>
	<style>
		table, th, td {
			border: 1px solid black;
		}
	</style>
</head>
<body>
<h1> You are checking out </h1>

<?php session_start(); ?>
<?php
	$total=0;
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
			//$total=0;
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
		echo "<form action=\"confirmation.php\">";
		echo "Name: <input type=\"text\" name=\"Name\" required><br>";
		echo "Email: <input type=\"text\" name=\"Email\" required><br>";
		echo "Payment Method: <select name=\"PaymentMethod\"><br>";
		echo "<option value=\"Credit Card\" selected=\"yes\">Credit Card";
		echo "<option value=\"Cash\">Cash";
		echo "<option value=\"Redeem Point\">Redeem Point";
		echo "</select>";
		echo "<input type=\"submit\" value=\"Confirm\"/>";
		echo "</form>";
	}else{
		echo "<h2><u>Nothing</u></h2>";
	}
?>
</body>
</html>