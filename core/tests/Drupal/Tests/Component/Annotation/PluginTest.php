<?php

declare(strict_types=1);

namespace Drupal\Tests\Component\Annotation;

use Drupal\Component\Annotation\Plugin;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Drupal\Component\Annotation\Plugin
 * @group Annotation
 */
class PluginTest extends TestCase {

  /**
   * @covers ::__construct
   * @covers ::parse
   * @covers ::get
   */
  public function testGet(): void {
    // Assert all values are accepted through constructor and default value is
    // used for non existent but defined property.
    $plugin = new PluginStub([
      1 => 'oak',
      'foo' => 'bar',
      'biz' => [
        'baz' => 'boom',
      ],
      'nestedAnnotation' => new Plugin([
        'foo' => 'bar',
      ]),
    ]);
    $this->assertEquals([
      // This property wasn't in our definition but is defined as a property on
      // our plugin class.
      'defaultProperty' => 'test_value',
      1 => 'oak',
      'foo' => 'bar',
      'biz' => [
        'baz' => 'boom',
      ],
      'nestedAnnotation' => [
        'foo' => 'bar',
      ],
    ], $plugin->get());

    // Without default properties, we get a completely empty plugin definition.
    $plugin = new Plugin([]);
    $this->assertEquals([], $plugin->get());
  }

  /**
   * @covers ::getProvider
   */
  public function testGetProvider(): void {
    $plugin = new Plugin(['provider' => 'example']);
    $this->assertEquals('example', $plugin->getProvider());
  }

  /**
   * @covers ::setProvider
   */
  public function testSetProvider(): void {
    $plugin = new Plugin([]);
    $plugin->setProvider('example');
    $this->assertEquals('example', $plugin->getProvider());
  }

  /**
   * @covers ::getId
   */
  public function testGetId(): void {
    $plugin = new Plugin(['id' => 'example']);
    $this->assertEquals('example', $plugin->getId());
  }

  /**
   * @covers ::getClass
   */
  public function testGetClass(): void {
    $plugin = new Plugin(['class' => 'example']);
    $this->assertEquals('example', $plugin->getClass());
  }

  /**
   * @covers ::setClass
   */
  public function testSetClass(): void {
    $plugin = new Plugin([]);
    $plugin->setClass('example');
    $this->assertEquals('example', $plugin->getClass());
  }

}
/**
 * {@inheritdoc}
 */
class PluginStub extends Plugin {

  /**
   * A default property for testing.
   */
  protected $defaultProperty = 'test_value';

}
