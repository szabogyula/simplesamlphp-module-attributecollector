<?php
/**
 */

require_once('_include.php');

SimpleSAML\Utils\Auth::requireAdmin();

SimpleSAML\Logger::info('SAML2.0 - AttributeCollector module test endpoint');


$config = SimpleSAML\Configuration::getInstance();
$configauthproc = $config->getArray('authproc.aa', NULL);

foreach($configauthproc as $filter) {
  if ($filter['class'] == 'attributecollector:AttributeCollector') {
    $collectorFilterConfig = $filter['collector'];
    break;
  }
}

$sqlCollector = new sspmod_attributecollector_Collector_SQLCollector($collectorFilterConfig);

$uid = $_POST['uid'];

if ($uid && !preg_match('/^[a-zA-Z0-9_]*$/',$uid)) {
 throw new Exception('UID is not valid');
}

$result = $sqlCollector->getAttributes(['AatId' => [$uid]], 'AatId');

$attributes = $result;

$t = new \SimpleSAML\XHTML\Template($config, 'attributecollector:status.php', 'attributes');

$t->data['query'] = $collectorFilterConfig['query'];
$t->data['attributes'] = $attributes;
$t->show();

assert('FALSE');
