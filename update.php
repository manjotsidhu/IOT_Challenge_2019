<html>
<body>
<?php
include 'db.php';

if(isset($_GET["u"])) {
	$value = $_GET["u"];
	// Format to update sensor value : X-I-U..
	// where X is the garbage dump id, I is the ultrasonic sensor number and U is the ultrasonic sensor value.
	$results = explode("-", $value);
	$x = $results[0];
	$i = $results[1];
	$u = $results[2];
	
	$sq = mysqli_query($conn, "SELECT * FROM gd$x ORDER BY id DESC LIMIT 1;");
	$dn = mysqli_num_rows($sq);
	
	if ($dn > 0) {
		while($row = mysqli_fetch_assoc($sq)) {
			switch($i) {
				case 1: $u1 = $u; $u2 = $row["y2"]; $u3 = $row["y3"]; $u4 = $row["y4"]; break;
				case 2: $u1 = $row["y1"]; $u2 = $u; $u3 = $row["y3"]; $u4 = $row["y4"]; break;
				case 3: $u1 = $row["y1"]; $u3 = $u; $u2 = $row["y2"]; $u4 = $row["y4"]; break;
				case 4: $u1 = $row["y1"]; $u4 = $u; $u3 = $row["y3"]; $u2 = $row["y2"]; break;
			}
		}
		$sresult = "INSERT INTO gd$x (y1, y2, y3, y4) VALUES ('$u1', '$u2', '$u3', '$u4')";
	
	} else {
		$sresult = "INSERT INTO gd$x (y$i) VALUES ('$u')";	
	}
	
	if(mysqli_query($conn, $sresult)) {
		echo "200";
	} else {
		echo "error";
	}
	
} else if(isset($_GET["aif"])) {
	
	$value = $_GET["aif"];
	// Format to add a new garbage dump : M-N-O-P-Q..
	// where M is the id of garbage dump id, N is the latitude, O is the longitude, P is the height, Q is the capacity.
	$results = explode("-", $value);
	$m = $results[0];
	$n = $results[1];
	$o = $results[2];
	$p = $results[3];
	$q = $results[4];
	
	$found=0;
	$check = "SELECT * from dumps WHERE id = '$m'";
	$ddd = mysqli_query($conn, $check);
	while($row = mysqli_fetch_array($ddd)){
		$found=1;
	}//end while loop

	if ($found==0) {
		$create = "CREATE TABLE gd$m LIKE sample";
		$ok = 0;
		
		if(mysqli_query($conn, $create)) {
			$ok = 1;
		}
		
		$sfresult = "INSERT INTO dumps (id, lat, longi, height, capacity) VALUES ('$m', '$n', '$o', '$p', '$q')";
		
		if(mysqli_query($conn, $sfresult) && $ok == 1) {
			echo "200";
		} else {
			echo "error";
		}
	}
	
} else {
	
	
}

$dump_query = mysqli_query($conn, "SELECT * FROM dumps");
$garbage_dumps = mysqli_num_rows($dump_query);

if ($garbage_dumps > 0) {
	$i = 0;
	while($row = mysqli_fetch_assoc($dump_query)) {
    	$dumpsId[$i] = $row["id"];
    	$dumpsLat[$i] = $row["lat"];
    	$dumpsLong[$i] = $row["longi"];
    	$dumpsHeight[$i] = $row["height"];
    	$dumpsCap[$i] = $row["capacity"];
		$i++;
	}
}


?>
</body>
</html>