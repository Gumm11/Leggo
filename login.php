<?php
session_start();

// Periksa apakah pengguna sudah login melalui session atau cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
}

if (isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lemon&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@200&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <base href="https://localhost/github/leggo/">
    <title>Leggo</title>
    <style>
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border: 2px solid #000;
            display: none;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div id="popup" class="popup"></div>
    <div class="bg-purpleRadiant">
        <div class="login-img-container">
            <div class="login-body">
                <img id="login-logo" src="https://ik.imagekit.io/iwrtsyly3o/Leggo/Logo.png">
                <form id="loginForm">
                    <div class="login-body-col">
                        <div class="login-body-header-text">
                            Log in
                            <div id="error-message" class="error-text" style="color: red; font-size: 20px; margin-top: 15px"></div>
                        </div>
                        <input type="text" id="login-email" name="email" placeholder="Username atau Alamat Email">
                        <input type="password" id="login-password" name="password" placeholder="Password">
                        <button type="submit" id="btnlogin">
                            <p class="textbtnlogin">Log in</p>
                        </button>
                        <div class="teksatau">Atau log in dengan</div>
                        <div class="image-wrappergoogle">
                            <a href="https://en.wikipedia.org/wiki/Dinosaur">
                                <img src="public/Resources/Google.png" /></a>
                        </div>
                        <div class="posisibuatakun">
                            <p class="belum-punya-akun">
                                <span class="span">Belum punya akun?</span>
                                <a href="register.php">Buat akun yuk!</a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'public/php/authenticate.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const result = JSON.parse(xhr.responseText);
                        if (result.status === 'success') {
                            const user = result.user;
                            const popup = document.getElementById('popup');
                            popup.innerHTML = `
                                <p>Login berhasil!</p>
                                <p>Username: ${user.username}</p>
                                <p>Email: ${user.email}</p>
                                <p>Phone Number: ${user.phone_number}</p>
                            `;
                            popup.style.display = 'block';

                            setTimeout(() => {
                                window.location.href = 'index.html';
                            }, 1000);
                        } else {
                            document.getElementById('error-message').textContent = result.message;
                        }
                    } else {
                        document.getElementById('error-message').textContent = 'An error occurred during login.';
                    }
                }
            };

            xhr.send(`email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`);
        });
    </script>
</body>
</html>
