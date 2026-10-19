<?php

include "../connection.php";

$id = $_GET['id'];

$sql = "DELETE FROM detail_peminjaman
        WHERE id_detail='$id'";

$con->query($sql);

header("Location: tampil.php");
exit;

?>