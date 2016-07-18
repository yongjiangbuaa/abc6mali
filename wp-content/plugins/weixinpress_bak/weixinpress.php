<?php
/**
 * Plugin Name: WeixinPress
 * Plugin URI: http://www.houqun.me/bbs/forum.php?gid=1
 * Description: WeixinPress的主要功能就是能够将你的微信公众账号和你的WordPress博客关联，搜索和用户发送关键字匹配的文章，依据命令查看最新文章、热门文章和随机文章。<br />
 * Version: 0.7.0
 * Author: Will HQ , DiDiaoYu
 * Author URI: http://www.houqun.me
 */

// 切换到当前的绝对目录
chdir( dirname(__FILE__) );
// 切换到Wordpress的根目录，加载WP-LOAD.PHP文件
if (chdir('../../../')){    
    require_once( 'wp-load.php' );    
}

//define constants.  
define('WXP_TOKEN'                   , 'wxp_token');
define('WXP_WELCOME'                 , 'wxp_welcome');
define('WXP_WELCOME_CMD'             , 'wxp_welcome_cmd');
define('WXP_HELP'                    , 'wxp_help');
define('WXP_HELP_CMD'                , 'wxp_help_cmd');
define('WXP_KEYWORD_LENGTH'          , 'wxp_keyword_length');
define('WXP_AUTO_REPLY'              , 'wxp_auto_reply');
define('WXP_KEYWORD_IN_TITLE'        , 'wxp_keyword_in_title');
define('WXP_KEYWORD_IN_CONTENT'      , 'wxp_keyword_in_content');
define('WXP_CATEGORY_EXCLUDE'        , 'wxp_category_exclude');
define('WXP_KEYWORD_LENGTH_WARNING'  , 'wxp_keyword_length_warning');
define('WXP_KEYWORD_ERROR_WARNING'   , 'wxp_keyword_error_warning');
define('WXP_DEFAULT_ARTICLE_ACCOUNT' , 'wxp_default_article_account');
define('WXP_NEW_ARTICLE_CMD'         , 'wxp_new_article_cmd');
define('WXP_RAND_ARTICLE_CMD'        , 'wxp_rand_article_cmd');
define('WXP_HOT_ARTICLE_CMD'         , 'wxp_hot_article_cmd');
define('WXP_CMD_SEPERATOR'           , 'wxp_cmd_seperator');
define('WXP_DEFAULT_THUMB'           , 'wxp_default_thumb');

//$siteurl = get_option('siteurl');     
define('WXP_FOLDER'                  , dirname(plugin_basename(__FILE__)));
//define('WXP_URL'                   , $siteurl.'/wp-content/plugins/' . WXP_FOLDER);
define('WXP_URL'                     , plugins_url('', __FILE__));
define('WXP_FILE_PATH'               , dirname(__FILE__));
define('WXP_DIR_NAME'                , basename(WXP_FILE_PATH));


logger('in weixipress!!!');
//定义微信 Token
$wxp_token = get_option(WXP_TOKEN    , 'weixin');
define('WEIXIN_TOKEN'                , $wxp_token);
//定义默认缩略图
//define('WEIXIN_DEFAULT'            , $siteurl.'/wp-content/themes/Metropro/images/random2/tb'.rand(1, 12).'.jpg');
$wxp_thumb = get_option(WXP_DEFAULT_THUMB);
if(empty($wxp_thumb)){$wxp_thumb = WXP_URL.'/images/tb5.jpg';}
define('WEIXIN_DEFAULT'              , $wxp_thumb);

// Verify the connection to Weixin server with token
// Sometimes, these two functions will cause errors in some php enviroments.
// So these two functions should be disabed in formal plugin releases.
function traceHttp(){
    logger('REMOTE_ADDR: '.$_SERVER['REMOTE_ADDR'].((strpos($_SERVER['REMOTE_ADDR'], '101.226')!==false) ? ' From Weixin' : ' Unkown IP'));
    logger('QUERY_STRING: '.$_SERVER['QUERY_STRING']);
    logger('REQUEST URI: '.$_SERVER['REQUEST_URI']);
    logger('HTTP HOST: '.$_SERVER['HTTP_HOST']);
}

function logger($content){
    file_put_contents(WXP_FILE_PATH."/log.html", date('Y-m-d H:i:s ').$content.'<br>', FILE_APPEND);
}

/**
 * 导入管理设置页面代码
 */ 
