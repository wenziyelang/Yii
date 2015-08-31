<?php  
namespace common\library;
use yii\helpers\Html;
use backend\models\Model;
use Yii;
class FieldAddForm {
    
    function images($field, $value, $fieldinfo){
        extract($fieldinfo);
        $setting = unserialize($setting);
        extract($setting);
        $csrfParam = Yii::$app->request->csrfParam;
        $csrfToken = Yii::$app->request->csrfToken;
        $uploadUrl = Yii::$app->urlManager->createUrl('/content/upload');
        $douhao = '"';
        if(isset($issystem)&&$issystem==1){
            $arr = 'info';
        }else{
            $arr = 'sub';
        }
//        $html = $name.'<form><input id="'.$field.'_upload" name="'.$field.'_upload" type="file" multiple="true"></form>';
        $html = '<div class="attaclist"><div id="'.$field.'"><ul id="'.$field.'_ul"></ul></div><span class="input-group-btn"><button type="button" class="btn btn-white" onclick="openiframe(\'js/images/images_upload.html\',\''.$field.'_ul\',\'多图上传\',810,400,20)">上传文件</button></span></div>';
		return $html;
    }

   function image($field, $value, $fieldinfo){
        extract($fieldinfo);
        $setting = unserialize($setting);
        extract($setting);
        $csrfParam = Yii::$app->request->csrfParam;
        $csrfToken = Yii::$app->request->csrfToken;
        $uploadUrl = Yii::$app->urlManager->createUrl('/content/upload');
//		$html = "<script type='text/javascript'>$(document).ready(function(){
//            $('#{$field}_upload').uploadify({
//                'multi'    : false,
//                'queueSizeLimit' : 1,
//                'uploadLimit' : 1,
//                'buttonText': '上传图片',
//                'fileExt': '*.jpg;*.jpeg;*.gif;*.png',
//                'formData': {
//                    '{$csrfParam}': '{$csrfToken}',
//                },
//                'swf': 'js/uploadify.swf',
//                'uploader': '{$uploadUrl}',
//                'onUploadSuccess' : function(file, data, response) {
//                    data = JSON.parse(data);
//                    $('#{$field}_url').val(data.url);
//                },
//
//            });})</script>";
        if(isset($issystem)&&$issystem==1){
            $arr = 'info';
        }else{
            $arr = 'sub';
        }
//        $html .= '<form><input id="'.$field.'_upload" name="'.$field.'_upload" type="file" multiple="true"></form>
//        <input type="text" id ="'.$field.'_url" name = "'.$arr.'['.$field.']" style="margin-top: -45px; margin-left: 140px;">';
        $html = '<div class="input-group">
				   <input type="text" value="" class="form-control" id="thumb" name="'.$arr.'['.$field.']" size="100">
					<span class="input-group-btn">
					<button type="button" class="btn btn-white" onclick="openiframe(\'js/images/image_upload.html\',\'thumb\',\'上传附件\',810,400,1)">上传文件</button>
					</span>
				</div> ';
		return $html;
    }    
    
