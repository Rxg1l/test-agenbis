<?php
class DenahKursi {
    private $conn;
    private $table_name = "denah_kursi";

    // Deklarasi semua properti yang digunakan
    public $id;
    public $bus_id;
    public $nomor_kursi;
    public $baris;
    public $kolom;
    public $tipe_kursi;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ... semua method yang sudah ada tetap sama ...

    public function updateStatus($nomor_kursi, $bus_id, $status) {
        $query = "UPDATE " . $this->table_name . " 
                  SET status = ? 
                  WHERE nomor_kursi = ? AND bus_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $status);
        $stmt->bindParam(2, $nomor_kursi);
        $stmt->bindParam(3, $bus_id);
        
        return $stmt->execute();
    }

    public function getKursiTerbooking($jadwal_id) {
        $query = "SELECT dp.nomor_kursi
                  FROM detail_pemesanan dp
                  INNER JOIN pemesanan p ON dp.pemesanan_id = p.id
                  WHERE p.jadwal_id = ? 
                  AND dp.status_kursi = 'Dipesan'
                  AND p.status_pembayaran IN ('Pending', 'Success')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $jadwal_id);
        $stmt->execute();
        
        $booked_seats = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $booked_seats[] = $row['nomor_kursi'];
        }
        return $booked_seats;
    }
}
?>