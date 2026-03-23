<?php
session_start(); 
$default_theme = 'light';

require_once 'theme.php';
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $password_hash = hash('sha512', $password);
    
    
    $stmt = $pdo->prepare("SELECT id, username, first_name, is_active FROM users WHERE username = ? AND password_hash = ?");
    $stmt->execute([$username, $password_hash]);
    $user = $stmt->fetch();
    
    if ($user) {
        if ($user['is_active']) {
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['is_active'] = true;
            
            header("Location: index.php");
            exit;
        } else {
            $error = "Ο λογαριασμός δεν είναι ενεργοποιημένος";
        }
    } else {
        $error = "Λάθος username ή password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Σύνδεση - WebCars</title>
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
        <a href="register.php" style="color: white; text-decoration: none; margin-right: 15px;">Εγγραφή</a>
        <a href="login.php" style="color: white; padding: 5px 10px; text-decoration: none; margin-right: 15px;">Σύνδεση</a>
        <a href="activate.php" style="color: white; text-decoration: none; margin-right: 15px;">Ενεργοποίηση</a>
    </nav>
    
    <div style="max-width: 500px; margin: 30px auto; padding: 20px;">
        <div style="background: white; padding: 30px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <h2 style="text-align: center; color: #2c3e50; margin-bottom: 20px;">Σύνδεση</h2>
            
            <?php if($error): ?>
                <div style="color: red; background: #ffe6e6; padding: 10px; border-radius: 3px; margin-bottom: 15px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required
                       style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd;">
                
                <input type="password" name="password" placeholder="Κωδικός" required
                       style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd;">
                
                <button type="submit" style="width: 100%; padding: 12px; color: white; border: none; cursor: pointer; font-size: 16px;">
                    Σύνδεση
                </button>
            </form>
            
            <div style="margin-top: 20px; text-align: center;">
                <p>Δεν έχετε λογαριασμό; <a href="register.php">Εγγραφή</a></p>
                <p>Χρειάζεστε ενεργοποίηση; <a href="activate.php">Ενεργοποίηση</a></p>
            </div>
        </div>
    </div>
    
    <footer style="text-align: center; padding: 20px; background: #2c3e50; color: white; margin-top: 50px auto;">
        © 2026 WebCars
    </footer>
</body>
</html>