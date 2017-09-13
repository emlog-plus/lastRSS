<?php
!defined('EMLOG_ROOT') && exit('access deined!');
$DB = Database::getInstance();
function plugin_setting_view(){
	require_once(EMLOG_ROOT.'/content/plugins/lastRSS-master/lastRSS_config.php');
	global $DB, $lastRSS_cache_time, $lastRSS_item_num, $lastRSS_is_urlshort, $lastRSS_is_blank, $lastRSS_urlshort_domain;
?>
<div class="heading-bg  card-views">
  <ul class="breadcrumbs">
  <li><a href="./"><i class="fa fa-home"></i> <?php echo langs('home')?></a></li>
  <li class="active">Rss订阅设置</li>
 </ul>
</div>
<style type="text/css">
	#lastRSS_form_config p, #lastRSS_form_input p, #lastRSS_form_modify p {font-size:14px;}
	ol {margin:0;padding:0;}
	.entry {overflow:hidden;zoom:1;list-style: decimal inside none;font-size:14px;padding:8px;background:#DFE1E3;border-top:1px solid #E9EAEB;border-bottom:1px solid #C7CCD1;}
	.right {float:right;display:inline;}
	#lastRSS_form_modify {display:none;}
</style>
<?php if(isset($_GET['setting'])):?>
<div class="actived alert alert-success alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
插件设置完成
</div>
<?php endif;?>
<div class="row">
<div class="col-md-12">
<div class="panel panel-default card-view">
<div class="panel-body"> 
<form action="?plugin=lastRSS-master&action=setting&do=config" method="post" id="lastRSS_form_config">
<div class="form-group">
<label class="control-label mb-10">缓存时间<sup>留空或"0"表示不缓存,推荐设置大于"1200"</sup></label>
<input type="text" name="lastRSS_cache_time" value="<?php echo $lastRSS_cache_time; ?>" size="20" class="form-control" /> 
</div>
<div class="form-group">
<label class="control-label mb-10">显示条数<sup>前台轮播显示的条数,推荐大于"1"</sup></label>
<input type="text" name="lastRSS_item_num" value="<?php echo $lastRSS_item_num; ?>" size="20" class="form-control"/>
 </div>
	<p><input type="checkbox" value="1" name="lastRSS_is_urlshort"<?php if($lastRSS_is_urlshort == 1):?> checked<?php endif;?> /> 使用网址缩短服务来缩短RSS日志的链接(已存入数据库的链接不变)，需服务器开启curl，开启会增加程序运行时间</p>
	<p><label for="lastRSS_urlshort_domain">短网址服务商：</label>
		<select name="lastRSS_urlshort_domain" id="lastRSS_urlshort_domain">
			<option value="t.cn"<?php if($lastRSS_urlshort_domain == 't.cn'):?> selected<?php endif;?>>t.cn</option>
			<option value="bit.ly"<?php if($lastRSS_urlshort_domain == 'bit.ly'):?> selected<?php endif;?>>bit.ly</option>
			<option value="j.mp"<?php if($lastRSS_urlshort_domain == 'j.mp'):?> selected<?php endif;?>>j.mp</option>
			<option value="goo.gl"<?php if($lastRSS_urlshort_domain == 'goo.gl'):?> selected<?php endif;?>>goo.gl</option>
		</select>
	</p>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-md-12">
<div class="panel panel-default card-view">
<div class="panel-body"> 
<input type="checkbox" value="1" name="lastRSS_is_blank"<?php if($lastRSS_is_blank == 1):?> checked<?php endif;?> /> 新窗口打开前台显示的RSS日志链接&nbsp; <input type="submit" value="保存参数" class="btn btn-primary btn-sm" /></p>
</form>
</div></div></div></div>
<div class="row">
<div class="col-md-12">
<div class="panel panel-default card-view">
<div class="panel-body"> 
<form action="?plugin=lastRSS-master&action=setting&do=add" method="post" id="lastRSS_form_input">
<div class="form-group">
<label class="control-label mb-10">
RSS URL</label>
<input type="text" name="url" class="form-control"  />
</div>
<div class="form-group">
<label class="control-label mb-10">
标题(不填则插件自动获取)</label>
<input type="text" name="title" class="form-control"  />
</div>
<div class="form-group text-center">
<input type="submit" value="添加 RSS" class="btn  btn-success" /></div>
</form>
</div>
</div>
</div>
</div>
<?php
	$feeds = getRssFeeds();
	if ($DB->num_rows($feeds) != 0):
?>
<div class="row">
<div class="col-md-12">
<div class="panel panel-default card-view">
<div class="panel-body"> 
管理：(双击单行可对应修改该RSS的URL和标题)
</div>
</div>
</div>
</div>
<form action="?plugin=lastRSS-master&action=setting&do=update" method="post" id="lastRSS_form_modify">
	<p>RSS URL： <input type="text" name="url" id="modify_url" style="width: 270px;" />&nbsp; 标题： <input type="text" name="title" id="modify_title" style="width: 200px;" />&nbsp; <input type="hidden" name="id" id="modify_id" /> <input type="submit" value="更新 RSS" class="submit" /> <input type="submit" id="cancel_update" value="取消更新" /></p>
</form>
<div class="row">
<div class="col-md-12">
<div class="panel panel-default card-view">
<div class="panel-body"> 
<ol>
<?php
	while ($item = $DB->fetch_array($feeds)) {
?>
<li class="entry" data-id="<?php echo $item['id']; ?>" data-url="<?php echo $item['url']; ?>" data-title="<?php echo $item['title']; ?>">
	<span class="right"><a href="javascript:;" class="del">删除</a></span>
	《<span ><?php echo $item['title']; ?></span>》 URL：<span style="color:#666; font-size: 12px;"><?php echo $item['url']; ?></span>
</li>
<?php 
	}
	else:
?>
<div class="containertitle"><b>暂无RSS地址，请导入</b></div>
<?php
	endif;
?>
</ol>
</div>
</div>
</div>
</div>
<?php } ?>
<?php
function plugin_setting(){
	$do = isset($_GET['do']) ? $_GET['do'] : '';
	if ($do == 'del') {
		$id = intval($_POST['id']);
		if ($id != 0) {
			deleteFeed($id);
			$msg = array('success' => 'true');
			exit(json_encode($msg));
		}
	} elseif ($do == 'update') {
		$id = intval($_POST['id']);
		$url = isset($_POST['url']) ? trim($_POST['url']) : '';
		if (!empty($url)) {
			$title = (isset($_POST['title']) && trim($_POST['title']) !='') ? $_POST['title'] : getTitle($url);
			$title = addslashes($title);
			if (!empty($title)) {
				updateFeed($id, $url, $title);
				header("Location:plugin.php?plugin=lastRSS-maste&setting=true");
			} else {
				emMsg("RSS修改失败，插件无法获取{$url}的标题，请自行添加标题再添加该RSS");
			}
		} else {
			emMsg("RSS修改失败，RSS地址不能为空");
		}
	} elseif ($do == 'add') {
		$url = isset($_POST['url']) ? trim($_POST['url']) : '';
		if (!empty($url)) {
			$title = (isset($_POST['title']) && trim($_POST['title']) !='') ? $_POST['title'] : getTitle($url);
			$title = addslashes($title);
			if (!empty($title)) {
				insertFeed($url, $title);
				updateLogs();
				header("Location:plugin.php?plugin=lastRSS-maste&setting=true");
			} else {
				emMsg("RSS导入失败，插件无法获取{$url}的标题，请自行添加标题再导入该RSS");
			}
		} else {
			emMsg("RSS导入失败，RSS地址不能为空");
		}
	} elseif ($do == 'config') {
		$lastRSS_cache_time = isset($_POST['lastRSS_cache_time']) ? intval($_POST['lastRSS_cache_time']) : 0;
		$lastRSS_item_num = isset($_POST['lastRSS_item_num']) ? intval($_POST['lastRSS_item_num']) : 2;
		$lastRSS_is_urlshort = isset($_POST['lastRSS_is_urlshort']) ? intval($_POST['lastRSS_is_urlshort']) : 0;
		$lastRSS_urlshort_domain = isset($_POST['lastRSS_urlshort_domain']) ? trim($_POST['lastRSS_urlshort_domain']) : 't.cn';
		$lastRSS_is_blank = isset($_POST['lastRSS_is_blank']) ? intval($_POST['lastRSS_is_blank']) : 0;
		$data = "<?php
		\$lastRSS_cache_time = ".$lastRSS_cache_time.";
		\$lastRSS_item_num = ".$lastRSS_item_num.";
		\$lastRSS_is_urlshort = ".$lastRSS_is_urlshort.";
		\$lastRSS_urlshort_domain = '".$lastRSS_urlshort_domain."';
		\$lastRSS_is_blank = ".$lastRSS_is_blank.";
	?>";
		$file = EMLOG_ROOT.'/content/plugins/lastRSS-master/lastRSS_config.php';
		@ $fp = fopen($file, 'wb') OR emMsg('读取文件失败，如果您使用的是Unix/Linux主机，请修改文件/content/plugins/lastRSS-master/lastRSS_config.php的权限为777。如果您使用的是Windows主机，请联系管理员，将该文件设为everyone可写');
		@ $fw =	fwrite($fp,$data) OR emMsg('写入文件失败，如果您使用的是Unix/Linux主机，请修改文件/content/plugins/lastRSS-master/lastRSS_config.php的权限为777。如果您使用的是Windows主机，请联系管理员，将该文件设为everyone可写');
		fclose($fp);
		header("Location:plugin.php?plugin=lastRSS-master&setting=true");
	}
}
?>
<script type="text/javascript">
	$(function(){
		$body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $("html") : $("body")) : $("html,body");
		$("#menu_mg").addClass('active');
$("#lastRSS").addClass('active-page');
setTimeout(hideActived,2600);

		$(".entry").dblclick(function(){
			var feedUrl = $(this).data("url"),
				feedId = $(this).data("id"),
				feedTitle = $(this).data("title");
			$("#modify_url").val(feedUrl);
			$("#modify_title").val(feedTitle);
			$("#modify_id").val(feedId);
			$("#lastRSS_form_modify").slideDown(400,function(){
				$body.animate({
					scrollTop: $(this).offset().top - 40
				}, 500);
				$("#modify_url").focus();
			});
		});
		$('.del').click(function(){
			if (confirm('确认删除这个 RSS 数据源?')) {
				var _parent = $(this).parents('.entry');
				var _id = _parent.data('id');
				$.post('./plugin.php?plugin=lastRSS-maste&action=setting&do=del', {id: _id}, function(e, s){
					if (s === 'success') {
						_parent.remove();
					} else {
						alert('删除出错！请前往http://xiaosong.org/留言');
					}
				})
			}
			return false;
		});
		$("#cancel_update").click(function(){
			$("#lastRSS_form_modify").slideUp(200,function(){
				$("#modify_url").blur();
			});
			return false;
		})
	})
</script>
