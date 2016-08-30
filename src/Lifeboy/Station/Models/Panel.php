<?php namespace Lifeboy\Station\Models;

use Config, Session, URL, DB, Input, Validator, Hash, Request, App;
use Lifeboy\Station\Models\Group as Group;
use Lifeboy\Station\Config\StationConfig as StationConfig;

class Panel {

    public $request                     = FALSE;
    public $non_writable_field_types    = ['multiselect', 'virtual', 'subpanel'];
    public $nestable_default_columns    = ['position', 'parent_id', 'depth'];
    protected $pagination_key           = 'station_page';
    protected $reserved_user_filters    = ['station_page'];

    public function add_all_to_array($passed_array)
    {   

        foreach($passed_array as $element_name => &$array)
        {
            $array = [0 => 'Show All'] + $array;
        }

        return $passed_array;
    }

    /**
     * return array of prev/next ids for a given id in a panel
     *
     * @param  int  $id
     * @param  string  $panel_name
     * @return array  ['prev' => {id}, 'next' => {id}]
     */
    public function adjacents_for($id, $panel_name){

        $count        = $this->get_data_for($panel_name, TRUE, FALSE, TRUE);

        if ($count['data'] > 10000) return FALSE; // simply too many records to handle adjacent navigation :(

        $data         = $this->get_data_for($panel_name, FALSE, FALSE, TRUE);
        $records      = $data['data'];
        $ret          = FALSE;

        if (count($records) > 1){

            $first_record = current($records);
            $final_record = end($records);
            $ret          = ['prev' => $final_record['id'], 'next' => $first_record['id']];
            $is_curr      = FALSE;
            $last_record  = FALSE;

            foreach ($records as $record) {
                
                if ($is_curr){

                    $ret['next'] = $record['id'];
                    break;
                }

                if ($record['id'] == $id){

                    $is_curr = TRUE;
                    $ret['prev'] = $last_record ? $last_record['id'] : $ret['prev'];
                }

                $last_record = $record;
            }
        }

        return $ret;
    }

    /**
     * return array key list of available panel names
     * 
     * @param  boolean  $with_subpanels  // much more memory intensive if TRUE
     * @return array
     */
    public function all($with_subpanels = FALSE, $skip_build_suppressed = FALSE){

        $groups = StationConfig::app('user_groups');
        $ret = [];

        foreach ($groups as $group_name => $group) { // loop over groups
            
            foreach ($group['panels'] as $panel_name => $panel) { // loop over panels
                
                if (isset($panel['is_header']) && $panel['is_header']) continue;

                $ret[$panel_name] = TRUE;

                if (!$with_subpanels) continue;

                $panel_data = StationConfig::panel($panel_name);

                if ($skip_build_suppressed && isset($panel_data['panel_options']['no_build']) && $panel_data['panel_options']['no_build']) {

                    unset($ret[$panel_name]);
                    continue;
                }

                if (isset($panel_data['elements']) && count($panel_data['elements']) > 0){

                    foreach ($panel_data['elements'] as $element_name => $element) { // loop over elements to find subpanels
                        
                        if ($element['type'] == 'subpanel'){

                            $ret[$element_name] = TRUE;
                        }
                    }
                }
            }
        }

        return array_keys($ret);
    }

    /**
     * return array key list of available panel names by user group
     *
     * @return array
     */
    public function all_by_group(){

        return StationConfig::app('user_groups');
    }

    /**
     * insert new record for this panel
     *
     * @param  string  $panel_name
     * @param  array  $data // for the new record
     * @return array or false // if user does not have access
     */
    public function create_record_for($panel_name, $user_scope, $is_for_user = FALSE, $overrides = FALSE){

        $writable_fields = $this->writable_fields($user_scope);
        $data            = $this->request->only($writable_fields);
        $data            = $this->override($data, $overrides);
        $data            = $this->filter_data($data, $user_scope, $writable_fields);
        $model_name      = $this->model_name_for($panel_name);
        $model           = new $model_name;
        
        $model->fill($data);
        $model->save();
        $insert_id = $model->id;

        $this->attach_joins($model, $user_scope, $is_for_user);

        return $insert_id;
    }

