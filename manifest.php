<?php

$manifest = array (
	 'acceptable_sugar_versions' =>  array (
		  'exact_matches' => array (
		  	//alter scripts/post_install.php for the new version # as well before packaging
			0 => "6.2.3", /** DO NOT EDIT THIS VALUE. ANY CHANGE MADE TO THIS VALUE WILL BREAK YOUR SYSTEM */
		  ),
	  ),
	  'acceptable_sugar_flavors' =>
		  array(
			'CE', // 'PRO','ENT'
		  ),
	  'readme'=>'',
	  'key'=>'',
	  'author' => 'Cooperativa Gcoop LTDA',
	  'description' => 'Make graph based workflows for your modules, and easy extend it with plugins',
	  'icon' => '',
	  'is_uninstallable' => true,
	  'name' => 'Advanced Workflows',
	  'published_date' => '11/17/2011',
	  'type' => 'module',
	  'version' => '1.0.1',
	  'remove_tables' => 'prompt',

	  );
$installdefs = array (
	'id' => 'AdvancedWorkflows',
	'beans' =>
		array (
			array (
			  'module' => 'Workflows',
			  'class' => 'Workflow',
			  'path' => 'modules/Workflows/Workflow.php',
			  'tab' => true,
			),
			array (
			  'module' => 'ActionNodes',
			  'class' => 'ActionNode',
			  'path' => 'modules/ActionNodes/ActionNode.php',
			  'tab' => true,
			),
			array (
			  'module' => 'ChoiceNodes',
			  'class' => 'ChoiceNode',
			  'path' => 'modules/ChoiceNodes/ChoiceNode.php',
			  'tab' => true,
			),
			array (
			  'module' => 'Executions',
			  'class' => 'Execution',
			  'path' => 'modules/Executions/Execution.php',
			  'tab' => true,
			),
			array (
			  'module' => 'gcoop_notificaciones',
			  'class' => 'gcoop_notificaciones',
			  'path' => 'modules/gcoop_notificaciones/gcoop_notificaciones.php',
			  'tab' => true,
			),
		),
	'image_dir' => '<basepath>/icons',
	'copy' =>
		array (
			
            /** Module Files **/
            array (
				'from' => '<basepath>/install_dir/modules/Workflows',
				'to' => 'modules/Workflows',
			),
			array(
				'from' => '<basepath>/include/images/Workflows.gif',
				'to'   => 'themes/default/images/Workflows.gif'
			),
			array (
				'from' => '<basepath>/install_dir/modules/ActionNodes',
				'to' => 'modules/ActionNodes',
			),
			array(
				'from' => '<basepath>/include/images/ActionNodes.gif',
				'to'   => 'themes/default/images/ActionNodes.gif'
			),
			array (
				'from' => '<basepath>/install_dir/modules/ChoiceNodes',
				'to' => 'modules/ChoiceNodes',
			),
			array(
				'from' => '<basepath>/include/images/ChoiceNodes.gif',
				'to'   => 'themes/default/images/ChoiceNodes.gif'
			),

			array (
				'from' => '<basepath>/install_dir/modules/Executions',
				'to' => 'modules/Executions',
			),
			array(
				'from' => '<basepath>/include/images/Executions.gif',
				'to'   => 'themes/default/images/Executions.gif'
			),
			
   			array (
				'from' => '<basepath>/install_dir/modules/gcoop_notificaciones',
				'to' => 'modules/gcoop_notificaciones',
			),
   			
            /** Additional Files **/
            
            array(
                    'from' => '<basepath>/install_dir/custom/include/gcoop_global_funcs.php',
                    'to' => 'custom/include/gcoop_global_funcs.php',
                 ),
            array(
                    'from' => '<basepath>/install_dir/custom/include/javascript/jquery.js',
                    'to' => 'custom/include/javascript/jquery.js',
                 ),
            array(
                    'from' => '<basepath>/install_dir/themes/Sugar5/css/style.css',
                    'to' => 'themes/Sugar5/css/style.css',
                 ),
            array(
                    'from' => '<basepath>/install_dir/themes/Sugar5/images/alert.png',
                    'to' => 'themes/Sugar5/images/alert.png',
                 ),
            array(
                    'from' => '<basepath>/install_dir/themes/Sugar5/images/error.png',
                    'to' => 'themes/Sugar5/images/error.png',
                 ),
            array(
                    'from' => '<basepath>/install_dir/themes/Sugar5/images/info.png',
                    'to' => 'themes/Sugar5/images/info.png',
                 ),
            array(
                    'from' => '<basepath>/install_dir/themes/Sugar5/images/success.png',
                    'to' => 'themes/Sugar5/images/success.png',
                 ),
            array(
                    'from' => '<basepath>/install_dir/themes/Sugar5/images/working.gif',
                    'to' => 'themes/Sugar5/images/working.gif',
                 ),
            /** NOT UPGRADE SAFE CODE - try to make this as upgrade safe as possible */
            array(
                    'from' => '<basepath>/install_dir/notupgradesafe/themes/Sugar5/tpls/header.tpl',
                    'to' => 'themes/Sugar5/tpls/header.tpl',
                 ),
            array(
                    'from' => '<basepath>/install_dir/notupgradesafe/data/SugarBean.php',
                    'to' => 'data/SugarBean.php',
                 ),
            array(
                    'from' => '<basepath>/install_dir/notupgradesafe/include/EditView/EditView2.php',
                    'to' => 'include/EditView/EditView2.php',
                 ),
            array(
                    'from' => '<basepath>/install_dir/notupgradesafe/include/MVC/View/SugarView.php',
                    'to' => 'include/MVC/View/SugarView.php',
                 ),
            array(
                    'from' => '<basepath>/install_dir/notupgradesafe/include/MVC/Controller/SugarController.php',
                    'to' => 'include/MVC/Controller/SugarController.php',
                 ),
            array(
                    'from' => '<basepath>/install_dir/notupgradesafe/include/SearchForm/SearchForm2.php',
                    'to' => 'include/SearchForm/SearchForm2.php',
                 ),
            array(
                    'from' => '<basepath>/install_dir/notupgradesafe/include/entryPoint.php',
                    'to' => 'include/entryPoint.php',
                 ),
		),
	'layoutdefs' =>
		array (
		),
	'relationships' =>
		array (
		 ),

	'language' =>
		array (
		/** ENGLISH en_us */
			array (
				'from' => '<basepath>/install_dir/language/application/en_us.lang.php',
				'to_module' => 'application',
				'language' => 'en_us',
			),
		),

	'administration' =>
		array(
		),
	'menu'=> array(
	),
	'logic_hooks' =>
		array(
		),
);

?>
