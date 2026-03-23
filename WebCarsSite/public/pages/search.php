<?php
session_start();
$default_theme = 'light';

require_once 'theme.php';
require_once 'config.php';

$brand = $_GET['brand'] ?? '';
$engine_cc = $_GET['engine_cc'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$per_page = 4;


$sql_where = "";
$params_count = [];

if (!empty($brand)) {
    $sql_where .= " AND c.brand LIKE ?";
    $params_count[] = "%$brand%";
}

if (!empty($engine_cc) && is_numeric($engine_cc)) {
    $sql_where .= " AND c.engine_cc >= ?";
    $params_count[] = $engine_cc;
}

if (!empty($min_price) && is_numeric($min_price)) {
    $sql_where .= " AND c.price >= ?";
    $params_count[] = $min_price;
}

if (!empty($max_price) && is_numeric($max_price)) {
    $sql_where .= " AND c.price <= ?";
    $params_count[] = $max_price;
}


$count_sql = "SELECT COUNT(*) as total FROM cars c WHERE 1=1" . $sql_where;
$stmt = $pdo->prepare($count_sql);
$stmt->execute($params_count);
$total = $stmt->fetch()['total'];
$total_pages = ceil($total / $per_page);


$offset = ($page - 1) * $per_page;

$sql = "SELECT c.*, u.first_name, u.last_name 
        FROM cars c 
        JOIN users u ON c.user_id = u.id 
        WHERE 1=1" . $sql_where . " 
        ORDER BY c.id DESC 
        LIMIT $per_page OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params_count);
