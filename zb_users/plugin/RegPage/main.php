<?php
require '../../../zb_system/function/c_system_base.php';

require '../../../zb_system/function/c_system_admin.php';

$zbp->Load();

$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}

if (!$zbp->CheckPlugin('RegPage')) {$zbp->ShowError(48);die();}

$blogtitle='注册组件';

if(count($_POST)>0){

	if(GetVars('reset','POST')=='add'){
		RegPage_CreateCode(100);
	}

	if(GetVars('reset','POST')=='del'){
		RegPage_DelUsedCode();
	}

	if(GetVars('reset','POST')=='ept'){
		RegPage_EmptyCode();
	}
	
	$zbp->Config('RegPage')->open_reg=(int)$_POST['open_reg'];	
	$zbp->Config('RegPage')->default_level=(int)$_POST['default_level'];
	$zbp->Config('RegPage')->readme_text=$_POST['readme_text'];
	$zbp->Config('RegPage')->title_text=$_POST['title_text'];	
	$zbp->SaveConfig('RegPage');
	
	if(GetVars('addnavbar')){
		$zbp->AddItemToNavbar('item','regpage',$zbp->Config('RegPage')->title_text,$zbp->host.'?reg');
	}else{
		$zbp->DelItemToNavbar('item','regpage');
	}
	
	$zbp->SetHint('good');
	Redirect('./main.php');
}


require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';

?>
<div id="divMain">

  <div class="divHeader"><?php echo $blogtitle;?></div>
<div class="SubMenu"></div>
  <div id="divMain2">
	<form id="edit" name="edit" method="post" action="#">
<input id="reset" name="reset" type="hidden" value="" />
<table border="1" class="tableFull tableBorder">
<tr>
	<th class="td30"><p align='left'><b>选项</b><br><span class='note'></span></p></th>
	<th>
	</th>
</tr>
<tr>
	<td class="td30"><p align='left'><b>默认注册的会员等级</b></p></td>
	<td>
	<select name="default_level" style="width:200px;">
		<option value='5' <?php if($zbp->Config('RegPage')->default_level==5)echo 'selected="selected"';?>><?php echo $zbp->lang['user_level_name'][5] ;?></option>
		<option value='4' <?php if($zbp->Config('RegPage')->default_level==4)echo 'selected="selected"';?>><?php echo $zbp->lang['user_level_name'][4] ;?></option>
		<option value='3' <?php if($zbp->Config('RegPage')->default_level==3)echo 'selected="selected"';?>><?php echo $zbp->lang['user_level_name'][3] ;?></option>
	</select>
	</td>
</tr>
<tr>
	<td class="td30"><p align='left'><b>开放注册</b></p></td>
	<td><input type="text" class="checkbox" name="open_reg" value="<?php echo $zbp->Config('RegPage')->open_reg;?>" /></td>
</tr>
<tr>
	<td class="td30"><p align='left'><b>将注册链接加入导航栏</b></p></td>
	<td><input type="checkbox" name="addnavbar" value="ok" <?php if($zbp->CheckItemToNavbar('item','regpage')){?>checked="checked"<?php }?> /></td>
</tr>
<tr>
	<td class="td30"><p align='left'><b>注册页面标题</b></p></td>
	<td><input type="text" name="title_text" value="<?php echo htmlspecialchars($zbp->Config('RegPage')->title_text);?>" style="width:89%;" /></td>
</tr>
<tr>
	<td class="td30"><p align='left'><b>注册相关说明文字</b></p></td>
	<td><textarea name="readme_text" style="width:90%;height:100px;" /><?php echo htmlspecialchars($zbp->Config('RegPage')->readme_text);?></textarea></td>
</tr>
</table>
	  <hr/>
	  <p>
		<input type="submit" class="button" value="<?php echo $lang['msg']['submit']?>" />
	  </p>

<table border="1" class="tableFull tableBorder">
<tr>
	<th class="td10"></th>
	<th >邀请码</th>
	<th >用户级别(组)</th>
	<th >注册用户</th>
</tr>
<?php
$sql= $zbp->db->sql->Select($RegPage_Table,'*',null,null,null,null);
$array=$zbp->GetListCustom($RegPage_Table,$RegPage_DataInfo,$sql);
foreach ($array as $key => $reg) {
	echo '<tr>';
	echo '<td class="td15">'.$reg->ID.'</td>';
	echo '<td>'.$reg->InviteCode.'</td>';
	echo '<td class="td20">'.$zbp->lang['user_level_name'][$reg->Level].'</td>';
	echo '<td class="td20">'.($reg->AuthorID==0?'':$zbp->GetMemberByID($reg->AuthorID)->Name).'</td>';
	echo '</tr>';
}
?>
</table>

	  <hr/>
	  <p>
		<input type="submit" class="button" onclick="$('#reset').val('add');" value="生成100个邀请码" />

		<input type="submit" class="button" onclick="$('#reset').val('del');" value="删除已使用过的邀请码" />
		
		<input type="submit" class="button" onclick="$('#reset').val('ept');" value="清空所有邀请码" />
	  </p>

	</form>
	<script type="text/javascript">ActiveLeftMenu("aPluginMng");</script>
	<script type="text/javascript">AddHeaderIcon("<?php echo $bloghost . 'zb_users/plugin/RegPage/logo.png';?>");</script>	
  </div>
</div>


<?php
require $blogpath . 'zb_system/admin/admin_footer.php';

RunTime();
?>