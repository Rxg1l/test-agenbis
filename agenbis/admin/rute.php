<?php
require_once "../config/Database.php";
require_once "../models/Rute.php";
require_once "../controllers/AuthController.php";

$database = new Database();
$db = $database->getConnection();
$auth = new AuthController($db);
$auth->requireAdmin();

$rute = new Rute($db);

// Handle form actions
if($_POST) {
    if(isset($_POST['create'])) {
        $rute->kota_asal = $_POST['kota_asal'];
        $rute->kota_tujuan = $_POST['kota_tujuan'];
        $rute->jarak_km = $_POST['jarak_km'];
        $rute->waktu_tempuh_jam = $_POST['waktu_tempuh_jam'];
        
        if($rute->create()) {
            $success = "Rute berhasil ditambahkan";
        } else {
            $error = "Gagal menambahkan rute";
        }
    }
    
    if(isset($_POST['update'])) {
        $rute->id = $_POST['id'];
        $rute->kota_asal = $_POST['kota_asal'];
        $rute->kota_tujuan = $_POST['kota_tujuan'];
        $rute->jarak_km = $_POST['jarak_km'];
        $rute->waktu_tempuh_jam = $_POST['waktu_tempuh_jam'];
        
        if($rute->update()) {
            $success = "Rute berhasil diupdate";
        } else {
            $error = "Gagal mengupdate rute";
        }
    }
}

if(isset($_GET['delete'])) {
    $rute->id = $_GET['delete'];
    if($rute->delete()) {
        $success = "Rute berhasil dihapus";
    } else {
        $error = "Gagal menghapus rute";
    }
}

$all_rute = $rute->readAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Rute - AgenBis</title>
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
                    <a href="rute.php" class="text-purple-600 font-medium">Rute</a>
                    <a href="pemesanan.php" class="text-gray-600 hover:text-purple-600">Pemesanan</a>
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
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Kelola Data Rute</h1>
            <button onclick="openModal('create')"
                class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                <i class="fas fa-plus mr-2"></i>Tambah Rute
            </button>
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

        <!-- Rute Table -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Daftar Rute</h2>
            </div>
            <div class="p-6">
                <?php if($all_rute->rowCount() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rute</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jarak</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu Tempuh</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dibuat</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while($row = $all_rute->fetch()): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= $row['kota_asal'] ?> →
                                        <?= $row['kota_tujuan'] ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= $row['jarak_km'] ?> km</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= $row['waktu_tempuh_jam'] ?> jam</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <?= date('d M Y', strtotime($row['created_at'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="editRute(<?= htmlspecialchars(json_encode($row)) ?>)"
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <a href="?delete=<?= $row['id'] ?>" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Yakin ingin menghapus rute ini?')">
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
                    <i class="fas fa-route text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada data rute</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="ruteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 id="modalTitle" class="text-lg font-medium text-gray-900 mb-4">Tambah Rute Baru</h3>

                <form id="ruteForm" method="POST">
                    <input type="hidden" id="ruteId" name="id">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kota Asal</label>
                            <input type="text" id="kota_asal" name="kota_asal" required
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Nama kota asal">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kota Tujuan</label>
                            <input type="text" id="kota_tujuan" name="kota_tujuan" required
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Nama kota tujuan">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jarak (km)</label>
                            <input type="number" id="jarak_km" name="jarak_km" required min="1" step="0.1"
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Jarak dalam kilometer">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Waktu Tempuh (jam)</label>
                            <input type="number" id="waktu_tempuh_jam" name="waktu_tempuh_jam" required min="0.1"
                                step="0.1"
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Waktu tempuh dalam jam">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                            Batal
                        </button>
                        <button type="submit" id="submitBtn" name="create"
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function openModal(action) {
        const modal = document.getElementById('ruteModal');
        const title = document.getElementById('modalTitle');
        const form = document.getElementById('ruteForm');
        const submitBtn = document.getElementById('submitBtn');

        if (action === 'create') {
            title.textContent = 'Tambah Rute Baru';
            form.reset();
            submitBtn.name = 'create';
            submitBtn.textContent = 'Simpan';
            document.getElementById('ruteId').value = '';
        }

        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('ruteModal').classList.add('hidden');
    }

    function editRute(rute) {
        openModal('edit');
        document.getElementById('modalTitle').textContent = 'Edit Rute';
        document.getElementById('ruteId').value = rute.id;
        document.getElementById('kota_asal').value = rute.kota_asal;
        document.getElementById('kota_tujuan').value = rute.kota_tujuan;
        document.getElementById('jarak_km').value = rute.jarak_km;
        document.getElementById('waktu_tempuh_jam').value = rute.waktu_tempuh_jam;

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.name = 'update';
        submitBtn.textContent = 'Update';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('ruteModal');
        if (event.target === modal) {
            closeModal();
        }
    }

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
    });
    </script>
</body>

</html>