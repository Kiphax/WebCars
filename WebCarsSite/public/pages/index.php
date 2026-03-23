<?php
session_start();

require_once 'theme.php';
require_once 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>WebCars</title>
    <link rel="stylesheet" href="style_<?php echo $theme; ?>.css">
    <style>
        body { font-family: Arial; margin: 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .car-item { border: 1px solid #ccc; padding: 15px; margin: 10px 0; }
        
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
    
    <div class="container">
        <h1>Καλωσήρθατε στο WebCars</h1>
        <p>Η καλύτερη πλατφόρμα για μεταχειρισμένα αυτοκίνητα</p>
        
        <h2>Πρόσφατα Αυτοκίνητα</h2>
        
        <div>
            <?php
            $cars = $pdo->query("SELECT * FROM cars ORDER BY id DESC LIMIT 6")->fetchAll();
            foreach($cars as $car):
            ?>
            <div class="car-item">
                <h3><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h3>
                <p><?php echo number_format($car['price'], 2); ?>€</p>
                <p><?php echo number_format($car['kilometers']); ?> χλμ</p>
                <a href="view_car.php?id=<?php echo $car['id']; ?>">Περισσότερα</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <footer style="text-align: center; padding: 20px; margin-top: 50px; background: #2c3e50; color: white;">
        © 2026 WebCars
    </footer>
</body>
</html>