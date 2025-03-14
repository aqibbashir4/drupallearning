<?php

declare(strict_types=1);

namespace Drupal\Tests\jsonapi\Functional;

use Drupal\jsonapi\JsonApiSpec;
use Drupal\Core\Url;
use Drupal\rest\Entity\RestResourceConfig;

/**
 * JSON:API integration test for the "RestResourceConfig" config entity type.
 *
 * @group jsonapi
 */
class RestResourceConfigTest extends ConfigEntityResourceTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['rest', 'dblog'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $entityTypeId = 'rest_resource_config';

  /**
   * {@inheritdoc}
   */
  protected static $resourceTypeName = 'rest_resource_config--rest_resource_config';

  /**
   * {@inheritdoc}
   *
   * @var \Drupal\rest\RestResourceConfigInterface
   */
  protected $entity;

  /**
   * {@inheritdoc}
   */
  protected function setUpAuthorization($method): void {
    $this->grantPermissionsToTestedRole(['administer rest resources']);
  }

  /**
   * {@inheritdoc}
   */
  protected function createEntity() {
    $rest_resource_config = RestResourceConfig::create([
      'id' => 'llama',
      'plugin_id' => 'dblog',
      'granularity' => 'method',
      'configuration' => [
        'GET' => [
          'supported_formats' => [
            'json',
          ],
          'supported_auth' => [
            'cookie',
          ],
        ],
      ],
    ]);
    $rest_resource_config->save();

    return $rest_resource_config;
  }

  /**
   * {@inheritdoc}
   */
  protected function getExpectedDocument(): array {
    $self_url = Url::fromUri('base:/jsonapi/rest_resource_config/rest_resource_config/' . $this->entity->uuid())->setAbsolute()->toString(TRUE)->getGeneratedUrl();
    return [
      'jsonapi' => [
        'meta' => [
          'links' => [
            'self' => ['href' => JsonApiSpec::SUPPORTED_SPECIFICATION_PERMALINK],
          ],
        ],
        'version' => JsonApiSpec::SUPPORTED_SPECIFICATION_VERSION,
      ],
      'links' => [
        'self' => ['href' => $self_url],
      ],
      'data' => [
        'id' => $this->entity->uuid(),
        'type' => 'rest_resource_config--rest_resource_config',
        'links' => [
          'self' => ['href' => $self_url],
        ],
        'attributes' => [
          'langcode' => 'en',
          'status' => TRUE,
          'dependencies' => [
            'module' => [
              'dblog',
              'serialization',
              'user',
            ],
          ],
          'plugin_id' => 'dblog',
          'granularity' => 'method',
          'configuration' => [
            'GET' => [
              'supported_formats' => [
                'json',
              ],
              'supported_auth' => [
                'cookie',
              ],
            ],
          ],
          'drupal_internal__id' => 'llama',
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function getPostDocument(): array {
    // @todo Update in https://www.drupal.org/node/2300677.
    return [];
  }

}
