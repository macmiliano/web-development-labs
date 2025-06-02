<?php
require_once 'db_connection.php';
require_once 'Loanable.php';

class Member {
    private $memberId;
    private $name;
    private $email;
    private $membershipDate;
    private $db;
    private $loanManager;

    public function __construct($memberId = null) {
        $dbConnection = new DatabaseConnection();
        $this->db = $dbConnection->getConnection();
        $this->loanManager = new LoanManager();
        
        if ($memberId) {
            $this->loadMember($memberId);
        }
    }

    private function loadMember($memberId) {
        $stmt = $this->db->prepare("SELECT * FROM Members WHERE member_id = ?");
        $stmt->execute([$memberId]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($member) {
            $this->memberId = $member['member_id'];
            $this->name = $member['name'];
            $this->email = $member['email'];
            $this->membershipDate = $member['membership_date'];
        }
    }

    public function getBorrowedBooks() {
        return $this->loanManager->getBorrowedBooks($this->memberId);
    }

    public static function getAllMembers() {
        $dbConnection = new DatabaseConnection();
        $db = $dbConnection->getConnection();
        $stmt = $db->query("SELECT * FROM Members ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Getters
    public function getMemberId() { return $this->memberId; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getMembershipDate() { return $this->membershipDate; }
}

// Handle AJAX requests for borrowed books
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'borrowed_books' && isset($_GET['member_id'])) {
    $member = new Member($_GET['member_id']);
    $borrowedBooks = $member->getBorrowedBooks();
    header('Content-Type: application/json');
    echo json_encode($borrowedBooks);
    exit;
}

$members = Member::getAllMembers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Members</title>
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
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
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

        .members-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .member-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .member-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .member-name {
            font-size: 1.4rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .member-email {
            color: #666;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .member-date {
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .member-actions {
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
            min-width: 120px;
        }

        .btn-view {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(33, 150, 243, 0.4);
        }

        .borrowed-books-section {
            margin-top: 30px;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 15px;
            display: none;
        }

        .borrowed-books-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .borrowed-books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .borrowed-book-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .book-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .book-author {
            color: #666;
            margin-bottom: 8px;
        }

        .loan-date {
            color: #888;
            font-size: 0.9rem;
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

        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .no-books {
            text-align: center;
            padding: 20px;
            color: #888;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .members-grid {
                grid-template-columns: 1fr;
            }
            
            .borrowed-books-grid {
                grid-template-columns: 1fr;
            }
            
            .member-actions {
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
            <h1>👥 Library Members</h1>
            <p>Manage library members and view their borrowed books</p>
        </div>
        
        <div class="content">
            <div class="members-grid">
                <?php foreach ($members as $member): ?>
                    <div class="member-card">
                        <div class="member-name"><?php echo htmlspecialchars($member['name']); ?></div>
                        <div class="member-email">📧 <?php echo htmlspecialchars($member['email']); ?></div>
                        <div class="member-date">📅 Member since: <?php echo date('F j, Y', strtotime($member['membership_date'])); ?></div>
                        
                        <div class="member-actions">
                            <button class="btn btn-view" onclick="viewBorrowedBooks(<?php echo $member['member_id']; ?>, '<?php echo htmlspecialchars($member['name']); ?>')">
                                📚 View Borrowed Books
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="borrowed-books-section" id="borrowedBooksSection">
                <div class="borrowed-books-title" id="borrowedBooksTitle"></div>
                <div id="borrowedBooksContent"></div>
            </div>
            
            <div class="navigation">
                <a href="Book.php" class="nav-link">📚 View Books</a>
                <a href="library_test.php" class="nav-link">🧪 System Test</a>
            </div>
        </div>
    </div>

    <script>
        function viewBorrowedBooks(memberId, memberName) {
            const section = document.getElementById('borrowedBooksSection');
            const title = document.getElementById('borrowedBooksTitle');
            const content = document.getElementById('borrowedBooksContent');
            
            title.textContent = `Borrowed Books for ${memberName}`;
            content.innerHTML = '<div class="loading">🔄 Loading borrowed books...</div>';
            section.style.display = 'block';
            
            // Scroll to the section
            section.scrollIntoView({ behavior: 'smooth' });
            
            fetch(`Member.php?action=borrowed_books&member_id=${memberId}`)
                .then(response => response.json())
                .then(books => {
                    if (books.length === 0) {
                        content.innerHTML = '<div class="no-books">📖 No books currently borrowed</div>';
                    } else {
                        let html = '<div class="borrowed-books-grid">';
                        books.forEach(book => {
                            html += `
                                <div class="borrowed-book-card">
                                    <div class="book-title">${escapeHtml(book.title)}</div>
                                    <div class="book-author">by ${escapeHtml(book.author)}</div>
                                    <div class="book-author">Genre: ${escapeHtml(book.genre)}</div>
                                    <div class="book-author">Year: ${book.year}</div>
                                    <div class="book-author">Price: $${parseFloat(book.price).toFixed(2)}</div>
                                    <div class="loan-date">📅 Borrowed on: ${formatDate(book.loan_date)}</div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        content.innerHTML = html;
                    }
                })
                .catch(error => {
                    content.innerHTML = '<div class="no-books">❌ Error loading borrowed books</div>';
                    console.error('Error:', error);
                });
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        }
    </script>
</body>
</html>