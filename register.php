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
    <script src="https://cdn.emailjs.com/dist/email.min.js"></script>
    <script type="text/javascript">
        (function () {
            emailjs.init("-VGKTqsJ_In5TCCke");
            //console.log("EmailJS initialized");
        })();

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
            const re = /^[0-9]{10,15}$/;
            return re.test(phoneNumber);
        }

        async function checkExistingData() {
            let email = document.getElementById("register-email").value;
            let username = document.getElementById("register-username").value;
            let phoneNumber = document.getElementById("register-phone-number").value;

            let response = await fetch('public/php/check_existing.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: email,
                    username: username,
                    phone_number: phoneNumber
                })
            });

            let result = await response.json();
            return result;
        }

        async function requestOtp() {
            if (validateForm()) {
                let checkResult = await checkExistingData();

                if (checkResult.exists) {
                    document.getElementById("error-message").innerHTML = checkResult.message.replace(/\n/g, "<br>");
                    return;
                }

                let email = document.getElementById("register-email").value;
                let otp = Math.floor(100000 + Math.random() * 900000);
                sessionStorage.setItem("generatedOtp", otp); // Store OTP in session storage
                sendOtp(email, otp).then(() => {
                    document.getElementById("register-otp").style.display = 'block';
                    document.getElementById("btnvalidate").style.display = 'block';
                    document.getElementById("btnregister").style.display = 'none';
                    alert('OTP telah dikirim ke email Anda. Silakan masukkan OTP untuk melanjutkan.');
                }).catch(error => {
                    //console.log('Failed to send OTP', error);
                    //alert('Failed to send OTP: ' + error.text);
                });
            }
        }

        function sendOtp(email, otp) {
            //console.log(`Sending OTP: ${otp} to email: ${email}`);
            return emailjs.send('service_5ongxey', 'template_xcnd58y', {
                email: email,
                otp: otp
            }).then(function (response) {
                //console.log('SUCCESS!', response.status, response.text);
                //alert('OTP sent successfully: ' + response.text);
            }, function (error) {
                //console.log('FAILED...', error);
                //alert('Failed to send OTP: ' + JSON.stringify(error));
            });
        }

        async function validateOtp() {
            let enteredOtp = document.getElementById("register-otp").value;
            let generatedOtp = sessionStorage.getItem("generatedOtp");

            if (enteredOtp === generatedOtp) {
                sessionStorage.setItem("otpValidated", true);
                alert('OTP berhasil divalidasi. Data Anda akan disimpan.');
                // Submit the form data
                let formData = new FormData(document.getElementById("register-form"));
                formData.append('otpValidated', 'true'); // Add OTP validated status to form data

                let response = await fetch('public/php/submit.php', {
                    method: 'POST',
                    body: formData
                });

                let result = await response.json();
                if (result.success) {
                    alert('Registration successful! Redirecting to login.');
                    window.location.href = 'login.php';
                } else {
                    alert('Registration failed: ' + result.message);
                }
            } else {
                alert('OTP tidak valid. Silakan coba lagi.');
            }
        }
    </script>

    <div class="bg-purpleRadiant">
        <div class="login-img-container">
            <div class="login-body">
                <img id="login-logo" src="https://ik.imagekit.io/iwrtsyly3o/Leggo/Logo.png">
                <form id="register-form" onsubmit="return validateForm()">
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
                        <input type="password" id="register-confirm-password" name="confirm_password" placeholder="Confirm Password">
                        <input type="text" id="register-otp" name="otp" placeholder="Masukkan OTP" style="display: none;">
                        <button type="button" id="btnregister" onclick="requestOtp()">
                            <p class="textbtnregister">Request OTP</p>
                        </button>
                        <button type="button" id="btnvalidate" onclick="validateOtp()" style="display: none;">
                            <p class="textbtnvalidate">Validate OTP</p>
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