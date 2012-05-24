<?php
/**
 * Displays a DataTable with all of the records
 * 
 * @property $records
 */

$model = $records->model();

$fields = array_keys( $model::schema() );

$key = $model::key();

?>
<table>
	<thead>
		<tr>
<?php foreach( $fields as $field ):?>
			<th>
				<?=$field;?>
			</th>
<?php endforeach;?>
		</tr>
	</thead>
	<tbody>
<?php foreach( $records as $record ):?>
		<tr>
<?php     foreach( $fields as $field ):?>
			<td>
<?php         if( $field == $key ):?>
				<?=$this->html->link( $record->$field, array( 'Admin::entity', 'model_slug' => $this->_request->model_slug, 'id' => $record->$field ) );?>
<?php         else:?>			
				<?=$record->$field;?>
<?php         endif;?>
			</td>
<?php     endforeach;?>
		</tr>
<?php endforeach;?>
	</tbody>
</table>