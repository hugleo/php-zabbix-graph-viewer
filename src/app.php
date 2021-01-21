<?php
    require_once __DIR__.'/lib/vendor/autoload.php';
    use ZabbixApi\ZabbixApi;
    use ZabbixApi\Exception;

    $zabbixUrl = 'http://yourdomain/zabbix';

    $hostgroup = '';
    $hostname = '';
    $graphname = '';
  
    $decoded = base64_decode($_COOKIE['zbx_session']);
    $ar = json_decode($decoded, true);    
    $zbx_sessionid = $ar['sessionid'];    

    foreach ($_GET as $key => $value) {
        if ($key == 'hostgroup') {
			if (!empty($value)) {
				$hostgroup = $value;
            }
        }
        elseif ($key == 'hostname') {
            if (!empty($value)) {
                $hostname = $value;
			}
		}
    }

    $api = new ZabbixApi($zabbixUrl . '/api_jsonrpc.php', '', '', '', '', $zbx_sessionid);
    $api->setDefaultParams(array(
        'output' => 'extend',
        'real_hosts' => 'true'
    ));

    if (empty($hostgroup)) {
        $hostgroups = $api->hostgroupGet(array(
            'sortfield' => 'name'
        ));

        $arr = array();
        foreach ($hostgroups as $hg) {
            $item = new stdClass;
            $item->label = $hg->name;
            $item->value = $hg->groupid;
        $arr[] = $item;
        }

        echo json_encode($arr);
    }
    elseif (empty($hostname)) {
        $hosts = $api->hostGet(array(
        'sortfield' => 'host',
        'groupids' => array($hostgroup)
        ));

        $arr = array();
        foreach ($hosts as $h) {
            $item = new stdClass;
            $item->label = $h->host;
            $item->value = $h->hostid;
            $arr[] = $item;
        }

        echo json_encode($arr);
    }
    elseif (empty($graphname)) {
        $graphs = $api->graphGet(array(
            'sortfield' => 'name',
            'hostids' => array($hostname)
        ));

        $arr = array();
        foreach ($graphs as $g) {
            $item = new stdClass;
            $item->label = $g->name;
            $item->value = $g->graphid;
            $arr[] = $item;
        }

        echo json_encode($arr);
    }

?>
