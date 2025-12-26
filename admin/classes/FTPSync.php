<?php
/**
 * Clase SSHSync - Versión phpseclib (puro PHP, sin extensión ssh2)
 * Sincroniza imágenes de local a producción vía SSH/SFTP
 * 
 * Requiere: phpseclib (se instala vía Composer)
 * composer require phpseclib/phpseclib
 */

// Cargar autoload de Composer
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
} else {
    throw new Exception("phpseclib no instalado. Ejecuta: composer require phpseclib/phpseclib");
}

use phpseclib3\Net\SFTP;
use phpseclib3\Crypt\PublicKeyLoader;

class SSHSync {
    private $sftp = null;
    private $host;
    private $port;
    private $user;
    private $pass;
    private $auth_type;
    private $private_key_path;
    private $private_key_pass;
    private $error_log = [];
    private $success_count = 0;
    private $error_count = 0;

    /**
     * Constructor - Establece la conexión SSH
     */
    public function __construct() {
        $this->host = SSH_HOST;
        $this->port = SSH_PORT;
        $this->user = SSH_USER;
        $this->pass = SSH_PASS;
        $this->auth_type = SSH_AUTH_TYPE;
        $this->private_key_path = defined('SSH_PRIVATE_KEY_PATH') ? SSH_PRIVATE_KEY_PATH : null;
        $this->private_key_pass = defined('SSH_PRIVATE_KEY_PASS') ? SSH_PRIVATE_KEY_PASS : '';

        if (!$this->connect()) {
            throw new Exception("No se pudo conectar al servidor SSH");
        }
    }

