<?php
namespace ProcessWire;

/**
 * Configuration class for HannaCodeDialogTiny module
 */
class HannaCodeDialogTinyConfig extends ModuleConfig
{

    /**
     * Get default configuration values
     *
     * @return array
     */
    public function getDefaults()
    {
        return [
            'auto_add_toolbar' => 0,
            'exclude_prefix' => '_',
            'exclude_selection' => [],
        ];
    }

    /**
     * Get configuration inputfields
     *
     * @return InputfieldWrapper
     */
    public function getInputfields()
    {
        $inputfields = parent::getInputfields();
        $modules = $this->wire()->modules;

        // Auto-manage Hanna Code Integration
        $f = $modules->InputfieldCheckbox;
        $f->name = 'auto_add_toolbar';
        $f->label = $this->_('Auto-manage Hanna Code Integration');
        $f->description
            = $this->_("When enabled, this module automatically manages the 'hannacode' toolbar button and plugin activation based on whether 'TextformatterHannaCode' is enabled for each TinyMCE field.");
        $f->notes
            = $this->_('When you add or remove the TextformatterHannaCode from a field, both the toolbar button and the external plugin will be automatically added or removed. This sync happens when saving a field or when saving this module configuration.');
        $f->attr('checked', $this->auto_add_toolbar ? 'checked' : '');
        $inputfields->add($f);

        // Manual Setup Instructions
        $f = $modules->InputfieldMarkup;
        $f->label = $this->_('Manual Setup Instructions');
        $f->value = '<p>' . $this->_('If you prefer to manage the integration manually for each field:') . '</p>' .
            '<ol>' .
            '<li>' . $this->_('Go to <strong>Setup → Fields</strong> and edit your TinyMCE field') . '</li>' .
            '<li>' . $this->_('In the <strong>Details</strong> tab, add <code>TextformatterHannaCode</code> to <strong>Text Formatters</strong>') . '</li>' .
            '<li>' . $this->_('In the <strong>Input</strong> tab, scroll to <strong>Toolbar</strong> and add <code>hannacode</code>') . '</li>' .
            '<li>' . $this->_('Scroll to <strong>External plugins to enable</strong> and check <code>HannaCodeDialogTiny</code>') . '</li>' .
            '<li>' . $this->_('Save the field') . '</li>' .
            '</ol>';
        $f->icon = 'info-circle';
        $f->collapsed = Inputfield::collapsedYes;
        $inputfields->add($f);

        // Exclude prefix
        $f = $modules->InputfieldText;
        $f->name = 'exclude_prefix';
        $f->label = $this->_('Exclude prefix');
        $f->description = $this->_('Tags starting with this prefix will be hidden.');
        $f->value = $this->exclude_prefix ?: '_';
        $inputfields->add($f);

        // Exclude selection
        $f = $modules->InputfieldAsmSelect;
        $f->name = 'exclude_selection';
        $f->label = $this->_('Exclude Hanna tags');
        $f->description = $this->_('Select specific tags to hide.');

        $hanna = $modules->get('TextformatterHannaCode');
        if ($hanna && method_exists($hanna, 'hannaCodes')) {
            foreach ($hanna->hannaCodes()->getAll() as $hc) {
                $f->addOption($hc->name);
            }
        }

        $f->value = $this->exclude_selection;
        $inputfields->add($f);

        return $inputfields;
    }
}
