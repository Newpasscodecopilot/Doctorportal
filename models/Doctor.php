<?php
require_once 'models/User.php';

class Doctor extends User {
    protected $table = 'doctor';
    
    public function create($data) {
        $sql = "INSERT INTO doctor (name, email, password, age, phone_number, address, gender, docid, adrid, specialist, squestion, answer, type, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'doc', 'pending')";
        
        $params = [
            $data['name'],
            $data['email'],
            $data['password'], // Should be hashed
            $data['age'],
            $data['phone'],
            $data['address'],
            $data['gender'],
            $data['docid'],
            $data['adrid'],
            $data['specialist'],
            $data['squestion'],
            $data['answer']
        ];
        
        return $this->db->insert($sql, $params);
    }
    
    public function getById($id) {
        return $this->db->fetchOne("SELECT * FROM doctor WHERE did = ?", [$id]);
    }
    
    public function getByEmail($email) {
        return $this->db->fetchOne("SELECT * FROM doctor WHERE email = ?", [$email]);
    }
    
    public function getByDocId($docid) {
        return $this->db->fetchOne("SELECT * FROM doctor WHERE docid = ?", [$docid]);
    }
    
    public function getVerifiedDoctors() {
        return $this->db->fetchAll("SELECT * FROM doctor WHERE status = 'success' ORDER BY name");
    }
    
    public function getPendingDoctors() {
        return $this->db->fetchAll("SELECT * FROM doctor WHERE status = 'pending' ORDER BY name");
    }
    
    public function updateStatus($id, $status) {
        return $this->db->update("UPDATE doctor SET status = ? WHERE did = ?", [$status, $id]);
    }
    
    public function getAppointments($doctorId) {
        $sql = "SELECT a.*, p.name as patient_name, p.phone as patient_phone 
                FROM appointments a 
                JOIN patient p ON a.pat_id = p.pid 
                WHERE a.doc_id = ? 
                ORDER BY a.a_date DESC, a.a_time DESC";
        
        return $this->db->fetchAll($sql, [$doctorId]);
    }
    
    public function updateProfile($id, $data) {
        $sql = "UPDATE doctor SET name = ?, age = ?, phone_number = ?, address = ? WHERE did = ?";
        $params = [$data['name'], $data['age'], $data['phone'], $data['address'], $id];
        
        return $this->db->update($sql, $params);
    }
}
?>