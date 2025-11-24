<?php
require_once __DIR__ . '/../../db.php';   
require_once __DIR__ . '/../../facades/BookingFacade.php';

$booking = new BookingFacade($conn);

function js_back($msg){
    echo "<script>alert('".addslashes($msg)."');window.history.back();</script>";
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_form'])) {

        $booking->createBooking($_POST);

        echo "<script>
                alert('Your booking request has been submitted!');
                window.location.href='".addslashes($_SERVER['REQUEST_URI'])."';
              </script>";
        exit;
    }

} catch (Throwable $e) {
    js_back("Booking Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en-US">
    <head><meta charset="UTF-8">
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
                <title>3 Bedroom Villa – Deluxe Room (Rustic Vintage Style) – wanderlustpakistan.com</title>
<meta name="robots" content="max-image-preview:large">
	<style>img:is([sizes="auto" i], [sizes^="auto," i]) { contain-intrinsic-size: 3000px 1500px }</style>
	<link rel="alternate" type="application/rss+xml" title="wanderlustpakistan.com » Feed" href="./../../feed/index.html">
<link rel="alternate" type="application/rss+xml" title="wanderlustpakistan.com » Comments Feed" href="./../../comments/feed/index.html">
<script type="text/javascript">
/* <![CDATA[ */
window._wpemojiSettings = {"baseUrl":"https:\/\/s.w.org\/images\/core\/emoji\/16.0.1\/72x72\/","ext":".png","svgUrl":"https:\/\/s.w.org\/images\/core\/emoji\/16.0.1\/svg\/","svgExt":".svg","source":{"concatemoji":"\/\/wp-includes\/js\/wp-emoji-release.min.js?ver=6.8.3"}};
/*! This file is auto-generated */
!function(s,n){var o,i,e;function c(e){try{var t={supportTests:e,timestamp:(new Date).valueOf()};sessionStorage.setItem(o,JSON.stringify(t))}catch(e){}}function p(e,t,n){e.clearRect(0,0,e.canvas.width,e.canvas.height),e.fillText(t,0,0);var t=new Uint32Array(e.getImageData(0,0,e.canvas.width,e.canvas.height).data),a=(e.clearRect(0,0,e.canvas.width,e.canvas.height),e.fillText(n,0,0),new Uint32Array(e.getImageData(0,0,e.canvas.width,e.canvas.height).data));return t.every(function(e,t){return e===a[t]})}function u(e,t){e.clearRect(0,0,e.canvas.width,e.canvas.height),e.fillText(t,0,0);for(var n=e.getImageData(16,16,1,1),a=0;a<n.data.length;a++)if(0!==n.data[a])return!1;return!0}function f(e,t,n,a){switch(t){case"flag":return n(e,"\ud83c\udff3\ufe0f\u200d\u26a7\ufe0f","\ud83c\udff3\ufe0f\u200b\u26a7\ufe0f")?!1:!n(e,"\ud83c\udde8\ud83c\uddf6","\ud83c\udde8\u200b\ud83c\uddf6")&&!n(e,"\ud83c\udff4\udb40\udc67\udb40\udc62\udb40\udc65\udb40\udc6e\udb40\udc67\udb40\udc7f","\ud83c\udff4\u200b\udb40\udc67\u200b\udb40\udc62\u200b\udb40\udc65\u200b\udb40\udc6e\u200b\udb40\udc67\u200b\udb40\udc7f");case"emoji":return!a(e,"\ud83e\udedf")}return!1}function g(e,t,n,a){var r="undefined"!=typeof WorkerGlobalScope&&self instanceof WorkerGlobalScope?new OffscreenCanvas(300,150):s.createElement("canvas"),o=r.getContext("2d",{willReadFrequently:!0}),i=(o.textBaseline="top",o.font="600 32px Arial",{});return e.forEach(function(e){i[e]=t(o,e,n,a)}),i}function t(e){var t=s.createElement("script");t.src=e,t.defer=!0,s.head.appendChild(t)}"undefined"!=typeof Promise&&(o="wpEmojiSettingsSupports",i=["flag","emoji"],n.supports={everything:!0,everythingExceptFlag:!0},e=new Promise(function(e){s.addEventListener("DOMContentLoaded",e,{once:!0})}),new Promise(function(t){var n=function(){try{var e=JSON.parse(sessionStorage.getItem(o));if("object"==typeof e&&"number"==typeof e.timestamp&&(new Date).valueOf()<e.timestamp+604800&&"object"==typeof e.supportTests)return e.supportTests}catch(e){}return null}();if(!n){if("undefined"!=typeof Worker&&"undefined"!=typeof OffscreenCanvas&&"undefined"!=typeof URL&&URL.createObjectURL&&"undefined"!=typeof Blob)try{var e="postMessage("+g.toString()+"("+[JSON.stringify(i),f.toString(),p.toString(),u.toString()].join(",")+"));",a=new Blob([e],{type:"text/javascript"}),r=new Worker(URL.createObjectURL(a),{name:"wpTestEmojiSupports"});return void(r.onmessage=function(e){c(n=e.data),r.terminate(),t(n)})}catch(e){}c(n=g(i,f,p,u))}t(n)}).then(function(e){for(var t in e)n.supports[t]=e[t],n.supports.everything=n.supports.everything&&n.supports[t],"flag"!==t&&(n.supports.everythingExceptFlag=n.supports.everythingExceptFlag&&n.supports[t]);n.supports.everythingExceptFlag=n.supports.everythingExceptFlag&&!n.supports.flag,n.DOMReady=!1,n.readyCallback=function(){n.DOMReady=!0}}).then(function(){return e}).then(function(){var e;n.supports.everything||(n.readyCallback(),(e=n.source||{}).concatemoji?t(e.concatemoji):e.wpemoji&&e.twemoji&&(t(e.twemoji),t(e.wpemoji)))}))}((window,document),window._wpemojiSettings);
/* ]]> */
</script>
<link rel="stylesheet" id="xoo-aff-style-css" href="./../../wp-content/plugins/easy-login-woocommerce/xoo-form-fields-fw/assets/css/xoo-aff-style.css?ver=2.1.0" type="text/css" media="all">
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
<link rel="stylesheet" id="xoo-aff-font-awesome5-css" href="./../../wp-content/plugins/easy-login-woocommerce/xoo-form-fields-fw/lib/fontawesome5/css/all.min.css?ver=6.8.3" type="text/css" media="all">
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
<link rel="stylesheet" id="wp-block-library-css" href="./../../wp-includes/css/dist/block-library/style.min.css?ver=6.8.3" type="text/css" media="all">
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
<link rel="stylesheet" id="bookings-for-woocommerce-css" href="./../../wp-content/plugins/mwb-bookings-for-woocommerce/public/css/mwb-public.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="flatpickercss-css" href="./../../wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/flatpickr/dist/flatpickr.min.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="mwb-mbfw-select2-css-css" href="./../../wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/select-2/mwb-bookings-for-woocommerce-select2.css?ver=1763880593" type="text/css" media="all">
<link rel="stylesheet" id="bookings-for-woocommerceglobal_form-css" href="./../../wp-content/plugins/mwb-bookings-for-woocommerce/public/css/mwb-public-form.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="bookings-for-woocommercecommon-css" href="./../../wp-content/plugins/mwb-bookings-for-woocommerce/common/css/mwb-bookings-for-woocommerce-common.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="mwb-mbfw-common-custom-css-css" href="./../../wp-content/plugins/mwb-bookings-for-woocommerce/common/css/mwb-common.min.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="mwb-mbfw-time-picker-css-css" href="./../../wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/user-friendly-time-picker/dist/css/timepicker.min.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="jquery-ui-css" href="./../../wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/jquery-ui-css/jquery-ui.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="datetime-picker-css-css" href="./../../wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/datetimepicker-master/build/jquery.datetimepicker.min.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="mwb-bfwp-multi-date-picker-css-css" href="./../../wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/multiple-datepicker/jquery-ui.multidatespicker.css?ver=3.9.0" type="text/css" media="all">
<link rel="stylesheet" id="photoswipe-css" href="./../../wp-content/plugins/woocommerce/assets/css/photoswipe/photoswipe.min.css?ver=10.3.3" type="text/css" media="all">
<link rel="stylesheet" id="photoswipe-default-skin-css" href="./../../wp-content/plugins/woocommerce/assets/css/photoswipe/default-skin/default-skin.min.css?ver=10.3.3" type="text/css" media="all">
<link rel="stylesheet" id="woocommerce-layout-css" href="./../../wp-content/plugins/woocommerce/assets/css/woocommerce-layout.css?ver=10.3.3" type="text/css" media="all">
<link rel="stylesheet" id="woocommerce-smallscreen-css" href="./../../wp-content/plugins/woocommerce/assets/css/woocommerce-smallscreen.css?ver=10.3.3" type="text/css" media="only screen and (max-width: 768px)">
<link rel="stylesheet" id="woocommerce-general-css" href="./../../wp-content/plugins/woocommerce/assets/css/woocommerce.css?ver=10.3.3" type="text/css" media="all">
<style id="woocommerce-inline-inline-css" type="text/css">
.woocommerce form .form-row .required { visibility: visible; }
</style>
<link rel="stylesheet" id="xoo-el-style-css" href="./../../wp-content/plugins/easy-login-woocommerce/assets/css/xoo-el-style.css?ver=3.0.1" type="text/css" media="all">
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
		background-image: url(./../../wp-content/plugins/easy-login-woocommerce/assets/images/popup-sidebar.jpg);
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
<link rel="stylesheet" id="xoo-el-fonts-css" href="./../../wp-content/plugins/easy-login-woocommerce/assets/css/xoo-el-fonts.css?ver=3.0.1" type="text/css" media="all">
<link rel="stylesheet" id="elementor-frontend-css" href="./../../wp-content/plugins/elementor/assets/css/frontend.min.css?ver=3.32.4" type="text/css" media="all">
<style id="elementor-frontend-inline-css" type="text/css">
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
</style>
<link rel="stylesheet" id="widget-heading-css" href="./../../wp-content/plugins/elementor/assets/css/widget-heading.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="e-animation-grow-rotate-css" href="./../../wp-content/plugins/elementor/assets/lib/animations/styles/e-animation-grow-rotate.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="widget-social-icons-css" href="./../../wp-content/plugins/elementor/assets/css/widget-social-icons.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="e-apple-webkit-css" href="./../../wp-content/plugins/elementor/assets/css/conditionals/apple-webkit.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="widget-divider-css" href="./../../wp-content/plugins/elementor/assets/css/widget-divider.min.css?ver=3.32.4" type="text/css" media="all">
<link rel="stylesheet" id="brands-styles-css" href="./../../wp-content/plugins/woocommerce/assets/css/brands.css?ver=10.3.3" type="text/css" media="all">
<link rel="stylesheet" id="royal-elementor-kit-style-css" href="./../../wp-content/themes/royal-elementor-kit/style.css?ver=1.0" type="text/css" media="all">
<link rel="stylesheet" id="wpr-link-animations-css-css" href="./../../wp-content/plugins/royal-elementor-addons/assets/css/lib/animations/wpr-link-animations.min.css?ver=1.7.1040" type="text/css" media="all">
<link rel="stylesheet" id="wpr-button-animations-css-css" href="./../../wp-content/plugins/royal-elementor-addons/assets/css/lib/animations/button-animations.min.css?ver=1.7.1040" type="text/css" media="all">
<link rel="stylesheet" id="wpr-text-animations-css-css" href="./../../wp-content/plugins/royal-elementor-addons/assets/css/lib/animations/text-animations.min.css?ver=1.7.1040" type="text/css" media="all">
<link rel="stylesheet" id="wpr-addons-css-css" href="./../../wp-content/plugins/royal-elementor-addons/assets/css/frontend.min.css?ver=1.7.1040" type="text/css" media="all">
<link rel="stylesheet" id="font-awesome-5-all-css" href="./../../wp-content/plugins/elementor/assets/lib/font-awesome/css/all.min.css?ver=1.7.1040" type="text/css" media="all">
<link rel="stylesheet" id="elementor-gf-opensans-css" href="https://fonts.googleapis.com/css?family=Open+Sans:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&amp;display=swap" type="text/css" media="all">
<link rel="stylesheet" id="elementor-gf-playfairdisplay-css" href="https://fonts.googleapis.com/css?family=Playfair+Display:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&amp;display=swap" type="text/css" media="all">
<script type="text/javascript" data-cfasync="false" src="./../../wp-includes/js/jquery/jquery.min.js?ver=3.7.1" id="jquery-core-js"></script>
<script type="text/javascript" data-cfasync="false" src="./../../wp-includes/js/jquery/jquery-migrate.min.js?ver=3.4.1" id="jquery-migrate-js"></script>
<script type="text/javascript" id="xoo-aff-js-js-extra">
/* <![CDATA[ */
var xoo_aff_localize = {"adminurl":"\/\/wp-admin\/admin-ajax.php","password_strength":{"min_password_strength":3,"i18n_password_error":"Please enter a stronger password.","i18n_password_hint":"Hint: The password should be at least twelve characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! &quot; ? $ % ^ &amp; )."}};
/* ]]> */
</script>
<script type="text/javascript" src="./../../wp-content/plugins/easy-login-woocommerce/xoo-form-fields-fw/assets/js/xoo-aff-js.js?ver=2.1.0" id="xoo-aff-js-js" defer="defer" data-wp-strategy="defer"></script>
<script type="text/javascript" id="bookings-for-woocommercecommon-js-extra">
/* <![CDATA[ */
var mbfw_common_param = {"ajaxurl":"\/\/wp-admin\/admin-ajax.php"};
/* ]]> */
</script>
<script type="text/javascript" src="./../../wp-content/plugins/mwb-bookings-for-woocommerce/common/js/mwb-bookings-for-woocommerce-common.js?ver=3.9.0" id="bookings-for-woocommercecommon-js"></script>
<script type="text/javascript" src="./../../wp-content/plugins/woocommerce/assets/js/jquery-blockui/jquery.blockUI.min.js?ver=2.7.0-wc.10.3.3" id="wc-jquery-blockui-js" defer="defer" data-wp-strategy="defer"></script>
<script type="text/javascript" id="wc-add-to-cart-js-extra">
/* <![CDATA[ */
var wc_add_to_cart_params = {"ajax_url":"\/wp-admin\/admin-ajax.php","wc_ajax_url":"\/?wc-ajax=%%endpoint%%","i18n_view_cart":"View cart","cart_url":"\/","is_cart":"","cart_redirect_after_add":"no"};
/* ]]> */
</script>
<script type="text/javascript" src="./../../wp-content/plugins/woocommerce/assets/js/frontend/add-to-cart.min.js?ver=10.3.3" id="wc-add-to-cart-js" defer="defer" data-wp-strategy="defer"></script>
<script type="text/javascript" src="./../../wp-content/plugins/woocommerce/assets/js/zoom/jquery.zoom.min.js?ver=1.7.21-wc.10.3.3" id="wc-zoom-js" defer="defer" data-wp-strategy="defer"></script>
<script type="text/javascript" src="./../../wp-content/plugins/woocommerce/assets/js/flexslider/jquery.flexslider.min.js?ver=2.7.2-wc.10.3.3" id="wc-flexslider-js" defer="defer" data-wp-strategy="defer"></script>
<script type="text/javascript" src="./../../wp-content/plugins/woocommerce/assets/js/photoswipe/photoswipe.min.js?ver=4.1.1-wc.10.3.3" id="wc-photoswipe-js" defer="defer" data-wp-strategy="defer"></script>
<script type="text/javascript" src="./../../wp-content/plugins/woocommerce/assets/js/photoswipe/photoswipe-ui-default.min.js?ver=4.1.1-wc.10.3.3" id="wc-photoswipe-ui-default-js" defer="defer" data-wp-strategy="defer"></script>
<script type="text/javascript" id="wc-single-product-js-extra">
/* <![CDATA[ */
var wc_single_product_params = {"i18n_required_rating_text":"Please select a rating","i18n_rating_options":["1 of 5 stars","2 of 5 stars","3 of 5 stars","4 of 5 stars","5 of 5 stars"],"i18n_product_gallery_trigger_text":"View full-screen image gallery","review_rating_required":"yes","flexslider":{"rtl":false,"animation":"slide","smoothHeight":true,"directionNav":true,"controlNav":"thumbnails","slideshow":false,"animationSpeed":500,"animationLoop":false,"allowOneSlide":false},"zoom_enabled":"1","zoom_options":[],"photoswipe_enabled":"1","photoswipe_options":{"shareEl":false,"closeOnScroll":false,"history":false,"hideAnimationDuration":0,"showAnimationDuration":0},"flexslider_enabled":"1"};
/* ]]> */
</script>
<script type="text/javascript" src="./../../wp-content/plugins/woocommerce/assets/js/frontend/single-product.min.js?ver=10.3.3" id="wc-single-product-js" defer="defer" data-wp-strategy="defer"></script>
<script type="text/javascript" src="./../../wp-content/plugins/woocommerce/assets/js/js-cookie/js.cookie.min.js?ver=2.1.4-wc.10.3.3" id="wc-js-cookie-js" defer="defer" data-wp-strategy="defer"></script>
<script type="text/javascript" id="woocommerce-js-extra">
/* <![CDATA[ */
var woocommerce_params = {"ajax_url":"\/wp-admin\/admin-ajax.php","wc_ajax_url":"\/?wc-ajax=%%endpoint%%","i18n_password_show":"Show password","i18n_password_hide":"Hide password"};
/* ]]> */
</script>
<script type="text/javascript" src="./../../wp-content/plugins/woocommerce/assets/js/frontend/woocommerce.min.js?ver=10.3.3" id="woocommerce-js" defer="defer" data-wp-strategy="defer"></script>
<link rel="https://api.w.org/" href="./../../wp-json/index.html"><link rel="alternate" title="JSON" type="application/json" href="./../../wp-json/wp/v2/product/1745/index.html"><link rel="EditURI" type="application/rsd+xml" title="RSD" href="./../../xmlrpc.php?rsd">
<meta name="generator" content="WordPress 6.8.3">
<meta name="generator" content="WooCommerce 10.3.3">
<link rel="canonical" href="./index.html">
<link rel="shortlink" href="./../../index.html?p=1745">
<link rel="alternate" title="oEmbed (JSON)" type="application/json+oembed" href="./../../wp-json/oembed/1.0/embed/index.html?url=https%3A%2F%2F%2Fproduct%2F3-bedroom-villa-deluxe-room-rustic-vintage-style%2F">
<link rel="alternate" title="oEmbed (XML)" type="text/xml+oembed" href="./../../wp-json/oembed/1.0/embed/index.html?url=https%3A%2F%2F%2Fproduct%2F3-bedroom-villa-deluxe-room-rustic-vintage-style%2F&amp;format=xml">
	<noscript><style>.woocommerce-product-gallery{ opacity: 1 !important; }</style></noscript>
	<link rel="icon" href="./../../wp-content/uploads/2025/10/image-removebg-preview-26-100x100.png" sizes="32x32">
<link rel="icon" href="./../../wp-content/uploads/2025/10/image-removebg-preview-26.png" sizes="192x192">
<link rel="apple-touch-icon" href="./../../wp-content/uploads/2025/10/image-removebg-preview-26.png">
<meta name="msapplication-TileImage" content="./../../wp-content/uploads/2025/10/image-removebg-preview-26.png">
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
	         </style>    </head>

<body class="wp-singular product-template-default single single-product postid-1745 wp-embed-responsive wp-theme-royal-elementor-kit theme-royal-elementor-kit woocommerce woocommerce-page woocommerce-no-js elementor-default elementor-kit-10">

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
										<source media="(max-width: 767px)" srcset="./../../wp-content/uploads/2025/10/Copy-of-Copy-of-Add-a-heading__3_-removebg-preview.png">	
					
										<source srcset="./../../wp-content/uploads/2025/10/Copy-of-Copy-of-Add-a-heading__3_-removebg-preview.png 1x, ./../../wp-content/uploads/2025/10/Copy-of-Copy-of-Add-a-heading__3_-removebg-preview.png 2x">	
										
					<img src="./../../wp-content/uploads/2025/10/Copy-of-Copy-of-Add-a-heading__3_-removebg-preview.png" alt="">

											<a class="wpr-logo-url" rel="home" aria-label="" href="./../../index.html"></a>
									</source></source></picture>
				
				
									<a class="wpr-logo-url" rel="home" aria-label="" href="./../../index.html"></a>
				
			</div>
				
						</div>
				</div>
					</div>
		</div>
				<div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-306600e5" data-id="306600e5" data-element_type="column">
			<div class="elementor-widget-wrap elementor-element-populated">
						<div class="elementor-element elementor-element-42c4e46d wpr-main-menu-align-center wpr-nav-menu-bp-tablet wpr-main-menu-align--tabletcenter wpr-main-menu-align--mobilecenter wpr-pointer-underline wpr-pointer-line-fx wpr-pointer-fx-fade wpr-sub-icon-caret-down wpr-sub-menu-fx-fade wpr-mobile-menu-full-width wpr-mobile-menu-item-align-center wpr-mobile-toggle-v1 wpr-sub-divider-yes wpr-mobile-divider-yes elementor-widget elementor-widget-wpr-nav-menu" data-id="42c4e46d" data-element_type="widget" data-settings="{&quot;menu_layout&quot;:&quot;horizontal&quot;}" data-widget_type="wpr-nav-menu.default">
				<div class="elementor-widget-container">
					<nav class="wpr-nav-menu-container wpr-nav-menu-horizontal" data-trigger="hover"><ul id="menu-1-42c4e46d" class="wpr-nav-menu"><li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home menu-item-804"><a href="./../../index.php" class="wpr-menu-item wpr-pointer-item">Home</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1773"><a href="./../../wander-stays/index.html" class="wpr-menu-item wpr-pointer-item">Wander Stays</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-936"><a href="./../../contact-us/index.php" class="wpr-menu-item wpr-pointer-item">Contact Us</a></li>
<li class="xoo-el-login-tgr menu-item menu-item-type-custom menu-item-object-custom menu-item-2064"><a class="wpr-menu-item wpr-pointer-item">Login</a></li>
</ul></nav><nav class="wpr-mobile-nav-menu-container"><div class="wpr-mobile-toggle-wrap"><div class="wpr-mobile-toggle"><span class="wpr-mobile-toggle-line"></span><span class="wpr-mobile-toggle-line"></span><span class="wpr-mobile-toggle-line"></span></div></div><ul id="mobile-menu-2-42c4e46d" class="wpr-mobile-nav-menu"><li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home menu-item-804"><a href="./../../index.php" class="wpr-mobile-menu-item">Home</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1773"><a href="./../../wander-stays/index.html" class="wpr-mobile-menu-item">Wander Stays</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-936"><a href="./../../contact-us/index.php" class="wpr-mobile-menu-item">Contact Us</a></li>
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
		
	<div id="primary" class="content-area"><main id="main" class="site-main" role="main"><nav class="woocommerce-breadcrumb" aria-label="Breadcrumb"><a href="./../../index.html">Home</a> / <a href="./../../product-category/booking/index.html">Booking</a> / 3 Bedroom Villa – Deluxe Room (Rustic Vintage Style)</nav>
					
			<div class="woocommerce-notices-wrapper"></div><div id="product-1745" class="product type-product post-1745 status-publish first instock product_cat-booking product_cat-wander-stays product_tag-deluxe-stay product_tag-family-rooms product_tag-luxury-rooms product_tag-mountain-view-stay product_tag-romantic-getaway product_tag-rooms-in-murree has-post-thumbnail virtual purchasable product-type-mwb_booking">

	<div class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images" data-columns="4" style="opacity: 0; transition: opacity .25s ease-in-out;">
	<div class="woocommerce-product-gallery__wrapper">
		<div data-thumb=".//wp-content/uploads/2025/10/20240823_040252-2048x1536-1-1-100x100.webp" data-thumb-alt="3 Bedroom Villa – Deluxe Room (Rustic Vintage Style)" data-thumb-srcset=".//wp-content/uploads/2025/10/20240823_040252-2048x1536-1-1-100x100.webp 100w, .//wp-content/uploads/2025/10/20240823_040252-2048x1536-1-1-150x150.webp 150w, .//wp-content/uploads/2025/10/20240823_040252-2048x1536-1-1-300x300.webp 300w" data-thumb-sizes="(max-width: 100px) 100vw, 100px" class="woocommerce-product-gallery__image"><a href="./../../wp-content/uploads/2025/10/20240823_040252-2048x1536-1-1.webp"><img fetchpriority="high" width="600" height="450" src="./../../wp-content/uploads/2025/10/20240823_040252-2048x1536-1-1-600x450.webp" class="wp-post-image" alt="3 Bedroom Villa – Deluxe Room (Rustic Vintage Style)" data-caption="" data-src="./../../wp-content/uploads/2025/10/20240823_040252-2048x1536-1-1.webp" data-large_image=".//wp-content/uploads/2025/10/20240823_040252-2048x1536-1-1.webp" data-large_image_width="1600" data-large_image_height="1200" decoding="async" srcset="./../../wp-content/uploads/2025/10/20240823_040252-2048x1536-1-1-600x450.webp 600w, ./../../wp-content/uploads/2025/10/20240823_040252-2048x1536-1-1-300x225.webp 300w, ./../../wp-content/uploads/2025/10/20240823_040252-2048x1536-1-1-1024x768.webp 1024w, ./../../wp-content/uploads/2025/10/20240823_040252-2048x1536-1-1-768x576.webp 768w, ./../../wp-content/uploads/2025/10/20240823_040252-2048x1536-1-1-1536x1152.webp 1536w, ./../../wp-content/uploads/2025/10/20240823_040252-2048x1536-1-1.webp 1600w" sizes="(max-width: 600px) 100vw, 600px"></a></div>	</div>
</div>

	<div class="summary entry-summary">
		<h1 class="product_title entry-title">3 Bedroom Villa – Deluxe Room (Rustic Vintage Style)</h1><p class="price"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">₨</span> 50,000</bdi></span></p>
<div class="woocommerce-product-details__short-description">
	<p data-start="254" data-end="518">Escape to a peaceful retreat in the <strong data-start="290" data-end="317">Kuldana Hills of Murree</strong>, where comfort meets elegance.<br data-start="348" data-end="351">Our <strong data-start="355" data-end="370">Deluxe Room</strong> offers a perfect mix of style, warmth, and luxury — ideal for couples, families, or anyone looking to enjoy a relaxing stay surrounded by nature.</p>
<p data-start="520" data-end="636">Enjoy cozy interiors, a private outdoor sitting area, and all the modern comforts that make your vacation special.</p>
<hr data-start="638" data-end="641">
<h3 data-start="643" data-end="670"><strong data-start="647" data-end="668">Included Services</strong></h3>
<p data-start="671" data-end="1126">✔ Complimentary breakfast<br data-start="696" data-end="699">✔ Private bonfire and outdoor sitting area<br data-start="741" data-end="744">✔ Premium amenities for a luxurious experience<br data-start="790" data-end="793">✔ 24/7 hot water, heating, and WiFi<br data-start="828" data-end="831">✔ High-end toiletries with 5-star hygiene standards<br data-start="882" data-end="885">✔ Complimentary coffee and bottled water<br data-start="925" data-end="928">✔ Gated and secure free parking<br data-start="959" data-end="962">✔ Indoor and outdoor activities — Snooker, Foosball, Table Tennis, Cycling, Modified Bikes for Couples &amp; more<br data-start="1071" data-end="1074">✔ Private terrace and lawn for peaceful relaxation</p>
<hr data-start="1128" data-end="1131">
<h3 data-start="1133" data-end="1177"><strong data-start="1137" data-end="1175">Available Services (Extra Charges)</strong></h3>
<p data-start="1178" data-end="1460">🍽 Restaurant, candlelight dinner, and décor setup<br data-start="1228" data-end="1231">🚗 Pick &amp; drop or rent-a-car service<br data-start="1267" data-end="1270">🏕 Wildlife camping experience<br data-start="1300" data-end="1303">🥗 100% organic food menu<br data-start="1328" data-end="1331">💑 Romantic bike rides<br data-start="1353" data-end="1356">🧊 Mini fridge (15% additional charge)<br data-start="1394" data-end="1397">🥨 Snack bucket<br data-start="1412" data-end="1415">💍 Destination wedding &amp; event arrangements</p>
<hr data-start="1462" data-end="1465">
<p data-start="1467" data-end="1564">📍 <strong data-start="1470" data-end="1483">Location:</strong> Kuldana Hills, Murree<br data-start="1505" data-end="1508">🔗 <a class="decorated-link" href="https://g.co/kgs/UeVT3Bk" target="_new" rel="noopener" data-start="1511" data-end="1562"><strong data-start="1512" data-end="1535">View on Google Maps</strong></a></p>
<p data-start="1566" data-end="1619">🚫 <strong data-start="1569" data-end="1590">Reservations only</strong> — Walk-ins are not allowed</p>
</div>

	
	<form class="wanderlust-booking-form" method="post" action="">
    <input type="hidden" name="booking_form" value="1">

    <input type="hidden" name="product_name"
           value="3 Bedroom Villa – Deluxe Room (Rustic Vintage Style)">

    <p class="form-row">
        <label><b>Full Name *</b></label>
        <input type="text" name="full_name" required>
    </p>

    <p class="form-row">
        <label><b>Email Address *</b></label>
        <input type="email" name="email" required>
    </p>

    <p class="form-row">
        <label><b>Phone Number *</b></label>
        <input type="text" name="phone" required>
    </p>

    <p class="form-row">
        <label><b>From Date *</b></label>
        <input type="date" name="from_date" required>
    </p>

    <p class="form-row">
        <label><b>To Date *</b></label>
        <input type="date" name="to_date" required>
    </p>

    <p class="form-row">
        <label><b>Number of Guests</b></label>
        <input type="number" name="guests" min="1" value="2">
    </p>

    <button type="submit" class="btn-book-now">Book Now</button>
</form>


	
<div class="product_meta">

	
	
	<span class="posted_in">Categories: <a href="./../../product-category/booking/index.html" rel="tag">Booking</a>, <a href="./../../product-category/wander-stays/index.html" rel="tag">Wander Stays</a></span>
	<span class="tagged_as">Tags: <a href="./../../product-tag/deluxe-stay/index.html" rel="tag">Deluxe Stay</a>, <a href="./../../product-tag/family-rooms/index.html" rel="tag">Family Rooms</a>, <a href="./../../product-tag/luxury-rooms/index.html" rel="tag">Luxury Rooms</a>, <a href="./../../product-tag/mountain-view-stay/index.html" rel="tag">Mountain View Stay</a>, <a href="./../../product-tag/romantic-getaway/index.html" rel="tag">Romantic Getaway</a>, <a href="./../../product-tag/rooms-in-murree/index.html" rel="tag">Rooms in Murree</a></span>
	
</div>
	</div>

	
	<div class="woocommerce-tabs wc-tabs-wrapper">
		<ul class="tabs wc-tabs" role="tablist">
							<li role="presentation" class="description_tab" id="tab-title-description">
					<a href="#tab-description" role="tab" aria-controls="tab-description">
						Description					</a>
				</li>
					</ul>
					<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--description panel entry-content wc-tab" id="tab-description" role="tabpanel" aria-labelledby="tab-title-description">
				
	<h2>Description</h2>

<p data-start="249" data-end="557">Experience a blend of luxury and warmth in our <strong data-start="296" data-end="311">Deluxe Room</strong>, designed in a <strong data-start="327" data-end="351">rustic vintage style</strong> to bring comfort and charm together.<br data-start="388" data-end="391">Located within our scenic villa in <strong data-start="426" data-end="451">Kuldana Hills, Murree</strong>, this room is perfect for guests who want a premium stay with cozy interiors and peaceful surroundings.</p>
<p data-start="559" data-end="867">Guests can relax in beautifully designed spaces and enjoy full access to the <strong data-start="636" data-end="658">shared living area</strong>, which includes fun indoor games like <strong data-start="697" data-end="736">snooker, foosball, and table tennis</strong>.<br data-start="737" data-end="740">It’s an ideal choice for couples or small families seeking both elegance and comfort in the heart of Murree’s natural beauty.</p>
<hr data-start="869" data-end="872">
<h3 data-start="874" data-end="899"><strong data-start="878" data-end="899">Included Services</strong></h3>
<ul data-start="900" data-end="1185">
<li data-start="900" data-end="927">
<p data-start="902" data-end="927">Complimentary breakfast</p>
</li>
<li data-start="928" data-end="981">
<p data-start="930" data-end="981">Mini tea bar with assorted teas and bottled water</p>
</li>
<li data-start="982" data-end="1009">
<p data-start="984" data-end="1009">High-quality toiletries</p>
</li>
<li data-start="1010" data-end="1042">
<p data-start="1012" data-end="1042">Bluetooth speakers for music</p>
</li>
<li data-start="1043" data-end="1076">
<p data-start="1045" data-end="1076">Dedicated work desk and chair</p>
</li>
<li data-start="1077" data-end="1128">
<p data-start="1079" data-end="1128">Flat-screen TV and reading books for relaxation</p>
</li>
<li data-start="1129" data-end="1185">
<p data-start="1131" data-end="1185">Access to shared indoor games and common living area</p>
</li>
</ul>
<hr data-start="1187" data-end="1190">
<h3 data-start="1192" data-end="1222"><strong data-start="1196" data-end="1222">Shared Villa Amenities</strong></h3>
<ul data-start="1223" data-end="1514">
<li data-start="1223" data-end="1304">
<p data-start="1225" data-end="1304">Living areas with indoor games (shared between Standard &amp; Deluxe Room guests)</p>
</li>
<li data-start="1305" data-end="1364">
<p data-start="1307" data-end="1364">Exclusive private living area for Executive Room guests</p>
</li>
<li data-start="1365" data-end="1436">
<p data-start="1367" data-end="1436">Indoor entertainment: snooker, foosball, table tennis &amp; board games</p>
</li>
<li data-start="1437" data-end="1514">
<p data-start="1439" data-end="1514">Outdoor spaces surrounded by nature — perfect for evening tea or bonfires</p>
</li>
</ul>
			</div>
		
			</div>


	<section class="related products">

					<h2>Related products</h2>
				<ul class="products columns-4">

			
					<li class="product type-product post-1701 status-publish first instock product_cat-booking product_cat-wander-stays product_tag-arcadian-cave-room product_tag-cave-room product_tag-luxury-rooms product_tag-luxury-rooms-in-murree product_tag-nature-getaway product_tag-romantic-stays has-post-thumbnail virtual purchasable product-type-mwb_booking">
	<a href="./../arcadian-cave-room-ultimate-adventure-experience/index.html" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"><img width="300" height="300" src="./../../wp-content/uploads/2025/10/Arcadian-Cave-Room-4-1-300x300.webp" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="Arcadian Cave Room – Ultimate Adventure Experience" decoding="async" srcset="./../../wp-content/uploads/2025/10/Arcadian-Cave-Room-4-1-300x300.webp 300w, ./../../wp-content/uploads/2025/10/Arcadian-Cave-Room-4-1-150x150.webp 150w, ./../../wp-content/uploads/2025/10/Arcadian-Cave-Room-4-1-100x100.webp 100w" sizes="(max-width: 300px) 100vw, 300px"><h2 class="woocommerce-loop-product__title">Arcadian Cave Room – Ultimate Adventure Experience</h2>
	<span class="price"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">₨</span> 22,500</bdi></span></span>
</a><a class="button" href="./../arcadian-cave-room-ultimate-adventure-experience/index.html">View Details</a>	<span id="woocommerce_loop_add_to_cart_link_describedby_1701" class="screen-reader-text">
			</span>
</li>

			
					<li class="product type-product post-1699 status-publish instock product_cat-booking product_cat-glamps product_cat-wander-stays product_tag-glamps product_tag-luxury-glamps product_tag-luxury-stays-in-murree product_tag-nature-glamps has-post-thumbnail virtual purchasable product-type-mwb_booking">
	<a href="./../glamp-vega-natures-embrace/index.html" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"><img width="300" height="300" src="./../../wp-content/uploads/2025/10/DSC_0567-ezgif.com-webp-to-jpg-converter-300x300.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="Glamp Vega – Nature’s Embrace" decoding="async" srcset="./../../wp-content/uploads/2025/10/DSC_0567-ezgif.com-webp-to-jpg-converter-300x300.jpg 300w, ./../../wp-content/uploads/2025/10/DSC_0567-ezgif.com-webp-to-jpg-converter-150x150.jpg 150w, ./../../wp-content/uploads/2025/10/DSC_0567-ezgif.com-webp-to-jpg-converter-100x100.jpg 100w" sizes="(max-width: 300px) 100vw, 300px"><h2 class="woocommerce-loop-product__title">Glamp Vega – Nature’s Embrace</h2>
	<span class="price"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">₨</span> 28,000</bdi></span></span>
</a><a class="button" href="./../glamp-vega-natures-embrace/index.html">View Details</a>	<span id="woocommerce_loop_add_to_cart_link_describedby_1699" class="screen-reader-text">
			</span>
</li>

			
					<li class="product type-product post-1697 status-publish instock product_cat-booking product_cat-glamps product_cat-wander-stays product_tag-celestial-glamp product_tag-glamp-aurora product_tag-glamps product_tag-luxury-glamps product_tag-luxury-glamps-in-murree product_tag-romantic-glamps has-post-thumbnail virtual purchasable product-type-mwb_booking">
	<a href="./../1697/index.html" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"><img width="300" height="300" src="./../../wp-content/uploads/2025/10/DSC_0562-ezgif.com-webp-to-jpg-converter-1-300x300.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="Glamp Aurora – A Celestial Escape" decoding="async" srcset="./../../wp-content/uploads/2025/10/DSC_0562-ezgif.com-webp-to-jpg-converter-1-300x300.jpg 300w, ./../../wp-content/uploads/2025/10/DSC_0562-ezgif.com-webp-to-jpg-converter-1-150x150.jpg 150w, ./../../wp-content/uploads/2025/10/DSC_0562-ezgif.com-webp-to-jpg-converter-1-100x100.jpg 100w" sizes="(max-width: 300px) 100vw, 300px"><h2 class="woocommerce-loop-product__title">Glamp Aurora – A Celestial Escape</h2>
	<span class="price"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">₨</span> 28,000</bdi></span></span>
</a><a class="button" href="./../1697/index.html">View Details</a>	<span id="woocommerce_loop_add_to_cart_link_describedby_1697" class="screen-reader-text">
			</span>
</li>

			
					<li class="product type-product post-1692 status-publish last instock product_cat-booking product_cat-glamps product_cat-wander-stays product_tag-couple-getaway product_tag-glamp-flora product_tag-glamps-in-murree product_tag-luxury-glamps product_tag-nature-retreat product_tag-romantic-stays has-post-thumbnail virtual purchasable product-type-mwb_booking">
	<a href="./../glamp-flora-elegance-in-bloom/index.html" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"><img width="300" height="300" src="./../../wp-content/uploads/2025/10/DSC_0597-ezgif.com-webp-to-jpg-converter-2-300x300.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="Glamp Flora – Elegance in Bloom" decoding="async" srcset="./../../wp-content/uploads/2025/10/DSC_0597-ezgif.com-webp-to-jpg-converter-2-300x300.jpg 300w, ./../../wp-content/uploads/2025/10/DSC_0597-ezgif.com-webp-to-jpg-converter-2-150x150.jpg 150w, ./../../wp-content/uploads/2025/10/DSC_0597-ezgif.com-webp-to-jpg-converter-2-100x100.jpg 100w" sizes="(max-width: 300px) 100vw, 300px"><h2 class="woocommerce-loop-product__title">Glamp Flora – Elegance in Bloom</h2>
	<span class="price"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">₨</span> 22,500</bdi></span></span>
</a><a class="button" href="./../glamp-flora-elegance-in-bloom/index.html">View Details</a>	<span id="woocommerce_loop_add_to_cart_link_describedby_1692" class="screen-reader-text">
			</span>
</li>

			
		</ul>

	</section>
	</div>


		
	</main></div>
	
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
									<p><a href="./../../index.html">HOME</a></p><p><a href="./../../our-journey/index.html">OUR JOURNEY</a></p><p><a href="./../../wander-stays/index.html">WANDER STAYS</a></p><p><a href="./../../testimonials/index.html">TESTIMONIALS</a></p><p><a href="./../../contact-us/index.html">CONTACT US</a></p>								</div>
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
    <a href="https://ataraxydevelopers.com/" target="_blank" style="color: #2EA5B4; text-decoration: none;">Ataraxy Developers</a>
  </div>

  <div>
    <a href="./../../privacy-policy/index.html" style="color: #2EA5B4; text-decoration: none;">Privacy Policy</a> |
    <a href="./../../terms-conditions/index.html" style="color: #2EA5B4; text-decoration: none;">Terms &amp; Conditions</a>
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
		
        		  <li data-tab="login" class="xoo-el-login-tgr" style="order: 1 ">Login</li>
        
		 
			<li data-tab="register" class="xoo-el-reg-tgr" style="order: 2">Sign Up</li>
		
	</ul>
</div>
	
	
		
			
		<div data-section="login" class="xoo-el-section">

			<div class="xoo-el-fields">

				<div class="xoo-el-notice"></div>
				<form class="xoo-el-action-form xoo-el-form-login">

					
					<div class="xoo-el-fields-cont"><div class="xoo-aff-group xoo-aff-cont-text one xoo-aff-cont-required xoo-el-username_cont"><div class="xoo-aff-input-group"><span class="xoo-aff-input-icon fas fa-user-plus"></span><input type="text" class="xoo-aff-required xoo-aff-text" name="xoo-el-username" placeholder="Username / Email" value="" required="	" autocomplete="username"></div></div><div class="xoo-aff-group xoo-aff-cont-password one xoo-aff-cont-required xoo-el-password_cont"><div class="xoo-aff-input-group"><span class="xoo-aff-input-icon fas fa-key"></span><input type="password" class="xoo-aff-required xoo-aff-password" name="xoo-el-password" placeholder="Password" value="" required="	" autocomplete="current-password"><div class="xoo-aff-pw-toggle">
					<span class="xoo-aff-pwtog-show"><i class="far fa-eye"></i></span>
					<span class="xoo-aff-pwtog-hide"><i class="far fa-eye-slash"></i></span>
					</div></div></div></div>
<div class="xoo-aff-group xoo-el-login-btm-fields">
	<label class="xoo-el-form-label">
		<input type="checkbox" name="xoo-el-rememberme" value="forever">
		<span>Remember me</span>
	</label>
	<a class="xoo-el-lostpw-tgr" rel="nofollow" href="#">Forgot Password?</a>
</div>


<input type="hidden" name="_xoo_el_form" value="login">

<button type="submit" class="button btn xoo-el-action-btn xoo-el-login-btn">Sign in</button>

<input type="hidden" name="xoo_el_redirect" value="/product/3-bedroom-villa-deluxe-room-rustic-vintage-style/?simply_static_page=3570">
					
				</form>

				
	
	

	
	
			</div>

		</div>

	
			
		<div data-section="register" class="xoo-el-section">

			<div class="xoo-el-fields">

				<div class="xoo-el-notice"></div>
				<form class="xoo-el-action-form xoo-el-form-register">

					
					<div class="xoo-el-fields-cont"><div class="xoo-aff-group xoo-aff-cont-email one xoo-aff-cont-required xoo_el_reg_email_cont"><div class="xoo-aff-input-group"><span class="xoo-aff-input-icon fas fa-at"></span><input type="email" class="xoo-aff-required xoo-aff-email" name="xoo_el_reg_email" placeholder="Email" value="" required="	" autocomplete="email"></div></div><div class="xoo-aff-group xoo-aff-cont-password one xoo-aff-cont-required xoo_el_reg_pass_cont"><div class="xoo-aff-input-group"><span class="xoo-aff-input-icon fas fa-key"></span><input type="password" class="xoo-aff-required xoo-aff-password" name="xoo_el_reg_pass" placeholder="Password" value="" maxlength="20" minlength="6" required="	" autocomplete="new-password"><div class="xoo-aff-pw-toggle">
					<span class="xoo-aff-pwtog-show"><i class="far fa-eye"></i></span>
					<span class="xoo-aff-pwtog-hide"><i class="far fa-eye-slash"></i></span>
					</div></div></div><div class="xoo-aff-group xoo-aff-cont-password one xoo-aff-cont-required xoo_el_reg_pass_again_cont"><div class="xoo-aff-input-group"><span class="xoo-aff-input-icon fas fa-key"></span><input type="password" class="xoo-aff-required xoo-aff-password" name="xoo_el_reg_pass_again" placeholder="Confirm Password" value="" required="	" autocomplete="new-password"><div class="xoo-aff-pw-toggle">
					<span class="xoo-aff-pwtog-show"><i class="far fa-eye"></i></span>
					<span class="xoo-aff-pwtog-hide"><i class="far fa-eye-slash"></i></span>
					</div></div></div><div class="xoo-aff-group xoo-aff-cont-text onehalf xoo-aff-cont-required xoo_el_reg_fname_cont"><div class="xoo-aff-input-group"><span class="xoo-aff-input-icon far fa-user"></span><input type="text" class="xoo-aff-required xoo-aff-text" name="xoo_el_reg_fname" placeholder="First Name" value="" required="	"></div></div><div class="xoo-aff-group xoo-aff-cont-text onehalf xoo-aff-cont-required xoo_el_reg_lname_cont"><div class="xoo-aff-input-group"><span class="xoo-aff-input-icon far fa-user"></span><input type="text" class="xoo-aff-required xoo-aff-text" name="xoo_el_reg_lname" placeholder="Last Name" value="" required="	"></div></div><div class="xoo-aff-group xoo-aff-cont-checkbox_single one xoo-aff-cont-required xoo_el_reg_terms_cont"><div class="xoo-aff-required xoo-aff-checkbox_single"><label><input type="checkbox" name="xoo_el_reg_terms" class="xoo-aff-required xoo-aff-checkbox_single" value="yes">I accept the <a href="./../../index.html?page_id=3" target="_blank"> Terms of Service and Privacy Policy </a></label></div></div></div>
<input type="hidden" name="_xoo_el_form" value="register">


<button type="submit" class="button btn xoo-el-action-btn xoo-el-register-btn">Sign Up</button>

<input type="hidden" name="xoo_el_redirect" value="/product/3-bedroom-villa-deluxe-room-rustic-vintage-style/?simply_static_page=3570">
					
				</form>

				
	
	

	
	
			</div>

		</div>

	
			
		<div data-section="lostpw" class="xoo-el-section">

			<div class="xoo-el-fields">

				<div class="xoo-el-notice"></div>
				<form class="xoo-el-action-form xoo-el-form-lostpw">

					
					

<span class="xoo-el-form-txt">Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.</span>

<div class="xoo-el-fields-cont"><div class="xoo-aff-group xoo-aff-cont-text one xoo-aff-cont-required user_login_cont"><div class="xoo-aff-input-group"><span class="xoo-aff-input-icon fas fa-user-plus"></span><input type="text" class="xoo-aff-required xoo-aff-text" name="user_login" placeholder="Username / Email" value="" required="	"></div></div></div>

<input type="hidden" name="_xoo_el_form" value="lostPassword">

<input type="hidden" name="_wp_http_referer" value="/product/3-bedroom-villa-deluxe-room-rustic-vintage-style/?simply_static_page=3570">
<button type="submit" class="button btn xoo-el-action-btn xoo-el-lostpw-btn">Email Reset Link</button>
					
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

	<button type="submit" class="button btn xoo-el-code-submit-btn xoo-el-action-btn">Verify</button>

	
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
</div><script type="application/ld+json">{"@context":"https:\/\/schema.org\/","@graph":[{"@context":"https:\/\/schema.org\/","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"item":{"name":"Home","@id":"\/"}},{"@type":"ListItem","position":2,"item":{"name":"Booking","@id":"\/\/product-category\/booking\/"}},{"@type":"ListItem","position":3,"item":{"name":"3 Bedroom Villa \u2013 Deluxe Room (Rustic Vintage Style)","@id":"\/\/product\/3-bedroom-villa-deluxe-room-rustic-vintage-style\/"}}]},{"@context":"https:\/\/schema.org\/","@type":"Product","@id":"\/\/product\/3-bedroom-villa-deluxe-room-rustic-vintage-style\/#product","name":"3 Bedroom Villa \u2013 Deluxe Room (Rustic Vintage Style)","url":"\/\/product\/3-bedroom-villa-deluxe-room-rustic-vintage-style\/","description":"Escape to a peaceful retreat in the Kuldana Hills of Murree, where comfort meets elegance.Our Deluxe Room offers a perfect mix of style, warmth, and luxury \u2014 ideal for couples, families, or anyone looking to enjoy a relaxing stay surrounded by nature.\r\nEnjoy cozy interiors, a private outdoor sitting area, and all the modern comforts that make your vacation special.\r\n\r\n\r\n\r\n\r\nIncluded Services\r\n\u2714 Complimentary breakfast\u2714 Private bonfire and outdoor sitting area\u2714 Premium amenities for a luxurious experience\u2714 24\/7 hot water, heating, and WiFi\u2714 High-end toiletries with 5-star hygiene standards\u2714 Complimentary coffee and bottled water\u2714 Gated and secure free parking\u2714 Indoor and outdoor activities \u2014 Snooker, Foosball, Table Tennis, Cycling, Modified Bikes for Couples &amp;amp; more\u2714 Private terrace and lawn for peaceful relaxation\r\n\r\n\r\n\r\n\r\nAvailable Services (Extra Charges)\r\n\ud83c\udf7d Restaurant, candlelight dinner, and d\u00e9cor setup\ud83d\ude97 Pick &amp;amp; drop or rent-a-car service\ud83c\udfd5 Wildlife camping experience\ud83e\udd57 100% organic food menu\ud83d\udc91 Romantic bike rides\ud83e\uddca Mini fridge (15% additional charge)\ud83e\udd68 Snack bucket\ud83d\udc8d Destination wedding &amp;amp; event arrangements\r\n\r\n\r\n\r\n\ud83d\udccd Location: Kuldana Hills, Murree\ud83d\udd17 View on Google Maps\r\n\ud83d\udeab Reservations only \u2014 Walk-ins are not allowed","image":"\/\/wp-content\/uploads\/2025\/10\/20240823_040252-2048x1536-1-1.webp","sku":1745,"offers":[{"@type":"Offer","priceSpecification":[{"@type":"UnitPriceSpecification","price":"0","priceCurrency":"PKR","valueAddedTaxIncluded":false,"validThrough":"2026-12-31"}],"priceValidUntil":"2026-12-31","availability":"https:\/\/schema.org\/InStock","url":"\/\/product\/3-bedroom-villa-deluxe-room-rustic-vintage-style\/","seller":{"@type":"Organization","name":"wanderlustpakistan.com","url":"\/"}}]}]}</script><style id="elementor-post-10">.elementor-kit-10{--e-global-color-primary:#6EC1E4;--e-global-color-secondary:#54595F;--e-global-color-text:#7A7A7A;--e-global-color-accent:#61CE70;--e-global-typography-primary-font-family:"Roboto";--e-global-typography-primary-font-weight:600;--e-global-typography-secondary-font-family:"Roboto Slab";--e-global-typography-secondary-font-weight:400;--e-global-typography-text-font-family:"Roboto";--e-global-typography-text-font-weight:400;--e-global-typography-accent-font-family:"Roboto";--e-global-typography-accent-font-weight:500;}.elementor-section.elementor-section-boxed > .elementor-container{max-width:1140px;}.e-con{--container-max-width:1140px;}.elementor-widget:not(:last-child){margin-block-end:20px;}.elementor-element{--widgets-spacing:20px 20px;--widgets-spacing-row:20px;--widgets-spacing-column:20px;}{}h1.entry-title{display:var(--page-title-display);}@media(max-width:1024px){.elementor-section.elementor-section-boxed > .elementor-container{max-width:1024px;}.e-con{--container-max-width:1024px;}}@media(max-width:767px){.elementor-section.elementor-section-boxed > .elementor-container{max-width:767px;}.e-con{--container-max-width:767px;}}</style>
<div id="photoswipe-fullscreen-dialog" class="pswp" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true" aria-label="Full screen image">
	<div class="pswp__bg"></div>
	<div class="pswp__scroll-wrap">
		<div class="pswp__container">
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
		</div>
		<div class="pswp__ui pswp__ui--hidden">
			<div class="pswp__top-bar">
				<div class="pswp__counter"></div>
				<button class="pswp__button pswp__button--zoom" aria-label="Zoom in/out"></button>
				<button class="pswp__button pswp__button--fs" aria-label="Toggle fullscreen"></button>
				<button class="pswp__button pswp__button--share" aria-label="Share"></button>
				<button class="pswp__button pswp__button--close" aria-label="Close (Esc)"></button>
				<div class="pswp__preloader">
					<div class="pswp__preloader__icn">
						<div class="pswp__preloader__cut">
							<div class="pswp__preloader__donut"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
				<div class="pswp__share-tooltip"></div>
			</div>
			<button class="pswp__button pswp__button--arrow--left" aria-label="Previous (arrow left)"></button>
			<button class="pswp__button pswp__button--arrow--right" aria-label="Next (arrow right)"></button>
			<div class="pswp__caption">
				<div class="pswp__caption__center"></div>
			</div>
		</div>
	</div>
</div>
	<script type='text/javascript'>
		(function () {
			var c = document.body.className;
			c = c.replace(/woocommerce-no-js/, 'woocommerce-js');
			document.body.className = c;
		})();
	</script>
	<link rel="stylesheet" id="wc-blocks-style-css" href="./../../wp-content/plugins/woocommerce/assets/client/blocks/wc-blocks.css?ver=wc-10.3.3" type="text/css" media="all">
<link rel="stylesheet" id="elementor-gf-roboto-css" href="https://fonts.googleapis.com/css?family=Roboto:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&amp;display=swap" type="text/css" media="all">
<link rel="stylesheet" id="elementor-gf-robotoslab-css" href="https://fonts.googleapis.com/css?family=Roboto+Slab:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&amp;display=swap" type="text/css" media="all">
<script type="text/javascript" id="xoo-el-js-js-extra">
/* <![CDATA[ */
var xoo_el_localize = {"adminurl":"\/\/wp-admin\/admin-ajax.php","redirectDelay":"300","html":{"spinner":"<i class=\"xoo-el-icon-spinner8 xoo-el-spinner\"><\/i>","editField":"<span class=\"xoo-el-edit-em\">Change?<\/span>","notice":{"error":"<div class=\"xoo-el-notice-error \">%s<\/div>","success":"<div class=\"xoo-el-notice-success \">%s<\/div>"}},"autoOpenPopup":"no","autoOpenPopupOnce":"no","aoDelay":"500","loginClass":"","registerClass":"","errorLog":"no","resetPwPattern":"code","resend_wait":"90","preventClosing":"","hasCodeForms":"1","checkout":{"loginEnabled":"yes","loginRedirect":"\/product\/3-bedroom-villa-deluxe-room-rustic-vintage-style\/?simply_static_page=3570"}};
/* ]]> */
</script>
<script type="text/javascript" src="./../../wp-content/plugins/easy-login-woocommerce/assets/js/xoo-el-js.js?ver=3.0.1" id="xoo-el-js-js"></script>
<script type="text/javascript" src="./../../wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/flatpickr/dist/flatpickr.min.js?ver=1763880593" id="flatpicker_js-js"></script>
<script type="text/javascript" src="./../../wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/flatpickr/dist/l10n/default.js?ver=1763880593" id="wps-flatpickr-locale-js"></script>
<script type="text/javascript" id="bookings-for-woocommercepublic-js-extra">
/* <![CDATA[ */
var mwb_mbfw_public_obj = {"today_date":"23-11-2025","wrong_order_date_1":"To date can not be less than from date.","wrong_order_date_2":"From date can not be greater than To date.","daily_start_time":"","daily_end_time":"","upcoming_holiday":["1970-01-01"],"is_pro_active":"","booking_product":"","wps_cal_type":"dual_cal","wps_available_slots":[{"_from":"9:00","_to":"10:00"}],"booking_unit":"day","booking_unavailable":[],"single_available_dates":["2025-11-23","2025-11-24","2025-11-25","2025-11-26","2025-11-27","2025-11-28","2025-11-29","2025-11-30","2025-12-01","2025-12-02","2025-12-03","2025-12-04","2025-12-05","2025-12-06","2025-12-07","2025-12-08","2025-12-09","2025-12-10","2025-12-11","2025-12-12","2025-12-13","2025-12-14","2025-12-15","2025-12-16","2025-12-17","2025-12-18","2025-12-19","2025-12-20","2025-12-21","2025-12-22","2025-12-23","2025-12-24","2025-12-25","2025-12-26","2025-12-27","2025-12-28","2025-12-29","2025-12-30","2025-12-31","2025-11-23","2025-11-24","2025-11-25","2025-11-26","2025-11-27","2025-11-28","2025-11-29","2025-11-30","2025-12-01","2025-12-02","2025-12-03","2025-12-04","2025-12-05","2025-12-06","2025-12-07","2025-12-08","2025-12-09","2025-12-10","2025-12-11","2025-12-12","2025-12-13","2025-12-14","2025-12-15","2025-12-16","2025-12-17","2025-12-18","2025-12-19","2025-12-20","2025-12-21","2025-12-22","2025-12-23","2025-12-24","2025-12-25","2025-12-26","2025-12-27","2025-12-28","2025-12-29","2025-12-30","2025-12-31"],"single_available_dates_till":"","today_date_check":"2025-11-23","single_unavailable_dates":[],"date_format":"F j, Y","single_unavailable_prices":[],"wps_single_dates_temp":[],"wps_single_dates_temp_dual":[],"mwb_mbfw_show_date_with_time":"yes","booking_slot_array_max_limit":[],"validation_message":"Please select valid date!","is_mobile_device":"desktop","wps_mbfw_day_and_days_upto_togather_enabled":"","wps_diaplay_time_format":"tewentyfour_hour","firstDayOf_Week":"","hide_or_disable_slot":"hide_slot","lang":"default"};
/* ]]> */
</script>
<script type="text/javascript" src="./../../wp-content/plugins/mwb-bookings-for-woocommerce/public/js/mwb-public.js?ver=1763880593" id="bookings-for-woocommercepublic-js"></script>
<script type="text/javascript" id="mwb-mbfw-common-js-js-extra">
/* <![CDATA[ */
var mwb_mbfw_common_obj = {"ajax_url":"\/\/wp-admin\/admin-ajax.php","nonce":"e7b49095cc","minDate":"23-11-2025 06:11","minTime":"06:11","maxTime":"24\/11\/202500:00","date_time_format":"Please choose the dates from calendar with correct format, wrong format can not be entered","date_format":"F j, Y","is_single_cal":"","cancel_booking_order":"Are you sure to cancel Booking order?","holiday_alert":"It looks like some dates are not available in between the dates choosen by you! , please select available dates!"};
/* ]]> */
</script>
<script type="text/javascript" src="./../../wp-content/plugins/mwb-bookings-for-woocommerce/common/js/mwb-common.js?ver=3.9.0" id="mwb-mbfw-common-js-js"></script>
<script type="text/javascript" src="./../../wp-includes/js/jquery/ui/core.min.js?ver=1.13.3" id="jquery-ui-core-js"></script>
<script type="text/javascript" src="./../../wp-includes/js/jquery/ui/datepicker.min.js?ver=1.13.3" id="jquery-ui-datepicker-js"></script>
<script type="text/javascript" id="jquery-ui-datepicker-js-after">
/* <![CDATA[ */
jQuery(function(jQuery){jQuery.datepicker.setDefaults({"closeText":"Close","currentText":"Today","monthNames":["January","February","March","April","May","June","July","August","September","October","November","December"],"monthNamesShort":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],"nextText":"Next","prevText":"Previous","dayNames":["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],"dayNamesShort":["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],"dayNamesMin":["S","M","T","W","T","F","S"],"dateFormat":"MM d, yy","firstDay":1,"isRTL":false});});
/* ]]> */
</script>
<script type="text/javascript" src="./../../wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/user-friendly-time-picker/dist/js/timepicker.min.js?ver=3.9.0" id="mwb-mbfw-time-picker-js-js"></script>
<script type="text/javascript" src="./../../wp-includes/js/dist/vendor/moment.min.js?ver=2.30.1" id="moment-js"></script>
<script type="text/javascript" id="moment-js-after">
/* <![CDATA[ */
moment.updateLocale( 'en_US', {"months":["January","February","March","April","May","June","July","August","September","October","November","December"],"monthsShort":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],"weekdays":["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],"weekdaysShort":["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],"week":{"dow":1},"longDateFormat":{"LT":"g:i a","LTS":null,"L":null,"LL":"F j, Y","LLL":"F j, Y g:i a","LLLL":null}} );
/* ]]> */
</script>
<script type="text/javascript" src="./../../wp-includes/js/dist/hooks.min.js?ver=4d63a3d491d11ffd8ac6" id="wp-hooks-js"></script>
<script type="text/javascript" src="./../../wp-includes/js/dist/deprecated.min.js?ver=e1f84915c5e8ae38964c" id="wp-deprecated-js"></script>
<script type="text/javascript" src="./../../wp-includes/js/dist/date.min.js?ver=85ff222add187a4e358f" id="wp-date-js"></script>
<script type="text/javascript" id="wp-date-js-after">
/* <![CDATA[ */
wp.date.setSettings( {"l10n":{"locale":"en_US","months":["January","February","March","April","May","June","July","August","September","October","November","December"],"monthsShort":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],"weekdays":["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],"weekdaysShort":["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],"meridiem":{"am":"am","pm":"pm","AM":"AM","PM":"PM"},"relative":{"future":"%s from now","past":"%s ago","s":"a second","ss":"%d seconds","m":"a minute","mm":"%d minutes","h":"an hour","hh":"%d hours","d":"a day","dd":"%d days","M":"a month","MM":"%d months","y":"a year","yy":"%d years"},"startOfWeek":1},"formats":{"time":"g:i a","date":"F j, Y","datetime":"F j, Y g:i a","datetimeAbbreviated":"M j, Y g:i a"},"timezone":{"offset":0,"offsetFormatted":"0","string":"","abbr":""}} );
/* ]]> */
</script>
<script type="text/javascript" src="./../../wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/moment-js/moment-locale-js.js?ver=3.9.0" id="moment-locale-js-js"></script>
<script type="text/javascript" src="./../../wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/datetimepicker-master/build/jquery.datetimepicker.full.js?ver=3.9.0" id="datetime-picker-js-js"></script>
<script type="text/javascript" src="./../../wp-content/plugins/mwb-bookings-for-woocommerce/package/lib/multiple-datepicker/jquery-ui.multidatespicker.js?ver=1763880593" id="mwb-bfwp-multi-date-picker-js-js"></script>
<script type="text/javascript" src="./../../wp-content/plugins/elementor/assets/js/webpack.runtime.min.js?ver=3.32.4" id="elementor-webpack-runtime-js"></script>
<script type="text/javascript" src="./../../wp-content/plugins/elementor/assets/js/frontend-modules.min.js?ver=3.32.4" id="elementor-frontend-modules-js"></script>
<script type="text/javascript" id="elementor-frontend-js-before">
/* <![CDATA[ */
var elementorFrontendConfig = {"environmentMode":{"edit":false,"wpPreview":false,"isScriptDebug":false},"i18n":{"shareOnFacebook":"Share on Facebook","shareOnTwitter":"Share on Twitter","pinIt":"Pin it","download":"Download","downloadImage":"Download image","fullscreen":"Fullscreen","zoom":"Zoom","share":"Share","playVideo":"Play Video","previous":"Previous","next":"Next","close":"Close","a11yCarouselPrevSlideMessage":"Previous slide","a11yCarouselNextSlideMessage":"Next slide","a11yCarouselFirstSlideMessage":"This is the first slide","a11yCarouselLastSlideMessage":"This is the last slide","a11yCarouselPaginationBulletMessage":"Go to slide"},"is_rtl":false,"breakpoints":{"xs":0,"sm":480,"md":768,"lg":1025,"xl":1440,"xxl":1600},"responsive":{"breakpoints":{"mobile":{"label":"Mobile Portrait","value":767,"default_value":767,"direction":"max","is_enabled":true},"mobile_extra":{"label":"Mobile Landscape","value":880,"default_value":880,"direction":"max","is_enabled":false},"tablet":{"label":"Tablet Portrait","value":1024,"default_value":1024,"direction":"max","is_enabled":true},"tablet_extra":{"label":"Tablet Landscape","value":1200,"default_value":1200,"direction":"max","is_enabled":false},"laptop":{"label":"Laptop","value":1366,"default_value":1366,"direction":"max","is_enabled":false},"widescreen":{"label":"Widescreen","value":2400,"default_value":2400,"direction":"min","is_enabled":false}},"hasCustomBreakpoints":false},"version":"3.32.4","is_static":false,"experimentalFeatures":{"e_font_icon_svg":true,"additional_custom_breakpoints":true,"container":true,"e_pro_free_trial_popup":true,"nested-elements":true,"home_screen":true,"global_classes_should_enforce_capabilities":true,"e_variables":true,"cloud-library":true,"e_opt_in_v4_page":true,"import-export-customization":true},"urls":{"assets":"\/\/wp-content\/plugins\/elementor\/assets\/","ajaxurl":"\/\/wp-admin\/admin-ajax.php","uploadUrl":"\/\/wp-content\/uploads"},"nonces":{"floatingButtonsClickTracking":"f0f492a412"},"swiperClass":"swiper","settings":{"page":[],"editorPreferences":[]},"kit":{"active_breakpoints":["viewport_mobile","viewport_tablet"],"global_image_lightbox":"yes","lightbox_enable_counter":"yes","lightbox_enable_fullscreen":"yes","lightbox_enable_zoom":"yes","lightbox_enable_share":"yes","lightbox_title_src":"title","lightbox_description_src":"description"},"post":{"id":1745,"title":"3%20Bedroom%20Villa%20%E2%80%93%20Deluxe%20Room%20%28Rustic%20Vintage%20Style%29%20%E2%80%93%20wanderlustpakistan.com","excerpt":"<p data-start=\"254\" data-end=\"518\">Escape to a peaceful retreat in the <strong data-start=\"290\" data-end=\"317\">Kuldana Hills of Murree<\/strong>, where comfort meets elegance.<br data-start=\"348\" data-end=\"351\" \/>Our <strong data-start=\"355\" data-end=\"370\">Deluxe Room<\/strong> offers a perfect mix of style, warmth, and luxury \u2014 ideal for couples, families, or anyone looking to enjoy a relaxing stay surrounded by nature.<\/p>\r\n<p data-start=\"520\" data-end=\"636\">Enjoy cozy interiors, a private outdoor sitting area, and all the modern comforts that make your vacation special.<\/p>\r\n\r\n\r\n<hr data-start=\"638\" data-end=\"641\" \/>\r\n\r\n<h3 data-start=\"643\" data-end=\"670\"><strong data-start=\"647\" data-end=\"668\">Included Services<\/strong><\/h3>\r\n<p data-start=\"671\" data-end=\"1126\">\u2714 Complimentary breakfast<br data-start=\"696\" data-end=\"699\" \/>\u2714 Private bonfire and outdoor sitting area<br data-start=\"741\" data-end=\"744\" \/>\u2714 Premium amenities for a luxurious experience<br data-start=\"790\" data-end=\"793\" \/>\u2714 24\/7 hot water, heating, and WiFi<br data-start=\"828\" data-end=\"831\" \/>\u2714 High-end toiletries with 5-star hygiene standards<br data-start=\"882\" data-end=\"885\" \/>\u2714 Complimentary coffee and bottled water<br data-start=\"925\" data-end=\"928\" \/>\u2714 Gated and secure free parking<br data-start=\"959\" data-end=\"962\" \/>\u2714 Indoor and outdoor activities \u2014 Snooker, Foosball, Table Tennis, Cycling, Modified Bikes for Couples &amp; more<br data-start=\"1071\" data-end=\"1074\" \/>\u2714 Private terrace and lawn for peaceful relaxation<\/p>\r\n\r\n\r\n<hr data-start=\"1128\" data-end=\"1131\" \/>\r\n\r\n<h3 data-start=\"1133\" data-end=\"1177\"><strong data-start=\"1137\" data-end=\"1175\">Available Services (Extra Charges)<\/strong><\/h3>\r\n<p data-start=\"1178\" data-end=\"1460\">\ud83c\udf7d Restaurant, candlelight dinner, and d\u00e9cor setup<br data-start=\"1228\" data-end=\"1231\" \/>\ud83d\ude97 Pick &amp; drop or rent-a-car service<br data-start=\"1267\" data-end=\"1270\" \/>\ud83c\udfd5 Wildlife camping experience<br data-start=\"1300\" data-end=\"1303\" \/>\ud83e\udd57 100% organic food menu<br data-start=\"1328\" data-end=\"1331\" \/>\ud83d\udc91 Romantic bike rides<br data-start=\"1353\" data-end=\"1356\" \/>\ud83e\uddca Mini fridge (15% additional charge)<br data-start=\"1394\" data-end=\"1397\" \/>\ud83e\udd68 Snack bucket<br data-start=\"1412\" data-end=\"1415\" \/>\ud83d\udc8d Destination wedding &amp; event arrangements<\/p>\r\n\r\n\r\n<hr data-start=\"1462\" data-end=\"1465\" \/>\r\n<p data-start=\"1467\" data-end=\"1564\">\ud83d\udccd <strong data-start=\"1470\" data-end=\"1483\">Location:<\/strong> Kuldana Hills, Murree<br data-start=\"1505\" data-end=\"1508\" \/>\ud83d\udd17 <a class=\"decorated-link\" href=\"https:\/\/g.co\/kgs\/UeVT3Bk\" target=\"_new\" rel=\"noopener\" data-start=\"1511\" data-end=\"1562\"><strong data-start=\"1512\" data-end=\"1535\">View on Google Maps<\/strong><\/a><\/p>\r\n<p data-start=\"1566\" data-end=\"1619\">\ud83d\udeab <strong data-start=\"1569\" data-end=\"1590\">Reservations only<\/strong> \u2014 Walk-ins are not allowed<\/p>","featuredImage":"\/\/wp-content\/uploads\/2025\/10\/20240823_040252-2048x1536-1-1-1024x768.webp"}};
/* ]]> */
</script>
<script type="text/javascript" src="./../../wp-content/plugins/elementor/assets/js/frontend.min.js?ver=3.32.4" id="elementor-frontend-js"></script>
<script type="text/javascript" src="./../../wp-content/plugins/royal-elementor-addons/assets/js/lib/particles/particles.js?ver=3.0.6" id="wpr-particles-js"></script>
<script type="text/javascript" src="./../../wp-content/plugins/royal-elementor-addons/assets/js/lib/jarallax/jarallax.min.js?ver=1.12.7" id="wpr-jarallax-js"></script>
<script type="text/javascript" src="./../../wp-content/plugins/royal-elementor-addons/assets/js/lib/parallax/parallax.min.js?ver=1.0" id="wpr-parallax-hover-js"></script>
<script type="text/javascript" src="./../../wp-content/plugins/woocommerce/assets/js/sourcebuster/sourcebuster.min.js?ver=10.3.3" id="sourcebuster-js-js"></script>
<script type="text/javascript" id="wc-order-attribution-js-extra">
/* <![CDATA[ */
var wc_order_attribution = {"params":{"lifetime":1.0e-5,"session":30,"base64":false,"ajaxurl":"\/\/wp-admin\/admin-ajax.php","prefix":"wc_order_attribution_","allowTracking":true},"fields":{"source_type":"current.typ","referrer":"current_add.rf","utm_campaign":"current.cmp","utm_source":"current.src","utm_medium":"current.mdm","utm_content":"current.cnt","utm_id":"current.id","utm_term":"current.trm","utm_source_platform":"current.plt","utm_creative_format":"current.fmt","utm_marketing_tactic":"current.tct","session_entry":"current_add.ep","session_start_time":"current_add.fd","session_pages":"session.pgs","session_count":"udata.vst","user_agent":"udata.uag"}};
/* ]]> */
</script>
<script type="text/javascript" src="./../../wp-content/plugins/woocommerce/assets/js/frontend/order-attribution.min.js?ver=10.3.3" id="wc-order-attribution-js"></script>
<script type="text/javascript" src="./../../wp-content/plugins/royal-elementor-addons/assets/js/lib/perfect-scrollbar/perfect-scrollbar.min.js?ver=0.4.9" id="wpr-popup-scroll-js-js"></script>
<script type="text/javascript" src="./../../wp-content/plugins/royal-elementor-addons/assets/js/lib/dompurify/dompurify.min.js?ver=3.0.6" id="dompurify-js"></script>
<script type="text/javascript" id="wpr-addons-js-js-extra">
/* <![CDATA[ */
var WprConfig = {"ajaxurl":"\/\/wp-admin\/admin-ajax.php","resturl":"\/\/wp-json\/wpraddons\/v1","nonce":"073968aad8","addedToCartText":"was added to cart","viewCart":"View Cart","comparePageID":"","comparePageURL":"\/\/product\/3-bedroom-villa-deluxe-room-rustic-vintage-style\/","wishlistPageID":"","wishlistPageURL":"\/\/product\/3-bedroom-villa-deluxe-room-rustic-vintage-style\/","chooseQuantityText":"Please select the required number of items.","site_key":"","is_admin":"","input_empty":"Please fill out this field","select_empty":"Nothing selected","file_empty":"Please upload a file","recaptcha_error":"Recaptcha Error","woo_shop_ppp":"9","woo_shop_cat_ppp":"9","woo_shop_tag_ppp":"9","is_product_category":"","is_product_tag":""};
/* ]]> */
</script>
<script type="text/javascript" data-cfasync="false" src="./../../wp-content/plugins/royal-elementor-addons/assets/js/frontend.min.js?ver=1.7.1040" id="wpr-addons-js-js"></script>
<script type="text/javascript" src="./../../wp-content/plugins/royal-elementor-addons/assets/js/modal-popups.min.js?ver=1.7.1040" id="wpr-modal-popups-js-js"></script>

</body>
</html>
