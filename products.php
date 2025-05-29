<?php
include 'connect.php'; // Підключаємо файл з підключенням до бази даних

// Get the selected category from the request
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Prepare the SQL query
$sql = "SELECT * FROM products";
if (!empty($category)) {
    $sql .= " WHERE category = :category";
}

$stmt = $pdo->prepare($sql);

if (!empty($category)) {
    $stmt->bindParam(':category', $category);
}

$stmt->execute();

// Fetch all products
$products = $stmt->fetchAll();

// Return products as JSON if requested by AJAX
if (isset($_GET['format']) && $_GET['format'] === 'json') {
    header('Content-Type: application/json');
    echo json_encode($products);
    exit;
}

// Otherwise, output HTML
if ($products) {
    foreach ($products as $product) {
        echo '<div class="product">';
        echo '<img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '" class="product-image">';
        echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
        echo '<p>' . htmlspecialchars($product['description']) . '</p>';
        echo '<p>Ціна: ' . number_format($product['price'], 2, '.', '') . ' грн</p>';
        echo '<button class="add-to-cart" data-id="' . $product['id'] . '" data-name="' . htmlspecialchars($product['name']) . '" data-price="' . $product['price'] . '" data-image="' . htmlspecialchars($product['image']) . '">Додати у кошик</button>';
        echo '</div>';
    }
} else {
    echo '<p>Немає товарів.</p>';
}
?>