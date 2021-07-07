# Prueba técnica perfil Full-Stack developer

Importante: te recomendamos que leas antes las [consideraciones generales](../../../-/tree/main) comunes a todas las
pruebas de este repositorio.

## Introducción
El objetivo de esta prueba es demostrar tus capacidades para desarrollar una aplicación web de gestión de procesos compuesta de:
Frontend tipo Single Page Application, Backend API HTTP REST, procesado de datos y persistencia en base de datos.

## ¿Qué nos gustaría ver en tu prueba?
Somos fans de los principios [SOLID](https://levelup.gitconnected.com/solid-principles-simplified-php-examples-based-dc6b4f8861f6), 
Arquitectura Hexagonal y DDD. Si tienes experiencia y/o conocimientos en alguno de estos enfoques intenta aplicarlos en las 
pruebas.

Al ser un proyecto tan pequeño, puede que para poder demostrar tus habilidades tengas que hacer la solución algo más 
compleja de lo que realmente sería necesario, intentando encontrar un equilibrio entre mantenimiento y complejidad.

Preferimos que implementes el API con controladores muy simples que hagan uso de uno o más servicios en los que se reparta 
la lógica de negocio, usando la inyección de dependencias donde creas necesario. La operativa con la base de datos nos gustaría que 
se hiciera usando el patrón [Repository](https://medium.com/@cesiztel/repository-pattern-en-laravel-f66fcc9ea492).

Para terminar, lo ideal es que hubiera unos pocos test unitarios de los servicios usados en el API, mockeando los colaboradores.

Si no tienes alguno de estos conocimientos, intenta hacer tu código y la solución lo más limpia que puedas, 
teniendo en cuenta el mantenimiento futuro del código de todo el stack. **Esto es lo que más vamos a valorar, ya que todo
lo demás se puede aprender, si tu base de conocimientos es suficientemente sólida.**

¡Buena suerte!

## Historias de usuario
Como administrador de Foo Corp necesito una herramienta que permita crear y ejecutar procesos a demanda siguiendo las 
siguientes pautas:
  * Tendrán un tipo que identificará que tarea deben realizar.
  * Recibirán un texto de entrada que se suministrarán al crearlo. La longitud máxima será de 100 caracteres. 
  * Se deben poder crear en un formulario web. Habrá un botón de "crear proceso" y otro de "crear e iniciar proceso".
  * Se mostrarán en un listado para analizar su estado e iniciar los procesos que no hayan sido iniciados aún.
  * Cuando se inicie y finalice la ejecución de un proceso, debera actualizarse su estado.

Como administrador de Foo Corp quiero poder lanzar procesos de tipo VOWELS_COUNT que calcule el número de vocales
en el texto de entrada al proceso.

Como administrador de Foo Corp quiero poder lanzar procesos de tipo CONSONANTS_COUNT que calcule el número de consonantes
en el texto de entrada al proceso.

### Mockup de la interfaz de usuario
Desde el departamento de UX/UI nos han dado una primera versión de la interfaz de usuario de la herramienta a modo orientativo:

* [Listado de procesos](resources/processes_list.png)
* [Creación de procesos](resources/create_process.png)

### Detalles de la arquitectura
Te proponemos una o varias opciones "preferibles" por ser nuestro stack actual. Pero puedes hacerlo con otras tecnologías si te 
sientes más cómodo siempre que la base sea con el ecosistema de PHP y NodeJS.

* Frontend: preferible en React o VueJS y usando los componentes de [Bootstrap](https://getbootstrap.com/).
* API backend: preferible PHP con Laravel o Symfony.
* Procesos: preferible en NodeJS.
* Base de datos: MySQL, MariaDB, PostgreSQL, MongoDB o Elasticsearch.

Cada aplicación de la arquitectura debería estar en una subcarpeta diferente. Por ejemplo:
* ./frontend/
* ./backend/
* ./processes/

Para el desarrollo usa el sistema que prefieras (Docker, Vagrant, entorno local, etc).
Deberás añadir un fichero en formato md / txt con unas instrucciones mínimas para ejecutar tu código correctamente.

Con PHP la versión mínima será la 7 o superior, fijándote en añadir todos los tipos donde sea necesario.

Con NodeJS la versión mínima será la 10 o superior, con preferencia de usar async / await para la gestión de promises
en lugar de callbacks.

Cuando se inicie un proceso por el API, deberá ejecutar el script correspondiente de NodeJS, pasando los datos de entrada al proceso. 
Ejemplo: `node script.js "texto de entrada"`. La ejecución en la primera versión será síncrona, pero esperamos en el 
futuro que sea asíncrona, por ejemplo lanzándose directamente en Docker, por lo que habrá que tenerlo en cuenta en la solución.

### Criterios de aceptación
1. Código sin errores ni warnings.
2. Se debe cumplir la especificación de la arquitectura y la del API descrita en el apéndice.
3. Debe haber código del stack completo: Front + API backend + scripts de procesos + base de datos para almacenar los procesos.

### Apéndice: especificación del API de gestión de procesos
Tanto los clientes como el servidor deberán hacer uso de las cabeceras HTTP correctas para enviar y recibir JSON.

Cuando se devuelva un error `500 Internal Server Error` se espera que se indique un mensaje de error mínimo de ayuda en
el body de la respuesta. Por ejemplo (no es necesario que sea exactamente igual, adaptarlo al framework):

```json5
{
  "error": true,
  "code": 500,
  "message": "The process was already started"
}
```

#### Creación de proceso
`POST https://base_url/api/process`

```json5
{
  "id": "2282866f-32b5-44d1-828d-d400cd1f088f",
  "type": "VOWELS_COUNT",
  "input": "Input text data",
}
```

Respuesta: 201 Created si se creó el proceso. 

#### Listado de procesos
`GET https://base_url/api/process`

Respuesta 200 OK, body (JSON):
```json5
[
    {
      "id": "2282866f-32b5-44d1-828d-d400cd1f088f",
      "type": "VOWELS_COUNT",
      "input": "Text data",
      "output": null,
      "status": "NOT_STARTED",
      "started_at": null,
      "finished_at": null
    },
    {
      "id": "2282866f-32b5-44d1-828d-d400cd1f088f",
      "type": "VOWELS_COUNT",
      "input": "Text data",
      "output": "3",
      "status": "FINISHED",
      "started_at": "2021-07-05T11:52:45.44876Z",
      "finished_at": "2021-07-05T11:52:45.44876Z",
    },
    // etc
]
```

#### Iniciar proceso
`POST https://base_url/api/process/2282866f-32b5-44d1-828d-d400cd1f088f/start`

Respuesta:
```json5
{
  "id": "2282866f-32b5-44d1-828d-d400cd1f088f",
  "status": "STARTED",
  "started_at": "2021-07-05T11:52:45.44876Z"
}
```

Respuestas: 

* 200 OK si se inició el proceso. 
* 404 Not Found, si el proceso no existe. 
* 500 Internal Server Error, si hay cualquier otro error.

#### Webhook proceso finalizado
`POST https://base_url/api/process/2282866f-32b5-44d1-828d-d400cd1f088f/finished`

Ejemplo de body petición finalización sin errores (OK):
```json5
{
  "status": "OK",
  "output": "5",
  "finished_at": "2021-07-05T11:56:59.745013Z"
}
```

Ejemplo de body petición finalización con errores (KO):
```json5
{
  "status": "KO",
  "error_message": "Error message foo",
  "output": "",
  "finished_at": "2021-07-05T11:56:59.745013Z"
}
```

Respuestas: 

* 200 OK, si se registra que el proceso a finalizado.
* 404 Not Found, si el proceso no existe.
* 500 Internal Server Error, si hay cualquier otro error.