<?php

define('MYSQL_DATETIME_FORMAT', 'Y-m-d H:i:s');

define('TWO_DECIMAL_REGEX', '/^\d+(\.\d{1,2})?$/');
define('THREE_DECIMAL_REGEX', '/^\d+(\.\d{1,3})?$/');

define('MIN_STAKE_AMOUNT', 0.3);
define('MAX_STAKE_AMOUNT', 10000);
define('MIN_SELECTIONS', 1);
define('MAX_SELECTIONS', 20);
define('MIN_ODDS', 1);
define('MAX_ODDS', 10000);
define('MAX_WIN_AMOUNT', 20000);
define('PLAYER_DEFAULT_BALANCE', 1000);


define('UNKNOWN_ERROR_CODE', 0);
define('UNKNOWN_ERROR_MESSAGE', 'Unknown error');

define('STRUCTURE_MISMATCH_ERROR_CODE', 1);
define('STRUCTURE_MISMATCH_ERROR_MESSAGE', 'Betslip structure mismatch');

define('MIN_STAKE_AMOUNT_ERROR_CODE', 2);
define('MIN_STAKE_AMOUNT_ERROR_MESSAGE', 'Minimum stake amount is ');

define('MAX_STAKE_AMOUNT_ERROR_CODE', 3);
define('MAX_STAKE_AMOUNT_ERROR_MESSAGE', 'Maximum stake amount is ');

define('MIN_SELECTIONS_ERROR_CODE', 4);
define('MIN_SELECTIONS_ERROR_MESSAGE', 'Minimum number of selections is ');

define('MAX_SELECTIONS_ERROR_CODE', 5);
define('MAX_SELECTIONS_ERROR_MESSAGE', 'Maximum number of selections is ');

define('MIN_ODD_ERROR_CODE', 6);
define('MIN_ODD_ERROR_MESSAGE', 'Minimum odds are ');

define('MAX_ODD_ERROR_CODE', 7);
define('MAX_ODD_ERROR_MESSAGE', 'Maximum odds are ');

define('DUPLICATE_SELECTION_ERROR_CODE', 8);
define('DUPLICATE_SELECTION_ERROR_MESSAGE', 'Duplicate selection found');

define('MAX_WIN_AMOUNT_ERROR_CODE', 9);
define('MAX_WIN_AMOUNT_ERROR_MESSAGE', 'Maximum win amount is ');

define('PRE_ACTION_ERROR_CODE', 10);
define('PRE_ACTION_ERROR_MESSAGE', 'Your previous action is not finished yet');

define('INS_BAL_ERROR_CODE', 11);
define('INS_BAL_ERROR_MESSAGE', 'Insufficient balance');


