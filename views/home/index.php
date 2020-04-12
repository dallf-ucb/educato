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
<h1>Algunos datos de test: </h1>
<table class="table table-striped table-responsive">
    <tr>
        <td>Ultimo Id insertado
        </td>
        <td>
            <?php echo $id; ?>
        </td>
    </tr>
    <tr>
        <td>JSON datos filtrados
        </td>
        <td>
            <pre>
            <?php echo $where; ?>
        </pre>
        </td>
    </tr>
    <tr>
        <td>JSON datos paginados
        </td>
        <td>
            <pre>
            <?php echo $paged; ?>
        </pre>
        </td>
    </tr>
    <tr>
        <td>JSON una sola fila filtrada
        </td>
        <td>
            <pre>
            <?php echo $one; ?>
        </pre>
        </td>
    </tr>
</table>