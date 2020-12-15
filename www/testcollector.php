<?php
/**
 */

require_once('_include.php');

SimpleSAML\Utils\Auth::requireAdmin();
SimpleSAML\Logger::info('SAML2.0 - AttributeCollector module test endpoint');

$config = SimpleSAML\Configuration::getInstance();
$configauthprocs = [
    $config->getArray('authproc.aa', NULL),
    $config->getArray('authproc.sp', NULL),
    $config->getArray('authproc.idp', NULL),
    ];

// test only the first collector in authproces
foreach ($configauthprocs as $configauthproc) { 
  foreach($configauthproc as $filter) {
    if ($filter['class'] == 'attributecollector:AttributeCollector') {
      $collectorFilterConfig = $filter['collector'];
      break 2;
    }
  }
}

if (!$collectorFilterConfig) {
  throw new SimpleSAML\Error\Exception('There is no collector in authproces.');
}

if ($collectorFilterConfig['class'] != 'attributecollector:SQLCollector') {
  throw new SimpleSAML\Error\Exception('The tester works with only sqlcollector. Sorry.');
}

$sqlCollector = new sspmod_attributecollector_Collector_SQLCollector($collectorFilterConfig);

$uid = $_POST['uid'];

if ($uid && !preg_match('/^[a-zA-Z0-9_]*$/',$uid)) {
 throw new SimpleSAML\Error\Exception('UID is not valid');
}

$result = $sqlCollector->getAttributes(['AatId' => [$uid]], 'AatId');

$attributes = $result;

$t = new SimpleSAML\XHTML\Template($config, 'attributecollector:status.php', 'attributes');

$t->data['query'] = $collectorFilterConfig['query'];
$t->data['attributes'] = $attributes;
$t->show();

assert('FALSE');
