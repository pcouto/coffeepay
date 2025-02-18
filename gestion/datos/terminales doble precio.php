<?php
// Gestion de Terminales 
// para login seguro
include_once '../../login/includes/db_connect.php';
include_once '../../login/includes/functions.php';

$Version = "3.04 22/05/2023";

date_default_timezone_set('Europe/Madrid');

sec_session_start();

if (login_check($mysqli) == false) {
  echo ("No tiene autorizacion para ver esta página");
  die();
}
$userterminal  = htmlentities($_SESSION['terminal']);
$username = htmlentities($_SESSION['username']);
$oldbonos = 0;
$selectedtermimal = "";
// si se pasa un perametro de terminal, se ve solo el terminal seleccionado
$terminal = "";
if (isset($_GET["terminal"])) {
  $terminal = $_GET["terminal"];
}


// -------------------------------------------------
include_once("./config.php");

// include and create object
include(PHPGRID_LIBPATH . "inc/jqgrid_dist.php");

$db_conf = array(
  "type"      => PHPGRID_DBTYPE,
  "server"    => PHPGRID_DBHOST,
  "user"      => PHPGRID_DBUSER,
  "password"  => PHPGRID_DBPASS,
  "database"  => PHPGRID_DBNAME
);

$g = new jqgrid($db_conf);




/*************************************************************************************
              aqui los condicionantes
 **************************************************************************************/
$opt = array(); // Si hace mas de 3 dias que no envia un Alive, lo indica
$opt["column"] = "Diff";
$opt["target"] = "Alive";
$opt["op"] = ">";
$opt["value"] = 3; // you can use placeholder of column name as value
//$f["cellcss"] = "'color':'red' ,'font-weight': 'bold'";
$opt["cellcss"] = "'background-color':'#ff851b','color':'white','opacity':0.4";
$opt_conditions[] = $opt;


$opt = array(); // 
$opt["column"] = "DiasSinVisita";
$opt["op"] = ">";
$opt["value"] = "{VisitDays}"; // you can use placeholder of column name as value
//$opt["css"] = "'background-color':'blue','color':'white','fontWeight':'bold','opacity':1"; 
$opt["css"] = "'background-color':'blue','color':'white','opacity':1";
$opt_conditions[] = $opt;

$opt = array(); // Si el terminal se ha inhabilitado, lo indica
$opt["column"] = "Estado";
$opt["target"] = "Terminal";
$opt["op"] = "=";
$opt["value"] = 0; // you can use placeholder of column name as value
//$opt["class"] = "canceled_row"; // css class name
//$opt["css"] = "'background-color':'red','color':'white','fontWeight':'bold'"; // must use (single quote ') with css attr and value
$opt["css"] = "'background-color':'red','color':'white'"; // must use (single quote ') with css attr and value
$opt_conditions[] = $opt;

$opt = array(); // Si tiene bonos pendientes, lo tiene en 
$opt["column"] = "Bonos";
$opt["op"] = ">";
$opt["value"] = 0; // you can use placeholder of column name as value
$opt["cellcss"] = "'background-color':'#32a852','color':'white','opacity':0.4";
$opt_conditions[] = $opt;

$opt = array(); // 
$opt["column"] = "MoreIcons";
$opt["op"] = "<>";
$opt["value"] = "99999"; // you can use placeholder of column name as value
$opt["cellcss"] = "'background-color':'#32a852','color':'white','opacity':0.4";
$opt_conditions[] = $opt;

$g->set_conditional_css($opt_conditions);
/*************************************************************************************
              aqui las opciones del grid
 **************************************************************************************/
$opt["width"] = "100vw";
$opt["height"] = "68vh";
$opt["altRows"] = true;
$opt["altclass"] = "myAltRowClass";
$opt["hidegrid"] = false;
$opt["rownumbers"] = true;
$opt["rownumWidth"] = 40;
$opt["sortname"] = 'Terminal';
$opt["sortorder"] = "asc";
$opt["autowidth"] = true;
$opt["shrinkToFit"] = true;
$opt["footerrow"] = true;
$opt["rowNum"] = 5000;
$opt["persistsearch"] = false;
$opt["toolbar"] = "bottom";
$opt["edit_options"] = array('width' => '420');

