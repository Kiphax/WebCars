<?php
session_start();

require_once 'theme.php';
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];

    if(strlen($password) < 10) {
        $error = "Ο κωδικός πρέπει να έχει τουλάχιστον 10 χαρακτήρες";
    } elseif(!preg_match('/^[A-Za-z0-9!\-_@]+$/', $password)) {
        $error = "Ο κωδικός πρέπει να περιέχει μόνο: A-Z, a-z, 0-9, !, -, _, @";
    }
    
    if(empty($error)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $error = "Το username υπάρχει ήδη";
        }
    }
    
    if(empty($error)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Το email υπάρχει ήδη";
        }
    }
    
    if (empty($error)) {
        $password_hash = hash('sha512', $password);
        $activation_code = rand(10000, 99999);
        
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, first_name, last_name, phone, activation_code) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$username, $email, $password_hash, $first_name, $last_name, $phone, $activation_code])) {
            $success = "Εγγραφή επιτυχής! Κωδικός ενεργοποίησης: $activation_code";
        } else {
            $error = "Σφάλμα εγγραφής";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Εγγραφή - WebCars</title>
    <link rel="stylesheet" href="style_<?php echo $theme; ?>.css">
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
        <a href="register.php" style="color: white; padding: 5px 10px; text-decoration: none; margin-right: 15px;">Εγγραφή</a>
        <a href="login.php" style="color: white; text-decoration: none; margin-right: 15px;">Σύνδεση</a>
        <a href="activate.php" style="color: white; text-decoration: none; margin-right: 15px;">Ενεργοποίηση</a>
    </nav>
    
    <div style="max-width: 500px; margin: 30px auto; padding: 20px;">
        <div style="background: white; padding: 30px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <h2 style="text-align: center; color: #2c3e50; margin-bottom: 20px;">Εγγραφή Χρήστη</h2>
            
            <?php if($error): ?>
                <div style="color: red; background: #ffe6e6; padding: 10px; border-radius: 3px; margin-bottom: 15px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div style="color: green; background: #e6ffe6; padding: 10px; border-radius: 3px; margin-bottom: 15px;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required
                       style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd;">
                
                <input type="email" name="email" placeholder="Email" required
                       style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd;">
                
                <input type="password" name="password" 
                       placeholder="Κωδικός (min 10: A-Z, a-z, 0-9, !-_@)" 
                       minlength="10" required
                       pattern="[A-Za-z0-9!\-_@]{10,}"
                       title="Τουλάχιστον 10 χαρακτήρες: A-Z, a-z, 0-9, ! - _ @"
                       style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd;">
                
                <input type="text" name="first_name" placeholder="Όνομα" required
                       style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd;">
                
                <input type="text" name="last_name" placeholder="Επώνυμο" required
                       style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd;">
                
                <input type="text" name="phone" placeholder="Τηλέφωνο"
                       style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd;">
                
                <button type="submit" style="width: 100%; padding: 12px; background-color: #3498db; color: white; border: none; cursor: pointer; font-size: 16px;">
                    Εγγραφή
                </button>
            </form>
            
            <div style="margin-top: 20px; text-align: center;">
                <p>Έχετε ήδη λογαριασμό; <a href="login.php">Συνδεθείτε</a></p>
            </div>
        </div>
    </div>
    
    <footer style="text-align: center; padding: 20px; background: #2c3e50; color: white; margin-top: auto;">
        © 2026 WebCars
    </footer>
</body>
</html>