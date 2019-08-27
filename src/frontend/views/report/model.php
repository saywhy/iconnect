<?php

$header = '<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
	<!--[if gte mso 9]><xml>
 <w:WordDocument>
  <w:View>Print</w:View>
  </w:WordDocument>
</xml><![endif]-->
</head>
<body>';
$footer = '</body></html>';
$content = '';
$content .= '<div style="width:100%;height:300px;text-align:center;"><img src="' . $server_ip . '/images/model-img.png" width="90" height="107.5"/></div>
    <h1 align="center" style="word-wrap:break-word; word-break:normal;">iConnect安全管理平台</h1>
    <h1 align="center" style="word-wrap:break-word; word-break:normal;">运行报告</h1>
    <h3 align="center" style="word-wrap:break-word; word-break:normal;">' . $stime . '-' . $etime . '</h3>
<h2>一、安全总览:</h2>
<p style = "text-indent:3em;word-wrap:break-word; word-break:normal;">对当前网络安全运行整体状况进行统计，提供了指定时间段内的威胁告警总数，产生告警的内网主机数量统计，以及当前网络整体安全评分。</p>';
$content .= '<table border="1" cellspacing="0" align="center">
    <thead>
        <tr>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:100px;font-size:20px;">告警数量</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:300px;font-size:20px;">风险资产</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:200px;font-size:20px;">安全评分</th>
        </tr>
    </thead>
    <tbody>
        <tr style="font-size:18px">
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $alarm_count . '</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $risk_dev_count . '</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $safety_score . '</th>
        </tr>
    </tbody>
    </table>';
$content .= '<h2>二、威胁状况:</h2>
    <h3>2.1 风险资产top10（内网主机）</h3>
    <p style = "text-indent:3em;word-wrap:break-word; word-break:normal; ">统计指定时间段内网络中风险评估靠前的10台机器，以了解网络内风险资产状况。</p>';
$content .= '<table border="1" cellspacing="0" align="center">
    <thead>
        <tr>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:100px;font-size:20px;">排名</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:300px;font-size:20px;">资产</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:200px;font-size:20px;">风险度</th>
        </tr>
    </thead>
    <tbody>';
foreach ($risk_assets as $key => $value) {
    $content .= '<tr align="center" style="font-size:18px">
    <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . ($key + 1) . '</th>
    <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['client_ip'] . '</th>
    <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['risk_assets_count'] . '</th>
    </tr >';
}
$content .= '</tbody>
    </table>';
$content .= '
    <h3>2.2 威胁等级及数量</h3>
    <p style = "text-indent:3em;word-wrap:break-word; word-break:normal; ">按照威胁程度不同，分高、中、低三个等级分类展示威胁告警以及数量。</p>';
$content .= '<table border="1" cellspacing="0" align="center">
    <thead>
        <tr>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:100px;font-size:20px;">序号</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:300px;font-size:20px;">威胁等级</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:200px;font-size:20px;">数量</th>
        </tr>
    </thead>
    <tbody>';
foreach ($threat_level as $key => $value) {
    $content .= '<tr align="center" style="font-size:18px">
    <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . ($key + 1) . '</th>
    <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['degree'] . '</th>
    <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['total_count'] . '</th>
    </tr >';
}
$content .= '</tbody>
    </table>';
$content .= '<h3>2.3 威胁类型及数量</h3>
    <p style = "text-indent:3em;word-wrap:break-word; word-break:normal; ">按照不同的威胁指标对告警进行分类，以了解存在的威胁类型和数量。</p>';
$content .= '<table border="1" cellspacing="0" align="center">
    <thead>
        <tr>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:100px;font-size:20px;">序号</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:300px;font-size:20px;">威胁等级</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:200px;font-size:20px;">数量</th>
        </tr>
    </thead>
    <tbody>';
foreach ($threat_type as $key => $value) {
    $content .= '<tr align="center" style="font-size:18px">
    <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . ($key + 1) . '</th>
    <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['category'] . '</th>
    <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['total_count'] . '</th>
    </tr >';
}
$content .= '</tbody>
    </table>';
$content .= '<h3>2.4 最新告警</h3>
    <p style = "text-indent:3em;word-wrap:break-word; word-break:normal; ">下面列出在指定时间段内产生的最后10条告警，了解网络面临的最新威胁。</p>';

