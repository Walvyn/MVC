<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?php echo (isset($title_for_layout))? $title_for_layout : NAME_SITE; ?></title>
    </head>
   	<body>
   		<?php echo $content_for_layout; ?>
	</body>
</html>