<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

$template_color = $this->params->get('template_color'); 

echo '<?xml version="1.0" encoding="utf-8"?'.">\n"; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
	<head>
		<jdoc:include type="head" />
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/colors.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/typography.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $template_color ?>" rel="stylesheet" type="text/css" />
     	<meta name="w1-verification" content="153056983185" />
		<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/default.js"></script>
	</head>
	<body style="background:none;">
		<jdoc:include type="component" />
	</body>
</html>