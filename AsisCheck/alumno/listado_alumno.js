// Carga al abrir la pagina
document.addEventListener("DOMContentLoaded", function () {
  cargarDatosDesdeAPI('alumno/api_alumno.php?nombre=');
});

// CONSTANTES

const $min_dni = 10;
const $max_dni = 9999999999;
const $max_cadena = 30; // Nombre y apellido
const $fechaActual = new Date().toISOString().split("T")[0]; // Sacar fecha actual para el maximo de fecha en el formulario

// FUNCIONES

async function buscarAlumno(dni) {
  try {
    let response = await fetch('alumno/api_alumno.php?dni=' + dni);
    let data = await response.json();
    return data;
  } catch (error){
    console.log(error)
  }
}


async function calcularPromedio(presente, total) {
  promedio = ((presente * 100) / total).toFixed(0); // to fixed numeros despues del punto
  return promedio;
}

async function verificarAsistencia(dni) {
  try {
    const response = await fetch('asistencia/api_asistencia.php?tipo=asistiohoy&dni=' + dni);
    const data = await response.json();
    let asistio = (data[0][0]);
    let boton = document.getElementById('asistencia[' + dni + ']');
    if (asistio === 1) {
      return boton.classList.add("disabled");
    }
  } catch (error) {
    console.error(error);
    throw error; // Puedes lanzar el error o manejarlo aquí según tus necesidades
  }
}

async function promocion(id, $promedio, data) { // Cambiar color segun la condicion 
  try {
    let promocion = data[0]['porcentaje_promocion'];  // Porcentaje para promocionar
    let regular = data[0]['porcentaje_regular'];  // Porcentaje para regular
    let fila = document.getElementById("fila_" + id);
    if ($promedio > promocion) {
      return fila.classList.add('table-success'); // promocion
    } else if ($promedio >= regular) {
      return fila.classList.add('table-warning'); // regular
    } else {
      return fila.classList.add('table-danger'); // libre
    }
  } catch (error) {
    console.error(error);
    throw error; // Puedes lanzar el error o manejarlo aquí según tus necesidades
  }
}
async function diasclase() {
  try {
    const response = await fetch('asistencia/api_asistencia.php?tipo=diasclase');
    const data = await response.json();
    return data[0][0];
  } catch (error) {
    console.error(error);
    throw error; // Puedes lanzar el error o manejarlo aquí según tus necesidades
  }
}

async function cantAsistencia(dni) {
  try {
    const response = await fetch('asistencia/api_asistencia.php?tipo=cantasistencia&dni=' + dni);
    const data = await response.json();
    return data[0][0];
  } catch (error) {
    console.error(error);
    throw error; // Puedes lanzar el error o manejarlo aquí según tus necesidades
  }
}

async function verificarAlumnos() {
    try {
      const response = await fetch('alumno/api_alumno.php?nombre=');
      if (!response.ok) {
        throw new Error('Error en la solicitud a la API');
      }
      const data = await response.json();
      return data.length > 0;
    } catch (error) {
      return false;
    }
  } 

function validarNombreApellido(nombre) {
  // Expresión regular que verifica que el nombre contiene solo letras y espacios, y tiene al menos 2 caracteres.
  var regex = /^[A-Za-z\s]{2,30}$/;
  return regex.test(nombre);
}

function calcularEdad(fechaNacimiento) {
  const nacimiento = new Date(fechaNacimiento);
  if (Date.now() >= nacimiento.getTime()) {
    const edadDif = Date.now() - nacimiento.getTime();
    const edadFecha = new Date(edadDif);
    return Math.abs(edadFecha.getUTCFullYear() - 1970);
  }
  return 0;
}

function formatearFecha(fecha) {
  return fecha.split("-").reverse().join("/");
}