require_once('admin/admin_page.php');
logger("will check signauture!");
//if(isset($_GET["signature"])){
    global $weixinpress;
       if(!isset($weixinpress)){
        $weixinpress = new WeixinPress();
        $weixinpress->valid();
        exit;
    }
//}


class WeixinPress
{
    private $items        = '';
    private $articleCount = 0;
    private $keyword      = '';
    private $arg          = '';
    private $_receive;

    public function valid()
    {
        $echoStr = isset($_GET["echostr"]) ? $_GET["echostr"]: '';

/**        if ($echoStr) {
            if ($this->checkSignature())
                die($echoStr);
            else 
                die('no access');
        } else {
            if ($this->checkSignature())**/
                $this->responseMsg();
/**            else
                die('no access');
        }
        exit;**/
    }
    
    public function responseMsg()
    {
logger('in responseMsg');
        $array_weixinpress_option      = get_weixinpress_option();
        $array_weixinpress_welcome_cmd = explode(' ', $array_weixinpress_option[WXP_WELCOME_CMD]);
        $array_weixinpress_help_cmd    = explode(' ', $array_weixinpress_option[WXP_HELP_CMD]);
        $array_weixinpress_new_cmd     = explode(' ', $array_weixinpress_option[WXP_NEW_ARTICLE_CMD]);
        $array_weixinpress_rand_cmd    = explode(' ', $array_weixinpress_option[WXP_RAND_ARTICLE_CMD]);
        $array_weixinpress_hot_cmd     = explode(' ', $array_weixinpress_option[WXP_HOT_ARTICLE_CMD]);
        $wxp_keyword_length            = $array_weixinpress_option[WXP_KEYWORD_LENGTH];
        $wxp_auto_reply                = $array_weixinpress_option[WXP_AUTO_REPLY];
        $wxp_keyword_length_warning    = $array_weixinpress_option[WXP_KEYWORD_LENGTH_WARNING];
        $wxp_keyword_error_warning     = $array_weixinpress_option[WXP_KEYWORD_ERROR_WARNING];
        $wxp_cmd_seperator             = $array_weixinpress_option[WXP_CMD_SEPERATOR];
       
        //get post data, May be due to the different environments
        //$postStr = $GLOBALS['HTTP_RAW_POST_DATA'];
        //another waty to get post data
        $postStr = file_get_contents("php://input");
logger('postStr='.$postStr);
        //extract post data
        if ( !empty($postStr) ){    
            
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $msgType = strtolower(trim($postObj->MsgType));
            if($msgType == 'event'){
                $keywords = strtolower(trim($postObj->Event));
            }else{
                $keywords = strtolower(trim($postObj->Content));
            }

            //add by HQ
            $keywordArray = explode($wxp_cmd_seperator, $keywords, 2);
            if(is_array($keywordArray)){
                $this->keyword = $keywordArray[0];
                $this->arg = $keywordArray[1];
            } else {
                $this->keyword = $keywordArray;
            }

            

            $time = time();
            $textTpl = '<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%d</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>';     
            $picTpl = ' <xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%d</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <Content><![CDATA[]]></Content>
                        <ArticleCount>%d</ArticleCount>
                        <Articles>
                        %s
                        </Articles>
                        <FuncFlag>1</FuncFlag>
                        </xml>';
            
            if(strpos($this->keyword, '/:')===false){
                
                if((count($array_weixinpress_welcome_cmd)>0)&&(in_array($this->keyword, $array_weixinpress_welcome_cmd) || $this->keyword == 'subscribe' )){
                    // 订阅时的欢迎消息
                    $weixin_welcome = $array_weixinpress_option[WXP_WELCOME];
                    $weixin_welcome = apply_filters('weixin_welcome',$weixin_welcome);
                    echo sprintf($textTpl, $fromUsername, $toUsername, $time, $weixin_welcome);
                }elseif((count($array_weixinpress_welcome_cmd)>0)&&in_array($this->keyword, $array_weixinpress_help_cmd)){
                    // 获取帮助的消息
                    $weixin_help = $array_weixinpress_option[WXP_HELP];
                    $weixin_help = apply_filters('weixin_help',$weixin_help);
                    echo sprintf($textTpl, $fromUsername, $toUsername, $time, $weixin_help);
                }elseif((count($array_weixinpress_new_cmd)>0)&&in_array($this->keyword, $array_weixinpress_new_cmd)){
                    logger('before query new<br>');
                    $this->query('new');
                    if($this->articleCount == 0){
    					$weixin_not_found = "抱歉，最新文章显示错误，请重试一下 :-) ";
    					echo sprintf($textTpl, $fromUsername, $toUsername, $time, $weixin_not_found);
    				}else{
    					echo sprintf($picTpl, $fromUsername, $toUsername, $time, $this->articleCount,$this->items);
                    }
                }elseif((count($array_weixinpress_rand_cmd)>0)&&in_array($this->keyword, $array_weixinpress_rand_cmd)){
                    $this->query('rand');
                    if($this->articleCount == 0){
    					$weixin_not_found = "抱歉，随机文章显示错误，请重试一下 :-) ";
    					echo sprintf($textTpl, $fromUsername, $toUsername, $time, $weixin_not_found);
    				}else{
    					echo sprintf($picTpl, $fromUsername, $toUsername, $time, $this->articleCount,$this->items);
                    }
                }elseif((count($array_weixinpress_hot_cmd)>0)&&in_array($this->keyword, $array_weixinpress_hot_cmd)){
                    $this->query('hot');
                    if($this->articleCount == 0){
    					$weixin_not_found = "抱歉，热门文章显示错误，请重试一下 :-) ";
    					echo sprintf($textTpl, $fromUsername, $toUsername, $time, $weixin_not_found);
                    }else{
    					echo sprintf($picTpl, $fromUsername, $toUsername, $time, $this->articleCount,$this->items);
                    }
                }else {
                    $keyword_length = mb_strwidth(preg_replace('/[\x00-\x7F]/','',$this->keyword),'utf-8')+str_word_count($this->keyword)*2;
                    $weixin_keyword_allow_length = $wxp_keyword_length;
            
                    if($keyword_length > $weixin_keyword_allow_length){
                        if($wxp_auto_reply){// 如果自动回复开启
                            $weixin_keyword_too_long = $wxp_keyword_length_warning;
                            echo sprintf($textTpl, $fromUsername, $toUsername, $time, $weixin_keyword_too_long);
                        }
                    }elseif( !empty( $this->keyword )){
                        $this->query();
                        if($this->articleCount == 0){
                            //$weixin_not_found = "抱歉，没有找到与【{$this->keyword}】相关的文章，换个关键字，可能就有结果了哦 :-) ";
                            $weixin_not_found = str_replace('{keyword}', $this->keyword, $wxp_keyword_error_warning);
                            echo sprintf($textTpl, $fromUsername, $toUsername, $time, $weixin_not_found);
                        }else{
                            echo sprintf($picTpl, $fromUsername, $toUsername, $time, $this->articleCount,$this->items);
                        }
                    }
                }
            }
        }else {
            echo "";
            exit;
        }
    }

