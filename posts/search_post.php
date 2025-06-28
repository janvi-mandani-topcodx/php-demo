<?php
include '../connection.php';
session_start();
if (isset($_POST['search'])) {
    $userId = $_SESSION['id'];
    $search = $_POST['search'];
    $sql = "SELECT * FROM posts WHERE 
                (id LIKE '%$search%' OR
                user_id LIKE '%$search%' OR
                title LIKE '%$search%' OR 
                slug LIKE '%$search%' OR 
                description LIKE '%$search%' OR 
                status LIKE '%$search%')
                AND user_id ='" . $_SESSION['id'] . "'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                                <td>" . $row['id'] . "</td>
                                <td>" . $row['user_id'] . "</td>
                                <td>" . $row['title'] . "</td>
                                <td>" . $row['slug'] . "</td>
                                <td>" . $row['description'] . "</td>
                                <td>
                                    <img src='" . $row['image'] . "'  height='100px' width='100px' alt='Image'> 
                                </td>
                                <td>" . $row['status'] . "</td>

                                <td>
                                        <form action='delete_post.php' method='POST' style='display:inline;'>
                                            <input type='hidden' name='delete_post' value='" . $row['id'] . "'>
                                            <button type='submit ' class='btn btn-info' >Delete</button>
                                        </form>
                                        <button class='btn btn-info'><a href='edit_post.php?id=" . $row['id'] . "' class='text-dark'>Edit</a></button>

                                </td>
                            </tr>";
        }
    } else {
        echo "<tr><td colspan='12'>No results found.</td></tr>";
    }
}
