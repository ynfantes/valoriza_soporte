<?php
/**
 * Build a configuration array to pass to `Hybridauth\Hybridauth`
 */

$config = [
  /**
   * Set the Authorization callback URL to https://path/to/hybridauth/examples/example_06/callback.php.
   * Understandably, you need to replace 'path/to/hybridauth' with the real path to this script.
   */
  'callback' => 'https://soporte.administracion-condominio.com.ve/vendor/hybridauth/hybridauth/examples/example_06/callback.php',
  'providers' => [
    'Twitter' => [
      'enabled' => true,
      'keys' => [
        'key' => '...',
        'secret' => '...',
      ],
    ],
    'LinkedIn' => [
      'enabled' => true,
      'keys' => [
        'id' => '...',
        'secret' => '...',
      ],
    ],
    'Facebook' => [
      'enabled' => true,
      'keys' => [
        'id' => '242011383598796',
        'secret' => '151a51441d76a82b33a9e6ac8501a896',
      ],
    ],
  ],
];
