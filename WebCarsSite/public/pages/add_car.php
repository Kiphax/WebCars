<?php
session_start();
$default_theme = 'light';

require_once 'theme.php';
require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $body_type = $_POST['body_type'];
    $engine_cc = $_POST['engine_cc'];
    $fuel_type = $_POST['fuel_type'];
    $kilometers = $_POST['kilometers'];
    $first_registration = $_POST['first_registration'];
    $has_turbo = isset($_POST['has_turbo']) ? 1 : 0;
    $is_hybrid = isset($_POST['is_hybrid']) ? 1 : 0;
    $needs_repair = isset($_POST['needs_repair']) ? 1 : 0;
    $price = $_POST['price'];
    
    if (empty($brand) || empty($model) || empty($price)) {
        $error = "Συμπληρώστε τα υποχρεωτικά πεδία";
    } else {
        try {
            $sql = "INSERT INTO cars (user_id, brand, model, body_type, engine_cc, fuel_type, kilometers, first_registration, has_turbo, is_hybrid, needs_repair, price) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_SESSION['user_id'],
                $brand, $model, $body_type, $engine_cc, $fuel_type, $kilometers,
                $first_registration, $has_turbo, $is_hybrid, $needs_repair, $price
            ]);
            
            $success = " Το αυτοκίνητο προστέθηκε με επιτυχία!";
        } catch (PDOException $e) {
            $error = "Σφάλμα: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Προσθήκη Αυτοκινήτου</title>
    <link rel="stylesheet" href="style_<?php echo $theme; ?>.css">
    <style>
        body { font-family: Arial; margin: 0; background: #f5f5f5; }
        .container { max-width: 600px; margin: 30px auto; padding: 20px; }
        nav { background: #2c3e50; padding: 15px 20px; }
        nav a { color: white; text-decoration: none; margin-right: 20px; }
        .form-box { background: white; padding: 25px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; text-align: center; }
        input, select { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ddd; box-sizing: border-box; }
        button { background: #f39c12; color: white; padding: 12px; border: none; width: 100%; cursor: pointer; font-size: 16px; }
        .error { color: red; background: #ffe6e6; padding: 10px; border-radius: 3px; margin-bottom: 15px; }
        .success { color: green; background: #e6ffe6; padding: 10px; border-radius: 3px; margin-bottom: 15px; }
        .checkbox { width: auto; margin-right: 10px; }
        .checkboxes { margin: 15px 0; }
        .checkboxes label { margin-right: 20px; }
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
    <nav>
        <a href="index.php"> Αρχική</a>
        <a href="search.php"> Αναζήτηση</a>
        <a href="add_car.php"> Προσθήκη</a>
        <a href="logout.php"> Αποσύνδεση</a>
        <span style="color: white; margin-left: auto;">Γεια σου, <?php echo $_SESSION['first_name']; ?>!</span>
    </nav>
    
    <div class="container">
        <div class="form-box">
            <h2> Προσθήκη Νέου Αυτοκινήτου</h2>
            
            <?php if($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="success"><?php echo $success; ?></div>
                <p><a href="add_car.php"> Προσθήκη άλλου αυτοκινήτου</a></p>
                <p><a href="search.php"> Δείτε όλα τα αυτοκίνητα</a></p>
            <?php else: ?>
            
            <form method="POST">
                <input type="text" name="brand" placeholder="Μάρκα *" required>
                <input type="text" name="model" placeholder="Μοντέλο *" required>
                
                <select name="body_type" required>
                    <option value="">Επιλογή Τύπου *</option>
                    <option value="mini">Mini</option>
                    <option value="hatchback">Hatchback</option>
                    <option value="sedan">Sedan</option>
                    <option value="SUV">SUV</option>
                </select>
                
                <input type="number" name="engine_cc" placeholder="Κυβικά (cc) *" required>
                
                <select name="fuel_type" required>
                    <option value="">Επιλογή Καυσίμου *</option>
                    <option value="Petrol">Βενζίνη</option>
                    <option value="Diesel">Πετρέλαιο</option>
                    <option value="Hybrid">Υβριδικό</option>
                    <option value="Electric">Ηλεκτρικό</option>
                </select>
                
                <input type="number" name="kilometers" placeholder="Χιλιόμετρα *" required>
                <input type="date" name="first_registration" required>
                <input type="number" step="0.01" name="price" placeholder="Τιμή (€) *" required>
                
                <div class="checkboxes">
                    <label>
                        <input type="checkbox" name="has_turbo" class="checkbox"> Έχει turbo
                    </label>
                    <label>
                        <input type="checkbox" name="is_hybrid" class="checkbox"> Είναι υβριδικό
                    </label>
                    <label>
                        <input type="checkbox" name="needs_repair" class="checkbox"> Χρειάζεται επισκευή
                    </label>
                </div>
                
                <button type="submit"> Προσθήκη</button>
            </form>
            
            <?php endif; ?>
        </div>
    </div>
    <footer style="text-align: center; padding: 20px; margin-top: 50px; background: #2c3e50; color: white;">
        © 2026 WebCars
    </footer>
</body>
</html>