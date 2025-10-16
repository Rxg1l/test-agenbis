<?php
require_once "../config/Database.php";
require_once "../models/Pemesanan.php";
require_once "../controllers/AuthController.php";

$database = new Database();
$db = $database->getConnection();
$auth = new AuthController($db);
$auth->requireAdmin();

$pemesanan = new Pemesanan($db);

// Handle status update
if(isset($_POST['update_status'])) {
    $pemesanan->id = $_POST['id'];
    $pemesanan->status_pembayaran = $_POST['status_pembayaran'];
    
    if($pemesanan->updateStatus()) {
        $success = "Status pemesanan berhasil diupdate";
    } else {
        $error = "Gagal mengupdate status pemesanan";
    }
}

// Get all bookings
$all_pemesanan = $pemesanan->readAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pemesanan - AgenBis</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <i class="fas fa-bus text-2xl text-purple-600 mr-3"></i>
                    <span class="text-xl font-bold text-gray-800">AgenBis Admin</span>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-gray-600 hover:text-purple-600">Dashboard</a>
                    <a href="bus.php" class="text-gray-600 hover:text-purple-600">Bus</a>
                    <a href="jadwal.php" class="text-gray-600 hover:text-purple-600">Jadwal</a>
                    <a href="rute.php" class="text-gray-600 hover:text-purple-600">Rute</a>
                    <a href="pemesanan.php" class="text-purple-600 font-medium">Pemesanan</a>
                    <a href="users.php" class="text-gray-600 hover:text-purple-600">Pengguna</a>
                    <a href="../logout.php"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Kelola Pemesanan</h1>
            <p class="text-gray-600">Kelola semua pemesanan tiket dari pengguna</p>
        </div>

        <!-- Notifications -->
        <?php if(isset($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?= $success ?>
        </div>
        <?php endif; ?>

        <?php if(isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <?= $error ?>
        </div>
        <?php endif; ?>

        <!-- Pemesanan Table -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Daftar Pemesanan</h2>
            </div>
            <div class="p-6">
                <?php if($all_pemesanan->rowCount() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kode Booking</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rute</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah Tiket</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while($row = $all_pemesanan->fetch()): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= $row['kode_booking'] ?></div>
                                    <div class="text-sm text-gray-500">
                                        <?= date('d M Y H:i', strtotime($row['waktu_pemesanan'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= $row['kota_asal'] ?> →
                                        <?= $row['kota_tujuan'] ?></div>
                                    <div class="text-sm text-gray-500">
                                        <?= date('H:i', strtotime($row['waktu_keberangkatan'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <?= date('d M Y', strtotime($row['waktu_keberangkatan'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= $row['jumlah_tiket'] ?> tiket</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp <?= number_format($row['total_harga'], 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <select name="status_pembayaran" onchange="this.form.submit()" class="text-xs border rounded px-2 py-1 <?= 
                                                        $row['status_pembayaran'] == 'Success' ? 'bg-green-100 text-green-800' : 
                                                        ($row['status_pembayaran'] == 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                        ($row['status_pembayaran'] == 'Failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))
                                                    ?>">
                                            <option value="Pending"
                                                <?= $row['status_pembayaran'] == 'Pending' ? 'selected' : '' ?>>Pending
                                            </option>
                                            <option value="Success"
                                                <?= $row['status_pembayaran'] == 'Success' ? 'selected' : '' ?>>Success
                                            </option>
                                            <option value="Failed"
                                                <?= $row['status_pembayaran'] == 'Failed' ? 'selected' : '' ?>>Failed
                                            </option>
                                            <option value="Expired"
                                                <?= $row['status_pembayaran'] == 'Expired' ? 'selected' : '' ?>>Expired
                                            </option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="../user/pemesanan_detail.php?id=<?= $row['id'] ?>" target="_blank"
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="?delete=<?= $row['id'] ?>" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Yakin ingin menghapus pemesanan ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-ticket-alt text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada pemesanan</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
            <?php
            // Reset pointer dan hitung statistik
            $all_pemesanan->execute();
            $stats = [
                'total' => 0,
                'pending' => 0,
                'success' => 0,
                'failed' => 0
            ];
            
            while($row = $all_pemesanan->fetch()) {
                $stats['total']++;
                $stats[strtolower($row['status_pembayaran'])]++;
            }
            ?>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-2xl font-bold text-purple-600"><?= $stats['total'] ?></div>
                <div class="text-sm text-gray-600">Total Pemesanan</div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-2xl font-bold text-yellow-600"><?= $stats['pending'] ?></div>
                <div class="text-sm text-gray-600">Pending</div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-2xl font-bold text-green-600"><?= $stats['success'] ?></div>
                <div class="text-sm text-gray-600">Success</div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-2xl font-bold text-red-600"><?= $stats['failed'] ?></div>
                <div class="text-sm text-gray-600">Failed</div>
            </div>
        </div>
    </div>

    <script>
    // Konfirmasi logout
    document.addEventListener('DOMContentLoaded', function() {
        const logoutLinks = document.querySelectorAll('a[href*="logout.php"]');
        logoutLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('Apakah Anda yakin ingin logout?')) {
                    e.preventDefault();
                }
            });
        });

        // Konfirmasi sebelum mengubah status
        const statusSelects = document.querySelectorAll('select[name="status_pembayaran"]');
        statusSelects.forEach(select => {
            select.addEventListener('change', function(e) {
                if (!confirm('Yakin ingin mengubah status pemesanan?')) {
                    e.preventDefault();
                    this.form.reset();
                }
            });
        });
    });
    </script>
</body>

</html>