<?php
require_once 'models/User.php';

class Patient extends User {
    protected $table = 'patient';
    
    public function create($data) {
        $sql = "INSERT INTO patient (name, email, password, age, phone, address, gender, adharno, question, answer, type) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pat')";
        
        $params = [
            $data['name'],
            $data['email'],
            $data['password'], // Should be hashed
            $data['age'],
            $data['phone'],
            $data['address'],
            $data['gender'],
            $data['adharno'],
            $data['question'],
            $data['answer']
        ];
        
        return $this->db->insert($sql, $params);
    }
    
    public function getById($id) {
        return $this->db->fetchOne("SELECT * FROM patient WHERE pid = ?", [$id]);
    }
    
    public function getByEmail($email) {
        return $this->db->fetchOne("SELECT * FROM patient WHERE email = ?", [$email]);
    }
    
    public function getByAdharNo($adharno) {
        return $this->db->fetchOne("SELECT * FROM patient WHERE adharno = ?", [$adharno]);
    }
    
    public function getAppointments($patientId) {
        $sql = "SELECT a.*, d.name as doctor_name, d.specialist 
                FROM appointments a 
                JOIN doctor d ON a.doc_id = d.did 
                WHERE a.pat_id = ? 
                ORDER BY a.a_date DESC, a.a_time DESC";
        
        return $this->db->fetchAll($sql, [$patientId]);
    }
    
    public function bookAppointment($data) {
        $sql = "INSERT INTO appointments (pname, phone, p_age, a_date, a_time, problem, doc_id, pat_id, status, tid) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)";
        
        $tid = uniqid('TXN');
        $params = [
            $data['pname'],
            $data['phone'],
            $data['age'],
            $data['date'],
            $data['time'],
            $data['problem'],
            $data['doctor_id'],
            $data['patient_id'],
            $tid
        ];
        
        return $this->db->insert($sql, $params);
    }
    
    public function updateProfile($id, $data) {
        $sql = "UPDATE patient SET name = ?, age = ?, phone = ?, address = ? WHERE pid = ?";
        $params = [$data['name'], $data['age'], $data['phone'], $data['address'], $id];
        
        return $this->db->update($sql, $params);
    }
    
    public function submitFeedback($patientId, $doctorId, $feedback) {
        $sql = "INSERT INTO feedback (pid, did, feed, dt) VALUES (?, ?, ?, CURDATE())";
        return $this->db->insert($sql, [$patientId, $doctorId, $feedback]);
    }
}
?>