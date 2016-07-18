<?php 
/**
 * 添加Weixinpress管理菜单
 */  
add_action('admin_menu','weixinpress_menu');  
function weixinpress_menu() {      
    // Call wordpress core api function to add plugin setting menus.     
    add_menu_page( 
        "WeixinPress",
        "WeixinPress", 
        8,
        __FILE__,
        "weixinpress_optionpage",   
        WXP_URL."/images/weixin.png"
    ); 
    // Add sub menu page
    // add_submenu_page(__FILE__,'网站列表','网站列表','8','list-site','pro_admin_list_site'); 
}   

/**
 * 从数据库获取设置选项
 */ 
function get_weixinpress_option(){
    $array_weixinpress_option                              = array();
    $array_weixinpress_option[WXP_TOKEN]                   = stripslashes(get_option(WXP_TOKEN));
    $array_weixinpress_option[WXP_WELCOME]                 = stripslashes(get_option(WXP_WELCOME));
    $array_weixinpress_option[WXP_WELCOME_CMD]             = stripslashes(get_option(WXP_WELCOME_CMD));
    $array_weixinpress_option[WXP_HELP]                    = stripslashes(get_option(WXP_HELP));
    $array_weixinpress_option[WXP_HELP_CMD]                = stripslashes(get_option(WXP_HELP_CMD));
    $array_weixinpress_option[WXP_KEYWORD_LENGTH]          = get_option(WXP_KEYWORD_LENGTH);
    $array_weixinpress_option[WXP_AUTO_REPLY]              = get_option(WXP_AUTO_REPLY);
    $array_weixinpress_option[WXP_KEYWORD_IN_TITLE]        = get_option(WXP_KEYWORD_IN_TITLE);
    $array_weixinpress_option[WXP_KEYWORD_IN_CONTENT]      = get_option(WXP_KEYWORD_IN_CONTENT);
    $array_weixinpress_option[WXP_CATEGORY_EXCLUDE]        = get_option(WXP_CATEGORY_EXCLUDE);
    $array_weixinpress_option[WXP_KEYWORD_LENGTH_WARNING]  = stripslashes(get_option(WXP_KEYWORD_LENGTH_WARNING));
    $array_weixinpress_option[WXP_KEYWORD_ERROR_WARNING]   = stripslashes(get_option(WXP_KEYWORD_ERROR_WARNING));
    $array_weixinpress_option[WXP_DEFAULT_ARTICLE_ACCOUNT] = get_option(WXP_DEFAULT_ARTICLE_ACCOUNT);
    $array_weixinpress_option[WXP_NEW_ARTICLE_CMD]         = stripslashes(get_option(WXP_NEW_ARTICLE_CMD));
    $array_weixinpress_option[WXP_RAND_ARTICLE_CMD]        = stripslashes(get_option(WXP_RAND_ARTICLE_CMD));
    $array_weixinpress_option[WXP_HOT_ARTICLE_CMD]         = stripslashes(get_option(WXP_HOT_ARTICLE_CMD));
    $array_weixinpress_option[WXP_CMD_SEPERATOR]           = stripslashes(get_option(WXP_CMD_SEPERATOR));
    $array_weixinpress_option[WXP_DEFAULT_THUMB]           = stripslashes(get_option(WXP_DEFAULT_THUMB));
    
    return $array_weixinpress_option;
}

/**
 * 更新数据库中的设置选项
 */ 

