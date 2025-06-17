<?php
require_once 'db_connection.php';

interface Loanable {
    public function borrowBook($memberId);
    public function returnBook($memberId);
}

class LoanManager {
    private $db;

    public function __construct() {
        $dbConnection = new DatabaseConnection();
        $this->db = $dbConnection->getConnection();
    }

    public function borrowBook($bookId, $memberId) {
        try {
            // Check if book is already borrowed
            $stmt = $this->db->prepare("SELECT * FROM BookLoans WHERE book_id = ? AND is_returned = FALSE");
            $stmt->execute([$bookId]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Book is already borrowed'];
            }

            // Add loan record
            $stmt = $this->db->prepare("INSERT INTO BookLoans (book_id, member_id, loan_date, is_returned) VALUES (?, ?, CURDATE(), FALSE)");
            $stmt->execute([$bookId, $memberId]);
            
            return ['success' => true, 'message' => 'Book borrowed successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function returnBook($bookId, $memberId) {
        try {
            $stmt = $this->db->prepare("UPDATE BookLoans SET return_date = CURDATE(), is_returned = TRUE WHERE book_id = ? AND member_id = ? AND is_returned = FALSE");
            $stmt->execute([$bookId, $memberId]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Book returned successfully'];
            } else {
                return ['success' => false, 'message' => 'No active loan found for this book'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function getBorrowedBooks($memberId) {
        $stmt = $this->db->prepare("
            SELECT b.*, bl.loan_date 
            FROM Books b 
            JOIN BookLoans bl ON b.book_id = bl.book_id 
            WHERE bl.member_id = ? AND bl.is_returned = FALSE
        ");
        $stmt->execute([$memberId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isBookBorrowed($bookId) {
        $stmt = $this->db->prepare("SELECT * FROM BookLoans WHERE book_id = ? AND is_returned = FALSE");
        $stmt->execute([$bookId]);
        return $stmt->rowCount() > 0;
    }
}
?>