    /**
     * Conecta al servidor SSH
     */
    private function connect() {
        try {
            // Conectar vía SFTP
            $this->sftp = new SFTP($this->host, $this->port);
            
            if (!$this->sftp) {
                $this->error_log[] = "Error: No se pudo conectar a {$this->host}:{$this->port}";
                return false;
            }

            // Autenticar
            if ($this->auth_type === 'key') {
                if (!file_exists($this->private_key_path)) {
                    $this->error_log[] = "Error: Archivo de clave privada no encontrado: {$this->private_key_path}";
                    return false;
                }

                $key = PublicKeyLoader::load(file_get_contents($this->private_key_path), $this->private_key_pass);
                
                if (!$this->sftp->login($this->user, $key)) {
                    $this->error_log[] = "Error: Autenticación por clave privada fallida";
                    return false;
                }
            } else {
                // Autenticación por contraseña
                if (!$this->sftp->login($this->user, $this->pass)) {
                    $this->error_log[] = "Error: Credenciales SSH inválidas";
                    return false;
                }
            }

            return true;
        } catch (Exception $e) {
            $this->error_log[] = "Excepción SSH: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Desconecta del servidor SSH
     */
    public function disconnect() {
        if ($this->sftp) {
            $this->sftp->disconnect();
            $this->sftp = null;
        }
    }

    /**
     * Sincroniza imágenes de servicios
     */
    public function syncImagenesServicios() {
        $local_path = dirname(__DIR__) . '/classes/imgServicio';
        $remote_path = SSH_REMOTE_IMG_SERVICIO;

        return $this->syncFolder($local_path, $remote_path, 'Imágenes de Servicios');
    }

    /**
     * Sincroniza imágenes de blog
     */
    public function syncImagenesBlog() {
        $local_path = dirname(__DIR__) . '/classes/imgBlog';
        $remote_path = SSH_REMOTE_IMG_BLOG;

        return $this->syncFolder($local_path, $remote_path, 'Imágenes de Blog');
    }

    /**
     * Sincroniza imágenes de categorías
     */
    public function syncImagenesCategorias() {
        $local_path = dirname(__DIR__) . '/../img/categoria_servicio';
        $remote_path = SSH_REMOTE_IMG_CATEGORIA;

        return $this->syncFolder($local_path, $remote_path, 'Imágenes de Categorías');
    }

    /**
     * Sincroniza una carpeta completa
     */
    private function syncFolder($local_path, $remote_path, $description) {
        if (!is_dir($local_path)) {
            $this->error_log[] = "Error: Carpeta local no existe: {$local_path}";
            return false;
        }

        // Crear carpeta remota si no existe
        if (!$this->createRemoteDir($remote_path)) {
            $this->error_log[] = "Advertencia: Problema creando carpeta remota: {$remote_path}";
        }

        $files = @scandir($local_path);
        if ($files === false) {
            $this->error_log[] = "Error: No se pudo leer carpeta: {$local_path}";
            return false;
        }

        $uploaded = 0;
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $local_file = $local_path . DIRECTORY_SEPARATOR . $file;
            $remote_file = $remote_path . $file;

            if (is_file($local_file)) {
                if ($this->uploadFile($local_file, $remote_file)) {
                    $uploaded++;
                    $this->success_count++;
                    $this->error_log[] = "✓ Subido: {$file}";
                } else {
                    $this->error_count++;
                    $this->error_log[] = "✗ Error al subir: {$file}";
                }
            }
        }

        $this->error_log[] = "{$description}: {$uploaded} archivo(s) sincronizado(s)";
        return true;
    }

    /**
     * Crea una carpeta remota recursivamente
     */
    private function createRemoteDir($path) {
        try {
            $path = rtrim($path, '/');
            $parts = explode('/', trim($path, '/'));
            $current_path = '/';

            foreach ($parts as $part) {
                if (empty($part)) continue;
                
                $current_path .= $part . '/';
                
                if (!$this->sftp->is_dir($current_path)) {
                    if (!$this->sftp->mkdir($current_path, 0755, true)) {
                        $this->error_log[] = "Advertencia: No se pudo crear {$current_path}";
                    }
                }
            }

            return true;
        } catch (Exception $e) {
            $this->error_log[] = "Error creando directorio remoto: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Sube un archivo vía SFTP
     */
    private function uploadFile($local_file, $remote_file) {
        try {
            if (!file_exists($local_file)) {
                $this->error_log[] = "Error: Archivo local no existe: {$local_file}";
                return false;
            }

            // Leer archivo local
            $content = file_get_contents($local_file);
            
            if ($content === false) {
                $this->error_log[] = "Error: No se pudo leer {$local_file}";
                return false;
            }

            // Escribir en servidor remoto
            if (!$this->sftp->put($remote_file, $content, SFTP::SOURCE_STRING)) {
                $this->error_log[] = "Error escribiendo {$remote_file}";
                return false;
            }

            return true;
        } catch (Exception $e) {
            $this->error_log[] = "Excepción al subir {$local_file}: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Retorna el log de operaciones
     */
    public function getLog() {
        return $this->error_log;
    }

    /**
     * Retorna contador de éxitos
     */
    public function getSuccessCount() {
        return $this->success_count;
    }

    /**
     * Retorna contador de errores
     */
    public function getErrorCount() {
        return $this->error_count;
    }

    /**
     * Sincroniza TODAS las imágenes
     */
    public function syncAll() {
        $this->error_log[] = "=== INICIANDO SINCRONIZACIÓN DE IMÁGENES ===";
        $this->error_log[] = "Fecha/Hora: " . date('Y-m-d H:i:s');
        $this->error_log[] = "Servidor: " . $this->host . ":" . $this->port;
        $this->error_log[] = "Usuario: " . $this->user;
        $this->error_log[] = "";

        $this->syncImagenesServicios();
        $this->error_log[] = "";
        
        $this->syncImagenesBlog();
        $this->error_log[] = "";
        
        $this->syncImagenesCategorias();
        $this->error_log[] = "";

        $this->error_log[] = "=== RESUMEN FINAL ===";
        $this->error_log[] = "Archivos subidos: {$this->success_count}";
        $this->error_log[] = "Errores: {$this->error_count}";
        $this->error_log[] = "=== SINCRONIZACIÓN COMPLETADA ===";
    }

    /**
     * Destructor - Desconecta SSH
     */
    public function __destruct() {
        $this->disconnect();
    }
}
    private $ssh_conn = null;
    private $sftp_conn = null;
    private $host;
    private $port;
    private $user;
    private $pass;
    private $auth_type;
    private $private_key_path;
    private $private_key_pass;
    private $error_log = [];
    private $success_count = 0;
    private $error_count = 0;

    /**
     * Constructor - Establece la conexión SSH
     */
    public function __construct() {
        $this->host = SSH_HOST;
        $this->port = SSH_PORT;
        $this->user = SSH_USER;
        $this->pass = SSH_PASS;
        $this->auth_type = SSH_AUTH_TYPE;
        $this->private_key_path = defined('SSH_PRIVATE_KEY_PATH') ? SSH_PRIVATE_KEY_PATH : null;
        $this->private_key_pass = defined('SSH_PRIVATE_KEY_PASS') ? SSH_PRIVATE_KEY_PASS : '';

        if (!$this->connect()) {
            throw new Exception("No se pudo conectar al servidor SSH");
        }
    }

    /**
     * Conecta al servidor SSH
     */
    private function connect() {
        try {
            // Verificar que ssh2 está disponible
            if (!extension_loaded('ssh2')) {
                $this->error_log[] = "Advertencia: Extensión ssh2 no disponible";
                $this->error_log[] = "Asegúrate de instalar php_ssh2.dll y habilitarlo en php.ini";
                throw new Exception("Extensión ssh2 no disponible");
            }

            $this->ssh_conn = @ssh2_connect($this->host, $this->port);
            
            if (!$this->ssh_conn) {
                $this->error_log[] = "Error: No se pudo conectar a {$this->host}:{$this->port}";
                return false;
            }

            // Autenticar
            if ($this->auth_type === 'key') {
                if (!file_exists($this->private_key_path)) {
                    $this->error_log[] = "Error: Archivo de clave privada no encontrado: {$this->private_key_path}";
                    ssh2_disconnect($this->ssh_conn);
                    return false;
                }

                if (!@ssh2_auth_pubkey_file(
                    $this->ssh_conn,
                    $this->user,
                    $this->private_key_path . '.pub',
                    $this->private_key_path,
                    $this->private_key_pass
                )) {
                    $this->error_log[] = "Error: Autenticación por clave privada fallida";
                    ssh2_disconnect($this->ssh_conn);
                    return false;
                }
            } else {
                // Autenticación por contraseña
                if (!@ssh2_auth_password($this->ssh_conn, $this->user, $this->pass)) {
                    $this->error_log[] = "Error: Credenciales SSH inválidas";
                    ssh2_disconnect($this->ssh_conn);
                    return false;
                }
            }

            // Abrir SFTP
            $this->sftp_conn = @ssh2_sftp($this->ssh_conn);
            if (!$this->sftp_conn) {
                $this->error_log[] = "Error: No se pudo abrir sesión SFTP";
                ssh2_disconnect($this->ssh_conn);
                return false;
            }

            return true;
        } catch (Exception $e) {
            $this->error_log[] = "Excepción SSH: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Desconecta del servidor SSH
     */
    public function disconnect() {
        if ($this->ssh_conn) {
            @ssh2_disconnect($this->ssh_conn);
            $this->ssh_conn = null;
            $this->sftp_conn = null;
        }
    }

    /**
     * Sincroniza imágenes de servicios
     */
    public function syncImagenesServicios() {
        $local_path = dirname(__DIR__) . '/classes/imgServicio';
        $remote_path = SSH_REMOTE_IMG_SERVICIO;

        return $this->syncFolder($local_path, $remote_path, 'Imágenes de Servicios');
    }

    /**
     * Sincroniza imágenes de blog
     */
    public function syncImagenesBlog() {
        $local_path = dirname(__DIR__) . '/classes/imgBlog';
        $remote_path = SSH_REMOTE_IMG_BLOG;

        return $this->syncFolder($local_path, $remote_path, 'Imágenes de Blog');
    }

    /**
     * Sincroniza imágenes de categorías
     */
    public function syncImagenesCategorias() {
        $local_path = dirname(__DIR__) . '/../img/categoria_servicio';
        $remote_path = SSH_REMOTE_IMG_CATEGORIA;

        return $this->syncFolder($local_path, $remote_path, 'Imágenes de Categorías');
    }

    /**
     * Sincroniza una carpeta completa
     */
    private function syncFolder($local_path, $remote_path, $description) {
        if (!is_dir($local_path)) {
            $this->error_log[] = "Error: Carpeta local no existe: {$local_path}";
            return false;
        }

        // Crear carpeta remota si no existe
        if (!$this->createRemoteDir($remote_path)) {
            $this->error_log[] = "Advertencia: Problema creando carpeta remota: {$remote_path}";
        }

        $files = @scandir($local_path);
        if ($files === false) {
            $this->error_log[] = "Error: No se pudo leer carpeta: {$local_path}";
            return false;
        }

        $uploaded = 0;
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $local_file = $local_path . DIRECTORY_SEPARATOR . $file;
            $remote_file = $remote_path . $file;

            if (is_file($local_file)) {
                if ($this->uploadFile($local_file, $remote_file)) {
                    $uploaded++;
                    $this->success_count++;
                    $this->error_log[] = "✓ Subido: {$file}";
                } else {
                    $this->error_count++;
                    $this->error_log[] = "✗ Error al subir: {$file}";
                }
            }
        }

        $this->error_log[] = "{$description}: {$uploaded} archivo(s) sincronizado(s)";
        return true;
    }

    /**
     * Crea una carpeta remota recursivamente
     */
    private function createRemoteDir($path) {
        try {
            $path = rtrim($path, '/');
            $parts = explode('/', trim($path, '/'));
            $current_path = '/';

            foreach ($parts as $part) {
                if (empty($part)) continue;
                
                $current_path .= $part . '/';
                $sftp_path = 'ssh2.sftp://' . intval($this->sftp_conn) . $current_path;
                
                if (!is_dir($sftp_path)) {
                    @mkdir($sftp_path, 0755, true);
                }
            }

            return true;
        } catch (Exception $e) {
            $this->error_log[] = "Error creando directorio remoto: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Sube un archivo vía SFTP
     */
    private function uploadFile($local_file, $remote_file) {
        try {
            $sftp_path = 'ssh2.sftp://' . intval($this->sftp_conn) . $remote_file;
            
            // Copiar archivo
            if (!@copy($local_file, $sftp_path)) {
                $this->error_log[] = "Error copiando {$local_file} a {$remote_file}";
                return false;
            }

            return true;
        } catch (Exception $e) {
            $this->error_log[] = "Excepción al subir {$local_file}: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Retorna el log de operaciones
     */
    public function getLog() {
        return $this->error_log;
    }

    /**
     * Retorna contador de éxitos
     */
    public function getSuccessCount() {
        return $this->success_count;
    }

    /**
     * Retorna contador de errores
     */
    public function getErrorCount() {
        return $this->error_count;
    }

    /**
     * Sincroniza TODAS las imágenes
     */
    public function syncAll() {
        $this->error_log[] = "=== INICIANDO SINCRONIZACIÓN DE IMÁGENES ===";
        $this->error_log[] = "Fecha/Hora: " . date('Y-m-d H:i:s');
        $this->error_log[] = "Servidor: " . $this->host . ":" . $this->port;
        $this->error_log[] = "Usuario: " . $this->user;
        $this->error_log[] = "";

        $this->syncImagenesServicios();
        $this->error_log[] = "";
        
        $this->syncImagenesBlog();
        $this->error_log[] = "";
        
        $this->syncImagenesCategorias();
        $this->error_log[] = "";

        $this->error_log[] = "=== RESUMEN FINAL ===";
        $this->error_log[] = "Archivos subidos: {$this->success_count}";
        $this->error_log[] = "Errores: {$this->error_count}";
        $this->error_log[] = "=== SINCRONIZACIÓN COMPLETADA ===";
    }

    /**
     * Destructor - Desconecta SSH
     */
    public function __destruct() {
        $this->disconnect();
    }
}
