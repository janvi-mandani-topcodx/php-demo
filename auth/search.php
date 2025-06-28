<?php
include '../connection.php';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $sql =  "SELECT * FROM users WHERE 
            id LIKE '%$search%' OR
            first_name LIKE '%$search%' OR 
            last_name LIKE '%$search%' OR 
            email LIKE '%$search%' OR 
            phone_number LIKE '%$search%' OR 
            date_of_birth LIKE '%$search%' OR 
            gender LIKE '%$search%'  OR 
            card_number LIKE '%$search%' OR 
            irn LIKE '%$search%' OR 
            valid_to LIKE '%$search%'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                        <td>" . $row['id'] . "</td>
                        <td>" . $row['first_name'] . "</td>
                        <td>" . $row['last_name'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['phone_number'] . "</td>
                        <td>" . $row['date_of_birth'] . "</td>
                        <td>" . ucfirst($row['gender']) . "</td>
                        <td>" . $row['card_number'] . "</td>
                        <td>" . $row['irn'] . "</td>
                        <td>" . $row['valid_to'] . "</td>
                        <td>
                                <form action='delete.php' method='POST' style='display:inline;'>
                                    <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                                    <button type='submit ' class='btn btn-info' >Delete</button>
                                </form>
                                <button class='btn btn-info'><a href='edit.php?id=" . $row['id'] . "' class='text-dark'>Edit</a></button>
                            
                        </td>
                    </tr>";
        }
    } else {
        echo "<tr><td colspan='12'>No results found.</td></tr>";
    }
}
