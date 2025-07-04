<?php
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../auth/login.php");
    exit();
}
?>
<div class=" header navbar-light bg-light">
    <nav class="navbar navbar-expand-lg  d-flex justify-content-around px-5">
        <div>
            <a class="navbar-brand" href="#">LOGO</a>

        </div>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="../auth/userdata.php
                    ">Users</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="../posts/postdata.php">Posts</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="../products/productdata.php">Products</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="../auth/setting.php">Settings</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="../auth/discountdata.php">Discount</a>
                </li>
            </ul>
        </div>
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901" />
            </svg>

            <div class="btn-group ">
                <button class="dropdown-toggle bg-light  border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle icon" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                    </svg>
                </button>
                <ul class="dropdown-menu">
                    <?php if (isset($_SESSION['email'])) : ?>
                        <p>
                            <b>Welcome</b><br>
                            <strong>
                                <?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?><br>
                                <?php echo $_SESSION['email']; ?>
                            </strong>
                        </p>
                        <form method="POST" style="display:inline;">
                            <button type="submit" name="logout" class="dropdown-item">Logout</button>
                        </form>
                    <?php endif ?>
                </ul>

            </div>
    </nav>
</div>