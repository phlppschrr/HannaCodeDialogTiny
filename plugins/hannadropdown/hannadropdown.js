/**
 * hannadropdown plugin for TinyMCE
 */
tinymce.PluginManager.add('hannadropdown', function (editor, url) {

    // Add custom icon
    editor.ui.registry.addIcon('hcdtdd', '<img style="width:24px; height: 24px;" src="' + hcdt_config.dropdown_icon + '" />');

    editor.ui.registry.addMenuButton('hannadropdown', {
        text: hcdt_config.dropdown_title,
        icon: 'hcdtdd',
        fetch: function (callback) {
            var hannaTags = hcdt_config.hanna_tags;
            var items = [];
            var sortedKeys = Object.keys(hannaTags).sort();

            for (var i = 0; i < sortedKeys.length; i++) {
                (function (tagKey) {
                    var tagDefinition = hannaTags[tagKey];
                    items.push({
                        type: 'menuitem',
                        text: tagKey,
                        onAction: function () {
                            // Insert directly if no attributes are defined
                            if (!tagDefinition.has_attrs) {
                                editor.insertContent(hcdt_config.open_tag + tagKey + hcdt_config.close_tag);
                            } else {
                                // Open dialog if attributes exist
                                editor.execCommand('mceHannaDialog', false, {
                                    tag: tagKey,
                                    code: null
                                });
                            }
                        }
                    });
                })(sortedKeys[i]);
            }
            callback(items);
        }
    });

    return { getMetadata: function () { return { name: 'Hanna Dropdown' }; } };
});