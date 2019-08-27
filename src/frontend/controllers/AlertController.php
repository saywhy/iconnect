<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
//use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Alert;
use common\models\Config;
use common\models\SafetyScore;
use common\models\RiskAssets;
use common\models\AlertStatistions;
use common\models\DeviceHoursLogCount;

/**
 * Site controller
 */
class AlertController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        if (Config::getLicense()['validLicenseCount'] == 0) {
            $rules = [];
        } else {
            $rules = [
                [
                    'actions' => [''],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                [
                    'actions' => [''],
                    'allow' => true,
                    'roles' => ['@'],
                ],
                [
                    'actions' => ['index', 'detail', 'do_alarm', 'page', 'top', 'show-tabs', 'threat-type', 'untreated-alarm-type', 'system-state', 'risk-assets-sort', 'risk-assets', 'get-last24-logs', 'get-last24-alarms', 'safety-equipment', 'get-alert-count', 'get-same-indicator-alert', 'get-last365-total-logs'],
                    'allow' => true,
                    'roles' => ['user'],
                ],
                [
                    'actions' => [],
                    'allow' => true,
                    'roles' => ['admin'],
                ],
            ];
        }
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'detail', 'do_alarm', 'page', 'top', 'show-tabs', 'threat-type', 'untreated-alarm-type', 'system-state', 'risk-assets-sort', 'risk-assets', 'get-last24-logs', 'get-last24-alarms', 'safety-equipment', 'get-alert-count', 'get-same-indicator-alert', 'get-last365-total-logs'],
                'rules' => $rules,
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                // 'logout' => ['post'],
                // 'test' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /*
     * Displays homepage.
     *
     * @return mixed
     */

    public $enableCsrfValidation = false;

    private function isAPI() {
        $headers = Yii::$app->request->headers;
        if (stristr($headers['accept'], 'application/json') !== false) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        } else {
            Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
        }
    }

    public function actionIndex() {
        return $this->render('index');
    }

    //显示告警详情的方法
    public function actionDetail($id) {
        $alert = Alert::findOne($id);
        if (empty($alert)) {
            throw new \yii\web\HttpException(404);
        }
        //点击详情的时候，如果处于新告警状态，则标记为未解决
        if ($alert->status == 0) {
            $alert->status = 1;
            $alert->save();
        }
        $alert = ArrayHelper::toArray($alert);
        return $this->render('detail', ['alert' => $alert]);
    }

    //操作告警（处理，确认）
    public function actionDoAlarm() {
        if (Yii::$app->request->isPut) {
            $alarm_info = json_decode(Yii::$app->request->getRawBody(), true);
            $alarm_id = $alarm_info['id'];
            $alarm_status = $alarm_info['status'];
            if (!in_array($alarm_status, [0, 2, 3])) {
                return '告警状态输入错误';
            }
            $alert = Alert::findOne($alarm_id);
            if (empty($alert)) {
                throw new \yii\web\HttpException(404);
            }
            $alert->status = $alarm_status;
            //操作人员
            if ($alarm_status == 2) {
                $alert->processing_person = Yii::$app->user->identity->username;
            }
            $alert->save();
            return true;
        }
        return false;
    }

    //被展示告警的方法调用
    private function page($page = 1, $rows = 15, $whereList = []) {
        $query = Alert::find();
        foreach ($whereList as $value) {
            $query = $query->andWhere($value);
        }
        $query = $query->orderBy(['time' => SORT_DESC, 'id' => SORT_DESC]);
        $page = (int) $page;
        $rows = (int) $rows;
        $count = (int) $query->count();
        $maxPage = ceil($count / $rows);
        $page = $page > $maxPage ? $maxPage : $page;
        $pageData = $query->offSet(($page - 1) * $rows)->limit($rows)->asArray()->all();
        //获取安全设备信息
        $hostinfo = Alert::getHostInfo();
        foreach ($pageData as $key => $value) {
            $pageData[$key]['device_name'] = array_key_exists($value['device_ip'], $hostinfo) ? $hostinfo[$value['device_ip']] : '';
        }
        $data = [
            'data' => $pageData,
            'count' => $count,
            'maxPage' => $maxPage,
            'pageNow' => $page,
            'status' => 'success',
        ];
        return $data;
    }

    //告警首页的方法，显示所有的告警
    public function actionPage($page = 1, $rows = 15) {
        session_write_close();
        $this->isAPI();
        if (Yii::$app->request->isPost) {
            $post = json_decode(Yii::$app->request->getRawBody(), true);
            $page = empty($post['page']) ? $page : $post['page'];
            $rows = empty($post['rows']) ? $rows : $post['rows'];
        }
        $whereList = [];
        if (isset($post['client_ip']) && $post['client_ip'] != '') {
            $whereList[] = ['like', 'client_ip', $post['client_ip']];
        }
        if (isset($post['startTime']) && $post['startTime'] != '') {
            $whereList[] = ['>', 'time', $post['startTime']];
        }
        if (isset($post['endTime']) && $post['endTime'] != '') {
            $whereList[] = ['<', 'time', $post['endTime']];
        }
        if (isset($post['indicator']) && $post['indicator'] != '') {
            $whereList[] = ['indicator' => $post['indicator']];
        }
        if (isset($post['status'])) {
            if ($post['status'] == 0) {
                $whereList[] = ['IN', 'status', [0, 1]];
            } else if ($post['status'] == 2) {
                $whereList[] = ['=', 'status', 2];
            } else if ($post['status'] == 3) {
                $whereList[] = ['IN', 'status', [0, 1, 2]];
            }
        }
        return $this->page($page, $rows, $whereList);
    }

    //总览页面最上方的tab标签
    public function actionShowTabs() {
        session_write_close();
        $this->isAPI();
        if (Yii::$app->request->isGet) {
            //查询告警总数和，风险资产总数
            $alarm = Alert::showTabs();
            //获取安全评分
            $alarm['safety_score'] = SafetyScore::getSafetyScore();
            return $alarm;
        }
        return false;
    }

    //获取最近24小时的告警情况，以及24小时产生的告警总数
    public function actionGetLast24Alarms() {
        session_write_close();
        $this->isAPI();
        if (Yii::$app->request->isGet) {
            $alarm = Alert::getLast24Alarms();
            return json_encode($alarm);
        }
        return false;
    }

    //获取最近24小时的日志
    public function actionGetLast24Logs() {
        session_write_close();
        $this->isAPI();
        if (Yii::$app->request->isGet) {
            $current_time = mktime(date('H'), 0, 0, date('m'), date('d'), date('Y'));
            $last24logs = [];
            //从redis中获取
            for ($i = 0; $i < 24; $i++) {
                $current_log_count = Yii::$app->redis->get("logcount:" . date('H', $current_time - 3600 * $i));
                array_unshift($last24logs, array(0 => date('H', $current_time - 3600 * ($i - 1)) . ':00', 1 => $current_log_count ? $current_log_count : 0));
            }
            $last24logs[23][0] = date('H:i', time());
            return $last24logs;
        }
    }

    //获取近365天的日志总量
    public function actionGetLast365TotalLogs() {
        session_write_close();
        $last365totallogs = DeviceHoursLogCount::find()->sum('log_count');
        //从redis获取今天的量
        $safety_equipment = json_decode(Alert::bottomCommunication('/iconnect/host'), true)['_items'];
        $current_hour = date('H', time());
        foreach ($safety_equipment as $key => $value) {
            for ($i = 0; $i <= $current_hour; $i++) {
                $i = str_pad($i, 2, "0", STR_PAD_LEFT);
                $log_count = Yii::$app->redis->get($value['_id'] . ':' . $i);
                $last365totallogs = $last365totallogs + $log_count;
            }
        }
        return $last365totallogs ? $last365totallogs : 0;
    }

    //安全设备
    public function actionSafetyEquipment() {
        session_write_close();
        //获取所有的安全设备
        $safety_equipment = json_decode(Alert::bottomCommunication('/iconnect/host'), true)['_items'];
        if (empty($safety_equipment)) {
            return '{"safety_equipment_count":0,"all_safety_equipment_alert_count":0,"offline_equipment":0,"safety_equipment":[]}';
        }
        //声明在线设备为0
        $offline_equipment = 0;
        $safety_equipment_ids = [];
        foreach ($safety_equipment as $key => $value) {
            array_push($safety_equipment_ids, $value['_id']);
            //计算在线数量
            if (!$value['online']) {
                $offline_equipment += 1;
            }
        }
        //获取所有安全设备的日志总数
        $safety_equipment_logs_count = DeviceHoursLogCount::find()->groupBy('device_id')->where(['IN', 'device_id', $safety_equipment_ids])->asArray()->select(['device_id,sum(log_count) as logs_count'])->all();
        //将每个设备的rizhi总数放入到安全设备的数组
        foreach ($safety_equipment as $k => $v) {
            $safety_equipment[$k]['logs_count'] = 0;
            foreach ($safety_equipment_logs_count as $kk => $vv) {
                if ($v['_id'] == $vv['device_id']) {
                    $safety_equipment[$k]['logs_count'] = $vv['logs_count'];
                    break;
                }
            }
            //从redis里获取今天的数据
            $current_hour = date('H', time());
            for ($i = 0; $i <= $current_hour; $i++) {
                $i = str_pad($i, 2, "0", STR_PAD_LEFT);
                $log_count = Yii::$app->redis->get($v['_id'] . ':' . $i);
                $safety_equipment[$k]['logs_count'] = $safety_equipment[$k]['logs_count'] + $log_count;
            }
        }
        //所有的告警展示
        $safety_equipment_alert_count = Alert::find()->groupBy('device_id')->where(['IN', 'device_id', $safety_equipment_ids])->asArray()->select(['device_id,device_ip,count(device_id) as alerm_count'])->orderBy('alerm_count DESC')->all();
        //将每个设备的告警总数放入到安全设备的数组
        foreach ($safety_equipment as $k => $v) {
            unset($safety_equipment[$k]['family']);
            unset($safety_equipment[$k]['_updated']);
            unset($safety_equipment[$k]['_created']);
            unset($safety_equipment[$k]['online']);
            $safety_equipment[$k]['alerm_count'] = 0;
            foreach ($safety_equipment_alert_count as $kk => $vv) {
                $safety_equipment[$k]['device_ip'] = $vv['device_ip'];
                if ($v['_id'] == $vv['device_id']) {
                    $safety_equipment[$k]['alerm_count'] = $vv['alerm_count'];
                    break;
                }
            }
        }
        //排序
        $arrSort = array();
        foreach ($safety_equipment AS $uniqid => $row) {
            foreach ($row AS $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if (!empty($arrSort['alerm_count'])) {
            array_multisort($arrSort['alerm_count'], constant('SORT_DESC'), $safety_equipment);
        }
        $all_safety_equipment_alert_count = 0;
        foreach ($safety_equipment_alert_count as $v) {
            $all_safety_equipment_alert_count += $v['alerm_count'];
        }
        return json_encode(['safety_equipment_count' => count($safety_equipment), 'all_safety_equipment_alert_count' => $all_safety_equipment_alert_count, 'offline_equipment' => $offline_equipment, 'safety_equipment' => $safety_equipment]);
    }

    //威胁类型TOP5
    public function actionThreatType() {
        session_write_close();
        $this->isAPI();
        if (Yii::$app->request->isGet) {
            $threat_type = Alert::threatType();
            return $threat_type;
        }
        return false;
    }

    //未处理告警分类
    public function actionUntreatedAlarmType() {
        session_write_close();
        $this->isAPI();
        if (Yii::$app->request->isGet) {
            $untreatedAlarmType = Alert::untreatedAlarmType();
            return $untreatedAlarmType;
        }
        return false;
    }

    //监控系统状态
    public function actionSystemState() {
        session_write_close();
        $this->isAPI();
        if (Yii::$app->request->isGet) {
            $system_state = Alert::bottomCommunication('iconnect/metrics/localhost?cf=MAX&r=60&dt=10800');
            //分析memory
            $systeminfo = json_decode($system_state, true)['result'];
            if (empty($systeminfo)) {
                return '{"mem":{"if_alarm": true,"time":[""],"value":[]},"cpu":{"if_alarm":true,"time":[""],"value":[]},"disk":{"if_alarm":true,"time":[""],"value":[]}}';
            }
            $mem_info = [];
            $cpu_info = [];
            $disk_info = [];
            //分析内存是否超过85%，（半小时内）
            foreach ($systeminfo as $k => $v) {
                if ($v['metric'] == 'memory') {
                    $mem_info = $this->analysisSystemState(array_reverse($systeminfo[$k]['values']), 85);
                    break;
                }
            }
            //分析CPU是否超过85%，（半小时内）
            foreach ($systeminfo as $k => $v) {
                if ($v['metric'] == 'cpu') {
                    $cpu_info = $this->analysisSystemState(array_reverse($systeminfo[$k]['values']), 85);
                    break;
                }
            }
            //分析磁盘是否超过90%，（最近一次）
            foreach ($systeminfo as $k => $v) {
                if ($v['metric'] == 'disk') {
                    $disk_info = $this->analysisDiskState(array_reverse($systeminfo[$k]['values']), 90);
                    break;
                }
            }
            return json_encode(['mem' => $mem_info, 'cpu' => $cpu_info, 'disk' => $disk_info]);
        }
        return false;
    }

    //分析cpu和内存状态，被actionSystemState调用
    private function analysisSystemState($sysinfo, $maxvalue) {
        //声明是否告警和最新的值得参数
        $if_alarm = true;
        $time = [];
        $value = [];
        $times = 0;
        //获取状态是告警还是不告警
        foreach ($sysinfo as $k => $v) {
            $times += 1;
            //取出30分钟的，然后跳出
            if ($times < 30) {
                if ($v[1] >= 0 && $v[1] < $maxvalue) {
                    $if_alarm = false;
                    break;
                }
            }
        }
        foreach ($sysinfo as $k => $v) {
            if (substr(date("H:i", $v[0]), 3, 2) % 5 == 0) {
                array_push($time, date('H:i', $v[0]));
                array_push($value, round($v[1], 1));
            }
        }
        array_unshift($time, date('H:i', $sysinfo[0][0]));
        array_unshift($value, round($sysinfo[0][1], 1));
        return ['if_alarm' => $if_alarm, 'time' => $time, 'value' => $value];
    }

    //分析磁盘状态，被actionSystemState调用
    private function analysisDiskState($sysinfo, $maxvalue) {
        //声明是否告警和最新的值得参数
        $if_alarm = false;
        $time = [];
        $value = [];
        //获取状态是告警还是不告警
        for ($i = 0; $i <= 30; $i ++) {
            //如果有值且大于$maxvalue则认为超过了预警值
            error_reporting(E_ERROR);
            if ($sysinfo[$i][1] && ($sysinfo[$i][1] > $maxvalue)) {
                $if_alarm = true;
                break;
            }
        }
        foreach ($sysinfo as $k => $v) {
            if (substr(date("H:i", $v[0]), 3, 2) % 5 == 0) {
                array_push($time, date('H:i', $v[0]));
                array_push($value, round($v[1], 1));
            }
        }
        array_unshift($time, date('H:i', $sysinfo[0][0]));
        array_unshift($value, round($sysinfo[0][1], 1));
        return ['if_alarm' => $if_alarm, 'time' => $time, 'value' => $value];
    }

    //风险资产
    public function actionRiskAssets() {
        session_write_close();
        $this->isAPI();
        if (Yii::$app->request->isGet) {
            $risk_assets = RiskAssets::riskAssets();
            return $risk_assets;
        }
        return false;
    }

    //风险资产top5
    public function actionRiskAssetsSort() {
        session_write_close();
        $this->isAPI();
        if (Yii::$app->request->isGet) {
            $risk_assets = Alert::riskAssetsSort();
            return $risk_assets;
        }
        return false;
    }

    public function actionTop($group = 'device_ip') {
        session_write_close();
        $this->isAPI();
        $time = time() - (3600 * 24 * 14);
        $sql = 'SELECT ' . $group . ', COUNT(' . $group . ') as count FROM alert WHERE time > ' . $time . ' GROUP BY ' . $group . ' ORDER BY count DESC LIMIT 10';
        $data = Yii::$app->db->createCommand($sql)->query();
        $data = ArrayHelper::toArray($data);
        $ret['status'] = 'success';
        $ret['data'] = $data;
        return $ret;
    }

    //获取告警数量的方法
    public function actionGetAlertCount() {
        session_write_close();
        $this->isAPI();
        $data = AlertStatistions::find()->select('statistics_time,alert_count')->asArray()->all();
        $times = [];
        $alert_count = [];
        foreach ($data as $value) {
            array_push($times, $value['statistics_time']);
            array_push($alert_count, $value['alert_count']);
        }
        return ['times' => $times, 'alert_count' => $alert_count];
    }

    //获取相同指标的告警的方法
    public function actionGetSameIndicatorAlert($page1 = 1, $rows1 = 15) {
        session_write_close();
        $this->isAPI();
        if (Yii::$app->request->isGet) {
            $get = Yii::$app->request->get();
            //获取参数
            $indicator = $get['indicator'];
            $is_deal = $get['is_deal'];
            $page = empty($get['page']) ? $page1 : $get['page'];
            $rows = empty($get['rows']) ? $rows1 : $get['rows'];
        }
        //保证参数存在
        if (empty($indicator) || !in_array($is_deal, [0, 2])) {
            return '参数选择错误';
        }
        //转换值的类型
        if ($is_deal == 0) {
            $is_deal = [0, 1];
        }
        // 声明页码和每页显示数量等参数
        $page = (int) $page;
        $rows = (int) $rows;
        $count = (int) Alert::find()->where(['AND', ['=', 'indicator', $indicator], ['IN', 'status', $is_deal]])->count();
        $maxPage = ceil($count / $rows);
        $page = $page > $maxPage ? $maxPage : $page;
//        $pageData = $query->offSet(($page - 1) * $rows)->limit($rows)->asArray()->all();
        $pageData = Alert::find()->where(['AND', ['=', 'indicator', $indicator], ['IN', 'status', $is_deal]])->offSet(($page - 1) * $rows)->limit($rows)->asArray()->all();
        //转换日期时间
        foreach ($pageData as $k => $v) {
            $pageData[$k]['time'] = date('Y-m-d H:i:s', $v['time']);
            $pageData[$k]['updated_at'] = date('Y-m-d H:i:s', $v['updated_at']);
        }
        $data = [
            'data' => $pageData,
            'count' => $count,
            'maxPage' => $maxPage,
            'pageNow' => $page,
            'status' => 'success',
        ];
        return $data;
    }

}
