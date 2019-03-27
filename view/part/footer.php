<?php if(!defined('CONNECTION_TYPE')) die(); ?>

	    </div>
    </div>
    <div id="messages"></div>
		<div id="proto" class="hidden">
			<svg class="loading-icon" viewBox="-10 -10 120 120">
				<circle cx="50" cy="50" r="40" />
			</svg>
		</div>
		<?php SN()->display_errors(); ?>
<?php
$scripts = get_option('scripts');
foreach ($scripts as $script) { ?>
  <script src=".<?php echo $script; ?>"></script>
<?php
}
?>
  </body>
</html>
