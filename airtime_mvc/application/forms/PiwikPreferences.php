<?php
class Application_Form_PiwikPreferences extends Zend_Form_SubForm
{
    public function init()
    {
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'form/preferences_piwik.phtml'))
        ));
        
        //piwik url
        $this->addElement('text', 'PiwikSiteUrl', array(
            'class'      => 'input_text',
            'label'      => _('Piwik Site Url: (http://example.org:8080/)'),
            'filters'    => array('StringTrim'),
            'autocomplete' => 'off',
            'value' => Application_Model_Preference::GetPiwikSiteUrl(),
            'decorators' => array(
                'ViewHelper'
            ),
            'allowEmpty' => false,
            'validators' => array(
                new ConditionalNotEmpty(array('UsePiwik'=>'1'))
                #new Application_Form_Helper_ValidationTypes::overrideRegexValidator("/^[0-2]?[0-9]:[0-5][0-9]$/",_("'%value%' does not fit the time format 'HH:mm'"))

            )
        ));

        //enable piwik integration
        $this->addElement('checkbox', 'UsePiwik', array(
            'label'      => _('Enable Piwik for Advanced Listener Stats'),
            'required'   => false,
            'value' => Application_Model_Preference::GetPiwik(),
            'decorators' => array(
                'ViewHelper'
            )
        ));

        //piwik idsite
        $this->addElement('text', 'PiwikSiteId', array(
            'class'      => 'input_text',
            'label'      => _('Piwik Site Id'),
            'filters'    => array('StringTrim'),
            'autocomplete' => 'off',
            'value' => Application_Model_Preference::GetPiwikSiteId(),
            'decorators' => array(
                'ViewHelper'
            ),
            'allowEmpty' => false,
            'validators' => array(
                new ConditionalNotEmpty(array('UsePiwik'=>'1'))
            )
        ));

        //piwik token
        $this->addElement('text', 'PiwikToken', array(
            'class'      => 'input_text',
            'label'      => _('Piwik API Token'),
            'filters'    => array('StringTrim'),
            'autocomplete' => 'off',
            'value' => Application_Model_Preference::GetPiwikToken(),
            'decorators' => array(
                'ViewHelper'
            ),
            'allowEmpty' => false,
            'validators' => array(
                new ConditionalNotEmpty(array('UsePiwik'=>'1'))
            )
        ));
    }
}
?>