$grid["export"] = array("format" => "pdf", "filename" => "my-file", "heading" => "Invoice Details", "orientation" => "landscape", "paper" => "a4");
$grid["export"]["range"] = "filtered"; // or "all"
$grid["export"]["paged"] = "1";



$opt["search_options"]["tmplNames"] = array("Buscar Entre Fechas", "Importes");
$opt["search_options"]["tmplFilters"] = array(
  array(
    "groupOp" => "AND",
    "rules" => array(
      array("field" => "Date", "op" => "gt", "data" => "Desde"),
      array("field" => "Date", "op" => "lt", "data" => "Hasta"),
    )
  ),
  array(
    "groupOp" => "AND",
    "rules" => array(
      array("field" => "Importe", "op" => "eq", "data" => "5")
    )
  )
);

$opt["onSelectRow"] = "function(ids) { do_onselect(ids); }";

$opt["reloadedit"] = true;
$g->set_options($opt);

$e["on_update"] = array("update_terminal", null, true);
$e["on_after_update"] = array("after_update", null, true);

$e["js_on_load_complete"] = "grid_onload";
// $e["js_on_select_row"] = "select_row";

$g->set_events($e);

if ($terminal == "") {
  $g->select_command = "SELECT * from datos where terminal <> '000001'";
} else {
  $g->select_command = "SELECT * from datos where terminal = '" . $terminal . "' and terminal <> '000001'";
}



// set database table for CRUD operations
$g->table = "datos";



$g->set_actions(array(
  "add" => false, // allow/disallow add
  "edit" => true, // allow/disallow edit
  "delete" => false, // allow/disallow delete
  "view" => false, // allow/disallow view
  "refresh" => true, // show/hide refresh button
  "search" => "advance", // show single/multi field search condition (e.g. simple or advance)
  "autofilter" => true, // show/hide autofilter for search
  "export_excel" => true

));

/***************************************************************************
             Definimos Las Columnas
 ****************************************************************************/

/*$col = array();
            $col["title"] = "Id";
            $col["name"] = "Id";
            $col["width"] = "15";
            $col["hidden"] = false;
            $cols[] = $col;*/


$col = array();
$col["title"] = "Terminal";
$col["name"] = "Terminal";
$col["width"] = "15";
$col["hidden"] = false;
$col["sortable"] = true;
$col["show"] = array("list" => true, "add" => true, "edit" => true, "view" => true, "bulkedit" => false);
$col["editrules"]["readonly"] = true;
$cols[] = $col;

$col = array();
$col["title"] = "Establecimiento";
$col["name"] = "Establecimiento";
$col["width"] = "40";
$col["hidden"] = false;
$col["sortable"] = true;
$col["align"] = "left";
$col["show"] = array("list" => true, "add" => true, "edit" => true, "view" => true, "bulkedit" => false);
$col["editable"] = true;
$col["link"] = "index2.php?terminal={Terminal}";
$col["editrules"]["readonly"] = false;
$cols[] = $col;

$col = array();
$col["title"] = "Usuario";
$col["name"] = "Username";
$col["editable"] = false;
$col["width"] = "40";
$col["sortable"] = false;
$col["align"] = "left";
$col["hidden"] = true;
$cols[] = $col;


$col = array();
$col["title"] = "Password";
$col["name"] = "Password";
$col["width"] = "40";
$col["sortable"] = false;
$col["align"] = "center";
$col["hidden"] = true;

$cols[] = $col;

$col = array();
$col["title"] = "Email";
$col["name"] = "Email";
$col["width"] = "40";
$col["sortable"] = false;
$col["align"] = "left";
$col["editable"] = true;
//$col["show"] = array("list"=>true, "add"=>true, "edit"=>true, "view"=>true, "bulkedit"=>false);
$col["editrules"]["readonly"] = false;
$col["hidden"] = false;
$cols[] = $col;

