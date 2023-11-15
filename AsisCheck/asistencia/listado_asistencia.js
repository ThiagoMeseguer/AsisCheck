// Evento de clic en el botón de búsqueda
if (document.getElementById('fecha')) { // Ver si existe porque sino da error
    document.getElementById('fecha').addEventListener("input", function() {
    let input = document.getElementById('fecha').value;
        if (input === "") {
            // Si está vacío, cargar todos los datos
            window.alert("Fecha no ingresada");
            //cargarDatosDesdeAPI('api_asistencai.php');
        } else {
            // Si hay un valor en el campo de búsqueda, cargar datos filtrados
            cargarDatosDesdeAPI(`api_asistencia.php?tipo=registrar&fecha=${input}`);
        }
    });
}

function cargarDatosDesdeAPI(url) {
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la solicitud a la API');
            }
            return response.json();
        })
        .then(function (data) {
            const bodyTabla = document.getElementById('body_tabla');
            if (data.length != 0) {
                // Limpiar la tabla actual
                bodyTabla.innerHTML = '';
                bodyTabla.innerHTML = `<thead><tr>
                <th>Dni</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th></th>
                </tr></thead>
                `
                data.forEach(alumno => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="col-1">${alumno.dni_alumno}</td>
                        <td class="col-1">${alumno.nombre}</td>
                        <td class="col-1">${alumno.apellido}</td>
                        <td class="col-1"><input type="checkbox" class="form-check-input form-check-xl" name="asistieron" value="${alumno.dni_alumno}"></td>
                    `;
                    bodyTabla.appendChild(row);
                    document.getElementById('btn-registrar').innerHTML = `<input type="button" class="btn btn-success" value="Registrar" onclick="agregarAsistencia()"> `
                });
            }else{
                // Si ya estan todos con asistencia
                document.getElementById('btn-registrar').innerHTML = ``; // Sacar boton de registrar 
                bodyTabla.innerHTML =`
                <img src="../images/verificar.png" style="width: 50px; height: 50px; margin-top: 35px"><br>
                <h5>Asistieron todos en esta fecha</h5>`
            }
        })
    }

    async function agregarAsistencia() {
        let asistieron = [];
        let fecha = document.getElementById("fecha").value
        const checkboxElements = document.querySelectorAll('input[name="asistieron"]');
        checkboxElements.forEach(checkbox => {
            if (checkbox.checked) {
                asistieron.push(checkbox.value);
            }
        });
        if (asistieron.length > 0) {
            const formData = new FormData();
            
            // Agregar las variables como pares clave-valor al objeto FormData
            formData.append('asistieron', asistieron);
            formData.append('fecha', fecha);
            try {
                fetch('agregar_asistencia.php', {
                    method: 'post',
                    body: formData
                }).then(response => {
                    if (response.ok) {
                      Swal.fire({
                        icon: "success",
                        title: "Agregado con exito",
                      }).then(() => {
                        // Recargar la página después de mostrar la alerta de éxito
                        window.location.reload();
                      });
                } else {
                      Swal.fire({
                        icon: "error",
                        title: "Error en el servidor",
                        text: "No se pudo procesar la solicitud."
                      });}
                    })
            } catch (error) {
                
            }
        }else{
            Swal.fire({
                icon: "error",
                text: "No selecciono ningun alumno"
              });
        }
}

function formParametros(){
    let diasclase = document.getElementById('diasclase').value;
    let promocion = document.getElementById('promocion').value;
    let regular = document.getElementById('regular').value;

    if ((promocion >= 0 && promocion <= 100) && (regular >= 0 && regular <= 100)) {
        if ((promocion >= 0 && promocion <= 100) &&(regular >= 0 && regular <= 100) &&(promocion > regular || promocion === '100') && regular < 100)  {
            let data = new FormData();
            data.append('diasclase',diasclase);
            data.append('promocion',promocion);
            data.append('regular',regular);
            fetch('../parametros/configurar_parametros.php', {
                method: 'POST',
                body: data
            }).then(
                Swal.fire({ icon: "success", title: "¡Actualizado con exito!"})
            ).then(
                modal = document.getElementById('modal_parametros'),
                modalInstance = bootstrap.Modal.getInstance(modal),
                modalInstance.hide()
            )
        } else {
          Swal.fire({
            icon: "warning",
            title: "¡Error en los porcentajes!",
            text: "El porcentaje de promocion debe ser mayor que el de regular"
          });
        }
    }else{
        Swal.fire({
            icon: "warning",
            title: "¡Error en los porcentajes!",
            text: "Porcentajes no validos"
        });
    }
}   
