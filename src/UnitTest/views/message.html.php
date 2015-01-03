<?php
/**
 * This file is part of the CarteBlanche PHP framework.
 *
 * (c) Pierre Cassat <me@e-piwi.fr> and contributors
 *
 * License Apache-2.0 <http://github.com/php-carteblanche/carteblanche/blob/master/LICENSE>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
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

<?php if (!empty($message)) : ?>
	<div class="debugger" style="border: 1px solid <?php echo $color; ?>;padding: 0; margin: 10px;">
		<?php echo $message; ?>
	</div>
<?php endif; ?>