// Función para cargar datos de la API y mostrar en la tabla
async function cargarDatosDesdeAPI(url) {
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error('Error en la solicitud a la API');
    }

    const data = await response.json();
    const bodyTabla = document.getElementById('body_tabla');

    if (data.length !== 0) {
      // Limpiar la tabla actual
      bodyTabla.innerHTML = '';
      // Agregar resultados a la tabla
      const total = await diasclase();
      let response = await fetch('asistencia/api_asistencia.php?tipo=porcentajes');
      const porcentaje = await response.json();
      for (const alumno of data) {
        try {
          presente = await cantAsistencia(alumno.dni_alumno); // Calcular cantidad de asistencia
          promedio = await calcularPromedio(presente, total);
          if ((promedio > 100) || (promedio < 0)) {
            let error = document.getElementById("error");
            error.innerHTML = `<div style="display: flex;">
            <img src="images/peligro.png" style="width: 35px; height: 35px;">
            <p style="color: white; margin-top: 6px;"> Configure los días de clase en parámetros </p>
          </div>`
          }
          const row = document.createElement('tr');
          row.id = "fila_" + alumno.id_alumno; // Para poner color a la fila
          row.style.backgroundColor = "burlywood"; // 
          row.innerHTML = `
            <td class="col-1">${alumno.dni_alumno}</td>
            <td class="col-1">${alumno.apellido}</td> 
            <td class="col-1">${alumno.nombre}</td>
            <td class="col-2" style="padding-left:30px;">${formatearFecha(alumno.nacimiento)}</td>
            <td class="col-1" style="text-align:center;">${presente}</td>
            <td class="col-1" style="text-align:center">${promedio}%</td>
            <td class="col-1">
            <button id="editar" class="btn btn-warning text-center editar-alumno" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="modal_editar(${alumno.dni_alumno})">
              Editar
            </button>
            </td>
            <td class="col-3"><a id="asistencia[${alumno.dni_alumno}]" class="btn btn-success text-center" onclick=agregarAsistencia(${alumno.dni_alumno}) >Agregar Asistencia</a></td>
            <td class="col-1"><input type="button" class="btn btn-danger" onclick="deleteAlumno(${alumno.dni_alumno})" value="Eliminar"></td>
          `;
          bodyTabla.appendChild(row);
          await verificarAsistencia(alumno.dni_alumno);
          await promocion(alumno.id_alumno, promedio, porcentaje);
    
        } catch (error) {
          console.error(error);
        }
      }
    } else {
      const hayAlumnos = await verificarAlumnos();
      if (hayAlumnos) {
        // Hay alumnos, realizar alguna acción
        cargarDatosDesdeAPI("alumno/api_alumno.php?nombre=");
      } else {
        // No hay alumnos
        document.getElementById("sin_alumnos").innerHTML = `
        <br>
        <h5 style="text-align: center;color:white;">Sin alumnos registrados</h5>
      `;      
      }
    }
  } catch (error) {
    console.error(error);
    return false;
  }
}

// EVENTOS

// Mostrar modal segun dni del alumno a editar
  async function modal_editar(dniAlumno) {
    let alumno = await buscarAlumno(dniAlumno);
    document.getElementById("formulario_editar").innerHTML = `
    <div id="form_editarAlumno">
      <div class="modal-body">
        <input type="hidden" id="editar.id_alumno" name="editar.id_alumno"  value="${alumno.id_alumno}">
        <input type="hidden" id="editar.dni_viejo" value="${alumno.dni_alumno}">
        <div class="mb-3">
          Dni:<input type="number" class="form-control" id="editar.dni_alumno" name="editar.dni_alumno" min="1" max="9999999999" value="${alumno.dni_alumno}"required>
        </div>
        <div class="mb-3">
          Nombre:<input type="text" class="form-control" id="editar.nombre" name="editar.nombre" maxlength="50" value="${alumno.nombre}"required>
        </div>
        <div class="mb-3">
          Apellido:<input type="text" class="form-control" id="editar.apellido" name="editar.apellido" maxlength="50" value="${alumno.apellido}"required>
        </div>
        <div class="mb-3">
          Nacimiento:<input type="date" class="form-control" id="editar.nacimiento" name="editar.nacimiento" value="${alumno.nacimiento}"required>
        </div>
      </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="form_editar()">Actualizar</button>
    </div>
    </div>
  `;;
  }
