<?php
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2011 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

/*
 * Created on May 30, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once('include/tabs.php');
require_once('include/ListView/ListViewSmarty.php');

require_once('include/TemplateHandler/TemplateHandler.php');
require_once('include/EditView/EditView2.php');


 class SearchForm extends EditView{
 	var $seed = null;
 	var $module = '';
 	var $action = 'index';
 	var $searchdefs = array();
 	var $listViewDefs = array();
 	var $lv;
 	var $th;
    var $tpl;
    var $view = 'SearchForm';
    var $displayView = 'basic_search';
    var $formData;
    var $fieldDefs;
    var $customFieldDefs;
    var $tabs;
    var $parsedView = 'basic';
    //may remove
    var $searchFields;
    var $displaySavedSearch = true;
    //show the advanced tab
    var $showAdvanced = true;
    //show the basic tab
    var $showBasic = true;
    //array of custom tab to show declare in searchdefs (no custom tab if false)
    var $showCustom = false;
    // nb of tab to show
    var $nbTabs = 0;
    // hide saved searches drop and down near the search button
    var $showSavedSearchesOptions = true;

    var $displayType = 'searchView';

 	function SearchForm($seed, $module, $action = 'index'){
 		$this->th = new TemplateHandler();
 		$this->th->loadSmarty();
		$this->seed = $seed;
		$this->module = $module;
		$this->action = $action;
        $this->tabs = array(array('title'  => $GLOBALS['app_strings']['LNK_BASIC_SEARCH'],
                            'link'   => $module . '|basic_search',
                            'key'    => $module . '|basic_search',
                            'name'   => 'basic',
                            'displayDiv'   => ''),
                      array('title'  => $GLOBALS['app_strings']['LNK_ADVANCED_SEARCH'],
                            'link'   => $module . '|advanced_search',
                            'key'    => $module . '|advanced_search',
                            'name'   => 'advanced',
                            'displayDiv'   => 'display:none'),
                       );
        $this->searchColumns = array () ;
 	}

 	function setup($searchdefs, $searchFields = array(), $tpl, $displayView = 'basic_search', $listViewDefs = array()){
		$this->searchdefs =  $searchdefs[$this->module];
 		$this->tpl = $tpl;
 		//used by advanced search
 		$this->listViewDefs = $listViewDefs;
 		$this->displayView = $displayView;
 		$this->view = $this->view.'_'.$displayView;
 		$tokens = explode('_', $this->displayView);
 		$this->parsedView = $tokens[0];
 		if($this->displayView != 'saved_views'){
 			$this->_build_field_defs();
 		}

        $this->searchFields = $searchFields[$this->module];

        // Setub the tab array
        $this->tabs = array();
        if($this->showBasic){
            $this->nbTabs++;
            $this->tabs[]=array('title'  => $GLOBALS['app_strings']['LNK_BASIC_SEARCH'],
                                'link'   => $this->module . '|basic_search',
                                'key'    => $this->module . '|basic_search',
                                'name'   => 'basic',
                                'displayDiv' => '');
        }
        if($this->showAdvanced){
            $this->nbTabs++;
            $this->tabs[]=array('title'  => $GLOBALS['app_strings']['LNK_ADVANCED_SEARCH'],
                                'link'   => $this->module . '|advanced_search',
                                'key'    => $this->module . '|advanced_search',
                                'name'   => 'advanced',
                                'displayDiv' => 'display:none');
        }
        if($this->showCustom){
            foreach($this->showCustom as $v){
                $this->nbTabs++;
                $this->tabs[]=array('title'  => $GLOBALS['app_strings']["LNK_" . strtoupper($v)],
                    'link'   => $this->module . '|' . $v,
                    'key'    => $this->module . '|' . $v,
                    'name'   => str_replace('_search','',$v),
                    'displayDiv' => 'display:none',);
            }
        }
 	}

 	function display($header = true){
    	global $theme, $timedate;
 		$header_txt = '';
 		$footer_txt = '';
 		$return_txt = '';
		$this->th->ss->assign('module', $this->module);
		$this->th->ss->assign('action', $this->action);
		$this->th->ss->assign('displayView', $this->displayView);
		$this->th->ss->assign('APP', $GLOBALS['app_strings']);
		//Show the tabs only if there is more than one
		if($this->nbTabs>1){
		    $this->th->ss->assign('TABS', $this->_displayTabs($this->module . '|' . $this->displayView));
		}
		$this->th->ss->assign('searchTableColumnCount',
		    ((isset($this->searchdefs['templateMeta']['maxColumns']) ? $this->searchdefs['templateMeta']['maxColumns'] : 2) * 2 ) - 1);
		$this->th->ss->assign('fields', $this->fieldDefs);
		$this->th->ss->assign('customFields', $this->customFieldDefs);
		$this->th->ss->assign('formData', $this->formData);
        $time_format = $timedate->get_user_time_format();
        $this->th->ss->assign('TIME_FORMAT', $time_format);
        $this->th->ss->assign('USER_DATEFORMAT', $timedate->get_user_date_format());

        $date_format = $timedate->get_cal_date_format();
        $time_separator = ":";
        if(preg_match('/\d+([^\d])\d+([^\d]*)/s', $time_format, $match)) {
           $time_separator = $match[1];
        }
        // Create Smarty variables for the Calendar picker widget
        $t23 = strpos($time_format, '23') !== false ? '%H' : '%I';
        if(!isset($match[2]) || $match[2] == '') {
          $this->th->ss->assign('CALENDAR_FORMAT', $date_format . ' ' . $t23 . $time_separator . "%M");
        } else {
          $pm = $match[2] == "pm" ? "%P" : "%p";
          $this->th->ss->assign('CALENDAR_FORMAT', $date_format . ' ' . $t23 . $time_separator . "%M" . $pm);
        }
        $this->th->ss->assign('TIME_SEPARATOR', $time_separator);

        //Show and hide the good tab form
        foreach($this->tabs as $tabkey=>$viewtab){
            $viewName=str_replace(array($this->module . '|','_search'),'',$viewtab['key']);
            if(strpos($this->view,$viewName)!==false){
                $this->tabs[$tabkey]['displayDiv']='';
                //if this is advanced tab, use form with saved search sub form built in
                if($viewName=='advanced'){
                    $this->tpl = 'include/SearchForm/tpls/SearchFormGenericAdvanced.tpl';
                    if ($this->action =='ListView') {
                        $this->th->ss->assign('DISPLAY_SEARCH_HELP', true);
                    }
                    $this->th->ss->assign('DISPLAY_SAVED_SEARCH', $this->displaySavedSearch);
                    $this->th->ss->assign('SAVED_SEARCH', $this->displaySavedSearch());
                    //this determines whether the saved search subform should be rendered open or not
                    if(isset($_REQUEST['showSSDIV']) && $_REQUEST['showSSDIV']=='yes'){
                        $this->th->ss->assign('SHOWSSDIV', 'yes');
                        $this->th->ss->assign('DISPLAYSS', '');
                    }else{
                        $this->th->ss->assign('SHOWSSDIV', 'no');
                        $this->th->ss->assign('DISPLAYSS', 'display:none');
                    }
                }
            }else{
                $this->tabs[$tabkey]['displayDiv']='display:none';
            }

        }

        $this->th->ss->assign('TAB_ARRAY', $this->tabs);

        $totalWidth = 0;
        if ( isset($this->searchdefs['templateMeta']['widths'])
                && isset($this->searchdefs['templateMeta']['maxColumns'])) {
            $totalWidth = ( $this->searchdefs['templateMeta']['widths']['label'] +
                                $this->searchdefs['templateMeta']['widths']['field'] ) *
                                $this->searchdefs['templateMeta']['maxColumns'];
            // redo the widths in case they are too big
            if ( $totalWidth > 100 ) {
                $resize = 100 / $totalWidth;
                $this->searchdefs['templateMeta']['widths']['label'] =
                    $this->searchdefs['templateMeta']['widths']['label'] * $resize;
                $this->searchdefs['templateMeta']['widths']['field'] =
                    $this->searchdefs['templateMeta']['widths']['field'] * $resize;
            }
        }
        $this->th->ss->assign('templateMeta', $this->searchdefs['templateMeta']);
        $this->th->ss->assign('HAS_ADVANCED_SEARCH', !empty($this->searchdefs['layout']['advanced_search']));
        $this->th->ss->assign('displayType', $this->displayType);
        // return the form of the shown tab only
        if($this->showSavedSearchesOptions){
            $this->th->ss->assign('SAVED_SEARCHES_OPTIONS', $this->displaySavedSearchSelect());
        }
        if ($this->module == 'Documents'){
            $this->th->ss->assign('DOCUMENTS_MODULE', true);
        }
        $return_txt = $this->th->displayTemplate($this->seed->module_dir, 'SearchForm_'.$this->parsedView, $this->tpl);
        if($header){
			$this->th->ss->assign('return_txt', $return_txt);
			$header_txt = $this->th->displayTemplate($this->seed->module_dir, 'SearchFormHeader', 'include/SearchForm/tpls/header.tpl');
            //pass in info to render the select dropdown below the form
            $footer_txt = $this->th->displayTemplate($this->seed->module_dir, 'SearchFormFooter', 'include/SearchForm/tpls/footer.tpl');
			$return_txt = $header_txt.$footer_txt;
		}
		return $return_txt;
 	}

  function displaySavedSearch(){
        $savedSearch = new SavedSearch($this->listViewDefs[$this->module], $this->lv->data['pageData']['ordering']['orderBy'], $this->lv->data['pageData']['ordering']['sortOrder']);
        return $savedSearch->getForm($this->module, false);
    }


  function displaySavedSearchSelect(){
        $savedSearch = new SavedSearch($this->listViewDefs[$this->module], $this->lv->data['pageData']['ordering']['orderBy'], $this->lv->data['pageData']['ordering']['sortOrder']);
        return $savedSearch->getSelect($this->module);
    }



 	/**
     * displays the tabs (top of the search form)
     *
     * @param string $currentKey key in $this->tabs to show as the current tab
     *
     * @return string html
     */
    function _displayTabs($currentKey)
    {
        if(isset($_REQUEST['saved_search_select']) && $_REQUEST['saved_search_select']!='_none') {
            $saved_search=loadBean('SavedSearch');
            $saved_search->retrieveSavedSearch($_REQUEST['saved_search_select']);
        }

        $str = '<script>';
        if(!empty($_REQUEST['displayColumns']))
            $str .= 'SUGAR.savedViews.displayColumns = "' . $_REQUEST['displayColumns'] . '";';
        elseif(isset($saved_search->contents['displayColumns']) && !empty($saved_search->contents['displayColumns']))
            $str .= 'SUGAR.savedViews.displayColumns = "' . $saved_search->contents['displayColumns'] . '";';
        if(!empty($_REQUEST['hideTabs']))
            $str .= 'SUGAR.savedViews.hideTabs = "' . $_REQUEST['hideTabs'] . '";';
        elseif(isset($saved_search->contents['hideTabs']) && !empty($saved_search->contents['hideTabs']))
            $str .= 'SUGAR.savedViews.hideTabs = "' . $saved_search->contents['hideTabs'] . '";';
        if(!empty($_REQUEST['orderBy']))
            $str .= 'SUGAR.savedViews.selectedOrderBy = "' . $_REQUEST['orderBy'] . '";';
        elseif(isset($saved_search->contents['orderBy']) && !empty($saved_search->contents['orderBy']))
            $str .= 'SUGAR.savedViews.selectedOrderBy = "' . $saved_search->contents['orderBy'] . '";';
        if(!empty($_REQUEST['sortOrder']))
            $str .= 'SUGAR.savedViews.selectedSortOrder = "' . $_REQUEST['sortOrder'] . '";';
        elseif(isset($saved_search->contents['sortOrder']) && !empty($saved_search->contents['sortOrder']))
            $str .= 'SUGAR.savedViews.selectedSortOrder = "' . $saved_search->contents['sortOrder'] . '";';

        $str .= '</script>';

        return $str;
    }

 	/*
	 * Generate the data
	 */
	function _build_field_defs(){
		$this->formData = array();
		$this->fieldDefs = array();
		foreach($this->searchdefs['layout'][$this->displayView] as $data){
			if(is_array($data)){
				//Fields may be listed but disabled so that when they are enabled, they have the correct custom display data.
				if (isset($data['enabled']) && $data['enabled'] == false)
					continue;
				$data['name'] = $data['name'].'_'.$this->parsedView;
				$this->formData[] = array('field' => $data);
				$this->fieldDefs[$data['name']]= $data;
			} else {
				$this->formData[] = array('field' => array('name'=>$data.'_'.$this->parsedView));
			}
		}

		if($this->seed){
			$this->seed->fill_in_additional_detail_fields();
			// hack to make the employee status field for the Users/Employees module display correctly
			if($this->seed->object_name == 'Employee' || $this->seed->object_name == 'User'){
                $this->seed->field_defs['employee_status']['type'] = 'enum';
                $this->seed->field_defs['employee_status']['massupdate'] = true;
                $this->seed->field_defs['employee_status']['options'] = 'employee_status_dom';
                unset($this->seed->field_defs['employee_status']['function']);
            }

	        foreach($this->seed->toArray() as $name => $value) {
	            if(!empty($this->fieldDefs[$name.'_'.$this->parsedView]))
	            	$this->fieldDefs[$name.'_'.$this->parsedView] = array_merge($this->seed->field_defs[$name], $this->fieldDefs[$name.'_'.$this->parsedView]);
	            else{
	            	$this->fieldDefs[$name.'_'.$this->parsedView] = $this->seed->field_defs[$name];
	            	$this->fieldDefs[$name.'_'.$this->parsedView]['name'] = $this->fieldDefs[$name.'_'.$this->parsedView]['name'].'_'.$this->parsedView;
	            }

	            if(isset($this->fieldDefs[$name.'_'.$this->parsedView]['type']) && $this->fieldDefs[$name.'_'.$this->parsedView]['type'] == 'relate') {
	                if(isset($this->fieldDefs[$name.'_'.$this->parsedView]['id_name'])) {
	                   $this->fieldDefs[$name.'_'.$this->parsedView]['id_name'] .= '_'.$this->parsedView;
	                }
	            }

	            if(isset($this->fieldDefs[$name.'_'.$this->parsedView]['options']) && isset($GLOBALS['app_list_strings'][$this->fieldDefs[$name.'_'.$this->parsedView]['options']])) {
	                $this->fieldDefs[$name.'_'.$this->parsedView]['options'] = $GLOBALS['app_list_strings'][$this->fieldDefs[$name.'_'.$this->parsedView]['options']]; // fill in enums
	            }

	            if(isset($this->fieldDefs[$name.'_'.$this->parsedView]['function'])) {

	            	$this->fieldDefs[$name.'_'.$this->parsedView]['type']='multienum';

	       	 		if(is_array($this->fieldDefs[$name.'_'.$this->parsedView]['function'])) {
	       	 		   $this->fieldDefs[$name.'_'.$this->parsedView]['function']['preserveFunctionValue']=true;
	       	 		}

	       	 		$function = $this->fieldDefs[$name.'_'.$this->parsedView]['function'];

	       			if(is_array($function) && isset($function['name'])){
	       				$function_name = $this->fieldDefs[$name.'_'.$this->parsedView]['function']['name'];
	       			}else{
	       				$function_name = $this->fieldDefs[$name.'_'.$this->parsedView]['function'];
	       			}

					if(!empty($this->fieldDefs[$name.'_'.$this->parsedView]['function']['returns']) && $this->fieldDefs[$name.'_'.$this->parsedView]['function']['returns'] == 'html'){
						if(!empty($this->fieldDefs[$name.'_'.$this->parsedView]['function']['include'])){
								require_once($this->fieldDefs[$name.'_'.$this->parsedView]['function']['include']);
						}
						$value = $function_name($this->seed, $name, $value, $this->view);
						$this->fieldDefs[$name.'_'.$this->parsedView]['value'] = $value;
					}else{
						if(!empty($this->fieldDefs[$name.'_'.$this->parsedView]['function']['include'])){
								require_once($this->fieldDefs[$name.'_'.$this->parsedView]['function']['include']);
						}
						if(!isset($function['params']) || !is_array($function['params'])) {
							$this->fieldDefs[$name.'_'.$this->parsedView]['options'] = $function_name($this->seed, $name, $value, $this->view);
						} else {
							$this->fieldDefs[$name.'_'.$this->parsedView]['options'] = call_user_func_array($function_name, $function['params']);
						}
					}
	       	 	}
	       	 	if(isset($this->fieldDefs[$name]['type']) && $this->fieldDefs[$name.'_'.$this->parsedView]['type'] == 'function' && isset($this->fieldDefs[$name.'_'.$this->parsedView]['function_name'])){
	       	 		$value = $this->callFunction($this->fieldDefs[$name.'_'.$this->parsedView]);
	       	 		$this->fieldDefs[$name.'_'.$this->parsedView]['value'] = $value;
	       	 	}

	            $this->fieldDefs[$name]['value'] = $value;


	            if((!empty($_REQUEST[$name.'_'.$this->parsedView]) || (isset($_REQUEST[$name.'_'.$this->parsedView]) && $_REQUEST[$name.'_'.$this->parsedView] == '0'))
                && empty($this->fieldDefs[$name.'_'.$this->parsedView]['function']['preserveFunctionValue'])) {
	            	$value = $_REQUEST[$name.'_'.$this->parsedView];
	            	$this->fieldDefs[$name.'_'.$this->parsedView]['value'] = $value;
	            }

	        } //foreach


		}

	}

	    /**
     * Populate the searchFields from an array
     *
     * @param array $array array to search through
     * @param string $switchVar variable to use in switch statement
     * @param bool $addAllBeanFields true to process at all bean fields
     */
    function populateFromArray(&$array, $switchVar = null, $addAllBeanFields = true) {

       if((!empty($array['searchFormTab']) || !empty($switchVar)) && !empty($this->searchFields)) {
			$arrayKeys = array_keys($array);
            $searchFieldsKeys = array_keys($this->searchFields);
            if(empty($switchVar)) $switchVar = $array['searchFormTab'];
            //name of  the search tab
            $SearchName=str_replace('_search', '', $switchVar);
            if($switchVar=='saved_views'){
                foreach($this->searchFields as $name => $params) {
                    foreach($this->tabs as $tabName){
                        if(!empty($array[$name . '_' . $tabName['name']])) {
                             $this->searchFields[$name]['value'] = $array[$name . '_' . $tabName['name']];
                             if(empty($this->fieldDefs[$name . '_' . $tabName['name']]['value'])) $this->fieldDefs[$name . '_' . $tabName['name']]['value'] = $array[$name . '_' . $tabName['name']];
                        }
                    }
                }
                if($addAllBeanFields) {
                    foreach($this->seed->field_name_map as $key => $params) {
                        if(!in_array($key, $searchFieldsKeys)) {
                            foreach($this->tabs->name as $tabName){
                                if(in_array($key . '_' . $tabName['name'], $arrayKeys) ) {
									$this->searchFields[$key] = array('query_type' => 'default',
                                                                      'value'      => $array[$key . '_' . $tabName['name']]);
                                }
                            }
                        }
                    }
                }

            }else{

            	$fromMergeRecords = isset($array['merge_module']);

                foreach($this->searchFields as $name => $params) {
					$long_name = $name.'_'.$SearchName;           
					/*nsingh 21648: Add additional check for bool values=0. empty() considers 0 to be empty Only repopulates if value is 0 or 1:( */
                	if(isset($array[$long_name]) && !$this->isEmptyDropdownField($long_name, $array[$long_name]) && ( $array[$long_name] !== '' || (isset($this->fieldDefs[$long_name]['type']) && $this->fieldDefs[$long_name]['type'] == 'bool'&& ($array[$long_name]=='0' || $array[$long_name]=='1'))))
					{ 				
                        $this->searchFields[$name]['value'] = $array[$long_name];
                        if(empty($this->fieldDefs[$long_name]['value'])) {
                        	$this->fieldDefs[$long_name]['value'] = $array[$long_name];
                        }
                    }else if(!empty($array[$name]) && !$fromMergeRecords && !$this->isEmptyDropdownField($name, $array[$name])) { //basic        	
                    	$this->searchFields[$name]['value'] = $array[$name];
                        if(empty($this->fieldDefs[$long_name]['value'])) {
                        	$this->fieldDefs[$long_name]['value'] = $array[$name];
                        }
                    }
                    
                    if(!empty($params['enable_range_search']) && isset($this->searchFields[$name]['value']))
					{
						if(preg_match('/^range_(.*?)$/', $long_name, $match) && isset($array[$match[1].'_range_choice']))
						{
							$this->searchFields[$name]['operator'] = $array[$match[1].'_range_choice'];
						}
					}

					if(!empty($params['is_date_field']) && isset($this->searchFields[$name]['value']))
					{
						global $timedate;
                                                // FG - bug 45287 - to db conversion is ok, but don't adjust timezone (not now), otherwise you'll jump to the day before (if at GMT-xx)
						$date_value = $timedate->to_db_date($this->searchFields[$name]['value'], false);
						$this->searchFields[$name]['value'] = $date_value == '' ? $this->searchFields[$name]['value'] : $date_value;
					}                    
                }

                if((empty($array['massupdate']) || $array['massupdate'] == 'false') && $addAllBeanFields) {
                    foreach($this->seed->field_name_map as $key => $params) {
                    	if($key != 'assigned_user_name' && $key != 'modified_by_name')
                    	{
                    		$long_name = $key.'_'.$SearchName;
                    		
	                    	if(in_array($key.'_'.$SearchName, $arrayKeys) && !in_array($key, $searchFieldsKeys) && !$this->isEmptyDropdownField($long_name, $array[$long_name])) 
	                    	{  	                    		
	                    		
	                        	$this->searchFields[$key] = array('query_type' => 'default', 'value' => $array[$long_name]);
	                        	
                                if (!empty($params['type']) && $params['type'] == 'parent'
                                    && !empty($params['type_name']) && !empty($this->searchFields[$key]['value']))
                                {
                                	    require_once('include/SugarFields/SugarFieldHandler.php');
										$sfh = new SugarFieldHandler();
                   						$sf = $sfh->getSugarField('Parent');
                                	
                                        $this->searchFields[$params['type_name']] = array('query_type' => 'default',
                                                                                          'value'      => $sf->getSearchInput($params['type_name'], $array));
                                }
                                
                                if(empty($this->fieldDefs[$long_name]['value'])) {
                                    $this->fieldDefs[$long_name]['value'] =  $array[$long_name];
                                }
                            }
                        }
                    }
                }

            }
        }


       if ( is_array($this->searchFields) ) {
           foreach ( $this->searchFields as $fieldName => $field ) {
               if ( !empty($field['value']) && is_string($field['value']) ) {
                   $this->searchFields[$fieldName]['value'] = trim($field['value']);
               }
           }
       } 

    }

    /**
     * Populate the searchFields from $_REQUEST
     *
     * @param string $switchVar variable to use in switch statement
     * @param bool $addAllBeanFields true to process at all bean fields
     */
    function populateFromRequest($switchVar = null, $addAllBeanFields = true) {
    	$this->populateFromArray($_REQUEST, $switchVar, $addAllBeanFields);
    }

	function generateSearchWhere($add_custom_fields = false, $module='') {
        global $timedate;

        $this->searchColumns = array () ;
        $values = $this->searchFields;

        $where_clauses = array();
        $like_char = '%';
        $table_name = $this->seed->object_name;
        $this->seed->fill_in_additional_detail_fields();

        //rrs check for team_id
        
        foreach($this->searchFields as $field=>$parms) {
			$customField = false;
            // Jenny - Bug 7462: We need a type check here to avoid database errors
            // when searching for numeric fields. This is a temporary fix until we have
            // a generic search form validation mechanism.
            $type = (!empty($this->seed->field_name_map[$field]['type']))?$this->seed->field_name_map[$field]['type']:'';

			if(!empty($parms['enable_range_search']) && empty($type))
			{				
				if(preg_match('/^start_range_(.*?)$/', $field, $match))
				{
					$real_field = $match[1];
					$start_field = 'start_range_' . $real_field;
					$end_field = 'end_range_' . $real_field;

					if(isset($this->searchFields[$start_field]['value']) && isset($this->searchFields[$end_field]['value']))
					{								
						$this->searchFields[$real_field]['value'] = $this->searchFields[$start_field]['value'] . '<>' . $this->searchFields[$end_field]['value'];
						$this->searchFields[$real_field]['operator'] = 'between';
						$parms['value'] = $this->searchFields[$real_field]['value'];
						$parms['operator'] = 'between';

					        $field_type = isset($this->seed->field_name_map[$real_field]['type']) ? $this->seed->field_name_map[$real_field]['type'] : '';					
					        if($field_type == 'datetimecombo' || $field_type == 'datetime')
					        {
					   	    $type = $field_type;
					        }

						$field = $real_field;
						unset($this->searchFields[$end_field]['value']);
					}
				} else if (preg_match('/^range_(.*?)$/', $field, $match) && isset($this->searchFields[$field]['value'])) {
					$real_field = $match[1];
					
					//Special case for datetime and datetimecombo fields.  By setting the type here we allow an actual between search
					if($parms['operator'] == '=')
					{
					   $field_type = isset($this->seed->field_name_map[$real_field]['type']) ? $this->seed->field_name_map[$real_field]['type'] : '';					
					   if($field_type == 'datetimecombo' || $field_type == 'datetime')
					   {
					   	  $type = $field_type;
					   }
					}
					
					$this->searchFields[$real_field]['value'] = $this->searchFields[$field]['value'];
					$this->searchFields[$real_field]['operator'] = $this->searchFields[$field]['operator'];						
					$params['value'] = $this->searchFields[$field]['value'];
					$params['operator'] = $this->searchFields[$field]['operator'];
					unset($this->searchFields[$field]['value']);
					$field = $real_field;
				} else {
        		    //Skip this range search field, it is the end field THIS IS NEEDED or the end range date will break the query
	            	continue;
				}
			}
            
        	if(!empty($this->seed->field_name_map[$field]['source'])
        		&& ($this->seed->field_name_map[$field]['source'] == 'custom_fields' ||
        			//Non-db custom fields, such as custom relates
        			($this->seed->field_name_map[$field]['source'] == 'non-db'
        			&& (!empty($this->seed->field_name_map[$field]['custom_module']) ||
        				 isset($this->seed->field_name_map[$field]['ext2']))))){
                $customField = true;
              }

            if ($type == 'int') {
                if (!empty($parms['value'])) {
                    $tempVal = explode(',', $parms['value']);
                    $newVal = '';
                    foreach($tempVal as $key => $val) {
                        if (!empty($newVal))
                            $newVal .= ',';
                        if(!empty($val) && !(is_numeric($val)))
                            $newVal .= -1;
                        else
                            $newVal .= $val;
                    }
                    $parms['value'] = $newVal;
                }
            }

            //Navjeet- 6/24/08 checkboxes have been changed to dropdowns, so we can query unchecked checkboxes! Bug: 21648.

            // elseif($type == 'bool' && empty($parms['value']) && preg_match("/current_user_only/", string subject, array subpatterns, int flags, [int offset])) {
            //     continue;
            // }
            //
            elseif($type == 'html' && $customField) {
                continue;
            }

            
            if(isset($parms['value']) && $parms['value'] != "") {        	
            	
                $operator = 'like';
                if(!empty($parms['operator'])) {
                    $operator = $parms['operator'];
                }

                if(is_array($parms['value'])) {
                    $field_value = '';

                    // always construct the where clause for multiselects using the 'like' form to handle combinations of multiple $vals and multiple $parms
                     if(/*$GLOBALS['db']->dbType != 'mysql' &&*/ !empty($this->seed->field_name_map[$field]['isMultiSelect']) && $this->seed->field_name_map[$field]['isMultiSelect']) {
                        // construct the query for multenums
                        // use the 'like' query for all mssql and oracle examples as both custom and OOB multienums are implemented with types that cannot be used with an 'in'
                        $operator = 'custom_enum';
                        $table_name = $this->seed->table_name ;
                        if ($customField)
                            $table_name .= "_cstm" ;
                        $db_field = $table_name . "." . $field;

	                    foreach($parms['value'] as $key => $val) {
                            if($val != ' ' and $val != '') {
	                               $qVal = $GLOBALS['db']->quote($val);
	                               if (!empty($field_value)) {
	                                   $field_value .= ' or ';
	                               }
	                               $field_value .= "$db_field like '%^$qVal^%'";
	                        } else {
	                        	$field_value .= '('.$db_field . ' IS NULL or '.$db_field."='^^' or ".$db_field."='')";
	                        }
	                    }

                    } else {
                        $operator = $operator != 'subquery' ? 'in' : $operator;
	                    foreach($parms['value'] as $key => $val) {
	                        if($val != ' ' and $val != '') {
	                            if (!empty($field_value)) {
	                                $field_value .= ',';
	                            }
	                            $field_value .= "'" . $GLOBALS['db']->quote($val) . "'";
	                        }
                                // Bug 41209: adding a new operator "isnull" here
                                // to handle the case when blank is selected from dropdown.
                                // In that case, $val is empty.
                                // When $val is empty, we need to use "IS NULL",
                                // as "in (null)" won't work
                                else if ($operator=='in') {
                                    $operator = 'isnull';
                                }
	                    }
                    }

                } else {
                    $field_value = $GLOBALS['db']->quote($parms['value']);
                }

                //set db_fields array.
                if(!isset($parms['db_field'])) {
                    $parms['db_field'] = array($field);
                }

                //This if-else block handles the shortcut checkbox selections for "My Items" and "Closed Only"
                if(!empty($parms['my_items'])) {
                    if( $parms['value'] == false ) { 
						continue;
					} else { 
						//my items is checked.
						global $current_user;
	                    $field_value = $GLOBALS['db']->quote($current_user->id);
						$operator = '=' ;
					}
                } else if(!empty($parms['closed_values']) && is_array($parms['closed_values'])) {
                    if( $parms['value'] == false ) { 
						continue;
					} else { 
						$field_value = '';
						foreach($parms['closed_values'] as $closed_value)
						{
							$field_value .= ",'" . $GLOBALS['db']->quote($closed_value) . "'";
						}
	                    $field_value = substr($field_value, 1);
					}                	
                }                     
                
                $where = '';
                $itr = 0;

                if($field_value != '' || $operator=='isnull') {

                    $this->searchColumns [ strtoupper($field) ] = $field ;

                    foreach ($parms['db_field'] as $db_field) {
						if (strstr($db_field, '.') === false) {
                        	//Try to get the table for relate fields from link defs
                        	if ($type == 'relate' && !empty($this->seed->field_name_map[$field]['link'])
                        		&& !empty($this->seed->field_name_map[$field]['rname'])) {
                        			$link = $this->seed->field_name_map[$field]['link'];
                        			$relname = $link['relationship'];
                        			if (($this->seed->load_relationship($link))){
										//Martin fix #27494
										$db_field = $this->seed->field_name_map[$field]['name'];
                        			} else {
                        				//Best Guess for table name
                        				$db_field = strtolower($link['module']) . '.' . $db_field;
                        			}


                        	}
                        	else if ($type == 'parent') {
                        		if (!empty($this->searchFields['parent_type'])) {
                        			$parentType = $this->searchFields['parent_type'];
                        			$rel_module = $parentType['value'];
									global $beanFiles, $beanList;
	                        		if(!empty($beanFiles[$beanList[$rel_module]])) {
	    								require_once($beanFiles[$beanList[$rel_module]]);
									    $rel_seed = new $beanList[$rel_module]();
									    $db_field = 'parent_' . $rel_module . '_' . $rel_seed->table_name . '.name';
	                        		}
                        		}
                        	}
                        	// Relate fields in custom modules and custom relate fields
                        	else if ($type == 'relate' && $customField && !empty($this->seed->field_name_map[$field]['module'])) {
                        		$db_field = !empty($this->seed->field_name_map[$field]['name'])?$this->seed->field_name_map[$field]['name']:'name';
                        	}
                           else if(!$customField){
                               if ( !empty($this->seed->field_name_map[$field]['db_concat_fields']) )
                                   $db_field = db_concat($this->seed->table_name, $this->seed->field_name_map[$db_field]['db_concat_fields']);
                               else
                            	   $db_field = $this->seed->table_name .  "." . $db_field;
                        	}else{
                        		if ( !empty($this->seed->field_name_map[$field]['db_concat_fields']) )
                                   $db_field = db_concat($this->seed->table_name .  "_cstm.", $this->seed->field_name_map[$db_field]['db_concat_fields']);
                               else
                            	   $db_field = $this->seed->table_name .  "_cstm." . $db_field;
                        	}

                        }

                        if($type == 'date') {
                           // Collin - Have mysql as first because it's usually the case
                           // The regular expression check is to circumvent special case YYYY-MM
                           if($GLOBALS['db']->dbType == 'mysql') {
                                 if(preg_match('/^\d{4}.\d{1,2}$/', $field_value) == 0) {
                                    $field_value = $timedate->to_db_date($field_value, false);
                                    $operator = '=';
                                 } else {
                                    $operator = 'db_date';
                                 }
                           } else if($GLOBALS['db']->dbType == 'mssql') {
                                 if(preg_match('/^\d{4}.\d{1,2}$/', $field_value) == 0) {
                                    $field_value = "Convert(DateTime, '".$timedate->to_db_date($field_value, false)."')";
                                 }
                                 $operator = 'db_date';
                           } else {
                           	     $field_value = $timedate->to_db_date($field_value, false);
                           	     $operator = '=';
                           }
                        }

                        if($type == 'datetime' || $type == 'datetimecombo') {
                        	try {
                                // FG - bug45287 - If User asked for a range, takes edges from it.
                                $placeholderPos = strpos($field_value, "<>");
                                if ($placeholderPos !== FALSE && $placeholderPos > 0)
                                {
                                    $datesLimit = explode("<>", $field_value);
                                    $dateStart = $timedate->getDayStartEndGMT($datesLimit[0]);
                                    $dateEnd = $timedate->getDayStartEndGMT($datesLimit[1]);
                                    $dates = $dateStart;
                                    $dates['end'] = $dateEnd['end'];
                                    $dates['enddate'] = $dateEnd['enddate'];
                                    $dates['endtime'] = $dateEnd['endtime'];
                                }
                                else
                                {
                                    $dates = $timedate->getDayStartEndGMT($field_value);
                                }
                                // FG - bug45287 - Note "start" and "end" are the correct interval at GMT timezone
                                $field_value = $dates["start"] . "<>" . $dates["end"];
                                $operator = 'between';
                        	} catch(Exception $timeException) {
                        		//In the event that a date value is given that cannot be correctly processed by getDayStartEndGMT method,
                        		//just skip searching on this field and continue.  This may occur if user switches locale date formats 
                        		//in another browser screen, but re-runs a search with the previous format on another screen
                        		$GLOBALS['log']->error($timeException->getMessage());
                        		continue;
                        	}
                        }

                        // adjust date searches to take account for user timezone
                        // 'equals' and 'is between' cases are handled above.
                        if ($type =='' && !empty($parms['enable_range_search']) && $parms['enable_range_search']==true) {
                            // check if value is a db date or db datetime format
                            if (preg_match('/^(\d{4}-\d{2}-\d{2})( \d{2}:\d{2}:\d{2})?$/', $field_value)) {
                                if ($operator == 'not_equal') {
                                    $adjDate = $timedate->getDayStartEndGMT($field_value);
                                    $field_value = $adjDate['start'] . '<>' . $adjDate['end'];
                                    $operator = 'date_not_equal';
                                } elseif ($operator == 'greater_than' || $operator == 'less_than_equals') {
                                    $adjDate = $timedate->getDayStartEndGMT($field_value);
                                    $field_value = $adjDate['end'];
                                } elseif ($operator == 'less_than' || $operator == 'greater_than_equals') {
                                    $adjDate = $timedate->getDayStartEndGMT($field_value);
                                    $field_value = $adjDate['start'];
                                }
                                // check if value is something like [last_month]|[next_7_days]|[this_year]|etc...
                            } elseif (preg_match('/^\[[(this|last|next)_][_a-z0-9]*\]$/', $field_value)) {
                                switch($operator) {
                                    case 'last_7_days':
                                        $startDate = $timedate->getDayStartEndGMT(date('m/d/Y', time() - (7 * 24 * 60 * 60)));
                                        $endDate = $timedate->getDayStartEndGMT(date('m/d/Y'));
                                        break;
                                    case 'next_7_days':
                                        $startDate = $timedate->getDayStartEndGMT(date('m/d/Y'));
                                        $endDate = $timedate->getDayStartEndGMT(date('m/d/Y', time() + (7 * 24 * 60 * 60)));
                                        break;
                                    case 'next_month':
                                        $startDate = $timedate->getDayStartEndGMT(date('m/d/Y', mktime(0, 0, 0, date("m")+1, 01,   date("Y"))));
                                        $endDate = $timedate->getDayStartEndGMT(date('m/d/Y', mktime(0, 0, -1, date("m")+2, 01,   date("Y"))));
                                        break;
                                    case 'last_month':
                                        $startDate = $timedate->getDayStartEndGMT(date('m/d/Y', mktime(0, 0, 0, date("m")-1, 01,   date("Y"))));
                                        $endDate = $timedate->getDayStartEndGMT(date('m/d/Y', mktime(0, 0, -1, date("m"), 01,   date("Y"))));
                                        break;
                                    case 'this_month':
                                        $startDate = $timedate->getDayStartEndGMT(date('m/d/Y', mktime(0, 0, 0, date("m"), 01,   date("Y"))));
                                        $endDate = $timedate->getDayStartEndGMT(date('m/d/Y', mktime(0, 0, -1, date("m")+1, 01,   date("Y"))));
                                        break;
                                    case 'last_30_days':
                                        $startDate = $timedate->getDayStartEndGMT(date('m/d/Y', time() - (30 * 24 * 60 * 60)));
                                        $endDate = $timedate->getDayStartEndGMT(date('m/d/Y'));
                                        break;
                                    case 'next_30_days':
                                        $startDate = $timedate->getDayStartEndGMT(date('m/d/Y'));
                                        $endDate = $timedate->getDayStartEndGMT(date('m/d/Y', time() + (30 * 24 * 60 * 60)));
                                        break;
                                    case 'this_year':
                                        $startDate = $timedate->getDayStartEndGMT(date('m/d/Y', mktime(0, 0, 0, 01, 01,   date("Y"))));
                                        $endDate = $timedate->getDayStartEndGMT(date('m/d/Y', mktime(0, 0, 0, 12, 31,   date("Y"))));
                                        break;
                                    case 'last_year':
                                        $startDate = $timedate->getDayStartEndGMT(date('m/d/Y', mktime(0, 0, 0, 01, 01,   date("Y")-1)));
                                        $endDate = $timedate->getDayStartEndGMT(date('m/d/Y', mktime(0, 0, 0, 12, 31,   date("Y")-1)));
                                        break;
                                    case 'next_year':
                                        $startDate = $timedate->getDayStartEndGMT(date('m/d/Y', mktime(0, 0, 0, 01, 01,   date("Y")+1)));
                                        $endDate = $timedate->getDayStartEndGMT(date('m/d/Y', mktime(0, 0, 0, 12, 31,   date("Y")+1)));
                                        break;
                                }
                                $field_value = $startDate['start'] . "<>" . $endDate['end'];
                                $operator = 'between';
                            }
                        }
                        
                    	if($type == 'decimal' || $type == 'float' || $type == 'currency' || (!empty($parms['enable_range_search']) && empty($parms['is_date_field']))) {
							require_once('modules/Currencies/Currency.php');

							//we need to handle formatting either a single value or 2 values in case the 'between' search option is set
							//start by splitting the string if the between operator exists
							$fieldARR = explode('<>', $field_value);	
							//set the first pass through boolean
							$first_between = true;
	
							foreach($fieldARR as $fk => $fv){
								//reset the field value, it will be rebuild in the foreach loop below
								$tmpfield_value = unformat_number($fv);
		
								if ( $type == 'currency' && stripos($field,'_usdollar')!==FALSE ) {
									// It's a US Dollar field, we need to do some conversions from the user's local currency
									$currency_id = $GLOBALS['current_user']->getPreference('currency');
									if ( empty($currency_id) ) {
										$currency_id = -99;
									}
									if ( $currency_id != -99 ) {
										$currency = new Currency();
										$currency->retrieve($currency_id);
										$field_value = $currency->convertToDollar($tmpfield_value);
									}
								}

								//recreate the field value
								if($first_between){
									//set the field value with the new formatted temp value
									$field_value = $tmpfield_value;
								}else{
									//this is a between query, so append the between operator and add the second formatted temp value
									$field_value .= '<>'.$tmpfield_value;
								}
								//set the first pass through variable to false
								$first_between = false;								
							}
								
							if(!empty($parms['enable_range_search']) && $parms['operator'] == '=')
							{
								// Databases can't really search for floating point numbers, because they can't be accurately described in binary,
								// So we have to fuzz out the math a little bit								
								$field_value = ($field_value - 0.01) . "<>" . ($field_value + 0.01);
								$operator = 'between';
							}										
						}



                        $itr++;
                        if(!empty($where)) {
                            $where .= " OR ";
                        }
                        
                        switch(strtolower($operator)) {
                        	case 'subquery':
                        	    $in = 'IN';
                        	    if ( isset($parms['subquery_in_clause']) ) {
                        	        if ( !is_array($parms['subquery_in_clause']) ) {
                        	            $in = $parms['subquery_in_clause'];
                        	        }
                        	        elseif ( isset($parms['subquery_in_clause'][$field_value]) ) {
                        	            $in = $parms['subquery_in_clause'][$field_value];
                        	        }
                        	    }
                                $sq = $parms['subquery'];
                        		if(is_array($sq)){
                                    $and_or = ' AND ';
                                    if (isset($sq['OR'])){
                                        $and_or = ' OR ';
                                    }
                                    $first = true;
                                    foreach($sq as $q){
                                        if(empty($q) || strlen($q)<2) continue;
                                        if(!$first){
                                            $where .= $and_or;
                                        }
                                        $where .= " {$db_field} $in ({$q} '{$field_value}%') ";
                                        $first = false;
                                    }
                                }elseif(!empty($parms['query_type']) && $parms['query_type'] == 'format'){
                                    $stringFormatParams = array(0 => $field_value, 1 => $GLOBALS['current_user']->id);
                                    $where .= "{$db_field} $in (".string_format($parms['subquery'], $stringFormatParams).")";
                                }else{
                                    $where .= "{$db_field} $in ({$parms['subquery']} '{$field_value}%')";
                                }

    	                    	break;

                            case 'like':
                                if($type == 'bool' && $field_value == 0) {
                                    // Bug 43452 - FG - Added parenthesis surrounding the OR (without them the WHERE clause would be broken)
                                    $where .=  "( " . $db_field . " = '0' OR " . $db_field . " IS NULL )";
                                }
                                else {
                                	//check to see if this is coming from unified search or not
                                	$UnifiedSearch = !empty($parms['force_unifiedsearch']);
                                	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'UnifiedSearch'){
                                		$UnifiedSearch = true;
                                	}

                                	//check to see if this is a universal search OR the field has db_concat_fields set in vardefs, AND the field name is "last_name"
									if(($UnifiedSearch || !empty($this->seed->field_name_map[$field]['db_concat_fields'])) && strpos($db_field, 'last_name') !== false){
										//split the string value, and the db field name
										$string = explode(' ', $field_value);
										$column_name =  explode('.', $db_field);
										//when a search is done with a space, we concatenate and search against the full name.
										if(count($string)>1){
										    //add where clause against concatenated fields
											$where .= $GLOBALS['db']->concat($column_name[0],array('first_name','last_name')) . " LIKE '{$field_value}%'";
										    $where .= ' OR ' . $GLOBALS['db']->concat($column_name[0],array('last_name','first_name')) . " LIKE '{$field_value}%'";
										}else{
											//no space was found, add normal where clause
											$where .=  $db_field . " like '".$field_value.$like_char."'";
										}

									}
									else {

										//Check if this is a first_name, last_name search
										if(isset($this->seed->field_name_map) && isset($this->seed->field_name_map[$db_field]))
										{
											$vardefEntry = $this->seed->field_name_map[$db_field];
											if(!empty($vardefEntry['db_concat_fields']) && in_array('first_name', $vardefEntry['db_concat_fields']) && in_array('last_name', $vardefEntry['db_concat_fields']))
					                    	{
					                    	   	  if(!empty($GLOBALS['app_list_strings']['salutation_dom']) && is_array($GLOBALS['app_list_strings']['salutation_dom']))
					                    	   	  {
					                    	   	  	 foreach($GLOBALS['app_list_strings']['salutation_dom'] as $salutation)
					                    	   	  	 {
					                    	   	  	 	if(!empty($salutation) && strpos($field_value, $salutation) == 0)
					                    	   	  	 	{
					                    	   	  	 	   $field_value = trim(substr($field_value, strlen($salutation)));
					                    	   	  	 	   break;
					                    	   	  	 	}
					                    	   	  	 }
					                    	   	  }
					                    	}
										}

										//field is not last name or this is not from global unified search, so do normal where clause
										$where .=  $db_field . " like '".$field_value.$like_char."'";
									}
                                }
                                break;
                            case 'not in':    
                                $where .= $db_field . ' not in ('.$field_value.')';
                                break;
                            case 'in':
                                $where .=  $db_field . ' in ('.$field_value.')';
                                break;
                            case '=':
                                if($type == 'bool' && $field_value == 0) {
                                    $where .=  $db_field . " = '0' OR " . $db_field . " IS NULL";
                                }
                                else {
                                    $where .=  $db_field . " = '".$field_value ."'";
                                }
                                break;
                            case 'db_date':
                                if(preg_match('/^\d{4}.\d{1,2}$/', $field_value) == 0) {
                                  $where .=  $db_field . " = ". $field_value;
                                } else {
                                  // Create correct date_format conversion String
                                  if($GLOBALS['db']->dbType == 'oci8') {
                                  	$where .= db_convert($db_field,'date_format',array("'YYYY-MM'")) . " = '" . $field_value . "'";
                                  } else {
                                  	$where .= db_convert($db_field,'date_format',array("'%Y-%m'")) . " = '" . $field_value . "'";
                                  }
                                }
                                break;
                            // tyoung bug 15971 - need to add these special cases into the $where query
                            case 'custom_enum':
                            	$where .= $field_value;
                            	break;
                            case 'between':
                                $field_value = explode('<>', $field_value);
                                $where .= $db_field . " >= '".$field_value[0] . "' AND " .$db_field . " <= '".$field_value[1]."'";
                                break;
                            case 'date_not_equal':
                                $field_value = explode('<>', $field_value);
                                $where .= $db_field . " < '".$field_value[0] . "' OR " .$db_field . " > '".$field_value[1]."'";
                                break;
                            case 'innerjoin':
                                $this->seed->listview_inner_join[] = $parms['innerjoin'] . " '" . $parms['value'] . "%')";
                                break;
							case 'not_equal':
								$where .= $db_field . " != '". $field_value . "'";
								break;
							case 'greater_than':
								$where .= $db_field . " > '". $field_value . "'";
								break;
							case 'greater_than_equals':
								$where .= $db_field . " >= '". $field_value . "'";
								break;
							case 'less_than':
								$where .= $db_field . " < '". $field_value . "'";
								break;
							case 'less_than_equals':
								$where .= $db_field . " <= '". $field_value . "'";
								break;
                            case 'isnull':
                            	// OOTB fields are NULL, custom fields are blank
                                $where .= '('.$db_field . ' IS NULL or ' . $db_field . "='')"; 
                            	if ($field_value != '')
                                    $where .=  ' OR ' . $db_field . " in (".$field_value.')';
                                break;
                        }
                    }
                }

                if(!empty($where)) {
                    if($itr > 1) {
                        array_push($where_clauses, '( '.$where.' )');
                    }
                    else {
                        array_push($where_clauses, $where);
                    }
                }
            }
        }

        return $where_clauses;
    }
    
    
    /**
     * isEmptyDropdownField
     * 
     * This function checks to see if a blank dropdown field was supplied.  This scenario will occur where
     * a dropdown select is in single selection mode
     * 
     * @param $value Mixed dropdown value
     */
    private function isEmptyDropdownField($name='', $value=array())
    {
    	$result = is_array($value) && isset($value[0]) && $value[0] == '';
    	$GLOBALS['log']->debug("Found empty value for {$name} dropdown search key");
    	return $result;
    }    
 }
?>
