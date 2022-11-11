<?php

return [

	
	'payroll_status' => [
		[
			"id" => 0,
			"status" => 'DEFAULT', //grey
			"approve_text" => 'DEFAULT',
			"category" => '',
		],
		[
			"id" => 1,
			"status" => 'PAYROLL ADDED', //blue
			"approve_text" => 'PAYROLL ADDED',
			"category" => 1,
		],
		[
			"id" => 2,
			"status" => 'HR-INCOMPLETE', //red
			"approve_text" => 'HR-INCOMPLETE',
			"category" => 2,
		],
		[
			"id" => 3,
			"status" => 'HR-APPROVED >>> PBO-CHECKING', // green
			"approve_text" => 'HR-APPROVED',
			"category" => 1,
		],
		[
			"id" => 4,
			"status" => 'PBO-CHECKED >>> FOR APPROVAL', // orange
			"approve_text" => 'PBO-CHECKED',
			"category" => 1,
		],
		[
			"id" => 5,
			"status" => 'PBO-APPROVED >>> OPA-CHECKING', // green
			"approve_text" => 'PBO-APPROVED',
			"category" => 1,
		],
		[
			"id" => 6,
			"status" => 'OPA-CHECKED >>> FOR APPROVAL', // orange
			"approve_text" => 'OPA-CHECKED',
			"category" => 1,
		],
		[
			"id" => 7,
			"status" => 'OPA-APPROVED >>> PTO-FOR DEBITING', //green
			"approve_text" => 'OPA-APPROVED',
			"category" => 1,
		],
		[
			"id" => 8,
			"status" => 'CASH ADVANCE PREPARATION', //grey
			"approve_text" => 'CASH ADVANCE PREPARATION',
			"category" => 1,
		],
		[
			"id" => 9,
			"status" => 'CASH READY', //purple
			"approve_text" => 'CASH READY',
			"category" => 1,
		],
		[
			"id" => 10,
			"status" => 'PTO-INCOMPLETE >>> PSU-CHECKING', //red
			"approve_text" => 'PTO INCOMPLETE',
			"category" => 2,
		],
		[
			"id" => 11,
			"status" => 'PAYROLL OFFICER-CHECKED >>> PTO-CHECKING',
			"approve_text" => 'PAYROLL OFFICER-CHECKED >>> PTO-CHECKING',
			"category" => 1,
		],
		[
			"id" => 12,
			"status" => 'OPA-INCOMPLETE >>> PAYROLL OFFICER-CHECKING',
			"approve_text" => 'OPA INCOMPLETE',
			"category" => 2,
		],
		[
			"id" => 13,
			"status" => 'PAYROLL OFFICER-CHECKED >>> OPA-CHECKING',
			"approve_text" => 'PAYROLL OFFICER-CHECKED >>> OPA-CHECKING',
			"category" => 1,
		],
		[
			"id" => 14,
			"status" => 'PBO-INCOMPLETE >>> PAYROLL OFFICER-CHECKING',
			"approve_text" => 'PBO INCOMPLETE',
			"category" => 2,
		],
		[
			"id" => 15,
			"status" => 'PAYROLL OFFICER-CHECKED >>> PBO-CHECKING',
			"approve_text" => 'PAYROLL OFFICER-CHECKED >>> PBO-CHECKING',
			"category" => 1,
		],
		[
			"id" => 16,
			"status" => 'PAYROLL OFFICER-CHECKED >>> HR-CHECKING',
			"approve_text" => 'PAYROLL OFFICER-CHECKED >>> HR-CHECKING',
			"category" => 1,
		],

	],
     // this is status where you can see on the [payroll monitoring page]
	'role_status' => [
		"Admin" => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15,16],
		"PSU" => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15],
		"HR" => [1, 2, 3,16],
		"PTO" => [7,8,9,11],
		"PBO" => [3,15],
		"PBO_HEAD" => [4],
		"OPA" => [5,13],
		"OPA_HEAD" => [6],
		"PAYROLL_OFFICER" => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15,16],
	],

	'role_status_approve' => [
		"Admin" => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16],
		"PSU" => [11,13,15],
		"HR" => [1, 2, 3],
		"PTO" => [8,9,10],
		"PBO" => [4,14],
		"PBO_HEAD" => [5,14],
		"OPA" => [6,12],
		"OPA_HEAD" => [7,12],
		"PAYROLL_OFFICER" => [11,13,15,16],
	],

	'tax' => [
		'percent' => 0.02,
		'excess' => 20833.33
	]


];
