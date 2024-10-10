<?php

/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Template
 * @author $Author$
 * @version $Id$
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7]><html lang="<?php echo $language ?>" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]><html lang="<?php echo $language ?>" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]><html lang="<?php echo $language ?>" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="<?php echo $language ?>" class="no-js"> <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<title><?php echo $title ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

	<link rel="stylesheet" href="/css/style.css?v=<?php echo APP_VERSION; ?>">
	<?php if (isset($stylesheets) && is_array($stylesheets)): ?>
		<?php foreach ($stylesheets as $_n => $_stylesheet): ?>
			<link rel="stylesheet" href="/css/<?php echo $_stylesheet; ?>.css?v=<?php echo APP_VERSION; ?>">
		<?php endforeach; ?>
	<?php endif ?>
	<link rel="stylesheet" href="/css/custom.css?v=<?php echo APP_VERSION; ?>">
	<!--[if lt IE 9]>
        <script src="/js/html5shiv.js"></script>
        <![endif]-->
</head>

<body>
	<!-- Header (optional) -->
	<?php echo isset($header) ? $header : null ?>
	<!-- End of optional header -->

	<!-- Notification (optional) -->
	<?php echo isset($notification) ? $notification : null ?>
	<!-- End of optional notification -->

	<!-- Content (required) -->
	<?php echo $content; ?>
	<!-- End of required content -->

	<!-- Footer (optional) -->
	<?php echo isset($footer) ? $footer : null ?>
	<!-- End of optional footer -->

	<script src="/js/jquery-1.11.1.min.js"></script>
	<script src="/js/jquery-ui-1.11.1.min.js"></script>
	<script src="/js/jquery.idTabs.min.js"></script>
	<script src="/js/jquery.form.min.js"></script>
	<script src="/js/jquery-clairvoyant.js"></script>
	<script src="/js/jquery.are-you-sure.js"></script>
	<?php if (isset($javascripts) && is_array($javascripts)): ?>
		<?php foreach ($javascripts as $_n => $_js): ?>
			<script src="<?php echo $_js; ?>.js?v=<?php echo APP_VERSION; ?>"></script>
		<?php endforeach; ?>
	<?php endif ?>
	<script src="/js/script.js?v=<?php echo APP_VERSION; ?>"></script>
</body>

</html>