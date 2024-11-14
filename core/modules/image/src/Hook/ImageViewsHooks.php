<?php

namespace Drupal\image\Hook;

use Drupal\field\FieldStorageConfigInterface;
use Drupal\Core\Hook\Attribute\Hook;

/**
 * Hook implementations for image.
 */
class ImageViewsHooks {

  /**
   * Implements hook_field_views_data().
   *
   * Views integration for image fields. Adds an image relationship to the default
   * field data.
   *
   * @see views_field_default_views_data()
   */
  #[Hook('field_views_data')]
  public function fieldViewsData(FieldStorageConfigInterface $field_storage) {
    $data = views_field_default_views_data($field_storage);
    foreach ($data as $table_name => $table_data) {
      // Add the relationship only on the target_id field.
      $data[$table_name][$field_storage->getName() . '_target_id']['relationship'] = [
        'id' => 'standard',
        'base' => 'file_managed',
        'entity type' => 'file',
        'base field' => 'fid',
        'label' => t('image from @field_name', [
          '@field_name' => $field_storage->getName(),
        ]),
      ];
    }
    return $data;
  }

  /**
   * Implements hook_field_views_data_views_data_alter().
   *
   * Views integration to provide reverse relationships on image fields.
   */
  #[Hook('field_views_data_views_data_alter')]
  public function fieldViewsDataViewsDataAlter(array &$data, FieldStorageConfigInterface $field_storage) {
    $entity_type_id = $field_storage->getTargetEntityTypeId();
    $field_name = $field_storage->getName();
    $entity_type_manager = \Drupal::entityTypeManager();
    $entity_type = $entity_type_manager->getDefinition($entity_type_id);
    $pseudo_field_name = 'reverse_' . $field_name . '_' . $entity_type_id;
    /** @var \Drupal\Core\Entity\Sql\DefaultTableMapping $table_mapping */
    $table_mapping = $entity_type_manager->getStorage($entity_type_id)->getTableMapping();
    [$label] = views_entity_field_label($entity_type_id, $field_name);
    $data['file_managed'][$pseudo_field_name]['relationship'] = [
      'title' => t('@entity using @field', [
        '@entity' => $entity_type->getLabel(),
        '@field' => $label,
      ]),
      'label' => t('@field_name', [
        '@field_name' => $field_name,
      ]),
      'help' => t('Relate each @entity with a @field set to the image.', [
        '@entity' => $entity_type->getLabel(),
        '@field' => $label,
      ]),
      'group' => $entity_type->getLabel(),
      'id' => 'entity_reverse',
      'base' => $entity_type->getDataTable() ?: $entity_type->getBaseTable(),
      'entity_type' => $entity_type_id,
      'base field' => $entity_type->getKey('id'),
      'field_name' => $field_name,
      'field table' => $table_mapping->getDedicatedDataTableName($field_storage),
      'field field' => $field_name . '_target_id',
      'join_extra' => [
        0 => [
          'field' => 'deleted',
          'value' => 0,
          'numeric' => TRUE,
        ],
      ],
    ];
  }

}
