return [
  'paths' => ['api/*', 'sanctum/csrf-cookie'],
  'allowed_methods' => ['*'],
  'allowed_origins' => ['*'], // útil en dev con Electron
  'allowed_headers' => ['*'],
  'supports_credentials' => false,
];
