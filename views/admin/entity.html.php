<?php
/**
 * Displays an edit/create form for an entity
 * 
 * TODO: iterate over each relationship and build links to each related entity
 * 
 * @property $entity
 * @property $fields
 * @property $related_entities
 */
?>

<?=$this->form->create( $entity );?>

<?php foreach( $fields as $field => $options ):?>

	<?=$this->form->field( $field, $options );?>

<?php endforeach;?>

	<?=$this->form->submit( 'Save' );?>

<?=$this->form->end();?>

<?php foreach( $related_entities as $key => $value ):?>

<?php     if( is_a( $value, '\lithium\data\Collection' ) ):?>
<?php         foreach( $value as $related_entity ):?>
<?php var_dump( $related_entity);?>
<?php         endforeach;?>
<?php     else:?>
<?php var_dump( $value);?>
<?php     endif;?>

<?php endforeach;?>