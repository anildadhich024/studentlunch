<?php
return [
	"XL_CATG"	=> [
		"SR_NO"			=> 1,
		"CATG_ID"		=> 2,
		"CATG_NAME"		=> 3,
		"CATG_STATUS"	=> 4,
	],

	"XL_ITEM"	=> [
		"SR_NO"			=> 1,
		"ITEM_ID"		=> 2,
		"CATG_NAME"		=> 3,
		"ITEM_NAME"		=> 4,
		"ITEM_PRC"		=> 5,
		"ITEM_DESC"		=> 6,
		"ITEM_DAY"		=> 7,
		"ITEM_STATUS"	=> 8,
	],

	"XL_MILK"	=> [
		"SR_NO"			=> 1,
		"ACC_ID"		=> 2,
		"BUSS_TYPE"		=> 3,
		"BUSS_NAME"		=> 4,
		"ABN_NO"		=> 5,
		"USER_NAME"		=> 6,
		"PHONE_NO"		=> 7,
		"MOBILE_NO"		=> 8,
		"EMAIL_ID"		=> 9,
		"BUSS_ADDR"		=> 10,
		"STRP_ACC"		=> 11,
		"ACC_STATUS"	=> 12,
		"SCHL_TYPE"		=> 13,
		"SCHL_NAME"		=> 14,
		"SCHL_DIST"		=> 15,
		"PIN_CODE"		=> 16,
		"SBRB_NAME"		=> 17,
		"CUT_OFF"		=> 18,
	],

	"XL_PRNT"	=> [
		"SR_NO"			=> 1,
		"ACC_ID"		=> 2,
		"RLTN_NAME"		=> 3,
		"PRNT_NAME"		=> 4,
		"MOBILE_NO"		=> 5,
		"EMAIL_ID"		=> 6,
		"PRNT_ADDR"		=> 7,
		"ACC_STATUS"	=> 8,
		"SCHL_TYPE"		=> 9,
		"SCHL_NAME"		=> 10,
		"CHLD_NAME"		=> 11,
		"CLS_NAME"		=> 12,
	],

	"XL_SCHL"	=> [
		"SR_NO"			=> 1,
		"ACC_ID"		=> 2,
		"SCHL_TYPE"		=> 3,
		"SCHL_NAME"		=> 4,
		"PHONE_NO"		=> 5,
		"MOBILE_NO"		=> 6,
		"EMAIL_ID"		=> 7,
		"SCHL_ADDR"		=> 8,
		"ACC_STATUS"	=> 9,
		"CNCT_ROLE"		=> 10,
		"CNCT_NAME"		=> 11,
		"CNCT_MOBILE"	=> 12,
		"CNCT_PHONE"	=> 13,
		"CNCT_EMAIL"	=> 14,
	],

	"XL_CREDIT" => [
		"SR_NO"			=> 1,
		"TRAN_DATE"		=> 2,
		"ORD_NO"		=> 3,
		"STDNT_NAME"	=> 4,
		"CRDT_AMO"		=> 5,
		"DEBT_AMO"		=> 6,
	],

	"XL_CREDIT_PRNT" => [
		"SR_NO"			=> 1,
		"TRAN_DATE"		=> 2,
		"ORD_NO"		=> 3,
		"STDNT_NAME"	=> 4,
		"MILK_NAME"		=> 5,
		"CRDT_AMO"		=> 6,
		"DEBT_AMO"		=> 7,
	],

	"XL_TCHR_CRDT" => [
		"SR_NO"			=> 1,
		"TRAN_DATE"		=> 2,
		"ORD_NO"		=> 3,
		"MILK_NAME"		=> 4,
		"CRDT_AMO"		=> 5,
		"DEBT_AMO"		=> 6,
	],

	"XL_ORD" => [
		"SR_NO"			=> 1,
		"DEL_DATE"		=> 2,
		"ORD_NO"		=> 3,
		"PRNT_NAME"		=> 4,
		"STDNT_NAME"	=> 5,
		"SCHL_NAME"		=> 6,
		"SUB_AMO"		=> 7,
		"GST_AMO"		=> 8,
		"GRNT_AMO"		=> 9,
		"ORD_STATUS"	=> 10,
		"ITM_NAME"		=> 11,
		"ITM_PRC"		=> 12,
		"ITM_QTY"		=> 13,
		"TTL_PRC"		=> 14,
	],

	"XL_ORD_PRNT" => [
		"SR_NO"			=> 1,
		"DEL_DATE"		=> 2,
		"ORD_NO"		=> 3,
		"ORD_TYPE"		=> 4,
		"MILK_NAME"		=> 5,
		"STDNT_NAME"	=> 6,
		"SUB_AMO"		=> 7,
		"GST_AMO"		=> 8,
		"GRNT_AMO"		=> 9,
		"TRAN_DATE"		=> 10,
		"ORD_STATUS"	=> 11,
		"ITM_NAME"		=> 12,
		"ITM_PRC"		=> 13,
		"ITM_QTY"		=> 14,
		"TTL_PRC"		=> 15,
	],

	"XL_TCHR_ORD" => [
		"SR_NO"			=> 1,
		"DEL_DATE"		=> 2,
		"ORD_NO"		=> 3,
		"MILK_NAME"		=> 4,
		"SUB_AMO"		=> 5,
		"GST_AMO"		=> 6,
		"GRNT_AMO"		=> 7,
		"TRAN_DATE"		=> 8,
		"ORD_STATUS"	=> 9,
		"ITM_NAME"		=> 10,
		"ITM_PRC"		=> 11,
		"ITM_QTY"		=> 12,
		"TTL_PRC"		=> 13,
	],

	"XL_ORD_ADMN" => [
		"SR_NO"			=> 1,
		"DEL_DATE"		=> 2,
		"ORD_NO"		=> 3,
		"ORD_TYPE"		=> 4,
		"STDNT_NAME"	=> 5,
		"SCHL_NAME"		=> 6,
		"MILK_NAME"		=> 7,
		"SUB_AMO"		=> 8,
		"GST_AMO"		=> 9,
		"GRNT_AMO"		=> 10,
		"ORD_STATUS"	=> 11,
		"TRAN_DATE"		=> 12,
		"ITM_NAME"		=> 13,
		"ITM_PRC"		=> 14,
		"ITM_QTY"		=> 15,
		"TTL_PRC"		=> 16,
	],

	"XL_ORD_COMM" => [
		"SR_NO"			=> 1,
		"TRAN_DATE"		=> 2,
		"ORD_NO"		=> 3,
		"STDNT_NAME"	=> 4,
		"ORD_AMO"		=> 5,
		"CRDT_AMO"		=> 6,
		"PAY_AMO"		=> 7,
		"COMM_AMO"		=> 8,
	],

	"XL_COMM_CMRY" => [
		"SR_NO"			=> 1,
		"MILK_NAME"		=> 2,
		"ORD_CNT"		=> 3,
		"SALE_AMO"		=> 4,
		"CRDT_AMO"		=> 5,
		"PAY_AMO"		=> 6,
		"COMM_AMO"		=> 7,
	],

	"XL_TCHR"	=> [
		"SR_NO"			=> 1,
		"ACC_ID"		=> 2,
		"TCHR_NAME"		=> 3,
		"MOBILE_NO"		=> 4,
		"EMAIL_ID"		=> 5,
		"TCHR_ADDR"		=> 6,
		"ACC_STATUS"	=> 7,
		"SCHL_TYPE"		=> 8,
		"SCHL_NAME"		=> 9,
		"SBRB_NAME"		=> 10,
		"PIN_CODE"		=> 11,
		"ROL_NAME"		=> 12,
	],
]
?>