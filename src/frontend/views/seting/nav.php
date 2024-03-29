<?php
function isActiveNav($path)
{
    $url = explode('?',Yii::$app->request->getUrl())[0];
    $url = rtrim($url, '/'); 
    if($url == $path)
    {
        return 'active';
    }else
    {
        return '';
    }
}
function getPathNav($path)
{
    $url = explode('?',Yii::$app->request->getUrl())[0];
    $url = rtrim($url, '/'); 
    if($url == $path)
    {
        return 'javascript:void(0);';
    }else
    {
        return $path;
    }

}
?>
<ul class="nav nav-tabs">
        <li class="<?= isActiveNav('/seting/sys-time') ?>">
            <a href="<?= getPathNav('/seting/sys-time') ?>">系统时间</a>
          </li>
          <li class="<?= isActiveNav('/seting/user') ?>">
            <a href="<?= getPathNav('/seting/user') ?>">用户管理</a>
          </li>
          <li class="<?= isActiveNav('/seting/group') ?>">
            <a href="<?= getPathNav('/seting/group') ?>">用户组管理</a>
          </li>
          <li class="<?= isActiveNav('/seting/prototype') ?>">
            <a href="<?= getPathNav('/seting/prototype') ?>">情报管理</a>
          </li>
          <li class="<?= isActiveNav('/seting/network') ?>">
            <a href="<?= getPathNav('/seting/network') ?>">网络配置</a>
          </li>
          <li class="<?= isActiveNav('/seting/license') ?>">
            <a href="<?= getPathNav('/seting/license') ?>">许可证</a>
          </li>
          <li class="<?= isActiveNav('/seting/log') ?>">
            <a href="<?= getPathNav('/seting/log') ?>">系统日志</a>
          </li>
        </ul>