$col = array();
$col["title"] = "Telefono";
$col["name"] = "Telefono";
$col["width"] = "20";
$col["sortable"] = false;
$col["editable"] = true;
//$col["show"] = array("list"=>true, "add"=>true, "edit"=>true, "view"=>true, "bulkedit"=>false);
$col["align"] = "right";
$cols[] = $col;

$col = array();
$col["title"] = "Creditos";
$col["name"] = "Creditos";
$col["width"] = "12";
$col["sortable"] = false;
$col["editable"] = false;
$col["align"] = "right";
$cols[] = $col;

$col = array();
$col["title"] = "PPDA";
$col["name"] = "PrecioPorDosis";
$col["width"] = "12";
$col["sortable"] = true;
$col["editable"] = true;
$col["formatter"] = "currency";
$col["formatoptions"] = array(
  "prefix" => '',
  "suffix" => " €",
  "thousandsSeparator" => ",",
  "decimalSeparator" => ".",
  "decimalPlaces" => 2
);
$col["align"] = "right";
$cols[] = $col;


$col = array();
$col["title"] = "PPDB";
$col["name"] = "PrecioPorDosisB";
$col["width"] = "12";
$col["sortable"] = true;
$col["editable"] = true;
$col["formatter"] = "currency";
$col["formatoptions"] = array(
  "prefix" => '',
  "suffix" => " €",
  "thousandsSeparator" => ",",
  "decimalSeparator" => ".",
  "decimalPlaces" => 2
);
$col["align"] = "right";
$cols[] = $col;

$col = array();
$col["title"] = "Saldo";
$col["name"] = "Saldo";
$col["width"] = "15";
$col["sortable"] = true;
$col["formatter"] = "currency";
$col["formatoptions"] = array(
  "prefix" => '',
  "suffix" => " €",
  "thousandsSeparator" => ",",
  "decimalSeparator" => ".",
  "decimalPlaces" => 2
);
$col["align"] = "right";
$cols[] = $col;

$col = array();
$col["title"] = "Bonos";
$col["name"] = "Bonos";
$col["width"] = "12";
$col["sortable"] = true;
$col["editable"] = true;
$col["editrules"] = array("custom" => true, "custom_func" => "function(val,label){return IsTermOnLine(val,label);}");
$col["editoptions"] = array("onblur" => "ValidateBonos_Onblur(this)");
$col["align"] = "right";

$cols[] = $col;

$col = array();
$col["title"] = "Total A";
$col["name"] = "TotalDosisA";
$col["width"] = "12";
$col["sortable"] = false;
$col["editable"] = false;
$col["align"] = "right";
$cols[] = $col;

$col = array();
$col["title"] = "Total B";
$col["name"] = "TotalDosisB";
$col["width"] = "12";
$col["sortable"] = false;
$col["editable"] = false;
$col["align"] = "right";
$cols[] = $col;

$col = array();
$col["title"] = "Parcial A";
$col["name"] = "ParcialDosisA";
$col["width"] = "12";
$col["sortable"] = false;
$col["editable"] = false;
$col["align"] = "right";
$cols[] = $col;

$col = array();
$col["title"] = "Parcial B";
$col["name"] = "ParcialDosisB";
$col["width"] = "12";
$col["sortable"] = false;
$col["editable"] = false;
$col["align"] = "right";
$cols[] = $col;
$col = array();

$col = array();
$col["title"] = "PrevData";
$col["name"] = "PrevData";
$col["width"] = "12";
$col["editable"] = true;
$col["align"] = "right";
$col["hidden"] = true;
$cols[] = $col;

$col = array();
$col["title"] = "Per.Visita";
$col["name"] = "VisitDays";
$col["width"] = "12";
$col["sortable"] = false;
$col["editable"] = true;
$col["align"] = "right";
$cols[] = $col;

$col = array();
$col["title"] = "Ult. Caja";
$col["name"] = "LastCash";
$col["formatter"] = "datetime";
$col["formatoptions"] = array("srcformat" => 'Y-m-d H:i:s', "newformat" => 'd/m/Y H:i:s', "opts" => array());
$col["width"] = "28";
$col["align"] = "right";
$cols[] = $col;

