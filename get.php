<?php
include 'db.php';

if(isset($_GET["id"])) {
	$id = $_GET["id"];
	
	$sq = mysqli_query($conn, "SELECT * FROM gd$id ORDER BY id DESC LIMIT 1;");
	$dn = mysqli_num_rows($sq);

	if ($dn > 0) {
		while($row = mysqli_fetch_assoc($sq)) {
			$u1 = $row["y1"];
			$u2 = $row["y2"];
			$u3 = $row["y3"];
			$u4 = $row["y4"];
			//$time = $row["time"];
		}
	}
	
	echo $u1."-".$u2."-".$u3."-".$u4;
}
?>