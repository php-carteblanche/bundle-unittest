<?php
if (empty($stacks) || !is_array($stacks)) $stacks=array();
?>
<?php if (file_exists(_ASSETS.'css/styles.css')): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS.'css/styles.css'; ?>" />
<?php endif; ?>
<?php if (file_exists(_ASSETS.'css/profiler.css')): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS.'css/profiler.css'; ?>" />
<?php endif; ?>
<?php if (file_exists(_ASSETS.'js/scripts.js')): ?>
	<script type="text/javascript" src="<?php echo _ASSETS.'js/scripts.js'; ?>"></script>
<?php endif; ?>

<?php if (!empty($title)) : ?>
	<div id="page_header" class="header">
		<h1><?php echo $title; ?></h1>
	</div>
<?php endif; ?>

	<div id="page_content" class="content">

<div class="debugger">

<?php if (!empty($profiling_info)) : ?>
	<div class="header_info">
		<?php echo $profiling_info; ?>
	</div>
	<br class="clear" />
<?php endif; ?>

<?php if (!empty($stacks)) : ?>
	<?php foreach($stacks as $_id=>$group) : ?>
		<?php if (!empty($group['info'])) { echo $group['info']; } ?>
		<?php if (!empty($group['presentation'])) { echo $group['presentation']; } ?>
		<?php if (count($group['tests'])>0) : ?>
			<?php echo join(' ', $group['tests']); ?>
		<?php endif; ?>
		<?php if (!empty($group['conclusion'])) { echo $group['conclusion']; } ?>
	<br class="clear" />
	<?php endforeach; ?>
<?php endif; ?>

</div>
	</div>

<div class="footer">
[ UserAgent: <?php echo $_SERVER['HTTP_USER_AGENT']; ?> | PHP: <?php echo phpversion(); ?> (<?php echo php_sapi_name(); ?>) ]
</div>