$col = array();
$col["title"] = "Dias S/V";
$col["name"] = "DiasSinVisita";
$col["width"] = "12";
$col["hidden"] = false;
$col["align"] = "right";
$cols[] = $col;

$col = array();
$col["title"] = "Alive";
$col["name"] = "Alive";
$col["formatter"] = "datetime";
$col["formatoptions"] = array("srcformat" => 'Y-m-d H:i:s', "newformat" => 'd/m/Y H:i:s', "opts" => array());
$col["width"] = "28";
$col["align"] = "right";
$col["hidden"] = false;
$cols[] = $col;

$col = array();
$col["title"] = "Dias S/C";
$col["name"] = "Diff";
$col["width"] = "12";
$col["hidden"] = false;
$col["sortable"] = false;
$col["align"] = "right";
$cols[] = $col;

$col = array();
$col["title"] = "Estado";
$col["name"] = "Estado";
$col["width"] = "2";
$col["hidden"] = true;
$cols[] = $col;

$col["default"] = $buttons_html;
$cols[] = $col;

$col = array();
$col["title"] = "Actions";
$col["name"] = "act";
$cols[] = $col;

// para los totales

$col = array();
$col["title"] = "Total_Caja";
$col["name"] = "Total_Caja";
$col["width"] = "40";
$col["hidden"] = true;
$cols[] = $col;



$g->set_columns($cols);

$e["on_data_display"] = array("filter_display", "", true);
//$e["on_upload"] = "grid_onupload";

$g->set_events($e);



// custom on_export callback function
function render_pdf($param)
{
  $grid = $param["grid"];
  $arr = $param["data"];

  $html .= "<h1>" . $grid->options["export"]["heading"] . "</h1>";
  $html .= '<table border="0" cellpadding="4" cellspacing="2">';

  $i = 0;
  $total = 0;
  foreach ($arr as $v) {
    $shade = ($i++ % 2) ? 'bgcolor="#efefef"' : '';
    $html .= "<tr>";
    foreach ($v as $k => $d) {
      if ($k == 'total')
        $total += floatval($d);

      // bold header
      if ($i == 1)
        $html .= "<td bgcolor=\"lightgrey\"><strong>" . ucwords($d) . "</strong></td>";
      else
        $html .= "<td $shade>$d</td>";
    }
    $html .= "</tr>";
  }


  $html .= "<tr bgcolor=\"lightgrey\"><td></td><td></td><td align='right'><strong>Total: $total</strong></td><td></td><td></td></tr>";

  $html .= "</table>";

  return $html;
}

// Registro de incidencias

function Reclog($StringToRecord)
{
  $myfile = fopen("Terminales.txt", "a") or die("Unable to open file!");
  fwrite($myfile, $StringToRecord);
  fwrite($myfile, PHP_EOL);
}



// Miramos las diferencias de fechas para mostrar los terminales que llevan mas de 5 dias sin conectarse 
function filter_display($data)
{
  foreach ($data["params"] as &$d) {  // Calculo de los días sin conexion.
    $now = time(); // or your date as well
    $your_date = strtotime($d["Alive"]);
    $datediff = $now - $your_date;
    // El terminal 000001 es solo para la contraseña del admin
    if ($d["Terminal"] <> "000001") {
      $d["Diff"] = intval($datediff / 86400);
    }
    $d["CpTerminal"] = $d["Terminal"];

    // Calculo de los dias sin visita.
    $Last_Visit_Date = strtotime($d["LastCash"]);
    $datediff = $now - $Last_Visit_Date;
    // El terminal 000001 es solo para la contraseña del admin
    if ($d["Terminal"] <> "000001") {
      $d["DiasSinVisita"] = intval($datediff / 86400);
    }
  }

  // Calculamos el saldo total de los terminales 
  $rows = $_GET["jqgrid_page"] * $_GET["rows"];
  $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
  $sord = $_GET['sord']; // get the direction
  global $g;

  // running total
  $result = $g->execute_query("SELECT SUM(saldo) as s from datos as tmp where terminal <> '000001'");
  $rs = $result->GetRows();
  $rs = $rs[0];
  foreach ($data["params"] as &$d) {
    $d["Total_Caja"] = $rs["s"];
  }
}

