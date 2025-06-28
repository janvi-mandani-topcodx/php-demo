<?php
include '../connection.php';
include '../bootstrap.php';

$products = $con->query("SELECT * FROM products WHERE status = 1");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../jquery-3.7.1.min.js"></script>

</head>

<body>
    <?php
    include 'header_product.php'
    ?>
    <div class='container'>
        <div class='row'>
            <?php
            while ($product = $products->fetch_assoc()) {

                echo "<div class='col-4 my-2'>
                        <div class='card'>
                            <a href='product_details.php?slug=" . $product['slug'] . "' class='text-decoration-none'>
                                <div class='bg-image hover-overlay'>
                                    <img src='" . $product['image'] . "'  class='img-fluid image-height'/>
                                </div>
                            </a>
                                    <div class='card-body'>
                                        <h5 class='card-title text-dark '>" . $product['title'] . " - " . $product['categories'] . "</h5>
                                        <p class='card-text text-dark'>" . $product['description'] . "</p>
                                        <div class='d-flex justify-content-between align-items-center my-3'>
                                            <h5 class='text-dark'>$" . $product['price'] . "</h5>";

                if (isset($_SESSION['id'])) {
                    echo "<button class='my-4 btn btn-dark fw-bold shadow btnAddAction' data-image='" . $product['image'] . "' data-title='" . $product['title'] . "' data-price = '" . $product['price'] . "' data-id = '" . $product['id'] . "' data-bs-toggle='offcanvas' data-bs-target='#demo' >Add To Cart 
                                                        <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-cart' viewBox='0 0 16 16'>
                                                        <path d='M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2'/>
                                                        </svg>
                                                    </button>";
                } else {
                    echo "";
                }
                echo "</div>
                                    </div>
                        </div>  
                    </div>";
            }
            ?>
        </div>
    </div>
    
</body>

</html>