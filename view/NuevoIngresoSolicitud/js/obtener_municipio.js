/* 

    $(document).ready(function() {
        $('#Departamento').select2();
        $('#Municipio').select2();

        $('#Departamento').change(function() {
            var selectedDepartments = $(this).val();
            if (selectedDepartments.length > 0) {
                $.ajax({
                    type: 'POST',
                    url: '../NuevoIngresoSolicitud/obtener_municipio.php',
                    data: { departamentos: selectedDepartments },
                    success: function(response) {
                        var municipios = JSON.parse(response);
                        $('#Municipio').empty();
                        municipios.forEach(function(municipio) {
                            $('#Municipio').append(new Option(municipio.NOM_MUNICIPIO, municipio.ID_MUNICIPIO));
                        });
                    }
                });
            } else {
                $('#Municipio').empty();
            }
        });
    });

 */ /* 
    $(document).ready(function () {
        $('#Departamento').select2();
        $('#Municipio').select2();

        var selectedMunicipios = [];
        var lastSelectedDepartment = null;

        $('#Departamento').change(function () {
            var selectedDepartments = $(this).val();
            if (selectedDepartments.length > 0) {
                // Mantener el último departamento seleccionado
                var currentSelectedDepartments = $(this).val();
                if (currentSelectedDepartments.length > selectedDepartments.length) {
                    lastSelectedDepartment = currentSelectedDepartments.filter(x => !selectedDepartments.includes(x))[0];
                } else {
                    lastSelectedDepartment = currentSelectedDepartments[currentSelectedDepartments.length - 1];
                }

                $.ajax({
                    type: 'POST',
                    url: '../NuevoIngresoSolicitud/obtener_municipio.php',
                    data: { departamentos: [lastSelectedDepartment] },
                    success: function (response) {
                        var municipios = JSON.parse(response);

                        // Vaciar el select de municipios pero mantener los seleccionados
                        $('#Municipio').find('option').not(':selected').remove();

                        // Añadir solo los municipios del último departamento seleccionado
                        municipios.forEach(function (municipio) {
                            if ($('#Municipio option[value="' + municipio.ID_MUNICIPIO + '"]').length === 0) {
                                $('#Municipio').append(new Option(municipio.NOM_MUNICIPIO, municipio.ID_MUNICIPIO));
                            }
                        });

                        $('#Municipio').trigger('change');
                    }
                });
            } else {
                $('#Municipio').empty();
            }
        });

        $('#Municipio').change(function () {
            selectedMunicipios = $('#Municipio').select2('data').map(function (municipio) {
                return { id: municipio.id, text: municipio.text };
            });
        });
    }); */

$(document).ready(function () {
  $("#Departamento1, #Departamento2").select();
  $("#Municipio1, #Municipio2").select();

  function handleDepartmentChange(departmentSelect, municipalitySelect) {
    var selectedMunicipios = [];
    var lastSelectedDepartment = null;

    $(departmentSelect).change(function () {
      var selectedDepartments = $(this).val();
      if (selectedDepartments.length > 0) {
        var currentSelectedDepartments = $(this).val();
        if (currentSelectedDepartments.length > selectedDepartments.length) {
          lastSelectedDepartment = currentSelectedDepartments.filter(
            (x) => !selectedDepartments.includes(x)
          )[0];
        } else {
          lastSelectedDepartment =
            currentSelectedDepartments[currentSelectedDepartments.length - 1];
        }

        $.ajax({
          type: "POST",
          url: "../NuevoIngresoSolicitud/obtener_municipio.php",
          data: { departamentos: [lastSelectedDepartment] },
          success: function (response) {
            var municipios = JSON.parse(response);

            $(municipalitySelect).find("option").not(":selected").remove();

            municipios.forEach(function (municipio) {
              if (
                $(
                  municipalitySelect +
                    ' option[value="' +
                    municipio.ID_MUNICIPIO +
                    '"]'
                ).length === 0
              ) {
                $(municipalitySelect).append(
                  new Option(municipio.NOM_MUNICIPIO, municipio.ID_MUNICIPIO)
                );
              }
            });

            $(municipalitySelect).trigger("change");
          },
        });
      } else {
        $(municipalitySelect).empty();
      }
    });

    $(municipalitySelect).change(function () {
      selectedMunicipios = $(municipalitySelect)
        .select2("data")
        .map(function (municipio) {
          return { id: municipio.id, text: municipio.text };
        });
    });
  }

  handleDepartmentChange("#Departamento1", "#Municipio1");
  handleDepartmentChange("#Departamento2", "#Municipio2");
});
