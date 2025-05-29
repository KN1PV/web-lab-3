<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Наші товари</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <h1 class="title">Наші товари</h1>
    
    <!-- Category filter -->
    <div class="category-filter">
        <button class="category-btn active" data-category="">Всі товари</button>
        <button class="category-btn" data-category="smartphones">Смартфони</button>
        <button class="category-btn" data-category="laptops">Ноутбуки</button>
        <button class="category-btn" data-category="headphones">Гарнітури</button>
    </div>
    
    <div class="product-container" id="productContainer">
        <?php
        include 'products.php';
        if (!$products) {
            echo '<p>Немає товарів.</p>';
        }
        ?>
    </div>
    
    <button class="cart-btn">
        <span class="cart-icon">🛒</span>
        Перегляд кошика
        <span class="cart-count">0</span>
    </button>
    
    <div class="modal" id="cartModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ваш кошик</h2>
                <button class="close-btn" id="closeCart">✕</button>
            </div>
            
            <div class="cart-items" id="cartItems">
                <p id="emptyCart">Ваш кошик порожній</p>
            </div>
            
            <div class="cart-total" id="cartTotal">
                Загалом: 0 грн
            </div>
            
            <button class="checkout-btn">Оформити замовлення</button>
        </div>
    </div>

    <script src="./script.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryButtons = document.querySelectorAll('.category-btn');
        
        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Get the selected category
                const category = this.getAttribute('data-category');
                
                // Reload products with the selected category
                loadProducts(category);
            });
        });
        
        function loadProducts(category) {
            const productContainer = document.getElementById('productContainer');
            
            // Fetch products from the server
            fetch('products.php?category=' + category)
                .then(response => response.text())
                .then(html => {
                    productContainer.innerHTML = html;
                    
                    // Reinitialize add to cart buttons
                    document.querySelectorAll('.add-to-cart').forEach(button => {
                        button.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            const name = this.getAttribute('data-name');
                            const price = parseFloat(this.getAttribute('data-price'));
                            const image = this.getAttribute('data-image');
                            
                            cart.addToCart(id, name, price, image);
                            cart.updateCart();
                            
                            cart.showNotification(`Товар "${name}" додано в кошик!`);
                        });
                    });
                })
                .catch(error => {
                    console.error('Error loading products:', error);
                    productContainer.innerHTML = '<p>Помилка при завантаженні товарів.</p>';
                });
        }
        
        // Reinitialize add to cart buttons for initial load
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const price = parseFloat(this.getAttribute('data-price'));
                const image = this.getAttribute('data-image');
                
                cart.addToCart(id, name, price, image);
                cart.updateCart();
                
                cart.showNotification(`Товар "${name}" додано в кошик!`);
            });
        });
    });
    </script>
</body>
</html>