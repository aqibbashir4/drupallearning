<?php

declare(strict_types=1);

namespace Drupal\entity_reference_test\Hook;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Hook\Attribute\Hook;

/**
 * Hook implementations for entity_reference_test.
 */
class EntityReferenceTestHooks {

  /**
   * Implements hook_entity_base_field_info().
   */
  #[Hook('entity_base_field_info')]
  public function entityBaseFieldInfo(EntityTypeInterface $entity_type) {
    $fields = [];
    if ($entity_type->id() === 'entity_test') {
      $fields['user_role'] = BaseFieldDefinition::create('entity_reference')->setLabel(t('User role'))->setDescription(t('The role of the associated user.'))->setSetting('target_type', 'user_role')->setSetting('handler', 'default');
    }
    return $fields;
  }

  /**
   * Implements hook_entity_base_field_info_alter().
   */
  #[Hook('entity_base_field_info_alter')]
  public function entityBaseFieldInfoAlter(&$fields, EntityTypeInterface $entity_type) {
    if ($entity_type->id() === 'entity_test') {
      // Allow user_id field to use configurable widget.
      $fields['user_id']->setSetting('handler', 'default')->setDisplayOptions('form', ['type' => 'entity_reference_autocomplete', 'weight' => 0])->setDisplayConfigurable('form', TRUE);
    }
  }

}
