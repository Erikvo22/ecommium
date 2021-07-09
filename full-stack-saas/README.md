# Prueba técnica perfil Full-Stack developer

> **Importante:** lee antes las [consideraciones generales](../../../-/tree/main) comunes a todas las
pruebas de este repositorio.

## Introducción
El objetivo de esta prueba es demostrar tus capacidades para desarrollar una aplicación web de gestión de procesos compuesta de:
Frontend tipo Single Page Application, Backend API HTTP REST, procesado de datos y persistencia en base de datos.

## Duración de la prueba
Implementar el 100% de las features puede llevar más de 8 horas, ya que pedimos empezar con un proyecto de cero.
Por eso te pedimos que pares cuando lleves entre 3-4 horas de prueba (a tu discreción).

Somos conscientes de que va a ser difícil que completes todas las features, así que te pedimos que antes de codificar
analices e implementes aquellas que puedan reflejar mejor tus conocimientos y/o que aporten más a la solución de cara a
presentarla a un cliente final real.

## ¿Qué nos gustaría ver en tu prueba?
> **Antes de nada:** si no tienes alguno de los conocimientos de este apartado, intenta hacer tu código y la solución lo más limpia que puedas,
teniendo en cuenta el mantenimiento futuro del código de todo el stack. **Esto es lo que más vamos a valorar, ya que todo
lo demás se puede aprender, si tu base de conocimientos es suficientemente sólida.**

Somos fans de los principios [SOLID](https://levelup.gitconnected.com/solid-principles-simplified-php-examples-based-dc6b4f8861f6), 
Arquitectura Hexagonal y DDD. Si tienes experiencia y/o conocimientos en alguno de estos enfoques intenta aplicarlos en las 
pruebas.

Nos gustaría que implementes el API con controladores muy simples que hagan uso de uno o más servicios en los que se reparta 
la lógica de negocio, usando la inyección de dependencias donde creas necesario. Además, la operativa con la base de datos debería 
hacerse usando el patrón [Repository](https://medium.com/@cesiztel/repository-pattern-en-laravel-f66fcc9ea492).
Finalmente, lo ideal es que hubiera unos pocos test unitarios de los servicios usados en el API, mockeando los colaboradores.

## Historias de usuario
Como administrador de Sellboost necesito una herramienta que permita administrar procesos siguiendo con estos requisitos:

* Tendrán un tipo que identificará que tarea deben realizar.
* Recibirán un texto de entrada que se suministrarán al crearlo. La longitud máxima será de 100 caracteres.
* Cuando se inicie y finalice la ejecución de un proceso, debera actualizarse su estado para reflejarlo.
* Debe existir una interfaz web para poder operar con la herramienta:  
    * Se deben poder crear procesos en un formulario. Habrá un botón de "crear proceso" y otro de "crear e iniciar proceso".
    * Se mostrarán en un listado para visualizar su estado y poder iniciar procesos.

Como administrador de Sellboost quiero poder lanzar procesos de tipo VOWELS_COUNT que calculen el número de vocales
en el texto de entrada al proceso.

### Interfaz de usuario
Desde el departamento de UX/UI nos han dado una primera versión de la interfaz de usuario de la herramienta:

* [Listado de procesos](processes_list.png)
* [Creación de procesos](create_process.png)

### Detalles de la arquitectura
Te proponemos una o varias opciones "preferibles" por ser nuestro stack actual. Pero puedes usar otras tecnologías si te 
sientes más ágil, siempre que la base sea con el ecosistema de PHP y NodeJS.

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

Con PHP la versión mínima será la 7 o superior, fijándote en añadir todos los tipos donde sea necesario y activando el modo estricto.

Con NodeJS la versión mínima será la 10 o superior, con preferencia de usar async / await para la gestión de promises
en lugar de callbacks.

Cuando se inicie un proceso por el API, deberá ejecutar el script correspondiente de NodeJS, pasando los datos de entrada al proceso como parámetros. 
Ejemplo: `node script.js $param1 $param2`. La ejecución será síncrona, pero esperamos en el 
futuro que sea asíncrona, por ejemplo lanzándose directamente en Docker, por lo que habrá que tenerlo en cuenta en la solución.

### Apéndice: especificación del API de gestión de procesos
No es necesario implementar un proceso de autentificación en los métodos del API. 

Tanto los clientes como el servidor deberán hacer uso de las cabeceras HTTP correctas para enviar y recibir JSON.

#### Gestión de errores
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
  "input": "Input text data"
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
      "id": "69ca144b-9b17-4fc0-a1c8-952d5fef7a42",
      "type": "VOWELS_COUNT",
      "input": "Text data",
      "output": null,
      "status": "STARTED",
      "started_at": "2021-07-05T11:52:45.44876Z",
      "finished_at": null
    },
    {
      "id": "1f6df483-a7fe-48e9-9ca7-55a1142ecbdd",
      "type": "VOWELS_COUNT",
      "input": "Text data",
      "output": "3",
      "status": "FINISHED",
      "started_at": "2021-07-05T11:52:45.44876Z",
      "finished_at": "2021-07-05T11:52:45.44876Z"
    },
    {
      "id": "a34b8277-9255-42bf-9605-9bb2298e21b4",
      "type": "VOWELS_COUNT",
      "input": "Text data",
      "output": "Error message foo",
      "status": "ERROR",
      "started_at": "2021-07-05T11:52:45.44876Z",
      "finished_at": "2021-07-05T11:52:45.44876Z"
    }
]
```

#### Iniciar proceso
`POST https://base_url/api/process/2282866f-32b5-44d1-828d-d400cd1f088f/start`

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
  "output": "Error message foo",
  "finished_at": "2021-07-05T11:56:59.745013Z"
}
```

Respuestas: 

* 200 OK, si se registra que el proceso a finalizado.
* 404 Not Found, si el proceso no existe.
* 500 Internal Server Error, si hay cualquier otro error.