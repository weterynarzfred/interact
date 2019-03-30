<?php
function create_slug($name) {
	$name = mb_strtolower($name);
	return preg_replace('/(^[^a-z]|[^a-z0-9-_])/', '_', $name);
}