function update_weixinpress_option(){
    if($_POST['action']=='保存设置'){
        update_option(WXP_TOKEN, $_POST['wxp-token']);
        update_option(WXP_WELCOME, $_POST['wxp-welcome']);
        update_option(WXP_WELCOME_CMD, $_POST['wxp-welcome-cmd']);
        update_option(WXP_HELP, $_POST['wxp-help']);
        update_option(WXP_HELP_CMD, $_POST['wxp-help-cmd']);
        update_option(WXP_KEYWORD_LENGTH, $_POST['wxp-keyword-length']);

        $auto_reply = $_POST['wxp-auto-reply'];
        if($auto_reply != 1 ) {$auto_reply = 0;}
        update_option(WXP_AUTO_REPLY, $auto_reply);

        $keyword_in_title = $_POST['wxp-keyword-in-title'];
        if($keyword_in_title != 1){$keyword_in_title=0;}
        update_option(WXP_KEYWORD_IN_TITLE, $keyword_in_title);

        $keyword_in_content = $_POST['wxp-keyword-in-content'];
        if($keyword_in_content != 1){$keyword_in_content=0;}
        update_option(WXP_KEYWORD_IN_CONTENT, $keyword_in_content);

        update_option(WXP_CATEGORY_EXCLUDE, $_POST['wxp-category-exclude']);
        update_option(WXP_KEYWORD_LENGTH_WARNING, $_POST['wxp-keyword-length-warning']);
        update_option(WXP_KEYWORD_ERROR_WARNING, $_POST['wxp-keyword-error-warning']);
        update_option(WXP_DEFAULT_ARTICLE_ACCOUNT, $_POST['wxp-default-article-account']);
        update_option(WXP_NEW_ARTICLE_CMD, $_POST['wxp-new-article-cmd']);
        update_option(WXP_RAND_ARTICLE_CMD, $_POST['wxp-rand-article-cmd']);
        update_option(WXP_HOT_ARTICLE_CMD, $_POST['wxp-hot-article-cmd']);
        update_option(WXP_CMD_SEPERATOR, $_POST['wxp-cmd-seperator']);
        update_option(WXP_DEFAULT_THUMB, $_POST['wxp-default-thumb']);
    }
    weixinpress_topbarmessage('恭喜，更新配置成功');
}

