<?php

$vals = ['a','b','c','d','e'];
$keys = [0,3];

print_r(
	array_values(array_intersect_key($vals,array_flip($keys)))
);

?>
