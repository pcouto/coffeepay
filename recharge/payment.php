<?php
$Id = "";
if (isset($_POST["Id"])) {
    $Id = $_POST["Id"];
}

if ($Id = "") {
    die("No Data available for payment. Try Later.");
}

$Id = $_POST["Id"];
$Terminal = $_POST["Terminal"];
$Establecimiento = $_POST["Establecimiento"];
$Saldo = $_POST["Saldo"];
$Creditos = $_POST["Creditos"];
$PrecioPorDosis = $_POST["PrecioPorDosis"];
$Alive = $_POST["Alive"];
$TotalDosisA = $_POST["TotalDosisA"];
$TotalDosisB = $_POST["TotalDosisB"];
$ParcialDosisA = $_POST["ParcialDosisA"];
$ParcialDosisB = $_POST["ParcialDosisB"];

?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>recargas</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <script>
        vSelected = document.getElementById("selectorPrecios");
    </script>
</head>

<body style="text-align: center;">
    <div style="text-align: center;"></div>
    <h1 style="text-align: center;">CoffeePay Medios de Pago</h1>
    <form action="media.php" method="post">
        <input type="hidden" name="Id" value="<?php echo $Id; ?>">
        <input type="hidden" name="Terminal" value="<?php echo $Terminal; ?>">
        <input type="hidden" name="Establecimiento" value="<?php echo $Establecimiento; ?>">
        <input type="hidden" name="PrecioPorDosis" value="<?php echo $PrecioPorDosis; ?>">
        <input type="hidden" name="Saldo" value="<?php echo $Saldo; ?>">
        <input type="hidden" name="Creditos" value="<?php echo $Creditos; ?>">
        <input type="hidden" name="Alive" value="<?php echo $Alive; ?>">
        <input type="hidden" name="TotalDosisA" value="<?php echo $TotalDosisA; ?>">
        <input type="hidden" name="TotalDosisB" value="<?php echo $TotalDosiB; ?>">
        <input type="hidden" name="ParcialDosisA" value="<?php echo $ParcialDosisA; ?>">
        <input type="hidden" name="ParcialDosisB" value="<?php echo $ParcialDosisB; ?>">
        <p class="border-1 shadow"><br>Verifique que estos son sus datos antes de proceder&nbsp;<br><br>Terminal : &nbsp<?php echo $Terminal ?><label class="form-label" id="Terminal">
            </label><br>Establecimiento :&nbsp <?php echo $Establecimiento ?><label class="form-label" id="Establecimiento"></label><br><br></p>
        <div style="margin: 0px;padding: 0px;margin-top: 28px;"><label class="form-label">Importe a Recargar :&nbsp;</label>
            <select style="text-align: center;" name="Importe">
                <optgroup Id="selectorPrecios" name="selectorPrecios" abel="Valores Validos">
                    <option value="5" selected="">5 Euros</option>
                    <option value="10" selected="">10 Euros</option>
                    <option value="20">20 Euros</option>
                    <option value="30">30 Euros</option>
                    <option value="40">40 Euros</option>
                    <option value="50">50 Euros</option>
                    <option value="60">60 Euros</option>
                    <option value="70">70 Euros</option>
                    <option value="80">80 Euros</option>
                    <option value="90">90 Euros</option>
                    <option value="100">100 Euros</option>
                </optgroup>
            </select>

            
        </div>

        <p class="border-1" style="margin-top: 16px;">Dosis a Agregar :&nbsp;<label class="form-label" id="Dosis"></label></p>
        <button type="submit" class="btn btn-primary btn-lg text-center" style="box-shadow: 3px 3px 20px 0px var(--bs-btn-bg);margin: 18px;">Recargar</button>
    </form>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>