function after_update($data)
{
  global  $g;

  $Terminal = strip_tags($data["Terminal"]);
  $PPD =  $data["params"]["PrecioPorDosis"];
  $PPDB =  $data["params"]["PrecioPorDosisB"];
  $Bonos =  $data["params"]["Bonos"];
  $Establecimiento = $data["params"]["Establecimiento"];
  $PrevData = '[{"PPD": ' . $PPD . ',"PPDB": ' . $PPDB . ',"Bonos": ' . $Bonos . ', "Est": "' . $Establecimiento . '"}]';
  $Sql = "UPDATE datos SET PrevData = '$PrevData' where Terminal = '$Terminal'";
  $result = $g->execute_query($Sql);
}

function update_terminal($data)
{

  global  $g;
  global $username;
  $Terminal = strip_tags($data["Terminal"]);
  $Establecimiento =  $data["params"]["Establecimiento"];
  $Bonos =  $data["params"]["Bonos"];
  $PrecioPorDosisA =  $data["params"]["PrecioPorDosis"];
  $PrecioPorDosisB =  $data["params"]["PrecioPorDosisB"];
  $Previo = $data["params"]["PrevData"];
  $PrevData =  json_decode($Previo);
  $PrevBonos = $PrevData[0]->{"Bonos"};
  $PrevEstablecimiento = $PrevData[0]->{"Est"};
  $PrevPrecioPorDosisA = $PrevData[0]->{"PPD"};
  $PrevPrecioPorDosisB = $PrevData[0]->{"PPDB"};

  if ($PrevBonos <> $Bonos) {
    if ($PrevBonos <> 0) {
      $Desc = "Cambio de Bonos";
      $Detail = "$PrevBonos -> $Bonos Bonos";
    } else {
      $Desc = "Bonos Añadidos";
      $Detail = "$Bonos Bonos";
    }
    //$Sql = "INSERT INTO journal (Fecha, Terminal,Establecimiento,Operacion, Descripcion,Importe,Notes) VALUES ('".date('Y-m-d H:i:s')."','$Terminal', '$Establecimiento','$Desc','$Detail ',0,'Usuario : $username')";
    $Sql = "INSERT INTO journal (Fecha, Terminal,Establecimiento,Operacion, Descripcion,Importe,Notes) VALUES ('" . date('Y-m-d H:i:s') . "','$Terminal', '$Establecimiento','$Desc','$Detail ',0,'Usuario : Sotocafe')";
    $result = $g->execute_query($Sql);
  }

  if ($PrevPrecioPorDosisA <> $PrecioPorDosisA or $PrevPrecioPorDosisB <> $PrecioPorDosisB) {
    $Desc = "";
    if ($PrevPrecioPorDosisA <> $PrecioPorDosisA) {
      $Desc = "Cambio de Precio A";
      $Detail = "$PrevPrecioPorDosisA -> $PrecioPorDosisA ";
    }
    if ($PrevPrecioPorDosisA = 0) {
      $Desc = "Precio A Establecido";
      $Detail = "$PrecioPorDosisA Euros";
    }

    if ($Desc <> "") {
      $Sql = "INSERT INTO journal (Fecha, Terminal,Establecimiento,Operacion, Descripcion,Importe,Notes) VALUES ('" . date('Y-m-d H:i:s') . "','$Terminal', '$Establecimiento','$Desc','$Detail ',0,'Usuario : Sotocafe')";
      $result = $g->execute_query($Sql);
    }

    $Desc = "";
    if ($PrevPrecioPorDosisB <> $PrecioPorDosisB) {
      $Desc = "Cambio de Precio B";
      $Detail = "$PrevPrecioPorDosisB -> $PrecioPorDosisB";
    }
    if ($PrevPrecioPorDosisB = 0) {
      $Desc = "Precio B Establecido";
      $Detail = " $PrecioPorDosisB Euros";
    }
    if ($Desc <> "") {
      $Sql = "INSERT INTO journal (Fecha, Terminal,Establecimiento,Operacion, Descripcion,Importe,Notes) VALUES ('" . date('Y-m-d H:i:s') . "','$Terminal', '$Establecimiento','$Desc','$Detail ',0,'Usuario : Sotocafe')";
      $result = $g->execute_query($Sql);
    }
  }

  if ($PrevEstablecimiento <> $Establecimiento) {
    if ($PrevEstablecimiento <> "") {
      $Desc = "Cambio de Nombre";
      $Detail = "Antes :$PrevEstablecimiento";
    } else {
      $Desc = "Nombre  Establecido";
      $Detail = "$Establecimiento ";
    }
    $Sql = "INSERT INTO journal (Fecha, Terminal,Establecimiento,Operacion, Descripcion,Importe,Notes) VALUES ('" . date('Y-m-d H:i:s') . "','$Terminal', '$Establecimiento','$Desc','$Detail ',0,'Usuario : Sotocafe')";
    $result = $g->execute_query($Sql);
  }
}


