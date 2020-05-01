<?php
/**
 * Vista de inicio y bienvenida al sitio
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/views/auth/login.php
 */
?>
<?php if (logged()) { ?>
<h1>Bienvenido <?php echo getSes("username") ?>, ingrese a las diferentes opciones usando el men&uacute;</h1>
<?php } else { ?>
<h3>Bienvenido ingrese usando la opci&oacute;n de ingresar del men&uacute; o usando el siguiente bot&oacute;n </h3>
<a href="<?php echo baseUrl(); ?>auth/login" class="btn btn-primary btn-lg">Ingresar</a>
<?php } ?>