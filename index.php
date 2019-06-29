<?php 
session_start();

require('./db.php');

if (isset($_POST['add_to_cart'])) {
	if (isset($_SESSION['shopping_cart'])) {

		$item_array_id = array_column($_SESSION['shopping_cart'], 'item_id');
		if (!in_array($_GET['id'], $item_array_id)) {
			
			$count = count($_SESSION['shopping_cart']);
			$item_array = array(
				'item_id' => $_GET['id'],
				'item_name' => $_POST['hidden_name'],
				'item_price' => $_POST['hidden_price'],
				'item_quantity' => $_POST['quantity']
			);
			$_SESSION['shopping_cart'][$count] = $item_array;

		} else {
			header('Location: http://localhost//isidoranikolic/simpleCart/index.php?error=You alredy added this product!');
		}

	} else {

		$item_array = array(
			'item_id' => $_GET['id'],
			'item_name' => $_POST['hidden_name'],
			'item_price' => $_POST['hidden_price'],
			'item_quantity' => $_POST['quantity']
		);
		$_SESSION['shopping_cart'][0] = $item_array;

	}
}

if (isset($_GET['action'])) {
	if ($_GET['action'] == 'delete') {
		foreach ($_SESSION['shopping_cart'] as $keys => $values) {
			if ($values['item_id'] == $_GET['id']) {
				unset($_SESSION['shopping_cart'][$keys]);
				header('Location: http://localhost//isidoranikolic/simpleCart/index.php');
			}
		}
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="./css/main.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
	</head>
	<body>
		<br>
		<div class="container-fluid">
			<?php foreach($res as $product): ?>
				<div class="col-md-4 shadow-lg p-3 mb-5 bg-white rounded">
					<form method="post" action="index.php?action=add&id=<?php echo $product['id']; ?>">
						<div>
							<img src="<?php echo $product['image_path']; ?>" alt="product_picture">
							<div class="overlay">
								<div class="text"><?php echo $product['description']; ?></div>
							</div>
							<h4><?php echo ucfirst($product['name']); ?></h4>
							<h4 class="text-danger"><?php echo '$'.number_format($product['price'], 2); ?></h4>
							<input type="text" name="quantity" class="form-control" value="1">
							<small>Enter the desired quantity</small>
							<input type="hidden" name="hidden_name" value="<?php echo $product['name']; ?>">
							<input type="hidden" name="hidden_price" value="<?php echo $product['price']; ?>">
							<button type="submit" name="add_to_cart" class="btn btn-dark">Shop Now</button>
						</div>
					</form>
				</div>
			<?php endforeach; ?>

			<?php if(isset($_GET['error'])): ?>
				<p class="error animated bounceInLeft fast"><?php echo $_GET['error']; ?></p>
			<?php endif; ?>

			<br>
			<h3>Order details</h3>
			<div class="table-responsive">
				<table class="table table-bordered shadow-lg p-3 mb-5 bg-white rounded">
					<thead class="thead-dark">
						<tr>
							<th width="40%">Item name</th>
							<th width="10%">Quantity</th>
							<th width="20%">Price</th>
							<th width="15%">Total</th>
							<th width="5%">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($_SESSION['shopping_cart'])) : ?>
							<?php $total = 0; ?>
							<?php foreach ($_SESSION['shopping_cart'] as $keys => $values): ?>  
								<tr>
									<td style="font-weight: bold;"><?php echo $values['item_name']; ?></td>
									<td><?php echo $values['item_quantity']; ?></td>
									<td><?php echo $values['item_price']; ?></td>
									<td align="right"><?php echo number_format($values['item_quantity'] * $values['item_price'], 2); ?></td>
									<td><a href="index.php?action=delete&id=<?php echo $values['item_id']; ?>"><span class="text-danger">Remove</span></a></td>
								</tr>
								<?php	$total = $total + ($values['item_quantity'] * $values['item_price']); ?>
							<?php endforeach; ?>
							<tr>
								<td colspan="3" align="right">Total</td>
								<td align="right">$<?php echo number_format($total, 2); ?></td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<br>
		</div>
	</body>
</html>