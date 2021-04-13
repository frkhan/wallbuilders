<?php
    $currentTab = $tabs['overview'];

    if(isset($_GET['tab'])){
        if(in_array($_GET['tab'], array_keys($tabs))){
            $currentTab = $tabs[$_GET['tab']];
        }
    }

    if(AIOGDPR_Settings::get('show_setup') == '1'){
        $currentTab = $tabs['setup'];
    }
?>

<div class="wrap">
    <h2>
        <img class="aiogdpr-admin-logo" src="<?= AIOGDPR::pluginURI('admin/assets/images/logo.png') ?>" alt="All-in-One GDPR Logo">
        All-in-One GDPR

        <?php if(AIOGDPR_Settings::get('privacy_center_page') !== '0'): ?>
            &nbsp; &nbsp;
		    <a onclick="window.open('<?= get_permalink(AIOGDPR_Settings::get('privacy_center_page')) ?>', '_blank').focus();" href="#" class="button button-primary">Privacy Center</a>
	    <?php endif ?>
    </h2>

    <?php if(!isset($currentTab->hideMenu)): ?>
        <h2 class="nav-tab-wrapper" id="aio-gdpr-nav-menu">
            <?php foreach($tabs as $t): ?>
                <?php if( !(isset($t->isHidden) && @$t->isHidden === TRUE) ): ?>
                    <a href="<?= AIOGDPR::adminURL(array('tab' => $t->slug)) ?>" class="nav-tab <?= ($currentTab->slug === $t->slug)? 'nav-tab-active' : '' ?>">
                        <?= $t->title ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </h2>
    <?php else: ?>
        <hr>
    <?php endif; ?>

    <?php $currentTab->adminView(); ?>
</div>