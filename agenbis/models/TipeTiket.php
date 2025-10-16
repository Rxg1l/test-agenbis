<?php
class TipeTiket {
    private $conn;
    private $table_name = "tipe_tiket";

    // Deklarasi semua properti yang digunakan
    public $id;
    public $nama_tipe;
    public $deskripsi;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>