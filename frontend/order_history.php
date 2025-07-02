<?php
include '../connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include '../bootstrap.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <?php
    include 'header_product.php';
    ?>
    <div class="container ">

        <h2>Order History</h2>

        <!-- <div class="d-flex justify-content-between">
            <input type="text" id="search_post" placeholder="search...." class="searchButton">
        </div> -->



        <table id="result_posts" class="table table-striped">
            <thead>
                <tr>
                    <th>Order Id</th>
                    <th>Date</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $order_history = $con->query("SELECT * FROM orders");
                while ($history = $order_history->fetch_assoc()) {
                    echo "<tr>
                            <td><a href='order_details.php?id=" . $history['id'] . "'>" . $history['id'] . "</a></td>
                            <td>" . $history['created_at'] . "</td>
                            <td>$" . $history['total'] . "</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- <script>
        $(document).ready(function() {
            $('#search_post').on('keyup', function() {
                var query = $(this).val();
                if (query.length > 1) {
                    $.ajax({
                        url: "search_post.php",
                        type: "POST",
                        data: {
                            search: query
                        },
                        success: function(data) {
                            $("#result_posts tbody").html(data);
                        },
                    });
                } else {
                    loadAllRecords();
                }
            });

            function loadAllRecords() {
                $.ajax({
                    url: "search_post.php",
                    type: "POST",
                    data: {
                        search: ""
                    },
                    success: function(data) {
                        $("#result_posts tbody").html(data);
                    },
                });
            }
        });
    </script> -->
</body>

</html>