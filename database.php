<?php
include "connection.php";
$databaseName = 'php';
$sql = "DROP DATABASE IF EXISTS $databaseName";
if ($con->query($sql)) {
    echo "Database drop successfully";
} else {
    echo "Error creating database: " . $con->error;
}
$sql = "CREATE DATABASE $databaseName ";
if ($con->query($sql)) {
    echo "<br>Database created successfully";
} else {
    echo "Error creating database: " . $con->error;
}

$sqltable = "CREATE TABLE $databaseName.users (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        first_name varchar(100),
        last_name varchar(100),
        email varchar(100) UNIQUE,
        password varchar(255),
        phone_number varchar(15),
        date_of_birth date,
        gender varchar(10),
        card_number varchar(50),
        irn varchar(50),
        valid_to varchar(50)
    )";

if ($con->query($sqltable)) {
    echo "<br>Users Table created successfully";
} else {
    echo "<br>Error creating table: " . $con->error;
}

$post = "CREATE TABLE $databaseName.posts (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id int ,
        title varchar(100),
        slug varchar(100),
        description varchar(100),
        image varchar(100),
        status BOOLEAN,
        CONSTRAINT user_post_id FOREIGN KEY (user_id)
        REFERENCES users(id)
    )";

if ($con->query($post)) {
    echo "<br>Posts Table created successfully";
} else {
    echo "<br>Error creating table: " . $con->error;
}

$postComment = "CREATE TABLE $databaseName.post_comments (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id int ,
        post_id int ,
        comment varchar(100),
        created_at varchar(100),
        CONSTRAINT user_post_comment_id FOREIGN KEY (post_id)
        REFERENCES posts(id),
        CONSTRAINT post_user_comment_id FOREIGN KEY (user_id)
        REFERENCES users(id)
    )";

if ($con->query($postComment)) {
    echo "<br>Comment Table created successfully";
} else {
    echo "<br>Error creating table: " . $con->error;
}

$product = "CREATE TABLE $databaseName.products (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        title varchar(100),
        slug varchar(100),
        price int,
        image varchar(100),
        description varchar(400),
        categories varchar(100),
        tags varchar(100),
        status BOOLEAN
    )";

if ($con->query($product)) {
    echo "<br>product Table created successfully";
} else {
    echo "<br>Error creating table: " . $con->error;
}

$cart = "CREATE TABLE $databaseName.carts (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id int ,
        product_id int,
        quantity  int,
        CONSTRAINT cart_user_id FOREIGN KEY (user_id)
        REFERENCES users(id),
        CONSTRAINT cart_product_id FOREIGN KEY (product_id)
        REFERENCES products(id)
    )";

if ($con->query($cart)) {
    echo "<br>cart Table created successfully";
} else {
    echo "<br>Error creating table: " . $con->error;
}

$orders = "CREATE TABLE $databaseName.orders (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id int ,
        shipping_address  json,
        delivery varchar(100),
        mobile_number varchar(15),
        pincode varchar(20),
        created_at varchar(100),
        total int,
        shipping_cost int,
        CONSTRAINT order_user_id FOREIGN KEY (user_id)
        REFERENCES users(id)
    )";

if ($con->query($orders)) {
    echo "<br>order Table created successfully";
} else {
    echo "<br>Error creating table: " . $con->error;
}

$orderItems = "CREATE TABLE $databaseName.order_items (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        order_id int ,
        product_id  int,
        quantity int,
        price int,
        CONSTRAINT item_order_id FOREIGN KEY (order_id)
        REFERENCES orders(id),
        CONSTRAINT item_product_id FOREIGN KEY (product_id)
        REFERENCES products(id)

    )";

if ($con->query($orderItems)) {
    echo "<br>order items Table created successfully";
} else {
    echo "<br>Error creating table: " . $con->error;
}

$orderItems = "CREATE TABLE $databaseName.settings (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        key varchar(100),
        value int
    )";

if ($con->query($orderItems)) {
    echo "<br>setting  Table created successfully";
} else {
    echo "<br>Error creating table: " . $con->error;
}
