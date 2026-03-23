<?php
session_start();
require_once 'theme.php';
require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM cars WHERE user_id = ? ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$my_cars = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Τα Αυτοκίνητά Μου - WebCars</title>
    <link rel="stylesheet" href="style_<?php echo $theme; ?>.css">
    <style>
        body { font-family: Arial; margin: 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .car-list { margin: 20px 0; }
        .car-item { border: 1px solid #ccc; padding: 15px; margin: 10px 0; background: white; }
        .no-cars { text-align: center; padding: 40px; color: #666; }
        .actions a { margin-right: 10px; }
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
        <a href="search.php" style="color: white; text-decoration: none; margin-right: 15px;">Αναζήτηση</a>
        <a href="add_car.php" style="color: white; text-decoration: none; margin-right: 15px;">Προσθήκη</a>
        <a href="my_cars.php" style=" color: white; padding: 5px 10px; text-decoration: none; margin-right: 15px;">Τα Αυτοκίνητά Μου</a>
        <a href="logout.php" style="color: white; text-decoration: none; margin-right: 15px;">Αποσύνδεση</a>
        
        <div style="color: white; margin-left: auto; display: inline;">
            Γεια σου, <?php echo htmlspecialchars($_SESSION['first_name'] ?? 'Χρήστης'); ?>!
        </div>
    </nav>
    
    <div class="container">
        <h1>Τα Αυτοκίνητά Μου</h1>
        
        <?php if(count($my_cars) > 0): ?>
            <div class="car-list">
                <?php foreach($my_cars as $car): ?>
                <div class="car-item">
                    <h3><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h3>
                    <p><strong>Τιμή:</strong> <?php echo number_format($car['price'], 2); ?>€</p>
                    <p><strong>Χλμ:</strong> <?php echo number_format($car['kilometers']); ?></p>
                    <p><strong>Έτος:</strong> <?php echo $car['first_registration']; ?></p>
                    
                    <div class="actions">
                        <a href="view_car.php?id=<?php echo $car['id']; ?>" 
                           style="background: #2c3e50; color: white; padding: 5px 10px; text-decoration: none;">
                            Προβολή
                        </a>
                        <a href="edit_car.php?id=<?php echo $car['id']; ?>" 
                           style="background: #f39c12; color: white; padding: 5px 10px; text-decoration: none;">
                            Επεξεργασία
                        </a>
                        <a href="upload_images.php?car_id=<?php echo $car['id']; ?>" 
                           style="background: #27ae60; color: white; padding: 5px 10px; text-decoration: none;">
                            Προσθήκη Εικόνων
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-cars">
                <h3>Δεν έχετε καταχωρίσει αυτοκίνητα ακόμα.</h3>
                <p>Προσθέστε το πρώτο σας αυτοκίνητο <a href="add_car.php">εδώ</a>.</p>
            </div>
        <?php endif; ?>
    </div>
      <footer style="text-align: center; padding: 20px; margin-top: 400px; background: #2c3e50; color: white;">
        © 2026 WebCars
    </footer>
    
</body>
</html>