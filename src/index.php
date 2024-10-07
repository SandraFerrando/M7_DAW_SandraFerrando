<?php
// Si es vol fer factorials de forma iterativa ha de estar en true, si es vol fer de forma recursiva ha de estar en false
define('FACTORIAL_ITERATIU', true);

// Funció per calcular el factorial de forma iterativa
function factorial_iteratiu($n) {
    $resultat = 1;
    for ($i = 2; $i <= $n; $i++) {
        $resultat *= $i;
    }
    return $resultat;
}

// Funció per calcular el factorial de forma recursiva
function factorial_recursiu($n) {
    if ($n <= 1) {
        return 1;
    }
    return $n * factorial_recursiu($n - 1);
}

// Funció per realitzar operacions numèriques
function operacio_numerica($num1, $num2, $operacio) {
    switch ($operacio) {
        case '+':
            return $num1 + $num2;
        case '-':
            return $num1 - $num2;
        case '*':
            return $num1 * $num2;
        case '/':
            return $num2 != 0 ? $num1 / $num2 : "Error: Divisió per zero";
        case '!':
            return FACTORIAL_ITERATIU ? factorial_iteratiu($num1) : factorial_recursiu($num1);
        default:
            return "Operació no vàlida";
    }
}

// Funció per realitzar operacions amb strings
function operacio_string($string1, $string2, $operacio) {
    switch ($operacio) {
        case 'concatenar':
            return $string1 . $string2;
        case 'eliminar':
            return str_replace($string2, '', $string1);
        default:
            return "Operació no vàlida";
    }
}

// Inicialitzar l'historial
$historial = isset($_POST['historial']) ? json_decode($_POST['historial'], true) : array();

// Processar la sol·licitud de l'usuari
$resultat = null;
$operacio_text = '';

if (isset($_POST['operacio_numerica'])) {
    $num1 = $_POST['num1'];
    $num2 = isset($_POST['num2']) ? $_POST['num2'] : null;
    $operacio = $_POST['operacio_numerica'];

    if ($operacio == '!') {
        // El factorial solo necesita el primer número
        $resultat = operacio_numerica($num1, 0, $operacio);
        $operacio_text = "$num1! = $resultat";

    } else {
        // Validar que el segon número estige present per a operacions diferents de factorial
        if (!isset($num2) || empty($num2)) {
            $resultat = "Error: Falta el segon número per l'operació.";
            $operacio_text = ''; // No mostrar operació incompleta
        } elseif ($operacio == '/') {
            // Validar que en una divisio el segon número no sigue zero
            if ($num2 == 0) {
                $resultat = "Error: No es pot dividir per zero";
                $operacio_text = ''; // No mostrar operació incompleta
            } else {
                $resultat = operacio_numerica($num1, $num2, $operacio);
                $operacio_text = "$num1 $operacio $num2 = $resultat";
            }
        } else {
            $resultat = operacio_numerica($num1, $num2, $operacio);
            $operacio_text = "$num1 $operacio $num2 = $resultat";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Calculadora Web</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Calculadora Web</h1>
    
    <h2>Operacions numèriques</h2>
    <form id="form-numeric" method="post">
        <input type="text" name="num1" required placeholder="Número 1"><br>
        <input type="text" name="num2" placeholder="Número 2" 
        ><br>
        <button type="submit" name="operacio_numerica" value="+">+</button>
        <button type="submit" name="operacio_numerica" value="-">-</button>
        <button type="submit" name="operacio_numerica" value="*">*</button>
        <button type="submit" name="operacio_numerica" value="/">/</button>
        <button type="submit" name="operacio_numerica" value="!">!</button>
        <input type="hidden" name="historial" value="<?php echo htmlspecialchars(json_encode($historial)); ?>">
    </form>

    <h2>Operacions amb strings</h2>
    <form id="form-string" method="post">
        <input type="text" name="string1" required placeholder="String 1">
        <input type="text" name="string2" required placeholder="String 2"><br>
        <button type="submit" name="operacio_string" value="concatenar">Concatenar</button>
        <button type="submit" name="operacio_string" value="eliminar">Eliminar</button>
        <input type="hidden" name="historial" value="<?php echo htmlspecialchars(json_encode($historial)); ?>">
    </form>

    <h2>Resultat</h2>
<p id="resultat">
    <?php
    if (!empty($resultat)) {
        echo htmlspecialchars($resultat);
    }
    ?>
</p>

    <h2>Historial d'operacions</h2>
    <ul id="historial">
    <?php
    foreach ($historial as $operacio) {
        echo "<li>" . htmlspecialchars($operacio) . "</li>";
    }
    ?>
    </ul>
</body>
</html>
