<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lemon&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@200&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
</head>

<body>
    <script>
        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }
    </script>

    <div class="bg-purpleRadiant">
        <div class="login-img-container">
            <div class="login-body">
                <img id="login-logo" src="https://ik.imagekit.io/iwrtsyly3o/Leggo/Logo.png">
                <form action="public/php/authenticate.php" method="post">
                    <div class="login-body-col">
                        <div class="login-body-header-text">
                            Log in
                            <?php if (isset($_GET['error'])): ?>
                                <div class="error-text" style="color: red; font-size: 20px; margin-top:15px">
                                    <label>
                                        <?php echo $_GET['error']; ?>
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                        <input type="text" id="login-email" name="email" placeholder="Username atau Alamat Email">
                        <input type="password" id="login-password" name="password" placeholder="Password">
                        <button id="btnlogin">
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
</body>

</html>