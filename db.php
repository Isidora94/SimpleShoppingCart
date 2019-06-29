<?php 

$conn = mysqli_connect('localhost', 'root', '', 'test');

$query = 'select * from product';
$res = $conn->query($query);
return $res;

