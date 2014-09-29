<!DOCTYPE html>
<html>
<head>
	<title>Zappos Challenge</title>
<style type="text/css">

body {
	margin:0;
	background: #c0c0c0;
	font-family: 'Arial', sans-serif;
}

form {
	width:300px;
	margin:30px auto 0;
	background:white;
	padding: 20px 50px;
}

label {
	display: block;
	font-size:12px;
	font-weight: bold;
}

input {
	margin-bottom:15px;
	width:100%;
}

input.submit {
	width:50%;
	margin:20px auto;
	display: block;
}

</style>
</head>
<body>

<form action="products.php" method="get">
	<label for="numProducts">Number of Products</label>
	<input type="text" name="numProducts" id="numProducts" />

	<label for="dollarAmount">Desired Dollar Amount</label>
	<input type="text" name="dollarAmount" id="dollarAmount" />

	<input type="submit" class="submit" value="Find Combinations" />

</form>

</body>
</html>