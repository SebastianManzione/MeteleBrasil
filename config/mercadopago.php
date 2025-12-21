<?php
class MercadoPagoConfig
{
    private static string $environment = 'production';
    private static array $config = [
        'AR' => [
            'sandbox' => [
                'public_key' => 'APP_USR-cf99fd0d-bd3b-4038-9e53-c32f6e1fe899',
                'access_token' => 'APP_USR-8384221837051411-102019-a6f7b59c139b14d53d7376658a168a9e-2113221059',
            ],
            'production' => [
                'public_key' => 'APP_USR-1e84af2f-980f-456d-9b04-8498b5c4820a',
                'access_token' => 'APP_USR-199473761358972-101620-cae572d51b80f9e4152070592e0fcf84-342426513',
            ],
        ],
        'BR' => [
            'sandbox' => [
                'public_key' => 'APP_USR-7dd6b817-5156-4593-87b5-0bc42f3973d3',
                'access_token' => 'APP_USR-4482222184019651-102019-da6ec2127f08cb8017297fb49a686524-2116010774',
            ],
            'production' => [
                'public_key' => 'TEST-4d2c6227-0f58-4aff-93af-961aede6efd7',
                'access_token' => 'APP_USR-7870778771559994-082007-492e7493924d2c0a8f5a0ae8dedcc9cd-138180833',
            ],
        ],
    ];

    public static function getCredentials(string $country): array
    {
        $country = strtoupper($country);

        if (!isset(self::$config[$country])) {
            throw new Exception("País no soportado: {$country}");
        }

        $env = self::$environment;

        if (!isset(self::$config[$country][$env])) {
            throw new Exception("No existen credenciales para {$country} en entorno {$env}");
        }

        return self::$config[$country][$env];
    }

    /**
     * Permite cambiar el entorno desde código si se requiere.
     */
    public static function setEnvironment(string $env): void
    {
        if (!in_array($env, ['sandbox', 'production'])) {
            throw new Exception("Entorno inválido: {$env}");
        }
        self::$environment = $env;
    }

    public static function getEnvironment(): string
    {
        return self::$environment;
    }
}