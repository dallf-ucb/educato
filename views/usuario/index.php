<?php
/**
 * Vista de administración de usuarios
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/views/layouts/default.php
 */
?>
<h2>Administraci&oacute;n de Usuarios</h2>

<form id="frmData">
    <div id="alertPanel">
    </div>
    <div id="accordion">
        <div class="card">
            <div class="card-header">
                <a class="card-link" data-toggle="collapse" href="#collapseFilter">
                    <h5 class="panel-title"><i class="fas fa-filter"></i>&nbsp;Filtrar Usuarios</h5>
                </a>
            </div>
            <div id="collapseFilter" class="collapse show" data-parent="#accordion">
                <div class="card-body">
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="StartDate">Por fecha,
                                desde:</label>
                            <div class="form-group">
                                <div class="input-group date" id="StartDate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        data-target="#StartDate" />
                                    <div class="input-group-append" data-target="#StartDate"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <label class="control-label" for="EndDate">Hasta:</label>
                            <div class="form-group">
                                <div class="input-group date" id="EndDate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                        data-target="#EndDate" />
                                    <div class="input-group-append" data-target="#EndDate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col">
                            <label for="txtNombre">Usuario: </label>
                            <input class="form-control" id="txtNombre" name="txtNombre" type="text" placeholder="Usuario"
                                maxlength="500" />
                        </div>
                        <div class="form-group col">
                            <label for="selRol">Rol: </label>
                            <select id="selRol" name="selRol" class="form-control">
                                <option value=""> - </option>
                                <option value="invitado">Invitado</option>
                                <option value="administrador">Administrador</option>
                                <option value="administrativo">Administrativo</option>
                                <option value="docente">Docente</option>
                                <option value="estudiante">Estudiante</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="rol">Estado: </label><br />
                            <input id="chkVisible" name="chkVisible" type="checkbox" checked="checked" data-toggle="toggle" data-on="<i class='fa fa-eye'></i> Visible" data-off="<i class='fa fa-eye-slash'></i> Invisible" data-onstyle="success" data-offstyle="danger" data-width="130" data-height="38">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button id="btnSearch" name="btnSearch" class="btn btn-primary" data-submit="true" type="button"><i
                            class="fas fa-search"></i>&nbsp;Search</button>&nbsp;&nbsp;
                    <button id="btnReset" name="btnReset" class="btn btn-warning" type="button"><i
                            class="fas fa-times"></i>&nbsp;Reset</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <a class="collapsed card-link" data-toggle="collapse" href="#collapseForm">
                    <h5 class="panel-title">
                        <i id="iconTitleForm" class="fas fa-plus"></i>&nbsp;<span id="lblTitleForm">Agregar</span>
                        Usuario
                    </h5>
                </a>
            </div>
            <div id="collapseForm" class="collapse" data-parent="#accordion">
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="nombre">Usuario: </label>
                            <input class="form-control" id="nombre" name="nombre" type="text" placeholder="Usuario"
                                maxlength="500" />
                        </div>
                        <div class="form-group col">
                            <label for="clave">Clave: </label>
                            <input class="form-control" id="clave" name="clave" type="password" placeholder="Clave"
                                maxlength="500" aria-describedby="claveHelp" />
                            <small id="claveHelp" class="form-text text-muted">Solo llenar en caso de querer cambiar la clave.</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="rol">Rol: </label>
                            <select id="rol" name="rol" class="form-control">
                                <option value="invitado">Invitado</option>
                                <option value="administrador">Administrador</option>
                                <option value="administrativo">Administrativo</option>
                                <option value="docente">Docente</option>
                                <option value="estudiante">Estudiante</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="rol">Estado: </label><br />
                            <input type="checkbox" id="visible" name="visible" checked="checked" data-toggle="toggle" data-on="<i class='fa fa-eye'></i> Visible" data-off="<i class='fa fa-eye-slash'></i> Invisible" data-onstyle="success" data-offstyle="danger" data-width="130" data-height="38">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button id="btnSave" name="btnSave" class="btn btn-success" data-submit="true" type="button"><i
                            class="fas fa-check"></i>&nbsp;Guardar</button>&nbsp;&nbsp;
                    <button id="btnCancel" name="btnCancel" class="btn btn-warning" type="button"><i
                            class="fas fa-times"></i>&nbsp;Cancel</button>
                </div>
            </div>
        </div>

    </div>
</form>

<br />

<div class="row" id="progressBar">
    <div class="col-md-12">
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                aria-valuemax="100" style="width: 0%"></div>
        </div>
    </div>
</div>

<table id="dataGrid" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="colsort" onclick="sortDataBy(this, 'nombre');">Nombre <span class="fas fa-sort-down"></span>
            </th>
            <th class="colsort" onclick="sortDataBy(this, 'rol');">Rol <span
                    class="fas fa-sort-down"></span></th>
            <th class="colsort" onclick="sortDataBy(this, 'created_at');">Fecha de creaci&oacute;n <span id="defaultSort" 
                    class="fas fa-sort-down"></span></th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<nav aria-label="Grid pagination" id="gridNav">
    <ul class="pagination justify-content-center">
        <li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="goToStart();"><span
                    class="fas fa-fast-backward"></span></a>
        </li>
        <li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="goToPrevious();"><span
                    class="fas fa-step-backward"></span></a>
        </li>
        <li class="page-item active"><a class="page-link" href="javascript:void(0)">Esta en la p&aacute;gina <span
                    id="CurrentPage"></span></a></li>
        <li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="goToNext();"><span
                    class="fas fa-step-forward"></span></a>
        </li>
        <li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="goToEnd();"><span
                    class="fas fa-fast-forward"></span></a>
        </li>
    </ul>
</nav>

<script id="tmpList" type="x-tmpl-mustache">
    {{#.}}
<tr><td>{{nombre}}</td><td>{{rol}}</td><td><nobr>{{created_at}}</nobr></td>
    <td>
        <div class="btn-group">
            <button type="button" data-toggle="tooltip" title="Editar" class="btn btn-primary btn-xs" onclick="edit({{id}},'{{nombre}}','{{rol}}');"><span class="fas fa-pencil-alt"></span></button>
            <button type="button" data-toggle="tooltip" title="Ocultar" class="btn btn-warning btn-xs btn-hide" onclick="hide({{id}});"><span class="fas fa-eye-slash"></span></button>
            <button type="button" data-toggle="tooltip" title="Borrar" class="btn btn-danger btn-xs" onclick="del({{id}});"><span class="fas fa-ban"></span></button>
        </div>
    </td>
</tr>
{{/.}}
</script>

<script id="tmpMsg" type="x-tmpl-mustache">
    <div class="alert alert-{{Type}} alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <strong>{{Title}}</strong>&nbsp;&nbsp;<span>{{Msg}}</span>
    </div>
</script>
<script type="text/javascript">
var urlBase = "<?php echo baseUrl(); ?>";
var urlAPI = urlBase + "api/usuario";
</script>
<script type="text/javascript" src="<?php echo baseUrl(); ?>/assets/js/usuario/logic.js"></script>