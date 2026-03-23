<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: my_cars.php");
    exit();
}

$image_id = $_POST['image_id'] ?? '';
$filename = $_POST['filename'] ?? '';

if(empty($image_id) || empty($filename)) {
    $_SESSION['error'] = "Λείπουν απαραίτητα στοιχεία";
    header("Location: my_cars.php");
    exit();
}

$stmt = $pdo->prepare("
    SELECT ci.* FROM car_images ci 
    JOIN cars c ON ci.car_id = c.id 
    WHERE ci.id = ? AND c.user_id = ?
");
$stmt->execute([$image_id, $_SESSION['user_id']]);
$image = $stmt->fetch();

if(!$image) {
    $_SESSION['error'] = "Η εικόνα δεν βρέθηκε ή δεν ανήκει σε εσάς";
    header("Location: my_cars.php");
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM car_images WHERE id = ?");
    $stmt->execute([$image_id]);
    
    $file_path = "uploads/" . $filename;
    if(file_exists($file_path)) {
        unlink($file_path);
    }
    
    $_SESSION['success'] = "Η εικόνα διαγράφηκε επιτυχώς";
    
} catch(PDOException $e) {
    $_SESSION['error'] = "Σφάλμα κατά τη διαγραφή: " . $e->getMessage();
}

$referer = $_SERVER['HTTP_REFERER'] ?? 'my_cars.php';
header("Location: $referer");
exit();