    function editor($field, $value, $fieldinfo) {
        extract($fieldinfo);
        $setting = unserialize($setting);
        extract($setting);
        if(!$height) $height = 400;
        if(empty($value)){
            if(!empty($defaultvalue)){
                $value = $defaultvalue;
            }else{
                $value = '';
            }
        }
        if($minlength || $pattern) $allow_empty = '';
        if(isset($issystem)&&$issystem==1){
            $arr = 'info';
        }else{
            $arr = 'sub';
        }
		$str = '<script type="text/javascript">$(document).ready(function(){var ue2 =  UE.getEditor("'.$field.'",{
        initialFrameHeight :'.$height.',
       });})</script>';
		$str .= '<script id="'.$field.'" name="'.$arr.'['.$field.']" type="text/plain">'.$value.'</script>';
        return  $str;
    }      
 
    function datetime($field, $value, $fieldinfo) {
        extract($fieldinfo);
        if(isset($issystem)&&$issystem==1){
            $arr = 'info';
        }else{
            $arr = 'sub';
        }
		date_default_timezone_set('PRC'); 
		$str = '<input  type="text"  name="'.$arr.'['.$field.']'.'"  id="'.$field.'"  value="'.date('Y-m-d H:i:s',time()).'"  class="date">&nbsp;
				<script  type="text/javascript">
					Calendar.setup({
					weekNumbers: true,
					inputField : "'.$field.'",
					trigger    : "'.$field.'",
					dateFormat: "%Y-%m-%d %H:%M:%S",
					showTime: true,
					minuteStep: 1,
					onSelect   : function() {this.hide();}
					});
				</script>';
        return $str;
    }
    
    function linkage($field, $value, $fieldinfo){
        extract($fieldinfo);
        $setting = unserialize($setting);
        extract($setting);
        //$model_array = Model::findOne($linkageid)->toArray();
        $model_array = Model::findOne($linkageid);
        if(!empty($model_array)){
            $model_array = $model_array->toArray();
            $tablePrefix =  Yii::$app->components['db']['tablePrefix'];//表前缀
            $conte_array = Yii::$app->db->createCommand('SELECT id,title FROM '.$tablePrefix.$model_array['table_name'])->queryAll();
             if(isset($issystem)&&$issystem==1){
                $arr = 'info';
            }else{
                $arr = 'sub';
            }
	    $html = '';
					
		switch($setting['boxtype']) {


			case 'select':
            $html .= '<div id="'.$field.'"><select id="'.$field.'_select" style="padding: 6px 12px;"><option value="">请选择</option>';
			foreach($conte_array as $key => $value){
                    $html .= '<option value="'.$value['id'].'">'.$value['title'].'</option>';
                } 
			$html .= ' </select></div>';
			break;

			case 'multiple':
//                $string .= Html::dropDownList($arr.'['.$field.'][]', $value, $option,[ 'id' => $field, 'multiple' => 'multiple']);
				$html .= '<select multiple="" class="width-80 chosen-select" id="'.$field.'" data-placeholder="Choose a Country..." name="'.$arr.'['.$field.'][]'.'">
									<option value="">&nbsp;</option>
									';foreach($conte_array as $key => $value){
										$html .= '<option value="'.$value['id'].'">'.$value['title'].'</option>';
									}
				$html .= '</select>';
			break;
		}

            return $html;
      }
    }
            
            
    
    function text($field, $value, $fieldinfo) {
        extract($fieldinfo);
        $setting = unserialize($setting);
        extract($setting);
        if(empty($value)){
            if(!empty($defaultvalue)){
                $value = $defaultvalue;
            }else{
                $value = '';
            }
        }
        
	$type = $ispassword ? 'password' : 'text';

        if(isset($issystem)&&$issystem==1){
            $arr = 'info';
        }else{
            $arr = 'sub';
        }
		return '<input  type="text"  name="'.$arr.'['.$field.']"  id="'.$field.'"  value="'.$value.'"  class="form-control">';
    }
    
    function textarea($field, $value, $fieldinfo) {
        extract($fieldinfo);
        $setting = unserialize($setting);
        extract($setting);
        
        if(empty($value)){
            if(!empty($defaultvalue)){
                $value = $defaultvalue;
            }else{
                $value = '';
            }
        }
        
        $allow_empty = 'empty:true,';
        if($minlength || $pattern) $allow_empty = '';
       if(isset($issystem)&&$issystem==1){
            $arr = 'info';
        }else{
            $arr = 'sub';
        }
		$str = '<textarea  name="'.$arr.'['.$field.']" id="'.$field.'" class="form-control"  cols="60"  rows="3">'.$value.'</textarea>';
        return $str;
    }
       
  function box($field, $value, $fieldinfo) {
                extract($fieldinfo);
		$setting = unserialize($setting);
		if(empty($value)){
                    if(!empty($defaultvalue)){
                        $value = $defaultvalue;
                    }else{
                        $value = '';
                    }
                }
                
		$options = explode("\n",$setting['options']);

		foreach($options as $_k) {
			$v = explode("|",$_k);
			$k = trim($v[1]);
			$option[$k] = $v[0];
		}
		$values = explode(',',$value);
		$value = array();
		foreach($values as $_k) {
			if($_k != '') $value[] = $_k;
		}
		$value = implode(',',$value);
                
                if(isset($issystem)&&$issystem==1){
                    $arr = 'info';
                }else{
                    $arr = 'sub';
                }
        $string = '';
		switch($setting['boxtype']) {
			case 'radio':
				$string .= Html::radioList($arr.'['.$field.']', $value, $option, [ 'id' => $field]);
			break;

			case 'checkbox':
                $string .= Html::checkboxList($arr.'['.$field.'][]', $value, $option, [ 'id' => $field]);
			break;

//			case 'select':
//                $string .= Html::dropDownList($arr.'['.$field.']', 1, $option,[ 'id' => $field]);
//			break;

			case 'multiple':
//                $string .= Html::dropDownList($arr.'['.$field.'][]', $value, $option,[ 'id' => $field, 'multiple' => 'multiple']);
				$string .= '<select multiple="" class="width-80 chosen-select" id="'.$field.'" data-placeholder="Choose a Country..." name="'.$arr.'['.$field.'][]'.'">
									<option value="">&nbsp;</option>
									';foreach($option as $key=>$val){
										$string .= '<option value="'.$key.'">'.$val.'</option>';
									}
				$string .= '</select>';
			break;
		}
		return $string;
    }
        
    
     function number($field, $value, $fieldinfo) {
       extract($fieldinfo);
       $setting = unserialize($setting);
       extract($setting);
        if(empty($value)){
            if(!empty($defaultvalue)){
                $value = $defaultvalue;
            }else{
                $value = '';
            }
        }
        if(isset($issystem)&&$issystem==1){
            $arr = 'info';
        }else{
            $arr = 'sub';
        }
       return "<input type='text' min = '".$minnumber."' max = '".$maxnumber."' name='".$arr."[".$field."]' id='".$field."' value='".$value."' style='height:34px;border:1px solid #e2e2e4;'>";
    }
	
	function islink($field, $value, $fieldinfo) {
		extract($fieldinfo);
       $setting = unserialize($setting);
       extract($setting);
		if($value) {
			$url = $this->data['url'];
			$checked = 'checked';
			$_GET['islink'] = 1;
		} else {
			$disabled = 'disabled';
			$url = $checked = '';
			$_GET['islink'] = 0;
		}
		if(isset($issystem)&&$issystem==1){
            $arr = 'info';
        }else{
            $arr = 'sub';
        }
		$size = isset($fieldinfo['size']) ? $fieldinfo['size'] : 25;
		$html = '<div class="col-sm-6 input-group" style="padding:0px;"><input type="text" name="linkurl" id="linkurl" value="'.$url.'" size="'.$size.'" maxlength="255" '.$disabled.' class="form-control input-text"></div>
				<select name="'.$arr.'['.$field.']" id="'.$field.'" onclick="ruselinkurl();" style="padding: 6px;"><option value="0">无转向链接</option><option value="1">使用转向链接</option></select>';
		$html .= "<script type='text/javascript'>
	function ruselinkurl() {
        if($('#".$field."').val()=='1') {
                $('#linkurl').attr('disabled',false);
                return false;
        } else {
                $('#linkurl').attr('disabled','true');
        }
	}
			</script>";
		return $html;
	}
	function title($field, $value, $fieldinfo) {
        extract($fieldinfo);
        $setting = unserialize($setting);
        extract($setting);
        if(empty($value)){
            if(!empty($defaultvalue)){
                $value = $defaultvalue;
            }else{
                $value = '';
            }
        }
        if(isset($issystem)&&$issystem==1){
            $arr = 'info';
        }else{
            $arr = 'sub';
        }
		$html = '<span class="input-group-addon">标题</span>
			<input type="text" name="'.$arr.'['.$field.']" id="'.$field.'" maxlength="80" value="" class="form-control" />
			<span class="input-group-btn"><button class="btn btn-white" type="button" onclick="check_title();">重复检测</button></span>';
		$html .= '<script  type="text/javascript">
	function check_title() {
        var title = $("#title").val();
		var catid = $("#catid").val();
		var modelid = $("#modelid").val();
        if(title=="") {
            alert("请填写标题");
            $("#title").focus();
        } else {
            $.post("index.php?r=content/checktitle", { title: title,catid: catid,modelid: modelid},
                function(data){
                    if(data=="ok") {
                        alert("没有重复标题");
                    } else if(data=="no") {
                        alert("有完全相同的标题存在");
                    } 
                });
        }
    }
</script>';
		return $html;
//		return '<input  type="text"  name="'.$arr.'['.$field.']"  id="'.$field.'"  value="'.$value.'"  class="form-control">';
    }
	
	function copyfrom($field, $value, $fieldinfo) {
		extract($fieldinfo);
       $setting = unserialize($setting);
       extract($setting);
		if(isset($issystem)&&$issystem==1){
            $arr = 'info';
        }else{
            $arr = 'sub';
        }
		$html = '<div style="padding:0px;" class="col-sm-6 input-group"><input type="text" name="'.$arr.'['.$field.']" id="'.$field.'" value="" class="form-control input-text"></div>
				<select onchange="change_value(\''.$field.'\',this.value)" name="'.$field.'_data" style="padding: 7px;">
				<option value="0">≡请选择≡</option>
				<option value="1">百利天下留学</option>
				<option value="2">美加百利留学</option>
				<option value="3">前程百利考试</option>
				</select>';
		return $html;
	}

    /**
     * 万能字段
     */