// ****************************************************************************************************************************

// render grid and get html/js output
$out = $g->render("list1");
?>



<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>

  <link rel="stylesheet" href="./lib/js/themes/custom/jquery-ui.custom.css">
  </link>
  <link rel="stylesheet" href="./lib/js/jqgrid/css/ui.jqgrid.css">
  </link>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

  <!-- Grid-->
  <script src="./lib/js/jquery.min.js" type="text/javascript"></script>
  <script src="./lib/js/jqgrid/js/i18n/grid.locale-es.js" type="text/javascript"></script>
  <script src="./lib/js/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
  <script src="./lib/js/themes/jquery-ui.custom.min.js" type="text/javascript"></script>

  <link rel="stylesheet" href="./css/estilos.css">
  <link rel="stylesheet" type="text/css" href="./css/terminales.css" />


  <!-- Bootstrap CSS -->


  <meta charset="utf-8">
  <title>Gestión de Terminales</title>

  <script type="text/javascript">
    var voldbonos = 0;


    /***********************************************************************************
     Para cuando se modifiquen los bonos, advertir de que el terminal esta en linea o no
    ************************************************************************************/
    function IsTermOnLine(value, label) {
      if (value > 0) {
        var grid = $("#list1");
        var selr = jQuery('#list1').jqGrid('getGridParam', 'selrow'); //lo que he seleccionado

        var result; //lo guardo en esta variable
        result = jQuery('#list1').jqGrid('getRowData', selr);
        var LastAlive = result.Alive;

        var rDate = ConvertDate(LastAlive);
        //alert (rDate);
        var now = new Date();
        var TimeDiff = Math.abs((now.getTime() - rDate.getTime()) / (1000));

        var Days = Math.floor(TimeDiff / (86400));
        TimeDiff -= Days * 86400;

        var Horas = Math.floor(TimeDiff / 3600) % 24;
        TimeDiff -= Horas * 3600;

        var Minutes = Math.floor(TimeDiff / 60) % 60;

        alert("Terminal sin conexiona a internet desde hace :\n\n\t " + Days.toLocaleString() + " Dias, " + Horas + " Horas, " + Minutes + " Minutos \t");

        if (confirm('Confirma que quiere Asignar bonos a este terminal?') == true) {
          return [true, ""];
        } else {
          return [false, "Grabación Cancelada"];
        }


      }
      return [true, ""]
    }

    function ConvertDate(initialDate) {
      //Dividimos la fecha primero utilizando el espacio para obtener solo la fecha y el tiempo por separado
      var splitDate = initialDate.split(" ");
      var date = splitDate[0].split("/");
      var time = splitDate[1].split(":");

      // Obtenemos los campos individuales para todas las partes de la fecha
      var dd = date[0];
      var mm = date[1] - 1;
      var yyyy = date[2];
      var hh = time[0];
      var min = time[1];
      var ss = time[2];

      // Creamos la fecha con Javascript
      var fecha = new Date(yyyy, mm, dd, hh, min, ss);

      // De esta manera se puede volver a convertir a un string
      var formattedDate = ("0" + fecha.getFullYear()).slice(-2) + '-' + ("0" + (fecha.getMonth() + 1)).slice(-2) + '-' + fecha.getDate() + ' ' + ("0" + fecha.getHours()).slice(-2) + ':' + ("0" + fecha.getMinutes()).slice(-2) + ':' + ("0" + fecha.getSeconds()).slice(-2);
      //return (formattedDate);
      return (fecha);
    }

    function ValidateBonos_Onblur(o) {

    }

    /***********************************************************************
     Suma de totales
    *************************************************************************/

    // e.g. to show footer summary
    function grid_onload() {

      var grid = $("#list1");
      // suma de importes de las cajas

      sum_Caja = Number(grid.jqGrid('getCol', 'Total_Caja')[0]);

      // 4th arg value of false will disable the using of formatter

      grid.jqGrid('footerData', 'set', {
        LastCash: 'Total Caja : ' + Intl.NumberFormat('eu-EU', {
          style: 'currency',
          currency: 'EUR'
        }).format(sum_Caja)
      }, false);




    }

    function do_onselect(id) {
      //alert ("Hola"); 
      const f = document.getElementById("MRterminal"); // Para el Reset Terminal.
      const g = document.getElementById("MCterminal"); // Para el Cierre de Terminal.
      const h = document.getElementById("MIterminal"); // Para la Cancelacion del terminal.
      f.innerHTML = id;
      g.innerHTML = id;
      h.innerHTML = id;
    }


    function showModal(varmodal) {
      // alert ("Hola " + voldbonos); 
      var v = document.getElementById("MRterminal").innerHTML;
      if (v == "") { // Ojo, en la primer prueba, el contenido de mrterminal era zzx + un espacio, por eso no funcionaba.
        alert("seleccione un terminal primero.")
      } else {
        if (varmodal == "reset") {
          document.getElementById("showmodalreset").click(); // Aqui teniamos problemas al poner $('#Mreset').modal('show');
        } // y la razon es un conflicto con la libreria jquery, ver ejemplo en modalcallsample
        if (varmodal == "cierre") {
          document.getElementById("showmodalcierre").click();
        }
        if (varmodal == "cancel") {
          document.getElementById("showmodalcancel").click();
        }
      }

    }
  </script>


