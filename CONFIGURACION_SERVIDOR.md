# Configuración del Servidor XAMPP para Laravel

## Problema Identificado

El error `Undefined array key 1` en `ServeCommand.php` ocurre cuando se intenta usar `php artisan serve` en Windows. **Con XAMPP NO necesitas usar este comando**.

## Solución: Usar Apache de XAMPP

### Opción 1: Acceso Directo (Más Simple)

1. **Asegúrate de que Apache esté corriendo en XAMPP**
   - Abre el Panel de Control de XAMPP
   - Haz clic en "Start" junto a Apache

2. **Accede a tu aplicación:**
   ```
   http://localhost/gisella_proyecto-electiva3/public
   ```

### Opción 2: Virtual Host (Recomendado)

1. **Edita el archivo hosts de Windows:**
   - Abre como Administrador: `C:\Windows\System32\drivers\etc\hosts`
   - Agrega esta línea al final:
   ```
   127.0.0.1    exploraquibdo.local
   ```

2. **Configura Virtual Host en Apache:**
   - Abre: `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
   - Agrega esta configuración:
   ```apache
   <VirtualHost *:80>
       ServerName exploraquibdo.local
       DocumentRoot "C:/xampp/htdocs/gisella_proyecto-electiva3/public"
       <Directory "C:/xampp/htdocs/gisella_proyecto-electiva3/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

3. **Reinicia Apache en XAMPP**

4. **Accede a tu aplicación:**
   ```
   http://exploraquibdo.local
   ```

## Verificaciones Importantes

### 1. Verificar que mod_rewrite esté habilitado
- Abre: `C:\xampp\apache\conf\httpd.conf`
- Busca: `#LoadModule rewrite_module modules/mod_rewrite.so`
- Asegúrate de que NO tenga el `#` al inicio (debe estar descomentado)

### 2. Verificar permisos de storage y cache
Ejecuta estos comandos en la terminal (en la carpeta del proyecto):
```bash
php artisan storage:link
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### 3. Verificar archivo .env
Asegúrate de que tu archivo `.env` tenga:
```
APP_URL=http://localhost/gisella_proyecto-electiva3/public
```
O si usas virtual host:
```
APP_URL=http://exploraquibdo.local
```

## Nota sobre MongoDB

Si ves errores sobre MongoDB en los logs pero no lo estás usando, puedes ignorarlos. Si quieres eliminarlos, comenta o elimina la línea de MongoDB en `config/app.php` en la sección de providers.

## Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Optimizar
php artisan optimize:clear

# Verificar rutas
php artisan route:list
```

## Solución de Problemas

### Si Apache no inicia:
1. Verifica que el puerto 80 no esté en uso
2. Cambia el puerto en `httpd.conf` si es necesario
3. Ejecuta XAMPP como Administrador

### Si ves error 500:
1. Revisa los permisos de las carpetas `storage` y `bootstrap/cache`
2. Verifica que el archivo `.env` exista y tenga `APP_KEY` generado
3. Revisa `storage/logs/laravel.log` para más detalles

### Si las rutas no funcionan:
1. Verifica que `mod_rewrite` esté habilitado
2. Verifica que el `.htaccess` esté en la carpeta `public`
3. Verifica la configuración de `AllowOverride` en Apache

