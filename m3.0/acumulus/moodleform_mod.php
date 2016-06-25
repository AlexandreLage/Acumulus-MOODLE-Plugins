<?php

//include class "moodleform_mod" and extend it in order to override the function "add_action_buttons()"


require_once($CFG->dirroot.'/course/moodleform_mod.php');

abstract class acumulus_needs_form extends moodleform_mod {
	
    /**
     * Overriding course/moodleform_mod.php's add_action_buttons() method, to add an extra submit "save changes and return" button.
     *
     * @param bool $cancel show cancel button
     * @param string $submitlabel null means default, false means none, string is label text
     * @param string $submit2label  null means default, false means none, string is label text
     * @return void
     */
    function add_action_buttons($cancel=true, $submitlabel=null, $submit2label=null) {
        if (is_null($submitlabel)) {
            $submitlabel = get_string('savechangesanddisplay');
        }

        $mform = $this->_form;

        // elements in a row need a group
        $buttonarray = array();

        if ($submitlabel !== false) {
            $buttonarray[] = &$mform->createElement('submit', 'submitbutton', $submitlabel);
        }

        if ($cancel) {
            $buttonarray[] = &$mform->createElement('cancel');
        }

        //echo $mform->getElementValue('name');
        //echo "This is the new add_action_buttuns() function.";
            
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->setType('buttonar', PARAM_RAW);
        $mform->closeHeaderBefore('buttonar');
    }
}

