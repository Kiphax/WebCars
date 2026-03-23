<?php
session_start(); 
$default_theme = 'light';

require_once 'theme.php'; 
require_once 'config.php';

$error = '';
$success = '';


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    $captcha_answer = $num1 + $num2;
    $_SESSION['captcha_answer'] = $captcha_answer;
    $captcha_question = "$num1 + $num2";
} else {
    $captcha_question = '';
}
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $activation_code = trim($_POST['activation_code']);
    $user_captcha = trim($_POST['captcha']);
    
    if(empty($username) || empty($password) || empty($activation_code) || empty($user_captcha)) {
        $error = "Συμπληρώστε όλα τα πεδία";
    } elseif(strlen($activation_code) != 5) {
        $error = "Ο κωδικός ενεργοποίησης πρέπει να είναι 5 ψηφία";
    } elseif($user_captcha != $_SESSION['captcha_answer']) {
        $error = "Λάθος απάντηση CAPTCHA";
    } else {
        $password_hash = hash('sha512', $password);
        
        $stmt = $pdo->prepare("SELECT id, first_name, is_active FROM users WHERE username = ? AND password_hash = ? AND activation_code = ?");
        $stmt->execute([$username, $password_hash, $activation_code]);
        $user = $stmt->fetch();
        
        if ($user) {
            if($user['is_active'] == 1) {
                $error = "Ο λογαριασμός είναι ήδη ενεργός";
            } else {
                $stmt = $pdo->prepare("UPDATE users SET is_active = 1 WHERE id = ?");
                if ($stmt->execute([$user['id']])) {
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                $_SESSION['first_name'] = $user['first_name'] ?? '';
                $_SESSION['is_active'] = 1;

                header("Location: index.php");
                exit();
                } else {
                    $error = "Παρουσιάστηκε σφάλμα. Δοκιμάστε ξανά.";
                }
            }
        } else {
            $error = "Λάθος στοιχεία ενεργοποίησης";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ενεργοποίηση - WebCars</title>
    <link rel="stylesheet" href="style_<?php echo $theme; ?>.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content {
            flex: 1;
        }
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
        <a href="register.php" style="color: white; text-decoration: none; margin-right: 15px;">Εγγραφή</a>
        <a href="login.php" style="color: white; text-decoration: none; margin-right: 15px;">Σύνδεση</a>
        <a href="activate.php" style="color: white; padding: 5px 10px; text-decoration: none; margin-right: 15px;">Ενεργοποίηση</a>
    </nav>
    
    <div class="content" style="flex: 1;">
        <div style="max-width: 500px; margin: 30px auto; padding: 20px;">
            <div style="background: white; padding: 30px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                <h2 style="text-align: center; color: #2c3e50; margin-bottom: 20px;">Ενεργοποίηση Λογαριασμού</h2>
                
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
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                           style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd;">
                    
                    <input type="password" name="password" placeholder="Κωδικός" required
                           style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd;">
                    
                    <input type="text" name="activation_code" placeholder="Κωδικός Ενεργοποίησης (5 ψηφία)" 
                           maxlength="5" pattern="[0-9]{5}" title="5 αριθμητικά ψηφία" required
                           value="<?php echo isset($_POST['activation_code']) ? htmlspecialchars($_POST['activation_code']) : ''; ?>"
                           style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd;">
                    
                    <div style="margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 5px; border: 1px solid #ddd;">
                        <p style="margin: 0 0 10px 0; font-weight: bold;">CAPTCHA: Πόσο κάνει <strong><?php echo $captcha_question; ?></strong>;</p>
                        <input type="text" name="captcha" placeholder="Απάντηση" required
                               style="width: 100%; padding: 10px; border: 1px solid #ccc;">
                    </div>
                    
                    <button type="submit" style="width: 100%; padding: 12px; color: white; border: none; cursor: pointer; font-size: 16px;">
                        Ενεργοποίηση Λογαριασμού
                    </button>
                </form>
                
                <div style="margin-top: 20px; text-align: center;">
                    <p>Δεν έχετε λογαριασμό; <a href="register.php">Εγγραφή</a></p>
                    <p>Έχετε ήδη ενεργοποιημένο λογαριασμό; <a href="login.php">Σύνδεση</a></p>
                </div>
            </div>
        </div>
    </div>

    <footer style="text-align: center; padding: 20px; background: #2c3e50; color: white; margin-top: auto;">
        © 2026 WebCars
    </footer>
</body>
</html>