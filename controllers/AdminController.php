<?php

namespace li3_admin\controllers;

use lithium\util\Inflector;

class AdminController extends \lithium\action\Controller
{
    public function _init()
    {
        parent::_init();

        $this->_render['paths']['template'] = LI3_ADMIN_PATH . '/views/{:controller}/{:template}.{:type}.php';
		$this->_render['paths']['element'] = LI3_ADMIN_PATH . '/views/elements/{:template}.html.php';

		//use the application's default layout
		$this->_render['paths']['layout'] = LITHIUM_APP_PATH . '/views/layouts/default.html.php';
    }

    public function index()
    {
        //find EVERY model and build an array of class => model_slug

        $models = array();

        foreach( \lithium\core\Libraries::locate('models') as $model )
            $models[] = array(
                'model_slug' => str_replace('\\', '-', $model),
                'model' => $model,
                'record_count' => $model::count(),
            );

        return compact( 'models' );
    }

    public function records()
    {
        $model = $this->_getModel();

        $records = $model::all();

        $fields = array_keys( $model::schema() );

        $key = $model::key();

        $foreign_keys = $this->_getForeignKeys();

        return compact( 'records', 'fields', 'key', 'foreign_keys' );
    }

    public function entity()
    {
        $model = $this->_getModel();

        $entity = isset( $this->request->id )?
                    $model::first( $this->_getFindOptions() ):
                    $model::create();

        $fields = array();

        foreach( $model::schema() as $field => $schema )
        {
            $options = array();

            $fields[$field] = $options;
        }

        $related_entities = $this->_getRelatedEntities( $entity );

        return compact( 'entity', 'fields', 'related_entities' );
    }

    protected function _getModel()
    {
        if( !isset( $this->_model ) )
            $this->_model = str_replace('-', '\\',  $this->request->model_slug );

        return $this->_model;
    }

    protected function _getFindOptions()
    {
        $id = $this->request->id;

        $model = $this->_getModel();

        $key = $model::key();

        $conditions = array(
            $key => $id,
        );

        return compact( 'conditions' );
    }

    protected function _getForeignKeys()
    {
        $model = $this->_getModel();

        $foreign_keys = array();

        foreach( $model::relations() as $name => $relationship )
        {
            if( $relationship->data('type') == 'belongsTo' )
            {
                $field = key( $relationship->data('key') );

                $foreign_keys[$field] = str_replace('\\', '-', $relationship->data('to') );
            }
        }

        return $foreign_keys;
    }

    protected function _getRelatedEntities( $entity )
    {
        $model = $entity->model();

        $related_entities = array();

        $with = array();

        $properties = array();

        foreach( $model::relations() as $relationship )
        {
            $name = $relationship->data('name');

            $properties[] = Inflector::tableize( $name );

            $with[] = $name;
        }

        $conditions = $entity->key();

        //$entity = $model::first( compact( 'conditions', 'with' ) );

        foreach( $properties as $property )
        {
            $related_entities[$property] = $entity->{$property};
        }

        return $related_entities;
    }
}
