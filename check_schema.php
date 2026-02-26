<?php
include "login_app/database.php";
$res = mysqli_query($conn, "SHOW TABLES");
echo "TABLES:\n";
while($r = mysqli_fetch_array($res)) {
    echo $r[0]."\n";
}
echo "\nCOLUMNS IN users:\n";
$res = mysqli_query($conn, "SHOW COLUMNS FROM users");
if($res){
    while($r = mysqli_assoc($res)) { echo json_encode($r)."\n"; }
} else {
    echo mysqli_error($conn);
}

echo "\nCOLUMNS IN guru:\n";
$res2 = mysqli_query($conn, "SHOW COLUMNS FROM guru");
if($res2){
    while($r = mysqli_fetch_assoc($res2)) { echo json_encode($r)."\n"; }
} else {
    echo mysqli_error($conn);
}
?>
