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
    <script type="text/javascript" src="https://cdn.emailjs.com/sdk/2.3.2/email.min.js"></script>
    <script src="https://cdn.emailjs.com/dist/email.min.js"></script>
    <script type="text/javascript">
    (function(){
        emailjs.init("-VGKTqsJ_In5TCCke");
        console.log("EmailJS initialized");
    })();
</script>
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
            testEmailJs();
            var error = getParameterByName('error');
            var success = getParameterByName('success');
            if (error) {
                document.getElementById('error-message').innerHTML = error;
            }
            if (success) {
                alert(success);
            }
        };

        function submitForm(event) {
    event.preventDefault();
    console.log("Form submission started");

    if (validateForm()) {
        console.log("Form validation passed");
        const formData = new FormData(document.getElementById('registerForm'));
        
        fetch('register_process.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log("Fetch response received", response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Data received from server", data);
            if (data.success) {
                console.log("Attempting to send OTP via email");
                return emailjs.send('service_5ongxey', 'template_xcnd58y', {
                    email: data.email,
                    otp: data.otp
                });
            } else {
                throw new Error(data.errors.join(', '));
            }
        })
        .then((response) => {
            console.log('EmailJS SUCCESS!', response.status, response.text);
            document.getElementById('registration-form').style.display = 'none';
            document.getElementById('otp-form').style.display = 'block';
        })
        .catch(error => {
            console.error('Failed:', error);
            let errorMessage = 'An error occurred: ';
            if (error.name === 'EmailJsResponseStatus') {
                errorMessage += 'Failed to send email. ';
                if (error.text) {
                    errorMessage += error.text;
                }
            } else {
                errorMessage += error.message;
            }
            document.getElementById('error-message').innerHTML = errorMessage;
        });
    } else {
        console.log("Form validation failed");
    }
}

function testEmailJs() {
    console.log("Testing EmailJS");
    emailjs.send('service_5ongxey', 'template_xcnd58y', {
        email: 'test@example.com',
        otp: '123456'
    })
    .then(function(response) {
        console.log('EmailJS TEST SUCCESS!', response.status, response.text);
    }, function(error) {
        console.log('EmailJS TEST FAILED...', error);
    });
}
  
function verifyOTP() {
    const otp = document.getElementById('otp').value;
    fetch('otp_verification.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'otp=' + otp
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP error! status: ${response.status}, message: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Registration successful!');
            window.location.href = 'login.php';
        } else {
            document.getElementById('otp-error-message').innerHTML = data.error;
        }
    })
    .catch(error => {
        console.error('Failed to verify OTP:', error);
        document.getElementById('otp-error-message').innerHTML = 'An error occurred: ' + error.message;
    });
}
    </script>

    <div class="bg-purpleRadiant">
        <div class="login-img-container">
            <div class="login-body">
                <img id="login-logo" src="https://ik.imagekit.io/iwrtsyly3o/Leggo/Logo.png">
                <form id="registerForm" onsubmit="submitForm(event)">
                    <div id="registration-form" class="login-body-col">
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
                        <button type="submit" id="btnregister">
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
                <div id="otp-form" style="display: none;" class="login-body-col">
                    <div class="login-body-header-text">
                        OTP Verification
                        <div class="error-text" style="margin-top: 15px">
                            <label id="otp-error-message" style="color: red; font-size: 20px;"></label>
                        </div>
                    </div>
                    <input type="text" id="otp" name="otp" placeholder="Enter OTP">
                    <button onclick="verifyOTP()" id="btnverify">
                        <p class="textbtnverify">Verify OTP</p>
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
