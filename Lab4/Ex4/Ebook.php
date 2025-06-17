<?php
require_once 'db_connection.php';
require_once 'Book.php';
require_once 'Discountable.php';

class Ebook extends Book implements Discountable {
    private $downloadLink;
    private $discountPercentage = 15; // 15% discount for eBooks

    public function __construct($bookId = null) {
        parent::__construct($bookId);
        if ($bookId) {
            $this->loadEbookData($bookId);
        }
    }

    private function loadEbookData($bookId) {
        $stmt = $this->db->prepare("SELECT download_link FROM Books WHERE book_id = ? AND is_ebook = TRUE");
        $stmt->execute([$bookId]);
        $ebook = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($ebook) {
            $this->downloadLink = $ebook['download_link'];
        }
    }

    public function download() {
        if ($this->downloadLink) {
            return $this->downloadLink;
        }
        return false;
    }

    public function getDiscount() {
        return $this->discountPercentage;
    }

    public function getDiscountedPrice() {
        return $this->price * (1 - $this->discountPercentage / 100);
    }

    public function getDownloadLink() {
        return $this->downloadLink;
    }

    public static function getAllEbooks() {
        $dbConnection = new DatabaseConnection();
        $db = $dbConnection->getConnection();
        $stmt = $db->query("SELECT * FROM Books WHERE is_ebook = TRUE ORDER BY title");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Handle specific eBook requests
$ebook = null;
if (isset($_GET['id'])) {
    $ebook = new Ebook($_GET['id']);
}

$ebooks = Ebook::getAllEbooks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Books Library</title>
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
            background: linear-gradient(135deg, #9C27B0 0%, #673AB7 100%);
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

        .featured-ebook {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            text-align: center;
        }

        .featured-title {
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .featured-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .featured-detail {
            background: rgba(255,255,255,0.2);
            padding: 15px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .pricing-info {
            margin: 20px 0;
            text-align: center;
        }

        .original-price {
            text-decoration: line-through;
            opacity: 0.7;
            font-size: 1.2rem;
        }

        .discounted-price {
            font-size: 1.5rem;
            font-weight: bold;
            margin-left: 10px;
        }

        .discount-badge {
            background: #FF5722;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            margin-left: 10px;
        }

        .ebooks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .ebook-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .ebook-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #9C27B0 0%, #673AB7 100%);
        }

        .ebook-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .ebook-title {
            font-size: 1.4rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .ebook-author {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .ebook-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .ebook-detail {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 8px;
        }

        .ebook-detail strong {
            color: #9C27B0;
        }

        .ebook-actions {
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

        .btn-download {
            background: linear-gradient(135deg, #9C27B0 0%, #673AB7 100%);
            color: white;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(156, 39, 176, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .digital-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #9C27B0 0%, #673AB7 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
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
            .ebooks-grid {
                grid-template-columns: 1fr;
            }
            
            .featured-details {
                grid-template-columns: 1fr;
            }
            
            .ebook-actions {
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
            <h1>💻 E-Books Library</h1>
            <p>Digital books with special discounts and instant downloads</p>
        </div>
        
        <div class="content">
            <?php if ($ebook && isset($_GET['id'])): ?>
                <div class="featured-ebook">
                    <div class="featured-title"><?php echo htmlspecialchars($ebook->getTitle()); ?></div>
                    <p>by <?php echo htmlspecialchars($ebook->getAuthor()); ?></p>
                    
                    <div class="featured-details">
                        <div class="featured-detail">
                            <strong>Genre:</strong><br><?php echo htmlspecialchars($ebook->getGenre()); ?>
                        </div>
                        <div class="featured-detail">
                            <strong>Year:</strong><br><?php echo $ebook->getYear(); ?>
                        </div>
                        <div class="featured-detail">
                            <strong>Discount:</strong><br><?php echo $ebook->getDiscount(); ?>% OFF
                        </div>
                        <div class="featured-detail">
                            <strong>Format:</strong><br>Digital E-Book
                        </div>
                    </div>
                    
                    <div class="pricing-info">
                        <span class="original-price">$<?php echo number_format($ebook->getPrice(), 2); ?></span>
                        <span class="discounted-price">$<?php echo number_format($ebook->getDiscountedPrice(), 2); ?></span>
                        <span class="discount-badge">Save <?php echo $ebook->getDiscount(); ?>%</span>
                    </div>
                    
                    <div class="ebook-actions">
                        <?php if ($ebook->getDownloadLink()): ?>
                            <a href="<?php echo htmlspecialchars($ebook->getDownloadLink()); ?>" target="_blank" class="btn btn-download">
                                ⬇️ Download E-Book
                            </a>
                        <?php endif; ?>
                        <a href="Book.php" class="btn btn-secondary">📚 Back to All Books</a>
                    </div>
                </div>
            <?php endif; ?>
            
            <h2 style="text-align: center; margin-bottom: 20px; color: #333;">All Available E-Books</h2>
            
            <div class="ebooks-grid">
                <?php foreach ($ebooks as $ebookData): 
                    $currentEbook = new Ebook($ebookData['book_id']);
                ?>
                    <div class="ebook-card">
                        <div class="digital-badge">📱 Digital</div>
                        
                        <div class="ebook-title"><?php echo htmlspecialchars($ebookData['title']); ?></div>
                        <div class="ebook-author">by <?php echo htmlspecialchars($ebookData['author']); ?></div>
                        
                        <div class="ebook-details">
                            <div class="ebook-detail"><strong>Genre:</strong> <?php echo htmlspecialchars($ebookData['genre']); ?></div>
                            <div class="ebook-detail"><strong>Year:</strong> <?php echo $ebookData['year']; ?></div>
                            <div class="ebook-detail">
                                <strong>Original:</strong> $<?php echo number_format($ebookData['price'], 2); ?>
                            </div>
                            <div class="ebook-detail">
                                <strong>Discounted:</strong> $<?php echo number_format($currentEbook->getDiscountedPrice(), 2); ?>
                            </div>
                        </div>
                        
                        <div class="pricing-info" style="margin: 15px 0; text-align: center;">
                            <div style="color: #9C27B0; font-weight: bold;">
                                💾 <?php echo $currentEbook->getDiscount(); ?>% Digital Discount Applied!
                            </div>
                        </div>
                        
                        <div class="ebook-actions">
                            <a href="Ebook.php?id=<?php echo $ebookData['book_id']; ?>" class="btn btn-download">
                                🔍 View Details
                            </a>
                            <?php if ($ebookData['download_link']): ?>
                                <a href="<?php echo htmlspecialchars($ebookData['download_link']); ?>" target="_blank" class="btn btn-secondary">
                                    ⬇️ Download
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="navigation">
                <a href="Book.php" class="nav-link">📚 All Books</a>
                <a href="Member.php" class="nav-link">👥 Members</a>
                <a href="library_test.php" class="nav-link">🧪 System Test</a>
            </div>
        </div>
    </div>
</body>
</html>