// BOTON BUSCAR
document.getElementById('buscar').addEventListener("click", function () {
  let input = document.getElementById('nombre').value;
  if (input === "") {
    // Si está vacío, cargar todos los datos
    cargarDatosDesdeAPI('alumno/api_alumno.php?nombre=');
  } else {
    // Si hay un valor, cargar datos filtrados
    cargarDatosDesdeAPI(`alumno/api_alumno.php?nombre=${input}`);
  }
});
// FORMULARIO AGREGAR ALUMNO (index.php)
async function form_agregarAlumno() {
  try {
    let dni = document.getElementById("agregar.dni_alumno").value;
    let nombre = document.getElementById("agregar.nombre").value;
    let apellido = document.getElementById("agregar.apellido").value;
    let nacimiento = document.getElementById("agregar.nacimiento").value;
    let enviar = true;
    let errores = [];

    document.getElementById("agregar.dni_alumno").classList.remove("is-invalid");
    document.getElementById("agregar.nombre").classList.remove("is-invalid");
    document.getElementById("agregar.apellido").classList.remove("is-invalid");
    document.getElementById("agregar.nacimiento").classList.remove("is-invalid");

    if ((dni > 0)&&(dni.length <= 10)) {
      if (await buscarAlumno(dni)) { // Validar DNI
        errores.push("Dni (Duplicado)");
        enviar = false;
      }
    } else {
      document.getElementById("agregar.dni_alumno").classList.add("is-invalid");
      errores.push("Dni Invalido");
      enviar = false;
    }

    if (!validarNombreApellido(nombre)) { // Validar NOMBRE
      document.getElementById("agregar.nombre").classList.add("is-invalid");
      errores.push("Nombre");
      enviar = false;
    }

    if (!validarNombreApellido(apellido)) {
      document.getElementById("agregar.apellido").classList.add("is-invalid");
      errores.push("Apellido");
      enviar = false;
    }

    let edad = calcularEdad(nacimiento);
    let edadmin = 18;
    let edadmax = 200;
    let hoy = new Date().toISOString().split('T')[0];

    if ((nacimiento<=hoy)&&(nacimiento != '')){
      if (edad < edadmin) {
        document.getElementById("agregar.nacimiento").classList.add("is-invalid");
        errores.push("Menor de edad");
        enviar = false;
      }
      if (edad > edadmax) {
        document.getElementById("agregar.nacimiento").classList.add("is-invalid");
        errores.push(`Limite de edad ${edadmax} años`);
        enviar = false;
      }
    }else{
      document.getElementById("agregar.nacimiento").classList.add("is-invalid");
      errores.push("Fecha Invalida");
      enviar = false;
    }

    if (enviar) { // Enviar el formulario para Agregar
      let data = new FormData();
      data.append('dni_alumno', dni);
      data.append('nombre', nombre);
      data.append('apellido', apellido);
      data.append('nacimiento', nacimiento);
      fetch('alumno/agregar_alumno.php', {
        method: 'POST',
        body: data
      })
        .then(response => {
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
            });
          }
        })
    } else {
      let mensaje = ``;
      errores.forEach((element) => {
        mensaje += `${element}<br>`;
      });

      Swal.fire({
        title: 'Error',
        html: mensaje,
        icon: 'error'
      });
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: error.message
    });
  }
}

