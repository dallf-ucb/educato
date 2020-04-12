var pageNumber = 1;
var pageSize = 10;
var totalPages = 0;
var total = 0;
var dataSet;
var progress;
var currentSort = "created_at";
var direction = "DESC";
var selectedId = -1;

function goToStart() {
  pageNumber = 1;
  loadData();
}

function goToPrevious() {
  pageNumber--;
  if (pageNumber <= 0) pageNumber = totalPages;
  loadData();
}

function goToNext() {
  pageNumber++;
  if (pageNumber > totalPages) pageNumber = 1;
  loadData();
}

function goToEnd() {
  pageNumber = totalPages;
  loadData();
}

function showGrid() {
  $("#CurrentPage").html(pageNumber + " de " + totalPages);

  var template = $("#tmpList").html();
  Mustache.parse(template);
  var rendered = Mustache.render(template, dataSet);

  $("#progressBar").hide();
  clearInterval(progress);

  $("#dataGrid").show();
  $("#gridNav").show();

  $("#dataGrid > tbody").html(rendered);
  $('[data-toggle="tooltip"]').tooltip();
}

function alertUser(type, msg) {
  var message = [];
  if (type == "e") {
    message.Title = "Error!";
    message.Type = "danger";
    message.Msg = msg;
  }

  if (type == "s") {
    message.Title = "Success!";
    message.Type = "success";
    message.Msg = msg;
  }

  if (type == "w") {
    message.Title = "Warning!";
    message.Type = "warning";
    message.Msg = msg;
  }

  var template = $("#tmpMsg").html();
  Mustache.parse(template);
  var rendered = Mustache.render(template, message);
  $("#alertPanel").html(rendered);
}

function sortDataBy(obj, sortField) { 
  $(obj)
    .siblings()
    .find("span")
    .removeClass()
    .addClass("fas fa-sort-down");
    
  if (
    $(obj)
      .find("span:first-child")
      .hasClass("fa-sort-down")
  ) {
    $(obj)
      .find("span:first-child")
      .removeClass("fa-sort-down")
      .addClass("fa-sort-amount-down-alt");
    currentSort = sortField;
    direction = "ASC";
  } else {
    if (
      $(obj)
        .find("span:first-child")
        .hasClass("fa-sort-amount-down-alt")
    ) {
      $(obj)
        .find("span:first-child")
        .removeClass("fa-sort-amount-down-alt")
        .addClass("fa-sort-amount-down");
      currentSort = sortField;
      direction = "DESC";
    } else {
      if (
        $(obj)
          .find("span:first-child")
          .hasClass("fa-sort-amount-down")
      ) {
        $(obj)
          .find("span:first-child")
          .removeClass("fa-sort-amount-down")
          .addClass("fa-sort-down");
        currentSort = "created_at";
        direction = "DESC";
        $("#defaultSort")
          .removeClass("fa-sort-down")
          .addClass("fa-sort-amount-down");
      }
    }
  }

  loadData();
}

function resetSearchForm() {
  $("#alertPanel").empty();
  $("#progressBar").hide();
  var now = moment(new Date());
  $("#StartDate").datetimepicker(
    "date",
    now
      .subtract(1, "year")
      .hours(0)
      .minutes(0)
      .seconds(0)
      .milliseconds(0)
  );
  $("#EndDate").datetimepicker(
    "date",
    now
      .add(1, "year")
      .hours(0)
      .minutes(0)
      .seconds(0)
      .milliseconds(0)
  );
  $("#selRol")
    .val("")
    .trigger("change");
  pageNumber = 1;
  $("#txtNombre").val("");
  currentSort = "created_at";
  direction = "DESC";
  $("#dataGrid")
    .find("span")
    .removeClass();
  $("#dataGrid")
    .find("span")
    .addClass("fas fa-sort-down");
  $("#defaultSort")
    .removeClass("fa-sort-down")
    .addClass("fa-sort-amount-down");
}

$("#btnReset").click(function() {
  resetSearchForm();
  $("#btnSearch").prop("disabled", true);
  $("#btnReset").prop("disabled", true);
  loadData();
});

$("#btnSearch").click(function() {
  $("#alertPanel").empty();
  $("#btnSearch").prop("disabled", true);
  $("#btnReset").prop("disabled", true);
  pageNumber = 1;
  loadData();
});

function loading() {
  $("#progressBar").show();
  var progressBar = $(".progress-bar");
  var percentVal = 0;

  progress = window.setInterval(function() {
    progressBar
      .css("width", percentVal + "%")
      .attr("aria-valuenow", percentVal + "%");

    if (percentVal == 100) {
      percentVal = 0;
    }
    percentVal += 10;
  }, 500);
}

