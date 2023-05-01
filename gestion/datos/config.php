<?php

// PHP Grid database connection settings, Only need to update these in new project

define("PHPGRID_DBTYPE","mysqli"); // mysql,oci8(for oracle),mssql,postgres,sybase
define("PHPGRID_DBHOST","localhost");
define("PHPGRID_DBUSER","CoffeePay");
define("PHPGRID_DBPASS","@CoffeePay");
define("PHPGRID_DBNAME","coffeePay");

// Basepath for lib
define("PHPGRID_LIBPATH",dirname(__FILE__).DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR);
?>
