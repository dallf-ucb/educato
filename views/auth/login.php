<?php
/**
 * Vista de login que muestra el formulario de login para iniciar sesión
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/views/auth/login.php
 */
if (isset($msg)) {
    ?> 
<div class="alert alert-warning alert-dismissible">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>Alerta!</strong> <?php echo $msg; ?>.
</div>
<?php
} ?>
<h3 class="text-center">Ingresar al sitio</h3>
<hr />
<div class="row justify-content-center">
    <form class="col-md-4 col-12" id="frmLogin" 
    action="<?php echo baseUrl() ?>auth/validate" method="POST">
        <div class="form-group">
            <label for="nombre">Usuario:</label>
            <input type="text" class="form-control" placeholder="Usuario" 
            id="nombre" name="nombre">
        </div>
        <div class="form-group">
            <label for="clave">Clave:</label>
            <input type="password" class="form-control" placeholder="Clave" 
            id="clave" name="clave">
        </div>
        <div id="register-link" class="text-right">
            <button type="submit" class="btn btn-primary">Ingresar</button>
        </div>
    </form>
</div>
<script>
$(document).ready(function (){
    $("#frmLogin").validate({
      rules: {
        nombre: "required",
        clave: "required", 
        clave: {
          required: true,
          minlength: 3
        }
      },
      messages: {
        nombre: "Ingrese un nombre de usuario",
        clave: {
          required: "Ingrese su clave",
          minlength: "Su clave debe ser al menos de 3 caracteres"
        },
      },
      submitHandler: function(form) {
        form.submit();
      }
    });
});
</script>