    public function custom_view_vars(){

        $custom_view_vars = StationConfig::app('custom_view_vars');
        $ret = [];

        if ($custom_view_vars && is_array($custom_view_vars)){

            foreach ($custom_view_vars as $var => $custom_value_declaration) {

                if (strpos($custom_value_declaration, '::') || strpos($custom_value_declaration, '->')){

                    // it's a function call
                    $args           = preg_match('#\((.*?)\)#', $custom_value_declaration, $match);
                    $args_injected  = isset($match[1]) ? explode(',', $this->inject_vars($match[1], TRUE)) : [];
                    $function       = preg_replace("/\([^)]+\)/", "", $custom_value_declaration);
                    $value          = call_user_func_array($function, $args_injected);
                
                } else {

                    $value = $custom_value_declaration;
                }

                $ret[$var] = $value;
            }
        }

        return $ret;
    }

    /**
     * delete specific record for this panel
     *
     * @param  string  $panel_name
     * @param  int  $id // of the record
     * @return array or false // if user does not have access
     */
    public function delete_record_for($panel_name, $id){

        $model_name      = $this->model_name_for($panel_name);
        $model           = new $model_name;
        
        $model::destroy($id);

        return TRUE;
    }

    /**
     * reduce the elements in user_scope down to only ones specified
     *
     * @param  array  $user_scope
     * @param  array  $element_names_to_keep  // list of key names
     * @return array  // $user_scope
     */
    public function filter_scope_elements($user_scope, $element_names_to_keep = []){

        foreach ($user_scope['config']['elements'] as $key => $value) {
            
            if (!in_array($key, $element_names_to_keep)){

                unset($user_scope['config']['elements'][$key]);
            }
        }

        return $user_scope;
    }

    /**
     * for each element of a panel that a user can access
     * and which references a foreign table for data, get that data!
     *
     * @param  array  $user_scope
     * @return array // set of foreign tables' data, organized by element name as key
     */
    public function foreign_data_for($user_scope){

        $ret = [];

        foreach ($user_scope['config']['elements'] as $element_name => $element) {
            
            if ($element['type'] == 'subpanel'){

                $subpanel = StationConfig::panel($element_name);
                
                foreach ($subpanel['elements'] as $sub_element_name => $sub_element) {
                    
                    if ($this->has_foreign_data($sub_element)){

                        $ret[$sub_element_name] = $this->foreign_data($sub_element);
                    }
                }

            } else if ($this->has_foreign_data($element)){

                $ret[$element_name] = $this->foreign_data($element);
            }
        }

        return $ret;
    }

    /**
     * for each element of a panel that a user can access
     * and which references a foreign panel, get the panel config!
     *
     * @param  array  $user_scope
     * @return array // set of foreign panels' data, organized by element name as key
     */
    public function foreign_panels_for($user_scope){

        $ret = [];

        foreach ($user_scope['config']['elements'] as $element_name => $element) {
            
            if ($element['type'] == 'subpanel'){

                $ret[$element_name] = StationConfig::panel($element_name);

            }
        }

        return $ret;
    }

    /**
     * return everything we can about this panel 
     * config, data, joins, etc.
     *
     * @param  string  $panel_name
     * @return array or false // if not found or user does not have access
     */
    public function get_data_for($panel_name, $count_only = FALSE, $subpanel_parent = FALSE, $ids_only = FALSE, $keyword = FALSE, $filter_on_count = FALSE){

        $panel = $this->user_scope($panel_name, 'L', $subpanel_parent);

        if (isset($panel['config']['panel_options']['override']) && $panel['config']['panel_options']['override']) return $panel;
        if (!$panel) return FALSE;

        $model_name                = $this->model_name_for($panel_name);
        $table_name                = $panel['config']['panel_options']['table'];
        $model                     = new $model_name;
        $is_filtered               = (!$count_only && !$keyword) || ($count_only && $filter_on_count);
        $primary_element           = $table_name.'.'.$this->primary_element_for($panel);
        $fields_for_select         = $ids_only ? [$table_name.'.id'] : $this->fields_for_select($panel);
        $fields_for_select         = $keyword ? [$table_name.'.id', DB::raw($primary_element.' AS name')] : $fields_for_select;
        $where_clause              = $this->where_clause_for($panel); 
        $joins                     = $this->joins_for($panel); 
        $user_filters              = $is_filtered ? $this->user_filters_for($panel_name) : array();
        $order_by                  = $this->order_by_clause_for($panel, $user_filters); 

        $query                     = $model::select($fields_for_select); 
        $query                     = $where_clause ? $query->whereRaw($where_clause) : $query;
        $query                     = $keyword ? $query->whereRaw($primary_element." LIKE '%".addslashes($keyword)."%'") : $query;
        $query                     = $is_filtered ? $this->apply_joins_with_filters($joins, $user_filters, $query, $panel) : $query;
        $query                     = $order_by ? $query->orderByRaw($order_by) : $query;

        $panel['user_filters']     = $user_filters;
        $panel['has_user_filters'] = count($user_filters) > 0;

        if ($count_only){

            $panel['data'] = $query->count();

        } else {

            $is_nestable         = isset($panel['config']['panel_options']['nestable_by']) && $panel['config']['panel_options']['nestable_by'];
            $is_reorderable      = isset($panel['config']['panel_options']['reorderable_by']) && $panel['config']['panel_options']['reorderable_by'];
            $should_not_paginate = $ids_only || $keyword || $is_nestable || $is_reorderable;
            $query               = $should_not_paginate ? $query : $this->paginate($query, $panel_name);
            $panel['data']       = $query->get()->toArray(); 
        }

        if ($is_filtered) $panel['data'] = $this->run_pivot_filters($panel, $user_filters);

        return $panel;
    }

