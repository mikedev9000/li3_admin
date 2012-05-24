<?php

use lithium\net\http\Router;

Router::connect( "/admin", 							"Admin::index" );
Router::connect( "/admin/{:model_slug}", 			"Admin::records" );
Router::connect( "/admin/{:model_slug}/create", 	"Admin::entity" );
Router::connect( "/admin/{:model_slug}/{:id}", 		"Admin::entity" );