function loadData() {
  $("#dataGrid > tbody").html("");
  $("#dataGrid").hide();
  $("#gridNav").hide();
  loading();

  var startDate = moment($("#StartDate").datetimepicker("date")).startOf('day').format(
    "YYYY-MM-DD HH:mm:ss"
  );
  var endDate = moment($("#EndDate").datetimepicker("date")).endOf('day').format(
    "YYYY-MM-DD HH:mm:ss"
  );
  var nombre = $("#txtNombre").val();
  var rol = $("#selRol").val();
  var visible = $("#chkVisible").val();
  var filter =
    "?startDate=" +
    startDate +
    "&endDate=" +
    endDate +
    "&nombre=" +
    nombre + 
    "&rol=" +
    rol +
    "&visible=" +
    visible +
    "&pageNumber=" +
    pageNumber +
    "&pageSize=" +
    pageSize +
    "&sortBy=" +
    currentSort +
    "&sortDirection=" +
    direction;

  $.ajax({
    url: urlAPI + filter,
    type: "GET",
    dataType: "json", 
    success: function(result) { 
      total = result.Total;
      if (!result.Error && total > 0) {
        totalPages = Math.floor(total / pageSize);
        if (total % pageSize > 0) totalPages++;
        dataSet = result.Data;
        showGrid();
      } else if(response.Error == "login") {
        // similar behavior as an HTTP redirect
        window.location.replace(urlBase + "auth/login");
      } else {
        $("#progressBar").hide();
        clearInterval(progress);

        alertUser(
          "w",
          "No se encontraron datos para mostrar."
        );
      }

      $("#btnSearch").prop("disabled", false);
      $("#btnReset").prop("disabled", false);
      $("#btnSave").prop("disabled", false);
      $("#btnCancel").prop("disabled", false);
    }
  });
}

$(document).ready(function() {
  $("#progressBar").hide();
  $("#dataGrid").hide();
  $("#gridNav").hide();
  $("#selRol").select2();
  $("#rol").select2();

  var now = moment(new Date());

  $("#StartDate").datetimepicker({
    format: "MM/DD/YYYY",
    allowInputToggle: true
  });

  $("#EndDate").datetimepicker({
    format: "MM/DD/YYYY",
    allowInputToggle: true,
    useCurrent: false, 
  });

  $("#StartDate").on("change.datetimepicker", function (e) {
    $('#EndDate').datetimepicker('minDate', e.date);
  });
  $("#EndDate").on("change.datetimepicker", function (e) {
    $('#StartDate').datetimepicker('maxDate', e.date);
  });

  loading();

  resetSearchForm();
  $("#progressBar").hide();
  clearInterval(progress);
  loadData();
  $("#claveHelp").hide();
});

function resetForm() {
  $("#collapseFilter").collapse("show");

  $(window).scrollTop($("#accordion").offset().top);
  $("#progressBar").hide();
  $("#rol")
    .val("")
    .trigger("change");
  $("#nombre").val("");
  $("#clave").val("");
  $("#claveHelp").hide();
  if (selectedId != -1) {
    $("#lblTitleForm").text("Agregar");
    $("#iconTitleForm")
      .removeClass("fa-pencil-alt")
      .addClass("fa-plus");
  }
  selectedId = -1;
}

$("#btnCancel").click(function() {
  resetForm();
});

$("#btnSave").click(function() {
  $("#alertPanel").empty();
  $("#btnSave").prop("disabled", true);
  $("#btnCancel").prop("disabled", true);

  var nombre = $("#nombre").val();
  var rol = $("#rol").val();
  var clave = $("#clave").val();
  var usuario = {
    id: selectedId,
    nombre: nombre,
    rol: rol,
    clave: clave,   
  };

  var methodType = "POST";
  if (selectedId != -1) {
    methodType = "PUT";
  }

  $.ajax({
    url: urlAPI,
    type: methodType, 
    data: usuario,
    dataType: "json", 
    success: function(response) {
      if (!response.Error) {
        alertUser("s", "El usuario se guardo.");
        resetForm();
        pageNumber = 1;
        loadData();
      } else if(response.Error == "login") {
        // similar behavior as an HTTP redirect
        window.location.replace(urlBase + "auth/login");
      } else {
        alertUser("e", "Hubo un error al guardar el usuario.");
        $("#btnSave").prop("disabled", false);
        $("#btnCancel").prop("disabled", false);
      }
    }
  });
});

function edit(id, nombre, rol) {
  $("#collapseForm").collapse("show");

  $("#rol")
    .val(rol)
    .trigger("change"); 
  selectedId = id; 
  $("#nombre").val(nombre);
  $("#lblTitleForm").text("Editar");
  $("#iconTitleForm")
    .removeClass("fa-plus")
    .addClass("fa-pencil-alt");
  $("#claveHelp").show();
  $("#btnSave").prop("disabled", false);
  $("#btnCancel").prop("disabled", false);
  $("#nombre").focus();
}

function del(id) {
  if (
    confirm(
      "Esta seguro de borrar el usuario? Puede ocultarlo en cambio."
    )
  ) {
    $.ajax({
      url: urlAPI + "?id=" + id,
      type: "DELETE",
      dataType: "json", 
      success: function(msg) {
        if (!msg.Error) {
          alertUser("s", "El usuario se borro exitosamente.");
          pageNumber = 1;
          loadData();
        } else if(response.Error == "login") {
          // similar behavior as an HTTP redirect
          window.location.replace(urlBase + "auth/login");
        } else {
          alertUser("e", "No se pudo borrar el usuario.");
        }
      }
    });
  }
}

function hide(id) {
  $.ajax({
    url: urlAPI + "?id=" + id + "&hide=true",
    success: function(msg) {
      if (!msg.Error) {
        alertUser("s", "El usuario se oculto exitosamente.");
        pageNumber = 1;
        loadData();
      } else if(response.Error == "login") {
        // similar behavior as an HTTP redirect
        window.location.replace(urlBase + "auth/login");
      } else {
        alertUser("e", "No se pudo ocultar el usuario.");
      }
    }
  });
}
