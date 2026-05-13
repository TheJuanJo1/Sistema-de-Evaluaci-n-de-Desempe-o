Sistema de Evaluación de Desempeño — Requerimientos Completos

📌 OBJETIVO DEL SISTEMA
Desarrollar un sistema web para la evaluación de desempeño de:
Docentes
Administrativos
donde distintos roles institucionales evalúan únicamente las competencias asignadas, permitiendo consolidar observaciones, firmas, documentos y generar un PDF final institucional.

👥 ROLES DEL SISTEMA

1. Administrador
Será el encargado de:
Crear usuarios
Asignar roles
Crear docentes
Crear administrativos
Editar información de usuarios
Activar/desactivar usuarios
Gestionar periodos de evaluación
Ver todas las evaluaciones
Configuración general del sistema
Permisos
✅ Acceso total
 ✅ CRUD de usuarios
 ✅ CRUD de trabajadores
 ✅ Ver PDFs
 ✅ Configuración general

2. Rector/a
Evalúa únicamente:
Estratégico
Emocional
Puede:
✅ Ver listado de evaluados
 ✅ Realizar evaluación de sus competencias asignadas
 ✅ Guardar observaciones
 ✅ Editar mientras no esté cerrada
No puede:
❌ Ver competencias de otros roles
 ❌ Imprimir evaluación final

3. Coordinador Académico
Evalúa:
Académico
Comportamental
Puede:
✅ Ver únicamente sus competencias
 ✅ Agregar observaciones
 ✅ Guardar evaluación

4. Coordinador de Convivencia
Evalúa:
Cultura
Calidad
Puede:
✅ Evaluar solo esas secciones
 ✅ Agregar observaciones

5. Talento Humano
Evalúa:
Seguridad y Salud en el Trabajo
Pero además:
✅ Puede ver TODA la evaluación
 ✅ Puede imprimir PDF final
 ✅ Puede subir firma digital
 ✅ Puede cerrar evaluación
 ✅ Puede ver observaciones de todos
 ✅ Completa información final
 ✅ Puede crear trabajadores
 ✅ Puede gestionar hojas de vida y documentos

📋 FLUJO GENERAL DEL SISTEMA

1. Inicio de sesión
Cada usuario inicia sesión con sus credenciales.

2. Dashboard / Bienvenida
Vista inicial tipo panel administrativo.
Opciones:
Evaluar Docentes
Evaluar Administrativos

3. Listado de evaluados
Se muestra:
Nombre
Cargo
Estado
Acción

Botones:
Evaluar
Ver
Documentos

4. Formulario de evaluación
Dependiendo del rol:
Se muestran SOLO las competencias asignadas.
Ejemplo:
Rector
Solo verá:
Estratégico
Emocional

📊 ESCALA DE CALIFICACIÓN
Opción
Valor
Siempre
5
Casi Siempre
4
A veces
3
Casi Nunca
2
Nunca
1


📈 CÁLCULO DEL PROMEDIO
Cada competencia:
Suma de respuestas / cantidad de preguntas
Luego:
Promedio general

📝 OBSERVACIONES
Cada rol tendrá:
Campo de observaciones independiente
Ejemplo:
Observación Rector
Observación Coordinación Académica
Observación Convivencia
Observación Talento Humano
Estas observaciones aparecerán en el PDF final.

✍️ FIRMAS
Talento Humano
Puede:
✅ Subir firma digital
Formatos:
PNG
JPG
JPEG

Si NO sube firma
El PDF dejará:
_____________________
Firma
para firma manual.

📄 PDF FINAL
El PDF mostrará:
✅ Datos del evaluado
 ✅ Competencias evaluadas
 ✅ Respuestas
 ✅ Promedios
 ✅ Observaciones por rol
 ✅ Nombre de quien evaluó
 ✅ Firma subida
 ✅ Fecha
 ✅ Resultado final

🔒 CONTROL DE VISIBILIDAD
Cada rol SOLO puede ver:
Sus competencias
Sus observaciones
EXCEPTO:
Talento Humano
Que puede ver:
✅ Todo el proceso

🧑‍💼 MÓDULO DE TALENTO HUMANO
Talento Humano tendrá un módulo especial para administrar trabajadores.

👨‍🏫 GESTIÓN DE TRABAJADORES
Talento Humano podrá:
✅ Crear trabajadores
 ✅ Editar trabajadores
 ✅ Eliminar trabajadores
 ✅ Filtrar trabajadores
 ✅ Buscar trabajadores

📋 DATOS DEL TRABAJADOR
Campos:
Nombre completo
Tipo de documento
Número de documento
Cargo
Tipo:
Docente
Administrativo
Estado

📂 HOJA DE VIDA Y DOCUMENTOS
Talento Humano podrá:
✅ Subir hoja de vida inicial
 ✅ Añadir más documentos posteriormente
 ✅ Eliminar documentos
 ✅ Ver documentos
 ✅ Buscar documentos

📁 GESTIÓN DOCUMENTAL
Desde la lista de trabajadores:
Habrá un botón:
Documentos
Que abrirá una vista donde se mostrará:
Lista de archivos
Botón añadir documento
Botón eliminar documento
Buscador de archivos

🔎 FILTROS DE TRABAJADORES
Talento Humano podrá filtrar:
Todos
Solo docentes
Solo administrativos

🔗 RELACIÓN CON LAS EVALUACIONES
Los trabajadores creados por:
Administrador
Talento Humano
aparecerán automáticamente en:
Evaluar Docentes
si el tipo es:
Docente

Evaluar Administrativos
si el tipo es:
Administrativo

🖥️ VISTA RECOMENDADA
Nombre
Documento
Tipo
Estado
Acciones
Juan Pérez
123456789
Docente
Activo
Ver / Documentos / Evaluar


