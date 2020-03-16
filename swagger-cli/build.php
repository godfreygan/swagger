<?php
/**
 * 执行格式：php build.php -f 模板文件名 -c 项目模块 -m 接口模块
 * 执行示例：
 * php build.php -f order-service.ini -c order -m api
 *
 */
require(__DIR__ . "/vendor/autoload.php");

//1个冒号:表此选项需要值但也不是必须，2个冒号::表此选项值可选但也非必须,无冒号则无需传值即不接收值
$options = getopt('f:c:m:', ["file::", "cloudname::", "modulename"]);
//var_dump($options);exit;
$swaggerFileName = getDataVal($options, 'f', getDataVal($options, 'file'));
if(empty($swaggerFileName)) {
    $swaggerFileName = 'order-service.ini';
}

$cloudName = getDataVal($options, 'c', getDataVal($options, 'cloudname'));
if(empty($cloudName)) {
    $cloudName = 'order';
}

$moduleName = getDataVal($options, 'm', getDataVal($options, 'modulename'));
if(empty($moduleName)) {
    $moduleName = '';
}

$iniFile = __DIR__ . '/' . $swaggerFileName;
if(!file_exists($iniFile)) {
    exit($iniFile . ' not exists' . PHP_EOL);
}
$swaggerIniData = parse_ini_file($iniFile);
//代码扫描目录
$scanDir = (isset($swaggerIniData['scandir']) && $swaggerIniData['scandir'])?$swaggerIniData['scandir']:__DIR__ . '/app';
if(!$scanDir) {
    exit('scan dir can not empty' . PHP_EOL);
}

if($swaggerFileName == 'mobile-api.ini'){
    $realPath = '/app/'. ucfirst($cloudName) .'/';
} else {
    $realPath = '/app/'. ucfirst($cloudName) .'/'. ucfirst($moduleName). 'Controllers/';
}

$scanDir .= $realPath;
if(!is_dir($scanDir)) {
    exit($realPath . ' not exists' . PHP_EOL);
}

$fileName = explode('.', $swaggerFileName);
$distFile = (isset($fileName[0]) && $fileName[0])?$fileName[0]:'swagger';
if(empty($moduleName)){
    $distFile = __DIR__ .'/../swagger-ui/'. $distFile .'_'. $cloudName .'.yml';
}else{
    $distFile = __DIR__ .'/../swagger-ui/'. $distFile .'_'. $cloudName . '_' .$moduleName .'.yml';
}
echo "scandir:" . $scanDir . PHP_EOL;
echo "distFile:" . $distFile . PHP_EOL;

$openapi = \OpenApi\scan($scanDir, ['exclude'=>['/app/views', '/app/vendor']]); //第1个参数：扫描要生成文档的代码目录，一般配置问控制器目录
//第2个参数配置选型，其中exclude配置要忽略的目录
//header('Content-Type: application/x-yaml');
$content = $openapi->toYaml(); //生成yaml内容
//echo $content . PHP_EOL;
$path = dirname($distFile);
if(!is_dir($path)) {
    mkdir($path, 0755, true);
}
file_put_contents($distFile, $content);

echo 'finish' . PHP_EOL;

function getDataVal($data, $key = null, $default = '') {
    if(!is_array($data) || is_null($key)) {
        return $data;
    }
    if(!$key) {
        $key = 0;
    }
    if(isset($data[$key])) {
        return $data[$key];
    } else {
        return $default;
    }

}
