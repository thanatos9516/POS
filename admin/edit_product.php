<?php
	
	include('session.php');
	$id=$_GET['id'];
	
	$p=mysqli_query($conn,"select * from product where productid='$id'");
	$prow=mysqli_fetch_array($p);
	
	$name=$_POST['name'];
	$category=$_POST['category'];
	$supplier=$_POST['supplier'];
	$price=$_POST['price'];
	$description1=$_POST['description'];
    $description = str_replace("\n", "<br>", $description1);
	$tech1=$_POST['tech'];
    $tech = str_replace("\n", "<br>", $tech1);
	$qty=$_POST['qty'];
	
	$fileInfo = PATHINFO($_FILES["image"]["name"]);
	
	if (empty($_FILES["image"]["name"])){
		$location=$prow['photo'];
	}
	else{
		if ($fileInfo['extension'] == "jpg" OR $fileInfo['extension'] == "png") {
			$newFilename = $fileInfo['filename'] . "_" . time() . "." . $fileInfo['extension'];
			move_uploaded_file($_FILES["image"]["tmp_name"], "../upload/" . $newFilename);
			$location = "upload/" . $newFilename;
		}
		else{
			$location=$prow['photo'];
			?>
				<script>
					window.alert('Photo not updated. Please upload JPG or PNG photo only!');
				</script>
			<?php
		}
	}
	
	mysqli_query($conn,"update product set product_name='$name', supplierid='$supplier', categoryid='$category', product_price='$price', photo='$location', product_qty='$qty', description='$description', tech='$tech' where productid='$id'");
	
	if($qty!=$prow['product_qty']){
		mysqli_query($conn,"insert into inventory (userid,action,productid,quantity,inventory_date) values ('".$_SESSION['id']."','Update Quantity', '$id', '$qty', NOW())");
	}
	?>
		<script>
			window.alert('Product updated successfully!');
			window.history.back();
		</script>
	<?php

?>