    private function query($queryArg = NULL){
        logger('in query function and the query arg is '.$queryArg.'<br>');
        global $wp_query;

        $queryKeyword = $this->keyword;

        $weixin_count = get_option(WXP_DEFAULT_ARTICLE_ACCOUNT);
        logger('after get option');
        
        if(!empty($this->arg)) { 
            if (preg_match("/^\d*$/",$this->arg)){ // if the arg is a number or not, is_numeric($fgid)
                $weixin_count = $this->arg;
            } else { // if the arg is not a number, so we consier XXX@YYY the whole as one keyword, and we use "XXX YYY" instead of "XXX@YYY" to query information.
                $queryKeyword = $this->keyword.' '.$this->arg;
                $this->keyword = $this->keyword.'@'.$this->arg;
            }
        } 

        $weixin_count = apply_filters('weixin_count',$weixin_count);
        logger('before category exclude');

        $category_exclude = trim(get_option(WXP_CATEGORY_EXCLUDE));
        $category_exclude_array = explode(',', $category_exclude);

        if (empty($category_exclude)){
            switch ($queryArg) {
                case 'new':
                    $weixin_query_array = array('showposts' => $weixin_count , 'post_status' => 'publish' );
                    break;
                case 'rand':
                    $weixin_query_array = array('orderby' => 'rand', 'posts_per_page' => $weixin_count , 'post_status' => 'publish' );
                    break;
                 case 'hot':
                    $weixin_query_array = array('orderby' => 'meta_value_num', 'meta_key'=>'views', 'order'=>'DESC', 'posts_per_page' => $weixin_count , 'post_status' => 'publish' );
                    break;
                default:
                    $weixin_query_array = array('s' => $queryKeyword, 'posts_per_page' => $weixin_count , 'post_status' => 'publish' );
                    break;
            }
        } else {
            switch ($queryArg) {
                case 'new':
                    $weixin_query_array = array('showposts' => $weixin_count , 'post_status' => 'publish', 'category__not_in' => $category_exclude_array );
                    break;
                case 'rand':
                    $weixin_query_array = array('orderby' => 'rand', 'posts_per_page' => $weixin_count , 'post_status' => 'publish', 'category__not_in' => $category_exclude_array );
                    break;
                 case 'hot':
                    $weixin_query_array = array('orderby' => 'meta_value_num', 'meta_key'=>'views', 'order'=>'DESC', 'posts_per_page' => $weixin_count , 'post_status' => 'publish', 'category__not_in' => $category_exclude_array );
                    break;
                default:
                    $weixin_query_array = array('s' => $queryKeyword, 'posts_per_page' => $weixin_count , 'post_status' => 'publish', 'category__not_in' => $category_exclude_array );
                    break;
            }
        }

        
        $weixin_query_array = apply_filters('weixin_query',$weixin_query_array);

        $wp_query->query($weixin_query_array);
        //query_posts($weixin_query_array);

        if(have_posts()){
        	
            while (have_posts()) {
                the_post();

                global $post;

                $title = get_the_title(); 
                $content = get_the_content(); 
   
                //$excerpt = get_post_excerpt($post);
                 $excerpt = strip_tags(get_the_excerpt());
                 if(!isset($excerpt)){
                 	$except = get_post_excerpt($content);
                 }

                $thumbnail_id = get_post_thumbnail_id(get_the_id());
                
                if($thumbnail_id ){
                    $thumb = wp_get_attachment_image_src($thumbnail_id, 'thumbnail');
                    $thumb = $thumb[0];
                }else{
                    $thumb = get_post_first_image($content);
                }

                if(!$thumb && WEIXIN_DEFAULT){
                    $thumb = WEIXIN_DEFAULT;
                }

                $link = get_permalink();

                $items = $items . $this->get_item($title, $excerpt, $thumb, $link);

            }
        }

        $this->articleCount = count($wp_query->posts);
        if($this->articleCount > $weixin_count) $this->articleCount = $weixin_count;

        $this->items = $items;
    }