    /**
     * return everything we can about this panel but only the data for a 
     * single record in the corresponding table
     *
     * @param  string  $panel_name
     * @param  int  $id // of the record
     * @return array or false // if user does not have access
     */
    public function get_record_for($panel_name, $id, $subpanel_parent = FALSE){

        $panel              = $this->user_scope($panel_name, 'U', $subpanel_parent);

        if (!$panel) return FALSE;

        $model_name         = $this->model_name_for($panel_name);
        $model              = new $model_name;
        $fields_for_select  = $this->fields_for_select($panel);
        $where_clause       = $this->where_clause_for($panel); 
        $joins              = $this->joins_for($panel);
        $query              = $model::select($fields_for_select);
        $query              = $where_clause ? $query->whereRaw($where_clause) : $query;
        $query              = count($joins) > 0 ? $query->with($joins) : $query;
        $panel['data']      = $query->find($id);

        return $panel;
    }

    public function img_sizes_for($user_scope, $app_config)
    {
        $ret                = array();
        $ret['standard']    = $app_config['media_options']['sizes'];

        foreach ($user_scope['config']['elements'] as $key => $value)
        {
            if(isset($value['sizes'])) $ret[$key] = $value['sizes'];
        }

        return $ret;
    }

    public function inject_vars($str, $standard_only = FALSE){

        $standard_vars    = ['%user_id%' => Session::get('user_data.id')]; 
        $custom_user_vars = !$standard_only ? $this->custom_user_vars($str) : [];
        $replacements     = array_merge($standard_vars, $custom_user_vars);

        return str_replace(array_keys($replacements), array_values($replacements), $str);
    }

    /**
     * returns the determined model name for the specified panel
     *
     * @param  array  $panel // full configuration from StationConfig::panel({panel-name}) config file  
     * @return string // the model name
     */
    public function model_name_for($panel_name, $no_path = FALSE){

        switch ($panel_name) {

            case 'users':
                return $no_path ? 'User' : 'Lifeboy\Station\Models\User';
                break;

            case 'groups':
                return $no_path ? 'Group' : 'Lifeboy\Station\Models\Group';
                break;
            
            default:
                $name = ucwords(str_singular($panel_name));
                return $no_path ? $name : '\App\Models\\'.$name;
                break;
        }
    }

    public function primary_element_value($record = array()){

        foreach ($record as $key => $value) {
            
            if (!is_numeric($value) && is_string($value) && strlen($value) > 1){

                return $value;
            }
        }

        return '';
    }

    public function reorder($table, $column = 'position', $ids = array()){

        $i = 1;

        foreach ($ids as $id) {
            
            DB::table($table)->where('id', $id)->update(array($column => $i));
            $i++;
        }

        return TRUE;
    }

    public function reorder_nested($table, $columns, $ids = array()){

        $data = $this->flatten_nested_data(json_decode($ids, TRUE));
        $columns_to_use = $this->get_nested_columns($columns);
        
        foreach ($data as $key => $item) {
            
            $depth = count($item['parents']) - 1;
            $parent_id = $depth == 0 ? 0 : $item['parents'][($depth - 1)];

            $data_to_update = [

                $columns_to_use[0] => ($key+1), // 'position' column // start at 1, not 0 so new items will float to the top.
                $columns_to_use[1] => $parent_id, // 'parent_id' column
                $columns_to_use[2] => $depth // 'depth' column
            ];

            DB::table($table)->where('id', $item['value'])->update($data_to_update);
        }

        return TRUE;
    }