//添加默认配置
function add_weixinpress_option(){
	$defalut_val = array(
		WXP_TOKEN => uniqid(),
//		WXP_URL_STR => 'weixinpress',
		WXP_WELCOME => '欢迎关注小站，更多精彩内容，可通过发送关键字获取！如：
发送“帮助”或“help”，查看帮助信息
发送“最新文章”，将获取最新文章
发送“最热文章”，将获取最热门的文章
发送“随机文章”，将获取随机选取的文章发送',
		WXP_WELCOME_CMD => '欢迎 welcome',
		WXP_HELP => '非常感谢关注小站，可通过发送关键字获取精彩内容！如：
发送“帮助”或“help”，查看帮助信息
发送“最新文章”或“new”，将获取最新文章
发送“最热文章”或“hot”，将获取最热门的文章
发送“随机文章”或“rand”，将获取随机选取的文章发送',
		WXP_HELP_CMD => '帮助 help',
		WXP_KEYWORD_LENGTH => '15',
		WXP_AUTO_REPLY => 0,
        WXP_KEYWORD_IN_TITLE => 1,
        WXP_KEYWORD_IN_CONTENT => 1,
        WXP_CATEGORY_EXCLUDE => '',
		WXP_KEYWORD_LENGTH_WARNING => '',
		WXP_KEYWORD_ERROR_WARNING => '你输入的关键字未匹配到任何内容，可以换其他关键词试试哦，如：
发送“帮助”或“help”，查看帮助信息
发送“最新文章”，将获取最新文章
发送“最热文章”，将获取最热门的文章
发送“随机文章”，将获取随机选取的文章发送',
		WXP_DEFAULT_ARTICLE_ACCOUNT => 10,
		WXP_NEW_ARTICLE_CMD => '最新文章 new',
		WXP_RAND_ARTICLE_CMD => '随机文章 rand',
		WXP_HOT_ARTICLE_CMD => '最热文章 hot',
		WXP_CMD_SEPERATOR => '@',
		WXP_DEFAULT_THUMB => '',
	);
	$options = get_weixinpress_option();
	update_option(WXP_TOKEN, !empty($options[WXP_TOKEN])?$options[WXP_TOKEN]:$defalut_val[WXP_TOKEN]);
//	update_option(WXP_URL_STR, $defalut_val[WXP_URL_STR]);
	update_option(WXP_WELCOME, !empty($options[WXP_WELCOME])?$options[WXP_WELCOME]:$defalut_val[WXP_WELCOME]);
	update_option(WXP_WELCOME_CMD, !empty($options[WXP_WELCOME_CMD])?$options[WXP_WELCOME_CMD]:$defalut_val[WXP_WELCOME_CMD]);
	update_option(WXP_HELP, !empty($options[WXP_HELP])?$options[WXP_HELP]:$defalut_val[WXP_HELP]);
	update_option(WXP_HELP_CMD, !empty($options[WXP_HELP_CMD])?$options[WXP_HELP_CMD]:$defalut_val[WXP_HELP_CMD]);
	update_option(WXP_KEYWORD_LENGTH, !empty($options[WXP_KEYWORD_LENGTH])?$options[WXP_KEYWORD_LENGTH]:$defalut_val[WXP_KEYWORD_LENGTH]);
	update_option(WXP_AUTO_REPLY, !empty($options[WXP_AUTO_REPLY])?$options[WXP_AUTO_REPLY]:$defalut_val[WXP_AUTO_REPLY]);
    update_option(WXP_KEYWORD_IN_TITLE, !empty($options[WXP_KEYWORD_IN_TITLE])?$options[WXP_KEYWORD_IN_TITLE]:$defalut_val[WXP_KEYWORD_IN_TITLE]);
    update_option(WXP_KEYWORD_IN_CONTENT, !empty($options[WXP_KEYWORD_IN_CONTENT])?$options[WXP_KEYWORD_IN_CONTENT]:$defalut_val[WXP_KEYWORD_IN_CONTENT]);
    update_option(WXP_CATEGORY_EXCLUDE, !empty($options[WXP_CATEGORY_EXCLUDE])?$options[WXP_CATEGORY_EXCLUDE]:$defalut_val[WXP_CATEGORY_EXCLUDE]);
	update_option(WXP_KEYWORD_LENGTH_WARNING, !empty($options[WXP_KEYWORD_LENGTH_WARNING])?$options[WXP_KEYWORD_LENGTH_WARNING]:$defalut_val[WXP_KEYWORD_LENGTH_WARNING]);
	update_option(WXP_KEYWORD_ERROR_WARNING, !empty($options[WXP_KEYWORD_ERROR_WARNING])?$options[WXP_KEYWORD_ERROR_WARNING]:$defalut_val[WXP_KEYWORD_ERROR_WARNING]);
	update_option(WXP_DEFAULT_ARTICLE_ACCOUNT, !empty($options[WXP_DEFAULT_ARTICLE_ACCOUNT])?$options[WXP_DEFAULT_ARTICLE_ACCOUNT]:$defalut_val[WXP_DEFAULT_ARTICLE_ACCOUNT]);
	update_option(WXP_NEW_ARTICLE_CMD, !empty($options[WXP_NEW_ARTICLE_CMD])?$options[WXP_NEW_ARTICLE_CMD]:$defalut_val[WXP_NEW_ARTICLE_CMD]);
	update_option(WXP_RAND_ARTICLE_CMD, !empty($options[WXP_RAND_ARTICLE_CMD])?$options[WXP_RAND_ARTICLE_CMD]:$defalut_val[WXP_RAND_ARTICLE_CMD]);
	update_option(WXP_HOT_ARTICLE_CMD, !empty($options[WXP_HOT_ARTICLE_CMD])?$options[WXP_HOT_ARTICLE_CMD]:$defalut_val[WXP_HOT_ARTICLE_CMD]);
	update_option(WXP_CMD_SEPERATOR, !empty($options[WXP_CMD_SEPERATOR])?$options[WXP_CMD_SEPERATOR]:$defalut_val[WXP_CMD_SEPERATOR]);
	update_option(WXP_DEFAULT_THUMB, !empty($options[WXP_DEFAULT_THUMB])?$options[WXP_DEFAULT_THUMB]:$defalut_val[WXP_DEFAULT_THUMB]);
}
register_activation_hook(__FILE__,'add_weixinpress_option');
/*register_deactivation_hook(__FILE__,'delete_weixinpress_option');
//清除默认设置
function delete_weixinpress_option(){
	delete_option(WXP_TOKEN);
	delete_option(WXP_URL_STR);
	delete_option(WXP_WELCOME);
	delete_option(WXP_WELCOME_CMD);
	delete_option(WXP_HELP);
	delete_option(WXP_HELP_CMD);
	delete_option(WXP_KEYWORD_LENGTH);
	delete_option(WXP_AUTO_REPLY);
	delete_option(WXP_KEYWORD_LENGTH_WARNING);
	delete_option(WXP_KEYWORD_ERROR_WARNING);
	delete_option(WXP_DEFAULT_ARTICLE_ACCOUNT);
	delete_option(WXP_NEW_ARTICLE_CMD);
	delete_option(WXP_RAND_ARTICLE_CMD);
	delete_option(WXP_HOT_ARTICLE_CMD);
	delete_option(WXP_CMD_SEPERATOR);
	delete_option(WXP_DEFAULT_THUMB);
}*/

