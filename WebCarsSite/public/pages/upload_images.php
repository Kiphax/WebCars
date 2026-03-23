<?php
session_start();
require_once 'config.php';
require_once 'theme.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$car_id = $_GET['car_id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ? AND user_id = ?");
$stmt->execute([$car_id, $_SESSION['user_id']]);
$car = $stmt->fetch();

if(!$car) {
    $_SESSION['error'] = "Το αυτοκίνητο δεν βρέθηκε ή δεν ανήκει σε εσάς";
    header("Location: my_cars.php");
    exit();
}

$car_name = htmlspecialchars($car['brand'] . ' ' . $car['model']);
$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['images'])) {
    $upload_count = 0;

    if(count($_FILES['images']['name']) < 2) {
        $error = "Πρέπει να επιλέξετε τουλάχιστον 2 εικόνες";
    } else {
        
        for($i = 0; $i < count($_FILES['images']['name']); $i++) {
            if($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }
            
            
            $file_type = $_FILES['images']['type'][$i];
            if($file_type != 'image/jpeg' && $file_type != 'image/jpg') {
                $error .= "Το αρχείο " . htmlspecialchars($_FILES['images']['name'][$i]) . " δεν είναι JPG.<br>";
                continue;
            }
            
        
            $file_size = $_FILES['images']['size'][$i];
            if($file_size > 250 * 1024) {
                $error .= "Το αρχείο " . htmlspecialchars($_FILES['images']['name'][$i]) . " είναι μεγαλύτερο από 250KB.<br>";
                continue;
            }
            
            
            $original_name = $_FILES['images']['name'][$i];
            $extension = pathinfo($original_name, PATHINFO_EXTENSION);
            $new_filename = "car_" . $car_id . "_" . time() . "_" . $i . "." . $extension;
            $upload_path = "uploads/" . $new_filename;
            
            
            $description = !empty($_POST['descriptions'][$i]) ? $_POST['descriptions'][$i] : '';
            
            if(move_uploaded_file($_FILES['images']['tmp_name'][$i], $upload_path)) {
                
                $stmt = $pdo->prepare("INSERT INTO car_images (car_id, filename, description) VALUES (?, ?, ?)");
                $stmt->execute([$car_id, $new_filename, $description]);
                $upload_count++;
            } else {
                $error .= "Σφάλμα κατά το upload του αρχείου " . htmlspecialchars($_FILES['images']['name'][$i]) . ".<br>";
            }
        }
        
        if($upload_count > 0) {
            $success = "Ανεβήκαν επιτυχώς " . $upload_count . " εικόνες!";
        }
    } 
} 

$stmt = $pdo->prepare("SELECT * FROM car_images WHERE car_id = ? ORDER BY uploaded_at DESC");
$stmt->execute([$car_id]);
$existing_images = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Εικόνων - WebCars</title>
    <link rel="stylesheet" href="style_<?php echo $theme; ?>.css">
    <style>
        body { font-family: Arial; margin: 20px; }
        .error { color: red; background: #ffe6e6; padding: 10px; border-radius: 3px; margin: 10px 0; }
        .success { color: green; background: #e6ffe6; padding: 10px; border-radius: 3px; margin: 10px 0; }
        .image-item { border: 1px solid #ccc; padding: 10px; margin: 10px 0; }
        .image-item img { max-width: 200px; }
    </style>
</head>
<body>
    <h1>Upload Εικόνων για: <?php echo $car_name; ?></h1>
    
    <p><a href="my_cars.php">← Πίσω στα Αυτοκίνητά Μου</a></p>
    
    <?php if($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if($success): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <h2>Προσθήκη Νέων Εικόνων</h2>
    <form method="POST" enctype="multipart/form-data">
        <p><strong>Επιλέξτε εικόνες (μόνο JPG, max 250KB το καθένα):</strong></p>
        <input type="file" name="images[]" multiple accept="image/jpeg,image/jpg" required><br><br>
        
        <p><strong>Προσθέστε περιγραφή (λεζάντα) για κάθε εικόνα:</strong></p>
        <div id="descriptionsContainer">
        </div>
        
        <button type="submit" style="padding: 10px 20px;">Ανέβασμα Εικόνων</button>
    </form>
    
    <?php if(count($existing_images) > 0): ?>
        <h2>Υπάρχουσες Εικόνες</h2>
        <?php foreach($existing_images as $img): ?>
            <div class="image-item">
                <img src="uploads/<?php echo htmlspecialchars($img['filename']); ?>" 
                     alt="<?php echo htmlspecialchars($img['description']); ?>">
                <p><strong>Περιγραφή:</strong> <?php echo htmlspecialchars($img['description']); ?></p>
                <p><strong>Αρχείο:</strong> <?php echo htmlspecialchars($img['filename']); ?></p>
                
                
                <form method="POST" action="delete_images.php" style="display: inline;">
                    <input type="hidden" name="image_id" value="<?php echo $img['id']; ?>">
                    <input type="hidden" name="filename" value="<?php echo $img['filename']; ?>">
                    <button type="submit" onclick="return confirm('Διαγραφή αυτής της εικόνας;')">
                        Διαγραφή Εικόνας
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Δεν υπάρχουν εικόνες για αυτό το αυτοκίνητο ακόμα.</p>
    <?php endif; ?>
    
    <script>

        document.querySelector('input[name="images[]"]').addEventListener('change', function(e) {
            const container = document.getElementById('descriptionsContainer');
            container.innerHTML = '';
            
            for(let i = 0; i < this.files.length; i++) {
                const div = document.createElement('div');
                div.style.margin = '10px 0';
                div.innerHTML = `
                    <label>Περιγραφή για "${this.files[i].name}":</label><br>
                    <input type="text" name="descriptions[]" 
                           placeholder="Π.χ. Μπροστινή όψη, Εσωτερικό, Κινητήρας" 
                           style="width: 300px; padding: 5px;">
                `;
                container.appendChild(div);
            }
        });
    </script>
</body>
</html>