    /**
     * update a single record for this panel
     *
     * @param  string  $panel_name
     * @param  array  $user_scope
     * @param  int  $id // of the record
     * @return array or false // if user does not have access
     */
    public function update_record_for($panel_name, $user_scope, $id){
        
        $writable_fields = $this->writable_fields($user_scope);
        $data            = $this->request->only($writable_fields); 
        $data            = $this->filter_data($data, $user_scope, $writable_fields);
        $model_name      = $this->model_name_for($panel_name);
        $model           = new $model_name;

        $old_item = $model::find($id);
        $old_item::unguard();
        $old_item->fill($data);
        $old_item->save();

        $this->attach_joins($old_item, $user_scope);

        return TRUE;
    }

    /**
     * return panel config as seen from user perspective.
     * elements are filtered down to only the ones visible to this user.
     *
     * @param  string  $panel_name
     * @param  string  $letter // of CRUDL
     * @param  string  $subpanel_parent // parent panel name
     * @return array or false // if user does not have access
     */
    public function user_scope($panel_name, $letter = 'L', $subpanel_parent = FALSE){

        $ret               = array();
        $panels            = $this->all_by_group();
        $primary_role      = App::runningInConsole() ? 'admin' : Session::get('user_data.primary_group');
        $has_panel_access  = FALSE;
        $has_parent_access = FALSE;

        if (!$primary_role) $primary_role = 'anon';

        if ($subpanel_parent){ // does user have access to the parent panel 'U' method?

            $has_parent_access = isset($panels[$primary_role]['panels'][$subpanel_parent]['permissions']) 
                            && strpos($panels[$primary_role]['panels'][$subpanel_parent]['permissions'], 'U') !== FALSE;
        
        } else {

            $has_panel_access = isset($panels[$primary_role]['panels'][$panel_name]['permissions']) 
                            && strpos($panels[$primary_role]['panels'][$panel_name]['permissions'], $letter) !== FALSE;
        }

        if ($has_panel_access || $has_parent_access){ // can access panel AND can perform $letter method

            $ret['registry']           = isset($panels[$primary_role]['panels'][$panel_name]) ? $panels[$primary_role]['panels'][$panel_name] : '';
            $ret['config']             = StationConfig::panel($panel_name); // start with the whole config!
            $ret['config']['elements'] = $this->elements_for_letter($ret['config'], $letter); // but filter the elements.
            $ret['config']['elements'] = $this->inject_vars_for_elements($ret['config']['elements']);

            //if (count($ret['config']['elements']) == 0) return FALSE; // no elements, no access for you!

            return $ret;
        }

        return FALSE;
    }
    
    /**
     * returns array of panels that a user can access 
     *
     * @return array
     */
    public function user_panel_access_list(){

        $ret            = array();
        $base_uri       = StationConfig::app('root_uri_segment');
        $primary_role   = Session::get('user_data.primary_group');
        $primary_role   = $primary_role ?: 'anon';
        $panels         = $this->all_by_group();

        foreach($panels[$primary_role]['panels'] as $panel_name => $panel_data)
        {
            // if user has permissions or it's a header
            if ((isset($panel_data['permissions']) 
                && $panel_data['permissions'] != 'none' 
                && $panel_data['permissions'] != '')
                || isset($panel_data['is_header']))
            {
                $slug           = isset($panel_data['uri_slug']) ? $this->inject_vars($panel_data['uri_slug']) : 'index';
                $uri            = $base_uri.'/panel/'.$panel_name.'/'.$slug;
                $seperator_name = isset($panel_data['is_header']) ? $panel_data['name'] : '';
                $label          = isset($panel_data['name']) ? $panel_data['name'] : $seperator_name;
                
                if ($label != ''){

                    if(isset($panel_data['badge']))
                    {
                        $panel_data['badge'] = $this->get_data_for($panel_name,TRUE);
                    }

                    $ret[] = [

                        'panel'        => $panel_name,
                        'uri'          => URL::to($uri),
                        'label'        => $label,
                        'is_header'    => isset($panel_data['is_header']) && $panel_data['is_header'],
                        'icon'         => isset($panel_data['icon']) ? $panel_data['icon'] : FALSE,
                        'badge'        => isset($panel_data['badge']) ? $panel_data['badge']['data'] : FALSE
                    ];
                }
            }
        }

        return $ret;
    }

