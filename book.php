<?php
// Get book details from URL parameters
$title = isset($_GET['title']) ? urldecode($_GET['title']) : 'Unknown Book';
$author = isset($_GET['author']) ? urldecode($_GET['author']) : 'Unknown Author';
$price = isset($_GET['price']) ? urldecode($_GET['price']) : 'N/A';
$image = isset($_GET['image']) ? urldecode($_GET['image']) : 'default.jpg';
$description = isset($_GET['description']) ? urldecode($_GET['description']) : 'No description available.';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - VerseWell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .book-container {
            max-width: 1000px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            background: #fff;
            padding: 2rem;
            border-radius: .5rem;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
        }
        .book-image {
            flex: 1 1 300px;
        }
        .book-image img {
            width: 100%;
            height: auto;
            object-fit: cover;
            max-height: 500px;
        }
        .book-info {
            flex: 1 1 500px;
        }
        .book-info h1 {
            font-size: 2.5rem;
            color: #333;
            margin-top: 0;
        }
        .book-info .author {
            font-size: 1.5rem;
            color: #666;
            padding: 1rem 0;
        }
        .book-info .price {
            font-size: 2rem;
            color: #e84393;
            padding: 1rem 0;
        }
        .book-info .description {
            font-size: 1.2rem;
            color: #333;
            padding: 1rem 0;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            margin-top: 1rem;
            padding: 1rem 3rem;
            background: #e84393;
            color: #fff;
            font-size: 1.2rem;
            cursor: pointer;
            border-radius: .5rem;
            text-decoration: none;
        }
        .btn:hover {
            background: #d6337d;
        }
    </style>
</head>
<body>
    <div class="book-container">
        <div class="book-image">
            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($title); ?>">
        </div>
        <div class="book-info">
            <h1><?php echo htmlspecialchars($title); ?></h1>
            <p class="author">by <?php echo htmlspecialchars($author); ?></p>
            <div class="price"><?php echo htmlspecialchars($price); ?></div>
            <p class="description"><?php echo htmlspecialchars($description); ?></p>
            <a href="#" class="btn add-to-cart" 
               data-title="<?php echo htmlspecialchars($title); ?>" 
               data-price="<?php echo htmlspecialchars($price); ?>" 
               data-img="<?php echo htmlspecialchars($image); ?>">
                Add to Cart
            </a>
        </div>
    </div>

    <script>
        // Add to cart functionality
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const title = this.getAttribute('data-title');
                const price = this.getAttribute('data-price');
                const img = this.getAttribute('data-img');
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                cart.push({title, price, img});
                localStorage.setItem('cart', JSON.stringify(cart));
                alert('Book added to cart!');
            });
        });
    </script>
</body>
</html>