<?php
require "DBController.php";

class Auth {
    private $db;

    public function __construct() {
        $this->db = new DBController();
    }

    public function getMemberByUsername($username) {
        $query = "SELECT * FROM members WHERE member_name = ?";
        $result = $this->db->runQuery($query, 's', [$username]);
        return $result;
    }

    public function getTokenByUsername($username, $expired) {
        $query = "SELECT * FROM tbl_token_auth WHERE username = ? AND is_expired = ?";
        $result = $this->db->runQuery($query, 'si', [$username, $expired]);
        return $result;
    }

    public function markAsExpired($tokenId) {
        $query = "UPDATE tbl_token_auth SET is_expired = ? WHERE id = ?";
        $expired = 1;
        $result = $this->db->update($query, 'ii', [$expired, $tokenId]);
        return $result;
    }

    public function insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date) {
        $query = "INSERT INTO tbl_token_auth (username, password_hash, selector_hash, expiry_date) VALUES (?, ?, ?, ?)";
        $result = $this->db->insert($query, 'ssss', [$username, $random_password_hash, $random_selector_hash, $expiry_date]);
        return $result;
    }
}
?>