    /**
     * checks all inputs from user against validation object and rules from the panel config
     *
     * @param  string  $panel_name
     * @param  array  $user_scope
     * @return object Validator
     */
    public function validates_against($panel_name, $user_scope, $unique_id = FALSE){

        $inputs   = [];
        $rules    = [];
        $messages = [];

        foreach ($user_scope['config']['elements'] as $element_name => $element) {
            
            if (isset($element['rules'])) {
                
                $inputs[$element_name] = $this->request->input($element_name);
                $rules[$element_name]  = $this->filter_element_rules($element['rules'], $unique_id);

                // make pretty error messages using field "labels" not the DB column name / element name
                // right now we only rewrite the error message for 'required' // TODO: add more of these for other common rules
                if (strpos($rules[$element_name], 'required') !== FALSE){ 

                    $messages[$element_name.'.required'] = '"'.$element['label'].'" is required.';
                }
            }
        }

        return Validator::make($inputs, $rules, $messages);
    }



    /**
     * accepts a panel name from config, for example 'users.L'
     * and returns the URI slug needed to redirect to.
     *
     * @param  string  $name
     * @return string // URI slug
     */
    static function config_to_uri($name){

        $base_uri       = StationConfig::app('root_uri_segment').'/';
        $name_arr       = explode('.', $name);
        $panel_name     = $name_arr[0];
        $letter         = isset($name_arr[1]) ? $name_arr[1] : 'L'; // list view by default?
        $panel_method   = static::method_name_for_letter($letter);

        return $base_uri.'panel/'.$panel_name.'/'.$panel_method; // TODO: <--- we need to add support for record numbers here
    }

    /**
     * converts a single letter from a CRUDL letter string 
     * returns the URI segment name
     *
     * @param  string  $letter
     * @return string // URI segment name
     */
    static function method_name_for_letter($letter = 'L'){

        switch (substr($letter, 0, 1)) {

            case 'L':
                
                return 'index';
                break;

            case 'C':
                
                return 'create';
                break;

            case 'R':
                
                return 'show';
                break;

            case 'U':
                
                return 'edit';
                break;

            case 'D':
                
                return 'delete';
                break;
            
            default:
                
                return 'index';
                break;
        }
    }



    private function apply_joins_with_filters($joins, $filters, $query, $panel){

        // removed reserved filters
        $filters = $this->remove_reserved_filters($filters);

        // set filters for non-pivots
        foreach ($filters as $filter => $id) {
            
            if (!isset($panel['config']['elements'][$filter]['data']['pivot'])){

                $query = $query->where($panel['config']['panel_options']['table'].".".$filter, '=', $id);
            }
        }

        // set filters & joins for pivots
        if (count($joins) > 0){

            foreach ($joins as $join) {

                // if there is a filter set for this join & it is a pivot
                if (isset($filters[$join]) && isset($panel['config']['elements'][$join]['data']['pivot'])){
                    
                    $query = $query->with(array($join => function($query) use ($join, $filters)
                    {
                        $query->where(str_singular($join).'_id', '=', $filters[$join]);
                    }));

                // just join (any kind). no filter.
                } else {
                    
                   $query = $query->with($join); 
                }
            }
        }

        return $query;
    }

    private function attach_joins($model, $user_scope, $for_user = FALSE){

        if ($for_user) {
            
            $starting_group_name = StationConfig::app('user_group_upon_register');

            if (strpos($starting_group_name, 'input:') !== FALSE){

                // group is set by choice made at registration
                $str_arr          = explode(':', $starting_group_name);
                $value_from_input = $this->request->input($str_arr[1]);
                $lowest_group_id  = Group::where('name', '=', 'standard')->pluck('id'); // TODO: change. may not always have a standard group!
                $group_id         = is_numeric($value_from_input) ? $value_from_input : $lowest_group_id;

            } else { // new user is forced into a group

                $group_id = Group::where('name', '=', $starting_group_name)->first()->id;
            }
            
            $model->groups()->attach($group_id);
        
        } else {

            $joins = $this->joins_for($user_scope, TRUE);

            foreach ($joins as $join) {
                
                $data_for_join = $this->request->input($join);
                $model->$join()->sync($data_for_join == null ? array() : $data_for_join);
            }
        }
    }

    /**
     * convert terms into SELECT SQL clause  
     *
     * @param  mixed  $terms 
     * @return string // SQL clause
     */
    private function concat_clause($terms, $output_field = 'value'){

        if (is_array($terms)){

            $arr = [];

            foreach ($terms as $term) {
                
                $arr[] = substr_count($term, '.') != 1 ? "'".$term."'" : $term;
            }

            $ret = "CONCAT(".implode(',', $arr).") AS ".$output_field;

        } else {

            $ret = $terms.' AS '.$output_field;
        }

        return $ret;
    }

