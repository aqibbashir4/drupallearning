<?php

namespace Drupal\Core\TypedData\Plugin\DataType;

use Drupal\Core\Serialization\Attribute\JsonSchema;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\Attribute\DataType;
use Drupal\Core\TypedData\Type\StringInterface;

/**
 * The Email data type.
 *
 * The plain value of Email is the email address represented as PHP string.
 */
#[DataType(
  id: "email",
  label: new TranslatableMarkup("Email"),
  constraints: ["Email" => []],
)]
class Email extends StringData implements StringInterface {

  /**
   * {@inheritdoc}
   */
  #[JsonSchema(['type' => 'string', 'format' => 'email'])]
  public function getCastedValue() {
    return parent::getCastedValue();
  }

}
