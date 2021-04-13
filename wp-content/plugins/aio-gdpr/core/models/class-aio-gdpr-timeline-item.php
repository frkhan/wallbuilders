<?php

Class AIOGDPR_TimelineItem extends AIOGDPR_Model {

	public $postType = 'aiogdpr_timeline';
	public $attributes = array(
        'request_id',
        'user_id'
	);
	public $virtual = array(

    );

    public function _finderRequest($args){
		 return array(
            'orderby'        => 'post__in',
            'order'          => 'ASC',
			'posts_per_page' => '999',
            'meta_query' => array(
                array(
                    'key'	=> 'request_id',
                    'value' => $args['request_id']
               	)
            )
        );
	}
}

AIOGDPR_TimelineItem::register(array(
    'show_in_nav_menus'   => FALSE,
    'show_in_menu' 		  => FALSE,
    'show_ui' 			  => FALSE,
    'publicly_queryable'  => FALSE,
    'exclude_from_search' => FALSE,
    'public' 			  => FALSE,
));