    private function custom_user_vars($str){

        $custom_user_vars = StationConfig::app('custom_user_vars');
        $ret = [];

        if ($custom_user_vars && is_array($custom_user_vars)){

            foreach ($custom_user_vars as $var => $custom_value_declaration) {
                
                $flanked_var = '%'.$var.'%';

                if (strpos($str, $flanked_var) !== FALSE){

                    if (strpos($custom_value_declaration, '::') || strpos($custom_value_declaration, '->')){

                        // it's a function call
                        $args           = preg_match('#\((.*?)\)#', $custom_value_declaration, $match);
                        $args_injected  = isset($match[1]) ? explode(',', $this->inject_vars($match[1], TRUE)) : [];
                        $function       = preg_replace("/\([^)]+\)/", "", $custom_value_declaration);
                        $value          = call_user_func_array($function, $args_injected);
                    
                    } else {

                        $value = $custom_value_declaration;
                    }

                    $ret[$flanked_var] = $value;
                }
            }
        }

        return $ret;
    }

    /**
     * returns array of elements which can be accessed given a CRUDL letter / method
     *
     * @param  array  $panel_config // full config of panel
     * @param  string  $letter // method letter of CRUDL
     * @return array // elements, filtered
     */
    private function elements_for_letter($panel_config, $letter = 'L'){

        $ret = [];

        if (!isset($panel_config['elements']) || count($panel_config['elements']) == 0) return $ret;

        foreach ($panel_config['elements'] as $element_name => $element) {
            
            $hidden_in_list = $letter == 'L' && $element['type'] == 'hidden';

            if ((isset($element['display']) && strpos($element['display'], $letter) !== FALSE) && !$hidden_in_list){

                $ret[$element_name] = $element;
            }
        }

        return $ret;
    }

    /**
     * return array of fields that can be selected directly from table (not joins)
     *
     * @param  array  $panel // the full panel config
     * @return array // field names
     */
    private function fields_for_select($panel){

        $table_name  = $panel['config']['panel_options']['table'];
        $preview_url = isset($panel['config']['panel_options']['preview_url']) ? $panel['config']['panel_options']['preview_url'] : FALSE;
        $ret         = [$table_name.'.id'];

        foreach ($panel['config']['elements'] as $element_name => $element) {
            
            if (!isset($element['data']['pivot']) && !in_array($element['type'], $this->non_writable_field_types)){

                $ret[] = $table_name.'.'.$element_name;
            }

            if ($element['type'] == 'virtual' && isset($element['concat'])){

                $ret[] = DB::raw('CONCAT('.$element['concat'].') AS '.$element_name);
            }
        }

        // if we have nestable columns, let's add those too.
        if (isset($panel['config']['panel_options']['nestable_by'])){

            $nestable_columns = $this->get_nested_columns($panel['config']['panel_options']['nestable_by']);

            foreach ($nestable_columns as $col) {
                
                $ret[] = $table_name.'.'.$col;
            }
        }

        if ($preview_url){

            $ret[] = DB::raw($this->concat_clause($preview_url, 'preview_url'));
        }

        return $ret;
    }

    /**
     * right now this just adds an 'ignore' ID to any 'unique' validation rule when we are updating that record
     * this could easily accept more filters if needed.
     *
     * @param  string  $rules
     * @param  int  $unique_id
     * @return string // filtered rules set
     */
    private function filter_element_rules($rules, $unique_id = FALSE){

        if (!$unique_id || $rules == '') return $rules; // no unique ID, we don't care.

        $rules_arr = explode('|', $rules);
        $new_rules = [];

        foreach ($rules_arr as $rule) {
            
            if (strpos($rule, 'unique:') !== FALSE){

                $new_rules[] = $rule.','.$unique_id;
            
            } else {

                $new_rules[] = $rule;
            }
        }

        $ret = implode('|', $new_rules);

        return $ret;
    }

    private function filter_data($data, $user_scope, $writable_fields){

        foreach ($user_scope['config']['elements'] as $element_name => $element) {
            
            // passwords
            if (in_array($element_name, $writable_fields) && $element['type'] == 'password' && $data[$element_name] != ''){

                $data[$element_name] = Hash::make($data[$element_name]);
            }

            if (in_array($element_name, $writable_fields) && $element['type'] == 'password' && $data[$element_name] == ''){

                unset($data[$element_name]);
            }
        }

        return $data;
    }

