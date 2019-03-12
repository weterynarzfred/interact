    </div>
    <div id="messages"></div>
<?php
$scripts = get_option('scripts');
foreach ($scripts as $script) { ?>
  <script src=".<?php echo $script; ?>"></script>
<?php
}
?>
  </body>
</html>
