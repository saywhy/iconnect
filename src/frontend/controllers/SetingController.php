<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\SensorVersion;
use common\models\ProFile;
use common\models\Group;
use common\models\GroupUser;
use common\models\Config;
use yii\helpers\ArrayHelper;
use common\models\Alert;

/**
 * Seting controller
 */
class SetingController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        if (Config::getLicense()['validLicenseCount'] == 0) {
            $rules = [
                    [
                    'actions' => ['user', 'license', 'network', 'log'],
                    'allow' => true,
                    'roles' => ['admin'],
                ],
            ];
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
                    'actions' => [''],
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
                'only' => ['license', 'network', 'group', 'user', 'log', 'addgroups', 'prototype', 'sys-time', 'time-synchronization', 'manual-time-synchronization', 'ntp-time-synchronization'],
                'rules' => $rules,
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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

    /**
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
        if (Config::getLicense()['validLicenseCount'] == 0) {
            return $this->redirect('/seting/license');
        } else {
            return $this->render('index');
        }
    }

    public function actionNetwork() {
        return $this->render('network');
    }

    public function actionLicense() {
        return $this->render('license');
    }

    public function actionPrototype() {
        return $this->render('prototype');
    }

    //系统时间首页
    public function actionSysTime() {
        return $this->render('systime');
    }

    public function actionGroup() {
        $GroupList = Group::find()->orderBy('level')->all();
        $GroupList = ArrayHelper::toArray($GroupList);
        return $this->render('group', ['GroupList' => $GroupList]);
    }

    public function actionUser() {
        if (Config::getLicense()['validLicenseCount'] == 0) {
            return $this->redirect('/seting/license');
        } else {
            $GroupList = Group::find()->orderBy('level')->all();
            $GroupList = ArrayHelper::toArray($GroupList);
            return $this->render('user', ['GroupList' => $GroupList]);
        }
    }

    public function actionLog() {
        return $this->render('log');
    }

    public function actionAddgroups() {
        $this->isAPI();
        if (!Yii::$app->request->isPost) {
            $data['status'] = 'fail';
            $data['errorMessage'] = 'Not post request';
            return $data;
        }
        $post = json_decode(Yii::$app->request->getRawBody(), true);
        $data['success'] = 0;
        $data['fail'] = 0;
        foreach ($post['uidList'] as $key => $uid) {
            $groupUser = GroupUser::find()->where(['uid' => $uid, 'gid' => $post['gid']])->one();
            if (isset($groupUser)) {
                $data['fail'] ++;
                continue;
            }
            $groupUser = new GroupUser();
            $groupUser->uid = $uid;
            $groupUser->gid = $post['gid'];
            $groupUser->save();
            $data['success'] ++;
        }
        $data['status'] = 'success';
        return $data;
    }

    //被actionProxyServer调用的方法
    private function getRueryString() {
        parse_str($_SERVER['QUERY_STRING'], $query);
        foreach ($query as $key => &$value) {
            $value = urlencode($value);
        }
        return http_build_query($query);
    }

    //被actionProxyServer调用的方法
    private function setHeader($response) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->set('Content-Type', 'application/json');
    }

    //配置代理服务器的方法
    public function actionProxyServer($path) {
        $path = $path . '?' . $this->getRueryString();
        $ResultClient = Yii::$app->ProxyServerClient;
        if (Yii::$app->request->isPost) {
            $postdata = Yii::$app->request->getRawBody();
            $response = $ResultClient->post($path, $postdata, $_FILES);
        } elseif (Yii::$app->request->isPut) {
            $putdata = Yii::$app->request->getRawBody();
            //判断参数的合法性
            if ($a = $this->checkProxyParames($putdata)) {
                return json_encode(array('status' => 1, 'error_info' => $a));
            }
            $response = $ResultClient->put($path, $putdata);
        } elseif (Yii::$app->request->isDelete) {
            $response = $ResultClient->delete($path);
        } else {
            $response = $ResultClient->get($path);
        }
        $this->setHeader($response);
        return $response->getContent();
    }

    //检查代理服务器参数
    private function checkProxyParames($putdata) {
        $data = json_decode($putdata, true);
        if (count($data) != 2 || !array_key_exists('HTTP_PROXY', $data) || !array_key_exists('HTTPS_PROXY', $data)) {
            return '参数错误!';
        }
        //验证代理服务器参数参数
        if ((!preg_match('/^(http|https|socks5):\/\/((\w){1,64}:(\w){1,64}@){0,1}((1[0-9][0-9]\.)|(2[0-4][0-9]\.)|(25[0-5]\.)|([1-9][0-9]\.)|([0-9]\.)){3}((1[0-9][0-9])|(2[0-4][0-9])|(25[0-5])|([1-9][0-9])|([0-9]))(:([0-9]|[1-9]\d{1,3}|[1-5]\d{4}|6[0-5]{2}[0-3][0-5])){0,1}$/', $data['HTTP_PROXY']) && $data['HTTP_PROXY']) || (!preg_match('/^(http|https|socks5):\/\/((\w){1,64}:(\w){1,64}@){0,1}((1[0-9][0-9]\.)|(2[0-4][0-9]\.)|(25[0-5]\.)|([1-9][0-9]\.)|([0-9]\.)){3}((1[0-9][0-9])|(2[0-4][0-9])|(25[0-5])|([1-9][0-9])|([0-9]))(:([0-9]|[1-9]\d{1,3}|[1-5]\d{4}|6[0-5]{2}[0-3][0-5])){0,1}$/', $data['HTTPS_PROXY']) && $data['HTTPS_PROXY'])) {
            return '代理服务器格式填写有误!';
        }
        return 0;
    }

    //时钟同步(获取时间)
    public function actionTimeSynchronization() {
        if (Yii::$app->request->isGet) {
            $systime = json_decode(Alert::bottomCommunication('/iconnect/utils/systime'), true);
            $ntp = json_decode(Alert::bottomCommunication('/iconnect/utils/ntp'), true);
            return json_encode(['zone' => $systime['result']['zone'], 'time' => $systime['result']['time'], 'server' => $ntp['result']['server']]);
        }
        return '无效请求';
        //$this->$params['synchro_type']($params);
    }

    //设置时间和时区
    public function actionManualTimeSynchronization() {
        if (Yii::$app->request->isPut) {
            $data = Alert::bottomCommunication('/iconnect/utils/systime');
            return $data;
//            if ($params['synchro_type'] == 'manual') {
//                $this->manualManualTimeSynchronization($params);
//            } else if ($params['synchro_type'] == 'ntp') {
//                $this->ntpManualTimeSynchronization($params);
//            } else {
//                return '同步类型选择错误';
//            }
        }
        return '无效请求';
//        if (!preg_match('/^\d{4}-\d{2}-\d{2}[ |+]{1}\d{2}:\d{2}:\d{2}$/', $params['date'])) {
//            return '日期格式填写错误';
//        }
    }

    //设置ntp服务器
    public function actionNtpTimeSynchronization() {
//        if (!preg_match('/^((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]\d)|\d)(\.((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]\d)|\d)){3}$/', $params['date'])) {
//            return "ntp服务器地址格式错误";
//        }
        if (Yii::$app->request->isPut) {
            $data = Alert::bottomCommunication('/iconnect/utils/ntp');
            return $data;
        }
        return '无效请求';
    }

}