    private function flatten_nested_data(array $array = array(), $key_stack = array(), $result = array()){

        foreach ($array as $key => $value) {

            if (is_numeric($value)) $key_stack[] = $value;

            if (is_array($value)) {

              $result = $this->flatten_nested_data($value, $key_stack, $result);
            
            } else {

                $result[] = array(

                  'parents' => $key_stack,
                  'value'   => $value
                );
            }
        }

        return $result;
    }

    private function foreign_data($element){

        $table              = $element['data']['table'];
        $display            = $this->concat_clause($element['data']['display']);
        $where              = isset($element['data']['where']) ? $element['data']['where'] : FALSE;
        $order              = isset($element['data']['order']) ? $element['data']['order'] : FALSE;
        $query              = DB::table($table)->select('id', DB::raw($display));
        $query              = $where ? $query->whereRaw($this->inject_vars($where)) : $query;
        $query              = $order ? $query->orderBy($order) : $query;
        $data               = $query->get();
        return              $this->to_array($data);
    }

    private function get_nested_columns($columns){

        $i = 0;

        // if user specified their own columns to use, use those, if not, use the defaults
        foreach ($this->nestable_default_columns as $nestable_default_column) {
            
            $columns_to_use[$i] = isset($columns[$i]) ? $columns[$i] : $nestable_default_column;
            $i++;
        }

        return $columns_to_use;
    }

    private function has_foreign_data($element){

       return isset($element['data']['table']) && isset($element['data']['display']);
    }

    private function inject_vars_for_elements($elements){

        if (Session::get('user_data')){

            $ret               = [];
            $fields_to_replace = ['default'];

            foreach ($elements as $name => $element) {
                
                $ret[$name] = $element;

                foreach ($fields_to_replace as $field) {
                    
                    if (isset($ret[$name][$field])){

                        $ret[$name][$field] = $this->inject_vars($ret[$name][$field]);
                    }
                }
            }

            return $ret;

        } else {

            return $elements;
        }
    }

    /**
     * returns a list of join tables for the specified panel
     *
     * @param  array  $panel // full configuration from StationConfig::panel({panel-name}) config file  
     * @return array // join tables
     */
    private function joins_for($panel, $for_write = FALSE){

        $ret = [];

        foreach ($panel['config']['elements'] as $element_name => $element) {
            
            // add join on a pivot
            if (isset($element['data']['pivot']) || isset($element['data']['table'])){

                if(isset($element['data']['relation']) && $element['data']['relation']=='belongsToMany')
                {
                    $ret[] = isset($element['data']['pivot']) ? $element['data']['pivot'] : $element['data']['table'];
                }
            }

            // add join on a non-pivot
            /*
            if (isset($element['data']['relation']) && $element['data']['relation'] == 'belongsTo' && isset($element['data']['table']) &&  && !$for_write){

                $ret[] = $element['data']['table'];
            }
            */

            // add join on a subpanel, but only when reading. subpanels write one-at-a-time.
            if ($element['type'] == 'subpanel' && !$for_write){

                $ret[] = $element_name;
            }
        }

        return $ret;
    }

    private function order_by_clause_for($panel, $user_filters){

        // TODO, see about filters and use one for sorting.
        
        if (isset($panel['config']['panel_options']['default_order_by']) && $panel['config']['panel_options']['default_order_by'] != '') {
            
            $field     = $panel['config']['panel_options']['default_order_by'];
            $table     = $panel['config']['panel_options']['table'];
            $ret       = "";

            foreach (explode(',', $field) as $key => $term) {
                
                $term = trim($term);
                $ret .= $key > 0 ? ", " : "";
                $ret .= $table.'.'.$term;
            }

            return $ret;
        }

        return FALSE;
    }

    private function override($data, $overrides = FALSE){

        $ret = $data;

        if ($overrides && is_array($overrides) && count($overrides) > 0){

            foreach ($overrides as $key => $value) {
                
                $ret[$key] = $value;
            }
        }

        return $ret;
    }

