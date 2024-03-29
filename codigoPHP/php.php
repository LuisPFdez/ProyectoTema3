<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Formulario</title>
        <meta charset="UTF-8">
        <style>
            .formulario{
                display: block;
            }

            .bloque{
                margin-bottom: 15px;
            }

            .bloque:last-of-type{
                margin-bottom: 0px;
            }

            .error{
                color: red;
            }
        </style>
    </head>
    <body>
        <?php
        /*
         * @author Luis Puente Fernandez
         * @since 21-10-2020
         */

        require_once '../core/201020libreriaValidacion.php'; //Se carga la libreria de validación

        function calcularEdad($fecha) {

            $fechaActual = new DateTime();
            $fechaFormateada = new DateTime(str_replace("/", "-", $fecha));

            return $fechaActual->diff($fechaFormateada)->y;
        }

        function calcularPorcentaje($parte, $total) {
            return ($parte / $total ) * 100;
        }

        function calcularMediaArrays($valores) {
            if (is_array($valores)) {
                
            } else {
                return $valores;
            }
        }

        $estadisticas = array(
            "edad" => 0,
            "nEdad" => 0,
            "altura" => 0,
            "nAltura" => 0,
            "peso" => 0,
            "nPeso" => 0,
            "salario" => 0,
            "empleo" => array(
                "camarero" => 0,
                "mecanico" => 0,
                "fisico" => 0,
                "quimico" => 0,
                "piloto" => 0,
                "medico" => 0,
                "bombero" => 0,
                "otro" => 0,
                "no_trabajo" => 0
            ),
            "mascotas" => array(
                "gato" => 0,
                "perro" => 0,
                "loro" => 0,
                "iguana" => 0,
                "hamster" => 0,
                "coballa" => 0,
                "otro" => 0,
            )
        );
        define("NPERSONAS", 1);
        define("OBLIGATORIO", 1);
        $trabajos = array("camarero", "mecanico", "fisico", "quimico", "piloto", "medico", "bombero", "otro", "no_trabajo");

        $entradaOK = true; //Variable que comprobará si esta bien metida la entrada o n
        for ($persona = 0; $persona < NPERSONAS; $persona++) {

            $formulario[$persona] = array(
                "nombre" => null,
                "genero" => null,
                "fechaNacimiento" => null,
                "telefono" => null,
                "dni" => null,
                "altura" => null,
                "peso" => null,
                "salario" => null,
                "empleo" => null,
                "mascotas" => null
            );

            $errores[$persona] = array(
                "nombre" => null,
                "genero" => null,
                "fechaNacimiento" => null,
                "telefono" => null,
                "dni" => null,
                "altura" => null,
                "peso" => null,
                "salario" => null,
                "empleo" => null
            );
        }
        /*
         * @
         */
        if (isset($_REQUEST['enviar'])) {

            for ($persona = 0; $persona < NPERSONAS; $persona++) {
                echo $errores[$persona]['fechaNacimiento'];
                $errores[$persona]['nombre'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['nombre'][$persona], 25, 2, OBLIGATORIO);
                $errores[$persona]['genero'] = !isset($_REQUEST['genero'][$persona]) ? "Por favor introduce marca al menos un campo" : null;
                $errores[$persona]['fechaNacimiento'] = validacionFormularios::validarFecha($_REQUEST['fechaNacimiento'][$persona], (new DateTime())->format("m/d/Y"));
                $errores[$persona]['telefono'] = validacionFormularios::validaTelefono($_REQUEST['telefono'][$persona]);
                $errores[$persona]['dni'] = validacionFormularios::validarDni($_REQUEST['dni'][$persona], OBLIGATORIO);
                $errores[$persona]['altura'] = validacionFormularios::comprobarEntero($_REQUEST['altura'][$persona], 300, 30);
                $errores[$persona]['peso'] = validacionFormularios::comprobarEntero($_REQUEST['peso'][$persona], 1500);
                $errores[$persona]['salario'] = validacionFormularios::comprobarEntero($_REQUEST['salario'][$persona], PHP_INT_MAX, 0, OBLIGATORIO);
                $errores[$persona]['empleo'] = validacionFormularios::validarElementoEnLista(strtolower($_REQUEST['empleo'][$persona]), $trabajos);

                foreach ($errores[$persona] as $clave => $valor) {
                    if ($valor != null) {
                        $_REQUEST[$clave][$persona] = "";
                        $entradaOK = false;
                    }
                }
            }
        } else {
            $entradaOK = false;
        }

        if ($entradaOK) {

            for ($persona = 0; $persona < NPERSONAS; $persona++) {
                $formulario[$persona]['nombre'] = $_REQUEST['nombre'][$persona];
                $formulario[$persona]['genero'] = $_REQUEST['genero'][$persona];
                $formulario[$persona]['fechaNacimiento'] = $_REQUEST['fechaNacimiento'][$persona];
                $formulario[$persona]['telefono'] = $_REQUEST['telefono'][$persona];
                $formulario[$persona]['dni'] = $_REQUEST['dni'][$persona];
                $formulario[$persona]['altura'] = $_REQUEST['altura'][$persona];
                $formulario[$persona]['peso'] = $_REQUEST['paso'][$persona];
                $formulario[$persona]['salario'] = $_REQUEST['salario'][$persona];
                $formulario[$persona]['empleo'] = $_REQUEST['empleo'][$persona];
                $formulario[$persona]['mascotas'] = $_REQUEST['mascotas'][$persona];

                echo "<p>Datos de " . $formulario[$persona]['nombre'] . "</p>";
                echo "<p>" . ($formulario[$persona]['genero'] != "otro" ? "Genero " . $formulario[$persona]['genero'] . " " : "Genero no binario ");
                echo!empty($formulario[$persona]['fechaNacimiento']) ? calcularEdad($formulario[$persona]['fechaNacimiento']) . "años</p>" : "</p>";
                echo "<p>" . (!empty($formulario[$persona]['telefono']) ? "Telefono: " . $formulario[$persona]['telefono'] . ", " : "");
                echo "Dni: " . $formulario[$persona]['dni'] . "</p>";

                if (!empty($formulario[$persona]['altura']) || $formulario[$persona]['peso']) {
                    echo "<p>Medidas corporates -> ";

                    if (!empty($formulario[$persona]['altura'])) {
                        echo "Altura: " . $formulario[$persona]['altura'] . " ";
                    }

                    if (!empty($formulario[$persona]['peso'])) {
                        echo "Peso: " . $formulario[$persona]['peso'];
                    }
                    echo "</p>";
                }

                echo '<p>Salario: ' . $formulario[$persona]['salario'] . ", Empleo: " . $formulario[$persona]['empleo'] . "</p>";



                if (isset($formulario[$persona]['mascotas'])) {
                    echo "<p>Mascotas: ";
                    foreach ($formulario[$persona]['mascotas'] as $mascota) {
                        echo $mascota . " ";
                        $estadisticas['mascota'][$mascota]++;
                    }
                    echo "</p>";
                }

                $estadisticas['salario'] += $formulario[$persona]['salario'];
                $estadisticas['empleo'][$formulario[$persona]['empleo']]++;

                if (isset($formulario[$persona]['fechaNacimiento'])) {
                    $estadisticas['edad'] += calcularEdad($formulario[$persona]['fechaNacimiento']);
                    $estadisticas['nEdad']++;
                }

                if (isset($formulario[$persona]['altura'])) {
                    $estadisticas['altura'] += $formulario[$persona]['altura'];
                    $estadisticas['nAltura']++;
                }

                if (isset($formulario[$persona]['peso'])) {
                    $estadisticas['peso'] += $formulario[$persona]['peso'];
                    $estadisticas['nPeso']++;
                }
            }
            echo "<p>Medias: <br>";
            echo ($estadisticas['altura'] > 0 ? "Altura: " . $estadisticas['altura'] / $estadisticas['nAltura'] : "Nadie ha introducido la altura") . "<br>";
            echo ($estadisticas['peso'] > 0 ? "Peso: " . $estadisticas['peso'] / $estadisticas['nPeso'] : "Nadie ha introducido el peso") . "</p>";
            echo "Trabajos: ";
        } else {
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <?php
                for ($persona = 0; $persona < NPERSONAS; $persona++) {
                    ?>

                    <!--Lista de empleos-->
                    <datalist id="empleos">
                        <option value="camarero">Camarero</option> 
                        <option value="mecanico">Mecanico</option>
                        <option value="cartero">Cartero</option>
                        <option value="fisico">Fisico</option>
                        <option value="quimico">Quimico</option>
                        <option value="piloto">Piloto</option>
                        <option value="policio">Policia</option>
                        <option value="medico">Medico</option>
                        <option value="bombero">Bombero</option>
                        <option value="otro">Otro</option>
                        <option value="no_trabajo">No trabajo</option>
                    </datalist>

                    <div class="formulario">

                        <div class="bloque">
                            <label for="nombre">Introduce tu nombre: </label>
                            <input type="text" id="nombre" name="nombre[]" value="<?php echo $_REQUEST['nombre'][$persona] ?>">
                            <?php
                            echo!empty($errores[$persona]['nombre']) ? "<p class=\"error\">" . $errores[$persona]['nombre'] . "</p>" : "";
                            ?>
                        </div>

                        <div class="bloque">
                            <p>Introduce tu genero: </p>
                            <label for="genero">Hombre</label>
                            <input type="radio" id="hombre" name="genero[]" value="hombre" <?php
                    if (isset($_REQUEST['genero']) && $_REQUEST['genero'][$persona] == "hombre") {
                        echo 'checked';
                    }
                            ?> >
                            <label for="genero">Mujer</label>
                            <input type="radio" id="mujer" name="genero[]" value="mujer" <?php
                    if (isset($_REQUEST['genero']) && $_REQUEST['genero'][$persona] == "mujer") {
                        echo 'checked';
                    }
                            ?> >
                            <label for="genero">Otro</label>
                            <input type="radio" id="otro" name="genero[]" value="otro" <?php
                    if (isset($_REQUEST['genero']) && $_REQUEST['genero'][$persona] == "otro") {
                        echo 'checked';
                    }
                            ?> >
                                   <?php
                                   echo!empty($errores[$persona]['genero']) ? "<p class=\"error\">" . $errores[$persona]['genero'] . "</p>" : "";
                                   ?>
                        </div>

                        <div class="bloque">
                            <label for="fechaNacimiento">Introduce tu fecha de nacimiento: </label>
                            <input type="date" id="fechaNacimiento[]" name="fechaNacimiento[]" value="<?php echo $_REQUEST['fechaNacimiento'][$persona] ?>">
                            <?php
                            echo!empty($errores[$persona]['fechaNacimiento']) ? "<p class=\"error\">" . $errores[$persona]['fechaNacimiento'] . "</p>" : "";
                            ?>
                        </div>

                        <div class="bloque">
                            <label for="telefono">Introduce tu telefono: </label>
                            <input type="text" id="telefono" name="telefono[]" value="<?php echo $_REQUEST['telefono'][$persona] ?>">
                            <?php
                            echo!empty($errores[$persona]['telefono']) ? "<p class=\"error\">" . $errores[$persona]['telefono'] . "</p>" : "";
                            ?>
                        </div>

                        <div class="bloque">
                            <label for="dni">Introduce tu dni: </label>
                            <input type="text" id="dni" name="dni[]" value="<?php echo $_REQUEST['dni'][$persona] ?>">
                            <?php
                            echo!empty($errores[$persona]['dni']) ? "<p class=\"error\">" . $errores[$persona]['dni'] . "</p>" : "";
                            ?>
                        </div>

                        <div class="bloque">
                            <label for="altura">Introduce tu altura: </label>
                            <input type="number" id="altura" name="altura[]" value="<?php echo $_REQUEST['altura'][$persona] ?>">
                            <?php
                            echo!empty($errores[$persona]['altura']) ? "<p class=\"error\">" . $errores[$persona]['altura'] . "</p>" : "";
                            ?>
                        </div>

                        <div class="bloque">
                            <label for="peso">Introduce tu peso: </label>
                            <input type="number" id="peso" name="peso[]" value="<?php echo $_REQUEST['peso'][$persona] ?>">
                            <?php
                            echo!empty($errores[$persona]['peso']) ? "<p class=\"error\">" . $errores[$persona]['peso'] . "</p>" : "";
                            ?>
                        </div>

                        <div class="bloque">
                            <label for="salario">Introduce tu salario: </label>
                            <input type="text" id="salario[]" name="salario[]" value="<?php echo $_REQUEST['salario'][$persona] ?>">
                            <?php
                            echo!empty($errores[$persona]['salario']) ? "<p class=\"error\">" . $errores[$persona]['salario'] . "</p>" : "";
                            ?>
                        </div>

                        <div class="bloque">
                            <label for="empleo">Introduce tu empleo: </label>
                            <input list="empleos" id="empleo" name="empleo[]" value="<?php echo $_REQUEST['empleo'][$persona] ?>">
                            <?php
                            echo!empty($errores[$persona]['empleo']) ? "<p class=\"error\">" . $errores[$persona]['empleo'] . "</p>" : "";
                            ?>
                        </div>

                        <div class="bloque">
                            <label for="gato">Gato</label>
                            <input type="checkbox" name="mascotas[<?php echo $persona ?>]['gato']" id="gato" value="gato" <?php
                    if (isset($_REQUEST['mascotas'][$persona]['gato'])) {
                        echo 'checked';
                    }
                            ?> >
                            <label for="perro">Perro</label>
                            <input type="checkbox" name="mascotas[<?php echo $persona ?>]['perro']" id="perro" value="perro" <?php
                    if (isset($_REQUEST['mascotas'][$persona]['perro'])) {
                        echo 'checked';
                    }
                            ?> >
                            <label for="loro">Loro</label>
                            <input type="checkbox" name="mascotas[<?php echo $persona ?>]['loro']" id="loro" value="loro" <?php
                    if (isset($_REQUEST['mascotas'][$persona]['loro'])) {
                        echo 'checked';
                    }
                            ?> >
                            <label for="iguana">Iguana</label>
                            <input type="checkbox" name="mascotas[<?php echo $persona ?>]['iguana']" id="iguana" value="iguana" <?php
                    if (isset($_REQUEST['mascotas'][$persona]['iguana'])) {
                        echo 'checked';
                    }
                            ?> >
                            <label for="hamster">Hamster</label>
                            <input type="checkbox" name="mascotas[<?php echo $persona ?>]['hamster']" id="hasmter" value="hasmter" <?php
                    if (isset($_REQUEST['mascotas'][$persona]['hasmter'])) {
                        echo 'checked';
                    }
                            ?> >
                            <label for="coballa">Coballa</label>
                            <input type="checkbox" name="mascotas[<?php echo $persona ?>]['coballa']" id="coballa" value="coballa" <?php
                    if (isset($_REQUEST['mascotas'][$persona]['coballa'])) {
                        echo 'checked';
                    }
                            ?> >
                            <label for="otro">Otro</label>
                            <input type="checkbox" name="mascotas[<?php echo $persona ?>]['otro']" id="otro" value="otro" <?php
                    if (isset($_REQUEST['mascotas'][$persona]['otro'])) {
                        echo 'checked';
                    }
                            ?> >
                        </div>
                    </div>


                    <?php
                }
                ?>
                <input type="submit" name="enviar" value="enviar">
            </form>
            <?php
        }
        ?>
    </body>
</html>