// Custom message bar
function weixinpress_topbarmessage($msg) {
     echo '<div class="updated fade" id="message"><p>' . $msg . '</p></div>';
}

// Plugin setting option page
function weixinpress_optionpage(){

?>
    <style type="text/css">
        h2{
            height:36px;
            line-height: 36px;
        }
        label{
            display: inline-block;
            font-weight: bold;
        }
        textarea{
            width:450px;
            height:80px;
        }
        input{
            width: 450px;
            height: 30px;
        }
        table{
            border: 0px solid #ececec;
        }
        tr{
            margin: 20px 0px;
        }
        .right{
            vertical-align: top;
            padding-top: 10px;
            width:120px;
            text-align: right;
        }
        .left{
            width: 500px;
            padding-left:50px;
            text-align: left;
        }
        .wxp-logo{
            background: url(<?php echo WXP_URL; ?>/images/weixin-big.png) 0px 0px no-repeat;
            background-size: 36px 36px;
            height: 36px;
            width: 36px;
            float: left;
        }
        .wxp-notes{
            margin: 10px 0px 30px 0px;
            display: inline-block;
            width: 450px;
        }
        .wxp-submit-btn{
            height: 30px;
            width: 150px;
            background-color: #21759b;
            font-weight: bold;
            color: #ffffff;
            font-family: "Microsoft YaHei";
        }
        .wxp-center{
            text-align: center;
        }
        .wxp-btn-box{
            margin: 15px 0px;
        }
        .wxp-option-main{
            margin: 5px 0px;
            width: 650px;
            float:left;
        }
        .wxp-option-sidebar{
            width: 100px;
            float:left;
        }
        .sidebar-box{
            border:1px solid #dfdfdf;
            width:200px;
            border-radius: 3px;
            box-shadow: inset 0 1px 0 #fff;
            background-color: #f5f5f5;
        }
        .sidebar-box h3{
            font-size: 15px;
            font-weight: bold;
            padding: 7px 10px;
            margin: 0;
            line-height: 1;
            background-color: #f1f1f1;
            border-bottom-color: #dfdfdf;
            text-shadow: #fff 0 1px 0;
            box-shadow: 0 1px 0 #fff;
        }
        .sidebar-box a{
            padding: 4px;
            display: block;
            padding-left: 25px;
            text-decoration: none;
            border: none;
        }
    </style>

    <div class="wxp-option-container">
        <div class="wxp-header">
            <div class="wxp-logo"></div>
            <h2>WeiXinPress设置</h2>
        </div>
        <?php
        if(isset($_POST['action'])){
            if($_POST['action']=='保存设置'){
                update_weixinpress_option();
            }
        }
        $array_weixinpress_option = get_weixinpress_option();
        ?>
        <div class="wxp-option-main">
            <form name="wxp-options" method="post" action="">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="right"><label>接口TOKEN：</label></td>
                        <td class="left">
                            <input type="text" name="wxp-token" value="<?php echo $array_weixinpress_option[WXP_TOKEN]; ?>"/>
                            <span class="wxp-notes">填写用于微信（易信）接口的TOKEN，与微信（易信）后台设置一致</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><label>欢迎信息：</label></td>
                        <td class="left">
                            <textarea name="wxp-welcome"><?php echo $array_weixinpress_option[WXP_WELCOME]; ?></textarea>
                            <span class="wxp-notes">填写用于用户订阅时发送的欢迎信息</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><label>欢迎命令：</label></td>
                        <td class="left">
                            <input type="text" name="wxp-welcome-cmd" value="<?php echo $array_weixinpress_option[WXP_WELCOME_CMD]; ?>"/>
                            <span class="wxp-notes">填写用于用户查询问候信息的命令，例如“hi”，“你好”</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><label>帮助信息：</label></td>
                        <td class="left">
                            <textarea name="wxp-help"><?php echo $array_weixinpress_option[WXP_HELP]; ?></textarea>
                            <span class="wxp-notes">填写用于用户寻求帮助时的帮助信息</span>
                        </td>
                    </tr>
                     <tr>
                        <td class="right"><label>帮助命令：</label></td>
                        <td class="left">
                            <input type="text" name="wxp-help-cmd" value="<?php echo $array_weixinpress_option[WXP_HELP_CMD]; ?>"/>
                            <span class="wxp-notes">填写用于用户寻求帮助时命令，例如“帮助”、“help”，持多个命令，中间用空格隔开</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><label>关键字长度：</label></td>
                        <td class="left">
                            <input type="text" name="wxp-keyword-length" value="<?php echo $array_weixinpress_option[WXP_KEYWORD_LENGTH]; ?>"/>
                            <span class="wxp-notes">填写用户输入的关键字长度限制，注意：单个中文字长度为2，单个英文字符或数字长度为1，例如“时间管理”长度填为8，“weixin”长度是6</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><label>是否自动回复：</label></td>
                        <td class="left">
                            <input type="checkbox" name="wxp-auto-reply" value="1" <?php if($array_weixinpress_option[WXP_AUTO_REPLY]){ ?> checked<?php } ?>/><br/>
                            <span class="wxp-notes">当用户输入关键字长度超过限定长度时，是否自动回复消息。默认不勾选，即不自动回复消息，系统认为用户要与公共账号进行人工沟通。</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><label>关键字长度提醒：</label></td>
                        <td class="left">
                            <textarea name="wxp-keyword-length-warning"><?php echo $array_weixinpress_option[WXP_KEYWORD_LENGTH_WARNING]; ?></textarea>
                            <span class="wxp-notes">当用户输入的关键字长度超出限制时，自动回复给用户的错误提示信息，结合上面面“是否自动回复”使用</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><label>关键字查询范围：</label></td>
                        <td class="left">
                            <input type="checkbox" name="wxp-keyword-in-title" value="1" <?php if($array_weixinpress_option[WXP_KEYWORD_IN_TITLE]){ ?> checked<?php } ?> readOnly=true/>标题 &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="wxp-keyword-in-content" value="1" <?php if($array_weixinpress_option[WXP_KEYWORD_IN_CONTENT]){ ?> checked<?php } ?>/>内容
                            <br/>
                            <span class="wxp-notes">选择关键词查询的范围：查询标题中是否有关键字，还是内容中有关键字，或者二者同时；二者均不选时，默认选择标题中关键字。</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><label>不包含文章类别：</label></td>
                        <td class="left">
                            <input type="text" name="wxp-category-exclude" value="<?php echo $array_weixinpress_option[WXP_CATEGORY_EXCLUDE]; ?>"/>
                            <span class="wxp-notes">填写文章搜索时不包含的文章类别的ID，ID为数字，多个类别用英文逗号(,)隔开;留空，表示查询所有类别下的文章。</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><label>关键字错误提醒：</label></td>
                        <td class="left">
                            <textarea name="wxp-keyword-error-warning"><?php echo $array_weixinpress_option[WXP_KEYWORD_ERROR_WARNING]; ?></textarea>
                            <span class="wxp-notes">当使用用户输入的关键字没有查找到相关文章时，自动回复给用户的错误提示信息，信息中用户输入的关键词用”{keyword}“表示</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><label>默认文章数：</label></td>
                        <td class="left">
                            <input type="text" name="wxp-default-article-account" value="<?php echo $array_weixinpress_option[WXP_DEFAULT_ARTICLE_ACCOUNT]; ?>"/>
                            <span class="wxp-notes">填写默认返回的文章数目，即用户不用命令分隔符指定返回数目时返回的文章数目，最大数为10</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><label>最新文章命令：</label></td>
                        <td class="left">
                            <input type="text" name="wxp-new-article-cmd" value="<?php echo $array_weixinpress_option[WXP_NEW_ARTICLE_CMD]; ?>"/>
                            <span class="wxp-notes">填写用户查询最新文章的命令，持多个命令，中间用空格隔开</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><label>随机文章命令：</label></td>
                        <td class="left">
                            <input type="text" name="wxp-rand-article-cmd" value="<?php echo $array_weixinpress_option[WXP_RAND_ARTICLE_CMD]; ?>"/>
                            <span class="wxp-notes">填写用户查询随机文章的命令，支持多个命令，中间用空格隔开</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><label>热门文章命令：</label></td>
                        <td class="left">
                            <input type="text" name="wxp-hot-article-cmd" value="<?php echo $array_weixinpress_option[WXP_HOT_ARTICLE_CMD]; ?>"/>
                            <span class="wxp-notes">填写用户查询随机文章的命令，持多个命令，中间用空格隔开</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><label>命令分隔符：</label></td>
                        <td class="left">
                            <input type="text" name="wxp-cmd-seperator" value="<?php echo $array_weixinpress_option[WXP_CMD_SEPERATOR]; ?>"/>
                            <span class="wxp-notes">填写命令分隔符，即支持使用类似“关键@6”的命令，其中“@”为命令分隔符，后面的数字为返回的文章数，最大为10</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><label>默认缩略图地址：</label></td>
                        <td class="left">
                            <input type="text" name="wxp-default-thumb" value="<?php echo $array_weixinpress_option[WXP_DEFAULT_THUMB]; ?>"/>
                            <span class="wxp-notes">填写默认缩略图地址，当文章中没有图片时，使用该地址代表的图片</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="wxp-center wxp-btn-box">
                            <input type="submit" class="wxp-submit-btn" name="action" value="保存设置"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="wxp-option-sidebar">
            <div class="sidebar-box">
                <h3>关于Weixinpress</h3>
                <a href="http://www.houqun.me" target="_blank">古侯子博客</a>
                <a href="http://www.houqun.me/bbs/forum.php?mod=forumdisplay&fid=2" target="_blank">查看插件主页</a>
                <a href="http://www.houqun.me/bbs/forum.php?mod=forumdisplay&fid=37" target="_blank">报告插件BUG</a>
                
            </div>
            <div class="sidebar-box" style="margin-top:10px;">
                <h3>赞助WeixinPress</h3>
                <a href="http://me.alipay.com/houqun" target="_blank"><b>点此赞助本插件</b></a>
                <a href="#">感谢赞助本插件的网友：</a>
                <a>西小西、*辉、*树森</a>
                <a>*洋、*宪丰、*红梅</a>
            </div>
        </div>
    </div>
<?php 
}

?>