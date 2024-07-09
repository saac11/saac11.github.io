<?php
session_start();
$connect = mysqli_connect("localhost", "root", "", "carro-compras");

if (isset($_POST["add_to_cart"])) {
	if (isset($_SESSION["shopping_cart"])) {
		$item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
		if (!in_array($_GET["id"], $item_array_id)) {
			$count = count($_SESSION["shopping_cart"]);
			$item_array = array(
				'item_id'			=>	$_GET["id"],
				'item_name'			=>	$_POST["hidden_name"],
				'item_price'		=>	$_POST["hidden_price"],
				'item_quantity'		=>	$_POST["quantity"]
			);
			$_SESSION["shopping_cart"][$count] = $item_array;
		} else {
			echo '<script>alert("Producto ya fue agregado")</script>';
		}
	} else {
		$item_array = array(
			'item_id'			=>	$_GET["id"],
			'item_name'			=>	$_POST["hidden_name"],
			'item_price'		=>	$_POST["hidden_price"],
			'item_quantity'		=>	$_POST["quantity"]
		);
		$_SESSION["shopping_cart"][0] = $item_array;
	}
}

if (isset($_GET["action"])) {
	if ($_GET["action"] == "delete") {
		foreach ($_SESSION["shopping_cart"] as $keys => $values) {
			if ($values["item_id"] == $_GET["id"]) {
				unset($_SESSION["shopping_cart"][$keys]);
				echo '<script>alert("Producto retirado")</script>';
				echo '<script>window.location="index.php"</script>';
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>ConfiguroWeb</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<body style="background:black;">
	<br />
	<div class="container">
		<br />
		<br />
		<br />
		<h3 align="center"><a href="https://www.configuroweb.com/" title="Para más desarrollos ConfiguroWeb" style="color:white;text-decoration:none;">Para más desarrollos ConfiguroWeb</a></h3><br />
		<br /><br />
		<?php
		$query = "SELECT * FROM tbl_product ORDER BY id ASC";
		$result = mysqli_query($connect, $query);
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {
		?>
				<div class="col-md-4">
					<form method="post" action="index.php?action=add&id=<?php echo $row["id"]; ?>">
						<div style="border:1px solid #333; background-color:grey; border-radius:5px; padding:16px;" align="center">
							<img src="images/<?php echo $row["image"]; ?>" class="img-responsive" /><br />

							<h4 class="text-white" style="color:white"><?php echo $row["name"]; ?></h4>

							<h4 class="text-danger">$ <?php echo $row["price"]; ?></h4>

							<input type="text" name="quantity" value="1" class="form-control" />

							<input type="hidden" name="hidden_name" value="<?php echo $row["name"]; ?>" />

							<input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>" />

							<input type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-warning" value="Agregar Producto" />

						</div>
					</form>
				</div>
		<?php
			}
		}
		?>
		<div style="clear:both"></div>
		<br />
		<h3 style="color:white">Información de la Orden</h3>
		<div class="table-responsive">
			<table class="table table-bordered">
				<tr>
					<th width="40%" style="color:white">Nombre del Producto</th>
					<th width="10%" style="color:white">Cantidad</th>
					<th width="20%" style="color:white">Precio</th>
					<th width="15%" style="color:white">Total</th>
					<th width="5%" style="color:white">Acción</th>
				</tr>
				<?php
				if (!empty($_SESSION["shopping_cart"])) {
					$total = 0;
					foreach ($_SESSION["shopping_cart"] as $keys => $values) {
				?>
						<tr>
							<td style="color:white"><?php echo $values["item_name"]; ?></td>
							<td style="color:white"><?php echo $values["item_quantity"]; ?></td>
							<td style="color:white">$ <?php echo $values["item_price"]; ?></td>
							<td style="color:white">$ <?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>
							<td style="color:white"><a href="index.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Quitar Producto</span></a></td>
						</tr>
					<?php
						$total = $total + ($values["item_quantity"] * $values["item_price"]);
					}
					?>
					<tr>
						<td colspan="3" align="right" style="color:white;">Total</td>
						<td align="right" style="color:white;">$ <?php echo number_format($total, 2); ?></td>
						<td></td>
					</tr>
				<?php
				}
				?>

			</table>
		</div>
	</div>
	</div>
	<br />
</body>

</html>

<?php
//Si ha utilizado una versión anterior de PHP, descomente esta función para eliminar el error.

/*function array_column($array, $column_name)
{
	$output = array();
	foreach($array as $keys => $values)
	{
		$output[] = $values[$column_name];
	}
	return $output;
}*/
?>