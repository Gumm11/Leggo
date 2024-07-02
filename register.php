<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lemon&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@200&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <base href="https://localhost/github/leggo/">
</head>

<body>
    <script>
        function validateForm() {
            let email = document.getElementById("register-email").value;
            let username = document.getElementById("register-username").value;
            let password = document.getElementById("register-password").value;
            let confirmPassword = document.getElementById("register-confirm-password").value;
            let phoneNumber = document.getElementById("register-phone-number").value;
            let errorMsg = '';

            if (username.trim() === '' || email.trim() === '' || password.trim() === '' || confirmPassword.trim() === '' || phoneNumber.trim() === '') {
                errorMsg += "Semua field harus diisi.<br>";
            }

            if (!validateEmail(email)) {
                errorMsg += "Email tidak valid.<br>";
            }

            if (username.length < 4) {
                errorMsg += "Username harus lebih dari 3 karakter.<br>";
            }

            if (password.length < 6) {
                errorMsg += "Password harus lebih dari 5 karakter.<br>";
            }

            if (password !== confirmPassword) {
                errorMsg += "Password dan konfirmasi password tidak sama.<br>";
            }

            if (!validatePhoneNumber(phoneNumber)) {
                errorMsg += "Nomor telepon tidak valid.<br>";
            }

            if (errorMsg) {
                document.getElementById("error-message").innerHTML = errorMsg;
                return false;
            }

            return true;
        }

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(String(email).toLowerCase());
        }

        function validatePhoneNumber(phoneNumber) {
            const re = /^[0-9]{10,15}$/; // Adjust the regex based on the phone number format you expect
            return re.test(phoneNumber);
        }

        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }

        // Cek "error" atau "success" dalam parameter dalam URL
        window.onload = function () {
            var error = getParameterByName('error');
            var success = getParameterByName('success');
            if (error) {
                document.getElementById('error-message').innerHTML = error;
            }
            if (success) {
                alert(success);
            }
        };
    </script>

    <div class="bg-purpleRadiant">
        <div class="login-img-container">
            <div class="login-body">
                <img id="login-logo" src="https://ik.imagekit.io/iwrtsyly3o/Leggo/Logo.png">
                <form action="public/php/submit.php" method="post" onsubmit="return validateForm()">
                    <div class="login-body-col">
                        <div class="login-body-header-text">
                            Register
                            <div class="error-text" style="margin-top: 15px">
                                <label id="error-message" style="color: red; font-size: 20px;"></label>
                            </div>
                        </div>
                        <input type="text" id="register-username" name="username" placeholder="Username">
                        <input type="text" id="register-email" name="email" placeholder="Alamat email">
                        <input type="text" id="register-phone-number" name="phone_number" placeholder="Nomor Telepon">
                        <input type="password" id="register-password" name="password" placeholder="Password">
                        <input type="password" id="register-confirm-password" name="confirm_password"
                            placeholder="Confirm Password">
                        <button id="btnregister">
                            <p class="textbtnregister">Register</p>
                        </button>
                        <div class="posisibuatakun">
                            <p class="sudah-punya-akun">
                                <span class="span">Sudah punya akun?</span>
                                <a href="login.php">Log in!</a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
