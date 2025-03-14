<?php

declare(strict_types=1);

namespace Drupal\Tests\migrate_drupal\Kernel\Plugin\migrate;

use Drupal\ban\Plugin\migrate\destination\BlockedIp;
use Drupal\KernelTests\FileSystemModuleDiscoveryDataProviderTrait;
use Drupal\migrate\Plugin\migrate\destination\ComponentEntityDisplayBase;
use Drupal\migrate\Plugin\migrate\destination\Config;
use Drupal\migrate\Plugin\migrate\destination\EntityConfigBase;
use Drupal\migrate\Plugin\migrate\destination\EntityContentBase;
use Drupal\shortcut\Plugin\migrate\destination\ShortcutSetUsers;
use Drupal\system\Plugin\migrate\destination\d7\ThemeSettings;
use Drupal\Tests\migrate_drupal\Kernel\MigrateDrupalTestBase;
use Drupal\Tests\migrate_drupal\Traits\CreateMigrationsTrait;
use Drupal\user\Plugin\migrate\destination\UserData;

/**
 * Tests that all migrations are tagged as either content or configuration.
 *
 * @group migrate_drupal
 */
class DestinationCategoryTest extends MigrateDrupalTestBase {

  use FileSystemModuleDiscoveryDataProviderTrait;
  use CreateMigrationsTrait;

  /**
   * The migration plugin manager.
   *
   * @var \Drupal\migrate\Plugin\MigrationPluginManager
   */
  protected $migrationManager;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    // Enable all modules.
    self::$modules = array_keys($this->coreModuleListDataProvider());
    parent::setUp();
    $this->migrationManager = \Drupal::service('plugin.manager.migration');
  }

  /**
   * Tests that all D6 migrations are tagged as either Configuration or Content.
   */
  public function testD6Categories(): void {
    $migrations = $this->drupal6Migrations();
    $this->assertArrayHasKey('d6_node:page', $migrations);
    $this->assertCategories($migrations);
  }

  /**
   * Tests that all D7 migrations are tagged as either Configuration or Content.
   */
  public function testD7Categories(): void {
    $migrations = $this->drupal7Migrations();
    $this->assertArrayHasKey('d7_node:page', $migrations);
    $this->assertCategories($migrations);

  }

  /**
   * Asserts that all migrations are tagged as either Configuration or Content.
   *
   * @param \Drupal\migrate\Plugin\MigrationInterface[] $migrations
   *   The migrations.
   *
   * @internal
   */
  protected function assertCategories(array $migrations): void {
    foreach ($migrations as $id => $migration) {
      $object_classes = class_parents($migration->getDestinationPlugin());
      $object_classes[] = get_class($migration->getDestinationPlugin());

      // Ensure that the destination plugin is an instance of at least one of
      // the expected classes.
      if (in_array('Configuration', $migration->getMigrationTags(), TRUE)) {
        $this->assertNotEmpty(array_intersect($object_classes, $this->getConfigurationClasses()), "The migration $id is tagged as Configuration.");
      }
      elseif (in_array('Content', $migration->getMigrationTags(), TRUE)) {
        $this->assertNotEmpty(array_intersect($object_classes, $this->getContentClasses()), "The migration $id is tagged as Content.");
      }
      else {
        $this->fail("The migration $id is not tagged as either 'Content' or 'Configuration'.");
      }
    }
  }

  /**
   * Get configuration classes.
   *
   * Configuration migrations should have a destination plugin that is an
   * instance of one of the following classes.
   *
   * @return array
   *   The configuration class names.
   */
  protected function getConfigurationClasses(): array {
    return [
      Config::class,
      EntityConfigBase::class,
      ThemeSettings::class,
      ComponentEntityDisplayBase::class,
      ShortcutSetUsers::class,
    ];
  }

  /**
   * Get content classes.
   *
   * Content migrations should have a destination plugin that is an instance
   * of one of the following classes.
   *
   * @return array
   *   The content class names.
   */
  protected function getContentClasses(): array {
    return [
      EntityContentBase::class,
      // @todo Remove BlockedIp in https://www.drupal.org/project/drupal/issues/3488827
      BlockedIp::class,
      UserData::class,
    ];
  }

}
