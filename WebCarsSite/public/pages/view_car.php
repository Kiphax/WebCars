<?php
session_start();
$default_theme = 'light';

require_once 'theme.php';
require_once 'config.php';


$id = $_GET['id'] ?? 0;

$sql = "SELECT c.*, u.first_name, u.last_name, u.phone 
        FROM cars c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$car = $stmt->fetch();

if(!$car) {
    die("Το αυτοκίνητο δεν βρέθηκε");
}


$images_sql = "SELECT * FROM car_images WHERE car_id = ? ORDER BY uploaded_at DESC";
$images_stmt = $pdo->prepare($images_sql);
$images_stmt->execute([$id]);
$images = $images_stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?> - WebCars</title>
    <link rel="stylesheet" href="style_<?php echo $theme; ?>.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h1>
    
    <p><strong> Τιμή:</strong> <?php echo number_format($car['price'], 2); ?> €</p>
    <p><strong> Χιλιόμετρα:</strong> <?php echo number_format($car['kilometers']); ?></p>
    <p><strong> Καύσιμο:</strong> <?php echo htmlspecialchars($car['fuel_type']); ?></p>
    <p><strong> Έτος:</strong> <?php echo $car['first_registration']; ?></p>
    <p><strong> Κυβικά:</strong> <?php echo $car['engine_cc']; ?> cc</p>
    <p><strong> Τύπος Αμαξώματος:</strong> <?php echo htmlspecialchars($car['body_type']); ?></p>
    <p><strong> Turbo:</strong> <?php echo $car['has_turbo'] ? 'Ναι' : 'Όχι'; ?></p>
    <p><strong> Υβριδικό:</strong> <?php echo $car['is_hybrid'] ? 'Ναι' : 'Όχι'; ?></p>
    <p><strong> Χρειάζεται Επισκευή:</strong> <?php echo $car['needs_repair'] ? 'Ναι' : 'Όχι'; ?></p>
    
    <hr>
    
    <?php if(count($images) > 0): ?>
        <h2> Εικόνες</h2>
        <?php foreach($images as $img): ?>
            <div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0; display: inline-block;">
                <img src="uploads/<?php echo htmlspecialchars($img['filename']); ?>" 
                     alt="<?php echo htmlspecialchars($img['description']); ?>"
                     style="max-width: 300px;">
                <p><strong>Περιγραφή:</strong> <?php echo htmlspecialchars($img['description']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Δεν υπάρχουν διαθέσιμες εικόνες.</p>
    <?php endif; ?>
    
    <hr>
    
    <h2> Στοιχεία Πωλητή</h2>
    <p><strong>Ονοματεπώνυμο:</strong> <?php echo htmlspecialchars($car['first_name'] . ' ' . $car['last_name']); ?></p>
    <?php if(isset($_SESSION['user_id'])): ?>
        <p><strong> Τηλέφωνο:</strong> <?php echo htmlspecialchars($car['phone'] ?? 'Δεν δόθηκε'); ?></p>
    <?php else: ?>
        <p><em>Συνδεθείτε για να δείτε το τηλέφωνο επικοινωνίας</em></p>
    <?php endif; ?>
    
    <p style="margin-top: 30px;">
        <a href="search.php">← Πίσω στην Αναζήτηση</a>
    </p>
</body>
</html>