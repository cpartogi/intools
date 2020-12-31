function getProvince(id) {
  var ajaxurl = '{{route("getProvinces", ":id")}}';
  ajaxurl = ajaxurl.replace(':id', id);
  $.ajax({
    type: "GET",
    url: ajaxurl,
    success: function(data) {
      $.each(data, function(i, item) {
        $('#provinsi').append($('<option>', {
          value: item.id,
          text: item.name
        }));
      });
    }
  });
}

function getCity(id) {
  var ajaxurl = '{{route("getCities", ":id")}}';
  ajaxurl = ajaxurl.replace(':id', id);
  $.ajax({
    type: "GET",
    url: ajaxurl,
    success: function(data) {
      $.each(data, function(i, item) {
        $('#kota').append($('<option>', {
          value: item.id,
          text: item.name
        }));
      });
    }
  });
}
//Initialize Select2 Elements
$('#negara').select2()
$('#negara').on('select2:select', function(e) {
  var data = e.params.data;
  document.getElementById("provinsi").disabled = false;
  getProvince(data.id)
});

$('#provinsi').select2()
$('#provinsi').on('select2:select', function(e) {
  var data = e.params.data;
  getCity(data.id)
  document.getElementById("kota").disabled = false;
});

$('#kota').select2()


function getLogistik() {
  var ajaxurl = '{{route("list_logistic")}}';
  $.ajax({
    type: "GET",
    url: ajaxurl,
    success: function(data) {
      $.each(data, function(i, item) {
        $('#logistik').append($('<option>', {
          value: item.id,
          text: item.name
        }));
      });
    }
  });
}
getLogistik()
$('#logistik').select2()
// Initialize datatables

$("#cari").on("click", function() {
  var city_id = $("#kota").val();
  var logistic_id = $("#logistik").val();
  $('#table-hub').DataTable().clear().destroy()
  $('#table-hub').DataTable({
    processing: true,
    serverSide: true,
    paging: true,
    lengthChange: false,
    searching: false,
    ordering: false,
    info: true,
    autoWidth: true,
    ajax: {
      url: '{{route("list_lrc")}}?&logistic_id=' + logistic_id + '&city_id=' + city_id,
    },
    "columns": [{
        "data": "id"
      },
      {
        "data": "city.name"
      },
      {
        "data": "logistic.name"
      },
      {
        "data": "rate.name"
      },
      {
        "data": "hubless_enabled",
        "render": function(data, type, row, meta) {
          if (data == 0) {
            return `<input type="checkbox" data-id="` + row.id + `" class="tgl-hubless" data-toggle="toggle">`
          }
          return `<input type="checkbox" data-id="` + row.id + `" class="tgl-hubless" checked data-toggle="toggle">`
        }
      },
    ],
    "drawCallback": function(settings) {
      $('.tgl-hubless').bootstrapToggle()
      initHublessSwicthAction()
    }

  });

});

function initHublessSwicthAction() {
  $('.tgl-hubless').change(function() {
    var ajaxurl = '{{route("switch_hubless")}}?id=' + $(this).data('id') + "&prop=" + $(this).prop('checked');
    $.ajax({
      type: "GET",
      url: ajaxurl,
      success: function(data) {
        $.each(data, function(i, item) {
          $('#kota').append($('<option>', {
            value: item.id,
            text: item.name
          }));
        });
      }
    });
  })
}
