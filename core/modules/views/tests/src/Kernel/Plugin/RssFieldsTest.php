<?php

declare(strict_types=1);

namespace Drupal\Tests\views\Kernel\Plugin;

use Drupal\Tests\node\Traits\ContentTypeCreationTrait;
use Drupal\Tests\node\Traits\NodeCreationTrait;
use Drupal\Tests\user\Traits\UserCreationTrait;
use Drupal\Tests\views\Kernel\ViewsKernelTestBase;
use Drupal\views\Views;

/**
 * Tests \Drupal\views\Plugin\views\row\RssFields.
 *
 * @group views
 */
class RssFieldsTest extends ViewsKernelTestBase {
  use NodeCreationTrait;
  use ContentTypeCreationTrait;
  use UserCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['node', 'field', 'text', 'filter'];

  /**
   * {@inheritdoc}
   */
  public static $testViews = ['test_display_feed'];

  /**
   * {@inheritdoc}
   */
  protected function setUp($import_test_views = TRUE): void {
    parent::setUp($import_test_views);

    $this->installEntitySchema('user');
    $this->installEntitySchema('node');
    $this->installConfig(['node', 'filter']);
    $this->createContentType(['type' => 'article']);
  }

  /**
   * Tests correct processing of RSS fields.
   *
   * This overlaps with \Drupal\Tests\views\Functional\Plugin\DisplayFeedTest to
   * ensure that root-relative links also work in a scenario without
   * subdirectory.
   */
  public function testRssFields(): void {
    $this->setUpCurrentUser([], ['access content']);

    $date = '1975-05-18';

    $node = $this->createNode([
      'type' => 'article',
      'title' => 'Article title',
      'created' => strtotime($date),
      'body' => [
        0 => [
          'value' => 'A paragraph',
          'format' => filter_default_format(),
        ],
      ],
    ]);

    $node_url = $node->toUrl()->setAbsolute()->toString();

    $renderer = $this->container->get('renderer');

    $view = Views::getView('test_display_feed');
    $output = $view->preview('feed_2');
    $output = (string) $renderer->renderRoot($output);
    $this->assertStringContainsString('<link>' . $node_url . '</link>', $output);
    $this->assertStringContainsString('<pubDate>' . $date . '</pubDate>', $output);
  }

}