    public function get_item($title, $description, $picUrl, $url){
        if(!$description) $description = $title;

        return
        '
        <item>
            <Title><![CDATA['.$title.']]></Title>
            <Description><![CDATA['.$description.']]></Description>
            <PicUrl><![CDATA['.$picUrl.']]></PicUrl>
            <Url><![CDATA['.$url.']]></Url>
        </item>
        ';
    }

    /**
     * 获取微信服务器发来的信息
     */
    public function getRev()
    {
        $postStr = file_get_contents("php://input");
        $this->log($postStr);
        if (!empty($postStr)) {
            $this->_receive = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        return $this;
    }
    
    /**
     * 获取消息发送者
     */
    public function getRevFrom() {
        if ($this->_receive)
            return $this->_receive['FromUserName'];
        else 
            return false;
    }
    
    /**
     * 获取消息接受者
     */
    public function getRevTo() {
        if ($this->_receive)
            return $this->_receive['ToUserName'];
        else 
            return false;
    }
    
    /**
     * 获取接收消息的类型
     */
    public function getRevType() {
        if (isset($this->_receive['MsgType']))
            return $this->_receive['MsgType'];
        else 
            return false;
    }
    
    /**
     * 获取消息ID
     */
    public function getRevID() {
        if (isset($this->_receive['MsgId']))
            return $this->_receive['MsgId'];
        else 
            return false;
    }
    
    /**
     * 获取消息发送时间
     */
    public function getRevCtime() {
        if (isset($this->_receive['CreateTime']))
            return $this->_receive['CreateTime'];
        else 
            return false;
    }
    
    /**
     * 获取接收消息内容正文
     */
    public function getRevContent(){
        if (isset($this->_receive['Content']))
            return $this->_receive['Content'];
        else 
            return false;
    }
    
    /**
     * 获取接收消息图片
     */
    public function getRevPic(){
        if (isset($this->_receive['PicUrl']))
            return $this->_receive['PicUrl'];
        else 
            return false;
    }
    
    /**
     * 获取接收消息链接
     */
    public function getRevLink(){
        if (isset($this->_receive['Url'])){
            return array(
                'url'=>$this->_receive['Url'],
                'title'=>$this->_receive['Title'],
                'description'=>$this->_receive['Description']
            );
        } else 
            return false;
    }
    
    /**
     * 获取接收地理位置
     */
    public function getRevGeo(){
        if (isset($this->_receive['Location_X'])){
            return array(
                'x'=>$this->_receive['Location_X'],
                'y'=>$this->_receive['Location_Y'],
                'scale'=>$this->_receive['Scale'],
                'label'=>$this->_receive['Label']
            );
        } else 
            return false;
    }
    
    /**
     * 获取接收事件推送
     */
    public function getRevEvent(){
        if (isset($this->_receive['Event'])){
            return array(
                'event'=>$this->_receive['Event'],
                'key'=>$this->_receive['EventKey'],
            );
        } else 
            return false;
    }

    /**
     * 获取接收语言推送
     */
    public function getRevVoice(){
        if (isset($this->_receive['MediaId'])){
            return array(
                'mediaid'=>$this->_receive['MediaId'],
                'format'=>$this->_receive['Format'],
            );
        } else 
            return false;
    }
    

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];    
                
