<?php
class Rute {
    private $conn;
    private $table_name = "rute";

    // Deklarasi semua properti yang digunakan
    public $id;
    public $kota_asal;
    public $kota_tujuan;
    public $jarak_km;
    public $waktu_tempuh_jam;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method readAll yang diperlukan
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY kota_asal, kota_tujuan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getAllCities() {
        $query = "SELECT kota_asal FROM rute 
                  UNION 
                  SELECT kota_tujuan FROM rute 
                  ORDER BY kota_asal";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $cities = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cities[] = $row['kota_asal'];
        }
        return $cities;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET kota_asal=:kota_asal, kota_tujuan=:kota_tujuan, 
                      jarak_km=:jarak_km, waktu_tempuh_jam=:waktu_tempuh_jam";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":kota_asal", $this->kota_asal);
        $stmt->bindParam(":kota_tujuan", $this->kota_tujuan);
        $stmt->bindParam(":jarak_km", $this->jarak_km);
        $stmt->bindParam(":waktu_tempuh_jam", $this->waktu_tempuh_jam);

        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET kota_asal=:kota_asal, kota_tujuan=:kota_tujuan, 
                      jarak_km=:jarak_km, waktu_tempuh_jam=:waktu_tempuh_jam
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":kota_asal", $this->kota_asal);
        $stmt->bindParam(":kota_tujuan", $this->kota_tujuan);
        $stmt->bindParam(":jarak_km", $this->jarak_km);
        $stmt->bindParam(":waktu_tempuh_jam", $this->waktu_tempuh_jam);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }
}
?>