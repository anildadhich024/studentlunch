<?php
return [
	"TITLE"	=> [
		"Dr."	=> 101,
		"Mr."	=> 102,
		"Miss."	=> 103,
		"Mr."	=> 104,
		"Ms."	=> 105,
		"Mrs."	=> 106,
	],
  
	"HOLIDAY_TYPE"	=> [
		"SCHOOL HOLIDAY"	=> 107,
		"PUBLIC HOLIDAY"	=> 108, 
	],

	"ORD_TYPE"	=> [
		"PICKUP"	=> 909,
		"DELIVERY"	=> 910, 
	],

    "GST"	=> 0.10,
	
	"WEEK"	=> [
		"MONDAY"	=> 111,
		"TUESDAY"	=> 112,
		"WEDNESDAY"	=> 113,
		"THURSDAY"	=> 114,
		"FRIDAY"	=> 115,
		"SATURDAY"	=> 116,
		"SUNDAY"	=> 117,
	],

    "MONTH"	=> [
		"1"		=> "JAN",
		"2"		=> "FEB",
		"3"		=> "MAR",
		"4"		=> "APR",
		"5"		=> "MAY",
		"6"		=> "JUN",
		"7"		=> "JUL",
		"8"		=> "AUG",
		"9"		=> "SEP",
		"10"	=> "OCT",
		"11"	=> "NOV",
		"12"	=> "DEC",
	],

    "TRANS"	=> [
		"Credit"	=> 101,
		"Debit"		=> 102,
	],

	"MENU_TYPE"	=> [
		"STDNT"		=> 501,
		"TCHR"		=> 502,
		"BOTH"		=> 503,
	],

	"TAX_MTHD"	=> [
		"VAT"				=> 201,
		"GST"				=> 202,
		"NOT-APPLICABLE"	=> 203,
	],
	
	"ORDER_STATUS"	=> [
		"Pending"		=> 211,
		"Delivered"		=> 212,
		"Cancelled"		=> 213,
	],

	"REQ_STATUS"	=> [
		"Pending"		=> 301,
		"Listed"		=> 302, 
	],

	"USER"	=> [
		"PARENT"	=> 801,
		"MILK_BAR"	=> 802,
		"TEACHER"	=> 803,
		"CHILD"		=> 804,
	],

	"CANCEL_REASON_OTHR"	=> [
		"Duplicate Order"	=> 214,
		"Not Available"		=> 215,
		"Other"				=> 216,
	],

	"CANCEL_REASON_CHLD"	=> [
		"Child not well"						=> 217,
		"Child not attending school today"		=> 218,
		"Other"									=> 216,
	],
	
	"STATUS"	=> [
		"BLOCK"		=> 409,
		"UNBLOCK"	=> 410,
	],

	"DEL_STATUS"	=> [
		"DELETED"		=> 609,
		"UNDELETED"		=> 610,
	],

	"MLK_STATUS"	=> [
		"UNACTIVE"		=> 209,
		"ACTIVE"		=> 210,
	],

	"MAIL_STATUS"	=> [
		"UNVERIFIED"	=> 309,
		"VERIFIED"		=> 310,
	],

	"SCHL_TYPE"	=> [
		"Child Care"	=> 501,
		"Kinder" 		=> 502,
		"Primary" 		=> 503,
		"Secondary" 	=> 504,
	],

	"SCHL_ROLE"	=> [
		"Principle"				=> 601,
		"Assistant Principle"	=> 602,
		"School Admin"			=> 603,
		"School Nurse"			=> 604,
		"Business Manager"		=> 605,
		"School Librarian"		=> 606,
		"Teacher"				=> 607,

	],

	"BUSS_TYPE"	=> [
		"Cafe"				=> 701,
		"Milk Bar"			=> 702,
		"Convenience Store"	=> 703,
		"Restaurant"		=> 704,
	],

	"REQ_FROM"	=> [
		"Service_Provide"	=> 'SP',
		"Parent"			=> 'P', 
	],

	"RLTN_IDNO"	=> [
		"Father"	=> 1001,
		"Mother"	=> 1002,
	],

	"PLN_STATUS"	=> [
		"ACTIVE"		=> 117,
		"NON_ACTIVE"	=> 118,
	],

	"PRNT_PLN"	=> [
		"PAID"	=> 804,
		"FREE"	=> 805, 
	],

	'VLDT_MSG' => [
		'required' 			=> 'Required filed missing',
        'unique' 			=> 'Linked with another account',
        'alpha' 			=> 'Only alphabetic characters allowed',
        'digits' 			=> 'Minimum :digits numeric characters allowed',
        'min'				=> 'To sort, at least :min characters',
        'max'				=> 'To long, max :max characters',
        'between' 			=> 'Distance must be at least 0 - 7',
        'accepted' 			=> 'Accept Terms & Conditions',
        'integer' 			=> 'Must be an integer',
        'alpha_dash' 		=> 'Space not allowed',
        'same' 				=> 'Confirm password did not matched.',
        'sLgnPass.regex' 	=> 'Should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.',
        'sCurrPass.regex' 	=> 'Should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.',
        'sBussName.regex'	=> 'Special and numeric characters not allowed',
        'sFrstName.regex'	=> 'Special and numeric characters not allowed',
        'sLstName.regex'	=> 'Special and numeric characters not allowed',
        'sFrstName1.regex'	=> 'Special and numeric characters not allowed',
        'sLstName1.regex'	=> 'Special and numeric characters not allowed',
        'sSbrbName.regex'	=> 'Special and numeric characters not allowed',
        'sSbrbName1.regex'	=> 'Special and numeric characters not allowed',
        'sStrtName.regex'	=> 'Special and numeric characters not allowed',
        'sLgnName.regex'	=> 'Should contain alphabetic and underscore',
        'sCntryName.regex'	=> 'Should contain alphabetic and space',
        'sStateName.regex'	=> 'Should contain alphabetic and space',
        'sEmail.regex'		=> 'Invalid email address, Capital letter not allowed',
        'sLgnEmail.regex'	=> 'Couldn’t find your Account.',
	],
]
?>