        $weixin_token = apply_filters('weixin_token',WEIXIN_TOKEN);
        if(isset($_GET['debug'])){
            echo "\n".'WEIXIN_TOKEN：'.$weixin_token;
        }
        $tmpArr = array($weixin_token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);//解决微信有时无法响应的bug
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}

if(!function_exists('get_post_excerpt')){

    function get_post_excerpt($content){
        //$post_excerpt = strip_tags($excerpt); 
        //if(!$post_excerpt){
            $post_excerpt = mb_substr(trim(strip_tags($content)),0,120);
        //}
        return $post_excerpt;
    }
}

if(!function_exists('get_post_first_image')){

    function get_post_first_image($post_content){
        preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $post_content, $matches);
        if($matches){
            return $matches[1][0];
        }else{
            return false;
        }
    }
}
/*
$keyword_in_title = get_option(WXP_KEYWORD_IN_TITLE);
$keyword_in_content = get_option(WXP_KEYWORD_IN_CONTENT);

if($keyword_in_content != 1){
    if(!function_exists('search_by_title_only')){
        add_filter( 'posts_search', 'search_by_title_only', 10, 2);
        function search_by_title_only( $search, &$wp_query ){
            global $wpdb;
         
            if ( empty( $search ) )
                return $search; // skip processing - no search term in query
         
            $q = $wp_query->query_vars;    
            $n = ! empty( $q['exact'] ) ? '' : '%';
         
            $search =
            $searchand = '';
         
            foreach ( (array) $q['search_terms'] as $term ) {
                $term = esc_sql( like_escape( $term ) );
                $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
                $searchand = ' AND ';
            }
         
            if ( ! empty( $search ) ) {
                $search = " AND ({$search}) ";
                if ( ! is_user_logged_in() )
                    $search .= " AND ($wpdb->posts.post_password = '') ";
            }
         
            return $search;
        }
        
    }
}

if(!function_exists('search_orderby')){

    add_filter('posts_orderby_request', 'search_orderby');
    function search_orderby($orderby = ''){
        global $wpdb,$wp_query;

        $keyword = stripslashes($wp_query->query_vars['s']);

        if($keyword){ 

            $n = !empty($q['exact']) ? '' : '%';

            preg_match_all('/".*?("|$)|((?<=[\r\n\t ",+])|^)[^\r\n\t ",+]+/', $keyword, $matches);
            $search_terms = array_map('_search_terms_tidy', $matches[0]);

            $case_when = "0";

            foreach( (array) $search_terms as $term ){
                $term = esc_sql( like_escape( $term ) );

                $case_when .= " + (CASE WHEN {$wpdb->posts}.post_title LIKE '{$term}' THEN 3 ELSE 0 END) ";
                $case_when .= " + (CASE WHEN {$wpdb->posts}.post_title LIKE '{$n}{$term}{$n}' THEN 2 ELSE 0 END) ";
                $case_when .= " + (CASE WHEN {$wpdb->posts}.post_content LIKE '{$n}{$term}{$n}' THEN 1 ELSE 0 END)";
                
            }

            return "({$case_when}) DESC, {$wpdb->posts}.post_modified DESC";
        }else{
            return $orderby;
        }
    }
}*/
?>
