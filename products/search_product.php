<?php
include '../connection.php';
session_start();
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $sql = "SELECT * FROM products WHERE 
                id LIKE '%$search%' OR
                title LIKE '%$search%' OR 
                slug LIKE '%$search%' OR 
                description LIKE '%$search%' OR 
                price LIKE '%$search%' OR 
                categories LIKE '%$search%' OR 
                tags LIKE '%$search%' OR 
                status LIKE '%$search%'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        while ($product = $result->fetch_assoc()) {
            echo "<tr>
                                 <td>" . $product['id'] . "</td>
                                <td>" . $product['title'] . "</td>
                                <td>" . $product['slug'] . "</td>
                                <td>" . $product['description'] . "</td>
                                <td>" . $product['price'] . "</td>
                                 <td>
                                    <img src='" . $product['image'] . "'  height='100px' width='100px' alt='Image'> 
                                </td>
                                <td>" . $product['categories'] . "</td>
                                <td>" . $product['tags'] . "</td>
                                <td>" . ($product['status'] == 1 ? 'Active' : 'InActive') . "</td>

                                <td>
                                        <form action='delete_product.php' method='POST' style='display:inline;'>
                                            <input type='hidden' name='delete_product' value='" . $product['id'] . "'>
                                            <button type='submit' class='btn btn-info' >Delete</button>
                                        </form>
                                        <button class='btn btn-info'><a href='edit_product.php?id=" . $product['id'] . "'           class='text-dark'>Edit</a></button>

                                </td>
                            </tr>";

            // include 'product_detail.php';
        }
    } else {
        echo "<tr><td colspan='12'>No results found.</td></tr>";
    }
}