</head>

<body>
  <script>

  </script>
  <header id="pageHeader">
    <p>
    <h6>Listado de terminales.</h6><br></p>

  </header>
  <logo id="pageLogo">
    CAFE DUETAZZE
    <div class="logoc">
      <hr>EMPRESA ANDALUZA</hr>
    </div>
  </logo>

  <article id="mainArticle"><?php echo $out ?></article>

  <nav id="mainNav">
    <p>Menú</p>
    <div class="col" id="menulinks">
      <a class="text-white" href="index2.php">Movimientos</a>
      <a class="text-white" href="terminales.php">Terminales</a>

      <div class="col-md-8 text-center  role='group'">
        <button type="button" class="text-white btn btn-cierre btn-custom" onclick="showModal('cierre')">Cierre de Caja</button>
        <button type="button" id="showmodalcierre" class="text-white btn btn-cierre btn-custom" hidden data-toggle="modal" data-target="#Mcierre">Cierre de Caja</button>

        <button type="button" class="text-white btn btn-reset btn-custom" onclick="showModal('reset')">Reset</button>
        <button type="button" id="showmodalreset" class="text-white btn btn-reset btn-custom" hidden data-toggle="modal" data-target="#Mreset">Reset</button>

        <button type="button" class="text-white btn btn-cancel btn-custom" onclick="showModal('cancel')">Inhabilitar</button>
        <button type="button" id="showmodalcancel" class="text-white btn btn-cancel btn-custom" hidden data-toggle="modal" data-target="#Mcancel">Inhabilitar</button>

      </div>
      <a><a>
          <a class="text-white" href="../../graph/estadisticas/">Estadísticas</a>
          <a class="text-white" href="usuarios.php">Usuarios</a>

    </div>

  </nav>
  <footer id="pageFooter">
    <h6>By Knessen Korps S.L.</h6>
  </footer>
  <?php
  include "./Mreset.html";
  include "./Mcierre.html";
  include "./Mcancel.html";
  ?>
</body>

</html>