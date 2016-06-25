<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * The main acumulus configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_acumulus
 * @copyright  2015 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

//Path
require_once($CFG->dirroot.'/mod/acumulus/moodleform_mod.php');
require_once($CFG->libdir.'/moodlelib.php');
require_once($CFG->libdir.'/weblib.php');
/**
 * Module instance settings form
 *
 * @package    mod_acumulus
 * @copyright  2015 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_acumulus_mod_form extends acumulus_needs_form {

    /**
     * Defines forms elements
     */
	public function definition() {
		global $CFG;

	
		if((empty($CFG->i_initials) || empty($CFG->i_moodle_url)) || (empty($CFG->i_key) || empty($CFG->i_acumulus_url))){
			echo "Por favor, preencha as configurações do plugin.";
		
			sleep(2);
			header("Location: ./../admin/settings.php?section=modsettingacumulus");
			die();
		}
		
        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('geral', 'acumulus'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('titulodotrabalho', 'acumulus'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'titulodotrabalho', 'acumulus');

        // Adding the standard "intro" and "introformat" fields.
        if ($CFG->branch >= 29) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }

        // Adding the rest of acumulus settings, spreading all them into this fieldset
        // ... or adding more fieldsets ('header' elements) if needed for better logic.
//        $mform->addElement('static', 'label1', 'acumulussetting1', 'Your acumulus fields go here. Replace me!');

//        $mform->addElement('header', 'acumulusfieldset', get_string('acumulusfieldset', 'acumulus'));
//        $mform->addElement('static', 'label2', 'acumulussetting2', 'Your acumulus fields go here. Replace me!');

        //Each of these fields need to have a column on "mdl_acumulus" table
        //addElement('date_time_selector',string $field-column-name, string $label, array('optional'=>true));
        
        // assignment dates
        $mform->addElement('header', 'configdatas', get_string('configdatas', 'acumulus'));
        $mform->addElement('date_time_selector', 'timeavailable', get_string('availabledate', 'acumulus'), array('optional'=>true));
        $mform->setDefault('timeavailable', time());
        $mform->addElement('date_time_selector', 'timedue', get_string('duedate', 'acumulus'), array('optional'=>true));
        $mform->setDefault('timedue', time()+7*24*3600);
               
        
        
        
        
        
        
        // Add standard grading elements.
        $this->standard_grading_coursemodule_elements();

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();
                
        // Add Acumulus hidden IE initials
		//$initials = get_config('mod_acumulus');
		
		//var_dump($CFG);
		//echo "<br>";
        //$mform->addElement('hidden', 'initials', $CFG->sigla_instituicao);
		//$mform->addElement('hidden', 'key', $CFG->chave_instituicao);
        
        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
        
        //$id = required_param('id', PARAM_INT);
        //var_dump($DB);
    }
    
    /**
     * Performs operations on Acumulus' server using HTTP protocol
     * If there are errors return array of errors ("fieldname"=>"error message"),
     * otherwise true if ok.
     *
     * Server side rules do not work for uploaded files, implement serverside rules here if needed.
     *
     * @param array $data array of ("fieldname"=>value) of submitted data
     * @param array $files array of uploaded files "element_name"=>tmp_file_path
     * @return array of "element_name"=>"error_description" if there are errors,
     *         or an empty array if everything is OK (true allowed for backwards compatibility too).
     */
    function validation($data, $files) {
    	$errors = parent::validation($data, $files);
    		
    	/**
    	 * 

	        $mform = $this->_form;
	    	$data = $mform->exportValues();
	    	
	    	// redirect when submit
	    	if(array_key_exists("submitbutton", $data)){
	    		
	    		$add    = optional_param('add', '', PARAM_ALPHA);     // module name
	
	    		$data = (object) $mform->exportValues();
	    		
	    		if(!empty($add)){
	    			//This is a trick
	    			//It inserts a new activity in order to get the last id
	    			$lastid = add_course_module($data);
	    			//Then it deletes that line because modedit.php will insert the same line
	    			delete_course_module($lastid);
	    			
					//Set coursemodule value (id)
	    			$data->coursemodule = ++$lastid;    			    			
	    		}
	    		
	    		// encode $data in order to avoid tumpering
				$encodeddata=base64_encode(json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE));
			
	    		// set Acumulus URL to save activity 
	    		$url = "http://localhost/Dropbox/acumulus/moodle/activity.php?data=$encodeddata";
	    		
	    		redirect($url, "What's going on?");
	    		
	    		//TODO: return $url and set header somewhere else????????
	    	}
			
    	 */
	    	return $errors;
	    }
    	
        
        
    
    /**
     * This method is called after definition(), data submission and set_data().
     * All form setup that is dependent on form values should go in here.
     */
    function definition_after_data(){
    	global $CFG, $DB;

     
	}
}