$cars = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Αναζήτηση - WebCars</title>
    <link rel="stylesheet" href="style_<?php echo $theme; ?>.css">
    <style>
        body { font-family: Arial; margin: 20px; }
        .search-form { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .search-form input { padding: 8px; margin: 5px; }
        .car-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin: 20px 0; }
        .car-card { border: 1px solid #ddd; border-radius: 5px; padding: 15px; background: white; }
        .car-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 3px; }
        .car-price { color: #27ae60; font-size: 18px; font-weight: bold; }
        .pagination { margin: 20px 0; text-align: center; }
        .pagination a, .pagination span { margin: 0 5px; padding: 5px 10px; border: 1px solid #ddd; }
        .pagination .current { background: #3498db; color: white; }
    </style>
</head>
<body>
   
<div style="position: absolute; top: 15px; right: 15px;">
    <?php if($theme == 'light'): ?>
        <a href="?theme=dark" style="background: #34495e; color: white; padding: 5px 10px; border-radius: 3px; text-decoration: none; font-size: 12px;">🌙 Dark Mode</a>
    <?php else: ?>
        <a href="?theme=light" style="background: #34495e; color: white; padding: 5px 10px; border-radius: 3px; text-decoration: none; font-size: 12px;">☀️ Light Mode</a>
    <?php endif; ?>
</div>
   
    <nav style="background: #2c3e50; padding: 15px; margin: 0;">
        <a href="index.php" style="color: white; text-decoration: none; margin-right: 15px;">Αρχική</a>
        <a href="search.php" style="color: white; padding: 5px 10px; text-decoration: none; margin-right: 15px;">Αναζήτηση</a>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="add_car.php" style="color: white; text-decoration: none; margin-right: 15px;">Προσθήκη</a>
            <a href="my_cars.php" style="color: white; text-decoration: none; margin-right: 15px;">Τα Αυτοκίνητά Μου</a>
            <a href="logout.php" style="color: white; text-decoration: none; margin-right: 15px;">Αποσύνδεση</a>
            
            <div style="color: white; margin-left: auto; display: inline;">
                Γεια σου, <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'Χρήστης'); ?>!
            </div>
            
        <?php else: ?>
            <a href="register.php" style="color: white; text-decoration: none; margin-right: 15px;">Εγγραφή</a>
            <a href="login.php" style="color: white; text-decoration: none; margin-right: 15px;">Σύνδεση</a>
            <a href="activate.php" style="color: white; text-decoration: none; margin-right: 15px;">Ενεργοποίηση</a>
        <?php endif; ?>
    </nav>
    
    <div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        <h1> Αναζήτηση Αυτοκινήτων</h1>
        
        <div class="search-form">
            <form method="GET">
                <h3>Κριτήρια Αναζήτησης:</h3>
                
                <div>
                    <strong>Μάρκα:</strong>
                    <input type="text" name="brand" placeholder="π.χ. Toyota" 
                           value="<?php echo htmlspecialchars($brand); ?>">
                </div>
                
                <div>
                    <strong>Κυβικά (min):</strong>
                    <input type="number" name="engine_cc" placeholder="cc" 
                           value="<?php echo htmlspecialchars($engine_cc); ?>">
                </div>
                
                <div>
                    <strong>Τιμή:</strong>
                    Από <input type="number" name="min_price" placeholder="€" 
                              value="<?php echo htmlspecialchars($min_price); ?>">
                    Έως <input type="number" name="max_price" placeholder="€" 
                              value="<?php echo htmlspecialchars($max_price); ?>">
                </div>
                
                <button type="submit" style="padding: 10px 20px;">Αναζήτηση</button>
                <a href="search.php" style="margin-left: 10px;">Καθαρισμός</a>
            </form>
        </div>
        
        <div style="margin: 15px 0;">
            <strong>Γρήγορες αναζητήσεις:</strong>
            <a href="search.php?brand=Toyota">Toyota</a> |
            <a href="search.php?engine_cc=2000">2000cc+</a> |
            <a href="search.php?min_price=10000&max_price=15000">€10k-15k</a> |
            <a href="search.php">Όλα</a>
        </div>
        
        <h2>Βρέθηκαν <?php echo $total; ?> αυτοκίνητα</h2>
        
        <?php if($total > 0): ?>
            <div class="car-grid">
                <?php foreach($cars as $car): ?>
                    <?php 
                    
                    $img_stmt = $pdo->prepare("SELECT filename FROM car_images WHERE car_id = ? LIMIT 1");
                    $img_stmt->execute([$car['id']]);
                    $image = $img_stmt->fetch();
                    ?>
                    
                    <div class="car-card">
                        <?php if($image): ?>
                            <img src="uploads/<?php echo htmlspecialchars($image['filename']); ?>" 
                                 alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>">
                        <?php else: ?>
                            <div style="height: 150px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                 Δεν υπάρχει εικόνα
                            </div>
                        <?php endif; ?>
                        
                        <h3><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h3>
                        <div class="car-price"><?php echo number_format($car['price'], 2); ?> €</div>
                        <p> <?php echo number_format($car['kilometers']); ?> km</p>
                        <p> <?php echo htmlspecialchars($car['fuel_type']); ?></p>
                        <p> <?php echo $car['engine_cc']; ?> cc</p>
                        
                        <a href="view_car.php?id=<?php echo $car['id']; ?>" 
                           style="display: block; text-align: center; background: #3498db; color: white; padding: 8px; text-decoration: none; margin-top: 10px;">
                            Προβολή
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if($total_pages > 1): ?>
                <div class="pagination">
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if($i == $page): ?>
                            <span class="current"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="search.php?brand=<?php echo urlencode($brand); ?>&engine_cc=<?php echo urlencode($engine_cc); ?>&min_price=<?php echo urlencode($min_price); ?>&max_price=<?php echo urlencode($max_price); ?>&page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <p style="text-align: center; padding: 40px; color: #666;">
                Δεν βρέθηκαν αυτοκίνητα.
            </p>
        <?php endif; ?>
    </div>
    
    <footer style="text-align: center; padding: 20px; margin-top: 50px; background: #2c3e50; color: white;">
        © 2026 WebCars
    </footer>
</body>
</html>