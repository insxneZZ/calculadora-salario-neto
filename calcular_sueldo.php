<?php
function calcular_retencion($salario_bruto, $situacion_familiar, $numero_hijos, $discapacidad, $tipo_contrato)
{
    $retencion = 0;

    if ($situacion_familiar == "soltero") {
        if ($salario_bruto <= 12000) {
            $retencion = 0;
        } elseif ($salario_bruto <= 30000) {
            $retencion = 12;
        } else {
            $retencion = 18;
        }
    } elseif ($situacion_familiar == "casado_sin_hijos") {
        $retencion = ($salario_bruto <= 30000) ? 10 : 16;
    } elseif ($situacion_familiar == "casado_con_hijos") {
        $retencion = ($salario_bruto <= 30000) ? (8 - $numero_hijos) : (14 - $numero_hijos);
    } elseif ($situacion_familiar == "monoparental") {
        $retencion = ($salario_bruto <= 30000) ? (7 - $numero_hijos) : (12 - $numero_hijos);
    }

    if ($discapacidad == "33") {
        $retencion -= 2;
    } elseif ($discapacidad == "65") {
        $retencion -= 4;
    }

    if ($tipo_contrato == "temporal") {
        $retencion += 2;
    }

    return max($retencion, 0);
}

function calcular_seguridad_social($salario_bruto)
{
    $tasa_seguridad_social = 6.35;
    return $salario_bruto * ($tasa_seguridad_social / 100);
}

$salario_bruto = $_POST['salario_bruto'];
$situacion_familiar = $_POST['situacion_familiar'];
$numero_hijos = $_POST['numero_hijos'];
$discapacidad = $_POST['discapacidad'];
$tipo_contrato = $_POST['tipo_contrato'];
$numero_pagas = $_POST['numero_pagas'];

$retencion_irpf = calcular_retencion($salario_bruto, $situacion_familiar, $numero_hijos, $discapacidad, $tipo_contrato);

$cuota_seguridad_social = calcular_seguridad_social($salario_bruto);

$sueldo_neto_anual = ($salario_bruto - $cuota_seguridad_social) * (1 - $retencion_irpf / 100);

$sueldo_neto_mensual = $sueldo_neto_anual / $numero_pagas;


echo "<h1> Resultados del cálculo </h1>";
echo "<p>Salario Bruto Anual: $" . number_format($salario_bruto, 2) . "</p>";
echo "<p>Cuota de la Seguridad Social: $" . number_format($cuota_seguridad_social, 2) . "</p>";
echo "<p>Retención IRPF: " . number_format($retencion_irpf, 2) . "%</p>";
echo "<p>Salario Neto Anual: $" . number_format($sueldo_neto_anual, 2) . "</p>";
echo "<p>Número de Pagas: " . $numero_pagas . "</p>";
echo "<p>Salario Neto Mensual: $" . number_format($sueldo_neto_mensual, 2) . "</p>";
