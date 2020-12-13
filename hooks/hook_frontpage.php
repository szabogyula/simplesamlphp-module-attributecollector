<?php

/**
 * Hook to add the modinfo module to the frontpage.
 *
 * @param array &$links The links on the frontpage, split into sections.
 */
function attributecollector_hook_frontpage(&$links)
{
    assert('is_array($links)');
    assert('array_key_exists("links", $links)');

    $links['config']['attributecollector'] = array(
	'href' => SimpleSAML_Module::getModuleURL('attributecollector/testcollector.php'),
        'text' => 'AttributeCollector module tester',
        );
}
