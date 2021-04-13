<?php

Class AIOGDPR_Service extends AIOGDPR_Model {

	public $postType = 'aiogdpr_service';
	public $attributes = array(
		'name',
		'reason',
		'script',
		'tc_link',
		'default_setting',
		'is_required',
		'type',		// required, optional
	);
	public $virtual = array(
		'slug',
	);


	//======================================================================
	// Virtual
	//======================================================================
	public function _getSlug(){
		return $this->post()->post_name;
	}


	//======================================================================
	// Finders
	//======================================================================
	public function _finderSlug($args){
		 return array(
            'meta_query' => array(
                array(
                    'key'	=> 'slug',
                    'value' => $args['slug']
               	)
            )
        );
	}

	public function _postFinderSlug($results, $args){
		return @$results[0]; 
	}
	
	public function _finderRequired(){
		 return array(
            'meta_query' => array(
                array(
                    'key'	=> 'type',
                    'value' => 'required',
               	)
            )
        );
	}

	public function _postFinderRequired($results, $args){
		
	}
	


	//======================================================================
	// Hooks
	//======================================================================
	public function inserting(){
		$base = sanitize_title($this->name);
		$slug = $base;
		$i = 0;
		while(Self::finder('slug', array('slug' => $slug)) !== NULL){
			$i++;
			$slug = $base .'-'. $i;
		}

		$this->slug = $s;
		$this->title = $this->name; // Never use ->title, this is just to generate a valid post_name
	}


	//======================================================================
	// Methods
	//======================================================================
	public static function permissionsArray(){
		$return = array();
		foreach(Self::all() as $service){
			$return[] = $service->slug;
		}
		return $return;
	}

	public static function requiredSlugs(){
		$return = array();
		foreach(Self::finder('required') as $result){
			$return[] = $result->slug;
		}
		return $return;
	}

}


function registerServiceModel(){
	AIOGDPR_Service::register(array(
		'show_in_nav_menus'   => FALSE,
		'show_in_menu' 		  => FALSE,
		'show_ui' 			  => FALSE,
		'publicly_queryable'  => FALSE,
		'exclude_from_search' => FALSE,
		'public' 			  => FALSE,
	));
}
add_action('init', 'registerServiceModel');