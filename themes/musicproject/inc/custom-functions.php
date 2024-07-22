<?php 
/**
 * Adds a value or values to a custom field if they are not already present.
 *
 * @param string $field_key The key of the custom field.
 * @param mixed $value The value to add to the field. Can be a single value or an array of values.
 * @param int $post_id The ID of the post to which the custom field belongs.
 */

function add_value_to_field( string $field_key, mixed $value, int $post_id): void {
    $field = get_field($field_key, $post_id) ?: [];

    if (is_array($value)) {
        foreach ($value as $single_value) {
            if (!in_array($single_value, $field)) {
                $field[] = $single_value;
            }
        }
    } else {
        if (!in_array($value, $field)) {
            $field[] = $value;
        }
    }

    update_post_meta($post_id, $field_key, $field);
}
