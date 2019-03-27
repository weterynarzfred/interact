<?php if(!defined('CONNECTION_TYPE')) die(); ?>

	    </div>
    </div>
    <div id="messages"></div>
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
