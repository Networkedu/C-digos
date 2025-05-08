README General - NetWorkEdu
NetWorkEdu es una plataforma educativa, la cual a sido creada para el proyecto transversal del primer año del grado DAM en Jesuitas.En nuestyra plataforma podremos tener dos tipos de usuarios; alumnos y profesores, los cuales podran gestionar tareas y crear clases. Las tareas podran ser asiganadas a alumnos especificos ser editadas y borradas, esto es loq ue se nos pedia como obligratorio
aparte hemos añadido el apartado de clsaes muy por ecima ya que un profesor puede crear grupos de alumnos y poner el nombre que quiera a la clase y subirles un archivo correspondiente.
--Estructura del Proyecto

        /NetWorkEdu
        │
        ├── controllers/     # Lógica de negocio
        ├── models/          # Acceso a base de datos
        ├── vistas/          # Vistas del usuario
        ├── assets/          # Archivos estáticos como JS, CSS o multimedia
        ├── index.html       # Pantalla de bienvenida
        ├── index1.html      # Página de entrada a login o registro
        ├── config.php       # Configuración del entorno
        ├── conexion.php     # Conexión a la base de datos
 Archivos Globales
          index.html
              Pantalla de bienvenida con animación y acceso al sistema. Utiliza una animación de tipo "máquina de escribir" para mostrar el nombre del sistema
          index1.html
              Página de presentación con botón para iniciar sesión o registrarse. Implementa un diseño moderno, modo oscuro y video explicativo. Integra Alpine.js y TailwindCS
          config.php
            Define parámetros globales como rutas, constantes y configuraciones del sistema. 
          conexion.php
            Centraliza la conexión a la base de datos MySQL mediante PDO o MySQLi (según implementación interna).

 Controladores (/controllers)
        Autenticación y Usuario
            LoginController.php, logoutController.php, ContraseñaController.php, PerfilController.php, ActualizarPerfilController.php, register.php
        Paneles y Clases
            IndexAlumnoController.php, IndexProfesorController.php, MisClasesController.php, PanelAlumnoController.php, PanelProfesorController.php, ClasesAlumnoController.php, ClasesProfesorController.php
        Tareas
            CrearTareaController.php, EditarTareaController.php, ActualizarTareaController.php, EliminarTareaController.php, EntregarTareaController.php, TareaProfesorController.php, TareasAlumnoController.php, BorradorTareaController.php
        Clasess
            CrearClaseController.php, EditarClaseController.php, ActualizarClaseController.php, EliminarClaseController.php

Vistas (/vistas)
        Paneles y Roles
    Index_alumno.php, Index_profesor.php, Panel_alumno.php, Panel_profesor.php
    Gestión de Clases
        Clase_Alumno.php, Clase_Profesor.php, Editar_clase.php, eliminar_clase.php, mis_clases.php
    Gestión de Tareas
        Crear_tarea.php, Editar_tarea.php, eliminar_tarea.php, tareaprofesor.php, Borradortarea.php
    Usuario
        login.php, register.php, contraseña.php, menu_perfil.php, Header_total.php

 Modelos (/models)
    Autenticación y Usuario
        LoginModel.php, PerfilModel.php, ContraseñaModel.php, ActualizarPerfilModel.php
    Paneles y Clases
        PanelAlumnoModel.php, PanelProfesorModel.php, MisClasesModel.php, ClasesAlumnoModel.php, ClasesProfesorModel.php, EditarClaseModel.php, EliminarClaseModel.php
    Tareas
        CrearTareaModel.php, EditarTareaModel.php, ActualizarTareaModel.php, EliminarTareaModel.php, EntregarTareaModel.php, BorradorTareaModel.php, TareaModel.php, TareaAlumnoModel.php, TareaProfesorModel.php

 Tecnologías Usadas
    Backend: PHP 7+
    Base de Datos: MySQL
    Estructura: MVC sacada el curso aportado por jesuitas

Funcionalidades Clave
    Registro y login con gestión de roles (alumno/profesor)
    Creación, edición y eliminación de clases y tarea
    Entrega de tareas por parte de alumnos
    Gestión de usuarios y perfiles
    Vistas separadas por rol
    Animaciones y diseño responsivo
    Almacenamiento temporal en localStorage (para profesores en modo sin conexión)
