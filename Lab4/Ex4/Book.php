<?php
require_once 'db_connection.php';
require_once 'Loanable.php';

class Book implements Loanable {
    public $bookId;
    public $title;
    public $author;
    public $price;
    public $genre;
    public $year;
    public $db;
    public $loanManager;

    // Implement the required borrow() method from Loanable interface
    // Implement the required borrow() method from Loanable interface
    public function borrow(int $memberId): array {
        return $this->borrowBook($memberId);
    }

    public function __construct($bookId = null) {
        $dbConnection = new DatabaseConnection();
        $this->db = $dbConnection->getConnection();
        $this->loanManager = new LoanManager();
        
        if ($bookId) {
            $this->loadBook($bookId);
        }
    }

    public function loadBook($bookId) {
        $stmt = $this->db->prepare("SELECT * FROM Books WHERE book_id = ?");
        $stmt->execute([$bookId]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($book) {
            $this->bookId = $book['book_id'];
            $this->title = $book['title'];
            $this->author = $book['author'];
            $this->price = $book['price'];
            $this->genre = $book['genre'];
            $this->year = $book['year'];
        }
    }

    public function borrowBook($memberId) {
        return $this->loanManager->borrowBook($this->bookId, $memberId);
    }

    public function returnBook($memberId) {
        return $this->loanManager->returnBook($this->bookId, $memberId);
    }

    public static function getAllBooks() {
        $dbConnection = new DatabaseConnection();
        $db = $dbConnection->getConnection();
        $stmt = $db->query("SELECT * FROM Books ORDER BY title");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isAvailable() {
        return !$this->loanManager->isBookBorrowed($this->bookId);
    }

    // Getters
    public function getBookId() { return $this->bookId; }
    public function getTitle() { return $this->title; }
    public function getAuthor() { return $this->author; }
    public function getPrice() { return $this->price; }
    public function getGenre() { return $this->genre; }
    public function getYear() { return $this->year; }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $response = ['success' => false, 'message' => 'Invalid action'];
    
    if ($_POST['action'] === 'borrow' && isset($_POST['book_id']) && isset($_POST['member_id'])) {
        $book = new Book($_POST['book_id']);
        $response = $book->borrowBook($_POST['member_id']);
    } elseif ($_POST['action'] === 'return' && isset($_POST['book_id']) && isset($_POST['member_id'])) {
        $book = new Book($_POST['book_id']);
        $response = $book->returnBook($_POST['member_id']);
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Display all books
$books = Book::getAllBooks();
$loanManager = new LoanManager();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Books</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .content {
            padding: 30px;
        }

        .member-selector {
            margin-bottom: 30px;
            text-align: center;
        }

        .member-selector select {
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 25px;
            font-size: 16px;
            background: white;
            min-width: 200px;
        }

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        .book-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .book-title {
            font-size: 1.4rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .book-author {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .book-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .book-detail {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 8px;
        }

        .book-detail strong {
            color: #4CAF50;
        }

        .book-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            min-width: 100px;
        }

        .btn-borrow {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
        }

        .btn-borrow:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        }

        .btn-return {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
        }

        .btn-return:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(33, 150, 243, 0.4);
        }

        .btn-disabled {
            background: #ccc;
            color: #666;
            cursor: not-allowed;
        }

        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-available {
            background: #e8f5e8;
            color: #4CAF50;
        }

        .status-borrowed {
            background: #fff3e0;
            color: #ff9800;
        }

        .alert {
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            display: none;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .navigation {
            text-align: center;
            margin-top: 30px;
        }

        .nav-link {
            display: inline-block;
            margin: 0 15px;
            padding: 12px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 768px) {
            .books-grid {
                grid-template-columns: 1fr;
            }
            
            .book-actions {
                flex-direction: column;
            }
            
            .header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Library Book Management</h1>
            <p>Browse and manage all available books</p>
        </div>
        
        <div class="content">
            <div class="alert alert-success" id="successAlert"></div>
            <div class="alert alert-error" id="errorAlert"></div>
            
            <div class="member-selector">
                <label for="memberSelect"><strong>Select Member:</strong></label>
                <select id="memberSelect">
                    <option value="">Choose a member...</option>
                    <?php
                    $dbConnection = new DatabaseConnection();
                    $db = $dbConnection->getConnection();
                    $stmt = $db->query("SELECT * FROM Members ORDER BY name");
                    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($members as $member): ?>
                        <option value="<?php echo $member['member_id']; ?>">
                            <?php echo htmlspecialchars($member['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="books-grid">
                <?php foreach ($books as $book): 
                    $isAvailable = !$loanManager->isBookBorrowed($book['book_id']);
                ?>
                    <div class="book-card">
                        <div class="status-badge <?php echo $isAvailable ? 'status-available' : 'status-borrowed'; ?>">
                            <?php echo $isAvailable ? 'Available' : 'Borrowed'; ?>
                        </div>
                        
                        <div class="book-title"><?php echo htmlspecialchars($book['title']); ?></div>
                        <div class="book-author">by <?php echo htmlspecialchars($book['author']); ?></div>
                        
                        <div class="book-details">
                            <div class="book-detail"><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre']); ?></div>
                            <div class="book-detail"><strong>Year:</strong> <?php echo $book['year']; ?></div>
                            <div class="book-detail"><strong>Price:</strong> $<?php echo number_format($book['price'], 2); ?></div>
                            <div class="book-detail"><strong>Type:</strong> <?php echo $book['is_ebook'] ? 'E-Book' : 'Physical'; ?></div>
                        </div>
                        
                        <div class="book-actions">
                            <?php if ($isAvailable): ?>
                                <button class="btn btn-borrow" onclick="borrowBook(<?php echo $book['book_id']; ?>)">
                                    📖 Borrow Book
                                </button>
                            <?php else: ?>
                                <button class="btn btn-return" onclick="returnBook(<?php echo $book['book_id']; ?>)">
                                    📤 Return Book
                                </button>
                            <?php endif; ?>
                            
                            <?php if ($book['is_ebook']): ?>
                                <a href="Ebook.php?id=<?php echo $book['book_id']; ?>" class="btn" style="background: linear-gradient(135deg, #9C27B0 0%, #673AB7 100%); color: white;">
                                    💻 E-Book Details
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="navigation">
                <a href="Member.php" class="nav-link">👥 View Members</a>
                <a href="library_test.php" class="nav-link">🧪 System Test</a>
            </div>
        </div>
    </div>

    <script>
        function showAlert(message, type) {
            const alertElement = document.getElementById(type + 'Alert');
            alertElement.textContent = message;
            alertElement.style.display = 'block';
            setTimeout(() => {
                alertElement.style.display = 'none';
            }, 5000);
        }

        function borrowBook(bookId) {
            const memberId = document.getElementById('memberSelect').value;
            if (!memberId) {
                showAlert('Please select a member first!', 'error');
                return;
            }

            fetch('Book.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=borrow&book_id=${bookId}&member_id=${memberId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert(data.message, 'error');
                }
            })
            .catch(error => {
                showAlert('An error occurred: ' + error.message, 'error');
            });
        }

        function returnBook(bookId) {
            const memberId = document.getElementById('memberSelect').value;
            if (!memberId) {
                showAlert('Please select a member first!', 'error');
                return;
            }

            fetch('Book.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=return&book_id=${bookId}&member_id=${memberId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert(data.message, 'error');
                }
            })
            .catch(error => {
                showAlert('An error occurred: ' + error.message, 'error');
            });
        }
    </script>
</body>
</html>