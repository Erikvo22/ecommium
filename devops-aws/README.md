# Reto Dev-Ops: Microservicio para guardar en base de datos los datos pasados a través de una API REST.

## Importante

Si has llegado directamente a este espacio de trabajo te recomendamos que leas antes
las [consideraciones generales a tener en cuenta](../../../-/tree/main).

## Descripción general
El objetivo de esta prueba es generar una API REST sencilla que solo responda a peticiones de creación de recursos (POST).
El contenido de la petición deberá ser procesado añadiendo marcas de tiempo (created_at, finished_at) en el objeto JSON recibido y el mismo contenido deberá ser guardado en base de datos.

## Historias de usuario
* Yo como experto del dominio o product owner quiero agilizar el procesado masivo de ciertos datos críticos para nuestro sistema de Business Intelligence.
Actualmente ese procesado es lento y a veces bloquea el funcionamiento de nuestra principal vía de negocio por hacer uso
de los mismos recursos informáticos.


* Yo como encargado de la parte de IT de la compañía quiero mejorar esta situación e independizar el proceso de tratamiento masivo de datos, para ello propongo:
  * Usar un proveedor cloud para poder escalar horizontalmente y acelerar el procesado.
  * Proponer los detalles de infraestructura (qué servicios de AWS pueden ser los más adecuados en cuanto a
  costo/rendimiento) para el procesado y el guardado de los datos (se propone el uso de DynamoDB o similar).
  * Para simplificar la configuración de todos los servicios involucrados en la nube usaré alguna herramienta para orquestar todo el proceso de despliegue. 
  

* Yo como desarrollador recibiré el contenido del cuerpo de la petición POST:
  * añadiré marcas de tiempo (created_at, finished_at) y guardaré los datos en la base de datos correspondiente.
  * NOTA: para la marca de tiempo finished_at se puede meter un delay para ver las diferencias de tiempo.


* Yo como usuario final de la aplicación quiero hacer una llamada a un endpoint para arrancar el procesado masivo a demanda:
  * Tener un punto de acceso (API REST) para poder crear una solicitud de procesado (POST) pasando como cuerpo de la petición un JSON con un identificador único UUID y un texto que describa la acción (Puede ser un ENUM con cualquier dato).
    
## Criterios de aceptación
* La llamada a la API debe devolver un código de éxito si todo ha ido bien o un código de error si el cuerpo de la petición no está bien formado.