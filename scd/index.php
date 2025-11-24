<?php
require_once 'db.php';   // same folder me db.php hai
session_start();

/**
 * Small helper: JS alert + back
 */
function js_back($msg){
    echo "<script>
            alert('".addslashes($msg)."');
            window.history.back();
          </script>";
    exit;
}

/**
 * Mysqli ko exceptions throw karne do,
 * taake hum try/catch se handle kar saken.
 */
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    // Sirf POST requests handle karo
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        /* ================== LOGIN / SIGNUP (POPUP) ================== */
        $form_type = $_POST['form_type'] ?? '';

        /* ---------- LOGIN ---------- */
        if ($form_type === 'login') {

            // plugin ka username field actually email hi use karega
            $username = trim($_POST['xoo_el_username'] ?? '');
            $password = trim($_POST['xoo_el_password'] ?? '');

            if ($username === '' || $password === '') {
                js_back('Please fill all fields');
            }

            // Sirf email se search, kyun ke table me username column nahi hai
            $stmt = $conn->prepare(
                "SELECT id, full_name, email, password_hash
                 FROM users
                 WHERE email = ?"
            );

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {

                // Plain password vs password_hash
                if (password_verify($password, $row['password_hash'])) {

                    $_SESSION['user_id']   = $row['id'];
                    $_SESSION['user_name'] = $row['full_name'];

                    echo "<script>
                            alert('Login successful');
                            window.location.href='index.php';
                          </script>";
                    exit;

                } else {
                    js_back('Invalid email or password');
                }
            } else {
                js_back('Invalid email or password');
            }

            $stmt->close();
        }

        /* ---------- SIGNUP / REGISTER ---------- */
        elseif ($form_type === 'register') {

            $email   = trim($_POST['xoo_el_reg_email'] ?? '');
            $pass1   = trim($_POST['xoo_el_reg_pass'] ?? '');
            $pass2   = trim($_POST['xoo_el_reg_pass_again'] ?? '');
            $fname   = trim($_POST['xoo_el_reg_fname'] ?? '');
            $lname   = trim($_POST['xoo_el_reg_lname'] ?? '');
            $full_name = trim($fname . ' ' . $lname);

            if ($email === '' || $pass1 === '' || $pass2 === '' || $fname === '' || $lname === '') {
                js_back('Please fill all fields in Sign Up');
            }

            if ($pass1 !== $pass2) {
                js_back('Passwords do not match');
            }

            // duplicate email check
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->close();
                js_back('This email is already registered');
            }
            $stmt->close();

            // password hash banao
            $hash = password_hash($pass1, PASSWORD_BCRYPT);

            // naya user insert karo
            $stmt = $conn->prepare(
                "INSERT INTO users (full_name, email, password_hash)
                 VALUES (?, ?, ?)"
            );
            $stmt->bind_param("sss", $full_name, $email, $hash);

            if ($stmt->execute()) {
                echo "<script>
                        alert('Account created successfully! Please log in.');
                        window.location.href='index.php';
                      </script>";
                exit;
            } else {
                js_back('Error saving account');
            }

            $stmt->close();
        }

        /* ================== HOME PAGE KA PLAN FORM ================== */
        if (
            isset($_POST['form_name']) &&
            $_POST['form_name'] === 'home_plan_form'
        ) {
            // form fields
            $full_name       = trim($_POST['full_name'] ?? '');
            $email           = trim($_POST['email'] ?? '');
            $phone           = trim($_POST['phone'] ?? '');
            $travel_location = trim($_POST['travel_location'] ?? '');
            $experience_type = trim($_POST['experience_type'] ?? '');
            $message         = trim($_POST['message'] ?? '');

            // ---- VALIDATIONS ----

            // 1) Full name only letters + spaces
            if (!preg_match("/^[a-zA-Z ]+$/", $full_name)) {
                js_back('Name must contain only letters');
            }

            // 2) Email format validation
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                js_back('Invalid email format');
            }

            // 3) Phone digits only
            if (!preg_match("/^[0-9]+$/", $phone)) {
                js_back('Phone number must contain only digits');
            }

            // 4) Travel location -> ab koi validation nahi (free text)
            // sirf trim kiya hua value DB mein save ho jayega.

            // ---- DB INSERT ----
            $stmt = $conn->prepare("
                INSERT INTO contacts (full_name, email, phone, travel_location, experience_type, message)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "ssssss",
                $full_name,
                $email,
                $phone,
                $travel_location,
                $experience_type,
                $message
            );

            $stmt->execute();
            $stmt->close();

            echo "<script>
                    alert('Your details have been submitted successfully!');
                    window.location.href = 'index.php#plan-form';
                  </script>";
            exit;
        }
    }

} catch (Throwable $e) {
    // agar koi bhi DB / PHP error aaya to yahan aa jayega
    error_log('Error in index.php: '.$e->getMessage());
    js_back('Something went wrong on server, please try again later.');
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- WordPress / Theme CSS restore -->
    <link rel="stylesheet" href="wp-content/themes/twentytwentyfour/style.css">
    <link rel="stylesheet" href="wp-content/themes/twentytwentyfour/assets/css/style.min.css">
    <link rel="stylesheet" href="wp-content/plugins/elementor/assets/css/frontend.min.css">
    <link rel="stylesheet" href="wp-content/plugins/elementor/assets/lib/font-awesome/css/all.min.css">

    <!-- Your custom form CSS -->
    <style>
    .wanderlust-contact-form {
        background: #ffffff;
        padding: 25px 25px 25px;   /* ↓ yahan 30px → 25px kar diya */
        border-radius: 12px;
        box-shadow: 0 0px 0px rgba(0,0,0,0.08);
        font-family: "Open Sans", sans-serif;
        width: 100%;
    }

    .wanderlust-contact-form label {
        font-weight: 600;      /* ✔️ LABELS BOLD */
        margin-bottom: 6px;
        color: #333;
    }

    .wanderlust-contact-form .form-row {
        margin-bottom: 16px;   /* ✔️ SPACING PERFECT */
        display: flex;
        flex-direction: column;
    }

    .wanderlust-contact-form input,
    .wanderlust-contact-form select,
    .wanderlust-contact-form textarea {
        padding: 12px 15px;
        border-radius: 6px;
        border: 1px solid #ccc;
        width: 100%;
    }

    .wanderlust-contact-form .btn-submit {
        background: #2EA5B4;
        color: white;
        padding: 12px;
        border-radius: 30px;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-top: 10px;
        margin-bottom: 0px;  /* ✔️ Sub form ke neeche extra gap remove */
    }
</style>






		<title>wanderlustpakistan.com – Discover Pakistan’s Luxury Glamping &amp; Adventure Experiences</title>
<meta name="robots" content="max-image-preview:large">
	<style>img:is([sizes="auto" i], [sizes^="auto," i]) { contain-intrinsic-size: 3000px 1500px }</style>
	<link rel="alternate" type="application/rss+xml" title="wanderlustpakistan.com » Feed" href="./feed/index.html">
<link rel="alternate" type="application/rss+xml" title="wanderlustpakistan.com » Comments Feed" href="./comments/feed/index.html">
<script type="text/javascript">
/* <![CDATA[ */
window._wpemojiSettings = {"baseUrl":"https:\/\/s.w.org\/images\/core\/emoji\/16.0.1\/72x72\/","ext":".png","svgUrl":"https:\/\/s.w.org\/images\/core\/emoji\/16.0.1\/svg\/","svgExt":".svg","source":{"concatemoji":"\/\/wp-includes\/js\/wp-emoji-release.min.js?ver=6.8.3"}};
/*! This file is auto-generated */
!function(s,n){var o,i,e;function c(e){try{var t={supportTests:e,timestamp:(new Date).valueOf()};sessionStorage.setItem(o,JSON.stringify(t))}catch(e){}}function p(e,t,n){e.clearRect(0,0,e.canvas.width,e.canvas.height),e.fillText(t,0,0);var t=new Uint32Array(e.getImageData(0,0,e.canvas.width,e.canvas.height).data),a=(e.clearRect(0,0,e.canvas.width,e.canvas.height),e.fillText(n,0,0),new Uint32Array(e.getImageData(0,0,e.canvas.width,e.canvas.height).data));return t.every(function(e,t){return e===a[t]})}function u(e,t){e.clearRect(0,0,e.canvas.width,e.canvas.height),e.fillText(t,0,0);for(var n=e.getImageData(16,16,1,1),a=0;a<n.data.length;a++)if(0!==n.data[a])return!1;return!0}function f(e,t,n,a){switch(t){case"flag":return n(e,"\ud83c\udff3\ufe0f\u200d\u26a7\ufe0f","\ud83c\udff3\ufe0f\u200b\u26a7\ufe0f")?!1:!n(e,"\ud83c\udde8\ud83c\uddf6","\ud83c\udde8\u200b\ud83c\uddf6")&&!n(e,"\ud83c\udff4\udb40\udc67\udb40\udc62\udb40\udc65\udb40\udc6e\udb40\udc67\udb40\udc7f","\ud83c\udff4\u200b\udb40\udc67\u200b\udb40\udc62\u200b\udb40\udc65\u200b\udb40\udc6e\u200b\udb40\udc67\u200b\udb40\udc7f");case"emoji":return!a(e,"\ud83e\udedf")}return!1}function g(e,t,n,a){var r="undefined"!=typeof WorkerGlobalScope&&self instanceof WorkerGlobalScope?new OffscreenCanvas(300,150):s.createElement("canvas"),o=r.getContext("2d",{willReadFrequently:!0}),i=(o.textBaseline="top",o.font="600 32px Arial",{});return e.forEach(function(e){i[e]=t(o,e,n,a)}),i}function t(e){var t=s.createElement("script");t.src=e,t.defer=!0,s.head.appendChild(t)}"undefined"!=typeof Promise&&(o="wpEmojiSettingsSupports",i=["flag","emoji"],n.supports={everything:!0,everythingExceptFlag:!0},e=new Promise(function(e){s.addEventListener("DOMContentLoaded",e,{once:!0})}),new Promise(function(t){var n=function(){try{var e=JSON.parse(sessionStorage.getItem(o));if("object"==typeof e&&"number"==typeof e.timestamp&&(new Date).valueOf()<e.timestamp+604800&&"object"==typeof e.supportTests)return e.supportTests}catch(e){}return null}();if(!n){if("undefined"!=typeof Worker&&"undefined"!=typeof OffscreenCanvas&&"undefined"!=typeof URL&&URL.createObjectURL&&"undefined"!=typeof Blob)try{var e="postMessage("+g.toString()+"("+[JSON.stringify(i),f.toString(),p.toString(),u.toString()].join(",")+"));",a=new Blob([e],{type:"text/javascript"}),r=new Worker(URL.createObjectURL(a),{name:"wpTestEmojiSupports"});return void(r.onmessage=function(e){c(n=e.data),r.terminate(),t(n)})}catch(e){}c(n=g(i,f,p,u))}t(n)}).then(function(e){for(var t in e)n.supports[t]=e[t],n.supports.everything=n.supports.everything&&n.supports[t],"flag"!==t&&(n.supports.everythingExceptFlag=n.supports.everythingExceptFlag&&n.supports[t]);n.supports.everythingExceptFlag=n.supports.everythingExceptFlag&&!n.supports.flag,n.DOMReady=!1,n.readyCallback=function(){n.DOMReady=!0}}).then(function(){return e}).then(function(){var e;n.supports.everything||(n.readyCallback(),(e=n.source||{}).concatemoji?t(e.concatemoji):e.wpemoji&&e.twemoji&&(t(e.twemoji),t(e.wpemoji)))}))}((window,document),window._wpemojiSettings);
/* ]]> */
</script>
<link rel="stylesheet" id="xoo-aff-style-css" href="./wp-content/plugins/easy-login-woocommerce/xoo-form-fields-fw/assets/css/xoo-aff-style.css?ver=2.1.0" type="text/css" media="all">
<style id="xoo-aff-style-inline-css" type="text/css">



.xoo-aff-input-group .xoo-aff-input-icon{
	background-color:  #eee;
	color: #555;
	max-width: 40px;
	min-width: 40px;
	border-color: #cccccc;
	border-width: 1px;
	font-size: 14px;
}
.xoo-aff-group{
	margin-bottom: 30px;
}

.xoo-aff-group input[type="text"], .xoo-aff-group input[type="password"], .xoo-aff-group input[type="email"], .xoo-aff-group input[type="number"], .xoo-aff-group select, .xoo-aff-group select + .select2, .xoo-aff-group input[type="tel"], .xoo-aff-group input[type="file"]{
	background-color: #fff;
	color: #777;
	border-width: 1px;
	border-color: #cccccc;
	height: 50px;
}


.xoo-aff-group input[type="file"]{
	line-height: calc(50px - 13px);
}



.xoo-aff-group input[type="text"]::placeholder, .xoo-aff-group input[type="password"]::placeholder, .xoo-aff-group input[type="email"]::placeholder, .xoo-aff-group input[type="number"]::placeholder, .xoo-aff-group select::placeholder, .xoo-aff-group input[type="tel"]::placeholder, .xoo-aff-group .select2-selection__rendered, .xoo-aff-group .select2-container--default .select2-selection--single .select2-selection__rendered, .xoo-aff-group input[type="file"]::placeholder, .xoo-aff-group input::file-selector-button{
	color: #777;
}

.xoo-aff-group input[type="text"]:focus, .xoo-aff-group input[type="password"]:focus, .xoo-aff-group input[type="email"]:focus, .xoo-aff-group input[type="number"]:focus, .xoo-aff-group select:focus, .xoo-aff-group select + .select2:focus, .xoo-aff-group input[type="tel"]:focus, .xoo-aff-group input[type="file"]:focus{
	background-color: #ededed;
	color: #000;
}

[placeholder]:focus::-webkit-input-placeholder{
	color: #000!important;
}


.xoo-aff-input-icon + input[type="text"], .xoo-aff-input-icon + input[type="password"], .xoo-aff-input-icon + input[type="email"], .xoo-aff-input-icon + input[type="number"], .xoo-aff-input-icon + select, .xoo-aff-input-icon + select + .select2,  .xoo-aff-input-icon + input[type="tel"], .xoo-aff-input-icon + input[type="file"]{
	border-bottom-left-radius: 0;
	border-top-left-radius: 0;
}



</style>
<link rel="stylesheet" id="xoo-aff-font-awesome5-css" href="./wp-content/plugins/easy-login-woocommerce/xoo-form-fields-fw/lib/fontawesome5/css/all.min.css?ver=6.8.3" type="text/css" media="all">
<style id="wp-emoji-styles-inline-css" type="text/css">

	img.wp-smiley, img.emoji {
		display: inline !important;
		border: none !important;
		box-shadow: none !important;
		height: 1em !important;
		width: 1em !important;
		margin: 0 0.07em !important;
		vertical-align: -0.1em !important;
		background: none !important;
		padding: 0 !important;
	}
</style>
<style id="classic-theme-styles-inline-css" type="text/css">
/*! This file is auto-generated */
.wp-block-button__link{color:#fff;background-color:#32373c;border-radius:9999px;box-shadow:none;text-decoration:none;padding:calc(.667em + 2px) calc(1.333em + 2px);font-size:1.125em}.wp-block-file__button{background:#32373c;color:#fff;text-decoration:none}
</style>
<style id="global-styles-inline-css" type="text/css">
:root{--wp--preset--aspect-ratio--square: 1;--wp--preset--aspect-ratio--4-3: 4/3;--wp--preset--aspect-ratio--3-4: 3/4;--wp--preset--aspect-ratio--3-2: 3/2;--wp--preset--aspect-ratio--2-3: 2/3;--wp--preset--aspect-ratio--16-9: 16/9;--wp--preset--aspect-ratio--9-16: 9/16;--wp--preset--color--black: #000000;--wp--preset--color--cyan-bluish-gray: #abb8c3;--wp--preset--color--white: #ffffff;--wp--preset--color--pale-pink: #f78da7;--wp--preset--color--vivid-red: #cf2e2e;--wp--preset--color--luminous-vivid-orange: #ff6900;--wp--preset--color--luminous-vivid-amber: #fcb900;--wp--preset--color--light-green-cyan: #7bdcb5;--wp--preset--color--vivid-green-cyan: #00d084;--wp--preset--color--pale-cyan-blue: #8ed1fc;--wp--preset--color--vivid-cyan-blue: #0693e3;--wp--preset--color--vivid-purple: #9b51e0;--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple: linear-gradient(135deg,rgba(6,147,227,1) 0%,rgb(155,81,224) 100%);--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan: linear-gradient(135deg,rgb(122,220,180) 0%,rgb(0,208,130) 100%);--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange: linear-gradient(135deg,rgba(252,185,0,1) 0%,rgba(255,105,0,1) 100%);--wp--preset--gradient--luminous-vivid-orange-to-vivid-red: linear-gradient(135deg,rgba(255,105,0,1) 0%,rgb(207,46,46) 100%);--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray: linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%);--wp--preset--gradient--cool-to-warm-spectrum: linear-gradient(135deg,rgb(74,234,220) 0%,rgb(151,120,209) 20%,rgb(207,42,186) 40%,rgb(238,44,130) 60%,rgb(251,105,98) 80%,rgb(254,248,76) 100%);--wp--preset--gradient--blush-light-purple: linear-gradient(135deg,rgb(255,206,236) 0%,rgb(152,150,240) 100%);--wp--preset--gradient--blush-bordeaux: linear-gradient(135deg,rgb(254,205,165) 0%,rgb(254,45,45) 50%,rgb(107,0,62) 100%);--wp--preset--gradient--luminous-dusk: linear-gradient(135deg,rgb(255,203,112) 0%,rgb(199,81,192) 50%,rgb(65,88,208) 100%);--wp--preset--gradient--pale-ocean: linear-gradient(135deg,rgb(255,245,203) 0%,rgb(182,227,212) 50%,rgb(51,167,181) 100%);--wp--preset--gradient--electric-grass: linear-gradient(135deg,rgb(202,248,128) 0%,rgb(113,206,126) 100%);--wp--preset--gradient--midnight: linear-gradient(135deg,rgb(2,3,129) 0%,rgb(40,116,252) 100%);--wp--preset--font-size--small: 13px;--wp--preset--font-size--medium: 20px;--wp--preset--font-size--large: 36px;--wp--preset--font-size--x-large: 42px;--wp--preset--spacing--20: 0.44rem;--wp--preset--spacing--30: 0.67rem;--wp--preset--spacing--40: 1rem;--wp--preset--spacing--50: 1.5rem;--wp--preset--spacing--60: 2.25rem;--wp--preset--spacing--70: 3.38rem;--wp--preset--spacing--80: 5.06rem;--wp--preset--shadow--natural: 6px 6px 9px rgba(0, 0, 0, 0.2);--wp--preset--shadow--deep: 12px 12px 50px rgba(0, 0, 0, 0.4);--wp--preset--shadow--sharp: 6px 6px 0px rgba(0, 0, 0, 0.2);--wp--preset--shadow--outlined: 6px 6px 0px -3px rgba(255, 255, 255, 1), 6px 6px rgba(0, 0, 0, 1);--wp--preset--shadow--crisp: 6px 6px 0px rgba(0, 0, 0, 1);}:where(.is-layout-flex){gap: 0.5em;}:where(.is-layout-grid){gap: 0.5em;}body .is-layout-flex{display: flex;}.is-layout-flex{flex-wrap: wrap;align-items: center;}.is-layout-flex > :is(*, div){margin: 0;}body .is-layout-grid{display: grid;}.is-layout-grid > :is(*, div){margin: 0;}:where(.wp-block-columns.is-layout-flex){gap: 2em;}:where(.wp-block-columns.is-layout-grid){gap: 2em;}:where(.wp-block-post-template.is-layout-flex){gap: 1.25em;}:where(.wp-block-post-template.is-layout-grid){gap: 1.25em;}.has-black-color{color: var(--wp--preset--color--black) !important;}.has-cyan-bluish-gray-color{color: var(--wp--preset--color--cyan-bluish-gray) !important;}.has-white-color{color: var(--wp--preset--color--white) !important;}.has-pale-pink-color{color: var(--wp--preset--color--pale-pink) !important;}.has-vivid-red-color{color: var(--wp--preset--color--vivid-red) !important;}.has-luminous-vivid-orange-color{color: var(--wp--preset--color--luminous-vivid-orange) !important;}.has-luminous-vivid-amber-color{color: var(--wp--preset--color--luminous-vivid-amber) !important;}.has-light-green-cyan-color{color: var(--wp--preset--color--light-green-cyan) !important;}.has-vivid-green-cyan-color{color: var(--wp--preset--color--vivid-green-cyan) !important;}.has-pale-cyan-blue-color{color: var(--wp--preset--color--pale-cyan-blue) !important;}.has-vivid-cyan-blue-color{color: var(--wp--preset--color--vivid-cyan-blue) !important;}.has-vivid-purple-color{color: var(--wp--preset--color--vivid-purple) !important;}.has-black-background-color{background-color: var(--wp--preset--color--black) !important;}.has-cyan-bluish-gray-background-color{background-color: var(--wp--preset--color--cyan-bluish-gray) !important;}.has-white-background-color{background-color: var(--wp--preset--color--white) !important;}.has-pale-pink-background-color{background-color: var(--wp--preset--color--pale-pink) !important;}.has-vivid-red-background-color{background-color: var(--wp--preset--color--vivid-red) !important;}.has-luminous-vivid-orange-background-color{background-color: var(--wp--preset--color--luminous-vivid-orange) !important;}.has-luminous-vivid-amber-background-color{background-color: var(--wp--preset--color--luminous-vivid-amber) !important;}.has-light-green-cyan-background-color{background-color: var(--wp--preset--color--light-green-cyan) !important;}.has-vivid-green-cyan-background-color{background-color: var(--wp--preset--color--vivid-green-cyan) !important;}.has-pale-cyan-blue-background-color{background-color: var(--wp--preset--color--pale-cyan-blue) !important;}.has-vivid-cyan-blue-background-color{background-color: var(--wp--preset--color--vivid-cyan-blue) !important;}.has-vivid-purple-background-color{background-color: var(--wp--preset--color--vivid-purple) !important;}.has-black-border-color{border-color: var(--wp--preset--color--black) !important;}.has-cyan-bluish-gray-border-color{border-color: var(--wp--preset--color--cyan-bluish-gray) !important;}.has-white-border-color{border-color: var(--wp--preset--color--white) !important;}.has-pale-pink-border-color{border-color: var(--wp--preset--color--pale-pink) !important;}.has-vivid-red-border-color{border-color: var(--wp--preset--color--vivid-red) !important;}.has-luminous-vivid-orange-border-color{border-color: var(--wp--preset--color--luminous-vivid-orange) !important;}.has-luminous-vivid-amber-border-color{border-color: var(--wp--preset--color--luminous-vivid-amber) !important;}.has-light-green-cyan-border-color{border-color: var(--wp--preset--color--light-green-cyan) !important;}.has-vivid-green-cyan-border-color{border-color: var(--wp--preset--color--vivid-green-cyan) !important;}.has-pale-cyan-blue-border-color{border-color: var(--wp--preset--color--pale-cyan-blue) !important;}.has-vivid-cyan-blue-border-color{border-color: var(--wp--preset--color--vivid-cyan-blue) !important;}.has-vivid-purple-border-color{border-color: var(--wp--preset--color--vivid-purple) !important;}.has-vivid-cyan-blue-to-vivid-purple-gradient-background{background: var(--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple) !important;}.has-light-green-cyan-to-vivid-green-cyan-gradient-background{background: var(--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan) !important;}.has-luminous-vivid-amber-to-luminous-vivid-orange-gradient-background{background: var(--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange) !important;}.has-luminous-vivid-orange-to-vivid-red-gradient-background{background: var(--wp--preset--gradient--luminous-vivid-orange-to-vivid-red) !important;}.has-very-light-gray-to-cyan-bluish-gray-gradient-background{background: var(--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray) !important;}.has-cool-to-warm-spectrum-gradient-background{background: var(--wp--preset--gradient--cool-to-warm-spectrum) !important;}.has-blush-light-purple-gradient-background{background: var(--wp--preset--gradient--blush-light-purple) !important;}.has-blush-bordeaux-gradient-background{background: var(--wp--preset--gradient--blush-bordeaux) !important;}.has-luminous-dusk-gradient-background{background: var(--wp--preset--gradient--luminous-dusk) !important;}.has-pale-ocean-gradient-background{background: var(--wp--preset--gradient--pale-ocean) !important;}.has-electric-grass-gradient-background{background: var(--wp--preset--gradient--electric-grass) !important;}.has-midnight-gradient-background{background: var(--wp--preset--gradient--midnight) !important;}.has-small-font-size{font-size: var(--wp--preset--font-size--small) !important;}.has-medium-font-size{font-size: var(--wp--preset--font-size--medium) !important;}.has-large-font-size{font-size: var(--wp--preset--font-size--large) !important;}.has-x-large-font-size{font-size: var(--wp--preset--font-size--x-large) !important;}
:where(.wp-block-post-template.is-layout-flex){gap: 1.25em;}:where(.wp-block-post-template.is-layout-grid){gap: 1.25em;}
:where(.wp-block-columns.is-layout-flex){gap: 2em;}:where(.wp-block-columns.is-layout-grid){gap: 2em;}
:root :where(.wp-block-pullquote){font-size: 1.5em;line-height: 1.6;}
</style>
<link rel="stylesheet" id="bookings-for-woocommerce-css" href="./wp-content/plugins/mwb-bookings-for-woocommerce/public/css/mwb-public.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="flatpickercss-css" href="./wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/flatpickr/dist/flatpickr.min.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="mwb-mbfw-select2-css-css" href="./wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/select-2/mwb-bookings-for-woocommerce-select2.css?ver=1763880564" type="text/css" media="all">
<link rel="stylesheet" id="bookings-for-woocommerceglobal_form-css" href="./wp-content/plugins/mwb-bookings-for-woocommerce/public/css/mwb-public-form.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="bookings-for-woocommercecommon-css" href="./wp-content/plugins/mwb-bookings-for-woocommerce/common/css/mwb-bookings-for-woocommerce-common.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="mwb-mbfw-common-custom-css-css" href="./wp-content/plugins/mwb-bookings-for-woocommerce/common/css/mwb-common.min.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="mwb-mbfw-time-picker-css-css" href="./wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/user-friendly-time-picker/dist/css/timepicker.min.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="jquery-ui-css" href="./wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/jquery-ui-css/jquery-ui.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="datetime-picker-css-css" href="./wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/datetimepicker-master/build/jquery.datetimepicker.min.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="mwb-bfwp-multi-date-picker-css-css" href="./wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/multiple-datepicker/jquery-ui.multidatespicker.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="woocommerce-layout-css" href="./wp-content/plugins/woocommerce/assets/css/woocommerce-layout.css?ver=10.3.3" type="text/css" media="all">
<link rel="stylesheet" id="woocommerce-smallscreen-css" href="./wp-content/plugins/woocommerce/assets/css/woocommerce-smallscreen.css?ver=10.3.3" type="text/css" media="only screen and (max-width: 768px)">
<link rel="stylesheet" id="woocommerce-general-css" href="./wp-content/plugins/woocommerce/assets/css/woocommerce.css?ver=10.3.3" type="text/css" media="all">
<style id="woocommerce-inline-inline-css" type="text/css">
.woocommerce form .form-row .required { visibility: visible; }
</style>
<link rel="stylesheet" id="xoo-el-style-css" href="./wp-content/plugins/easy-login-woocommerce/assets/css/xoo-el-style.css?ver=3.0.1" type="text/css" media="all">
<style id="xoo-el-style-inline-css" type="text/css">

	.xoo-el-form-container button.btn.button.xoo-el-action-btn{
		background-color: #000000;
		color: #ffffff;
		font-weight: 600;
		font-size: 15px;
		height: 40px;
	}

.xoo-el-container:not(.xoo-el-style-slider) .xoo-el-inmodal{
	max-width: 800px;
	max-height: 650px;
}

.xoo-el-style-slider .xoo-el-modal{
	transform: translateX(800px);
	max-width: 800px;
}

	.xoo-el-sidebar{
		background-image: url(./wp-content/plugins/easy-login-woocommerce/assets/images/popup-sidebar.jpg);
		min-width: 40%;
	}

.xoo-el-main, .xoo-el-main a , .xoo-el-main label{
	color: #000000;
}
.xoo-el-srcont{
	background-color: #ffffff;
}
.xoo-el-form-container ul.xoo-el-tabs li.xoo-el-active {
	background-color: #000000;
	color: #ffffff;
}
.xoo-el-form-container ul.xoo-el-tabs li{
	background-color: #eeeeee;
	color: #000000;
	font-size: 16px;
	padding: 12px 20px;
}
.xoo-el-main{
	padding: 40px 30px;
}

.xoo-el-form-container button.xoo-el-action-btn:not(.button){
    font-weight: 600;
    font-size: 15px;
}



	.xoo-el-modal:before {
		vertical-align: middle;
	}

	.xoo-el-style-slider .xoo-el-srcont {
		justify-content: center;
	}

	.xoo-el-style-slider .xoo-el-main{
		padding-top: 10px;
		padding-bottom: 10px; 
	}





.xoo-el-popup-active .xoo-el-opac{
    opacity: 0.7;
    background-color: #000000;
}





</style>
<link rel="stylesheet" id="xoo-el-fonts-css" href="./wp-content/plugins/easy-login-woocommerce/assets/css/xoo-el-fonts.css?ver=3.0.1" type="text/css" media="all">
<link rel="stylesheet" id="wpforms-modern-full-css" href="./wp-content/plugins/wpforms-lite/assets/css/frontend/modern/wpforms-full.min.css?ver=1.9.8.2" type="text/css" media="all">
<style id="wpforms-modern-full-inline-css" type="text/css">
:root {
				--wpforms-field-border-radius: 3px;
--wpforms-field-border-style: solid;
--wpforms-field-border-size: 1px;
--wpforms-field-background-color: #ffffff;
--wpforms-field-border-color: rgba( 0, 0, 0, 0.25 );
--wpforms-field-border-color-spare: rgba( 0, 0, 0, 0.25 );
--wpforms-field-text-color: rgba( 0, 0, 0, 0.7 );
--wpforms-field-menu-color: #ffffff;
--wpforms-label-color: rgba( 0, 0, 0, 0.85 );
--wpforms-label-sublabel-color: rgba( 0, 0, 0, 0.55 );
--wpforms-label-error-color: #d63637;
--wpforms-button-border-radius: 3px;
--wpforms-button-border-style: none;
--wpforms-button-border-size: 1px;
--wpforms-button-background-color: #066aab;
--wpforms-button-border-color: #066aab;
--wpforms-button-text-color: #ffffff;
--wpforms-page-break-color: #066aab;
--wpforms-background-image: none;
--wpforms-background-position: center center;
--wpforms-background-repeat: no-repeat;
--wpforms-background-size: cover;
--wpforms-background-width: 100px;
--wpforms-background-height: 100px;
--wpforms-background-color: rgba( 0, 0, 0, 0 );
--wpforms-background-url: none;
--wpforms-container-padding: 0px;
--wpforms-container-border-style: none;
--wpforms-container-border-width: 1px;
--wpforms-container-border-color: #000000;
--wpforms-container-border-radius: 3px;
--wpforms-field-size-input-height: 43px;
--wpforms-field-size-input-spacing: 15px;
--wpforms-field-size-font-size: 16px;
--wpforms-field-size-line-height: 19px;
--wpforms-field-size-padding-h: 14px;
--wpforms-field-size-checkbox-size: 16px;
--wpforms-field-size-sublabel-spacing: 5px;
--wpforms-field-size-icon-size: 1;
--wpforms-label-size-font-size: 16px;
--wpforms-label-size-line-height: 19px;
--wpforms-label-size-sublabel-font-size: 14px;
--wpforms-label-size-sublabel-line-height: 17px;
--wpforms-button-size-font-size: 17px;
--wpforms-button-size-height: 41px;
--wpforms-button-size-padding-h: 15px;
--wpforms-button-size-margin-top: 10px;
--wpforms-container-shadow-size-box-shadow: none;
			}
</style>
<link rel="stylesheet" id="brands-styles-css" href="./wp-content/plugins/woocommerce/assets/css/brands.css?ver=10.3.3" type="text/css" media="all">
<link rel="stylesheet" id="royal-elementor-kit-style-css" href="./wp-content/themes/royal-elementor-kit/style.css?ver=1.0" type="text/css" media="all">
<link rel="stylesheet" id="elementor-frontend-css" href="./wp-content/plugins/elementor/assets/css/frontend.min.css?ver=3.32.4" type="text/css" media="all">
<style id="elementor-frontend-inline-css" type="text/css">
.elementor-800 .elementor-element.elementor-element-9201183:not(.elementor-motion-effects-element-type-background), .elementor-800 .elementor-element.elementor-element-9201183 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-image:url("./wp-content/uploads/2025/10/wmremove-transformed-8-1_11zon.png");background-position:center center;background-size:cover;}.elementor-800 .elementor-element.elementor-element-9201183 > .elementor-background-overlay{background-color:#FFFFFFB8;opacity:0.21;transition:background 0.3s, border-radius 0.3s, opacity 0.3s;}.elementor-800 .elementor-element.elementor-element-9201183{transition:background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;margin-top:10px;margin-bottom:0px;padding:180px 0px 200px 0px;}.elementor-widget-heading .elementor-heading-title{font-family:var( --e-global-typography-primary-font-family ), Sans-serif;font-weight:var( --e-global-typography-primary-font-weight );color:var( --e-global-color-primary );}.elementor-800 .elementor-element.elementor-element-c137ac9 .elementor-heading-title{font-family:"Poppins", Sans-serif;font-size:80px;font-weight:600;line-height:96px;letter-spacing:0.4px;text-shadow:0px 0px 2px #080800;color:#FFFFFF;}.elementor-widget-text-editor{font-family:var( --e-global-typography-text-font-family ), Sans-serif;font-weight:var( --e-global-typography-text-font-weight );color:var( --e-global-color-text );}.elementor-widget-text-editor.elementor-drop-cap-view-stacked .elementor-drop-cap{background-color:var( --e-global-color-primary );}.elementor-widget-text-editor.elementor-drop-cap-view-framed .elementor-drop-cap, .elementor-widget-text-editor.elementor-drop-cap-view-default .elementor-drop-cap{color:var( --e-global-color-primary );border-color:var( --e-global-color-primary );}.elementor-800 .elementor-element.elementor-element-7a4d140 > .elementor-widget-container{padding:10px 0px 20px 0px;}.elementor-800 .elementor-element.elementor-element-7a4d140{text-align:left;font-family:"Poppins", Sans-serif;font-size:14px;font-weight:700;line-height:22px;letter-spacing:0.4px;text-shadow:0px 0px 3px rgba(0, 0, 0, 0.99);color:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-7a4d140 p{margin-block-end:0px;}.elementor-800 .elementor-element.elementor-element-14ca025{--display:flex;--flex-direction:row;--container-widget-width:initial;--container-widget-height:100%;--container-widget-flex-grow:1;--container-widget-align-self:stretch;--flex-wrap-mobile:wrap;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:-20px;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button{background-color:#2EA5B4;-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;border-color:#8783FF00;border-style:solid;border-width:1px 1px 1px 1px;border-radius:5px 5px 5px 5px;}	.elementor-800 .elementor-element.elementor-element-9d207ad [class*="elementor-animation"]:hover,
								.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button::before,
								.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button::after{background-color:#FAFAFAFA;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button::before{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button::after{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;color:#000000;padding:8px 10px 8px 10px;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button .wpr-button-icon{-webkit-transition-duration:0.4s;transition-duration:0.4s;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button .wpr-button-icon svg{-webkit-transition-duration:0.4s;transition-duration:0.4s;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button .wpr-button-text{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button .wpr-button-content{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button-wrap{max-width:155px;}.elementor-800 .elementor-element.elementor-element-9d207ad{text-align:left;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button-content{-webkit-justify-content:center;justify-content:center;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button-text{-webkit-justify-content:center;justify-content:center;color:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button-icon{font-size:18px;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button-icon svg{width:18px;height:18px;}.elementor-800 .elementor-element.elementor-element-9d207ad.wpr-button-icon-position-left .wpr-button-icon{margin-right:12px;}.elementor-800 .elementor-element.elementor-element-9d207ad.wpr-button-icon-position-right .wpr-button-icon{margin-left:12px;}.elementor-800 .elementor-element.elementor-element-9d207ad.wpr-button-icon-style-inline .wpr-button-icon{color:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-9d207ad.wpr-button-icon-style-inline .wpr-button-icon svg{fill:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button-text,.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button::after{font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:500;letter-spacing:0.4px;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button:hover .wpr-button-text{color:#000000;}.elementor-800 .elementor-element.elementor-element-9d207ad.wpr-button-icon-style-inline .wpr-button:hover .wpr-button-icon{color:#000000;}.elementor-800 .elementor-element.elementor-element-9d207ad.wpr-button-icon-style-inline .wpr-button:hover .wpr-button-icon svg{fill:#000000;}.elementor-800 .elementor-element.elementor-element-9d207ad.wpr-button-icon-style-inline .wpr-button{padding:8px 10px 8px 10px;}.elementor-800 .elementor-element.elementor-element-9d207ad.wpr-button-icon-style-block .wpr-button-text{padding:8px 10px 8px 10px;}.elementor-800 .elementor-element.elementor-element-9d207ad.wpr-button-icon-style-inline-block .wpr-button-content{padding:8px 10px 8px 10px;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button{background-color:#2EA5B4;-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;border-color:#8783FF00;border-style:solid;border-width:1px 1px 1px 1px;border-radius:5px 5px 5px 5px;}	.elementor-800 .elementor-element.elementor-element-9d0568a [class*="elementor-animation"]:hover,
								.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button::before,
								.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button::after{background-color:#FAFAFAFA;}.elementor-800 .elementor-element.elementor-element-9d0568a{width:var( --container-widget-width, 42.5% );max-width:42.5%;--container-widget-width:42.5%;--container-widget-flex-grow:0;text-align:left;}.elementor-800 .elementor-element.elementor-element-9d0568a.elementor-element{--flex-grow:0;--flex-shrink:0;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button::before{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button::after{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;color:#000000;padding:8px 10px 8px 10px;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button .wpr-button-icon{-webkit-transition-duration:0.4s;transition-duration:0.4s;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button .wpr-button-icon svg{-webkit-transition-duration:0.4s;transition-duration:0.4s;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button .wpr-button-text{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button .wpr-button-content{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button-wrap{max-width:223px;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button-content{-webkit-justify-content:center;justify-content:center;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button-text{-webkit-justify-content:center;justify-content:center;color:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button-icon{font-size:18px;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button-icon svg{width:18px;height:18px;}.elementor-800 .elementor-element.elementor-element-9d0568a.wpr-button-icon-position-left .wpr-button-icon{margin-right:15px;}.elementor-800 .elementor-element.elementor-element-9d0568a.wpr-button-icon-position-right .wpr-button-icon{margin-left:15px;}.elementor-800 .elementor-element.elementor-element-9d0568a.wpr-button-icon-style-inline .wpr-button-icon{color:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-9d0568a.wpr-button-icon-style-inline .wpr-button-icon svg{fill:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button-text,.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button::after{font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:500;letter-spacing:0.4px;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button:hover .wpr-button-text{color:#000000;}.elementor-800 .elementor-element.elementor-element-9d0568a.wpr-button-icon-style-inline .wpr-button:hover .wpr-button-icon{color:#000000;}.elementor-800 .elementor-element.elementor-element-9d0568a.wpr-button-icon-style-inline .wpr-button:hover .wpr-button-icon svg{fill:#000000;}.elementor-800 .elementor-element.elementor-element-9d0568a.wpr-button-icon-style-inline .wpr-button{padding:8px 10px 8px 10px;}.elementor-800 .elementor-element.elementor-element-9d0568a.wpr-button-icon-style-block .wpr-button-text{padding:8px 10px 8px 10px;}.elementor-800 .elementor-element.elementor-element-9d0568a.wpr-button-icon-style-inline-block .wpr-button-content{padding:8px 10px 8px 10px;}.elementor-bc-flex-widget .elementor-800 .elementor-element.elementor-element-f17f568.elementor-column .elementor-widget-wrap{align-items:flex-end;}.elementor-800 .elementor-element.elementor-element-f17f568.elementor-column.elementor-element[data-element_type="column"] > .elementor-widget-wrap.elementor-element-populated{align-content:flex-end;align-items:flex-end;}.elementor-800 .elementor-element.elementor-element-0cca238{--display:flex;--flex-direction:row;--container-widget-width:initial;--container-widget-height:100%;--container-widget-flex-grow:1;--container-widget-align-self:stretch;--flex-wrap-mobile:wrap;--gap:0px 010px;--row-gap:0px;--column-gap:010px;--padding-top:80px;--padding-bottom:80px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-f28ef88{--display:flex;--flex-direction:column;--container-widget-width:100%;--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--margin-top:0px;--margin-bottom:0px;--margin-left:80px;--margin-right:0px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-8a97c8b .elementor-heading-title{font-family:"Island Moments", Sans-serif;font-weight:600;color:#555659;}.elementor-800 .elementor-element.elementor-element-84626d4{width:var( --container-widget-width, 80% );max-width:80%;--container-widget-width:80%;--container-widget-flex-grow:0;}.elementor-800 .elementor-element.elementor-element-84626d4 .elementor-heading-title{font-family:"Spinnaker", Sans-serif;font-size:35px;font-weight:600;letter-spacing:1.2px;color:#555659;}.elementor-800 .elementor-element.elementor-element-d1e43b4{--display:flex;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-widget-icon-box.elementor-view-stacked .elementor-icon{background-color:var( --e-global-color-primary );}.elementor-widget-icon-box.elementor-view-framed .elementor-icon, .elementor-widget-icon-box.elementor-view-default .elementor-icon{fill:var( --e-global-color-primary );color:var( --e-global-color-primary );border-color:var( --e-global-color-primary );}.elementor-widget-icon-box .elementor-icon-box-title, .elementor-widget-icon-box .elementor-icon-box-title a{font-family:var( --e-global-typography-primary-font-family ), Sans-serif;font-weight:var( --e-global-typography-primary-font-weight );}.elementor-widget-icon-box .elementor-icon-box-title{color:var( --e-global-color-primary );}.elementor-widget-icon-box:has(:hover) .elementor-icon-box-title,
					 .elementor-widget-icon-box:has(:focus) .elementor-icon-box-title{color:var( --e-global-color-primary );}.elementor-widget-icon-box .elementor-icon-box-description{font-family:var( --e-global-typography-text-font-family ), Sans-serif;font-weight:var( --e-global-typography-text-font-weight );color:var( --e-global-color-text );}.elementor-800 .elementor-element.elementor-element-0da7fd7 .elementor-icon-box-wrapper{align-items:center;}.elementor-800 .elementor-element.elementor-element-0da7fd7{--icon-box-icon-margin:20px;}.elementor-800 .elementor-element.elementor-element-0da7fd7 .elementor-icon-box-title{margin-block-end:15px;color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-0da7fd7.elementor-view-stacked .elementor-icon{background-color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-0da7fd7.elementor-view-framed .elementor-icon, .elementor-800 .elementor-element.elementor-element-0da7fd7.elementor-view-default .elementor-icon{fill:#2EA5B4;color:#2EA5B4;border-color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-0da7fd7 .elementor-icon{font-size:35px;}.elementor-800 .elementor-element.elementor-element-0da7fd7 .elementor-icon-box-title, .elementor-800 .elementor-element.elementor-element-0da7fd7 .elementor-icon-box-title a{font-family:"Spinnaker", Sans-serif;font-size:25px;font-weight:600;}.elementor-800 .elementor-element.elementor-element-0da7fd7 .elementor-icon-box-description{font-family:"Roboto", Sans-serif;font-weight:400;}.elementor-800 .elementor-element.elementor-element-3f22b43{--display:flex;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-b4c85bd .elementor-icon-box-wrapper{align-items:center;}.elementor-800 .elementor-element.elementor-element-b4c85bd{--icon-box-icon-margin:20px;}.elementor-800 .elementor-element.elementor-element-b4c85bd .elementor-icon-box-title{margin-block-end:15px;color:#555659;}.elementor-800 .elementor-element.elementor-element-b4c85bd.elementor-view-stacked .elementor-icon{background-color:#595959;}.elementor-800 .elementor-element.elementor-element-b4c85bd.elementor-view-framed .elementor-icon, .elementor-800 .elementor-element.elementor-element-b4c85bd.elementor-view-default .elementor-icon{fill:#595959;color:#595959;border-color:#595959;}.elementor-800 .elementor-element.elementor-element-b4c85bd .elementor-icon{font-size:35px;}.elementor-800 .elementor-element.elementor-element-b4c85bd .elementor-icon-box-title, .elementor-800 .elementor-element.elementor-element-b4c85bd .elementor-icon-box-title a{font-family:"Spinnaker", Sans-serif;font-size:25px;font-weight:600;}.elementor-800 .elementor-element.elementor-element-b4c85bd .elementor-icon-box-description{font-family:"Roboto", Sans-serif;font-weight:400;}.elementor-800 .elementor-element.elementor-element-f3db361{--display:flex;--flex-direction:column;--container-widget-width:100%;--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--border-radius:15px 15px 15px 15px;box-shadow:0px 1px 3px 1px rgba(0,0,0,0.5);--margin-top:-150px;--margin-bottom:0px;--margin-left:110px;--margin-right:30px;--padding-top:30px;--padding-bottom:30px;--padding-left:30px;--padding-right:30px;}.elementor-800 .elementor-element.elementor-element-f3db361:not(.elementor-motion-effects-element-type-background), .elementor-800 .elementor-element.elementor-element-f3db361 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-7fdae99{text-align:center;}.elementor-800 .elementor-element.elementor-element-7fdae99 .elementor-heading-title{color:#555659;}.elementor-800 .elementor-element.elementor-element-8600ff8 > .elementor-widget-container{padding:0px 10px 0px 10px;}.elementor-800 .elementor-element.elementor-element-8600ff8{text-align:center;}.elementor-800 .elementor-element.elementor-element-8600ff8 .elementor-heading-title{color:#555659;}.elementor-800 .elementor-element.elementor-element-56a5339 > .elementor-widget-container{margin:-20px 0px 0px 0px;padding:0px 20px 0px 20px;}.elementor-800 .elementor-element.elementor-element-d1e3852{--display:flex;--flex-direction:column;--container-widget-width:100%;--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--margin-top:0px;--margin-bottom:0px;--margin-left:0px;--margin-right:0px;--padding-top:80px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-3bc9b91{--display:flex;--flex-direction:row;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:100%;--container-widget-flex-grow:1;--container-widget-align-self:stretch;--flex-wrap-mobile:wrap;--align-items:center;--gap:50px 50px;--row-gap:50px;--column-gap:50px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-a1e7388{--display:flex;--margin-top:0px;--margin-bottom:0px;--margin-left:50px;--margin-right:0px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-a1e7388.e-con{--flex-grow:0;--flex-shrink:0;}.elementor-800 .elementor-element.elementor-element-4f71258 .elementor-heading-title{font-family:"Island Moments", Sans-serif;font-weight:600;color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-0e8ad55{width:100%;max-width:100%;}.elementor-800 .elementor-element.elementor-element-0e8ad55 .elementor-heading-title{font-family:"Spinnaker", Sans-serif;font-size:35px;font-weight:600;letter-spacing:1.2px;color:#555659;}.elementor-800 .elementor-element.elementor-element-b788551{--display:flex;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-b788551.e-con{--flex-grow:0;--flex-shrink:0;}.elementor-800 .elementor-element.elementor-element-a4644ea{--display:flex;--flex-direction:row;--container-widget-width:initial;--container-widget-height:100%;--container-widget-flex-grow:1;--container-widget-align-self:stretch;--flex-wrap-mobile:wrap;--justify-content:flex-end;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button{background-color:#2EA5B4;-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;border-color:#8783FF00;border-style:solid;border-width:1px 1px 1px 1px;border-radius:15px 15px 15px 15px;}	.elementor-800 .elementor-element.elementor-element-795dfbc [class*="elementor-animation"]:hover,
								.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button::before,
								.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button::after{background-color:#DADADAFA;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button::before{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button::after{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;color:#000000;padding:8px 10px 8px 10px;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button .wpr-button-icon{-webkit-transition-duration:0.4s;transition-duration:0.4s;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button .wpr-button-icon svg{-webkit-transition-duration:0.4s;transition-duration:0.4s;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button .wpr-button-text{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button .wpr-button-content{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button-wrap{max-width:170px;}.elementor-800 .elementor-element.elementor-element-795dfbc{text-align:left;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button-content{-webkit-justify-content:center;justify-content:center;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button-text{-webkit-justify-content:center;justify-content:center;color:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button-icon{font-size:18px;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button-icon svg{width:18px;height:18px;}.elementor-800 .elementor-element.elementor-element-795dfbc.wpr-button-icon-position-left .wpr-button-icon{margin-right:12px;}.elementor-800 .elementor-element.elementor-element-795dfbc.wpr-button-icon-position-right .wpr-button-icon{margin-left:12px;}.elementor-800 .elementor-element.elementor-element-795dfbc.wpr-button-icon-style-inline .wpr-button-icon{color:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-795dfbc.wpr-button-icon-style-inline .wpr-button-icon svg{fill:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button-text,.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button::after{font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:500;letter-spacing:0.4px;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button:hover .wpr-button-text{color:#000000;}.elementor-800 .elementor-element.elementor-element-795dfbc.wpr-button-icon-style-inline .wpr-button:hover .wpr-button-icon{color:#000000;}.elementor-800 .elementor-element.elementor-element-795dfbc.wpr-button-icon-style-inline .wpr-button:hover .wpr-button-icon svg{fill:#000000;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button:hover{border-color:#FFFFFFFA;}.elementor-800 .elementor-element.elementor-element-795dfbc.wpr-button-icon-style-inline .wpr-button{padding:8px 10px 8px 10px;}.elementor-800 .elementor-element.elementor-element-795dfbc.wpr-button-icon-style-block .wpr-button-text{padding:8px 10px 8px 10px;}.elementor-800 .elementor-element.elementor-element-795dfbc.wpr-button-icon-style-inline-block .wpr-button-content{padding:8px 10px 8px 10px;}.elementor-800 .elementor-element.elementor-element-da459cb{--display:flex;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-44fb30b{--display:grid;--e-con-grid-template-columns:repeat(3, 1fr);--e-con-grid-template-rows:repeat(1, 1fr);--gap:50px 50px;--row-gap:50px;--column-gap:50px;--grid-auto-flow:row;--padding-top:0px;--padding-bottom:0px;--padding-left:30px;--padding-right:30px;}.elementor-800 .elementor-element.elementor-element-1483345{--display:flex;--min-height:0px;--flex-direction:column;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--justify-content:center;--align-items:center;--gap:0px 0px;--row-gap:0px;--column-gap:0px;--border-radius:20px 20px 20px 20px;box-shadow:5px 7px 20px 0px rgba(0,0,0,0.5);--margin-top:250px;--margin-bottom:0350px;--margin-left:0px;--margin-right:0px;--padding-top:-2px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-23d8769{--display:flex;--min-height:0px;--margin-top:-200px;--margin-bottom:0px;--margin-left:0px;--margin-right:0px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-widget-image .widget-image-caption{color:var( --e-global-color-text );font-family:var( --e-global-typography-text-font-family ), Sans-serif;font-weight:var( --e-global-typography-text-font-weight );}.elementor-800 .elementor-element.elementor-element-7151b5f img{border-radius:20px 20px 20px 20px;}.elementor-800 .elementor-element.elementor-element-a6eb1d8{--display:flex;--margin-top:-54px;--margin-bottom:0px;--margin-left:-138px;--margin-right:0px;--padding-top:10px;--padding-bottom:10px;--padding-left:30px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-a6eb1d8:not(.elementor-motion-effects-element-type-background), .elementor-800 .elementor-element.elementor-element-a6eb1d8 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#FFFFFF;}.elementor-widget-icon-list .elementor-icon-list-item:not(:last-child):after{border-color:var( --e-global-color-text );}.elementor-widget-icon-list .elementor-icon-list-icon i{color:var( --e-global-color-primary );}.elementor-widget-icon-list .elementor-icon-list-icon svg{fill:var( --e-global-color-primary );}.elementor-widget-icon-list .elementor-icon-list-item > .elementor-icon-list-text, .elementor-widget-icon-list .elementor-icon-list-item > a{font-family:var( --e-global-typography-text-font-family ), Sans-serif;font-weight:var( --e-global-typography-text-font-weight );}.elementor-widget-icon-list .elementor-icon-list-text{color:var( --e-global-color-secondary );}.elementor-800 .elementor-element.elementor-element-e85d882 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child){padding-block-end:calc(30px/2);}.elementor-800 .elementor-element.elementor-element-e85d882 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child){margin-block-start:calc(30px/2);}.elementor-800 .elementor-element.elementor-element-e85d882 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item{margin-inline:calc(30px/2);}.elementor-800 .elementor-element.elementor-element-e85d882 .elementor-icon-list-items.elementor-inline-items{margin-inline:calc(-30px/2);}.elementor-800 .elementor-element.elementor-element-e85d882 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after{inset-inline-end:calc(-30px/2);}.elementor-800 .elementor-element.elementor-element-e85d882 .elementor-icon-list-icon i{color:#2EA5B4;transition:color 0.3s;}.elementor-800 .elementor-element.elementor-element-e85d882 .elementor-icon-list-icon svg{fill:#2EA5B4;transition:fill 0.3s;}.elementor-800 .elementor-element.elementor-element-e85d882{--e-icon-list-icon-size:20px;--icon-vertical-offset:0px;}.elementor-800 .elementor-element.elementor-element-e85d882 .elementor-icon-list-item > .elementor-icon-list-text, .elementor-800 .elementor-element.elementor-element-e85d882 .elementor-icon-list-item > a{font-family:"Roboto", Sans-serif;font-weight:400;}.elementor-800 .elementor-element.elementor-element-e85d882 .elementor-icon-list-text{color:var( --e-global-color-secondary );transition:color 0.3s;}.elementor-800 .elementor-element.elementor-element-6e911eb{--display:flex;--flex-direction:column;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--justify-content:center;--align-items:flex-start;--gap:20px 20px;--row-gap:20px;--column-gap:20px;--border-radius:0px 0px 20px 20px;--padding-top:30px;--padding-bottom:30px;--padding-left:30px;--padding-right:30px;}.elementor-800 .elementor-element.elementor-element-6e911eb:not(.elementor-motion-effects-element-type-background), .elementor-800 .elementor-element.elementor-element-6e911eb > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-79612c4 .elementor-heading-title{font-family:"Spinnaker", Sans-serif;font-size:23px;font-weight:600;color:#434343;}.elementor-800 .elementor-element.elementor-element-2724f8c .elementor-icon-list-icon i{color:#2EA5B4;transition:color 0.3s;}.elementor-800 .elementor-element.elementor-element-2724f8c .elementor-icon-list-icon svg{fill:#2EA5B4;transition:fill 0.3s;}.elementor-800 .elementor-element.elementor-element-2724f8c{--e-icon-list-icon-size:18px;--icon-vertical-offset:0px;}.elementor-800 .elementor-element.elementor-element-2724f8c .elementor-icon-list-text{transition:color 0.3s;}.elementor-widget-divider{--divider-color:var( --e-global-color-secondary );}.elementor-widget-divider .elementor-divider__text{color:var( --e-global-color-secondary );font-family:var( --e-global-typography-secondary-font-family ), Sans-serif;font-weight:var( --e-global-typography-secondary-font-weight );}.elementor-widget-divider.elementor-view-stacked .elementor-icon{background-color:var( --e-global-color-secondary );}.elementor-widget-divider.elementor-view-framed .elementor-icon, .elementor-widget-divider.elementor-view-default .elementor-icon{color:var( --e-global-color-secondary );border-color:var( --e-global-color-secondary );}.elementor-widget-divider.elementor-view-framed .elementor-icon, .elementor-widget-divider.elementor-view-default .elementor-icon svg{fill:var( --e-global-color-secondary );}.elementor-800 .elementor-element.elementor-element-aaa0c50{--divider-border-style:solid;--divider-color:#ADADAD;--divider-border-width:1px;}.elementor-800 .elementor-element.elementor-element-aaa0c50 .elementor-divider-separator{width:100%;}.elementor-800 .elementor-element.elementor-element-aaa0c50 .elementor-divider{padding-block-start:2px;padding-block-end:2px;}.elementor-800 .elementor-element.elementor-element-b6c3ea7{--display:flex;--flex-direction:row;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:100%;--container-widget-flex-grow:1;--container-widget-align-self:stretch;--flex-wrap-mobile:wrap;--justify-content:space-between;--align-items:center;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-f3597f9{--display:flex;--justify-content:center;--align-items:center;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--gap:10px 10px;--row-gap:10px;--column-gap:10px;--margin-top:0px;--margin-bottom:0px;--margin-left:0px;--margin-right:0px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-f3597f9.e-con{--flex-grow:0;--flex-shrink:0;}.elementor-800 .elementor-element.elementor-element-12d1ff6 > .elementor-widget-container{margin:0px 240px 0px 0px;}.elementor-800 .elementor-element.elementor-element-12d1ff6 .elementor-heading-title{font-family:"Spinnaker", Sans-serif;font-size:18px;font-weight:600;color:#434343;}.elementor-800 .elementor-element.elementor-element-8e83b26 .elementor-heading-title{font-family:"Roboto", Sans-serif;font-weight:600;color:#434343;}.elementor-widget-button .elementor-button{background-color:var( --e-global-color-accent );font-family:var( --e-global-typography-accent-font-family ), Sans-serif;font-weight:var( --e-global-typography-accent-font-weight );}.elementor-800 .elementor-element.elementor-element-5f6a2d8 .elementor-button{background-color:#2EA5B4;border-radius:15px 15px 15px 15px;}.elementor-800 .elementor-element.elementor-element-5f6a2d8 .elementor-button:hover, .elementor-800 .elementor-element.elementor-element-5f6a2d8 .elementor-button:focus{background-color:#7D7474FA;}.elementor-800 .elementor-element.elementor-element-5f6a2d8 > .elementor-widget-container{padding:10px 0px 5px 0px;}.elementor-800 .elementor-element.elementor-element-e5cc5c8{--display:flex;--min-height:0px;--flex-direction:column;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--justify-content:center;--align-items:center;--gap:0px 0px;--row-gap:0px;--column-gap:0px;--border-radius:20px 20px 20px 20px;box-shadow:5px 7px 20px 0px rgba(0,0,0,0.5);--margin-top:250px;--margin-bottom:350px;--margin-left:0px;--margin-right:0px;--padding-top:-2px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-9ae86b6{--display:flex;--min-height:0px;--margin-top:-200px;--margin-bottom:0px;--margin-left:0px;--margin-right:0px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-e5e84b3 img{border-radius:20px 20px 20px 20px;}.elementor-800 .elementor-element.elementor-element-e517d73{--display:flex;--margin-top:-54px;--margin-bottom:0px;--margin-left:-138px;--margin-right:0px;--padding-top:10px;--padding-bottom:10px;--padding-left:30px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-e517d73:not(.elementor-motion-effects-element-type-background), .elementor-800 .elementor-element.elementor-element-e517d73 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-6ca2fcd .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child){padding-block-end:calc(30px/2);}.elementor-800 .elementor-element.elementor-element-6ca2fcd .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child){margin-block-start:calc(30px/2);}.elementor-800 .elementor-element.elementor-element-6ca2fcd .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item{margin-inline:calc(30px/2);}.elementor-800 .elementor-element.elementor-element-6ca2fcd .elementor-icon-list-items.elementor-inline-items{margin-inline:calc(-30px/2);}.elementor-800 .elementor-element.elementor-element-6ca2fcd .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after{inset-inline-end:calc(-30px/2);}.elementor-800 .elementor-element.elementor-element-6ca2fcd .elementor-icon-list-icon i{color:#2EA5B4;transition:color 0.3s;}.elementor-800 .elementor-element.elementor-element-6ca2fcd .elementor-icon-list-icon svg{fill:#2EA5B4;transition:fill 0.3s;}.elementor-800 .elementor-element.elementor-element-6ca2fcd{--e-icon-list-icon-size:20px;--icon-vertical-offset:0px;}.elementor-800 .elementor-element.elementor-element-6ca2fcd .elementor-icon-list-item > .elementor-icon-list-text, .elementor-800 .elementor-element.elementor-element-6ca2fcd .elementor-icon-list-item > a{font-family:"Roboto", Sans-serif;font-weight:400;}.elementor-800 .elementor-element.elementor-element-6ca2fcd .elementor-icon-list-text{color:var( --e-global-color-secondary );transition:color 0.3s;}.elementor-800 .elementor-element.elementor-element-57fd93b{--display:flex;--flex-direction:column;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--justify-content:center;--align-items:flex-start;--gap:20px 20px;--row-gap:20px;--column-gap:20px;--border-radius:0px 0px 20px 20px;--padding-top:30px;--padding-bottom:30px;--padding-left:30px;--padding-right:30px;}.elementor-800 .elementor-element.elementor-element-57fd93b:not(.elementor-motion-effects-element-type-background), .elementor-800 .elementor-element.elementor-element-57fd93b > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-a5086b3 .elementor-heading-title{font-family:"Spinnaker", Sans-serif;font-size:23px;font-weight:600;color:#434343;}.elementor-800 .elementor-element.elementor-element-66ea9d2 .elementor-icon-list-icon i{color:#2EA5B4;transition:color 0.3s;}.elementor-800 .elementor-element.elementor-element-66ea9d2 .elementor-icon-list-icon svg{fill:#2EA5B4;transition:fill 0.3s;}.elementor-800 .elementor-element.elementor-element-66ea9d2{--e-icon-list-icon-size:18px;--icon-vertical-offset:0px;}.elementor-800 .elementor-element.elementor-element-66ea9d2 .elementor-icon-list-text{transition:color 0.3s;}.elementor-800 .elementor-element.elementor-element-efdbbe5{--divider-border-style:solid;--divider-color:#ADADAD;--divider-border-width:1px;}.elementor-800 .elementor-element.elementor-element-efdbbe5 .elementor-divider-separator{width:100%;}.elementor-800 .elementor-element.elementor-element-efdbbe5 .elementor-divider{padding-block-start:2px;padding-block-end:2px;}.elementor-800 .elementor-element.elementor-element-4bd7518{--display:flex;--flex-direction:row;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:100%;--container-widget-flex-grow:1;--container-widget-align-self:stretch;--flex-wrap-mobile:wrap;--justify-content:space-between;--align-items:center;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-83103a4{--display:flex;--justify-content:center;--align-items:center;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--gap:10px 10px;--row-gap:10px;--column-gap:10px;--margin-top:0px;--margin-bottom:0px;--margin-left:0px;--margin-right:0px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-83103a4.e-con{--flex-grow:0;--flex-shrink:0;}.elementor-800 .elementor-element.elementor-element-565e5b3 > .elementor-widget-container{margin:0px 240px 0px 0px;}.elementor-800 .elementor-element.elementor-element-565e5b3 .elementor-heading-title{font-family:"Spinnaker", Sans-serif;font-size:18px;font-weight:600;color:#434343;}.elementor-800 .elementor-element.elementor-element-2c3fe3f .elementor-heading-title{font-family:"Roboto", Sans-serif;font-weight:600;color:#434343;}.elementor-800 .elementor-element.elementor-element-7fb1f80 .elementor-button{background-color:#2EA5B4;border-radius:15px 15px 15px 15px;}.elementor-800 .elementor-element.elementor-element-7fb1f80 .elementor-button:hover, .elementor-800 .elementor-element.elementor-element-7fb1f80 .elementor-button:focus{background-color:#7D7474FA;}.elementor-800 .elementor-element.elementor-element-7fb1f80 > .elementor-widget-container{padding:10px 0px 5px 0px;}.elementor-800 .elementor-element.elementor-element-c1d75bb{--display:flex;--min-height:0px;--flex-direction:column;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--justify-content:center;--align-items:center;--gap:0px 0px;--row-gap:0px;--column-gap:0px;--border-radius:20px 20px 20px 20px;box-shadow:5px 7px 20px 0px rgba(0,0,0,0.5);--margin-top:250px;--margin-bottom:350px;--margin-left:0px;--margin-right:0px;--padding-top:-2px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-223d666{--display:flex;--min-height:0px;--margin-top:-200px;--margin-bottom:0px;--margin-left:0px;--margin-right:0px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-856180c img{border-radius:20px 20px 20px 20px;}.elementor-800 .elementor-element.elementor-element-4b42ddd{--display:flex;--margin-top:-54px;--margin-bottom:0px;--margin-left:-138px;--margin-right:0px;--padding-top:10px;--padding-bottom:10px;--padding-left:30px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-4b42ddd:not(.elementor-motion-effects-element-type-background), .elementor-800 .elementor-element.elementor-element-4b42ddd > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-de6c802 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child){padding-block-end:calc(30px/2);}.elementor-800 .elementor-element.elementor-element-de6c802 .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child){margin-block-start:calc(30px/2);}.elementor-800 .elementor-element.elementor-element-de6c802 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item{margin-inline:calc(30px/2);}.elementor-800 .elementor-element.elementor-element-de6c802 .elementor-icon-list-items.elementor-inline-items{margin-inline:calc(-30px/2);}.elementor-800 .elementor-element.elementor-element-de6c802 .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after{inset-inline-end:calc(-30px/2);}.elementor-800 .elementor-element.elementor-element-de6c802 .elementor-icon-list-icon i{color:#2EA5B4;transition:color 0.3s;}.elementor-800 .elementor-element.elementor-element-de6c802 .elementor-icon-list-icon svg{fill:#2EA5B4;transition:fill 0.3s;}.elementor-800 .elementor-element.elementor-element-de6c802{--e-icon-list-icon-size:20px;--icon-vertical-offset:0px;}.elementor-800 .elementor-element.elementor-element-de6c802 .elementor-icon-list-item > .elementor-icon-list-text, .elementor-800 .elementor-element.elementor-element-de6c802 .elementor-icon-list-item > a{font-family:"Roboto", Sans-serif;font-weight:400;}.elementor-800 .elementor-element.elementor-element-de6c802 .elementor-icon-list-text{color:var( --e-global-color-secondary );transition:color 0.3s;}.elementor-800 .elementor-element.elementor-element-5ce1439{--display:flex;--flex-direction:column;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--justify-content:center;--align-items:flex-start;--gap:20px 20px;--row-gap:20px;--column-gap:20px;--border-radius:0px 0px 20px 20px;--padding-top:30px;--padding-bottom:30px;--padding-left:30px;--padding-right:30px;}.elementor-800 .elementor-element.elementor-element-5ce1439:not(.elementor-motion-effects-element-type-background), .elementor-800 .elementor-element.elementor-element-5ce1439 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-eb7dbcd .elementor-heading-title{font-family:"Spinnaker", Sans-serif;font-size:23px;font-weight:600;color:#434343;}.elementor-800 .elementor-element.elementor-element-c3fdd4e .elementor-icon-list-icon i{color:#2EA5B4;transition:color 0.3s;}.elementor-800 .elementor-element.elementor-element-c3fdd4e .elementor-icon-list-icon svg{fill:#2EA5B4;transition:fill 0.3s;}.elementor-800 .elementor-element.elementor-element-c3fdd4e{--e-icon-list-icon-size:18px;--icon-vertical-offset:0px;}.elementor-800 .elementor-element.elementor-element-c3fdd4e .elementor-icon-list-text{transition:color 0.3s;}.elementor-800 .elementor-element.elementor-element-0910da4{--divider-border-style:solid;--divider-color:#ADADAD;--divider-border-width:1px;}.elementor-800 .elementor-element.elementor-element-0910da4 .elementor-divider-separator{width:100%;}.elementor-800 .elementor-element.elementor-element-0910da4 .elementor-divider{padding-block-start:2px;padding-block-end:2px;}.elementor-800 .elementor-element.elementor-element-4961110{--display:flex;--flex-direction:row;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:100%;--container-widget-flex-grow:1;--container-widget-align-self:stretch;--flex-wrap-mobile:wrap;--justify-content:space-between;--align-items:center;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-5da70e3{--display:flex;--justify-content:center;--align-items:center;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--gap:10px 10px;--row-gap:10px;--column-gap:10px;--margin-top:0px;--margin-bottom:0px;--margin-left:0px;--margin-right:0px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-5da70e3.e-con{--flex-grow:0;--flex-shrink:0;}.elementor-800 .elementor-element.elementor-element-8f45793 > .elementor-widget-container{margin:0px 240px 0px 0px;}.elementor-800 .elementor-element.elementor-element-8f45793 .elementor-heading-title{font-family:"Spinnaker", Sans-serif;font-size:18px;font-weight:600;color:#434343;}.elementor-800 .elementor-element.elementor-element-35702fc .elementor-heading-title{font-family:"Roboto", Sans-serif;font-weight:600;color:#434343;}.elementor-800 .elementor-element.elementor-element-b912e80 .elementor-button{background-color:#2EA5B4;border-radius:15px 15px 15px 15px;}.elementor-800 .elementor-element.elementor-element-b912e80 .elementor-button:hover, .elementor-800 .elementor-element.elementor-element-b912e80 .elementor-button:focus{background-color:#7D7474FA;}.elementor-800 .elementor-element.elementor-element-b912e80 > .elementor-widget-container{padding:10px 0px 5px 0px;}.elementor-800 .elementor-element.elementor-element-fdc2fbc{--display:flex;--flex-direction:row;--container-widget-width:initial;--container-widget-height:100%;--container-widget-flex-grow:1;--container-widget-align-self:stretch;--flex-wrap-mobile:wrap;--gap:0px 0px;--row-gap:0px;--column-gap:0px;--margin-top:-180px;--margin-bottom:0px;--margin-left:0px;--margin-right:0px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-431c2e0{--display:flex;--flex-direction:column;--container-widget-width:100%;--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-431c2e0:not(.elementor-motion-effects-element-type-background), .elementor-800 .elementor-element.elementor-element-431c2e0 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-image:url("./wp-content/uploads/2025/10/DeWatermark.ai_1761590304544.jpeg");background-position:center center;background-size:cover;}.elementor-800 .elementor-element.elementor-element-1e3b1f8{--display:flex;--flex-direction:column;--container-widget-width:100%;--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:50px;}.elementor-800 .elementor-element.elementor-element-b330488{--display:flex;--margin-top:0px;--margin-bottom:0px;--margin-left:50px;--margin-right:0px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-b330488.e-con{--flex-grow:0;--flex-shrink:0;}.elementor-800 .elementor-element.elementor-element-365923e .elementor-heading-title{font-family:"Island Moments", Sans-serif;font-weight:600;color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-86c00e0{width:100%;max-width:100%;}.elementor-800 .elementor-element.elementor-element-86c00e0 .elementor-heading-title{font-family:"Spinnaker", Sans-serif;font-size:35px;font-weight:600;letter-spacing:1.2px;color:#555659;}.elementor-800 .elementor-element.elementor-element-dfebc5c{--display:grid;--e-con-grid-template-columns:repeat(2, 1fr);--e-con-grid-template-rows:repeat(3, 1fr);--gap:30px 30px;--row-gap:30px;--column-gap:30px;--grid-auto-flow:row;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-87fa871{--display:flex;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-2a1f805 .elementor-icon-box-wrapper{align-items:center;}.elementor-800 .elementor-element.elementor-element-2a1f805{--icon-box-icon-margin:15px;}.elementor-800 .elementor-element.elementor-element-2a1f805 .elementor-icon-box-title{margin-block-end:10px;color:#555659;}.elementor-800 .elementor-element.elementor-element-2a1f805.elementor-view-stacked .elementor-icon{background-color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-2a1f805.elementor-view-framed .elementor-icon, .elementor-800 .elementor-element.elementor-element-2a1f805.elementor-view-default .elementor-icon{fill:#2EA5B4;color:#2EA5B4;border-color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-2a1f805 .elementor-icon{font-size:35px;}.elementor-800 .elementor-element.elementor-element-2a1f805 .elementor-icon-box-title, .elementor-800 .elementor-element.elementor-element-2a1f805 .elementor-icon-box-title a{font-family:"Spinnaker", Sans-serif;font-size:19px;font-weight:600;}.elementor-800 .elementor-element.elementor-element-2a1f805 .elementor-icon-box-description{font-family:"Roboto", Sans-serif;font-weight:400;color:#7A7A7A;}.elementor-800 .elementor-element.elementor-element-3e4a03e{--display:flex;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-fe331b2 .elementor-icon-box-wrapper{align-items:center;}.elementor-800 .elementor-element.elementor-element-fe331b2{--icon-box-icon-margin:15px;}.elementor-800 .elementor-element.elementor-element-fe331b2 .elementor-icon-box-title{margin-block-end:10px;color:#555659;}.elementor-800 .elementor-element.elementor-element-fe331b2.elementor-view-stacked .elementor-icon{background-color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-fe331b2.elementor-view-framed .elementor-icon, .elementor-800 .elementor-element.elementor-element-fe331b2.elementor-view-default .elementor-icon{fill:#2EA5B4;color:#2EA5B4;border-color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-fe331b2 .elementor-icon{font-size:35px;}.elementor-800 .elementor-element.elementor-element-fe331b2 .elementor-icon-box-title, .elementor-800 .elementor-element.elementor-element-fe331b2 .elementor-icon-box-title a{font-family:"Spinnaker", Sans-serif;font-size:19px;font-weight:600;}.elementor-800 .elementor-element.elementor-element-fe331b2 .elementor-icon-box-description{font-family:"Roboto", Sans-serif;font-weight:400;color:#7A7A7A;}.elementor-800 .elementor-element.elementor-element-7d24c99{--display:flex;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-60f7401 .elementor-icon-box-wrapper{align-items:center;}.elementor-800 .elementor-element.elementor-element-60f7401{--icon-box-icon-margin:15px;}.elementor-800 .elementor-element.elementor-element-60f7401 .elementor-icon-box-title{margin-block-end:10px;color:#555659;}.elementor-800 .elementor-element.elementor-element-60f7401.elementor-view-stacked .elementor-icon{background-color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-60f7401.elementor-view-framed .elementor-icon, .elementor-800 .elementor-element.elementor-element-60f7401.elementor-view-default .elementor-icon{fill:#2EA5B4;color:#2EA5B4;border-color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-60f7401 .elementor-icon{font-size:35px;}.elementor-800 .elementor-element.elementor-element-60f7401 .elementor-icon-box-title, .elementor-800 .elementor-element.elementor-element-60f7401 .elementor-icon-box-title a{font-family:"Spinnaker", Sans-serif;font-size:19px;font-weight:600;}.elementor-800 .elementor-element.elementor-element-60f7401 .elementor-icon-box-description{font-family:"Roboto", Sans-serif;font-weight:400;color:#7A7A7A;}.elementor-800 .elementor-element.elementor-element-ad571ad{--display:flex;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-a588300 .elementor-icon-box-wrapper{align-items:center;}.elementor-800 .elementor-element.elementor-element-a588300{--icon-box-icon-margin:15px;}.elementor-800 .elementor-element.elementor-element-a588300 .elementor-icon-box-title{margin-block-end:10px;color:#555659;}.elementor-800 .elementor-element.elementor-element-a588300.elementor-view-stacked .elementor-icon{background-color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-a588300.elementor-view-framed .elementor-icon, .elementor-800 .elementor-element.elementor-element-a588300.elementor-view-default .elementor-icon{fill:#2EA5B4;color:#2EA5B4;border-color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-a588300 .elementor-icon{font-size:35px;}.elementor-800 .elementor-element.elementor-element-a588300 .elementor-icon-box-title, .elementor-800 .elementor-element.elementor-element-a588300 .elementor-icon-box-title a{font-family:"Spinnaker", Sans-serif;font-size:19px;font-weight:600;}.elementor-800 .elementor-element.elementor-element-a588300 .elementor-icon-box-description{font-family:"Roboto", Sans-serif;font-weight:400;color:#7A7A7A;}.elementor-800 .elementor-element.elementor-element-676ae59{--display:flex;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-3e72418 .elementor-icon-box-wrapper{align-items:center;}.elementor-800 .elementor-element.elementor-element-3e72418{--icon-box-icon-margin:15px;}.elementor-800 .elementor-element.elementor-element-3e72418 .elementor-icon-box-title{margin-block-end:10px;color:#555659;}.elementor-800 .elementor-element.elementor-element-3e72418.elementor-view-stacked .elementor-icon{background-color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-3e72418.elementor-view-framed .elementor-icon, .elementor-800 .elementor-element.elementor-element-3e72418.elementor-view-default .elementor-icon{fill:#2EA5B4;color:#2EA5B4;border-color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-3e72418 .elementor-icon{font-size:35px;}.elementor-800 .elementor-element.elementor-element-3e72418 .elementor-icon-box-title, .elementor-800 .elementor-element.elementor-element-3e72418 .elementor-icon-box-title a{font-family:"Spinnaker", Sans-serif;font-size:19px;font-weight:600;}.elementor-800 .elementor-element.elementor-element-3e72418 .elementor-icon-box-description{font-family:"Roboto", Sans-serif;font-weight:400;color:#7A7A7A;}.elementor-800 .elementor-element.elementor-element-4a37859{--display:flex;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-7f1d7a2 .elementor-icon-box-wrapper{align-items:center;}.elementor-800 .elementor-element.elementor-element-7f1d7a2{--icon-box-icon-margin:15px;}.elementor-800 .elementor-element.elementor-element-7f1d7a2 .elementor-icon-box-title{margin-block-end:10px;color:#555659;}.elementor-800 .elementor-element.elementor-element-7f1d7a2.elementor-view-stacked .elementor-icon{background-color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-7f1d7a2.elementor-view-framed .elementor-icon, .elementor-800 .elementor-element.elementor-element-7f1d7a2.elementor-view-default .elementor-icon{fill:#2EA5B4;color:#2EA5B4;border-color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-7f1d7a2 .elementor-icon{font-size:35px;}.elementor-800 .elementor-element.elementor-element-7f1d7a2 .elementor-icon-box-title, .elementor-800 .elementor-element.elementor-element-7f1d7a2 .elementor-icon-box-title a{font-family:"Spinnaker", Sans-serif;font-size:19px;font-weight:600;}.elementor-800 .elementor-element.elementor-element-7f1d7a2 .elementor-icon-box-description{font-family:"Roboto", Sans-serif;font-weight:400;color:#7A7A7A;}.elementor-800 .elementor-element.elementor-element-835d99a{--display:flex;--flex-direction:column;--container-widget-width:100%;--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--padding-top:100px;--padding-bottom:50px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-04fd54f{--display:flex;--justify-content:center;--align-items:center;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--margin-top:0px;--margin-bottom:0px;--margin-left:0px;--margin-right:0px;--padding-top:0px;--padding-bottom:0px;--padding-left:40px;--padding-right:40px;}.elementor-800 .elementor-element.elementor-element-04fd54f.e-con{--flex-grow:0;--flex-shrink:0;}.elementor-800 .elementor-element.elementor-element-0878928{text-align:center;}.elementor-800 .elementor-element.elementor-element-0878928 .elementor-heading-title{font-family:"Island Moments", Sans-serif;font-weight:600;color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-2e20544{width:100%;max-width:100%;text-align:center;}.elementor-800 .elementor-element.elementor-element-2e20544 .elementor-heading-title{font-family:"Spinnaker", Sans-serif;font-size:35px;font-weight:600;letter-spacing:1.2px;color:#555659;}.elementor-800 .elementor-element.elementor-element-52a870b > .elementor-widget-container{padding:0px 100px 0px 100px;}.elementor-800 .elementor-element.elementor-element-52a870b{text-align:center;}.elementor-800 .elementor-element.elementor-element-3b3ce62{--display:flex;--flex-direction:row;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:100%;--container-widget-flex-grow:1;--container-widget-align-self:stretch;--flex-wrap-mobile:wrap;--justify-content:space-between;--align-items:center;--gap:0px 0px;--row-gap:0px;--column-gap:0px;--margin-top:3em;--margin-bottom:0em;--margin-left:0em;--margin-right:0em;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-16b4273{--display:flex;--flex-direction:column;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--justify-content:center;--align-items:stretch;--gap:15px 15px;--row-gap:15px;--column-gap:15px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-858dcaf img{width:80%;}.elementor-800 .elementor-element.elementor-element-70243da{width:100%;max-width:100%;text-align:center;}.elementor-800 .elementor-element.elementor-element-70243da .elementor-heading-title{font-family:"Spinnaker", Sans-serif;font-size:20px;font-weight:600;letter-spacing:1.2px;color:#555659;}.elementor-800 .elementor-element.elementor-element-131a381{text-align:center;}.elementor-800 .elementor-element.elementor-element-f8a6295{--display:flex;--flex-direction:column;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--justify-content:center;--align-items:stretch;--gap:15px 15px;--row-gap:15px;--column-gap:15px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-674b27f > .elementor-widget-container{margin:-10em 0em 0em 0em;}.elementor-800 .elementor-element.elementor-element-674b27f img{width:70%;}.elementor-800 .elementor-element.elementor-element-6e6e85d{--display:flex;--flex-direction:column;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--justify-content:center;--align-items:stretch;--gap:15px 15px;--row-gap:15px;--column-gap:15px;--margin-top:0em;--margin-bottom:-9em;--margin-left:0em;--margin-right:0em;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-b1dfbf0 img{width:80%;}.elementor-800 .elementor-element.elementor-element-0af4dfa{width:100%;max-width:100%;text-align:center;}.elementor-800 .elementor-element.elementor-element-0af4dfa .elementor-heading-title{font-family:"Spinnaker", Sans-serif;font-size:20px;font-weight:600;letter-spacing:1.2px;color:#555659;}.elementor-800 .elementor-element.elementor-element-81aab67{text-align:center;}.elementor-800 .elementor-element.elementor-element-d6c559e{--display:flex;--flex-direction:column;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--justify-content:center;--align-items:stretch;--gap:15px 15px;--row-gap:15px;--column-gap:15px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-4615a70 img{width:70%;}.elementor-800 .elementor-element.elementor-element-3f1b304{--display:flex;--flex-direction:column;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--justify-content:center;--align-items:stretch;--gap:15px 15px;--row-gap:15px;--column-gap:15px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-e11ae59 img{width:80%;}.elementor-800 .elementor-element.elementor-element-cfe8562{width:100%;max-width:100%;text-align:center;}.elementor-800 .elementor-element.elementor-element-cfe8562 .elementor-heading-title{font-family:"Spinnaker", Sans-serif;font-size:20px;font-weight:600;letter-spacing:1.2px;color:#555659;}.elementor-800 .elementor-element.elementor-element-06c42a5{text-align:center;}.elementor-800 .elementor-element.elementor-element-40f7cb7{--display:flex;--flex-direction:column;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--justify-content:center;--align-items:stretch;--gap:15px 15px;--row-gap:15px;--column-gap:15px;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-91c87d1 > .elementor-widget-container{margin:-10em 0em 0em 0em;}.elementor-800 .elementor-element.elementor-element-91c87d1 img{width:70%;}.elementor-800 .elementor-element.elementor-element-4513d29{--display:flex;--flex-direction:column;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--justify-content:center;--align-items:stretch;--gap:15px 15px;--row-gap:15px;--column-gap:15px;--margin-top:0em;--margin-bottom:-9em;--margin-left:0em;--margin-right:0em;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-4e3b1d6 img{width:80%;}.elementor-800 .elementor-element.elementor-element-637eded{width:100%;max-width:100%;text-align:center;}.elementor-800 .elementor-element.elementor-element-637eded .elementor-heading-title{font-family:"Spinnaker", Sans-serif;font-size:20px;font-weight:600;letter-spacing:1.2px;color:#555659;}.elementor-800 .elementor-element.elementor-element-de6a15e{text-align:center;}.elementor-800 .elementor-element.elementor-element-bcdb723{--display:flex;--flex-direction:column;--container-widget-width:100%;--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;}.elementor-800 .elementor-element.elementor-element-8e8e5b4{--display:flex;--flex-direction:column;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--justify-content:center;--align-items:center;--padding-top:80px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-297c3f4{text-align:center;}.elementor-800 .elementor-element.elementor-element-297c3f4 .elementor-heading-title{font-family:"Island Moments", Sans-serif;font-weight:600;color:#2EA5B4;}.elementor-800 .elementor-element.elementor-element-e260788{width:100%;max-width:100%;text-align:center;}.elementor-800 .elementor-element.elementor-element-e260788 .elementor-heading-title{font-family:"Spinnaker", Sans-serif;font-size:35px;font-weight:600;letter-spacing:1.2px;color:#555659;}.elementor-800 .elementor-element.elementor-element-d2aac71 > .elementor-widget-container{padding:0px 100px 0px 100px;}.elementor-800 .elementor-element.elementor-element-d2aac71{text-align:center;}.elementor-800 .elementor-element.elementor-element-6f44a0e{--display:flex;--min-height:500px;--flex-direction:column;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--flex-wrap-mobile:wrap;--justify-content:center;--align-items:center;--overlay-opacity:0.5;--padding-top:0px;--padding-bottom:0px;--padding-left:0px;--padding-right:0px;}.elementor-800 .elementor-element.elementor-element-6f44a0e:not(.elementor-motion-effects-element-type-background), .elementor-800 .elementor-element.elementor-element-6f44a0e > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-image:url("./wp-content/uploads/2025/10/girl-with-ethnic-clothes-on-top-hill.jpg");background-position:center center;background-size:cover;}.elementor-800 .elementor-element.elementor-element-6f44a0e::before, .elementor-800 .elementor-element.elementor-element-6f44a0e > .elementor-background-video-container::before, .elementor-800 .elementor-element.elementor-element-6f44a0e > .e-con-inner > .elementor-background-video-container::before, .elementor-800 .elementor-element.elementor-element-6f44a0e > .elementor-background-slideshow::before, .elementor-800 .elementor-element.elementor-element-6f44a0e > .e-con-inner > .elementor-background-slideshow::before, .elementor-800 .elementor-element.elementor-element-6f44a0e > .elementor-motion-effects-container > .elementor-motion-effects-layer::before{background-color:#1E1B1E;--background-overlay:'';}.elementor-800 .elementor-element.elementor-element-359fd66{width:var( --container-widget-width, 396.766% );max-width:396.766%;--container-widget-width:396.766%;--container-widget-flex-grow:0;text-align:center;}.elementor-800 .elementor-element.elementor-element-359fd66 > .elementor-widget-container{margin:-15px 0px 0px 0px;}.elementor-800 .elementor-element.elementor-element-359fd66.elementor-element{--flex-grow:0;--flex-shrink:0;}.elementor-800 .elementor-element.elementor-element-359fd66 .elementor-heading-title{font-family:"Playfair Display", Sans-serif;font-size:45px;font-weight:500;letter-spacing:0.4px;color:#FFFFFF;}.elementor-800 .elementor-element.elementor-element-0cec0c4 > .elementor-widget-container{padding:5px 0px 10px 0px;}.elementor-800 .elementor-element.elementor-element-0cec0c4{text-align:center;font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:400;line-height:22px;letter-spacing:0.4px;color:#E9E9E9;}.elementor-800 .elementor-element.elementor-element-df7f96b .elementor-button{background-color:#2EA5B4;border-radius:15px 15px 15px 15px;}.elementor-800 .elementor-element.elementor-element-df7f96b .elementor-button-content-wrapper{flex-direction:row;}.elementor-800 .elementor-element.elementor-element-df7f96b .elementor-button .elementor-button-content-wrapper{gap:13px;}body.elementor-page-800:not(.elementor-motion-effects-element-type-background), body.elementor-page-800 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#FFFFFF;}@media(min-width:768px){.elementor-800 .elementor-element.elementor-element-508c306{width:42.63%;}.elementor-800 .elementor-element.elementor-element-f17f568{width:57.326%;}.elementor-800 .elementor-element.elementor-element-0cca238{--content-width:1300px;}.elementor-800 .elementor-element.elementor-element-f28ef88{--width:50%;}.elementor-800 .elementor-element.elementor-element-f3db361{--width:40%;}.elementor-800 .elementor-element.elementor-element-d1e3852{--content-width:1200px;}.elementor-800 .elementor-element.elementor-element-a1e7388{--width:26.866%;}.elementor-800 .elementor-element.elementor-element-b788551{--width:49.239%;}.elementor-800 .elementor-element.elementor-element-a6eb1d8{--width:60%;}.elementor-800 .elementor-element.elementor-element-f3597f9{--width:100.277%;}.elementor-800 .elementor-element.elementor-element-e517d73{--width:60%;}.elementor-800 .elementor-element.elementor-element-83103a4{--width:100.277%;}.elementor-800 .elementor-element.elementor-element-4b42ddd{--width:60%;}.elementor-800 .elementor-element.elementor-element-5da70e3{--width:100.277%;}.elementor-800 .elementor-element.elementor-element-431c2e0{--width:50%;}.elementor-800 .elementor-element.elementor-element-1e3b1f8{--width:50%;}.elementor-800 .elementor-element.elementor-element-b330488{--width:100%;}.elementor-800 .elementor-element.elementor-element-835d99a{--content-width:1200px;}.elementor-800 .elementor-element.elementor-element-04fd54f{--width:100%;}.elementor-800 .elementor-element.elementor-element-8e8e5b4{--content-width:1200px;}}@media(max-width:1024px) and (min-width:768px){.elementor-800 .elementor-element.elementor-element-508c306{width:70%;}.elementor-800 .elementor-element.elementor-element-f17f568{width:30%;}}@media(min-width:1025px){.elementor-800 .elementor-element.elementor-element-6f44a0e:not(.elementor-motion-effects-element-type-background), .elementor-800 .elementor-element.elementor-element-6f44a0e > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-attachment:fixed;}}@media(max-width:1024px){.elementor-800 .elementor-element.elementor-element-9201183:not(.elementor-motion-effects-element-type-background), .elementor-800 .elementor-element.elementor-element-9201183 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-position:center center;background-repeat:no-repeat;background-size:cover;}.elementor-800 .elementor-element.elementor-element-9201183{padding:60px 60px 60px 60px;}.elementor-800 .elementor-element.elementor-element-508c306 > .elementor-element-populated{padding:0px 0px 0px 0px;}.elementor-800 .elementor-element.elementor-element-c137ac9 .elementor-heading-title{font-size:60px;line-height:75px;}.elementor-800 .elementor-element.elementor-element-44fb30b{--grid-auto-flow:row;}.elementor-800 .elementor-element.elementor-element-dfebc5c{--grid-auto-flow:row;}.elementor-800 .elementor-element.elementor-element-359fd66 .elementor-heading-title{font-size:34px;}}@media(max-width:767px){.elementor-800 .elementor-element.elementor-element-9201183:not(.elementor-motion-effects-element-type-background), .elementor-800 .elementor-element.elementor-element-9201183 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-position:bottom center;background-size:100% auto;}.elementor-800 .elementor-element.elementor-element-9201183{padding:0px 0px 0px 0px;}.elementor-800 .elementor-element.elementor-element-c137ac9 > .elementor-widget-container{margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;}.elementor-800 .elementor-element.elementor-element-c137ac9{text-align:center;}.elementor-800 .elementor-element.elementor-element-c137ac9 .elementor-heading-title{font-size:18px;line-height:60px;}.elementor-800 .elementor-element.elementor-element-7a4d140 > .elementor-widget-container{padding:0px 20px 0px 20px;}.elementor-800 .elementor-element.elementor-element-7a4d140{text-align:center;font-size:15px;letter-spacing:0.3px;}.elementor-800 .elementor-element.elementor-element-14ca025{--content-width:500px;--padding-top:0px;--padding-bottom:20px;--padding-left:10px;--padding-right:020px;}.elementor-800 .elementor-element.elementor-element-9d207ad{text-align:center;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button-content{-webkit-justify-content:center;justify-content:center;}.elementor-800 .elementor-element.elementor-element-9d207ad .wpr-button-text{-webkit-justify-content:center;justify-content:center;}.elementor-800 .elementor-element.elementor-element-9d0568a{width:var( --container-widget-width, 164.479px );max-width:164.479px;--container-widget-width:164.479px;--container-widget-flex-grow:0;text-align:center;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button-content{-webkit-justify-content:center;justify-content:center;}.elementor-800 .elementor-element.elementor-element-9d0568a .wpr-button-text{-webkit-justify-content:center;justify-content:center;}.elementor-800 .elementor-element.elementor-element-f28ef88{--margin-top:20px;--margin-bottom:20px;--margin-left:20px;--margin-right:20px;}.elementor-800 .elementor-element.elementor-element-f3db361{--margin-top:0em;--margin-bottom:0em;--margin-left:2em;--margin-right:2em;--padding-top:20px;--padding-bottom:20px;--padding-left:20px;--padding-right:20px;}.elementor-800 .elementor-element.elementor-element-a1e7388{--margin-top:20px;--margin-bottom:20px;--margin-left:10px;--margin-right:20px;}.elementor-800 .elementor-element.elementor-element-bd54935 > .elementor-widget-container{margin:0px 20px 0px 20px;}.elementor-800 .elementor-element.elementor-element-a4644ea{--justify-content:center;--align-items:center;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );}.elementor-800 .elementor-element.elementor-element-795dfbc{text-align:center;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button-content{-webkit-justify-content:center;justify-content:center;}.elementor-800 .elementor-element.elementor-element-795dfbc .wpr-button-text{-webkit-justify-content:center;justify-content:center;}.elementor-800 .elementor-element.elementor-element-44fb30b{--e-con-grid-template-columns:repeat(1, 1fr);--grid-auto-flow:row;}.elementor-800 .elementor-element.elementor-element-a6eb1d8{--margin-top:-55px;--margin-bottom:0px;--margin-left:0px;--margin-right:0px;--padding-top:10px;--padding-bottom:10px;--padding-left:0px;--padding-right:10px;}.elementor-800 .elementor-element.elementor-element-e85d882 > .elementor-widget-container{margin:0px 0px 0px 10px;}.elementor-800 .elementor-element.elementor-element-9ae86b6{--margin-top:-35em;--margin-bottom:0em;--margin-left:0em;--margin-right:0em;}.elementor-800 .elementor-element.elementor-element-e517d73{--margin-top:-55px;--margin-bottom:0px;--margin-left:0px;--margin-right:0px;}.elementor-800 .elementor-element.elementor-element-223d666{--margin-top:-35em;--margin-bottom:0em;--margin-left:0em;--margin-right:0em;}.elementor-800 .elementor-element.elementor-element-4b42ddd{--margin-top:-55px;--margin-bottom:0px;--margin-left:0px;--margin-right:0px;}.elementor-800 .elementor-element.elementor-element-fdc2fbc{--margin-top:-300px;--margin-bottom:0px;--margin-left:0px;--margin-right:0px;}.elementor-800 .elementor-element.elementor-element-b330488{--margin-top:20px;--margin-bottom:20px;--margin-left:20px;--margin-right:20px;}.elementor-800 .elementor-element.elementor-element-dfebc5c{--e-con-grid-template-columns:repeat(1, 1fr);--grid-auto-flow:row;}.elementor-800 .elementor-element.elementor-element-52a870b > .elementor-widget-container{padding:0px 20px 0px 20px;}.elementor-800 .elementor-element.elementor-element-f8a6295{--padding-top:10em;--padding-bottom:0em;--padding-left:0em;--padding-right:0em;}.elementor-800 .elementor-element.elementor-element-4615a70 > .elementor-widget-container{padding:10em 0em 0em 0em;}.elementor-800 .elementor-element.elementor-element-3f1b304{--padding-top:10em;--padding-bottom:0em;--padding-left:0em;--padding-right:0em;}.elementor-800 .elementor-element.elementor-element-91c87d1 > .elementor-widget-container{padding:20em 0em 0em 0em;}.elementor-800 .elementor-element.elementor-element-4e3b1d6 > .elementor-widget-container{padding:5em 0em 0em 0em;}.elementor-800 .elementor-element.elementor-element-e2acb2b > .elementor-widget-container{padding:110px 0px 0px 0px;}.elementor-800 .elementor-element.elementor-element-8e8e5b4{--margin-top:-100px;--margin-bottom:0px;--margin-left:0px;--margin-right:0px;}.elementor-800 .elementor-element.elementor-element-359fd66 .elementor-heading-title{font-size:30px;line-height:45px;}.elementor-800 .elementor-element.elementor-element-0cec0c4 > .elementor-widget-container{padding:0px 0px 0px 0px;}}
.elementor-274 .elementor-element.elementor-element-32bfc0d4:not(.elementor-motion-effects-element-type-background), .elementor-274 .elementor-element.elementor-element-32bfc0d4 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#FFFFFF00;}.elementor-274 .elementor-element.elementor-element-32bfc0d4{transition:background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;margin-top:0px;margin-bottom:0px;padding:15px 0px 0px 0px;}.elementor-274 .elementor-element.elementor-element-32bfc0d4 > .elementor-background-overlay{transition:background 0.3s, border-radius 0.3s, opacity 0.3s;}.elementor-bc-flex-widget .elementor-274 .elementor-element.elementor-element-54a40664.elementor-column .elementor-widget-wrap{align-items:flex-end;}.elementor-274 .elementor-element.elementor-element-54a40664.elementor-column.elementor-element[data-element_type="column"] > .elementor-widget-wrap.elementor-element-populated{align-content:flex-end;align-items:flex-end;}.elementor-274 .elementor-element.elementor-element-b4c77ba > .elementor-widget-container{margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;}.elementor-274 .elementor-element.elementor-element-b4c77ba{text-align:left;}.elementor-274 .elementor-element.elementor-element-b4c77ba .wpr-logo{padding:0px 0px 0px 0px;border-style:none;}.elementor-274 .elementor-element.elementor-element-b4c77ba .wpr-logo-image{max-width:500px;}.elementor-274 .elementor-element.elementor-element-b4c77ba.wpr-logo-position-left .wpr-logo-image{margin-right:0px;}.elementor-274 .elementor-element.elementor-element-b4c77ba.wpr-logo-position-right .wpr-logo-image{margin-left:0px;}.elementor-274 .elementor-element.elementor-element-b4c77ba.wpr-logo-position-center .wpr-logo-image{margin-bottom:0px;}.elementor-274 .elementor-element.elementor-element-b4c77ba .wpr-logo-image img{-webkit-transition-duration:0.7s;transition-duration:0.7s;}.elementor-274 .elementor-element.elementor-element-b4c77ba .wpr-logo-title{color:#605BE5;margin:0 0 0px;}.elementor-274 .elementor-element.elementor-element-b4c77ba .wpr-logo-description{color:#888888;}.elementor-bc-flex-widget .elementor-274 .elementor-element.elementor-element-306600e5.elementor-column .elementor-widget-wrap{align-items:center;}.elementor-274 .elementor-element.elementor-element-306600e5.elementor-column.elementor-element[data-element_type="column"] > .elementor-widget-wrap.elementor-element-populated{align-content:center;align-items:center;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-menu-item.wpr-pointer-item{transition-duration:0.2s;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-menu-item.wpr-pointer-item:before{transition-duration:0.2s;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-menu-item.wpr-pointer-item:after{transition-duration:0.2s;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle-wrap{text-align:center;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu .wpr-menu-item,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu > .menu-item-has-children > .wpr-sub-icon{color:#2F2F2F;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu .wpr-menu-item:hover,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu > .menu-item-has-children:hover > .wpr-sub-icon,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu .wpr-menu-item.wpr-active-menu-item,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu > .menu-item-has-children.current_page_item > .wpr-sub-icon{color:#2EA5B4;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-line-fx .wpr-menu-item:before,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-line-fx .wpr-menu-item:after{background-color:#158E9D;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-border-fx .wpr-menu-item:before{border-color:#158E9D;border-width:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-background-fx .wpr-menu-item:before{background-color:#158E9D;}.elementor-274 .elementor-element.elementor-element-42c4e46d .menu-item-has-children .wpr-sub-icon{font-size:15px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-background:not(.wpr-sub-icon-none) .wpr-nav-menu-horizontal .menu-item-has-children .wpr-pointer-item{padding-right:calc(15px + 7px);}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-border:not(.wpr-sub-icon-none) .wpr-nav-menu-horizontal .menu-item-has-children .wpr-pointer-item{padding-right:calc(15px + 7px);}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu .wpr-menu-item,.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu a,.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle-text{font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:400;text-transform:uppercase;letter-spacing:0.4px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-underline .wpr-menu-item:after,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-overline .wpr-menu-item:before,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-double-line .wpr-menu-item:before,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-double-line .wpr-menu-item:after{height:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-underline>nav>ul>li>.wpr-menu-item:after,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-overline>nav>ul>li>.wpr-menu-item:before,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-double-line>nav>ul>li>.wpr-menu-item:before,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-double-line>nav>ul>li>.wpr-menu-item:after{height:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-border-fx>nav>ul>li>.wpr-menu-item:before{border-width:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-underline>.elementor-widget-container>nav>ul>li>.wpr-menu-item:after,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-overline>.elementor-widget-container>nav>ul>li>.wpr-menu-item:before,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-double-line>.elementor-widget-container>nav>ul>li>.wpr-menu-item:before,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-double-line>.elementor-widget-container>nav>ul>li>.wpr-menu-item:after{height:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-border-fx>.elementor-widget-container>nav>ul>li>.wpr-menu-item:before{border-width:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d:not(.wpr-pointer-border-fx) .wpr-menu-item.wpr-pointer-item:before{transform:translateY(-0px);}.elementor-274 .elementor-element.elementor-element-42c4e46d:not(.wpr-pointer-border-fx) .wpr-menu-item.wpr-pointer-item:after{transform:translateY(0px);}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu .wpr-menu-item{padding-left:7px;padding-right:7px;padding-top:15px;padding-bottom:15px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-background:not(.wpr-sub-icon-none) .wpr-nav-menu-vertical .menu-item-has-children .wpr-sub-icon{text-indent:-7px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-border:not(.wpr-sub-icon-none) .wpr-nav-menu-vertical .menu-item-has-children .wpr-sub-icon{text-indent:-7px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu > .menu-item{margin-left:12px;margin-right:12px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu-vertical .wpr-nav-menu > li > .wpr-sub-menu{margin-left:12px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-main-menu-align-left .wpr-nav-menu-vertical .wpr-nav-menu > li > .wpr-sub-icon{right:12px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-main-menu-align-right .wpr-nav-menu-vertical .wpr-nav-menu > li > .wpr-sub-icon{left:12px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu .wpr-sub-menu-item,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu > .menu-item-has-children .wpr-sub-icon{color:#333333;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu .wpr-sub-menu-item{background-color:#ffffff;padding-left:15px;padding-right:15px;padding-top:13px;padding-bottom:13px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu .wpr-sub-menu-item:hover,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu > .menu-item-has-children .wpr-sub-menu-item:hover .wpr-sub-icon,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu .wpr-sub-menu-item.wpr-active-menu-item,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu > .menu-item-has-children.current_page_item .wpr-sub-icon{color:#ffffff;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu .wpr-sub-menu-item:hover,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu .wpr-sub-menu-item.wpr-active-menu-item{background-color:#EA6FFB;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu .wpr-sub-icon{right:15px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-main-menu-align-right .wpr-nav-menu-vertical .wpr-sub-menu .wpr-sub-icon{left:15px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu-horizontal .wpr-nav-menu > li > .wpr-sub-menu{margin-top:0px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-sub-divider-yes .wpr-sub-menu li:not(:last-child){border-bottom-color:#e8e8e8;border-bottom-width:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu{border-style:solid;border-width:1px 1px 1px 1px;border-color:#E8E8E8;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu a,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu .menu-item-has-children > a:after{color:#333333;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu li{background-color:#ffffff;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu li a:hover,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu .menu-item-has-children > a:hover:after,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu li a.wpr-active-menu-item,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu .menu-item-has-children.current_page_item > a:hover:after{color:#ffffff;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu a:hover,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu a.wpr-active-menu-item{background-color:#EA6FFB;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu a{padding-left:10px;padding-right:10px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu .menu-item-has-children > a:after{margin-left:10px;margin-right:10px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu .wpr-mobile-menu-item{padding-top:10px;padding-bottom:10px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-mobile-divider-yes .wpr-mobile-nav-menu a{border-bottom-color:#e8e8e8;border-bottom-width:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu .wpr-mobile-sub-menu-item{font-size:12px;padding-top:5px;padding-bottom:5px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu{margin-top:10px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle{border-color:#8783FFFA;width:38px;border-width:0px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle-text{color:#8783FFFA;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle-line{background-color:#8783FFFA;height:2px;margin-bottom:5px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle:hover{border-color:#EA6FFB;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle:hover .wpr-mobile-toggle-text{color:#EA6FFB;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle:hover .wpr-mobile-toggle-line{background-color:#EA6FFB;}.elementor-bc-flex-widget .elementor-274 .elementor-element.elementor-element-2e1cdbb3.elementor-column .elementor-widget-wrap{align-items:center;}.elementor-274 .elementor-element.elementor-element-2e1cdbb3.elementor-column.elementor-element[data-element_type="column"] > .elementor-widget-wrap.elementor-element-populated{align-content:center;align-items:center;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button{background-color:#FFFFFF00;-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;border-color:#2EA5B4;border-style:solid;border-width:1px 1px 1px 1px;border-radius:2px 2px 2px 2px;}	.elementor-274 .elementor-element.elementor-element-1a8c2969 [class*="elementor-animation"]:hover,
								.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button::before,
								.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button::after{background-color:#4A45D200;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button::before{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button::after{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;color:#2EA5B4;padding:8px 10px 8px 10px;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button .wpr-button-icon{-webkit-transition-duration:0.4s;transition-duration:0.4s;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button .wpr-button-icon svg{-webkit-transition-duration:0.4s;transition-duration:0.4s;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button .wpr-button-text{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button .wpr-button-content{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-wrap{max-width:500px;}.elementor-274 .elementor-element.elementor-element-1a8c2969{text-align:right;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-content{-webkit-justify-content:center;justify-content:center;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-text{-webkit-justify-content:center;justify-content:center;color:#2EA5B4;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-icon{font-size:18px;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-icon svg{width:18px;height:18px;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-position-left .wpr-button-icon{margin-right:12px;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-position-right .wpr-button-icon{margin-left:12px;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-style-inline .wpr-button-icon{color:#2EA5B4;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-style-inline .wpr-button-icon svg{fill:#2EA5B4;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-text,.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button::after{font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:500;letter-spacing:0.4px;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button:hover .wpr-button-text{color:#2EA5B4;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-style-inline .wpr-button:hover .wpr-button-icon{color:#2EA5B4;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-style-inline .wpr-button:hover .wpr-button-icon svg{fill:#2EA5B4;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button:hover{border-color:#158E9D;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-style-inline .wpr-button{padding:8px 10px 8px 10px;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-style-block .wpr-button-text{padding:8px 10px 8px 10px;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-style-inline-block .wpr-button-content{padding:8px 10px 8px 10px;}@media(max-width:1024px){.elementor-274 .elementor-element.elementor-element-54a40664 > .elementor-element-populated{padding:0px 0px 0px 0px;}.elementor-274 .elementor-element.elementor-element-b4c77ba > .elementor-widget-container{padding:0px 0px 0px 60px;}.elementor-274 .elementor-element.elementor-element-b4c77ba{text-align:left;}.elementor-274 .elementor-element.elementor-element-42c4e46d > .elementor-widget-container{padding:0px 40px 0px 0px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle-wrap{text-align:right;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu > .menu-item{margin-left:4px;margin-right:4px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu-vertical .wpr-nav-menu > li > .wpr-sub-menu{margin-left:4px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-main-menu-align-left .wpr-nav-menu-vertical .wpr-nav-menu > li > .wpr-sub-icon{right:4px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-main-menu-align-right .wpr-nav-menu-vertical .wpr-nav-menu > li > .wpr-sub-icon{left:4px;}}@media(min-width:768px){.elementor-274 .elementor-element.elementor-element-54a40664{width:27%;}.elementor-274 .elementor-element.elementor-element-306600e5{width:55.854%;}.elementor-274 .elementor-element.elementor-element-2e1cdbb3{width:16.452%;}}@media(max-width:1024px) and (min-width:768px){.elementor-274 .elementor-element.elementor-element-54a40664{width:30%;}.elementor-274 .elementor-element.elementor-element-306600e5{width:70%;}.elementor-274 .elementor-element.elementor-element-2e1cdbb3{width:20%;}}@media(max-width:767px){.elementor-274 .elementor-element.elementor-element-54a40664{width:50%;}.elementor-274 .elementor-element.elementor-element-b4c77ba > .elementor-widget-container{padding:0px 0px 0px 30px;}.elementor-274 .elementor-element.elementor-element-306600e5{width:50%;}.elementor-274 .elementor-element.elementor-element-42c4e46d > .elementor-widget-container{padding:0px 20px 0px 0px;}.elementor-274 .elementor-element.elementor-element-1a8c2969{text-align:center;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-content{-webkit-justify-content:center;justify-content:center;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-text{-webkit-justify-content:center;justify-content:center;}}
.elementor-287 .elementor-element.elementor-element-f3041db{margin-top:100px;margin-bottom:0px;}.elementor-widget-heading .elementor-heading-title{font-family:var( --e-global-typography-primary-font-family ), Sans-serif;font-weight:var( --e-global-typography-primary-font-weight );color:var( --e-global-color-primary );}.elementor-287 .elementor-element.elementor-element-5aa292ec{text-align:left;}.elementor-287 .elementor-element.elementor-element-5aa292ec .elementor-heading-title{font-family:"Playfair Display", Sans-serif;font-size:18px;font-weight:600;letter-spacing:1px;color:#2F2F2F;}.elementor-widget-text-editor{font-family:var( --e-global-typography-text-font-family ), Sans-serif;font-weight:var( --e-global-typography-text-font-weight );color:var( --e-global-color-text );}.elementor-widget-text-editor.elementor-drop-cap-view-stacked .elementor-drop-cap{background-color:var( --e-global-color-primary );}.elementor-widget-text-editor.elementor-drop-cap-view-framed .elementor-drop-cap, .elementor-widget-text-editor.elementor-drop-cap-view-default .elementor-drop-cap{color:var( --e-global-color-primary );border-color:var( --e-global-color-primary );}.elementor-287 .elementor-element.elementor-element-2c811db3{width:auto;max-width:auto;font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:400;letter-spacing:0.4px;color:#6D6D6D;}.elementor-287 .elementor-element.elementor-element-2c811db3 a{color:#6D6D6D;}.elementor-287 .elementor-element.elementor-element-2c811db3 a:hover, .elementor-287 .elementor-element.elementor-element-2c811db3 a:focus{color:#2EA5B4;}.elementor-287 .elementor-element.elementor-element-29309008{text-align:left;}.elementor-287 .elementor-element.elementor-element-29309008 .elementor-heading-title{font-family:"Playfair Display", Sans-serif;font-size:18px;font-weight:600;letter-spacing:1px;color:#2F2F2F;}.elementor-287 .elementor-element.elementor-element-47d804af{text-align:left;font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:400;line-height:30px;letter-spacing:0.4px;}.elementor-287 .elementor-element.elementor-element-5d618721{text-align:left;}.elementor-287 .elementor-element.elementor-element-5d618721 .elementor-heading-title{font-family:"Playfair Display", Sans-serif;font-size:18px;font-weight:600;letter-spacing:1px;color:#2F2F2F;}.elementor-287 .elementor-element.elementor-element-32b924e{text-align:left;font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:400;line-height:22px;letter-spacing:0.4px;}.elementor-287 .elementor-element.elementor-element-58c76c86{--grid-template-columns:repeat(0, auto);--icon-size:15px;--grid-column-gap:20px;--grid-row-gap:0px;}.elementor-287 .elementor-element.elementor-element-58c76c86 .elementor-widget-container{text-align:left;}.elementor-287 .elementor-element.elementor-element-58c76c86 > .elementor-widget-container{margin:-13px 0px 0px 0px;}.elementor-287 .elementor-element.elementor-element-58c76c86 .elementor-social-icon{background-color:#02010100;--icon-padding:0em;}.elementor-287 .elementor-element.elementor-element-58c76c86 .elementor-social-icon i{color:#2F2F2F;}.elementor-287 .elementor-element.elementor-element-58c76c86 .elementor-social-icon svg{fill:#2F2F2F;}.elementor-287 .elementor-element.elementor-element-58c76c86 .elementor-social-icon:hover{background-color:#8783FF00;}.elementor-287 .elementor-element.elementor-element-58c76c86 .elementor-social-icon:hover i{color:#EA6FFB;}.elementor-287 .elementor-element.elementor-element-58c76c86 .elementor-social-icon:hover svg{fill:#EA6FFB;}.elementor-widget-divider{--divider-color:var( --e-global-color-secondary );}.elementor-widget-divider .elementor-divider__text{color:var( --e-global-color-secondary );font-family:var( --e-global-typography-secondary-font-family ), Sans-serif;font-weight:var( --e-global-typography-secondary-font-weight );}.elementor-widget-divider.elementor-view-stacked .elementor-icon{background-color:var( --e-global-color-secondary );}.elementor-widget-divider.elementor-view-framed .elementor-icon, .elementor-widget-divider.elementor-view-default .elementor-icon{color:var( --e-global-color-secondary );border-color:var( --e-global-color-secondary );}.elementor-widget-divider.elementor-view-framed .elementor-icon, .elementor-widget-divider.elementor-view-default .elementor-icon svg{fill:var( --e-global-color-secondary );}.elementor-287 .elementor-element.elementor-element-b819f0c{--divider-border-style:solid;--divider-color:#E8E8E8;--divider-border-width:1px;}.elementor-287 .elementor-element.elementor-element-b819f0c .elementor-divider-separator{width:100%;}.elementor-287 .elementor-element.elementor-element-b819f0c .elementor-divider{padding-block-start:15px;padding-block-end:15px;}.elementor-287 .elementor-element.elementor-element-050573a > .elementor-widget-container{margin:-5px 0px 0px 0px;padding:0px 0px 20px 0px;}.elementor-287 .elementor-element.elementor-element-050573a{text-align:center;font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:400;letter-spacing:0.4px;}@media(max-width:1024px){.elementor-287 .elementor-element.elementor-element-f3041db{padding:0px 40px 0px 40px;}}
.elementor-kit-10{--e-global-color-primary:#6EC1E4;--e-global-color-secondary:#54595F;--e-global-color-text:#7A7A7A;--e-global-color-accent:#61CE70;--e-global-typography-primary-font-family:"Roboto";--e-global-typography-primary-font-weight:600;--e-global-typography-secondary-font-family:"Roboto Slab";--e-global-typography-secondary-font-weight:400;--e-global-typography-text-font-family:"Roboto";--e-global-typography-text-font-weight:400;--e-global-typography-accent-font-family:"Roboto";--e-global-typography-accent-font-weight:500;}.elementor-section.elementor-section-boxed > .elementor-container{max-width:1140px;}.e-con{--container-max-width:1140px;}.elementor-widget:not(:last-child){margin-block-end:20px;}.elementor-element{--widgets-spacing:20px 20px;--widgets-spacing-row:20px;--widgets-spacing-column:20px;}{}h1.entry-title{display:var(--page-title-display);}@media(max-width:1024px){.elementor-section.elementor-section-boxed > .elementor-container{max-width:1024px;}.e-con{--container-max-width:1024px;}}@media(max-width:767px){.elementor-section.elementor-section-boxed > .elementor-container{max-width:767px;}.e-con{--container-max-width:767px;}}
</style>
<link rel="stylesheet" id="e-animation-fadeInLeft-css" href="./wp-content/plugins/elementor/assets/lib/animations/styles/fadeInLeft.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="widget-heading-css" href="./wp-content/plugins/elementor/assets/css/widget-heading.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="widget-icon-box-css" href="./wp-content/plugins/elementor/assets/css/widget-icon-box.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="e-animation-fadeIn-css" href="./wp-content/plugins/elementor/assets/lib/animations/styles/fadeIn.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="e-animation-fadeInUp-css" href="./wp-content/plugins/elementor/assets/lib/animations/styles/fadeInUp.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="e-animation-slideInUp-css" href="./wp-content/plugins/elementor/assets/lib/animations/styles/slideInUp.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="e-animation-bob-css" href="./wp-content/plugins/elementor/assets/lib/animations/styles/e-animation-bob.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="widget-image-css" href="./wp-content/plugins/elementor/assets/css/widget-image.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="widget-icon-list-css" href="./wp-content/plugins/elementor/assets/css/widget-icon-list.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="widget-divider-css" href="./wp-content/plugins/elementor/assets/css/widget-divider.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="e-animation-bounceInUp-css" href="./wp-content/plugins/elementor/assets/lib/animations/styles/bounceInUp.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="e-animation-fadeInRight-css" href="./wp-content/plugins/elementor/assets/lib/animations/styles/fadeInRight.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="e-animation-bounce-css" href="./wp-content/plugins/elementor/assets/lib/animations/styles/bounce.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="e-animation-zoomInUp-css" href="./wp-content/plugins/elementor/assets/lib/animations/styles/zoomInUp.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="e-animation-slideInLeft-css" href="./wp-content/plugins/elementor/assets/lib/animations/styles/slideInLeft.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="e-animation-bounceIn-css" href="./wp-content/plugins/elementor/assets/lib/animations/styles/bounceIn.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="wpr-button-animations-css-css" href="./wp-content/plugins/royal-elementor-addons/assets/css/lib/animations/button-animations.min.css?ver=1.7.1040" type="text/css" media="all">
<link rel="stylesheet" id="wpr-text-animations-css-css" href="./wp-content/plugins/royal-elementor-addons/assets/css/lib/animations/text-animations.min.css?ver=1.7.1040" type="text/css" media="all">
<link rel="stylesheet" id="wpr-addons-css-css" href="./wp-content/plugins/royal-elementor-addons/assets/css/frontend.min.css?ver=1.7.1040" type="text/css" media="all">
<link rel="stylesheet" id="font-awesome-5-all-css" href="./wp-content/plugins/elementor/assets/lib/font-awesome/css/all.min.css?ver=1.7.1040" type="text/css" media="all">
<link rel="stylesheet" id="elementor-gf-poppins-css" href="https://fonts.googleapis.com/css?family=Poppins:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&amp;display=swap" type="text/css" media="all">
<link rel="stylesheet" id="elementor-gf-opensans-css" href="https://fonts.googleapis.com/css?family=Open+Sans:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&amp;display=swap" type="text/css" media="all">
<link rel="stylesheet" id="elementor-gf-islandmoments-css" href="https://fonts.googleapis.com/css?family=Island+Moments:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&amp;display=swap" type="text/css" media="all">
<link rel="stylesheet" id="elementor-gf-spinnaker-css" href="https://fonts.googleapis.com/css?family=Spinnaker:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&amp;display=swap" type="text/css" media="all">
<link rel="stylesheet" id="elementor-gf-roboto-css" href="https://fonts.googleapis.com/css?family=Roboto:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&amp;display=swap" type="text/css" media="all">
<link rel="stylesheet" id="elementor-gf-playfairdisplay-css" href="https://fonts.googleapis.com/css?family=Playfair+Display:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&amp;display=swap" type="text/css" media="all">
<link rel="stylesheet" id="elementor-gf-robotoslab-css" href="https://fonts.googleapis.com/css?family=Roboto+Slab:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&amp;display=swap" type="text/css" media="all">
<script type="text/javascript" data-cfasync="false" src="./wp-includes/js/jquery/jquery.min.js?ver=3.7.1" id="jquery-core-js"></script>
<script type="text/javascript" data-cfasync="false" src="./wp-includes/js/jquery/jquery-migrate.min.js?ver=3.4.1" id="jquery-migrate-js"></script>
<script type="text/javascript" id="xoo-aff-js-js-extra">
/* <![CDATA[ */
var xoo_aff_localize = {"adminurl":"\/\/wp-admin\/admin-ajax.php","password_strength":{"min_password_strength":3,"i18n_password_error":"Please enter a stronger password.","i18n_password_hint":"Hint: The password should be at least twelve characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! &quot; ? $ % ^ &amp; )."}};
/* ]]> */
</script>
<script type="text/javascript" src="./wp-content/plugins/easy-login-woocommerce/xoo-form-fields-fw/assets/js/xoo-aff-js.js?ver=2.1.0" id="xoo-aff-js-js" defer="defer" data-wp-strategy="defer"></script>
<script type="text/javascript" id="bookings-for-woocommercecommon-js-extra">
/* <![CDATA[ */
var mbfw_common_param = {"ajaxurl":"\/\/wp-admin\/admin-ajax.php"};
/* ]]> */
</script>
<script type="text/javascript" src="./wp-content/plugins/mwb-bookings-for-woocommerce/common/js/mwb-bookings-for-woocommerce-common.js?ver=3.9.0" id="bookings-for-woocommercecommon-js"></script>
<script type="text/javascript" src="./wp-content/plugins/woocommerce/assets/js/jquery-blockui/jquery.blockUI.min.js?ver=2.7.0-wc.10.3.3" id="wc-jquery-blockui-js" defer="defer" data-wp-strategy="defer"></script>
<script type="text/javascript" id="wc-add-to-cart-js-extra">
/* <![CDATA[ */
var wc_add_to_cart_params = {"ajax_url":"\/wp-admin\/admin-ajax.php","wc_ajax_url":"\/?wc-ajax=%%endpoint%%","i18n_view_cart":"View cart","cart_url":"\/","is_cart":"","cart_redirect_after_add":"no"};
/* ]]> */
</script>
<script type="text/javascript" src="./wp-content/plugins/woocommerce/assets/js/frontend/add-to-cart.min.js?ver=10.3.3" id="wc-add-to-cart-js" defer="defer" data-wp-strategy="defer"></script>
<script type="text/javascript" src="./wp-content/plugins/woocommerce/assets/js/js-cookie/js.cookie.min.js?ver=2.1.4-wc.10.3.3" id="wc-js-cookie-js" defer="defer" data-wp-strategy="defer"></script>
<script type="text/javascript" id="woocommerce-js-extra">
/* <![CDATA[ */
var woocommerce_params = {"ajax_url":"\/wp-admin\/admin-ajax.php","wc_ajax_url":"\/?wc-ajax=%%endpoint%%","i18n_password_show":"Show password","i18n_password_hide":"Hide password"};
/* ]]> */
</script>
<script type="text/javascript" src="./wp-content/plugins/woocommerce/assets/js/frontend/woocommerce.min.js?ver=10.3.3" id="woocommerce-js" defer="defer" data-wp-strategy="defer"></script>
<link rel="https://api.w.org/" href="./wp-json/index.html"><link rel="alternate" title="JSON" type="application/json" href="./wp-json/wp/v2/pages/800/index.html"><link rel="EditURI" type="application/rsd+xml" title="RSD" href="./xmlrpc.php?rsd">
<meta name="generator" content="WordPress 6.8.3">
<meta name="generator" content="WooCommerce 10.3.3">
<link rel="canonical" href="./index.html">
<link rel="shortlink" href="./index.html">
<link rel="alternate" title="oEmbed (JSON)" type="application/json+oembed" href="./wp-json/oembed/1.0/embed/index.html?url=https%3A%2F%2F%2F">
<link rel="alternate" title="oEmbed (XML)" type="text/xml+oembed" href="./wp-json/oembed/1.0/embed/index.html?url=https%3A%2F%2F%2F&amp;format=xml">
	<noscript><style>.woocommerce-product-gallery{ opacity: 1 !important; }</style></noscript>
	<link rel="icon" href="./wp-content/uploads/2025/10/image-removebg-preview-26-100x100.png" sizes="32x32">
<link rel="icon" href="./wp-content/uploads/2025/10/image-removebg-preview-26.png" sizes="192x192">
<link rel="apple-touch-icon" href="./wp-content/uploads/2025/10/image-removebg-preview-26.png">
<meta name="msapplication-TileImage" content="./wp-content/uploads/2025/10/image-removebg-preview-26.png">
		<style type="text/css" id="wp-custom-css">
			/* ====== WPForms Label Text Color ====== */
.wpforms-form label {
  color: #555659 !important; /* Text color for field labels */
  font-weight: 500;
}

/* ====== Input Field Text Color ====== */
.wpforms-form input,
.wpforms-form textarea,
.wpforms-form select {
  color: #555659 !important;
  border: 1px solid #ccc !important; /* Light border */
  border-radius: 6px !important;
  padding: 10px !important;
} 

/* ====== Submit Button Style ====== */
.wpforms-submit {
  background-color: #2EA5B4 !important; /* Button background */
  color: #ffffff !important;            /* Button text color */
  border: none !important;
  border-radius: 8px !important;
  font-weight: 600;
  padding: 12px 25px !important;
  transition: 0.3s ease;
}

/* ====== Hover Effect on Button ====== */
.wpforms-submit:hover {
  background-color: #238896 !important; /* Slightly darker shade */
}


/* Fix padding for the long product description tab */
.woocommerce-Tabs-panel--description {
  padding: 30px 40px !important;
  background-color: #ffffff;
  border-radius: 10px;
}

/* Add spacing around the Related Products section */
.related.products {
    padding-left: 20px;
    padding-right: 20px;
    margin-top: 40px;
}



.page-id-1747 h1.entry-title,
.page-id-1747 .entry-header,
.page-id-1747 .elementor-heading-title,
.page-id-1747 .page-title,
.page-id-1747 .ast-archive-title,
.page-id-1747 .ast-single-post-order,
.page-id-1747 .ast-header-title,
.page-id-1747 header.page-header {
  display: none !important;
  visibility: hidden !important;
  opacity: 0 !important;
  height: 0 !important;
  margin: 0 !important;
  padding: 0 !important;
}


/* ==== BOOK NOW BUTTON (Exact Wanderlust Style) ==== */
a.button, 
a.add_to_cart_button, 
a.product_type_simple {
  background-color: #2EA5B4 !important; /* Brand color */
  color: transparent !important; /* Hide default text */
  border: none !important;
  border-radius: 50px !important; /* Fully rounded */
  padding: 14px 15px !important; /* Wider sides */
  font-family: 'Spinnaker', sans-serif !important; /* Wanderlust font */
  font-size: 15px !important;
  font-weight: 600 !important;
  text-transform: uppercase !important;
  letter-spacing: 0.5px !important;
  position: relative;
  transition: all 0.3s ease-in-out !important;
  text-align: center !important;
  display: inline-block !important;
  overflow: hidden;
}

/* Replace default text with "Book Now" */
a.button::after,
a.add_to_cart_button::after,
a.product_type_simple::after {
  content: "Book Now" !important;
  position: absolute;
  inset: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #fff !important;
  font-weight: 600;
  text-transform: uppercase;
  pointer-events: none;
}

/* Hover effect - darker teal + lift */
a.button:hover, 
a.add_to_cart_button:hover, 
a.product_type_simple:hover {
  background-color: #23939F !important;
  transform: translateY(-2px);
  box-shadow: 0 6px 15px rgba(46,165,180,0.25);
}

/* ===== Fix Featured Product Image Padding (Single Product Page) ===== */
.single-product div.product div.images img {
  padding: 10px;         /* adds space inside the image box */
  margin: 0 auto;        /* centers image */
  border-radius: 10px;   /* optional - gives soft corners */
  box-sizing: border-box;
}

/* ===== Ensure Image Container Has Space From Edges ===== */
.single-product div.product div.images {
  padding: 25px;
}

/* ===== Responsive Fix ===== */
@media (max-width: 768px) {
  .single-product div.product div.images {
    padding: 10px;
  }
}






		</style>
		<style id="wpr_lightbox_styles">
				.lg-backdrop {
					background-color: rgba(0,0,0,0.6) !important;
				}
				.lg-toolbar,
				.lg-dropdown {
					background-color: rgba(0,0,0,0.8) !important;
				}
				.lg-dropdown:after {
					border-bottom-color: rgba(0,0,0,0.8) !important;
				}
				.lg-sub-html {
					background-color: rgba(0,0,0,0.8) !important;
				}
				.lg-thumb-outer,
				.lg-progress-bar {
					background-color: #444444 !important;
				}
				.lg-progress {
					background-color: #a90707 !important;
				}
				.lg-icon {
					color: #efefef !important;
					font-size: 20px !important;
				}
				.lg-icon.lg-toogle-thumb {
					font-size: 24px !important;
				}
				.lg-icon:hover,
				.lg-dropdown-text:hover {
					color: #ffffff !important;
				}
				.lg-sub-html,
				.lg-dropdown-text {
					color: #efefef !important;
					font-size: 14px !important;
				}
				#lg-counter {
					color: #efefef !important;
					font-size: 14px !important;
				}
				.lg-prev,
				.lg-next {
					font-size: 35px !important;
				}

				/* Defaults */
				.lg-icon {
				background-color: transparent !important;
				}

				#lg-counter {
				opacity: 0.9;
				}

				.lg-thumb-outer {
				padding: 0 10px;
				}

				.lg-thumb-item {
				border-radius: 0 !important;
				border: none !important;
				opacity: 0.5;
				}

				.lg-thumb-item.active {
					opacity: 1;
				}
	         </style>	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"></head>
<body class="home wp-singular page-template page-template-elementor_canvas page page-id-800 wp-embed-responsive wp-theme-royal-elementor-kit theme-royal-elementor-kit woocommerce-no-js elementor-default elementor-template-canvas elementor-kit-10 elementor-page elementor-page-800">
	<style>.elementor-274 .elementor-element.elementor-element-32bfc0d4:not(.elementor-motion-effects-element-type-background), .elementor-274 .elementor-element.elementor-element-32bfc0d4 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#FFFFFF00;}.elementor-274 .elementor-element.elementor-element-32bfc0d4{transition:background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;margin-top:0px;margin-bottom:0px;padding:15px 0px 0px 0px;}.elementor-274 .elementor-element.elementor-element-32bfc0d4 > .elementor-background-overlay{transition:background 0.3s, border-radius 0.3s, opacity 0.3s;}.elementor-bc-flex-widget .elementor-274 .elementor-element.elementor-element-54a40664.elementor-column .elementor-widget-wrap{align-items:flex-end;}.elementor-274 .elementor-element.elementor-element-54a40664.elementor-column.elementor-element[data-element_type="column"] > .elementor-widget-wrap.elementor-element-populated{align-content:flex-end;align-items:flex-end;}.elementor-274 .elementor-element.elementor-element-b4c77ba > .elementor-widget-container{margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;}.elementor-274 .elementor-element.elementor-element-b4c77ba{text-align:left;}.elementor-274 .elementor-element.elementor-element-b4c77ba .wpr-logo{padding:0px 0px 0px 0px;border-style:none;}.elementor-274 .elementor-element.elementor-element-b4c77ba .wpr-logo-image{max-width:500px;}.elementor-274 .elementor-element.elementor-element-b4c77ba.wpr-logo-position-left .wpr-logo-image{margin-right:0px;}.elementor-274 .elementor-element.elementor-element-b4c77ba.wpr-logo-position-right .wpr-logo-image{margin-left:0px;}.elementor-274 .elementor-element.elementor-element-b4c77ba.wpr-logo-position-center .wpr-logo-image{margin-bottom:0px;}.elementor-274 .elementor-element.elementor-element-b4c77ba .wpr-logo-image img{-webkit-transition-duration:0.7s;transition-duration:0.7s;}.elementor-274 .elementor-element.elementor-element-b4c77ba .wpr-logo-title{color:#605BE5;margin:0 0 0px;}.elementor-274 .elementor-element.elementor-element-b4c77ba .wpr-logo-description{color:#888888;}.elementor-bc-flex-widget .elementor-274 .elementor-element.elementor-element-306600e5.elementor-column .elementor-widget-wrap{align-items:center;}.elementor-274 .elementor-element.elementor-element-306600e5.elementor-column.elementor-element[data-element_type="column"] > .elementor-widget-wrap.elementor-element-populated{align-content:center;align-items:center;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-menu-item.wpr-pointer-item{transition-duration:0.2s;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-menu-item.wpr-pointer-item:before{transition-duration:0.2s;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-menu-item.wpr-pointer-item:after{transition-duration:0.2s;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle-wrap{text-align:center;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu .wpr-menu-item,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu > .menu-item-has-children > .wpr-sub-icon{color:#2F2F2F;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu .wpr-menu-item:hover,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu > .menu-item-has-children:hover > .wpr-sub-icon,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu .wpr-menu-item.wpr-active-menu-item,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu > .menu-item-has-children.current_page_item > .wpr-sub-icon{color:#2EA5B4;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-line-fx .wpr-menu-item:before,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-line-fx .wpr-menu-item:after{background-color:#158E9D;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-border-fx .wpr-menu-item:before{border-color:#158E9D;border-width:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-background-fx .wpr-menu-item:before{background-color:#158E9D;}.elementor-274 .elementor-element.elementor-element-42c4e46d .menu-item-has-children .wpr-sub-icon{font-size:15px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-background:not(.wpr-sub-icon-none) .wpr-nav-menu-horizontal .menu-item-has-children .wpr-pointer-item{padding-right:calc(15px + 7px);}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-border:not(.wpr-sub-icon-none) .wpr-nav-menu-horizontal .menu-item-has-children .wpr-pointer-item{padding-right:calc(15px + 7px);}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu .wpr-menu-item,.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu a,.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle-text{font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:400;text-transform:uppercase;letter-spacing:0.4px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-underline .wpr-menu-item:after,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-overline .wpr-menu-item:before,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-double-line .wpr-menu-item:before,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-double-line .wpr-menu-item:after{height:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-underline>nav>ul>li>.wpr-menu-item:after,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-overline>nav>ul>li>.wpr-menu-item:before,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-double-line>nav>ul>li>.wpr-menu-item:before,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-double-line>nav>ul>li>.wpr-menu-item:after{height:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-border-fx>nav>ul>li>.wpr-menu-item:before{border-width:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-underline>.elementor-widget-container>nav>ul>li>.wpr-menu-item:after,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-overline>.elementor-widget-container>nav>ul>li>.wpr-menu-item:before,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-double-line>.elementor-widget-container>nav>ul>li>.wpr-menu-item:before,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-double-line>.elementor-widget-container>nav>ul>li>.wpr-menu-item:after{height:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-border-fx>.elementor-widget-container>nav>ul>li>.wpr-menu-item:before{border-width:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d:not(.wpr-pointer-border-fx) .wpr-menu-item.wpr-pointer-item:before{transform:translateY(-0px);}.elementor-274 .elementor-element.elementor-element-42c4e46d:not(.wpr-pointer-border-fx) .wpr-menu-item.wpr-pointer-item:after{transform:translateY(0px);}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu .wpr-menu-item{padding-left:7px;padding-right:7px;padding-top:15px;padding-bottom:15px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-background:not(.wpr-sub-icon-none) .wpr-nav-menu-vertical .menu-item-has-children .wpr-sub-icon{text-indent:-7px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-pointer-border:not(.wpr-sub-icon-none) .wpr-nav-menu-vertical .menu-item-has-children .wpr-sub-icon{text-indent:-7px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu > .menu-item{margin-left:12px;margin-right:12px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu-vertical .wpr-nav-menu > li > .wpr-sub-menu{margin-left:12px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-main-menu-align-left .wpr-nav-menu-vertical .wpr-nav-menu > li > .wpr-sub-icon{right:12px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-main-menu-align-right .wpr-nav-menu-vertical .wpr-nav-menu > li > .wpr-sub-icon{left:12px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu .wpr-sub-menu-item,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu > .menu-item-has-children .wpr-sub-icon{color:#333333;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu .wpr-sub-menu-item{background-color:#ffffff;padding-left:15px;padding-right:15px;padding-top:13px;padding-bottom:13px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu .wpr-sub-menu-item:hover,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu > .menu-item-has-children .wpr-sub-menu-item:hover .wpr-sub-icon,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu .wpr-sub-menu-item.wpr-active-menu-item,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu > .menu-item-has-children.current_page_item .wpr-sub-icon{color:#ffffff;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu .wpr-sub-menu-item:hover,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu .wpr-sub-menu-item.wpr-active-menu-item{background-color:#EA6FFB;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu .wpr-sub-icon{right:15px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-main-menu-align-right .wpr-nav-menu-vertical .wpr-sub-menu .wpr-sub-icon{left:15px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu-horizontal .wpr-nav-menu > li > .wpr-sub-menu{margin-top:0px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-sub-divider-yes .wpr-sub-menu li:not(:last-child){border-bottom-color:#e8e8e8;border-bottom-width:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-sub-menu{border-style:solid;border-width:1px 1px 1px 1px;border-color:#E8E8E8;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu a,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu .menu-item-has-children > a:after{color:#333333;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu li{background-color:#ffffff;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu li a:hover,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu .menu-item-has-children > a:hover:after,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu li a.wpr-active-menu-item,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu .menu-item-has-children.current_page_item > a:hover:after{color:#ffffff;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu a:hover,
					 .elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu a.wpr-active-menu-item{background-color:#EA6FFB;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu a{padding-left:10px;padding-right:10px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu .menu-item-has-children > a:after{margin-left:10px;margin-right:10px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu .wpr-mobile-menu-item{padding-top:10px;padding-bottom:10px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-mobile-divider-yes .wpr-mobile-nav-menu a{border-bottom-color:#e8e8e8;border-bottom-width:1px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu .wpr-mobile-sub-menu-item{font-size:12px;padding-top:5px;padding-bottom:5px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-nav-menu{margin-top:10px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle{border-color:#8783FFFA;width:38px;border-width:0px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle-text{color:#8783FFFA;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle-line{background-color:#8783FFFA;height:2px;margin-bottom:5px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle:hover{border-color:#EA6FFB;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle:hover .wpr-mobile-toggle-text{color:#EA6FFB;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle:hover .wpr-mobile-toggle-line{background-color:#EA6FFB;}.elementor-bc-flex-widget .elementor-274 .elementor-element.elementor-element-2e1cdbb3.elementor-column .elementor-widget-wrap{align-items:center;}.elementor-274 .elementor-element.elementor-element-2e1cdbb3.elementor-column.elementor-element[data-element_type="column"] > .elementor-widget-wrap.elementor-element-populated{align-content:center;align-items:center;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button{background-color:#FFFFFF00;-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;border-color:#2EA5B4;border-style:solid;border-width:1px 1px 1px 1px;border-radius:2px 2px 2px 2px;}	.elementor-274 .elementor-element.elementor-element-1a8c2969 [class*="elementor-animation"]:hover,
								.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button::before,
								.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button::after{background-color:#4A45D200;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button::before{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button::after{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;color:#2EA5B4;padding:8px 10px 8px 10px;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button .wpr-button-icon{-webkit-transition-duration:0.4s;transition-duration:0.4s;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button .wpr-button-icon svg{-webkit-transition-duration:0.4s;transition-duration:0.4s;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button .wpr-button-text{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button .wpr-button-content{-webkit-transition-duration:0.4s;transition-duration:0.4s;-webkit-animation-duration:0.4s;animation-duration:0.4s;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-wrap{max-width:500px;}.elementor-274 .elementor-element.elementor-element-1a8c2969{text-align:right;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-content{-webkit-justify-content:center;justify-content:center;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-text{-webkit-justify-content:center;justify-content:center;color:#2EA5B4;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-icon{font-size:18px;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-icon svg{width:18px;height:18px;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-position-left .wpr-button-icon{margin-right:12px;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-position-right .wpr-button-icon{margin-left:12px;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-style-inline .wpr-button-icon{color:#2EA5B4;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-style-inline .wpr-button-icon svg{fill:#2EA5B4;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-text,.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button::after{font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:500;letter-spacing:0.4px;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button:hover .wpr-button-text{color:#2EA5B4;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-style-inline .wpr-button:hover .wpr-button-icon{color:#2EA5B4;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-style-inline .wpr-button:hover .wpr-button-icon svg{fill:#2EA5B4;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button:hover{border-color:#158E9D;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-style-inline .wpr-button{padding:8px 10px 8px 10px;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-style-block .wpr-button-text{padding:8px 10px 8px 10px;}.elementor-274 .elementor-element.elementor-element-1a8c2969.wpr-button-icon-style-inline-block .wpr-button-content{padding:8px 10px 8px 10px;}@media(max-width:1024px){.elementor-274 .elementor-element.elementor-element-54a40664 > .elementor-element-populated{padding:0px 0px 0px 0px;}.elementor-274 .elementor-element.elementor-element-b4c77ba > .elementor-widget-container{padding:0px 0px 0px 60px;}.elementor-274 .elementor-element.elementor-element-b4c77ba{text-align:left;}.elementor-274 .elementor-element.elementor-element-42c4e46d > .elementor-widget-container{padding:0px 40px 0px 0px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-mobile-toggle-wrap{text-align:right;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu > .menu-item{margin-left:4px;margin-right:4px;}.elementor-274 .elementor-element.elementor-element-42c4e46d .wpr-nav-menu-vertical .wpr-nav-menu > li > .wpr-sub-menu{margin-left:4px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-main-menu-align-left .wpr-nav-menu-vertical .wpr-nav-menu > li > .wpr-sub-icon{right:4px;}.elementor-274 .elementor-element.elementor-element-42c4e46d.wpr-main-menu-align-right .wpr-nav-menu-vertical .wpr-nav-menu > li > .wpr-sub-icon{left:4px;}}@media(min-width:768px){.elementor-274 .elementor-element.elementor-element-54a40664{width:27%;}.elementor-274 .elementor-element.elementor-element-306600e5{width:55.854%;}.elementor-274 .elementor-element.elementor-element-2e1cdbb3{width:16.452%;}}@media(max-width:1024px) and (min-width:768px){.elementor-274 .elementor-element.elementor-element-54a40664{width:30%;}.elementor-274 .elementor-element.elementor-element-306600e5{width:70%;}.elementor-274 .elementor-element.elementor-element-2e1cdbb3{width:20%;}}@media(max-width:767px){.elementor-274 .elementor-element.elementor-element-54a40664{width:50%;}.elementor-274 .elementor-element.elementor-element-b4c77ba > .elementor-widget-container{padding:0px 0px 0px 30px;}.elementor-274 .elementor-element.elementor-element-306600e5{width:50%;}.elementor-274 .elementor-element.elementor-element-42c4e46d > .elementor-widget-container{padding:0px 20px 0px 0px;}.elementor-274 .elementor-element.elementor-element-1a8c2969{text-align:center;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-content{-webkit-justify-content:center;justify-content:center;}.elementor-274 .elementor-element.elementor-element-1a8c2969 .wpr-button-text{-webkit-justify-content:center;justify-content:center;}}</style>		<div data-elementor-type="wp-post" data-elementor-id="274" class="elementor elementor-274">
						<section class="elementor-section elementor-top-section elementor-element elementor-element-32bfc0d4 elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="32bfc0d4" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
						<div class="elementor-container elementor-column-gap-default">
					<div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-54a40664" data-id="54a40664" data-element_type="column">
			<div class="elementor-widget-wrap elementor-element-populated">
						<div class="elementor-element elementor-element-b4c77ba wpr-logo-position-center elementor-widget elementor-widget-wpr-logo" data-id="b4c77ba" data-element_type="widget" data-widget_type="wpr-logo.default">
				<div class="elementor-widget-container">
								
			<div class="wpr-logo elementor-clearfix">

								<picture class="wpr-logo-image">
										<source media="(max-width: 767px)" srcset="./wp-content/uploads/2025/10/Copy-of-Copy-of-Add-a-heading__3_-removebg-preview.png">	
					
										<source srcset="./wp-content/uploads/2025/10/Copy-of-Copy-of-Add-a-heading__3_-removebg-preview.png 1x, ./wp-content/uploads/2025/10/Copy-of-Copy-of-Add-a-heading__3_-removebg-preview.png 2x">	
										
					<img src="./wp-content/uploads/2025/10/Copy-of-Copy-of-Add-a-heading__3_-removebg-preview.png" alt="">

											<a class="wpr-logo-url" rel="home" aria-label="" href="./index.php"></a>
									</source></source></picture>
				
				
									<a class="wpr-logo-url" rel="home" aria-label="" href="./index.php"></a>
				
			</div>
				
						</div>
				</div>
					</div>
		</div>
				<div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-306600e5" data-id="306600e5" data-element_type="column">
			<div class="elementor-widget-wrap elementor-element-populated">
						<div class="elementor-element elementor-element-42c4e46d wpr-main-menu-align-center wpr-nav-menu-bp-tablet wpr-main-menu-align--tabletcenter wpr-main-menu-align--mobilecenter wpr-pointer-underline wpr-pointer-line-fx wpr-pointer-fx-fade wpr-sub-icon-caret-down wpr-sub-menu-fx-fade wpr-mobile-menu-full-width wpr-mobile-menu-item-align-center wpr-mobile-toggle-v1 wpr-sub-divider-yes wpr-mobile-divider-yes elementor-widget elementor-widget-wpr-nav-menu" data-id="42c4e46d" data-element_type="widget" data-settings="{&quot;menu_layout&quot;:&quot;horizontal&quot;}" data-widget_type="wpr-nav-menu.default">
				<div class="elementor-widget-container">
					<nav class="wpr-nav-menu-container wpr-nav-menu-horizontal" data-trigger="hover"><ul id="menu-1-42c4e46d" class="wpr-nav-menu"><li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-800 current_page_item menu-item-804"><a href="./index.php" aria-current="page" class="wpr-menu-item wpr-pointer-item wpr-active-menu-item">Home</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1773"><a href="./wander-stays/index.html" class="wpr-menu-item wpr-pointer-item">Wander Stays</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-936"><a href="./contact-us/index.php" class="wpr-menu-item wpr-pointer-item">Contact Us</a></li>
<li class="xoo-el-login-tgr menu-item menu-item-type-custom menu-item-object-custom menu-item-2064"><a class="wpr-menu-item wpr-pointer-item">Login</a></li>
</ul></nav><nav class="wpr-mobile-nav-menu-container"><div class="wpr-mobile-toggle-wrap"><div class="wpr-mobile-toggle"><span class="wpr-mobile-toggle-line"></span><span class="wpr-mobile-toggle-line"></span><span class="wpr-mobile-toggle-line"></span></div></div><ul id="mobile-menu-2-42c4e46d" class="wpr-mobile-nav-menu"><li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-800 current_page_item menu-item-804"><a href="./index.html" aria-current="page" class="wpr-mobile-menu-item wpr-active-menu-item">Home</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1773"><a href="./wander-stays/index.html" class="wpr-mobile-menu-item">Wander Stays</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-936"><a href="./contact-us/index.php" class="wpr-mobile-menu-item">Contact Us</a></li>
<li class="xoo-el-login-tgr menu-item menu-item-type-custom menu-item-object-custom menu-item-2064"><a class="wpr-mobile-menu-item">Login</a></li>
</ul></nav>				</div>
				</div>
					</div>
		</div>
				<div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-2e1cdbb3 elementor-hidden-tablet elementor-hidden-mobile" data-id="2e1cdbb3" data-element_type="column">
			<div class="elementor-widget-wrap elementor-element-populated">
						<div class="elementor-element elementor-element-1a8c2969 wpr-button-icon-position-left wpr-button-icon-style-inline elementor-widget elementor-widget-wpr-button" data-id="1a8c2969" data-element_type="widget" data-widget_type="wpr-button.default">
				<div class="elementor-widget-container">
						
			
		
		<div class="wpr-button-wrap elementor-clearfix">
		<a class="wpr-button wpr-button-effect wpr-button-none" data-text="" href="tel:+923010528888">
			
			<span class="wpr-button-content">
									<span class="wpr-button-text">CALL US NOW</span>
								
									<span class="wpr-button-icon"><svg class="e-font-icon-svg e-fas-phone-alt" viewbox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M497.39 361.8l-112-48a24 24 0 0 0-28 6.9l-49.6 60.6A370.66 370.66 0 0 1 130.6 204.11l60.6-49.6a23.94 23.94 0 0 0 6.9-28l-48-112A24.16 24.16 0 0 0 122.6.61l-104 24A24 24 0 0 0 0 48c0 256.5 207.9 464 464 464a24 24 0 0 0 23.4-18.6l24-104a24.29 24.29 0 0 0-14.01-27.6z"></path></svg></span>
							</span>
		</a>

				</div>
	
	
					</div>
				</div>
					</div>
		</div>
					</div>
		</section>
				</div>
				<div data-elementor-type="wp-page" data-elementor-id="800" class="elementor elementor-800">
						<section class="elementor-section elementor-top-section elementor-element elementor-element-9201183 elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="9201183" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
							<div class="elementor-background-overlay"></div>
							<div class="elementor-container elementor-column-gap-default">
					<div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-508c306" data-id="508c306" data-element_type="column">
			<div class="elementor-widget-wrap elementor-element-populated">
						<div class="elementor-element elementor-element-c137ac9 elementor-invisible elementor-widget elementor-widget-heading" data-id="c137ac9" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInLeft&quot;}" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h1 class="elementor-heading-title elementor-size-default">Welcome to Wanderlust Pakistan</h1>				</div>
				</div>
				<div class="elementor-element elementor-element-7a4d140 elementor-invisible elementor-widget elementor-widget-text-editor" data-id="7a4d140" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInLeft&quot;}" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<p>Discover Pakistan’s most breathtaking destinations with our luxury glamping, resort stays, and adventure experiences — crafted for comfort and unforgettable memories.</p>								</div>
				</div>
		<div class="elementor-element elementor-element-14ca025 e-flex e-con-boxed wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-parent" data-id="14ca025" data-element_type="container">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-9d207ad wpr-button-icon-style-inline wpr-button-icon-position-right elementor-widget elementor-widget-wpr-button" data-id="9d207ad" data-element_type="widget" data-widget_type="wpr-button.default">
				<div class="elementor-widget-container">
						
			
		
		<div class="wpr-button-wrap elementor-clearfix">
		<a class="wpr-button wpr-button-effect wpr-button-none" data-text="" href="./wander-stays/index.html">
			
			<span class="wpr-button-content">
									<span class="wpr-button-text">Start Your Journey</span>
								
							</span>
		</a>

				</div>
	
	
					</div>
				</div>
				<div class="elementor-element elementor-element-9d0568a wpr-button-icon-position-left elementor-widget__width-initial elementor-widget-mobile__width-initial wpr-button-icon-style-inline elementor-widget elementor-widget-wpr-button" data-id="9d0568a" data-element_type="widget" data-widget_type="wpr-button.default">
				<div class="elementor-widget-container">
						
			
		
		<div class="wpr-button-wrap elementor-clearfix">
		<a class="wpr-button wpr-button-effect wpr-button-none" data-text="" href="https://www.dropbox.com/scl/fo/ephuyocnp9f56f9h3cg59/AK0v-k6EsUBaay9YdzpF78A/Glamp%20Flora?rlkey=gvf9nhc521hf4bdqqwwroz2k0&amp;e=2&amp;dl=0">
			
			<span class="wpr-button-content">
									<span class="wpr-button-text">Explore Gallery</span>
								
									<span class="wpr-button-icon"><svg class="e-font-icon-svg e-fas-video" viewbox="0 0 576 512" xmlns="http://www.w3.org/2000/svg"><path d="M336.2 64H47.8C21.4 64 0 85.4 0 111.8v288.4C0 426.6 21.4 448 47.8 448h288.4c26.4 0 47.8-21.4 47.8-47.8V111.8c0-26.4-21.4-47.8-47.8-47.8zm189.4 37.7L416 177.3v157.4l109.6 75.5c21.2 14.6 50.4-.3 50.4-25.8V127.5c0-25.4-29.1-40.4-50.4-25.8z"></path></svg></span>
							</span>
		</a>

				</div>
	
	
					</div>
				</div>
					</div>
				</div>
					</div>
		</div>
				<div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-f17f568" data-id="f17f568" data-element_type="column">
			<div class="elementor-widget-wrap">
							</div>
		</div>
					</div>
		</section>
		<div class="elementor-element elementor-element-0cca238 e-flex e-con-boxed wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-parent" data-id="0cca238" data-element_type="container">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-f28ef88 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no elementor-invisible e-con e-child" data-id="f28ef88" data-element_type="container" data-settings="{&quot;animation&quot;:&quot;fadeIn&quot;}">
				<div class="elementor-element elementor-element-8a97c8b elementor-widget elementor-widget-heading" data-id="8a97c8b" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Discover the Wanderlust Experience</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-84626d4 elementor-widget__width-initial elementor-widget elementor-widget-heading" data-id="84626d4" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">We Will Helping You Find Your Dream Vacation</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-7d24aa6 elementor-widget elementor-widget-text-editor" data-id="7d24aa6" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<p>At Wanderlust Pakistan, we create travel experiences that bring you closer to nature and comfort at the same time. Whether it’s luxury glamping in the mountains or a peaceful stay by the beach, we help you find the perfect escape from the city life.</p>								</div>
				</div>
		<div class="elementor-element elementor-element-d1e43b4 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="d1e43b4" data-element_type="container">
				<div class="elementor-element elementor-element-0da7fd7 elementor-view-stacked elementor-shape-rounded elementor-position-left elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="0da7fd7" data-element_type="widget" data-widget_type="icon-box.default">
				<div class="elementor-widget-container">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-hands-helping" viewbox="0 0 640 512" xmlns="http://www.w3.org/2000/svg"><path d="M488 192H336v56c0 39.7-32.3 72-72 72s-72-32.3-72-72V126.4l-64.9 39C107.8 176.9 96 197.8 96 220.2v47.3l-80 46.2C.7 322.5-4.6 342.1 4.3 357.4l80 138.6c8.8 15.3 28.4 20.5 43.7 11.7L231.4 448H368c35.3 0 64-28.7 64-64h16c17.7 0 32-14.3 32-32v-64h8c13.3 0 24-10.7 24-24v-48c0-13.3-10.7-24-24-24zm147.7-37.4L555.7 16C546.9.7 527.3-4.5 512 4.3L408.6 64H306.4c-12 0-23.7 3.4-33.9 9.7L239 94.6c-9.4 5.8-15 16.1-15 27.1V248c0 22.1 17.9 40 40 40s40-17.9 40-40v-88h184c30.9 0 56 25.1 56 56v28.5l80-46.2c15.3-8.9 20.5-28.4 11.7-43.7z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span>
							Best Travel Deals						</span>
					</h3>
				
									<p class="elementor-icon-box-description">
						We offer affordable yet premium glamping and stay packages across Pakistan — so you can enjoy comfort, adventure, and beauty without worrying about the cost.					</p>
				
			</div>
			
		</div>
						</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-3f22b43 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="3f22b43" data-element_type="container">
				<div class="elementor-element elementor-element-b4c85bd elementor-view-stacked elementor-shape-rounded elementor-position-left elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="b4c85bd" data-element_type="widget" data-widget_type="icon-box.default">
				<div class="elementor-widget-container">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-id-card" viewbox="0 0 576 512" xmlns="http://www.w3.org/2000/svg"><path d="M528 32H48C21.5 32 0 53.5 0 80v16h576V80c0-26.5-21.5-48-48-48zM0 432c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V128H0v304zm352-232c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16zm0 64c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16zm0 64c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16zM176 192c35.3 0 64 28.7 64 64s-28.7 64-64 64-64-28.7-64-64 28.7-64 64-64zM67.1 396.2C75.5 370.5 99.6 352 128 352h8.2c12.3 5.1 25.7 8 39.8 8s27.6-2.9 39.8-8h8.2c28.4 0 52.5 18.5 60.9 44.2 3.2 9.9-5.2 19.8-15.6 19.8H82.7c-10.4 0-18.8-10-15.6-19.8z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span>
							Instant Booking Confirmation						</span>
					</h3>
				
									<p class="elementor-icon-box-description">
						Book your next stay easily through our platform and get instant confirmation with hassle-free communication and support.					</p>
				
			</div>
			
		</div>
						</div>
				</div>
				</div>
				</div>
		<div div id="plan-form" class="elementor-element elementor-element-f3db361 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no elementor-invisible e-con e-child" data-id="f3db361" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;animation&quot;:&quot;fadeInUp&quot;}">
				<div class="elementor-element elementor-element-7fdae99 elementor-widget elementor-widget-heading" data-id="7fdae99" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h3 class="elementor-heading-title elementor-size-default">Plan Your Next Adventure</h3>				</div>
				</div>
				<div class="elementor-element elementor-element-8600ff8 elementor-widget elementor-widget-heading" data-id="8600ff8" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<span class="elementor-heading-title elementor-size-default">Have a question about our glamping sites, travel packages, or bookings? Send us your details below and our team will get in touch soon.</span>				</div>
				</div>
				<div class="elementor-element elementor-element-56a5339 elementor-widget elementor-widget-shortcode" data-id="56a5339" data-element_type="widget" data-widget_type="shortcode.default">
				<div class="elementor-widget-container"> 

				<div class="elementor-shortcode">
    <form class="wanderlust-contact-form" method="post" action="index.php#plan-form">
        <!-- hidden field, taake baad me PHP se pata chale kaunsa form submit hua -->
        <input type="hidden" name="form_name" value="home_plan_form">

        <div class="form-row">
            <label for="full_name">Full Name <span style="color:#e63946;">*</span></label>
            <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>
        </div>

        <div class="form-row">
            <label for="email">Email Address <span style="color:#e63946;">*</span></label>
            <input type="email" id="email" name="email" placeholder="example@email.com" required>
        </div>

        <div class="form-row">
            <label for="phone">Phone Number <span style="color:#e63946;">*</span></label>
            <input type="text" id="phone" name="phone" placeholder="+92 300 1234567" required>
        </div>

        <div class="form-row">
            <label for="destination">Where do you want to travel?</label>
            <input type="text" id="destination" name="destination" placeholder="e.g. Hunza, Skardu, Neelum Valley...">
        </div>

        <div class="form-row">
            <label for="experience">Type of Experience</label>
            <select id="experience" name="experience">
                <option value="">Select an experience</option>
                <option value="Mountain Glamping">Mountain Glamping</option>
                <option value="Lakeside Cabin">Lakeside Cabin</option>
                <option value="Desert Camp">Desert Camp</option>
                <option value="Forest Lodge">Forest Lodge</option>
                <option value="Cultural Stay">Cultural Stay</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-row">
            <button type="submit" name="plan_form_submit" class="btn-submit">
                Submit
            </button>
        </div>
    </form>
</div>


				</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-d1e3852 e-flex e-con-boxed wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-parent" data-id="d1e3852" data-element_type="container">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-3bc9b91 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no elementor-invisible e-con e-child" data-id="3bc9b91" data-element_type="container" data-settings="{&quot;animation&quot;:&quot;slideInUp&quot;}">
		<div class="elementor-element elementor-element-a1e7388 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="a1e7388" data-element_type="container">
				<div class="elementor-element elementor-element-4f71258 elementor-widget elementor-widget-heading" data-id="4f71258" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Best Visits</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-0e8ad55 elementor-widget__width-inherit elementor-widget elementor-widget-heading" data-id="0e8ad55" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Popular Glamps For You</h2>				</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-b788551 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="b788551" data-element_type="container">
				<div class="elementor-element elementor-element-bd54935 elementor-widget elementor-widget-text-editor" data-id="bd54935" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<p data-pm-slice="0 0 []">Find peaceful luxury glamps across Pakistan — perfect for your next relaxing getaway. Each stay is designed with comfort, privacy, and a touch of adventure for unforgettable moments.</p>								</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-a4644ea e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="a4644ea" data-element_type="container">
				<div class="elementor-element elementor-element-795dfbc wpr-button-icon-style-inline wpr-button-icon-position-right elementor-widget elementor-widget-wpr-button" data-id="795dfbc" data-element_type="widget" data-widget_type="wpr-button.default">
				<div class="elementor-widget-container">
						
			
		
		<div class="wpr-button-wrap elementor-clearfix">
		<a class="wpr-button wpr-button-effect wpr-button-none" data-text="" href="./wander-stays/index.html">
			
			<span class="wpr-button-content">
									<span class="wpr-button-text">All Packages</span>
								
							</span>
		</a>

				</div>
	
	
					</div>
				</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-da459cb e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="da459cb" data-element_type="container">
		<div class="elementor-element elementor-element-44fb30b e-grid e-con-full wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no elementor-invisible e-con e-child" data-id="44fb30b" data-element_type="container" data-settings="{&quot;animation&quot;:&quot;bounceInUp&quot;}">
		<div class="elementor-element elementor-element-1483345 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="1483345" data-element_type="container">
		<div class="elementor-element elementor-element-23d8769 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="23d8769" data-element_type="container">
				<div class="elementor-element elementor-element-7151b5f elementor-widget elementor-widget-image" data-id="7151b5f" data-element_type="widget" data-widget_type="image.default">
				<div class="elementor-widget-container">
																<a href="./product/glamp-aqua-beachside-bliss-in-the-mountains/index.html">
							<img decoding="async" src="./wp-content/uploads/elementor/thumbs/DSC_0548-ezgif.com-webp-to-jpg-converter-rdm937rpfb61y69izjm6qx9mfqvx5vqocwqqbdo1vs.jpg" title="Glamp Aqua" alt="Glamp Aqua" class="elementor-animation-bob" loading="lazy">								</a>
															</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-a6eb1d8 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="a6eb1d8" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-e85d882 elementor-icon-list--layout-inline elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="e85d882" data-element_type="widget" data-widget_type="icon-list.default">
				<div class="elementor-widget-container">
							<ul class="elementor-icon-list-items elementor-inline-items">
							<li class="elementor-icon-list-item elementor-inline-item">
											<span class="elementor-icon-list-icon">
							<svg aria-hidden="true" class="e-font-icon-svg e-fas-bed" viewbox="0 0 640 512" xmlns="http://www.w3.org/2000/svg"><path d="M176 256c44.11 0 80-35.89 80-80s-35.89-80-80-80-80 35.89-80 80 35.89 80 80 80zm352-128H304c-8.84 0-16 7.16-16 16v144H64V80c0-8.84-7.16-16-16-16H16C7.16 64 0 71.16 0 80v352c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16v-48h512v48c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16V240c0-61.86-50.14-112-112-112z"></path></svg>						</span>
										<span class="elementor-icon-list-text">Per Night</span>
									</li>
								<li class="elementor-icon-list-item elementor-inline-item">
											<span class="elementor-icon-list-icon">
							<svg aria-hidden="true" class="e-font-icon-svg e-fas-restroom" viewbox="0 0 640 512" xmlns="http://www.w3.org/2000/svg"><path d="M128 128c35.3 0 64-28.7 64-64S163.3 0 128 0 64 28.7 64 64s28.7 64 64 64zm384 0c35.3 0 64-28.7 64-64S547.3 0 512 0s-64 28.7-64 64 28.7 64 64 64zm127.3 226.5l-45.6-185.8c-3.3-13.5-15.5-23-29.8-24.2-15 9.7-32.8 15.5-52 15.5-19.2 0-37-5.8-52-15.5-14.3 1.2-26.5 10.7-29.8 24.2l-45.6 185.8C381 369.6 393 384 409.2 384H464v104c0 13.3 10.7 24 24 24h48c13.3 0 24-10.7 24-24V384h54.8c16.2 0 28.2-14.4 24.5-29.5zM336 0h-32c-8.8 0-16 7.2-16 16v480c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V16c0-8.8-7.2-16-16-16zM180.1 144.4c-15 9.8-32.9 15.6-52.1 15.6-19.2 0-37.1-5.8-52.1-15.6C51.3 146.5 32 166.9 32 192v136c0 13.3 10.7 24 24 24h8v136c0 13.3 10.7 24 24 24h80c13.3 0 24-10.7 24-24V352h8c13.3 0 24-10.7 24-24V192c0-25.1-19.3-45.5-43.9-47.6z"></path></svg>						</span>
										<span class="elementor-icon-list-text">4</span>
									</li>
						</ul>
						</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-6e911eb e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="6e911eb" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-79612c4 elementor-widget elementor-widget-heading" data-id="79612c4" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Glamp Aqua – Beachside Bliss in the Mountains</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-2724f8c elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="2724f8c" data-element_type="widget" data-widget_type="icon-list.default">
				<div class="elementor-widget-container">
							<ul class="elementor-icon-list-items">
							<li class="elementor-icon-list-item">
											<span class="elementor-icon-list-icon">
							<svg aria-hidden="true" class="e-font-icon-svg e-fas-map-marker-alt" viewbox="0 0 384 512" xmlns="http://www.w3.org/2000/svg"><path d="M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z"></path></svg>						</span>
										<span class="elementor-icon-list-text">Kuldana Hills, Murree</span>
									</li>
						</ul>
						</div>
				</div>
				<div class="elementor-element elementor-element-aaa0c50 elementor-widget-divider--view-line elementor-widget elementor-widget-divider" data-id="aaa0c50" data-element_type="widget" data-widget_type="divider.default">
				<div class="elementor-widget-container">
							<div class="elementor-divider">
			<span class="elementor-divider-separator">
						</span>
		</div>
						</div>
				</div>
		<div class="elementor-element elementor-element-b6c3ea7 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="b6c3ea7" data-element_type="container">
		<div class="elementor-element elementor-element-f3597f9 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="f3597f9" data-element_type="container">
				<div class="elementor-element elementor-element-12d1ff6 elementor-widget elementor-widget-heading" data-id="12d1ff6" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">From</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-8e83b26 elementor-widget elementor-widget-heading" data-id="8e83b26" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<span class="elementor-heading-title elementor-size-default">Weekdays (Mon-Thur)= 25000 PKR
Weekends (Fri-Sun)= 28000 PKR</span>				</div>
				</div>
				<div class="elementor-element elementor-element-5f6a2d8 elementor-align-center elementor-widget elementor-widget-button" data-id="5f6a2d8" data-element_type="widget" data-widget_type="button.default">
				<div class="elementor-widget-container">
									<div class="elementor-button-wrapper">
					<a class="elementor-button elementor-button-link elementor-size-sm" href="./product/glamp-aqua-beachside-bliss-in-the-mountains/index.php">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">EXPLORE MORE</span>
					</span>
					</a>
				</div>
								</div>
				</div>
				</div>
				</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-e5cc5c8 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="e5cc5c8" data-element_type="container">
		<div class="elementor-element elementor-element-9ae86b6 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="9ae86b6" data-element_type="container">
				<div class="elementor-element elementor-element-e5e84b3 elementor-widget elementor-widget-image" data-id="e5e84b3" data-element_type="widget" data-widget_type="image.default">
				<div class="elementor-widget-container">
																<a href="./product/glamp-flora-elegance-in-bloom/index.html">
							<img decoding="async" src="./wp-content/uploads/elementor/thumbs/DSC_0597-ezgif.com-webp-to-jpg-converter-rdmchkff0ynp6wjijfjyb1rm3btx3jgv6hs16097yg.jpg" title="Glamp Flora" alt="Glamp Flora" class="elementor-animation-bob" loading="lazy">								</a>
															</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-e517d73 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="e517d73" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-6ca2fcd elementor-icon-list--layout-inline elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="6ca2fcd" data-element_type="widget" data-widget_type="icon-list.default">
				<div class="elementor-widget-container">
							<ul class="elementor-icon-list-items elementor-inline-items">
							<li class="elementor-icon-list-item elementor-inline-item">
											<span class="elementor-icon-list-icon">
							<svg aria-hidden="true" class="e-font-icon-svg e-fas-bed" viewbox="0 0 640 512" xmlns="http://www.w3.org/2000/svg"><path d="M176 256c44.11 0 80-35.89 80-80s-35.89-80-80-80-80 35.89-80 80 35.89 80 80 80zm352-128H304c-8.84 0-16 7.16-16 16v144H64V80c0-8.84-7.16-16-16-16H16C7.16 64 0 71.16 0 80v352c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16v-48h512v48c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16V240c0-61.86-50.14-112-112-112z"></path></svg>						</span>
										<span class="elementor-icon-list-text">Per Night</span>
									</li>
								<li class="elementor-icon-list-item elementor-inline-item">
											<span class="elementor-icon-list-icon">
							<svg aria-hidden="true" class="e-font-icon-svg e-fas-restroom" viewbox="0 0 640 512" xmlns="http://www.w3.org/2000/svg"><path d="M128 128c35.3 0 64-28.7 64-64S163.3 0 128 0 64 28.7 64 64s28.7 64 64 64zm384 0c35.3 0 64-28.7 64-64S547.3 0 512 0s-64 28.7-64 64 28.7 64 64 64zm127.3 226.5l-45.6-185.8c-3.3-13.5-15.5-23-29.8-24.2-15 9.7-32.8 15.5-52 15.5-19.2 0-37-5.8-52-15.5-14.3 1.2-26.5 10.7-29.8 24.2l-45.6 185.8C381 369.6 393 384 409.2 384H464v104c0 13.3 10.7 24 24 24h48c13.3 0 24-10.7 24-24V384h54.8c16.2 0 28.2-14.4 24.5-29.5zM336 0h-32c-8.8 0-16 7.2-16 16v480c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V16c0-8.8-7.2-16-16-16zM180.1 144.4c-15 9.8-32.9 15.6-52.1 15.6-19.2 0-37.1-5.8-52.1-15.6C51.3 146.5 32 166.9 32 192v136c0 13.3 10.7 24 24 24h8v136c0 13.3 10.7 24 24 24h80c13.3 0 24-10.7 24-24V352h8c13.3 0 24-10.7 24-24V192c0-25.1-19.3-45.5-43.9-47.6z"></path></svg>						</span>
										<span class="elementor-icon-list-text">4</span>
									</li>
						</ul>
						</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-57fd93b e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="57fd93b" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-a5086b3 elementor-widget elementor-widget-heading" data-id="a5086b3" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Glamp Flora – Elegance in Bloom</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-66ea9d2 elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="66ea9d2" data-element_type="widget" data-widget_type="icon-list.default">
				<div class="elementor-widget-container">
							<ul class="elementor-icon-list-items">
							<li class="elementor-icon-list-item">
											<span class="elementor-icon-list-icon">
							<svg aria-hidden="true" class="e-font-icon-svg e-fas-map-marker-alt" viewbox="0 0 384 512" xmlns="http://www.w3.org/2000/svg"><path d="M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z"></path></svg>						</span>
										<span class="elementor-icon-list-text">Kuldana Hills, Murree</span>
									</li>
						</ul>
						</div>
				</div>
				<div class="elementor-element elementor-element-efdbbe5 elementor-widget-divider--view-line elementor-widget elementor-widget-divider" data-id="efdbbe5" data-element_type="widget" data-widget_type="divider.default">
				<div class="elementor-widget-container">
							<div class="elementor-divider">
			<span class="elementor-divider-separator">
						</span>
		</div>
						</div>
				</div>
		<div class="elementor-element elementor-element-4bd7518 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="4bd7518" data-element_type="container">
		<div class="elementor-element elementor-element-83103a4 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="83103a4" data-element_type="container">
				<div class="elementor-element elementor-element-565e5b3 elementor-widget elementor-widget-heading" data-id="565e5b3" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">From</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-2c3fe3f elementor-widget elementor-widget-heading" data-id="2c3fe3f" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<span class="elementor-heading-title elementor-size-default">Weekdays (Mon-Thur)= 22500 PKR
Weekends (Fri-Sun)= 25500 PKR</span>				</div>
				</div>
				<div class="elementor-element elementor-element-7fb1f80 elementor-align-center elementor-widget elementor-widget-button" data-id="7fb1f80" data-element_type="widget" data-widget_type="button.default">
				<div class="elementor-widget-container">
									<div class="elementor-button-wrapper">
					<a class="elementor-button elementor-button-link elementor-size-sm" href="./product/glamp-flora-elegance-in-bloom/index.php">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">EXPLORE MORE</span>
					</span>
					</a>
				</div>
								</div>
				</div>
				</div>
				</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-c1d75bb e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="c1d75bb" data-element_type="container">
		<div class="elementor-element elementor-element-223d666 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="223d666" data-element_type="container">
				<div class="elementor-element elementor-element-856180c elementor-widget elementor-widget-image" data-id="856180c" data-element_type="widget" data-widget_type="image.default">
				<div class="elementor-widget-container">
																<a href="./product/1697/index.html">
							<img decoding="async" src="./wp-content/uploads/elementor/thumbs/DSC_0562-ezgif.com-webp-to-jpg-converter-rdmchq2g5yvf4kbbmhzpq0cdnn24dq3979oy1o0ux4.jpg" title="Glamp Aurora" alt="Glamp Aurora" class="elementor-animation-bob" loading="lazy">								</a>
															</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-4b42ddd e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="4b42ddd" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-de6c802 elementor-icon-list--layout-inline elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="de6c802" data-element_type="widget" data-widget_type="icon-list.default">
				<div class="elementor-widget-container">
							<ul class="elementor-icon-list-items elementor-inline-items">
							<li class="elementor-icon-list-item elementor-inline-item">
											<span class="elementor-icon-list-icon">
							<svg aria-hidden="true" class="e-font-icon-svg e-fas-bed" viewbox="0 0 640 512" xmlns="http://www.w3.org/2000/svg"><path d="M176 256c44.11 0 80-35.89 80-80s-35.89-80-80-80-80 35.89-80 80 35.89 80 80 80zm352-128H304c-8.84 0-16 7.16-16 16v144H64V80c0-8.84-7.16-16-16-16H16C7.16 64 0 71.16 0 80v352c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16v-48h512v48c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16V240c0-61.86-50.14-112-112-112z"></path></svg>						</span>
										<span class="elementor-icon-list-text">Per Night</span>
									</li>
								<li class="elementor-icon-list-item elementor-inline-item">
											<span class="elementor-icon-list-icon">
							<svg aria-hidden="true" class="e-font-icon-svg e-fas-restroom" viewbox="0 0 640 512" xmlns="http://www.w3.org/2000/svg"><path d="M128 128c35.3 0 64-28.7 64-64S163.3 0 128 0 64 28.7 64 64s28.7 64 64 64zm384 0c35.3 0 64-28.7 64-64S547.3 0 512 0s-64 28.7-64 64 28.7 64 64 64zm127.3 226.5l-45.6-185.8c-3.3-13.5-15.5-23-29.8-24.2-15 9.7-32.8 15.5-52 15.5-19.2 0-37-5.8-52-15.5-14.3 1.2-26.5 10.7-29.8 24.2l-45.6 185.8C381 369.6 393 384 409.2 384H464v104c0 13.3 10.7 24 24 24h48c13.3 0 24-10.7 24-24V384h54.8c16.2 0 28.2-14.4 24.5-29.5zM336 0h-32c-8.8 0-16 7.2-16 16v480c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V16c0-8.8-7.2-16-16-16zM180.1 144.4c-15 9.8-32.9 15.6-52.1 15.6-19.2 0-37.1-5.8-52.1-15.6C51.3 146.5 32 166.9 32 192v136c0 13.3 10.7 24 24 24h8v136c0 13.3 10.7 24 24 24h80c13.3 0 24-10.7 24-24V352h8c13.3 0 24-10.7 24-24V192c0-25.1-19.3-45.5-43.9-47.6z"></path></svg>						</span>
										<span class="elementor-icon-list-text">8</span>
									</li>
						</ul>
						</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-5ce1439 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="5ce1439" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-eb7dbcd elementor-widget elementor-widget-heading" data-id="eb7dbcd" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Glamp Aurora – A Celestial Escape</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-c3fdd4e elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="c3fdd4e" data-element_type="widget" data-widget_type="icon-list.default">
				<div class="elementor-widget-container">
							<ul class="elementor-icon-list-items">
							<li class="elementor-icon-list-item">
											<span class="elementor-icon-list-icon">
							<svg aria-hidden="true" class="e-font-icon-svg e-fas-map-marker-alt" viewbox="0 0 384 512" xmlns="http://www.w3.org/2000/svg"><path d="M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z"></path></svg>						</span>
										<span class="elementor-icon-list-text">Kuldana Hills, Murree</span>
									</li>
						</ul>
						</div>
				</div>
				<div class="elementor-element elementor-element-0910da4 elementor-widget-divider--view-line elementor-widget elementor-widget-divider" data-id="0910da4" data-element_type="widget" data-widget_type="divider.default">
				<div class="elementor-widget-container">
							<div class="elementor-divider">
			<span class="elementor-divider-separator">
						</span>
		</div>
						</div>
				</div>
		<div class="elementor-element elementor-element-4961110 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="4961110" data-element_type="container">
		<div class="elementor-element elementor-element-5da70e3 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="5da70e3" data-element_type="container">
				<div class="elementor-element elementor-element-8f45793 elementor-widget elementor-widget-heading" data-id="8f45793" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">From</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-35702fc elementor-widget elementor-widget-heading" data-id="35702fc" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<span class="elementor-heading-title elementor-size-default">Weekdays (Mon-Thur)= 25000 PKR
Weekends (Fri-Sun)= 28000 PKR</span>				</div>
				</div>
				<div class="elementor-element elementor-element-b912e80 elementor-align-center elementor-widget elementor-widget-button" data-id="b912e80" data-element_type="widget" data-widget_type="button.default">
				<div class="elementor-widget-container">
									<div class="elementor-button-wrapper">
					<a class="elementor-button elementor-button-link elementor-size-sm" href="./product/1697/index.php">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">EXPLORE MORE</span>
					</span>
					</a>
				</div>
								</div>
				</div>
				</div>
				</div>
				</div>
				</div>
				</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-fdc2fbc e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-parent" data-id="fdc2fbc" data-element_type="container">
		<div class="elementor-element elementor-element-431c2e0 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="431c2e0" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				</div>
		<div class="elementor-element elementor-element-1e3b1f8 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no elementor-invisible e-con e-child" data-id="1e3b1f8" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;animation&quot;:&quot;fadeInRight&quot;}">
		<div class="elementor-element elementor-element-b330488 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="b330488" data-element_type="container">
				<div class="elementor-element elementor-element-365923e elementor-widget elementor-widget-heading" data-id="365923e" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Why Choose Us</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-86c00e0 elementor-widget__width-inherit elementor-widget elementor-widget-heading" data-id="86c00e0" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">We Are Professional Planners for Your Vacations</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-e78a830 elementor-widget elementor-widget-text-editor" data-id="e78a830" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<p>Experience peaceful luxury and comfort at Pakistan’s most scenic glamping destinations. Every stay is designed to reconnect you with nature — without compromising on style or service.</p>								</div>
				</div>
		<div class="elementor-element elementor-element-dfebc5c e-grid e-con-full wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="dfebc5c" data-element_type="container">
		<div class="elementor-element elementor-element-87fa871 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="87fa871" data-element_type="container">
				<div class="elementor-element elementor-element-2a1f805 elementor-position-left elementor-view-default elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="2a1f805" data-element_type="widget" data-widget_type="icon-box.default">
				<div class="elementor-widget-container">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-hotel" viewbox="0 0 576 512" xmlns="http://www.w3.org/2000/svg"><path d="M560 64c8.84 0 16-7.16 16-16V16c0-8.84-7.16-16-16-16H16C7.16 0 0 7.16 0 16v32c0 8.84 7.16 16 16 16h15.98v384H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h240v-80c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v80h240c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16h-16V64h16zm-304 44.8c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4c0 6.4-6.4 12.8-12.8 12.8h-38.4c-6.4 0-12.8-6.4-12.8-12.8v-38.4zm0 96c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4c0 6.4-6.4 12.8-12.8 12.8h-38.4c-6.4 0-12.8-6.4-12.8-12.8v-38.4zm-128-96c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4c0 6.4-6.4 12.8-12.8 12.8h-38.4c-6.4 0-12.8-6.4-12.8-12.8v-38.4zM179.2 256h-38.4c-6.4 0-12.8-6.4-12.8-12.8v-38.4c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4c0 6.4-6.4 12.8-12.8 12.8zM192 384c0-53.02 42.98-96 96-96s96 42.98 96 96H192zm256-140.8c0 6.4-6.4 12.8-12.8 12.8h-38.4c-6.4 0-12.8-6.4-12.8-12.8v-38.4c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4zm0-96c0 6.4-6.4 12.8-12.8 12.8h-38.4c-6.4 0-12.8-6.4-12.8-12.8v-38.4c0-6.4 6.4-12.8 12.8-12.8h38.4c6.4 0 12.8 6.4 12.8 12.8v38.4z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span>
							Best Accommodation						</span>
					</h3>
				
									<p class="elementor-icon-box-description">
						High-end glamps with heating, Wi-Fi, and 5-star amenities.					</p>
				
			</div>
			
		</div>
						</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-3e4a03e e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="3e4a03e" data-element_type="container">
				<div class="elementor-element elementor-element-fe331b2 elementor-position-left elementor-view-default elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="fe331b2" data-element_type="widget" data-widget_type="icon-box.default">
				<div class="elementor-widget-container">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-map-signs" viewbox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M507.31 84.69L464 41.37c-6-6-14.14-9.37-22.63-9.37H288V16c0-8.84-7.16-16-16-16h-32c-8.84 0-16 7.16-16 16v16H56c-13.25 0-24 10.75-24 24v80c0 13.25 10.75 24 24 24h385.37c8.49 0 16.62-3.37 22.63-9.37l43.31-43.31c6.25-6.26 6.25-16.38 0-22.63zM224 496c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16V384h-64v112zm232-272H288v-32h-64v32H70.63c-8.49 0-16.62 3.37-22.63 9.37L4.69 276.69c-6.25 6.25-6.25 16.38 0 22.63L48 342.63c6 6 14.14 9.37 22.63 9.37H456c13.25 0 24-10.75 24-24v-80c0-13.25-10.75-24-24-24z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span>
							Beautiful Destination						</span>
					</h3>
				
									<p class="elementor-icon-box-description">
						Enjoy stunning views in Murree, Nathiagali, and beyond.					</p>
				
			</div>
			
		</div>
						</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-7d24c99 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="7d24c99" data-element_type="container">
				<div class="elementor-element elementor-element-60f7401 elementor-position-left elementor-view-default elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="60f7401" data-element_type="widget" data-widget_type="icon-box.default">
				<div class="elementor-widget-container">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-hand-holding-usd" viewbox="0 0 576 512" xmlns="http://www.w3.org/2000/svg"><path d="M271.06,144.3l54.27,14.3a8.59,8.59,0,0,1,6.63,8.1c0,4.6-4.09,8.4-9.12,8.4h-35.6a30,30,0,0,1-11.19-2.2c-5.24-2.2-11.28-1.7-15.3,2l-19,17.5a11.68,11.68,0,0,0-2.25,2.66,11.42,11.42,0,0,0,3.88,15.74,83.77,83.77,0,0,0,34.51,11.5V240c0,8.8,7.83,16,17.37,16h17.37c9.55,0,17.38-7.2,17.38-16V222.4c32.93-3.6,57.84-31,53.5-63-3.15-23-22.46-41.3-46.56-47.7L282.68,97.4a8.59,8.59,0,0,1-6.63-8.1c0-4.6,4.09-8.4,9.12-8.4h35.6A30,30,0,0,1,332,83.1c5.23,2.2,11.28,1.7,15.3-2l19-17.5A11.31,11.31,0,0,0,368.47,61a11.43,11.43,0,0,0-3.84-15.78,83.82,83.82,0,0,0-34.52-11.5V16c0-8.8-7.82-16-17.37-16H295.37C285.82,0,278,7.2,278,16V33.6c-32.89,3.6-57.85,31-53.51,63C227.63,119.6,247,137.9,271.06,144.3ZM565.27,328.1c-11.8-10.7-30.2-10-42.6,0L430.27,402a63.64,63.64,0,0,1-40,14H272a16,16,0,0,1,0-32h78.29c15.9,0,30.71-10.9,33.25-26.6a31.2,31.2,0,0,0,.46-5.46A32,32,0,0,0,352,320H192a117.66,117.66,0,0,0-74.1,26.29L71.4,384H16A16,16,0,0,0,0,400v96a16,16,0,0,0,16,16H372.77a64,64,0,0,0,40-14L564,377a32,32,0,0,0,1.28-48.9Z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span>
							Competitive Price						</span>
					</h3>
				
									<p class="elementor-icon-box-description">
						Clear weekday and weekend rates — no hidden costs.					</p>
				
			</div>
			
		</div>
						</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-ad571ad e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="ad571ad" data-element_type="container">
				<div class="elementor-element elementor-element-a588300 elementor-position-left elementor-view-default elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="a588300" data-element_type="widget" data-widget_type="icon-box.default">
				<div class="elementor-widget-container">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-campground" viewbox="0 0 640 512" xmlns="http://www.w3.org/2000/svg"><path d="M624 448h-24.68L359.54 117.75l53.41-73.55c5.19-7.15 3.61-17.16-3.54-22.35l-25.9-18.79c-7.15-5.19-17.15-3.61-22.35 3.55L320 63.3 278.83 6.6c-5.19-7.15-15.2-8.74-22.35-3.55l-25.88 18.8c-7.15 5.19-8.74 15.2-3.54 22.35l53.41 73.55L40.68 448H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h608c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16zM320 288l116.36 160H203.64L320 288z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span>
							Luxury Glamp						</span>
					</h3>
				
									<p class="elementor-icon-box-description">
						Bonfires, private lawns, and candlelight dinners.					</p>
				
			</div>
			
		</div>
						</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-676ae59 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="676ae59" data-element_type="container">
				<div class="elementor-element elementor-element-3e72418 elementor-position-left elementor-view-default elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="3e72418" data-element_type="widget" data-widget_type="icon-box.default">
				<div class="elementor-widget-container">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-headset" viewbox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M192 208c0-17.67-14.33-32-32-32h-16c-35.35 0-64 28.65-64 64v48c0 35.35 28.65 64 64 64h16c17.67 0 32-14.33 32-32V208zm176 144c35.35 0 64-28.65 64-64v-48c0-35.35-28.65-64-64-64h-16c-17.67 0-32 14.33-32 32v112c0 17.67 14.33 32 32 32h16zM256 0C113.18 0 4.58 118.83 0 256v16c0 8.84 7.16 16 16 16h16c8.84 0 16-7.16 16-16v-16c0-114.69 93.31-208 208-208s208 93.31 208 208h-.12c.08 2.43.12 165.72.12 165.72 0 23.35-18.93 42.28-42.28 42.28H320c0-26.51-21.49-48-48-48h-32c-26.51 0-48 21.49-48 48s21.49 48 48 48h181.72c49.86 0 90.28-40.42 90.28-90.28V256C507.42 118.83 398.82 0 256 0z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span>
							Support 24/7						</span>
					</h3>
				
									<p class="elementor-icon-box-description">
						Always here to help from booking to checkout.					</p>
				
			</div>
			
		</div>
						</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-4a37859 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="4a37859" data-element_type="container">
				<div class="elementor-element elementor-element-7f1d7a2 elementor-position-left elementor-view-default elementor-mobile-position-top elementor-widget elementor-widget-icon-box" data-id="7f1d7a2" data-element_type="widget" data-widget_type="icon-box.default">
				<div class="elementor-widget-container">
							<div class="elementor-icon-box-wrapper">

						<div class="elementor-icon-box-icon">
				<span class="elementor-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-user-tie" viewbox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm95.8 32.6L272 480l-32-136 32-56h-96l32 56-32 136-47.8-191.4C56.9 292 0 350.3 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-72.1-56.9-130.4-128.2-133.8z"></path></svg>				</span>
			</div>
			
						<div class="elementor-icon-box-content">

									<h3 class="elementor-icon-box-title">
						<span>
							Best Travel Guide						</span>
					</h3>
				
									<p class="elementor-icon-box-description">
						Trusted guides for a smooth, worry-free stay.					</p>
				
			</div>
			
		</div>
						</div>
				</div>
				</div>
				</div>
				</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-835d99a elementor-hidden-desktop elementor-hidden-tablet elementor-hidden-mobile e-flex e-con-boxed wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-parent" data-id="835d99a" data-element_type="container">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-04fd54f e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="04fd54f" data-element_type="container">
				<div class="elementor-element elementor-element-0878928 elementor-widget elementor-widget-heading" data-id="0878928" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Our Process</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-2e20544 elementor-widget__width-inherit elementor-widget elementor-widget-heading" data-id="2e20544" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">We Complete Every Step Carefully</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-52a870b elementor-widget elementor-widget-text-editor" data-id="52a870b" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<p>From booking your favorite glamp to relaxing under the stars — we handle everything with care, so you can just enjoy your getaway. Experience comfort, peace, and adventure — all in one unforgettable stay.</p>								</div>
				</div>
		<div class="elementor-element elementor-element-3b3ce62 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no elementor-invisible e-con e-child" data-id="3b3ce62" data-element_type="container" data-settings="{&quot;animation&quot;:&quot;bounce&quot;}">
		<div class="elementor-element elementor-element-16b4273 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="16b4273" data-element_type="container">
				<div class="elementor-element elementor-element-858dcaf elementor-widget elementor-widget-image" data-id="858dcaf" data-element_type="widget" data-widget_type="image.default">
				<div class="elementor-widget-container">
															<img fetchpriority="high" decoding="async" width="960" height="960" src="./wp-content/uploads/2025/10/wmremove-transformed-10_11zon.png" class="attachment-large size-large wp-image-1442" alt="" srcset="./wp-content/uploads/2025/10/wmremove-transformed-10_11zon.png 1024w, ./wp-content/uploads/2025/10/wmremove-transformed-10_11zon-300x300.png 300w, ./wp-content/uploads/2025/10/wmremove-transformed-10_11zon-100x100.png 100w, ./wp-content/uploads/2025/10/wmremove-transformed-10_11zon-600x600.png 600w, ./wp-content/uploads/2025/10/wmremove-transformed-10_11zon-150x150.png 150w, ./wp-content/uploads/2025/10/wmremove-transformed-10_11zon-768x768.png 768w" sizes="(max-width: 960px) 100vw, 960px">															</div>
				</div>
				<div class="elementor-element elementor-element-70243da elementor-widget__width-inherit elementor-widget elementor-widget-heading" data-id="70243da" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Choose Your Glamp</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-131a381 elementor-widget elementor-widget-text-editor" data-id="131a381" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<p>Pick your favorite luxury glamp across scenic locations.</p>								</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-f8a6295 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="f8a6295" data-element_type="container">
				<div class="elementor-element elementor-element-674b27f elementor-widget elementor-widget-image" data-id="674b27f" data-element_type="widget" data-widget_type="image.default">
				<div class="elementor-widget-container">
															<img decoding="async" width="105" height="40" src="./wp-content/uploads/2025/10/arrow.png" class="attachment-large size-large wp-image-1443" alt="">															</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-6e6e85d e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="6e6e85d" data-element_type="container">
				<div class="elementor-element elementor-element-b1dfbf0 elementor-widget elementor-widget-image" data-id="b1dfbf0" data-element_type="widget" data-widget_type="image.default">
				<div class="elementor-widget-container">
															<img loading="lazy" decoding="async" width="960" height="960" src="./wp-content/uploads/2025/10/wmremove-transformed-12_11zon.png" class="attachment-large size-large wp-image-1458" alt="" srcset="./wp-content/uploads/2025/10/wmremove-transformed-12_11zon.png 1024w, ./wp-content/uploads/2025/10/wmremove-transformed-12_11zon-300x300.png 300w, ./wp-content/uploads/2025/10/wmremove-transformed-12_11zon-100x100.png 100w, ./wp-content/uploads/2025/10/wmremove-transformed-12_11zon-600x600.png 600w, ./wp-content/uploads/2025/10/wmremove-transformed-12_11zon-150x150.png 150w, ./wp-content/uploads/2025/10/wmremove-transformed-12_11zon-768x768.png 768w" sizes="(max-width: 960px) 100vw, 960px">															</div>
				</div>
				<div class="elementor-element elementor-element-0af4dfa elementor-widget__width-inherit elementor-widget elementor-widget-heading" data-id="0af4dfa" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Secure Booking</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-81aab67 elementor-widget elementor-widget-text-editor" data-id="81aab67" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<p>Confirm easily with a quick and safe payment.</p>								</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-d6c559e e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="d6c559e" data-element_type="container">
				<div class="elementor-element elementor-element-4615a70 elementor-widget elementor-widget-image" data-id="4615a70" data-element_type="widget" data-widget_type="image.default">
				<div class="elementor-widget-container">
															<img loading="lazy" decoding="async" width="106" height="46" src="./wp-content/uploads/2025/10/arrow-2.png" class="attachment-large size-large wp-image-1444" alt="">															</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-3f1b304 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="3f1b304" data-element_type="container">
				<div class="elementor-element elementor-element-e11ae59 elementor-widget elementor-widget-image" data-id="e11ae59" data-element_type="widget" data-widget_type="image.default">
				<div class="elementor-widget-container">
															<img loading="lazy" decoding="async" width="960" height="960" src="./wp-content/uploads/2025/10/wmremove-transformed-11_11zon.png" class="attachment-large size-large wp-image-1457" alt="" srcset="./wp-content/uploads/2025/10/wmremove-transformed-11_11zon.png 1024w, ./wp-content/uploads/2025/10/wmremove-transformed-11_11zon-300x300.png 300w, ./wp-content/uploads/2025/10/wmremove-transformed-11_11zon-100x100.png 100w, ./wp-content/uploads/2025/10/wmremove-transformed-11_11zon-600x600.png 600w, ./wp-content/uploads/2025/10/wmremove-transformed-11_11zon-150x150.png 150w, ./wp-content/uploads/2025/10/wmremove-transformed-11_11zon-768x768.png 768w" sizes="(max-width: 960px) 100vw, 960px">															</div>
				</div>
				<div class="elementor-element elementor-element-cfe8562 elementor-widget__width-inherit elementor-widget elementor-widget-heading" data-id="cfe8562" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Get Confirmation</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-06c42a5 elementor-widget elementor-widget-text-editor" data-id="06c42a5" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<p>Receive instant booking details via email.</p>								</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-40f7cb7 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="40f7cb7" data-element_type="container">
				<div class="elementor-element elementor-element-91c87d1 elementor-widget elementor-widget-image" data-id="91c87d1" data-element_type="widget" data-widget_type="image.default">
				<div class="elementor-widget-container">
															<img decoding="async" width="105" height="40" src="./wp-content/uploads/2025/10/arrow.png" class="attachment-large size-large wp-image-1443" alt="">															</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-4513d29 e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child" data-id="4513d29" data-element_type="container">
				<div class="elementor-element elementor-element-4e3b1d6 elementor-widget elementor-widget-image" data-id="4e3b1d6" data-element_type="widget" data-widget_type="image.default">
				<div class="elementor-widget-container">
															<img loading="lazy" decoding="async" width="960" height="960" src="./wp-content/uploads/2025/10/Gemini_Generated_Image_909adg909adg909a-3.jpg" class="attachment-large size-large wp-image-1459" alt="" srcset="./wp-content/uploads/2025/10/Gemini_Generated_Image_909adg909adg909a-3.jpg 1024w, ./wp-content/uploads/2025/10/Gemini_Generated_Image_909adg909adg909a-3-300x300.jpg 300w, ./wp-content/uploads/2025/10/Gemini_Generated_Image_909adg909adg909a-3-100x100.jpg 100w, ./wp-content/uploads/2025/10/Gemini_Generated_Image_909adg909adg909a-3-600x600.jpg 600w, ./wp-content/uploads/2025/10/Gemini_Generated_Image_909adg909adg909a-3-150x150.jpg 150w, ./wp-content/uploads/2025/10/Gemini_Generated_Image_909adg909adg909a-3-768x768.jpg 768w" sizes="(max-width: 960px) 100vw, 960px">															</div>
				</div>
				<div class="elementor-element elementor-element-637eded elementor-widget__width-inherit elementor-widget elementor-widget-heading" data-id="637eded" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Enjoy Your Stay</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-de6a15e elementor-widget elementor-widget-text-editor" data-id="de6a15e" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<p>Relax and experience peaceful luxury close to nature.</p>								</div>
				</div>
				</div>
				</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-bcdb723 elementor-hidden-desktop elementor-hidden-tablet elementor-hidden-mobile e-flex e-con-boxed wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-parent" data-id="bcdb723" data-element_type="container">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-e2acb2b elementor-invisible elementor-widget elementor-widget-text-editor" data-id="e2acb2b" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomInUp&quot;}" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<section style="background-color: #fff; padding: 100px 0; font-family: 'Spinnaker', sans-serif;">
  <div style="max-width: 1100px; margin: 0 auto; text-align: center;">

    <!-- Shared Logo -->
    <div style="margin-bottom: 50px;">
      <img decoding="async" style="width: 280px; display: block; margin: 0 auto 25px auto;" src="./wp-content/uploads/2025/10/Copy-of-Copy-of-Add-a-heading__3_-removebg-preview.png" alt="Wanderlust Logo">
      <h2 style="font-size: 34px; color: #1e2b32; font-weight: bold;">Our Journey</h2>
      <p style="font-size: 16px; color: #555; max-width: 700px; margin: 12px auto 0 auto; line-height: 1.7;">
        Wanderlust Pakistan is the parent brand behind Glamplex.pk — together redefining glamping and travel across Pakistan.
      </p>
    </div>

    <!-- Cards and Arrow Connection -->
    <div style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 40px; margin-top: 60px;">

      <!-- Parent Brand Card -->
      <div style="background: #fff; border: 1px solid #eee; border-radius: 20px; width: 310px; padding: 40px 25px; box-shadow: 0 6px 20px rgba(0,0,0,0.05); transition: all 0.3s ease;">
        <h3 style="font-size: 22px; color: #00a693; font-weight: 600; margin-bottom: 15px;">Wanderlust Pakistan</h3>
        <p style="font-size: 15px; color: #555; line-height: 1.7;">
          The parent brand curating premium glamping and nature-inspired travel experiences all over Pakistan.
        </p>
      </div>

      <!-- Arrow -->
      <div style="flex: 0 0 100px; display: flex; align-items: center; justify-content: center;"></div>

      <!-- Child Brand Card (Clickable) -->
      <a href="https://glamplex.pk/" target="_blank" style="text-decoration: none;">
        <div style="background: #fff; border: 1px solid #eee; border-radius: 20px; width: 310px; padding: 40px 25px; box-shadow: 0 6px 20px rgba(0,0,0,0.05); transition: all 0.3s ease; text-align: center;">
          <img decoding="async" style="width: 180px; display: block; margin: 0 auto 25px auto;" src="./wp-content/uploads/2025/10/Glamplex-Logo.webp" alt="Glamplex Logo">
          <h3 style="font-size: 22px; color: #00a693; font-weight: 600; margin-bottom: 15px;">Glamplex.pk</h3>
          <p style="font-size: 15px; color: #555; line-height: 1.7; margin-bottom: 25px;">
            The child platform of Wanderlust — helping travelers book peaceful and luxury glamps at scenic destinations.
          </p>
          <span class="visit-btn" style="color: #fff; background-color: #00a693; padding: 10px 26px; border-radius: 25px; font-size: 14px; font-weight: 600; display: inline-block; transition: background-color 0.3s ease;">
            Visit Glamplex.pk
          </span>
        </div>
      </a>

    </div>

    <!-- Description -->
    <div style="margin-top: 60px; max-width: 700px; margin-left: auto; margin-right: auto;">
      <p style="font-size: 15px; color: #333; line-height: 1.7;">
        Both brands share one identity and one mission — bringing comfort, style, and nature together for a truly luxurious travel experience.
      </p>
    </div>

  </div>
</section>

<style>
  /* Hover effect for cards */
  section div[style*="box-shadow"]:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  }

  /* Button hover effect */
  .visit-btn:hover {
    background-color: #00816e !important;
  }

  /* Responsive */
  @media (max-width: 768px) {
    section div[style*="display: flex; align-items: center; justify-content: center;"] {
      flex-direction: column;
      gap: 40px;
    }
    section svg {
      transform: rotate(90deg);
    }
  }
</style>
								</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-8e8e5b4 e-flex e-con-boxed wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-parent" data-id="8e8e5b4" data-element_type="container">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-297c3f4 elementor-widget elementor-widget-heading" data-id="297c3f4" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Best Destination</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-e260788 elementor-widget__width-inherit elementor-widget elementor-widget-heading" data-id="e260788" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Discover Our Beautiful Glamps Across Pakistan</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-d2aac71 elementor-widget elementor-widget-text-editor" data-id="d2aac71" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<p>Let Wanderlust take you to calm and scenic places across Pakistan. Experience glamping where nature, peace, and comfort come together beautifully.</p>								</div>
				</div>
				<div class="elementor-element elementor-element-d440ae4 elementor-invisible elementor-widget elementor-widget-text-editor" data-id="d440ae4" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomInUp&quot;}" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<style>
/* ===== GLAMPS GALLERY STYLE ===== */

/* Container styling */
.glamps-gallery {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 25px;
  margin: 50px 0;
}

/* Each row styling */
.glamps-row {
  display: flex;
  justify-content: center;
  gap: 20px;
  flex-wrap: wrap;
}

/* Each card */
.glamps-card {
  position: relative;
  width: 220px;
  height: 300px;
  border-radius: 15px;
  overflow: hidden;
  cursor: pointer;
  transition: transform 0.4s ease;
  display: block;
  text-decoration: none;
}

/* Hover zoom effect */
.glamps-card:hover {
  transform: scale(1.05);
}

/* Card image */
.glamps-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 15px;
  display: block;
}

/* Text overlay */
.glamps-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 40%;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
  color: #fff;
  display: flex;
  align-items: flex-end;
  justify-content: center;
  font-size: 18px;
  font-weight: bold;
  border-radius: 0 0 15px 15px;
  padding-bottom: 10px;
  text-align: center;
}
</style>

<!-- ===== GLAMPS GALLERY SECTION ===== -->
<div class="glamps-gallery">

  <!-- Top Row (3 images) -->
  <div class="glamps-row">

    <a class="glamps-card" href="./wander-stays/index.html" target="_blank" rel="noopener">
      <img decoding="async" src="./wp-content/uploads/2025/10/DeWatermark.ai_1761405563279-1.jpeg" alt="Luxury Tent Glamp">
      <div class="glamps-overlay">Luxury Tent Glamp</div>
    </a>

    <a class="glamps-card" href="./wander-stays/index.html" target="_blank" rel="noopener">
      <img decoding="async" src="./wp-content/uploads/2025/10/DeWatermark.ai_1761405586381.jpeg" alt="Mountain View Glamp">
      <div class="glamps-overlay">Mountain View Glamp</div>
    </a>

    <a class="glamps-card" href="./wander-stays/index.html" target="_blank" rel="noopener">
      <img decoding="async" src="./wp-content/uploads/2025/10/Gemini_Generated_Image_10z6dr10z6dr10z6.png" alt="Sky Dome Glamp">
      <div class="glamps-overlay">Sky Dome Glamp</div>
    </a>

  </div>

  <!-- Bottom Row (2 images) -->
  <div class="glamps-row">

    <a class="glamps-card" href="./wander-stays/index.html" target="_blank" rel="noopener">
      <img decoding="async" src="./wp-content/uploads/2025/10/DeWatermark.ai_1761406526997.jpeg" alt="Lake Side Glamp">
      <div class="glamps-overlay">Lake Side Glamp</div>
    </a>

    <a class="glamps-card" href="./wander-stays/index.html" target="_blank" rel="noopener">
      <img decoding="async" src="./wp-content/uploads/2025/10/DeWatermark.ai_1761406765832.jpeg" alt="Forest Cabin Glamp">
      <div class="glamps-overlay">Forest Cabin Glamp</div>
    </a>

  </div>

</div>
								</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-6f44a0e e-flex e-con-boxed wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-parent" data-id="6f44a0e" data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-359fd66 elementor-widget__width-initial elementor-invisible elementor-widget elementor-widget-heading" data-id="359fd66" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInLeft&quot;}" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Ready to Experience Glamping in Pakistan?</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-0cec0c4 elementor-invisible elementor-widget elementor-widget-text-editor" data-id="0cec0c4" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;slideInLeft&quot;}" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<p>Discover beautiful destinations, luxury glamps, and peaceful escapes — all planned and managed with care by Wanderlust Pakistan.</p>								</div>
				</div>
				<div class="elementor-element elementor-element-df7f96b elementor-align-center elementor-invisible elementor-widget elementor-widget-button" data-id="df7f96b" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;bounceIn&quot;}" data-widget_type="button.default">
				<div class="elementor-widget-container">
									<div class="elementor-button-wrapper">
					<a class="elementor-button elementor-button-link elementor-size-sm" href="tel:+923010528888">
						<span class="elementor-button-content-wrapper">
						<span class="elementor-button-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-phone-alt" viewbox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M497.39 361.8l-112-48a24 24 0 0 0-28 6.9l-49.6 60.6A370.66 370.66 0 0 1 130.6 204.11l60.6-49.6a23.94 23.94 0 0 0 6.9-28l-48-112A24.16 24.16 0 0 0 122.6.61l-104 24A24 24 0 0 0 0 48c0 256.5 207.9 464 464 464a24 24 0 0 0 23.4-18.6l24-104a24.29 24.29 0 0 0-14.01-27.6z"></path></svg>			</span>
									<span class="elementor-button-text">Book Your Stay Now</span>
					</span>
					</a>
				</div>
								</div>
				</div>
					</div>
				</div>
				</div>
		<style>.elementor-287 .elementor-element.elementor-element-f3041db{margin-top:100px;margin-bottom:0px;}.elementor-widget-heading .elementor-heading-title{font-family:var( --e-global-typography-primary-font-family ), Sans-serif;font-weight:var( --e-global-typography-primary-font-weight );color:var( --e-global-color-primary );}.elementor-287 .elementor-element.elementor-element-5aa292ec{text-align:left;}.elementor-287 .elementor-element.elementor-element-5aa292ec .elementor-heading-title{font-family:"Playfair Display", Sans-serif;font-size:18px;font-weight:600;letter-spacing:1px;color:#2F2F2F;}.elementor-widget-text-editor{font-family:var( --e-global-typography-text-font-family ), Sans-serif;font-weight:var( --e-global-typography-text-font-weight );color:var( --e-global-color-text );}.elementor-widget-text-editor.elementor-drop-cap-view-stacked .elementor-drop-cap{background-color:var( --e-global-color-primary );}.elementor-widget-text-editor.elementor-drop-cap-view-framed .elementor-drop-cap, .elementor-widget-text-editor.elementor-drop-cap-view-default .elementor-drop-cap{color:var( --e-global-color-primary );border-color:var( --e-global-color-primary );}.elementor-287 .elementor-element.elementor-element-2c811db3{width:auto;max-width:auto;font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:400;letter-spacing:0.4px;color:#6D6D6D;}.elementor-287 .elementor-element.elementor-element-2c811db3 a{color:#6D6D6D;}.elementor-287 .elementor-element.elementor-element-2c811db3 a:hover, .elementor-287 .elementor-element.elementor-element-2c811db3 a:focus{color:#2EA5B4;}.elementor-287 .elementor-element.elementor-element-29309008{text-align:left;}.elementor-287 .elementor-element.elementor-element-29309008 .elementor-heading-title{font-family:"Playfair Display", Sans-serif;font-size:18px;font-weight:600;letter-spacing:1px;color:#2F2F2F;}.elementor-287 .elementor-element.elementor-element-47d804af{text-align:left;font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:400;line-height:30px;letter-spacing:0.4px;}.elementor-287 .elementor-element.elementor-element-5d618721{text-align:left;}.elementor-287 .elementor-element.elementor-element-5d618721 .elementor-heading-title{font-family:"Playfair Display", Sans-serif;font-size:18px;font-weight:600;letter-spacing:1px;color:#2F2F2F;}.elementor-287 .elementor-element.elementor-element-32b924e{text-align:left;font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:400;line-height:22px;letter-spacing:0.4px;}.elementor-287 .elementor-element.elementor-element-58c76c86{--grid-template-columns:repeat(0, auto);--icon-size:15px;--grid-column-gap:20px;--grid-row-gap:0px;}.elementor-287 .elementor-element.elementor-element-58c76c86 .elementor-widget-container{text-align:left;}.elementor-287 .elementor-element.elementor-element-58c76c86 > .elementor-widget-container{margin:-13px 0px 0px 0px;}.elementor-287 .elementor-element.elementor-element-58c76c86 .elementor-social-icon{background-color:#02010100;--icon-padding:0em;}.elementor-287 .elementor-element.elementor-element-58c76c86 .elementor-social-icon i{color:#2F2F2F;}.elementor-287 .elementor-element.elementor-element-58c76c86 .elementor-social-icon svg{fill:#2F2F2F;}.elementor-287 .elementor-element.elementor-element-58c76c86 .elementor-social-icon:hover{background-color:#8783FF00;}.elementor-287 .elementor-element.elementor-element-58c76c86 .elementor-social-icon:hover i{color:#EA6FFB;}.elementor-287 .elementor-element.elementor-element-58c76c86 .elementor-social-icon:hover svg{fill:#EA6FFB;}.elementor-widget-divider{--divider-color:var( --e-global-color-secondary );}.elementor-widget-divider .elementor-divider__text{color:var( --e-global-color-secondary );font-family:var( --e-global-typography-secondary-font-family ), Sans-serif;font-weight:var( --e-global-typography-secondary-font-weight );}.elementor-widget-divider.elementor-view-stacked .elementor-icon{background-color:var( --e-global-color-secondary );}.elementor-widget-divider.elementor-view-framed .elementor-icon, .elementor-widget-divider.elementor-view-default .elementor-icon{color:var( --e-global-color-secondary );border-color:var( --e-global-color-secondary );}.elementor-widget-divider.elementor-view-framed .elementor-icon, .elementor-widget-divider.elementor-view-default .elementor-icon svg{fill:var( --e-global-color-secondary );}.elementor-287 .elementor-element.elementor-element-b819f0c{--divider-border-style:solid;--divider-color:#E8E8E8;--divider-border-width:1px;}.elementor-287 .elementor-element.elementor-element-b819f0c .elementor-divider-separator{width:100%;}.elementor-287 .elementor-element.elementor-element-b819f0c .elementor-divider{padding-block-start:15px;padding-block-end:15px;}.elementor-287 .elementor-element.elementor-element-050573a > .elementor-widget-container{margin:-5px 0px 0px 0px;padding:0px 0px 20px 0px;}.elementor-287 .elementor-element.elementor-element-050573a{text-align:center;font-family:"Open Sans", Sans-serif;font-size:14px;font-weight:400;letter-spacing:0.4px;}@media(max-width:1024px){.elementor-287 .elementor-element.elementor-element-f3041db{padding:0px 40px 0px 40px;}}</style>		<div data-elementor-type="wp-post" data-elementor-id="287" class="elementor elementor-287">
						<section class="elementor-section elementor-top-section elementor-element elementor-element-f3041db elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="f3041db" data-element_type="section">
						<div class="elementor-container elementor-column-gap-default">
					<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-73dc660" data-id="73dc660" data-element_type="column">
			<div class="elementor-widget-wrap elementor-element-populated">
						<section class="elementor-section elementor-inner-section elementor-element elementor-element-374ec39 elementor-section-boxed elementor-section-height-default elementor-section-height-default wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no" data-id="374ec39" data-element_type="section">
						<div class="elementor-container elementor-column-gap-default">
					<div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-58b2e37" data-id="58b2e37" data-element_type="column">
			<div class="elementor-widget-wrap elementor-element-populated">
						<div class="elementor-element elementor-element-5aa292ec elementor-widget elementor-widget-heading" data-id="5aa292ec" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">QUICK LINKS</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-2c811db3 elementor-widget__width-auto elementor-widget elementor-widget-text-editor" data-id="2c811db3" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<p><a href="./index.php">HOME</a></p><p><a href="./wander-stays/index.html">WANDER STAYS</a></p><p><a href="./contact-us/index.php">CONTACT US</a></p>								</div>
				</div>
					</div>
		</div>
				<div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-6337cb8" data-id="6337cb8" data-element_type="column">
			<div class="elementor-widget-wrap elementor-element-populated">
						<div class="elementor-element elementor-element-29309008 elementor-widget elementor-widget-heading" data-id="29309008" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">CONTACT INFO</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-47d804af elementor-widget elementor-widget-text-editor" data-id="47d804af" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<p>Kuldana Hills, Murree, Pakistan<br><i class="fas fa-phone-alt" style="color: #000000;"></i> +92 301 0528888<br>Glamplex.pk@gmail.com</p>								</div>
				</div>
					</div>
		</div>
				<div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-95eb6f7" data-id="95eb6f7" data-element_type="column">
			<div class="elementor-widget-wrap elementor-element-populated">
						<div class="elementor-element elementor-element-5d618721 elementor-widget elementor-widget-heading" data-id="5d618721" data-element_type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Follow Us</h2>				</div>
				</div>
				<div class="elementor-element elementor-element-32b924e elementor-widget elementor-widget-text-editor" data-id="32b924e" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<p>Wanderlust Pakistan brings you a luxurious escape into nature — from cozy glamps to breathtaking villas nestled in the heart of Murree. Experience comfort, adventure, and relaxation — all in one unforgettable stay.</p>								</div>
				</div>
				<div class="elementor-element elementor-element-58c76c86 e-grid-align-left elementor-shape-rounded elementor-grid-0 elementor-widget elementor-widget-social-icons" data-id="58c76c86" data-element_type="widget" data-widget_type="social-icons.default">
				<div class="elementor-widget-container">
							<div class="elementor-social-icons-wrapper elementor-grid" role="list">
							<span class="elementor-grid-item" role="listitem">
					<a class="elementor-icon elementor-social-icon elementor-social-icon-instagram elementor-animation-grow-rotate elementor-repeater-item-27ff064" href="https://www.instagram.com/glamplex.pk/?utm_source=ig_embed&amp;ig_rid=2ae07832-3217-41fd-9f47-22dfd1328325" target="_blank">
						<span class="elementor-screen-only">Instagram</span>
						<svg aria-hidden="true" class="e-font-icon-svg e-fab-instagram" viewbox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path></svg>					</a>
				</span>
							<span class="elementor-grid-item" role="listitem">
					<a class="elementor-icon elementor-social-icon elementor-social-icon-whatsapp elementor-animation-grow-rotate elementor-repeater-item-2dfe62f" href="https://api.whatsapp.com/send?phone=923010528888" target="_blank">
						<span class="elementor-screen-only">Whatsapp</span>
						<svg aria-hidden="true" class="e-font-icon-svg e-fab-whatsapp" viewbox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path></svg>					</a>
				</span>
							<span class="elementor-grid-item" role="listitem">
					<a class="elementor-icon elementor-social-icon elementor-social-icon-envelope elementor-animation-grow-rotate elementor-repeater-item-97899e7" href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;to=Glamplex.pk@gmail.com" target="_blank">
						<span class="elementor-screen-only">Envelope</span>
						<svg aria-hidden="true" class="e-font-icon-svg e-fas-envelope" viewbox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"></path></svg>					</a>
				</span>
					</div>
						</div>
				</div>
					</div>
		</div>
					</div>
		</section>
				<div class="elementor-element elementor-element-b819f0c elementor-widget-divider--view-line elementor-widget elementor-widget-divider" data-id="b819f0c" data-element_type="widget" data-widget_type="divider.default">
				<div class="elementor-widget-container">
							<div class="elementor-divider">
			<span class="elementor-divider-separator">
						</span>
		</div>
						</div>
				</div>
				<div class="elementor-element elementor-element-050573a elementor-widget elementor-widget-text-editor" data-id="050573a" data-element_type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									<div style="display: flex; justify-content: space-between; align-items: center; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 0;">

  <div style="color: #7A7A7A;">
    Developed by 
    <a href="#" target="_blank" style="color: #2EA5B4; text-decoration: none;">Abdullah & Aqeel</a>
  </div>

  <div>
    <a href="./privacy-policy/index.html" style="color: #2EA5B4; text-decoration: none;">Privacy Policy</a> |
    <a href="./terms-conditions/index.html" style="color: #2EA5B4; text-decoration: none;">Terms &amp; Conditions</a>
  </div>

</div>
								</div>
				</div>
					</div>
		</div>
					</div>
		</section>
				</div>
		<script type="speculationrules">
{"prefetch":[{"source":"document","where":{"and":[{"href_matches":"\/*"},{"not":{"href_matches":["\/wp-*.php","\/wp-admin\/*","\/wp-content\/uploads\/*","\/wp-content\/*","\/wp-content\/plugins\/*","\/wp-content\/themes\/royal-elementor-kit\/*","\/*\\?(.+)"]}},{"not":{"selector_matches":"a[rel~=\"nofollow\"]"}},{"not":{"selector_matches":".no-prefetch, .no-prefetch a"}}]},"eagerness":"conservative"}]}
</script>

<div class="xoo-el-container xoo-el-style-popup" style="visibility: hidden;">
    <div class="xoo-el-opac"></div>
    <div class="xoo-el-modal">
        <div class="xoo-el-inmodal">
            <span class="xoo-el-close xoo-el-icon-cross"></span>
            <div class="xoo-el-wrap">
                <div class="xoo-el-sidebar"></div>
                <div class="xoo-el-srcont">
                    <div class="xoo-el-main">

                        <div class="xoo-el-form-container xoo-el-form-popup" data-active="login">

                            <div class="xoo-el-header">
                                <ul class="xoo-el-tabs">
                                    <li data-tab="login" class="xoo-el-login-tgr" style="order: 1">Login</li>
                                    <li data-tab="register" class="xoo-el-reg-tgr" style="order: 2">Sign Up</li>
                                </ul>
                            </div>

                             <!-- =================== LOGIN SECTION =================== -->
<div data-section="login" class="xoo-el-section">
    <div class="xoo-el-fields">
        <div class="xoo-el-notice"></div>

        <!-- form_type = login -->
        <form class="xoo-el-form-login" method="post" action="">
            <input type="hidden" name="form_type" value="login">

            <div class="xoo-el-fields-cont">

                <!-- Username / Email -->
                <div class="xoo-aff-group xoo-aff-cont-text one xoo-aff-cont-required xoo-el-username_cont">
                    <div class="xoo-aff-input-group">
                        <span class="xoo-aff-input-icon fas fa-user-plus"></span>
                        <input
                            type="text"
                            class="xoo-aff-required xoo-aff-text"
                            name="xoo_el_username"
                            placeholder="Username / Email"
                            autocomplete="username"
                            required>
                    </div>
                </div>

                <!-- Password -->
                <div class="xoo-aff-group xoo-aff-cont-password one xoo-aff-cont-required xoo-el-password_cont">
                    <div class="xoo-aff-input-group">
                        <span class="xoo-aff-input-icon fas fa-key"></span>
                        <input
                            type="password"
                            class="xoo-aff-required xoo-aff-password"
                            name="xoo_el_password"
                            placeholder="Password"
                            autocomplete="current-password"
                            minlength="6"
                            required>
                    </div>
                </div>

            </div>

            <div class="xoo-aff-group xoo-el-login-btm-fields">
                <label class="xoo-el-form-label">
                    <input type="checkbox" name="remember_me" value="1">
                    <span>Remember me</span>
                </label>
                <a class="xoo-el-lostpw-tgr" rel="nofollow" href="#">Forgot Password?</a>
            </div>

            <button type="submit" class="button btn xoo-el-action-btn xoo-el-login-btn">
                Sign in
            </button>
        </form>

    </div>
</div>

<!-- =================== REGISTER SECTION =================== -->
<div data-section="register" class="xoo-el-section">
    <div class="xoo-el-fields">
        <div class="xoo-el-notice"></div>

        <!-- PHP-based register form -->
        <form class="xoo-el-form-register" method="post" action="">
            <input type="hidden" name="form_type" value="register">

            <div class="xoo-el-fields-cont">

                <!-- Email -->
                <div class="xoo-aff-group xoo-aff-cont-email one xoo-aff-cont-required xoo_el_reg_email_cont">
                    <div class="xoo-aff-input-group">
                        <span class="xoo-aff-input-icon fas fa-at"></span>
                        <input
                            type="email"
                            class="xoo-aff-required xoo-aff-email"
                            name="xoo_el_reg_email"
                            placeholder="Email"
                            autocomplete="email"
                            required>
                    </div>
                </div>

                <!-- Password -->
                <div class="xoo-aff-group xoo-aff-cont-password one xoo-aff-cont-required xoo_el_reg_pass_cont">
                    <div class="xoo-aff-input-group">
                        <span class="xoo-aff-input-icon fas fa-key"></span>
                        <input
                            type="password"
                            class="xoo-aff-required xoo-aff-password"
                            name="xoo_el_reg_pass"
                            placeholder="Password"
                            minlength="6"
                            maxlength="20"
                            autocomplete="new-password"
                            required>
                    </div>
                </div>

                <!-- Confirm Password (IMPORTANT: xoo-el-reg-pass-confirm) -->
                <div class="xoo-aff-group xoo-aff-cont-password one xoo-aff-cont-required xoo_el_reg_pass_again_cont">
                    <div class="xoo-aff-input-group">
                        <span class="xoo-aff-input-icon fas fa-key"></span>
                        <input
                            type="password"
                            class="xoo-aff-required xoo-aff-password xoo-el-reg-pass-confirm"
                            name="xoo_el_reg_pass_again"
                            placeholder="Confirm Password"
                            autocomplete="new-password"
                            required>
                    </div>
                </div>

                <!-- First Name -->
                <div class="xoo-aff-group xoo-aff-cont-text onehalf xoo-aff-cont-required xoo_el_reg_fname_cont">
                    <div class="xoo-aff-input-group">
                        <span class="xoo-aff-input-icon far fa-user"></span>
                        <input
                            type="text"
                            class="xoo-aff-required xoo-aff-text"
                            name="xoo_el_reg_fname"
                            placeholder="First Name"
                            required>
                    </div>
                </div>

                <!-- Last Name -->
                <div class="xoo-aff-group xoo-aff-cont-text onehalf xoo-aff-cont-required xoo_el_reg_lname_cont">
                    <div class="xoo-aff-input-group">
                        <span class="xoo-aff-input-icon far fa-user"></span>
                        <input
                            type="text"
                            class="xoo-aff-required xoo-aff-text"
                            name="xoo_el_reg_lname"
                            placeholder="Last Name"
                            required>
                    </div>
                </div>

                <!-- Terms checkbox  -->
                <div class="xoo-aff-group xoo-aff-cont-checkbox_single one xoo-aff-cont-required xoo_el_reg_terms_cont">
                    <div class="xoo-aff-required xoo-aff-checkbox_single">
                        <label>
                            <input
                                type="checkbox"
                                name="xoo_el_reg_terms"
                                class="xoo-aff-required xoo-aff-checkbox_single"
                                value="yes"
                                required>
                            I accept the
                            <a href="./index.php?page_id=3" target="_blank">
                                Terms of Service and Privacy Policy
                            </a>
                        </label>
                    </div>
                </div>

            </div>

            <button type="submit" class="button btn xoo-el-action-btn xoo-el-register-btn">
                Sign Up
            </button>
        </form>

                                </div>
                            </div>

                            <!-- =================== LOST PASSWORD / RESET (plugin ke paas hi rahne do) =================== -->
                            <div data-section="lostpw" class="xoo-el-section">
                                <div class="xoo-el-fields">
                                    <div class="xoo-el-notice"></div>
                                    <form class="xoo-el-action-form xoo-el-form-lostpw">
                                        <span class="xoo-el-form-txt">
                                            Lost your password? Please enter your username or email address.
                                            You will receive a link to create a new password via email.
                                        </span>

                                        <div class="xoo-el-fields-cont">
                                            <div class="xoo-aff-group xoo-aff-cont-text one xoo-aff-cont-required user_login_cont">
                                                <div class="xoo-aff-input-group">
                                                    <span class="xoo-aff-input-icon fas fa-user-plus"></span>
                                                    <input type="text"
                                                           class="xoo-aff-required xoo-aff-text"
                                                           name="user_login"
                                                           placeholder="Username / Email"
                                                           required>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="_xoo_el_form" value="lostPassword">
                                        <input type="hidden" name="_wp_http_referer" value="/?simply_static_page=1">

                                        <button type="submit" class="button btn xoo-el-action-btn xoo-el-lostpw-btn">
                                            Email Reset Link
                                        </button>
                                    </form>

                                    <form class="xoo-el-code-form" data-parentform=".xoo-el-form-lostpw" data-code="reset_password">
                                        <div class="xoo-el-code-sent-txt">
                                            <span class="xoo-el-code-no-txt"></span>
                                            <span class="xoo-el-code-no-change"> Change</span>
                                        </div>

                                        <div class="xoo-el-code-notice-cont">
                                            <div class="xoo-el-code-notice"></div>
                                        </div>

                                        <div class="xoo-el-code-input-cont">
                                            <input type="tel" autocomplete="off" name="xoo-el-code[]" class="xoo-el-code-input">
                                            <input type="tel" autocomplete="off" name="xoo-el-code[]" class="xoo-el-code-input">
                                            <input type="tel" autocomplete="off" name="xoo-el-code[]" class="xoo-el-code-input">
                                            <input type="tel" autocomplete="off" name="xoo-el-code[]" class="xoo-el-code-input">
                                            <input type="tel" autocomplete="off" name="xoo-el-code[]" class="xoo-el-code-input">
                                            <input type="tel" autocomplete="off" name="xoo-el-code[]" class="xoo-el-code-input">
                                        </div>

                                        <input type="hidden" name="xoo-el-code-phone-no">
                                        <input type="hidden" name="xoo-el-code-phone-code">

                                        <button type="submit" class="button btn xoo-el-code-submit-btn xoo-el-action-btn">
                                            Verify
                                        </button>

                                        <input type="hidden" name="xoo_el_code_form_id" value="reset_password">
                                    </form>
 
	
	
			</div>

		</div>

	
			
		<div data-section="resetpw" class="xoo-el-section">

			<div class="xoo-el-fields">

				<div class="xoo-el-notice"></div>
				<form class="xoo-el-action-form xoo-el-form-resetpw">

					
					




	<span class="xoo-el-form-txt">Please enter a new password</span>

	<div class="xoo-el-fields-cont"><div class="xoo-aff-group xoo-aff-cont-password one xoo-aff-cont-required xoo-el-rp-pass_cont"><div class="xoo-aff-input-group"><span class="xoo-aff-input-icon fas fa-key"></span><input type="password" class="xoo-aff-required xoo-aff-password" name="xoo-el-rp-pass" placeholder="New Password" value="" maxlength="20" minlength="6" required="	" autocomplete="new-password"><div class="xoo-aff-pw-toggle">
					<span class="xoo-aff-pwtog-show"><i class="far fa-eye"></i></span>
					<span class="xoo-aff-pwtog-hide"><i class="far fa-eye-slash"></i></span>
					</div></div></div><div class="xoo-aff-group xoo-aff-cont-password one xoo-aff-cont-required xoo-el-rp-pass-again_cont"><div class="xoo-aff-input-group"><span class="xoo-aff-input-icon fas fa-key"></span><input type="password" class="xoo-aff-required xoo-aff-password" name="xoo-el-rp-pass-again" placeholder="Confirm Password" value="" required="	" autocomplete="new-password"><div class="xoo-aff-pw-toggle">
					<span class="xoo-aff-pwtog-show"><i class="far fa-eye"></i></span>
					<span class="xoo-aff-pwtog-hide"><i class="far fa-eye-slash"></i></span>
					</div></div></div></div>
	<input type="hidden" name="_xoo_el_form" value="resetPassword">

	<input type="hidden" name="xoo-el-resetpw-nonce-field" value="8fec8d54a1">

	
	
	<button type="submit" class="button btn xoo-el-action-btn xoo-el-resetpw-btn">Change Password</button>


					
				</form>

				
	
	

	
	
			</div>

		</div>

	
	
</div></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="xoo-el-popup-notice" style="visibility: hidden;">
    <div class="xoo-el-notice-opac"></div>
    <div class="xoo-el-notice-modal">
        <div class="xoo-el-notice-inmodal">
            <span class="xoo-el-notice-close xoo-el-icon-cross"></span>
            <div class="xoo-el-notice-wrap">
               <iframe></iframe>
               <div class="xoo-el-notice-iframestyle" style="display: none;">
                   body::-webkit-scrollbar {
                        width: 7px;
                    }

                    body::-webkit-scrollbar-track {
                        border-radius: 10px;
                        background: #f0f0f0;
                    }

                    body::-webkit-scrollbar-thumb {
                        border-radius: 50px;
                        background: #dfdbdb
                    }
               </div>
            </div>
        </div>
    </div>
</div>	<script type='text/javascript'>
		(function () {
			var c = document.body.className;
			c = c.replace(/woocommerce-no-js/, 'woocommerce-js');
			document.body.className = c;
		})();
	</script>
	<link rel="stylesheet" id="wc-blocks-style-css" href="./wp-content/plugins/woocommerce/assets/client/blocks/wc-blocks.css?ver=wc-10.3.3" type="text/css" media="all">
<link rel="stylesheet" id="wpr-link-animations-css-css" href="./wp-content/plugins/royal-elementor-addons/assets/css/lib/animations/wpr-link-animations.min.css?ver=1.7.1040" type="text/css" media="all">
<link rel="stylesheet" id="e-animation-grow-rotate-css" href="./wp-content/plugins/elementor/assets/lib/animations/styles/e-animation-grow-rotate.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="widget-social-icons-css" href="./wp-content/plugins/elementor/assets/css/widget-social-icons.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="e-apple-webkit-css" href="./wp-content/plugins/elementor/assets/css/conditionals/apple-webkit.min.css?ver=3.32.4" type="text/css" media="all">
<script type="text/javascript" id="xoo-el-js-js-extra">
/* <![CDATA[ */
var xoo_el_localize = {"adminurl":"\/\/wp-admin\/admin-ajax.php","redirectDelay":"300","html":{"spinner":"<i class=\"xoo-el-icon-spinner8 xoo-el-spinner\"><\/i>","editField":"<span class=\"xoo-el-edit-em\">Change?<\/span>","notice":{"error":"<div class=\"xoo-el-notice-error \">%s<\/div>","success":"<div class=\"xoo-el-notice-success \">%s<\/div>"}},"autoOpenPopup":"no","autoOpenPopupOnce":"no","aoDelay":"500","loginClass":"","registerClass":"","errorLog":"no","resetPwPattern":"code","resend_wait":"90","preventClosing":"","hasCodeForms":"1","checkout":{"loginEnabled":"yes","loginRedirect":"\/?simply_static_page=1"}};
/* ]]> */
</script>
<script type="text/javascript" src="./wp-content/plugins/easy-login-woocommerce/assets/js/xoo-el-js.js?ver=3.0.1" id="xoo-el-js-js"></script>
<script type="text/javascript" src="./wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/flatpickr/dist/flatpickr.min.js?ver=1763880564" id="flatpicker_js-js"></script>
<script type="text/javascript" src="./wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/flatpickr/dist/l10n/default.js?ver=1763880564" id="wps-flatpickr-locale-js"></script>
<script type="text/javascript" id="bookings-for-woocommercepublic-js-extra">
/* <![CDATA[ */
var mwb_mbfw_public_obj = {"today_date":"23-11-2025","wrong_order_date_1":"To date can not be less than from date.","wrong_order_date_2":"From date can not be greater than To date.","daily_start_time":"","daily_end_time":"","upcoming_holiday":[""],"is_pro_active":"","booking_product":"","wps_cal_type":"","wps_available_slots":"","booking_unit":"","booking_unavailable":[],"single_available_dates":[],"single_available_dates_till":"","today_date_check":"2025-11-23","single_unavailable_dates":[],"date_format":"F j, Y","single_unavailable_prices":[],"wps_single_dates_temp":[],"wps_single_dates_temp_dual":[],"mwb_mbfw_show_date_with_time":"","booking_slot_array_max_limit":[],"validation_message":"Please select valid date!","is_mobile_device":"desktop","wps_mbfw_day_and_days_upto_togather_enabled":"","wps_diaplay_time_format":"","firstDayOf_Week":"","hide_or_disable_slot":"","lang":"default"};
/* ]]> */
</script>
<script type="text/javascript" src="./wp-content/plugins/mwb-bookings-for-woocommerce/public/js/mwb-public.js?ver=1763880564" id="bookings-for-woocommercepublic-js"></script>
<script type="text/javascript" id="mwb-mbfw-common-js-js-extra">
/* <![CDATA[ */
var mwb_mbfw_common_obj = {"ajax_url":"\/\/wp-admin\/admin-ajax.php","nonce":"e7b49095cc","minDate":"23-11-2025 06:11","minTime":"06:11","maxTime":"24\/11\/202500:00","date_time_format":"Please choose the dates from calendar with correct format, wrong format can not be entered","date_format":"F j, Y","is_single_cal":"","cancel_booking_order":"Are you sure to cancel Booking order?","holiday_alert":"It looks like some dates are not available in between the dates choosen by you! , please select available dates!"};
/* ]]> */
</script>
<script type="text/javascript" src="./wp-content/plugins/mwb-bookings-for-woocommerce/common/js/mwb-common.js?ver=3.9.0" id="mwb-mbfw-common-js-js"></script>
<script type="text/javascript" src="./wp-includes/js/jquery/ui/core.min.js?ver=1.13.3" id="jquery-ui-core-js"></script>
<script type="text/javascript" src="./wp-includes/js/jquery/ui/datepicker.min.js?ver=1.13.3" id="jquery-ui-datepicker-js"></script>
<script type="text/javascript" id="jquery-ui-datepicker-js-after">
/* <![CDATA[ */
jQuery(function(jQuery){jQuery.datepicker.setDefaults({"closeText":"Close","currentText":"Today","monthNames":["January","February","March","April","May","June","July","August","September","October","November","December"],"monthNamesShort":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],"nextText":"Next","prevText":"Previous","dayNames":["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],"dayNamesShort":["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],"dayNamesMin":["S","M","T","W","T","F","S"],"dateFormat":"MM d, yy","firstDay":1,"isRTL":false});});
/* ]]> */
</script>
<script type="text/javascript" src="./wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/user-friendly-time-picker/dist/js/timepicker.min.js?ver=3.9.0" id="mwb-mbfw-time-picker-js-js"></script>
<script type="text/javascript" src="./wp-includes/js/dist/vendor/moment.min.js?ver=2.30.1" id="moment-js"></script>
<script type="text/javascript" id="moment-js-after">
/* <![CDATA[ */
moment.updateLocale( 'en_US', {"months":["January","February","March","April","May","June","July","August","September","October","November","December"],"monthsShort":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],"weekdays":["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],"weekdaysShort":["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],"week":{"dow":1},"longDateFormat":{"LT":"g:i a","LTS":null,"L":null,"LL":"F j, Y","LLL":"F j, Y g:i a","LLLL":null}} );
/* ]]> */
</script>
<script type="text/javascript" src="./wp-includes/js/dist/hooks.min.js?ver=4d63a3d491d11ffd8ac6" id="wp-hooks-js"></script>
<script type="text/javascript" src="./wp-includes/js/dist/deprecated.min.js?ver=e1f84915c5e8ae38964c" id="wp-deprecated-js"></script>
<script type="text/javascript" src="./wp-includes/js/dist/date.min.js?ver=85ff222add187a4e358f" id="wp-date-js"></script>
<script type="text/javascript" id="wp-date-js-after">
/* <![CDATA[ */
wp.date.setSettings( {"l10n":{"locale":"en_US","months":["January","February","March","April","May","June","July","August","September","October","November","December"],"monthsShort":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],"weekdays":["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],"weekdaysShort":["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],"meridiem":{"am":"am","pm":"pm","AM":"AM","PM":"PM"},"relative":{"future":"%s from now","past":"%s ago","s":"a second","ss":"%d seconds","m":"a minute","mm":"%d minutes","h":"an hour","hh":"%d hours","d":"a day","dd":"%d days","M":"a month","MM":"%d months","y":"a year","yy":"%d years"},"startOfWeek":1},"formats":{"time":"g:i a","date":"F j, Y","datetime":"F j, Y g:i a","datetimeAbbreviated":"M j, Y g:i a"},"timezone":{"offset":0,"offsetFormatted":"0","string":"","abbr":""}} );
/* ]]> */
</script>
<script type="text/javascript" src="./wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/moment-js/moment-locale-js.js?ver=3.9.0" id="moment-locale-js-js"></script>
<script type="text/javascript" src="./wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/datetimepicker-master/build/jquery.datetimepicker.full.js?ver=3.9.0" id="datetime-picker-js-js"></script>
<script type="text/javascript" src="./wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/multiple-datepicker/jquery-ui.multidatespicker.js?ver=1763880564" id="mwb-bfwp-multi-date-picker-js-js"></script>
<script type="text/javascript" src="./wp-content/plugins/royal-elementor-addons/assets/js/lib/particles/particles.js?ver=3.0.6" id="wpr-particles-js"></script>
<script type="text/javascript" src="./wp-content/plugins/royal-elementor-addons/assets/js/lib/jarallax/jarallax.min.js?ver=1.12.7" id="wpr-jarallax-js"></script>
<script type="text/javascript" src="./wp-content/plugins/royal-elementor-addons/assets/js/lib/parallax/parallax.min.js?ver=1.0" id="wpr-parallax-hover-js"></script>
<script type="text/javascript" src="./wp-content/plugins/woocommerce/assets/js/sourcebuster/sourcebuster.min.js?ver=10.3.3" id="sourcebuster-js-js"></script>
<script type="text/javascript" id="wc-order-attribution-js-extra">
/* <![CDATA[ */
var wc_order_attribution = {"params":{"lifetime":1.0e-5,"session":30,"base64":false,"ajaxurl":"\/\/wp-admin\/admin-ajax.php","prefix":"wc_order_attribution_","allowTracking":true},"fields":{"source_type":"current.typ","referrer":"current_add.rf","utm_campaign":"current.cmp","utm_source":"current.src","utm_medium":"current.mdm","utm_content":"current.cnt","utm_id":"current.id","utm_term":"current.trm","utm_source_platform":"current.plt","utm_creative_format":"current.fmt","utm_marketing_tactic":"current.tct","session_entry":"current_add.ep","session_start_time":"current_add.fd","session_pages":"session.pgs","session_count":"udata.vst","user_agent":"udata.uag"}};
/* ]]> */
</script>
<script type="text/javascript" src="./wp-content/plugins/woocommerce/assets/js/frontend/order-attribution.min.js?ver=10.3.3" id="wc-order-attribution-js"></script>
<script type="text/javascript" src="./wp-content/plugins/elementor/assets/js/webpack.runtime.min.js?ver=3.32.4" id="elementor-webpack-runtime-js"></script>
<script type="text/javascript" src="./wp-content/plugins/elementor/assets/js/frontend-modules.min.js?ver=3.32.4" id="elementor-frontend-modules-js"></script>
<script type="text/javascript" id="elementor-frontend-js-before">
/* <![CDATA[ */
var elementorFrontendConfig = {"environmentMode":{"edit":false,"wpPreview":false,"isScriptDebug":false},"i18n":{"shareOnFacebook":"Share on Facebook","shareOnTwitter":"Share on Twitter","pinIt":"Pin it","download":"Download","downloadImage":"Download image","fullscreen":"Fullscreen","zoom":"Zoom","share":"Share","playVideo":"Play Video","previous":"Previous","next":"Next","close":"Close","a11yCarouselPrevSlideMessage":"Previous slide","a11yCarouselNextSlideMessage":"Next slide","a11yCarouselFirstSlideMessage":"This is the first slide","a11yCarouselLastSlideMessage":"This is the last slide","a11yCarouselPaginationBulletMessage":"Go to slide"},"is_rtl":false,"breakpoints":{"xs":0,"sm":480,"md":768,"lg":1025,"xl":1440,"xxl":1600},"responsive":{"breakpoints":{"mobile":{"label":"Mobile Portrait","value":767,"default_value":767,"direction":"max","is_enabled":true},"mobile_extra":{"label":"Mobile Landscape","value":880,"default_value":880,"direction":"max","is_enabled":false},"tablet":{"label":"Tablet Portrait","value":1024,"default_value":1024,"direction":"max","is_enabled":true},"tablet_extra":{"label":"Tablet Landscape","value":1200,"default_value":1200,"direction":"max","is_enabled":false},"laptop":{"label":"Laptop","value":1366,"default_value":1366,"direction":"max","is_enabled":false},"widescreen":{"label":"Widescreen","value":2400,"default_value":2400,"direction":"min","is_enabled":false}},"hasCustomBreakpoints":false},"version":"3.32.4","is_static":false,"experimentalFeatures":{"e_font_icon_svg":true,"additional_custom_breakpoints":true,"container":true,"e_pro_free_trial_popup":true,"nested-elements":true,"home_screen":true,"global_classes_should_enforce_capabilities":true,"e_variables":true,"cloud-library":true,"e_opt_in_v4_page":true,"import-export-customization":true},"urls":{"assets":"\/\/wp-content\/plugins\/elementor\/assets\/","ajaxurl":"\/\/wp-admin\/admin-ajax.php","uploadUrl":"\/\/wp-content\/uploads"},"nonces":{"floatingButtonsClickTracking":"f0f492a412"},"swiperClass":"swiper","settings":{"page":[],"editorPreferences":[]},"kit":{"active_breakpoints":["viewport_mobile","viewport_tablet"],"global_image_lightbox":"yes","lightbox_enable_counter":"yes","lightbox_enable_fullscreen":"yes","lightbox_enable_zoom":"yes","lightbox_enable_share":"yes","lightbox_title_src":"title","lightbox_description_src":"description"},"post":{"id":800,"title":"wanderlustpakistan.com%20%E2%80%93%20Discover%20Pakistan%E2%80%99s%20Luxury%20Glamping%20%26%20Adventure%20Experiences","excerpt":"","featuredImage":false}};
/* ]]> */
</script>
<script type="text/javascript" src="./wp-content/plugins/elementor/assets/js/frontend.min.js?ver=3.32.4" id="elementor-frontend-js"></script>
<script type="text/javascript" src="./wp-content/plugins/royal-elementor-addons/assets/js/lib/perfect-scrollbar/perfect-scrollbar.min.js?ver=0.4.9" id="wpr-popup-scroll-js-js"></script>
<script type="text/javascript" src="./wp-content/plugins/royal-elementor-addons/assets/js/lib/dompurify/dompurify.min.js?ver=3.0.6" id="dompurify-js"></script>
<script type="text/javascript" id="wpr-addons-js-js-extra">
/* <![CDATA[ */
var WprConfig = {"ajaxurl":"\/\/wp-admin\/admin-ajax.php","resturl":"\/\/wp-json\/wpraddons\/v1","nonce":"073968aad8","addedToCartText":"was added to cart","viewCart":"View Cart","comparePageID":"","comparePageURL":"\/\/","wishlistPageID":"","wishlistPageURL":"\/\/","chooseQuantityText":"Please select the required number of items.","site_key":"","is_admin":"","input_empty":"Please fill out this field","select_empty":"Nothing selected","file_empty":"Please upload a file","recaptcha_error":"Recaptcha Error","woo_shop_ppp":"9","woo_shop_cat_ppp":"9","woo_shop_tag_ppp":"9","is_product_category":"","is_product_tag":""};
/* ]]> */
</script>
<script type="text/javascript" data-cfasync="false" src="./wp-content/plugins/royal-elementor-addons/assets/js/frontend.min.js?ver=1.7.1040" id="wpr-addons-js-js"></script>
<script type="text/javascript" src="./wp-content/plugins/royal-elementor-addons/assets/js/modal-popups.min.js?ver=1.7.1040" id="wpr-modal-popups-js-js"></script>
<script type="text/javascript" src="./wp-content/plugins/wpforms-lite/assets/lib/jquery.validate.min.js?ver=1.21.0" id="wpforms-validation-js"></script>
<script type="text/javascript" src="./wp-content/plugins/wpforms-lite/assets/lib/mailcheck.min.js?ver=1.1.2" id="wpforms-mailcheck-js"></script>
<script type="text/javascript" src="./wp-content/plugins/wpforms-lite/assets/lib/punycode.min.js?ver=1.0.0" id="wpforms-punycode-js"></script>
<script type="text/javascript" src="./wp-content/plugins/wpforms-lite/assets/js/share/utils.min.js?ver=1.9.8.2" id="wpforms-generic-utils-js"></script>
<script type="text/javascript" src="./wp-content/plugins/wpforms-lite/assets/js/frontend/wpforms.min.js?ver=1.9.8.2" id="wpforms-js"></script>
<script type="text/javascript" src="./wp-content/plugins/wpforms-lite/assets/js/frontend/wpforms-modern.min.js?ver=1.9.8.2" id="wpforms-modern-js"></script>
<script type="text/javascript" src="./wp-content/plugins/wpforms-lite/assets/js/frontend/fields/address.min.js?ver=1.9.8.2" id="wpforms-address-field-js"></script>
<script type='text/javascript'>
/* <![CDATA[ */
var wpforms_settings = {"val_required":"This field is required.","val_email":"Please enter a valid email address.","val_email_suggestion":"Did you mean {suggestion}?","val_email_suggestion_title":"Click to accept this suggestion.","val_email_restricted":"This email address is not allowed.","val_number":"Please enter a valid number.","val_number_positive":"Please enter a valid positive number.","val_minimum_price":"Amount entered is less than the required minimum.","val_confirm":"Field values do not match.","val_checklimit":"You have exceeded the number of allowed selections: {#}.","val_limit_characters":"{count} of {limit} max characters.","val_limit_words":"{count} of {limit} max words.","val_min":"Please enter a value greater than or equal to {0}.","val_max":"Please enter a value less than or equal to {0}.","val_recaptcha_fail_msg":"Google reCAPTCHA verification failed, please try again later.","val_turnstile_fail_msg":"Cloudflare Turnstile verification failed, please try again later.","val_inputmask_incomplete":"Please fill out the field in required format.","uuid_cookie":"","locale":"en","country":"","country_list_label":"Country list","wpforms_plugin_url":"\/\/wp-content\/plugins\/wpforms-lite\/","gdpr":"","ajaxurl":"\/\/wp-admin\/admin-ajax.php","mailcheck_enabled":"1","mailcheck_domains":[],"mailcheck_toplevel_domains":["dev"],"is_ssl":"1","currency_code":"USD","currency_thousands":",","currency_decimals":"2","currency_decimal":".","currency_symbol":"$","currency_symbol_pos":"left","val_requiredpayment":"Payment is required.","val_creditcard":"Please enter a valid credit card number.","css_vars":["field-border-radius","field-border-style","field-border-size","field-background-color","field-border-color","field-text-color","field-menu-color","label-color","label-sublabel-color","label-error-color","button-border-radius","button-border-style","button-border-size","button-background-color","button-border-color","button-text-color","page-break-color","background-image","background-position","background-repeat","background-size","background-width","background-height","background-color","background-url","container-padding","container-border-style","container-border-width","container-border-color","container-border-radius","field-size-input-height","field-size-input-spacing","field-size-font-size","field-size-line-height","field-size-padding-h","field-size-checkbox-size","field-size-sublabel-spacing","field-size-icon-size","label-size-font-size","label-size-line-height","label-size-sublabel-font-size","label-size-sublabel-line-height","button-size-font-size","button-size-height","button-size-padding-h","button-size-margin-top","container-shadow-size-box-shadow"],"isModernMarkupEnabled":"1","formErrorMessagePrefix":"Form error message","errorMessagePrefix":"Error message","submitBtnDisabled":"Submit button is disabled during form submission.","readOnlyDisallowedFields":["captcha","content","divider","hidden","html","entry-preview","pagebreak","payment-total"],"error_updating_token":"Error updating token. Please try again or contact support if the issue persists.","network_error":"Network error or server is unreachable. Check your connection or try again later.","token_cache_lifetime":"86400","hn_data":{"1078":3},"address_field":{"list_countries_without_states":["GB","DE","CH","NL"]}}
/* ]]> */
</script>
	</body>
</html>

<?php
// -------------------
// HOME PAGE FORM SUBMISSION
// -------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_name']) && $_POST['form_name'] === 'home_plan_form') {

    // 1. Get submitted fields
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $travel_location = $_POST['travel_location'];
    $experience_type = $_POST['experience_type'];
    $message = $_POST['message'];

    // 2. Insert into DB
    $sql = "INSERT INTO contacts (full_name, email, phone, travel_location, experience_type, message)
            VALUES ('$full_name', '$email', '$phone', '$travel_location', '$experience_type', '$message')";

    if ($conn->query($sql)) {
        echo "<script>
                alert('Your details have been submitted successfully!');
                window.location.href = 'index.php#plan-form';
              </script>";
    } else {
        echo "<script>
                alert('Error occurred: " . $conn->error . "');
                window.location.href = 'index.php#plan-form';
              </script>";
    }
}
?>
