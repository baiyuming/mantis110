<?php
	$g_hostname = 'localhost';
	$g_db_type = 'mysql';
	$g_database_name = 'bugtracker';
	$g_db_username = 'root';
	$g_db_password = 'dirrid';
# — 邮件设置 ————-
#$g_administrator_email  = 'mantis@****.com';#
#$g_webmaster_email      = 'mantis@****.com';# 管理员邮件帐号
#$g_from_email           = 'mantis@****.com';# 发送者帐号，即Mantis自动发邮件是显示的用户帐号
#$g_return_path_email    = 'mantis@****.com';# 邮件回复地址
#$g_enable_email_notification    = OFF;      # 开通邮件通知
#$g_smtp_host     = 'mail.****.com';     # SMTP 服务器
#$g_smtp_username = 'username';     # 邮箱登录用户名
#$g_smtp_password = 'password';  # 邮箱登录密码
#$g_use_phpMailer = OFF;    # 使用 PHPMailer 发送邮件
#$g_phpMailer_path = 'C:/xampp/htdocs/mantis/core/phpmailer'; # PHPMailer 的存放路径
#$g_phpMailer_method   = 2;       # PHPMailer 以 SMTP 方式发送 Email
# — 其他设置 ————-
$g_show_project_menu_bar = ON;# 是否显示项目选择栏，ON是显示，OFF不显示
$g_show_queries_count     = ON; # 在页脚是否显示执行的查询次数，ON是显示，OFF不显示
$g_default_new_account_access_level = DEVELOPER; # 默认用户级别
$g_view_summary_threshold   = VIEWER; #设置查看权限
$g_window_title = '千米缺陷管理系统'; # 浏览器标题
$g_page_title = '千米缺陷管理系统'; # 页面标题栏
$g_max_failed_login_count = 5;#默认登录失败次数
$g_show_realname = ON;#显示真名
$g_allow_anonymous_login  = ON;# 允许用户匿名登录
$g_anonymous_account = 'dummy';#匿名登录的用户名
# — 日期设置 ————-
$g_short_date_format = 'Y-m-d'; # 短日期格式，Y 大写表示 4 位年
$g_normal_date_format ='Y-m-d H:i'; # 普通日期格式
$g_complete_date_format ='Y-m-d H:i:s'; # 完整日期格式
# — 报表设置 ————-
#$g_use_jpgraph = ON;
#$g_jpgraph_path = 'C:/xampp/htdocs/mantis/core/jpgraph-2.3.3/src/';  #设置jpgraph的路径
#//$g_graph_font = 'chinese_gbk’;
?>
