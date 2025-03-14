<?php

declare(strict_types=1);

namespace Drupal\Tests\system\Kernel\Migrate\d6;

use Drupal\Tests\migrate_drupal\Kernel\d6\MigrateDrupal6TestBase;

/**
 * Migrates various configuration objects owned by the System module.
 *
 * @group migrate_drupal_6
 */
class MigrateSystemConfigurationTest extends MigrateDrupal6TestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['file', 'system'];

  /**
   * The expected configuration after migration.
   */
  protected $expectedConfig = [
    'system.cron' => [
      'threshold' => [
        'requirements_warning' => 172800,
        'requirements_error' => 1209600,
      ],
      // Logging is not handled by the migration.
      'logging' => TRUE,
    ],
    'system.date' => [
      'first_day' => 4,
      // Country is not handled by the migration.
      'country' => [
        'default' => NULL,
      ],
      // Timezone is not handled by the migration.
      'timezone' => [
        'default' => 'Europe/Paris',
        'user' => [
          'configurable' => FALSE,
          // Default is not handled by the migration.
          'default' => 0,
          // Warn is not handled by the migration.
          'warn' => FALSE,
        ],
      ],
    ],
    'system.file' => [
      'allow_insecure_uploads' => TRUE,
      // default_scheme is not handled by the migration.
      'default_scheme' => 'public',
      // temporary_maximum_age is not handled by the migration.
      'temporary_maximum_age' => 21600,
    ],
    'system.image.gd' => [
      'jpeg_quality' => 75,
    ],
    'system.image' => [
      'toolkit' => 'gd',
    ],
    'system.logging' => [
      'error_level' => 'some',
    ],
    'system.maintenance' => [
      // Langcode is not handled by the migration.
      'langcode' => 'en',
      'message' => 'Drupal is currently under maintenance. We should be back shortly. Thank you for your patience.',
    ],
    'system.performance' => [
      'cache' => [
        'page' => [
          'max_age' => 0,
        ],
      ],
      'css' => [
        'preprocess' => FALSE,
        // Gzip is not handled by the migration.
        'gzip' => TRUE,
      ],
      // fast_404 is not handled by the migration.
      'fast_404' => [
        'enabled' => TRUE,
        'paths' => '/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i',
        'exclude_paths' => '/\/(?:styles|imagecache)\//',
        'html' => '<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>',
      ],
      'js' => [
        'preprocess' => FALSE,
        // Gzip is not handled by the migration.
        'gzip' => TRUE,
      ],
    ],
    'system.rss' => [
      'items' => [
        'view_mode' => 'title',
      ],
    ],
    'system.site' => [
      // Neither langcode nor default_langcode are not handled by the migration.
      'langcode' => 'en',
      // UUID is not handled by the migration.
      'uuid' => '',
      'name' => 'site_name',
      'mail' => 'site_mail@example.com',
      'slogan' => 'Migrate rocks',
      'page' => [
        '403' => '/user',
        '404' => '/page-not-found',
        'front' => '/node',
      ],
      'admin_compact_mode' => FALSE,
      'weight_select_max' => 100,
      'default_langcode' => 'en',
      'mail_notification' => NULL,
    ],
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Delete 'site_frontpage' in order to test the migration of a non-existing
    // front page link.
    $this->sourceDatabase->delete('variable')
      ->condition('name', 'site_frontpage')
      ->execute();

    $migrations = [
      'd6_system_cron',
      'd6_system_date',
      'd6_system_file',
      'system_image_gd',
      'system_image',
      'system_logging',
      'system_maintenance',
      'd6_system_performance',
      'system_rss',
      'system_site',
    ];
    $this->executeMigrations($migrations);
  }

  /**
   * Tests that all expected configuration gets migrated.
   */
  public function testConfigurationMigration(): void {
    foreach ($this->expectedConfig as $config_id => $values) {
      $actual = \Drupal::config($config_id)->get();
      unset($actual['_core']);
      $this->assertSame($values, $actual, $config_id . ' matches expected values.');
    }
  }

}
