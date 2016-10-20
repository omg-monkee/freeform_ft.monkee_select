<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monkee_select_freeform_ft extends Freeform_base_ft {
	public 	$info 	= array(
		'name' 			=> 'Monk-ee Select',
		'version' 		=> '3.0.0', //Modified Freeform Select 5.1.0
		'description' 		=> 'Modified version of the default Select fieldtype.'
	);

	private $valid_resources = array(
		'channel_field' ,
		'value_list'	,
		'value_label_list'
	);
	
	public $javascript_events = array(
		'none',
		'onblur',
		'onchange',
		'onclick',
		'oncontextmenu',
		'ondblclick',
		'onfocus',
		'onfocusin',
		'onfocusout',
		'oninput',
		'oninvalid',
		'onkeydown',
		'onkeypress',
		'onkeyup',
		'onmousedown',
		'onmouseenter',
		'onmouseleave',
		'onmousemove',
		'onmouseover',
		'onmouseout',
		'onmouseup',
		'onreset',
		'onsearch',
		'onselect',
		'onsubmit'
	);


	// --------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @access	public
	 * @return	null
	 */

	public function __construct () {
		parent::__construct();

		$this->info['name'] 		= lang('monksel_default_field_name');
		$this->info['description'] 	= lang('monksel_default_field_desc');
	}
	//END __construct


	// --------------------------------------------------------------------

	/**
	 * Display Field Settings
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */

	public function display_settings ($data = array())
	{
		$this->multi_item_row($data);
		
		//blank line
		$blank_line			= ( ! isset($data['blank_line']) OR
						$data['blank_line'] == '') ?
							'n' :
							$data['blank_line'];

		ee()->table->add_row(
			lang('monksel_blank_line', 'blank_line') .
			'<div class="subtext">' .
				lang('monksel_blank_line_desc') .
			'</div>',
			form_hidden('blank_line', 'n') .
			form_checkbox(array(
				'id'	=> 'blank_line',
				'name'	=> 'blank_line',
				'value'		=> 'y',
				'checked' 	=> $blank_line == 'y'
			)) .
			'&nbsp;&nbsp;' .
			lang('monksel_blank_line', 'blank_line')
		);
		
		//default value
		$default_value 		= ( ! isset($data['default_value']) OR
						$data['default_value'] == '') ?
						'' :
						$data['default_value'];
							
		ee()->table->add_row(
			lang('monksel_default_value', 'default_value') .
				'<div class="subtext">' .
					lang('monksel_default_value_desc') .
				'</div>',
			form_input(array(
				'name'		=> 'default_value',
				'id'		=> 'default_value',
				'value'		=> $default_value,
				'maxlength'	=> '250',
				'size'		=> '50',
			))
		);
		
		//autofocus
		$enable_autofocus 		= ( ! isset($data['enable_autofocus']) OR
						$data['enable_autofocus'] == '') ?
							'false' :
							$data['enable_autofocus'];
							
		ee()->table->add_row(
			lang('monksel_enable_autofocus', 'enable_autofocus') .
			'<div class="subtext">' .
				lang('monksel_enable_autofocus_desc') .
			'</div>',
			form_hidden('enable_autofocus', 'n') .
			form_checkbox(array(
				'id'	=> 'enable_autofocus',
				'name'	=> 'enable_autofocus',
				'value'		=> 'true',
				'checked' 	=> $enable_autofocus == 'true'
			)) .
			'&nbsp;&nbsp;' .
			lang('monksel_enable', 'enable_autofocus')
		);
		
		//title field
		$title_value 		= ( ! isset($data['field_title']) OR
						$data['field_title'] == '') ? 'field_desc' : $data['field_title'];
						
		$title_custom_value 		= ( ! isset($data['field_title_text']) OR
						$data['field_title_text'] == '' OR $title_value != 'field_custom') ?
						'' :
						$data['field_title_text'];
						
		$output = form_radio(array('name' => 'field_title', 'id' => 'field_none', 'value' => 'none', 'checked' => ($title_value == 'none'), 'onclick' => "$('#field_title_custom').attr('disabled', '');"));
		$output .= NBS . NBS . lang('monksel_field_none', 'field_none') . NBS . NBS . NBS . NBS . "\n";
		
		$output .= form_radio(array('name' => 'field_title', 'id' => 'field_label', 'value' => 'field_label', 'checked' => ($title_value == 'field_label'), 'onclick' => "$('#field_title_custom').attr('disabled', '');"));
		$output .= NBS . NBS . lang('monksel_field_label', 'field_label') . NBS . NBS . NBS . NBS . "\n";
		
		$output .= form_radio(array('name' => 'field_title', 'id' => 'field_desc', 'value' => 'field_desc', 'checked' => ($title_value == 'field_desc'), 'onclick' => "$('#field_title_custom').attr('disabled', '');"));
		$output .= NBS . NBS . lang('monksel_field_desc', 'field_desc') . NBS . NBS . NBS . NBS . "\n";
		
		$output .= form_radio(array('name' => 'field_title', 'id' => 'field_custom', 'value' => 'field_custom', 'checked' => ($title_value == 'field_custom'), 'onclick' => "$('#field_title_custom').removeAttr('disabled');"));
		$output .= NBS . NBS . lang('monksel_field_custom', 'field_custom') . NBS . NBS . NBS . NBS . "\n";
		$output .= "<input type='text' name='field_title_custom' id='field_title_custom' placeholder='" . lang('monksel_field_title_custom') . "' value='$title_custom_value'";
		if ($title_value != 'field_custom') { $output .= " disabled"; }
		$output .= " />\n";
		
		ee()->table->add_row(
			lang('monksel_field_title', 'field_title') .
				'<div class="subtext">' .
					lang('monksel_field_title_desc') .
				'</div>',
			$output
		);
		
		//css class							
		$css_name 		= ( ! isset($data['css_name']) OR
						$data['css_name'] == '') ?
							'false' :
							$data['css_name'];
							
		$css_type 		= ( ! isset($data['css_type']) OR
						$data['css_type'] == '') ?
							'false' :
							$data['css_type'];
							
		$css_custom 		= ( ! isset($data['css_custom']) OR
						$data['css_custom'] == '') ?
						'' :
						$data['css_custom'];
							
		ee()->table->add_row(
			lang('monksel_css_classes', 'css_classes') .
			'<div class="subtext">' .
				lang('monksel_css_classes_desc') .
			'</div>',
			form_hidden('css_name', 'n') .
			form_checkbox(array(
				'id'	=> 'css_name',
				'name'	=> 'css_name',
				'value'		=> 'true',
				'checked' 	=> $css_name == 'true'
			)) .
			'&nbsp;&nbsp;' .
			lang('monksel_css_name', 'css_name') .
			'&nbsp;&nbsp;&nbsp;&nbsp;' .
			form_hidden('css_type', 'n') .
			form_checkbox(array(
				'id'	=> 'css_type',
				'name'	=> 'css_type',
				'value'		=> 'true',
				'checked' 	=> $css_type == 'true'
			)) .
			'&nbsp;&nbsp;' .
			lang('monksel_css_type', 'css_type') .
			'&nbsp;&nbsp;&nbsp;&nbsp;' .
			form_input(array(
				'name'		=> 'css_custom',
				'id'		=> 'css_custom',
				'value'		=> $css_custom,
				'placeholder'	=> lang('monksel_css_custom'),
				'maxlength'	=> '250',
				'size'		=> '50',
			))
		);
		
		//custom parameters
		$custom_param_1		= ( ! isset($data['custom_param_1']) OR
						$data['custom_param_1'] == '') ?
							'' :
							$data['custom_param_1'];
							
		$custom_value_1		= ( ! isset($data['custom_value_1']) OR
						$data['custom_value_1'] == '') ?
							'' :
							$data['custom_value_1'];					
							
		$custom_param_2		= ( ! isset($data['custom_param_2']) OR
						$data['custom_param_2'] == '') ?
							'' :
							$data['custom_param_2'];
		
		$custom_value_2		= ( ! isset($data['custom_value_2']) OR
						$data['custom_value_2'] == '') ?
							'' :
							$data['custom_value_2'];
												
		$custom_param_3		= ( ! isset($data['custom_param_3']) OR
						$data['custom_param_3'] == '') ?
							'' :
							$data['custom_param_3'];
		
		$custom_value_3		= ( ! isset($data['custom_value_3']) OR
						$data['custom_value_3'] == '') ?
							'' :
							$data['custom_value_3'];
												
		$custom_param_4		= ( ! isset($data['custom_param_4']) OR
						$data['custom_param_4'] == '') ?
							'' :
							$data['custom_param_4'];
		
		$custom_value_4		= ( ! isset($data['custom_value_4']) OR
						$data['custom_value_4'] == '') ?
							'' :
							$data['custom_value_4'];
												
		$custom_param_5		= ( ! isset($data['custom_param_5']) OR
						$data['custom_param_5'] == '') ?
							'' :
							$data['custom_param_5'];					
		
		$custom_value_5		= ( ! isset($data['custom_value_5']) OR
						$data['custom_value_5'] == '') ?
							'' :
							$data['custom_value_5'];	
		
		ee()->table->add_row(
			lang('monktext_custom_params', 'custom_params') .
			'<div class="subtext">' .
				lang('monktext_custom_params_desc') .
			'</div>',
			'<p>'.
			form_input(array(
				'name'		=> 'custom_param_1',
				'id'		=> 'custom_param_1',
				'value'		=> $custom_param_1,
				'placeholder'	=> lang('monktext_custom_place'),
				'maxlength'	=> '250',
				'size'		=> '50',
			)) . '&nbsp;&nbsp;&nbsp;&nbsp;' .
			form_input(array(
				'name'		=> 'custom_value_1',
				'id'		=> 'custom_value_1',
				'value'		=> $custom_value_1,
				'placeholder'	=> lang('monktext_custom_value'),
				'maxlength'	=> '250',
				'size'		=> '50',
			)) . '</p><p>' .
			form_input(array(
				'name'		=> 'custom_param_2',
				'id'		=> 'custom_param_2',
				'value'		=> $custom_param_2,
				'placeholder'	=> lang('monktext_custom_place'),
				'maxlength'	=> '250',
				'size'		=> '50',
			)) . '&nbsp;&nbsp;&nbsp;&nbsp;' .
			form_input(array(
				'name'		=> 'custom_value_2',
				'id'		=> 'custom_value_2',
				'value'		=> $custom_value_2,
				'placeholder'	=> lang('monktext_custom_value'),
				'maxlength'	=> '250',
				'size'		=> '50',
			)). '</p><p>' .
			form_input(array(
				'name'		=> 'custom_param_3',
				'id'		=> 'custom_param_3',
				'value'		=> $custom_param_3,
				'placeholder'	=> lang('monktext_custom_place'),
				'maxlength'	=> '250',
				'size'		=> '50',
			)) . '&nbsp;&nbsp;&nbsp;&nbsp;' .
			form_input(array(
				'name'		=> 'custom_value_3',
				'id'		=> 'custom_value_3',
				'value'		=> $custom_value_3,
				'placeholder'	=> lang('monktext_custom_value'),
				'maxlength'	=> '250',
				'size'		=> '50',
			)). '</p><p>' .
			form_input(array(
				'name'		=> 'custom_param_4',
				'id'		=> 'custom_param_4',
				'value'		=> $custom_param_4,
				'placeholder'	=> lang('monktext_custom_place'),
				'maxlength'	=> '250',
				'size'		=> '50',
			)) . '&nbsp;&nbsp;&nbsp;&nbsp;' .
			form_input(array(
				'name'		=> 'custom_value_4',
				'id'		=> 'custom_value_4',
				'value'		=> $custom_value_4,
				'placeholder'	=> lang('monktext_custom_value'),
				'maxlength'	=> '250',
				'size'		=> '50',
			)). '</p><p>' .
			form_input(array(
				'name'		=> 'custom_param_5',
				'id'		=> 'custom_param_5',
				'value'		=> $custom_param_5,
				'placeholder'	=> lang('monktext_custom_place'),
				'maxlength'	=> '250',
				'size'		=> '50',
			)) . '&nbsp;&nbsp;&nbsp;&nbsp;' .
			form_input(array(
				'name'		=> 'custom_value_5',
				'id'		=> 'custom_value_5',
				'value'		=> $custom_value_5,
				'placeholder'	=> lang('monktext_custom_value'),
				'maxlength'	=> '250',
				'size'		=> '50',
			)) . '</p>'
		);
		
		//javascript
		$js_confirm	= ( ! isset($data['js_confirm']) OR
						$data['js_confirm'] == '') ?
							'false' :
							$data['js_confirm'];
							
		$js_event 	= isset($data['js_event']) ?
							$data['js_event'] :
							'none';
							
		$js_action 		= ( ! isset($data['js_action']) OR
						$data['js_action'] == '') ?
						'' :
						$data['js_action'];
		
		$output = "<select name=\"js_event\" id=\"js_event\"";
		if ($js_confirm == "false") { $output .= ' disabled'; }
		$output .= ">\n";
		foreach ($this->javascript_events as $event)
		{
			$output .= "<option value=\"" . $event . "\"";
			if ($js_confirm == "true") { if ($js_event == $event) { $output .= " selected"; } }
			$output .= ">" . $event . "</option>\n";
		}
		$output .= "</select>\n";
		
		$text_field = array(
			'name'		=> 'js_action',
			'id'		=> 'js_action',
			'value'		=> $js_action,
			'placeholder'	=> lang('monksel_js_action'),
			'maxlength'	=> '250',
			'size'		=> '50'
		);
		
		if ($js_confirm == "false") { $text_field['disabled'] = ''; }
		
		ee()->table->add_row(
			lang('monksel_js_event', 'js_event') .
				'<div class="subtext">' .
					lang('monksel_js_event_desc') .
				'</div>',
			form_hidden('js_confirm', 'n') .
			form_checkbox(array(
				'id'	=> 'js_confirm',
				'name'	=> 'js_confirm',
				'value'		=> 'true',
				'checked' 	=> $js_confirm == 'true',
				'onclick'	=> "$('#js_event, #js_action').attr('disabled',!$('#js_event').attr('disabled'))"
			)) .
			'&nbsp;&nbsp;' .
			lang('monksel_js_confirm', 'js_confirm') .
			'<br /><br />' .
			$output .
			'&nbsp;&nbsp;&nbsp;&nbsp;' .
			form_input($text_field)
		);
	}
	//END display_settings


	// --------------------------------------------------------------------

	/**
	 * Validate Field Settings
	 *
	 * @access	public
	 * @param	array
	 * @return	bool
	 */

	public function validate_settings ($data = array())
	{
		return $this->validate_multi_item_row_settings($data);
	}
	//END validate_settings


	// --------------------------------------------------------------------

	/**
	 * Save Field Settings
	 *
	 * @access	public
	 * @return	string
	 */

	public function save_settings ($data = array())
	{		
		$settings = $this->save_multi_item_row_settings($data);
		
		$field_title = ee()->input->get_post('field_title');
		if ($field_title == 'field_label') {
			$title = ee()->input->get_post('field_label'); 
		} else if ($field_title == 'field_desc') {
			$title = ee()->input->get_post('field_description'); 
		} else if ($field_title == 'field_custom') {
			$title = ee()->input->get_post('field_title_custom');
		} else {
			$title = '';
		}
		
		$settings['blank_line'] = ee()->input->get_post('blank_line') == 'n' ? 'n' : 'y';
		$settings['default_value'] = ee()->input->get_post('default_value');
		$settings['enable_autofocus'] = ee()->input->get_post('enable_autofocus') == 'n' ? 'false' : 'true';
		$settings['field_title'] = ee()->input->get_post('field_title');
		$settings['field_title_text'] = $title;
		$settings['css_name'] = ee()->input->get_post('css_name') == 'n' ? 'false' : 'true';
		$settings['css_type'] = ee()->input->get_post('css_type') == 'n' ? 'false' : 'true';
		$settings['css_custom'] = ee()->input->get_post('css_custom');
		$settings['custom_param_1'] = ee()->input->get_post('custom_param_1');
		$settings['custom_value_1'] = ee()->input->get_post('custom_value_1');
		$settings['custom_param_2'] = ee()->input->get_post('custom_param_2');
		$settings['custom_value_2'] = ee()->input->get_post('custom_value_2');
		$settings['custom_param_3'] = ee()->input->get_post('custom_param_3');
		$settings['custom_value_3'] = ee()->input->get_post('custom_value_3');
		$settings['custom_param_4'] = ee()->input->get_post('custom_param_4');
		$settings['custom_value_4'] = ee()->input->get_post('custom_value_4');
		$settings['custom_param_5'] = ee()->input->get_post('custom_param_5');
		$settings['custom_value_5'] = ee()->input->get_post('custom_value_5');
		$settings['js_confirm'] = ee()->input->get_post('js_confirm') == 'n' ? 'false' : 'true';
		$settings['js_event'] = ee()->input->get_post('js_event');
		$settings['js_action'] = ee()->input->get_post('js_action');
		
		return $settings;
	}
	//END save_settings


	// --------------------------------------------------------------------

	/**
	 * Display Field
	 *
	 * @access	public
	 * @param	string 	saved data input
	 * @param  	array 	input params from tag
	 * @param 	array 	attribute params from tag
	 * @return	string 	display output
	 */

	public function display_field ($data = '', $params = array(), $attr = array())
	{
		if ($this->settings['blank_line'] == 'y') {
			$list_items = array(' ' => '');
			$list_items_temp = $this->get_field_options();
			foreach ($list_items_temp as $key => $value) {
				$list_items[$key] = $value;
			}
		} else {
			$list_items = $this->get_field_options();
		}
		
		$data = $this->prep_multi_item_data($data);
		$data = isset($data[0]) ? $data[0] : '';
		if ($data == '' and !isset($params['default_value'])) { $data = $this->settings['default_value']; }		
		
		$field_options['id'] = 'freeform_' . $this->field_name;
		if ($this->settings['enable_autofocus'] == 'true') { $field_options['autofocus'] = ''; }
		if ($this->settings['field_title_text'] != '') { $field_options['title'] = $this->settings['field_title_text']; }
		
		if ($this->settings['css_name'] == "true" or $this->settings['css_type'] == "true" or $this->settings['css_custom'] != '') {
			$class = '';
			if ($this->settings['css_name'] == "true") { 
				$class .= $this->field_name; 
			}
			if ($this->settings['css_type'] == "true") {
				if (isset($class) and $class != '') { $class .= ' '; }
				$class .= lang('monksel_default_field_type'); }
			if ($this->settings['css_custom']!= '') {
				if (isset($class) and $class != '') { $class .= ' '; }
				$class .= $this->settings['css_custom'];
			}
			$field_options['class'] = $class;
		}
		
		if ($this->settings['custom_param_1'] != '') { $field_options[$this->settings['custom_param_1']] = $this->settings['custom_value_1']; }
		if ($this->settings['custom_param_2'] != '') { $field_options[$this->settings['custom_param_2']] = $this->settings['custom_value_2']; }
		if ($this->settings['custom_param_3'] != '') { $field_options[$this->settings['custom_param_3']] = $this->settings['custom_value_3']; }
		if ($this->settings['custom_param_4'] != '') { $field_options[$this->settings['custom_param_4']] = $this->settings['custom_value_4']; }
		if ($this->settings['custom_param_5'] != '') { $field_options[$this->settings['custom_param_5']] = $this->settings['custom_value_5']; }
		
		if ($this->settings['js_confirm'] == "true" and $this->settings['js_event'] != "none" and $this->settings['js_action'] != '') { $field_options[$this->settings['js_event']] = stripslashes($this->settings['js_action']); }

		return form_dropdown(
			$this->field_name,
			$list_items,
			(array_key_exists(form_prep($data), $list_items) ? form_prep($data) : NULL),
			$this->stringify_attributes(array_merge(
				$field_options,
				$attr
			))
		);
	}
	//END display_field


	// --------------------------------------------------------------------

	/**
	 * Replace Tag
	 *
	 * @access	public
	 * @param	string 	data
	 * @param 	array 	params from tag
 	 * @param 	string 	tagdata if there is a tag pair
	 * @return	string
	 */

	public function replace_tag ($data, $params = array(), $tagdata = FALSE)
	{
		return $this->multi_item_replace_tag($data, $tagdata, FALSE, $params);
	}
	//END replace tag


	// --------------------------------------------------------------------

	/**
	 * display_email_data
	 *
	 * formats data for email notifications
	 *
	 * @access	public
	 * @param 	string 	data from table for email output
	 * @param 	object 	instance of the notification object
	 * @return	string 	output data
	 */

	public function display_email_data ($data, $notification_obj = null)
	{
		return $this->encode_ee(
			str_replace('<br/>', "\n", entities_to_ascii($this->replace_tag($data)))
		);
	}
	//END display_email_data


	// --------------------------------------------------------------------

	/**
	 * Display Entry in the CP
	 *
	 * formats data for cp entry
	 *
	 * @access	public
	 * @param 	string 	data from table for email output
	 * @return	string 	output data
	 */

	public function display_entry_cp ($data)
	{
		return $this->display_multi_item_output($data);
	}
	//END display_entry_cp


	// --------------------------------------------------------------------

	/**
	 * validate
	 *
	 * @access	public
	 * @param	string 	input data from post to be validated
	 * @return	bool
	 */

	public function validate ($data)
	{
		// we are OK with blank
		if ( ! $data or trim($data) == '')
		{
			return TRUE;
		}

		return array_key_exists($data, $this->get_field_options(FALSE, FALSE));
	}
	//END validate


	// --------------------------------------------------------------------

	/**
	 * Save Field Data
	 *
	 * @access	public
	 * @param	string 	data to be inserted
	 * @param	int 	form id
	 * @return	string 	data to go into the entry_field
	 */

	public function save ($data)
	{
		return $this->save_multi_item($data);
	}
	//END save


	// --------------------------------------------------------------------

	/**
	 * Export
	 *
	 * @access	public
	 * @param	string 	data to be exported
	 * @return	string 	data to go into the export
	 */

	public function export ($data = '', $export_type = '')
	{
		return $this->display_multi_item_output($data, FALSE);
	}
	//END export
}
//END class Monkee_select_freeform_ft
