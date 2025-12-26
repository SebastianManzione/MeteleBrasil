# Gestión de credenciales (SSH/DB)

Para mantener seguras las credenciales y facilitar futuros usos, usa estos pasos:

- Archivo local (git-ignored): crea `config/credentials.local.php` a partir de `config/credentials.example.php` y completa:

```php
<?php
return [
  'ssh' => [ 'host' => '185.173.111.212', 'port' => 65002, 'user' => 'u925692129', 'pass' => 'XXXXXXXX' ],
  'db'  => [ 'host' => 'localhost', 'user' => 'u925692129_metelebrasil', 'pass' => 'YYYYYYYY', 'name' => 'u925692129_metelebrasil' ]
];
```

- Alternativa variables de entorno (Windows PowerShell):

```powershell
[System.Environment]::SetEnvironmentVariable('SSH_HOST','185.173.111.212','User')
[System.Environment]::SetEnvironmentVariable('SSH_PORT','65002','User')
[System.Environment]::SetEnvironmentVariable('SSH_USER','u925692129','User')
[System.Environment]::SetEnvironmentVariable('SSH_PASS','<tu_password_ssh>','User')
[System.Environment]::SetEnvironmentVariable('DB_HOST','localhost','User')
[System.Environment]::SetEnvironmentVariable('DB_USER','u925692129_metelebrasil','User')
[System.Environment]::SetEnvironmentVariable('DB_PASS','<tu_password_db>','User')
[System.Environment]::SetEnvironmentVariable('DB_NAME','u925692129_metelebrasil','User')
```

- Carga centralizada: usa `config/creds_loader.php` para obtener credenciales en scripts nuevos:

```php
require_once __DIR__.'/config/creds_loader.php';
$creds = loadCredentials();
$sshHost = $creds['ssh']['host'];
```

## Buenas prácticas
- No commitees contraseñas en el repo (ya se ignora `config/credentials.local.php`).
- Usa HTTPS y SSH con contraseña robusta o clave pública.
- Mantén las credenciales fuera de archivos compartidos y encriptados si es posible.

## Nota
Si deseas, puedo rellenar `config/credentials.local.php` con los valores actuales para que quede listo (lo mantendré fuera del repo).