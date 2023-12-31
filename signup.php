<?php
// signup.php (signup page)
session_start();
require_once 'includes/db_config.php';
require_once 'includes/functions.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$signup_success = "";
$signup_error = "";

if (isset($_POST['signup'])) {
    // Retrieve form data and sanitize input
    $sponsor_id = sanitize_input($_POST['sponsor_id']);
    $name = sanitize_input($_POST['name']);
    $mobile_no = sanitize_input($_POST['mobile_no']);
    $pan_no = sanitize_input($_POST['pan_no']);
    $aadhar_no = sanitize_input($_POST['aadhar_no']);
    $password = sanitize_input($_POST['password']);
    $address = sanitize_input($_POST['address']);
    $account_holder_name = sanitize_input($_POST['account_holder_name']);
    $bank_name = sanitize_input($_POST['bank_name']);
    $account_number = sanitize_input($_POST['account_number']);
    $ifsc_code = sanitize_input($_POST['ifsc_code']);

    // Validate the sponsor ID
    $sql = "SELECT id FROM users WHERE id = '$sponsor_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (sponsor_id, name, mobile_no, pan_no, aadhar_no, password, address, account_holder_name, bank_name, account_number, ifsc_code)
            VALUES ('$sponsor_id', '$name', '$mobile_no', '$pan_no', '$aadhar_no', '$hashed_password', '$address', '$account_holder_name', '$bank_name', '$account_number', '$ifsc_code')";

        if (mysqli_query($conn, $sql)) {
            $user_id = mysqli_insert_id($conn);
            $_SESSION['user_id'] = $sponsor_id; // Use auto-generated user_id instead of sponsor_id
            $_SESSION['username'] = $name; // Store user's name or any other relevant data in the session
            // Add other relevant data to the session
            header("Location: dashboard.php");
            exit();
        } else {
            $signup_error = "Error: " . mysqli_error($conn);
        }
    } else {
        $signup_error = "Invalid sponsor ID. Please enter a valid sponsor ID.";
    }
}
?>

<!-- ... (rest of the signup page HTML) ... -->

<!-- ... (rest of the signup page HTML) ... -->


<!-- Rest of the signup page HTML remains the same -->



<!DOCTYPE html>
<html>

<head>
    <title>Signup Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-5">
                <form action="" method="post" class="form-group">
                    <h2 class="text-center">Sign Up</h2>
                    <input type="text" name="sponsor_id" id="sponsor_id" class="form-control" placeholder="Sponsor ID" required>
                    <span id="sponsor_name"></span>
                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                    <input type="tel" name="mobile_no" class="form-control" placeholder="Mobile No." required>
                    <input type="text" name="pan_no" class="form-control" placeholder="PAN No." required>
                    <input type="text" name="aadhar_no" class="form-control" placeholder="Aadhar No." required>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <textarea name="address" class="form-control" placeholder="Address" required></textarea>
                    <input type="text" name="account_holder_name" class="form-control" placeholder="Account Holder Name" required>
                    <input type="text" name="bank_name" class="form-control" placeholder="Bank Name" required>
                    <input type="text" name="account_number" class="form-control" placeholder="Account Number" required>
                    <input type="text" name="ifsc_code" class="form-control" placeholder="IFSC Code" required>
                    <button type="submit" name="signup" class="btn btn-primary btn-block mt-3">Sign Up</button>
                </form>
                <?php if (!empty($signup_error)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $signup_error; ?>
                </div>
            <?php } ?>
                <p class="text-center">Already have an account? <a href="index.php">Login</a></p>
            </div>
        </div>
    </div>
    <script>
    // Function to perform AJAX request
    function checkSponsorID() {
        var sponsor_id = document.getElementById("sponsor_id").value;
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = this.responseText;
                if (response != "") {
                    document.getElementById("sponsor_name").innerHTML = response;
                } else {
                    document.getElementById("sponsor_name").innerHTML = "Invalid sponsor ID";
                }
            }
        };

        xhttp.open("GET", "ajax_check_sponsor.php?sponsor_id=" + sponsor_id, true);
        xhttp.send();
    }

    // Attach the checkSponsorID function to the input field's onblur event (when the field loses focus)
    document.getElementById("sponsor_id").addEventListener("blur", checkSponsorID);
</script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
