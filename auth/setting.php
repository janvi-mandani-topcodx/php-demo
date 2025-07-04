 <?php
    include '../connection.php';
    session_start();
    if (isset($_POST['setting']) && $_SERVER["REQUEST_METHOD"] == "POST") {
        $freeshippingThreashold = $_POST['shipping_threashold'];
        $freeshippingCost =  $_POST['shipping_cost'];
        $settings = [
            'free_shipping_threashold' => $freeshippingThreashold,
            'shipping_cost' => $freeshippingCost,
        ];
        foreach ($settings as $key => $value) {
            $key = $key;
            $value = $value;
            $check = $con->query("SELECT * FROM settings WHERE setting_key = '$key' ");
            if ($check->num_rows > 0) {
                $con->query("UPDATE settings SET value = '$value' WHERE setting_key = '$key'");
            } else {
                $con->query("INSERT INTO settings (setting_key, value) VALUES ('$key', '$value')");
            }
        }
    }
    $shippingThreashold = 0;
    $shippingCost = 0;

    $result = $con->query("SELECT * FROM settings");
    while ($row = $result->fetch_assoc()) {
        if ($row['setting_key'] == 'free_shipping_threashold') {
            $shippingThreashold = $row['value'];
        } elseif ($row['setting_key'] == 'shipping_cost') {
            $shippingCost = $row['value'];
        }
    }
    ?>
 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Setting</title>
     <?php include '../bootstrap.php'; ?>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <link rel="stylesheet" href="../style.css">
 </head>

 <body>
     <?php
        include '../header.php';
        ?>
     <div class="backgroundColor">
         <section class="vh-100">
             <div class="container py-5 h-100">
                 <div class="row d-flex justify-content-center align-items-center h-100">
                     <div class="col-12 col-md-12 col-lg-9 col-xl-8">
                         <h2>Settings</h2>
                         <div class="card shadow-2-strong" style="border-radius: 1rem;">
                             <div class="card-body p-5 text-center">
                                 <form action="setting.php" method="post">
                                     <div class="form-group  pe-2">
                                         <label for="shipping" class='lable_setting pb-2 lable'>Free Shipping Threashold ($)</label>
                                         <input type="text" class="form-control input" id="shipping" name="shipping_threashold" value="<?php echo isset($shippingThreashold) ? $shippingThreashold : 0; ?>">
                                     </div>
                                     <div class="form-group ">
                                         <label for="cost" class='lable_setting pb-2 lable'>Shipping Cost ($)</label>
                                         <input type="text" class="form-control input" id="cost" name="shipping_cost" value="<?php echo isset($shippingCost) ? $shippingCost : 0; ?>">
                                     </div>
                                     <input type="hidden" class="form-control" id="shippingThreashold" name="" value="<?= $shippingThreashold ?>">
                                     <input type="hidden" class="form-control" id="shippingCost" name="" value="<?= $shippingCost ?>">

                                     <button type="submit" class="btn btn-dark shadow px-4 mt-5" name="setting">Update</button>
                                 </form>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </section>
     </div>
 </body>

 </html>