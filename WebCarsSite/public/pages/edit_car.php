<?php
session_start();
$default_theme = 'light';

require_once 'theme.php';
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM cars WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$car = $stmt->fetch();

if (!$car) {
    header("Location: add_car.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
            
            $stmt = $pdo->prepare("UPDATE cars SET brand = ?, model = ?, body_type = ?, engine_cc = ?, fuel_type = ?, kilometers = ?, first_registration = ?, has_turbo = ?, is_hybrid = ?, needs_repair = ?, price = ? WHERE user_id = ?");
    
            $stmt->execute([
                $brand, $model, $body_type, $engine_cc, $fuel_type, $kilometers,
                $first_registration, $has_turbo, $is_hybrid, $needs_repair,
                $price, $_SESSION['user_id']
            ]);
            
            $success = "Ενημέρωση επιτυχής!";
            
            $stmt = $pdo->prepare("SELECT * FROM cars WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $car = $stmt->fetch();
            
        } catch (PDOException $e) {
            $error = "Σφάλμα: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Επεξεργασία Αυτοκινήτου - WebCars</title>
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
    <nav>
        <a href="index.php"> Αρχική</a>
        <a href="search.php"> Αναζήτηση</a>
        <a href="add_car.php"> Προσθήκη</a>
        <a href="logout.php"> Αποσύνδεση</a>
        <span style="color: white; margin-left: auto;">Γεια σου, <?php echo $_SESSION['first_name']; ?>!</span>
    </nav>
    
    <div class="container">
        <div class="form-box">
            <h2> Επεξεργασία Αυτοκινήτου</h2>
            
            <?php if($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="text" name="brand" value="<?php echo htmlspecialchars($car['brand']); ?>" placeholder="Μάρκα *" required>
                <input type="text" name="model" value="<?php echo htmlspecialchars($car['model']); ?>" placeholder="Μοντέλο *" required>
                
                <select name="body_type" required>
                    <option value="">Επιλογή Τύπου *</option>
                    <option value="mini" <?php echo $car['body_type'] == 'mini' ? 'selected' : ''; ?>>Mini</option>
                    <option value="hatchback" <?php echo $car['body_type'] == 'hatchback' ? 'selected' : ''; ?>>Hatchback</option>
                    <option value="sedan" <?php echo $car['body_type'] == 'sedan' ? 'selected' : ''; ?>>Sedan</option>
                    <option value="SUV" <?php echo $car['body_type'] == 'SUV' ? 'selected' : ''; ?>>SUV</option>
                </select>
                
                <input type="number" name="engine_cc" value="<?php echo $car['engine_cc']; ?>" placeholder="Κυβικά (cc) *" required>
                
                <select name="fuel_type" required>
                    <option value="">Επιλογή Καυσίμου *</option>
                    <option value="Petrol" <?php echo $car['fuel_type'] == 'Petrol' ? 'selected' : ''; ?>>Βενζίνη</option>
                    <option value="Diesel" <?php echo $car['fuel_type'] == 'Diesel' ? 'selected' : ''; ?>>Πετρέλαιο</option>
                    <option value="Hybrid" <?php echo $car['fuel_type'] == 'Hybrid' ? 'selected' : ''; ?>>Υβριδικό</option>
                    <option value="Electric" <?php echo $car['fuel_type'] == 'Electric' ? 'selected' : ''; ?>>Ηλεκτρικό</option>
                </select>
                
                <input type="number" name="kilometers" value="<?php echo $car['kilometers']; ?>" placeholder="Χιλιόμετρα *" required>
                <input type="date" name="first_registration" value="<?php echo $car['first_registration']; ?>" required>
                <input type="number" step="0.01" name="price" value="<?php echo $car['price']; ?>" placeholder="Τιμή (€) *" required>
                
                <div class="checkboxes">
                    <label>
                        <input type="checkbox" name="has_turbo" class="checkbox" <?php echo $car['has_turbo'] ? 'checked' : ''; ?>> Έχει turbo
                    </label>
                    <label>
                        <input type="checkbox" name="is_hybrid" class="checkbox" <?php echo $car['is_hybrid'] ? 'checked' : ''; ?>> Είναι υβριδικό
                    </label>
                    <label>
                        <input type="checkbox" name="needs_repair" class="checkbox" <?php echo $car['needs_repair'] ? 'checked' : ''; ?>> Χρειάζεται επισκευή
                    </label>
                </div>
                
                <button type="submit"> Αποθήκευση Αλλαγών</button>
            </form>
            
            <p style="text-align: center; margin-top: 20px;">
                <a href="index.php"> Πίσω στην Αρχική</a>
            </p>
        </div>
    </div>
    <footer style="text-align: center; padding: 20px; background: #2c3e50; color: white; margin-top: auto;">
        © 2026 WebCars
    </footer>
</body>
</html>