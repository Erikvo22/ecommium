# Prueba técnica perfil DevOps
Importante: te recomendamos que leas antes las [consideraciones generales](../../../-/tree/main) comunes a todas las
pruebas de este repositorio.

## Introducción
El objetivo de esta prueba es demostrar tus capacidades para definir y desplegar una arquitectura de un API HTTP en AWS.

## Descripción de tareas
Debes crear la arquitectura de una API HTTP sencilla que solo responda a peticiones POST para ser procesadas y almacenadas 
en una base de datos.

**Petición de ejemplo:**

`POST https://base_url/api_endpoint`

```json5
{
  "id": "2282866f-32b5-44d1-828d-d400cd1f088f",
  "message": "Mensaje a guardar"
}
```

**Respuestas:**

* 201 CREATED: si todo ha ido bien.
* 500 ERROR: si hubo un error de cualquier tipo (falta el campo id o message en la petición, falla el guardado, etc).

Se propone que el API HTTP se publique como una función en AWS Lambda y que el guardado en la base de datos se haga en un 
contenedor de Docker. Puedes escoger el lenguaje de programación que prefieras en cada caso, en que servicio de AWS
lanzas el contenedor y en que tipo / servicio de base de datos de AWS guardas los datos.

Por ejemplo podrías lanzar el contenedor en un clúster de AWS Fargate y usar como base de datos DynamoDB.

Debes utilizar un sistema que permita desplegar la solución en AWS de la forma más automatizada que puedas y en la que 
tengas más experiencia. Por ejemplo: Terraform, Gitlab pipelines, Ansible, etc.