    /**
     * adds skip() and take() methods to the eloquent query chain 
     * used to limit and offset the full set of data for a panel
     * 
     * takes into account the current session variable of current page
     * also provides different results based on if this is an ajax request (see inline notes)
     *
     * @param  illuminate\eloquent  $query 
     * @param  string  $panel_name
     * @return illuminate\eloquent
     */
    private function paginate($query, $panel_name){

        $var_name       = $this->pagination_key;
        $offset         = 0;
        $n_per_page     = 50;
        $requested_page = $this->request->input($var_name);
        $incrementer    = 0;
        $key            = $var_name.'.'.$panel_name;

        if ($requested_page == 'next') $incrementer = 1;

        if (!Session::has($key) && $requested_page != FALSE){ // page requested but no session yet

            $page = is_numeric($requested_page) ? $requested_page : 1;
            Session::put($key, $page);

        } else if (Session::has($key) && $requested_page != FALSE){ // page requested + already a session 

            $curr_page = Session::get($key);
            $page = $incrementer > 0 ? $curr_page + $incrementer : $requested_page;
            Session::put($key, $page);

        } else if (Session::has($key)){ // no page requested + already a session 

            $page = Session::get($key) + $incrementer;
            if ($incrementer > 0) Session::put($key, $page);

        } else { // no page requested + no session

            $page = 0;
        }

        // if we have an ajax request we only need to grab the next page worth,
        // however when we have a normal request we need to load the full set up through the page we're on
        $offset = Request::ajax() ? $n_per_page * $page : 0;
        $take   = Request::ajax() ? $n_per_page : $n_per_page * ($page + 1);

        return $query->skip($offset)->take($take);
    }

    private function primary_element_for($panel){

        foreach ($panel['config']['elements'] as $element_name => $element) {
            
            if ($element['type'] == 'text') {
                
                return $element_name;
            }
        }

        // if we don't have a primary element yet, just return the first one.
        foreach ($panel['config']['elements'] as $element_name => $element) {
            
            return $element_name;
        }
    }

    private function remove_zeroed_values($data){

        $ret = [];

        foreach ($data as $key => $value) {
            
            if ($value != 0){

                $ret[$key] = $value;
            }
        }

        return $ret;
    }

    private function remove_reserved_filters($filters = array()){

        foreach ($this->reserved_user_filters as $reserved_user_filter) {
            
            unset($filters[$reserved_user_filter]);
        }

        return $filters;
    }

    /**
     * returns a reduced data set for the panel based on the filters set which apply to pivot style joins
     *
     * @param  array  $panel
     * @param  array  $filters
     * @return array  $panel['data']
     */
    private function run_pivot_filters($panel, $filters){

        $ret                = [];
        $filters            = $this->remove_reserved_filters($filters);
        $n_filters_applied  = 0;

        if (count($filters) > 0){

            foreach ($filters as $filter => $filter_id){

                if (isset($panel['config']['elements'][$filter]['data']['pivot'])){

                    foreach ($panel['data'] as $item) {
                        
                        if (count($item[$filter]) > 0){

                            $ret[] = $item;
                        }
                    }

                    $n_filters_applied++;
                }
            }
        
        } else {

            return $panel['data'];
        }

        return $n_filters_applied == count($filters) ? $ret : $panel['data'];
    }

    /**
     * convenience function to index an object by ID into an array 
     *
     * @param  mixed  $data // outer array with inner objects  
     * @return array
     */
    private function to_array($data) {

        $ret = [];

        foreach ($data as $item) {
            
            $ret[$item->id] = $item->value;
        }

        return $ret;
    }

    private function user_filters_for($panel_name){

        if ($this->request->input('ids')) return array(); // this was a reorder request, not a filter request

        if (count($this->request->all()) > 0){

            $filters = $this->remove_zeroed_values($this->request->all());
            Session::put('user_filters.'.$panel_name, $filters);
            return $filters;

        } else if (Session::has('user_filters.'.$panel_name)){

            $filters = Session::get('user_filters.'.$panel_name);
            return $this->remove_zeroed_values($filters);
        
        } else {

            return array();
        }
    }

    private function where_clause_for($panel){

        if (isset($panel['config']['panel_options']['where']) && $panel['config']['panel_options']['where'] != '') {
            
            if (Session::get('user_data')){

                return $this->inject_vars($panel['config']['panel_options']['where']);

            } else {

                return $panel['config']['panel_options']['where'];
            }
        }

        return FALSE;
    }

    /**
     * given the fields we know a user has access to for method they are in 
     * return a list of element/field names that can be written to DB
     *
     * @param  array  $user_scope
     * @return array // element names
     */
    private function writable_fields($user_scope){

        $ret = [];

        foreach ($user_scope['config']['elements'] as $element_name => $element) {
            
            if (!in_array($element['type'], $this->non_writable_field_types) 
                && !isset($element['data']['pivot'])
                && !isset($element['disabled'])){

                $ret[] = $element_name;
            }
        }

        return $ret;
    }
}