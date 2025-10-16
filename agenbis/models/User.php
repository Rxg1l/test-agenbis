<?php
class User {
    private $conn;
    private $table_name = "users";

    // Deklarasi semua properti yang digunakan
    public $id;
    public $nama;
    public $email;
    public $password;
    public $telepon;
    public $alamat;
    public $role;
    public $foto_profil;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ... semua method yang sudah ada tetap sama ...

    public function getAllUsers() {
        $query = "SELECT id, nama, email, telepon, alamat, role, foto_profil, created_at 
                  FROM " . $this->table_name . " 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function updateRole() {
        $query = "UPDATE " . $this->table_name . "
                  SET role=:role, updated_at=CURRENT_TIMESTAMP
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":role", $this->role);
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