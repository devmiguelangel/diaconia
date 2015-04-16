<?php

$data = array(
	1 => array(
		'name' => 'Free Cover',
		'slug' => 'FC',
		'range' => array(
			1 => array(
				'edad_min' => 15,
				'edad_max' => 49,
				'amount_min' => 1,
				'amount_max' => 55000
			),
			2 => array(
				'edad_min' => 50,
				'edad_max' => 70,
				'amount_min' => 1,
				'amount_max' => 40000
			)
		)
	),
	2 => array(
		'name' => 'Afiliación Automatica',
		'slug' => 'AA',
		'range' => array(
			1 => array(
				'edad_min' => 15,
				'edad_max' => 49,
				'amount_min' => 55001,
				'amount_max' => 350000
			),
			2 => array(
				'edad_min' => 50,
				'edad_max' => 64,
				'amount_min' => 40001,
				'amount_max' => 350000
			)
		)
	),
	3 => array(
		'name' => 'Facultativo',
		'slug' => 'FA',
		'range' => array(
			1 => array(
				'edad_min' => 15,
				'edad_max' => 64,
				'amount_min' => 350000,
				'amount_max' => 900000
			),
			2 => array(
				'edad_min' => 65,
				'edad_max' => 70,
				'amount_min' => 40001,
				'amount_max' => 900000
			)
		)
	)
);

echo json_encode($data);

?>