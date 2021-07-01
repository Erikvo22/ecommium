# Prueba técnica perfil ecommerce-developer

Importante: te recomendamos que leas antes las [consideraciones generales](../../../-/tree/main) comunes a todas las 
pruebas de este repositorio.

## Introducción 

El objetivo de esta prueba es demostrar tus capacidades para desarrollar un módulo para extraer datos de una
plataforma de ecommerce: Prestashop, Magento, Wordpress con Woocommerce y Shopify.

Únicamente deberás realizar la prueba en la/s plataforma/s que te hayamos indicado, preferiblemente en la última versión 
estable de cada una, salvo que tengas más experiencia con una versión anterior. Ten presente los requerimientos mínimos 
de cada una para escribir código PHP compatible.

Ejemplo: Prestashop 1.6 podría ser instalado en PHP 5.2+ y Prestashop 1.7 en PHP 5.4+.

# Descripción de tareas

Debes implementar un módulo instalable en la plataforma para poder extraer datos usando una API. Para la prueba puedes 
ignorar cualquier concepto de autentificación y autorización.

El módulo debe habilitar un endpoint de "products" al que se pueda invocar con una petición HTTP GET y devolver una respuesta en JSON
con datos de los productos registrados del catálogo.

La respuesta debe estar paginada, devolviendo una cantidad establecida de productos en cada respuesta para no saturar
el servidor. El objetivo es poder encadenar peticiones al API hasta descargar todos los productos.

Se deben aceptar los siguientes parámetros vía GET:

* page: entero positivo obligatorio. Número de página solicitada, si se omite la página solicitada es la 1.
* limit: entero positivo obligatorio. Límite de productos por página, si se omite, el valor por defecto es 10.

### Ejemplo de petición y respuesta
Petición con los valores por defecto pasados explícitamente:

`GET http://base_url/api_endpoint/products?page=1&limit=10`

```json5
{
  "pagination": {
    "page": 1,
    "total_pages": 172,
    "limit": 10,
    "has_more_pages": true
  },
  "records": [
    {
      "id": 25,
      "name": "Zapatillas negras",
      "description": "Zapatillas de lona negra 100% ecológicas.",
      "price": 43.25,
      "quantity": 25,
      "active": true,
      "created_at": "2021-04-01T12:07:44.027024Z"
    },
    {
      "id": 26,
      "name": "Zapatillas rojas",
      "description": "Zapatillas de lona roja 100% ecológicas.",
      "price": 40.25,
      "quantity": 1,
      "active": true,
      "created_at": "2021-05-01T12:07:44.027024Z"
    }
    // ... Resto de productos siguiendo el mismo formato
  ]
}
```

Notas sobre algunos de los campos:

* **pagination.has_more_pages**: devolver true siempre, salvo que después de la página actual no haya más productos.
* **records.created_at**: devolver la fecha y hora en formato ISO 8601 usando el estándar por defecto de PHP.