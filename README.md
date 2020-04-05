## swagger
1） swagger放在与项目代码同一级别位置

2） 在 swagger/swagger-cli ,仿照 swagger.ini.example 新建 .ini文件。文件名与项目文件夹名字一致（否则要修改build.php）。
在文件中只需填写代码扫描目录即可，相对路径、绝对路径都可。

3） 此时，已经可以手动生成 yml文件了，执行格式：php /swagger/swagger-cli/build.php -f 模板文件名 -c 项目模块 -m 接口模块
例1：php /swagger/swagger-cli/build.php -f order-service.ini -c order
例2：php /swagger/swagger-cli/build.php -f order-service.ini -c order -m manage

4） 执行了第三步后，在 swagger/swagger-ui 会生成 yml文件。说明已经成功大半了。
order-service_order.yml
order-service_order_manage.yml

5） 配置站点，指向 swagger/swagger-ui 。具体配置不做赘述。

6） 浏览器访问刚才配置的域名，如：localhost.swagger.com。浏览器默认打开 order-service_order.yml，如果想修改成默认 yml文件，修改 swagger/swagger-ui/index.html 中91行左右的地方，把 'order-service_order.yml' 改为你的想要的默认页，刷新页面即可。



## swagger2markdown

1)  首先生成json内容的文件，只要是json内容即可。

2） 假设已经配好swagger相关（如上面swagger指示），然后执行生成json内容的文件
例1：php /swagger/swagger-cli/buildjson.php -f order-service.ini -c order
例2：php /swagger/swagger-cli/buildjson.php -f order-service.ini -c order -m manage

3） 编写转换文件（或者直接在 md/swagger2md.php 中编写）
```php
<?php
$path = __DIR__;
require(__DIR__ ."/md/swagger2md.php");
// 实际参数根据自己情况来配
$cfg    = ['file_path' => 'swagger.json', 'request_host' => 'http://godfrey.gan.dazhairen.com', 'md_dir_path' => 'docs', 'md_tpl_path' => 'tpl.md', 'is_create_menu' => TRUE, 'menu_file_name' => 'SUMMARY.md'];
$s2mObj = new Swagger2Md($cfg);
$s2mObj->transformation();
?>
```

4） 在浏览器执行该文件，浏览器没有报错误，且全部成功。OK，恭喜你，已经操作完成了！！！

5） 查看配置的文件夹中是否有生成接口文件、导航文件

**ps：swagger2md是在极短时间内写出来的，可能存在缺陷。如有发现问题，可联系本人（g854787652@gmail.com）修正。 谢谢！！！**