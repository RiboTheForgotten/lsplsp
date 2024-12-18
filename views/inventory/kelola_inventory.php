<?php require_once '../../system/init.php'; ?>

<?php require_once '../layout/header.php'; ?>

<?php

$sql = "SELECT inventory.*,
        vendor.nama_vendor,
        storage_unit.nama_gudang,
        storage_unit.lokasi_gudang
        FROM inventory 
        JOIN vendor ON vendor.id_vendor = inventory.id_vendor
        JOIN storage_unit ON storage_unit.id_gudang = inventory.id_gudang
        ORDER BY updated_at DESC";

$stmt = $conn->prepare($sql);

if(!$stmt->execute()) {
    die("Gagal mengambil data storage unit");
}

$result = $stmt->get_result();

// Check if there are any items with zero or low stock
$low_stock_query = "SELECT COUNT(*) as low_stock_count FROM inventory WHERE kuantitas <= 0";
$low_stock_stmt = $conn->prepare($low_stock_query);
$low_stock_stmt->execute();
$low_stock_result = $low_stock_stmt->get_result()->fetch_assoc();
$low_stock_count = $low_stock_result['low_stock_count'];
?>

<?php if($low_stock_count > 0): ?>
<div class="container-fluid mt-3">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Peringatan!</strong> Terdapat <?= $low_stock_count ?> barang dengan stok habis.
    </div>
</div>
<?php endif; ?>

<section class="mt-3" id="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>Daftar Barang</h3>                        
                        <a href="tambah_barang.php" class="btn btn-primary">Tambah Barang</a>                        
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jenis Barang</th>
                                    <th>Kuantitas</th>                                    
                                    <th>Nama Vendor</th>                                    
                                    <th>Nama Gudang</th>                                    
                                    <th>Lokasi Gudang</th>                                    
                                    <th>Aksi</th>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php while($row = $result->fetch_assoc()) : ?>
                                    <tr class="<?= $row['kuantitas'] <= 0 ? 'table-danger' : '' ?>">
                                        <td><?= $i ?></td>
                                        <td><?= $row['nama_barang'] ?></td>
                                        <td><?= $row['jenis_barang'] ?></td>
                                        <td><?= $row['kuantitas'] ?></td>
                                        <td><?= $row['nama_vendor'] ?></td>
                                        <td><?= $row['nama_gudang'] ?></td>
                                        <td><?= $row['lokasi_gudang'] ?></td>                                        
                                        <td>
                                            <a href="update_barang.php?id_barang=<?= $row['id_barang'] ?>" class="btn btn-success">Update</a>
                                            <a href="../../system/crud/inventory.php?act=delete&id_barang=<?= $row['id_barang'] ?>" class="btn btn-danger">Hapus</a>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once '../layout/footer.php'; ?>

</document_content>