// ENVIAR FORMULARIO DE PARAMETROS (Archivo index.php)
let form_parametros = document.getElementById("form_parametros");
form_parametros.addEventListener("submit", function (event) {
  event.preventDefault(); // Prevenir el envío predeterminado del formulario
  let promocion = document.getElementById('promocion').value;
  let regular = document.getElementById('regular').value;
  document.getElementById("promocion").classList.add("is-invalid");
  document.getElementById("regular").classList.add("is-invalid");

  if ((promocion >= 0 && promocion <= 100) && (regular >= 0 && regular <= 100)) {
    if ((promocion >= 0 && promocion <= 100) &&(regular >= 0 && regular <= 100) &&(promocion > regular || promocion === '100') && regular < 100) {
      Swal.fire({ icon: "success", title: "¡Actualizado con exito!" });
      // Envía el formulario después de mostrar la alerta
      setTimeout(function () {
        form_parametros.submit();
      }, 1000);
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
});
// FORMULARIO DE EDITAR ALUMNO (Linea 200)
function form_editar() {
  try {
    let id = document.getElementById("editar.id_alumno").value;
    let dniViejo = document.getElementById("editar.dni_viejo").value;
    let dni = document.getElementById("editar.dni_alumno").value;
    let nombre = document.getElementById("editar.nombre").value;
    let apellido = document.getElementById("editar.apellido").value
    let nacimiento = document.getElementById("editar.nacimiento").value;
    document.getElementById("editar.dni_alumno").classList.remove("is-invalid");
    document.getElementById("editar.nombre").classList.remove("is-invalid");
    document.getElementById("editar.apellido").classList.remove("is-invalid");
    document.getElementById("editar.nacimiento").classList.remove("is-invalid");

    let enviar = true;
    let errores = [];
    if ((dni < 1) || (dni.length >= 10)) {
      errores.push("Dni Invalido");
      document.getElementById("editar.dni_alumno").classList.add("is-invalid");
      enviar = false;
    }
    if (!validarNombreApellido(nombre)) { // Validar NOMBRE
      errores.push("Nombre");
      document.getElementById("editar.nombre").classList.add("is-invalid");
      enviar = false;
    }

    if (!validarNombreApellido(apellido)) {
      errores.push("Apellido");
      document.getElementById("editar.apellido").classList.add("is-invalid");
      enviar = false;
    }

    let edadmin = 18;
    let edadmax = 200;
    let hoy = new Date().toISOString().split('T')[0];

    if ((nacimiento<=hoy)&&(nacimiento != '')){
      edad = calcularEdad(nacimiento)
      if (edad < edadmin) {
        document.getElementById("editar.nacimiento").classList.add("is-invalid");
        errores.push("Menor de edad");
        enviar = false;
      }
      if (edad > edadmax) {
        document.getElementById("editar.nacimiento").classList.add("is-invalid");
        errores.push(`Limite de edad ${edadmax} años`);
        enviar = false;
      }
    }else{
      document.getElementById("editar.nacimiento").classList.add("is-invalid");
      errores.push("Fecha Invalida");
      enviar = false;
    }

    if (enviar) {
      let data = new FormData();
      data.append('id_alumno', id);
      data.append('dni_viejo',dniViejo);
      data.append('dni_alumno', dni);
      data.append('nombre', nombre);
      data.append('apellido', apellido);
      data.append('nacimiento', nacimiento);
      fetch('alumno/editar_alumno.php', {
        method: 'POST',
        body: data
      })
        .then(response => {
          if (response.ok) {
            Swal.fire({
              icon: "success",
              title: "Editado con exito",
            }).then(() => {
              // Recargar la página después de mostrar la alerta de éxito
              window.location.reload();
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Error en el servidor",
              text: "No se pudo procesar la solicitud."
            });
          }
        })
    } else {
      Swal.fire({
        icon: "error",
        title: "Error",
        html: errores.join('<br>')
      });
    }
  } catch (error) {
    console.log(error);
  }
}
// ELIMINAR ALUMNO
function deleteAlumno(dni) {
  Swal.fire({
    title: "Estas seguro?",
    text: "Se perderan todas las asistencias",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#FF0000",
    cancelButtonColor: "#9E9E9E",
    confirmButtonText: "Si, Eliminar!",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(`alumno/eliminar_alumno.php?dni_alumno=${dni}`);
      Swal.fire({
        title: "Eliminado!",
        icon: "success",
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        stopKeydownPropagation: true,
        allowOutsideClick: false
      }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
          location.reload();
        }
      });
    }
  });
}

function agregarAsistencia(dni){
  fetch("asistencia/agregar_asistencia.php?dni="+dni)
  .then(response => {
    if (response.ok) {
      document.getElementById('asistencia[' + dni + ']').classList.add("disabled");
      Swal.fire({
        title: "Asistencia Agregada",
        icon: "success",
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        stopKeydownPropagation: true,
        allowOutsideClick: false
      }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
          cargarDatosDesdeAPI('alumno/api_alumno.php?nombre=');
          //location.reload();
        }
      });
    }
  })
}
