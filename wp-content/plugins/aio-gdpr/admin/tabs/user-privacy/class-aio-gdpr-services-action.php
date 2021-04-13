<?php

Class AIOGDPR_ServicesAction extends AIOGDPR_AjaxAction{

    public $title = 'Remote JavaScript';
    public $slug  = 'user-privacy'; 
    public function adminView(){
        include plugin_dir_path(__FILE__) .'page.php';
    }

    
    protected $action = 'admin-services';

    protected function run(){
        $this->requireAdmin();
        
        // User permissons page
        if($this->has('user_permissions_page')){
            AIOGDPR_Settings::set('user_permissions_page', $this->get('user_permissions_page'));
        }

        // Update Services
        if($this->has('services')){

	        foreach($this->get('services') as $ID => $service){
                $s = AIOGDPR_Service::find($ID);
				if(!is_null($s)){
		            $s->name 		    = $service['name'];
		            $s->reason		    = $service['reason'];
		            $s->script		    = $service['script'];
		            $s->tc_link		    = $service['tc_link'];
		            $s->type		    = $service['type'];
                    $s->is_required		= (@$service['is_required'] !== NULL)? '1' : '0';
                    $s->default_setting	= (@$service['default_setting'] !== NULL)? '1' : '0';
		            $s->save();
		        }
	        }
	    }


	    $this->returnBack();
    }
}

AIOGDPR_ServicesAction::listen();