//	function omnipotent($field, $value, $fieldinfo) {
//		extract($fieldinfo);
//       $setting = unserialize($setting);
//	   extract($setting);
//		$formtext = str_replace('{FIELD_VALUE}',$value,$formtext);
////		$formtext = str_replace('{MODELID}',$this->modelid,$formtext);
//		preg_match_all('/{FUNC\((.*)\)}/',$formtext,$_match);
//		foreach($_match[1] as $key=>$match_func) {
//			$string = '';
//			$params = explode('~~',$match_func);
//			$user_func = $params[0];
//			$string = $user_func($params[1]);
//			$formtext = str_replace($_match[0][$key],$string,$formtext);
//		}
////		$id  = $this->id ? $this->id : 0;
////		$formtext = str_replace('{ID}',$id,$formtext);
////		$errortips = $this->fields[$field]['errortips'];
////		if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:'.$minlength.',max:'.$maxlength.',onerror:"'.$errortips.'"});';
//
////		if($errortips) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"'.$errortips.'",onfocus:"'.$errortips.'"}).inputValidator({min:1,onerror:"'.$errortips.'"});';
//		return $formtext;
//	}

    /**
     * 相关内容
     */
    function relation($field, $value, $fieldinfo) {
        extract($fieldinfo);
        $setting = unserialize($setting);
        extract($setting);
        if(isset($issystem)&&$issystem==1){
            $arr = 'info';
        }else{
            $arr = 'sub';
        }
        return "<div class='input-group'>
                    <input type='hidden' name='".$arr."[".$field."]' id='".$field."' value='".$value."' >
                    <input type='text' name='search' id='relation_search' class='form-control' style='width: 200px;'>
                    <span class='input-group-btn pull-left'>
                    <button class='btn btn-white' type='button' onclick='relation_add(\"index.php?r=content/relation&cid=".$_GET['catid']."\");'>搜索</button>
                    </span>
                </div>
                <div class='tasks-widget'>
                    <ul class='task-list' id='relation_result'></ul>
                </div>
                ";
    }
    
    
}
