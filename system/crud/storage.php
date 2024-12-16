<?php
require_once '../../koneksi.php';

if(isset($_POST['act']) && !empty($_POST['act'])) {
    $act = $_POST['act'];
} elseif(isset($_GET['act']) && !empty($_GET['act'])) {
    $act = $_GET['act'];
} else {
    die("Act tidak ditemukan");
}

if($act == 'insert') {
    insertData($conn);
} elseif($act == 'update') {
    updateData($conn);
} elseif($act == 'delete') {
    deleteData($conn);
} else {
    die("Act tidak sesuai");
}

function insertData($conn) {
    try{

        if(!isset($_POST['nama_gudang']) || empty($_POST['nama_gudang'])) {
            throw new Exception("Nama Gudang tidak boleh kosong");            
        }

        if(!isset($_POST['lokasi_gudang']) || empty($_POST['lokasi_gudang'])) {
            throw new Exception("Lokasi Gudang tidak boleh kosong");            
        }        

        $nama_gudang = $_POST['nama_gudang'];
        $lokasi_gudang = $_POST['lokasi_gudang'];        

        $sql = "INSERT INTO storage_unit
                (nama_gudang, lokasi_gudang)
                VALUES
                (?, ?)
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $nama_gudang, $lokasi_gudang);

        if(!$stmt->execute()) {
            throw new Exception("Gagal eksekusi kueri");            
        } else {
            echo "
                <script>
                    alert('Berhasil Insert Data');
                    window.location.href = '../../views/storage/kelola_storage.php';
                </script>
            ";
        }

    }catch(Exception $e) {
        echo "Error :". $e->getMessage();
    }
}

function updateData($conn) {
    try{

        if(!isset($_POST['id_gudang']) || empty($_POST['id_gudang'])) {
            throw new Exception("ID gudang tidak ditemukan");            
        }

        $id_gudang = $_POST['id_gudang'];

        if(!isset($_POST['nama_gudang']) || empty($_POST['nama_gudang'])) {
            throw new Exception("Nama Gudang tidak boleh kosong");            
        }

        if(!isset($_POST['lokasi_gudang']) || empty($_POST['lokasi_gudang'])) {
            throw new Exception("Lokasi Gudang tidak boleh kosong");            
        }        

        $nama_gudang = $_POST['nama_gudang'];
        $lokasi_gudang = $_POST['lokasi_gudang'];        

        $sql = "UPDATE storage_unit SET
                nama_gudang = ?, lokasi_gudang = ?
                WHERE id_gudang = ?                
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $nama_gudang, $lokasi_gudang, $id_gudang);

        if(!$stmt->execute()) {
            throw new Exception("Gagal eksekusi kueri");            
        } else {
            echo "
                <script>
                    alert('Berhasil Update Data');
                    window.location.href = '../../views/storage/kelola_storage.php';
                </script>
            ";
        }

    }catch(Exception $e) {
        echo "Error :". $e->getMessage();
    }
}

function deleteData($conn) {
    try{

        if(!isset($_GET['id_gudang']) || empty($_GET['id_gudang'])) {
            throw new Exception("ID Vendor tidak ditemukan");            
        }

        $id_gudang = $_GET['id_gudang'];        

        $sql = "DELETE FROM storage_unit WHERE id_gudang = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id_gudang);

        if(!$stmt->execute()) {
            throw new Exception("Gagal eksekusi kueri");            
        } else {
            echo "
                <script>
                    alert('Berhasil Hapus Data');
                    window.location.href = '../../views/storage/kelola_storage.php';
                </script>
            ";
        }

    }catch(Exception $e) {
        if(strpos($e->getMessage(), "a foreign key constraint fails") !== false) {
            echo "
                <script>
                    alert('Gagal Hapus Data, data ini terkait dengan data lain');
                    window.location.href = '../../views/storage/kelola_storage.php';
                </script>
            ";
        } else {
            echo "Error :". $e->getMessage();
        }
    }
}