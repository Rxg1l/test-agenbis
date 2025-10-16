<?php
require_once "../config/Database.php";
require_once "../models/User.php";
require_once "../controllers/AuthController.php";

$database = new Database();
$db = $database->getConnection();
$auth = new AuthController($db);
$auth->requireAdmin();

$user = new User($db);

// Handle user actions
if(isset($_POST['update_role'])) {
    $user->id = $_POST['user_id'];
    $user->role = $_POST['role'];
    
    if($user->updateRole()) {
        $success = "Role pengguna berhasil diupdate";
    } else {
        $error = "Gagal mengupdate role pengguna";
    }
}

if(isset($_GET['delete'])) {
    $user->id = $_GET['delete'];
    if($user->delete()) {
        $success = "Pengguna berhasil dihapus";
    } else {
        $error = "Gagal menghapus pengguna";
    }
}

// Get all users
$all_users = $user->getAllUsers();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - AgenBis</title>
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
                    <a href="pemesanan.php" class="text-gray-600 hover:text-purple-600">Pemesanan</a>
                    <a href="users.php" class="text-purple-600 font-medium">Pengguna</a>
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
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Kelola Pengguna</h1>
            <p class="text-gray-600">Kelola data pengguna sistem AgenBis</p>
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

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Daftar Pengguna</h2>
            </div>
            <div class="p-6">
                <?php if($all_users->rowCount() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kontak</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Role</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bergabung</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while($row = $all_users->fetch()): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full"
                                                src="<?= $row['foto_profil'] ? '../assets/images/'.$row['foto_profil'] : 'https://ui-avatars.com/api/?name='.urlencode($row['nama']).'&background=purple&color=white' ?>"
                                                alt="<?= $row['nama'] ?>">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= $row['nama'] ?></div>
                                            <div class="text-sm text-gray-500"><?= $row['email'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= $row['telepon'] ?: '-' ?></div>
                                    <div class="text-sm text-gray-500"><?= substr($row['alamat'] ?: '-', 0, 30) ?>...
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                        <select name="role" onchange="this.form.submit()" class="text-xs border rounded px-2 py-1 <?= 
                                                        $row['role'] == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'
                                                    ?>">
                                            <option value="penumpang"
                                                <?= $row['role'] == 'penumpang' ? 'selected' : '' ?>>Penumpang</option>
                                            <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin
                                            </option>
                                        </select>
                                        <input type="hidden" name="update_role" value="1">
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <?= date('d M Y', strtotime($row['created_at'])) ?></div>
                                    <div class="text-sm text-gray-500"><?= date('H:i', strtotime($row['created_at'])) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="?delete=<?= $row['id'] ?>" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Yakin ingin menghapus pengguna <?= $row['nama'] ?>?')">
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
                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada pengguna</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <?php
            // Reset pointer dan hitung statistik
            $all_users->execute();
            $stats = [
                'total' => 0,
                'admin' => 0,
                'penumpang' => 0
            ];
            
            while($row = $all_users->fetch()) {
                $stats['total']++;
                $stats[$row['role']]++;
            }
            ?>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-2xl font-bold text-purple-600"><?= $stats['total'] ?></div>
                <div class="text-sm text-gray-600">Total Pengguna</div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-2xl font-bold text-blue-600"><?= $stats['admin'] ?></div>
                <div class="text-sm text-gray-600">Admin</div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-2xl font-bold text-green-600"><?= $stats['penumpang'] ?></div>
                <div class="text-sm text-gray-600">Penumpang</div>
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

        // Konfirmasi sebelum mengubah role
        const roleSelects = document.querySelectorAll('select[name="role"]');
        roleSelects.forEach(select => {
            select.addEventListener('change', function(e) {
                if (!confirm('Yakin ingin mengubah role pengguna?')) {
                    e.preventDefault();
                    this.form.reset();
                }
            });
        });
    });
    </script>
</body>

</html>