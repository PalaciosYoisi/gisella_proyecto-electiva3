# Sistema de Login y Registro - ExploraQuibdó

## ✅ Implementación Completada

### 1. Controlador de Autenticación
- **Archivo:** `app/Http/Controllers/AutenticacionController.php`
- **Métodos:**
  - `iniciarSesion()` - Procesa el login
  - `registrar()` - Procesa el registro de nuevos usuarios
  - `cerrarSesion()` - Cierra la sesión del usuario

### 2. Modelo de Usuarios Actualizado
- **Archivo:** `app/Models/usuarios.php`
- Implementa `Authenticatable` de Laravel
- Configurado para MongoDB
- Campos: id_usuario, tipo_usuario, nombre_completo, correo, contraseña, telefono, fecha_registro, estado

### 3. Rutas Configuradas
- `POST /login` - Ruta para iniciar sesión
- `POST /registro` - Ruta para registrar nuevo usuario
- `POST /logout` - Ruta para cerrar sesión

### 4. Formularios Actualizados
- Los modales de login y registro ahora envían datos por AJAX
- Validación en frontend y backend
- Mensajes de error y éxito
- Redirección automática según tipo de usuario

### 5. JavaScript Actualizado
- `public/js/inicio.js` - Manejo de formularios con AJAX
- Validación de formularios
- Manejo de respuestas del servidor
- Mensajes de error y éxito

## 🔐 Crear Usuario Administrador

### Opción 1: MongoDB Shell

```javascript
use gisella_proyecto_electiva3; // Cambiar por tu base de datos

db.usuarios.insertOne({
    id_usuario: ObjectId().toString(),
    tipo_usuario: "administrador",
    nombre_completo: "Administrador Principal",
    correo: "admin@exploraquibdo.com",
    contraseña: "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi",
    telefono: "+57 300 000 0000",
    fecha_registro: new Date(),
    estado: "activo",
    token_verificacion: null
});
```

**Credenciales por defecto:**
- Correo: `admin@exploraquibdo.com`
- Contraseña: `password`

### Opción 2: Generar Hash Personalizado

Si quieres usar otra contraseña:

```bash
php artisan tinker
```

```php
Hash::make('tu_contraseña_aqui');
```

Copia el hash y úsalo en el documento MongoDB.

## 📋 Tipos de Usuario

- `turista` - Usuario regular (por defecto en registro)
- `prestador` - Prestador de servicios turísticos
- `administrador` - Administrador del sistema

## 🔄 Flujo de Autenticación

### Registro:
1. Usuario completa el formulario
2. Validación en frontend y backend
3. Verificación de correo único
4. Hash de contraseña
5. Creación de usuario con tipo "turista"
6. Inicio de sesión automático
7. Redirección a `/panel/turista`

### Login:
1. Usuario ingresa correo y contraseña
2. Validación de credenciales
3. Verificación de estado activo
4. Creación de sesión
5. Redirección según tipo de usuario:
   - Administrador → `/panel/administrador`
   - Prestador → `/panel/prestador`
   - Turista → `/panel/turista`

## 🛠️ Próximos Pasos

1. Implementar middleware de autenticación para proteger rutas
2. Agregar funcionalidad de "Recordar sesión"
3. Implementar recuperación de contraseña
4. Agregar verificación de correo electrónico
5. Implementar roles y permisos más detallados

## ⚠️ Notas Importantes

- Las contraseñas se hashean con `Hash::make()` de Laravel
- La sesión se almacena en variables de sesión de Laravel
- El tipo de usuario determina la redirección después del login
- Los usuarios nuevos se crean con estado "activo" por defecto

