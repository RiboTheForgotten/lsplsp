<?php 
$conn = new mysqli("localhost", "root" ,"", "nyobalsp");

if($conn->connect_error) {
    die("Koneksi Gagal");
}
// else {
//     echo "Koneksi Berhasil";
// }
?>