$content .= '<table border="1" cellspacing="0" align="center">
    <thead>
        <tr>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:100px;font-size:20px;">序号</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:150px;font-size:20px;">威胁等级</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:200px;font-size:20px;">威胁类型</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:200px;font-size:20px;">告警时间</th>
        </tr>
    </thead>
    <tbody>';
foreach ($last_alert as $key => $value) {
    $content .= '
        <tr align="center" style="font-size:18px">
            <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . ($key + 1) . '</th>
            <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['degree'] . '</th>
            <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['type'] . '</th>
            <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['time'] . '</th>
        </tr >';
}
$content .= '</tbody>
    </table>';
$content .= '<h2>三、安全设备状况:</h2>
    <h3>3.1 安全设备运行状况</h3>
    <p style = "text-indent:3em;word-wrap:break-word; word-break:normal; ">下表列出被管理的安全设备、当前在线状态以及在指定的时间段内曾经离线的情况详情，以了解安全设备整体运行状态。</p>';
$content .= '<table border="1" cellspacing="0" align="center">
    <thead>
        <tr>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:100px;font-size:20px;">序号</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:300px;font-size:20px;">安全设备</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:200px;font-size:20px;">日志信息</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:200px;font-size:20px;">产生时间</th>
        </tr>
    </thead>
    <tbody>';
foreach ($device_logs as $key => $value) {
    $content .= '
        <tr align="center" style="font-size:18px">
            <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . ($key + 1) . '</th>
            <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['device_name'] . '</th>
            <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['info'] . '</th>
            <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['created_at'] . '</th>
        </tr >';
}
$content .= '</tbody>
    </table>';
$content .= '
    <h3>3.2 安全设备发送日志量统计</h3>
    <p style = "text-indent:3em;word-wrap:break-word; word-break:normal; ">针对被管理安全设备统计在指定时间段内发送到平台的日志数量，以了解和掌握安全设备日志外发情况。</p>';
$content .= '<table border="1" cellspacing="0" align="center">
    <thead>
        <tr>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:100px;font-size:20px;">序号</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:300px;font-size:20px;">安全设备</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:200px;font-size:20px;">日志量</th>
        </tr>
    </thead>
    <tbody>';
foreach ($device_send_logs as $key => $value) {
    $content .= '<tr align="center" style="font-size:18px">
    <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . ($key + 1) . '</th>
    <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['dev_name'] . '</th>
    <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['logs_count'] . '</th>
    </tr >';
}
$content .= '</tbody>
    </table>';
$content .= '
    <h3>3.3 产生告警安全设备及告警数量统计</h3>
    <p style = "text-indent:3em;word-wrap:break-word; word-break:normal; ">针对被管理安全设备统计在指定时间段内产生的告警数量 ，以了解威胁告警运行状况。</p>';
$content .= '<table border="1" cellspacing="0" align="center">
    <thead>
        <tr>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:100px;font-size:20px;">序号</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:150px;font-size:20px;">安全设备</th>
            <th style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;width:200px;font-size:20px;">告警数量</th>
        </tr>
    </thead>
    <tbody>';
foreach ($risk_device as $key => $value) {
    $content .= '
        <tr align="center" style="font-size:18px">
            <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . ($key + 1) . '</th>
            <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['dev_name'] . '</th>
            <th style = "overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' . $value['risk_assets_count'] . '</th>
        </tr >';
}
$content .= '</tbody>
    </table>';

//文件下载
$file_name = iconv("utf-8", "gb2312", "运行报告_" . $stime . "-" . $etime . ".docx");
down_load($file_name, $header . $content . $footer);

//如果想直接保存到服务器的话 
// file_put_contents('test.doc',$header.$content.$footer); 
//文件下载函数
function down_load($showname, $content) {
    if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
        $showname = rawurlencode($showname);
        $showname = preg_replace('/\./', '%2e', $showname, substr_count($showname, '.') - 1);
    }
    header("Cache-Control: ");
    header("Pragma: ");
    header("Content-Type: application/octet-stream");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Content-Length: " . (string) (strlen($content)));
    header('Content-Disposition: attachment; filename="' . $showname . '"');
    header("Content-Transfer-Encoding: binary\n");
    echo $content;
    exit();
}
