<?php
if (class_exists('WP_Customize_Control') && !class_exists('Velocity_Services_Repeater_Control')) {
class Velocity_Services_Repeater_Control extends WP_Customize_Control {
    public $type = 'velocity_services_repeater';
    public function enqueue() { if (function_exists('wp_enqueue_editor')) wp_enqueue_editor(); }
    public function render_content() {
        $label = esc_html($this->label);
        $value = $this->value();
        $items = json_decode($value, true);
        if (!is_array($items)) { $items = array(); }
        echo '<span class="customize-control-title">'.$label.'</span>';
        echo '<style>.velocity-repeater .vr-item{border:1px solid #ddd;background:#fff;margin-bottom:10px}.velocity-repeater .vr-head{padding:8px 12px;background:#f7f7f7;border-bottom:1px solid #e5e5e5;display:flex;align-items:center;justify-content:space-between;cursor:pointer}.velocity-repeater .vr-title{font-weight:600}.velocity-repeater .vr-caret{width:0;height:0;border-left:5px solid transparent;border-right:5px solid transparent;border-top:6px solid #666;transition:transform .15s}.velocity-repeater .vr-item.collapsed .vr-caret{transform:rotate(-180deg)}.velocity-repeater .vr-body{padding:12px}.velocity-repeater .vr-remove{color:#c00;text-decoration:none}</style>';
        echo '<div class="velocity-repeater">';
        $i = 0;
        foreach ($items as $item) {
            $bi = isset($item['bi']) ? esc_attr($item['bi']) : '';
            $content = isset($item['content']) ? $item['content'] : '';
            $id = 'vr-content-'.intval(++$i);
            echo '<div class="vr-item">';
            echo '<div class="vr-head"><strong class="vr-title">'.('Service '.intval($i)).'</strong><span class="vr-caret"></span></div>';
            echo '<div class="vr-body">';
            echo '<label>Bootstrap Icon slug</label><input class="vr-bi" type="text" value="'.$bi.'" placeholder="bi-gear">';
            echo '<small class="description"><a href="https://icons.getbootstrap.com/" target="_blank">icons.getbootstrap.com</a></small>';
            echo '<label class="mt-2">Content</label><textarea id="'.$id.'" class="vr-content">'.esc_textarea($content).'</textarea>';
            echo '<div class="vr-button"><button type="button" class="button-link vr-remove">Remove</button></div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '<button type="button" class="button button-primary vr-add">Add Item</button>';
        echo '<input type="hidden" class="vr-hidden" value="'. esc_attr($value) .'">';
        echo <<<JS
<script>(function($){var root=$(".velocity-repeater").last(),add=root.nextAll(".vr-add").first(),hidden=add.nextAll(".vr-hidden").first();function getContent(\$ta){var id=\$ta.attr("id");if(window.tinymce&&tinymce.get(id)){return tinymce.get(id).getContent();}return \$ta.val();}function collect(){var items=[];root.find(".vr-item").each(function(){items.push({bi:$(this).find(".vr-bi").val(),content:getContent($(this).find(".vr-content"))});});var json=JSON.stringify(items);hidden.val(json);try{if(window.wp&&wp.customize){wp.customize("{$this->id}",function(s){if(s&&s.set){s.set(json);}});}}catch(e){}}var collectTimer=null;function scheduleCollect(){if(collectTimer){clearTimeout(collectTimer);}collectTimer=setTimeout(collect,150);}root.on("change input","input",scheduleCollect);root.on("input change",".vr-bi",function(){var v=$(this).val();var item=$(this).closest(".vr-item");var t=item.find(".vr-title");if(v){t.text(v);}else{var idx=item.index()+1;t.text("Service "+idx);}scheduleCollect();});root.on("click",".vr-head",function(){var item=$(this).closest(".vr-item"),body=item.find(".vr-body"),ta=item.find(".vr-content"),id=ta.attr("id");body.slideToggle(150,function(){if(body.is(":visible")){try{if(window.wp&&wp.editor&&(!window.tinymce||!tinymce.get(id))){wp.editor.initialize(id,{tinymce:true,quicktags:true});}}catch(e){}}});item.toggleClass("collapsed");});root.on("click",".vr-remove",function(){ $(this).closest(".vr-item").remove(); scheduleCollect(); });add.on("click",function(){var uid="vr-content-"+Date.now();var count=root.find('.vr-item').length+1;root.append('<div class="vr-item"><div class="vr-head"><strong class="vr-title">Service '+count+'</strong><span class="vr-caret"></span></div><div class="vr-body"><label>Bootstrap Icon slug</label><input class="vr-bi" type="text" placeholder="bi-gear"><small class="description"><a href="https://icons.getbootstrap.com/" target="_blank">icons.getbootstrap.com</a></small><label class="mt-2">Content</label><textarea id="'+uid+'" class="vr-content"></textarea><div class="vr-button"><button type="button" class="button-link vr-remove">Remove</button></div></div></div>'); try{if(window.wp&&wp.editor){wp.editor.initialize(uid,{tinymce:true,quicktags:true});}}catch(e){} scheduleCollect();});root.on("keyup",".vr-content",scheduleCollect);})(jQuery);</script>
JS;
    }
}
}
function velocity_sanitize_services_repeater($value){
    $arr = json_decode($value, true);
    if (!is_array($arr)) return '[]';
    $out = array();
    foreach ($arr as $item){
        $bi_raw = isset($item['bi']) ? $item['bi'] : '';
        $bi = strtolower(preg_replace('/[^a-z0-9\-]/','', $bi_raw));
        $content = isset($item['content']) ? wp_kses_post($item['content']) : '';
        $out[] = array('bi'=>$bi,'content'=>$content);
    }
    return wp_json_encode($out);
}

if (class_exists('WP_Customize_Control') && !class_exists('Velocity_Media_Repeater_Control')) {
class Velocity_Media_Repeater_Control extends WP_Customize_Control {
    public $type = 'velocity_media_repeater';
    public $limit = 20;
    public function render_content() {
        $label = esc_html($this->label);
        $value = $this->value();
        $items = json_decode($value, true);
        if (!is_array($items)) { $items = array(); }
        if (isset($this->limit)) { $this->limit = intval($this->limit); }
        echo '<span class="customize-control-title">'.$label.'</span>';
        echo '<style>.velocity-media-repeater .vr-item{border:1px solid #ddd;background:#fff;margin-bottom:10px}.velocity-media-repeater .vr-head{padding:8px 12px;background:#f7f7f7;border-bottom:1px solid #e5e5e5;display:flex;align-items:center;justify-content:space-between;cursor:pointer}.velocity-media-repeater .vr-title{font-weight:600}.velocity-media-repeater .vr-caret{width:0;height:0;border-left:5px solid transparent;border-right:5px solid transparent;border-top:6px solid #666;transition:transform .15s}.velocity-media-repeater .vr-item.collapsed .vr-caret{transform:rotate(-180deg)}.velocity-media-repeater .vr-body{padding:12px}.velocity-media-repeater .vr-remove{color:#c00;text-decoration:none}.velocity-media-repeater .vr-preview img{max-width:100%;height:auto;display:block}</style>';
        echo '<div class="velocity-media-repeater" data-limit="'.intval($this->limit).'">';
        $i = 0;
        foreach ($items as $item) {
            $id = isset($item['id']) ? absint($item['id']) : 0;
            $thumb = $id ? wp_get_attachment_image_url($id, 'thumbnail') : '';
            echo '<div class="vr-item">';
            echo '<div class="vr-head"><strong class="vr-title">'.('Logo '.intval(++$i)).'</strong><span class="vr-caret"></span></div>';
            echo '<div class="vr-body" style="display:none">';
            echo '<div class="vr-preview">'.($thumb ? '<img src="'.$thumb.'" />' : '').'</div>';
            echo '<input class="vr-img-id" type="hidden" value="'.$id.'">';
            echo '<div class="vr-button"><button type="button" class="button button-secondary vr-select">Select Image</button> <button type="button" class="button-link vr-remove">Remove</button></div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '<button type="button" class="button button-primary vr-add">Add Item</button>';
        echo '<input type="hidden" class="vr-hidden" value="'. esc_attr($value) .'">';
        echo <<<JS
<script>(function($){var root=$('.velocity-media-repeater').last(),add=root.nextAll('.vr-add').first(),hidden=add.nextAll('.vr-hidden').first();var limit=parseInt(root.attr('data-limit'))||20;function collect(){var items=[];root.find('.vr-item').each(function(){var id=$(this).find('.vr-img-id').val();if(id){items.push({id:parseInt(id)});}});var json=JSON.stringify(items);hidden.val(json);try{if(window.wp&&wp.customize){wp.customize("{$this->id}",function(s){if(s&&s.set){s.set(json);}});}}catch(e){}}var collectTimer=null;function scheduleCollect(){if(collectTimer){clearTimeout(collectTimer);}collectTimer=setTimeout(collect,150);}root.on('click','.vr-head',function(){var item=$(this).closest('.vr-item');item.find('.vr-body').slideToggle(150);item.toggleClass('collapsed');});root.on('click','.vr-remove',function(){ $(this).closest('.vr-item').remove(); scheduleCollect(); });var frame=null;root.on('click','.vr-select',function(){var item=$(this).closest('.vr-item'),idInput=item.find('.vr-img-id'),preview=item.find('.vr-preview');if(!frame){var lib=wp.media.query({type:'image'});frame=wp.media({title:'Select Image',library:lib,multiple:false});}frame.off('select');frame.on('select',function(){var att=frame.state().get('selection').first().toJSON();idInput.val(att.id);preview.html('<img src="'+(att.sizes&&att.sizes.thumbnail?att.sizes.thumbnail.url:att.url)+'" />');scheduleCollect();});frame.open();});add.on('click',function(){if(root.find('.vr-item').length>=limit){alert('Limit reached');return;}var count=root.find('.vr-item').length+1;root.append('<div class="vr-item"><div class="vr-head"><strong class="vr-title">row '+count+'</strong><span class="vr-caret"></span></div><div class="vr-body" style="display:none"><div class="vr-preview"></div><input class="vr-img-id" type="hidden" value=""><div class="vr-button"><button type="button" class="button button-secondary vr-select">Select Image</button> <button type="button" class="button-link vr-remove">Remove</button></div></div></div>');scheduleCollect();});})(jQuery);</script>
JS;
    }
}
}

function velocity_sanitize_client_repeater($value){
    $arr = json_decode($value, true);
    if (!is_array($arr)) return '[]';
    $out = array();
    foreach ($arr as $item){
        $id = isset($item['id']) ? absint($item['id']) : 0;
        if ($id) { $out[] = array('id'=>$id); }
    }
    return wp_json_encode($out);
}

if (class_exists('WP_Customize_Control') && !class_exists('Velocity_SmallBanner_Repeater_Control')) {
class Velocity_SmallBanner_Repeater_Control extends WP_Customize_Control {
    public $type = 'velocity_smallbanner_repeater';
    public $limit = 3;
    public function render_content() {
        $label = esc_html($this->label);
        $value = $this->value();
        $items = json_decode($value, true);
        if (!is_array($items)) { $items = array(); }
        if (isset($this->limit)) { $this->limit = intval($this->limit); }
        echo '<span class="customize-control-title">'.$label.'</span>';
        echo '<style>.velocity-smallbanner-repeater .vr-item{border:1px solid #ddd;background:#fff;margin-bottom:10px}.velocity-smallbanner-repeater .vr-head{padding:8px 12px;background:#f7f7f7;border-bottom:1px solid #e5e5e5;display:flex;align-items:center;justify-content:space-between;cursor:pointer}.velocity-smallbanner-repeater .vr-title{font-weight:600}.velocity-smallbanner-repeater .vr-caret{width:0;height:0;border-left:5px solid transparent;border-right:5px solid transparent;border-top:6px solid #666;transition:transform .15s}.velocity-smallbanner-repeater .vr-item.collapsed .vr-caret{transform:rotate(-180deg)}.velocity-smallbanner-repeater .vr-body{padding:12px}.velocity-smallbanner-repeater .vr-remove{color:#c00;text-decoration:none}.velocity-smallbanner-repeater .vr-preview img{width:100%;height:auto;display:block}</style>';
        echo '<div class="velocity-smallbanner-repeater" data-limit="'.intval($this->limit).'">';
        $i = 0;
        foreach ($items as $item) {
            $id = isset($item['id']) ? absint($item['id']) : 0;
            $title = isset($item['title']) ? esc_html($item['title']) : '';
            $thumb = $id ? wp_get_attachment_image_url($id, 'thumbnail') : '';
            echo '<div class="vr-item">';
            echo '<div class="vr-head"><strong class="vr-title">'.($title ? $title : 'row '.intval(++$i)).'</strong><span class="vr-caret"></span></div>';
            echo '<div class="vr-body">';
            echo '<label><b>Banner Image</b></label><br/><small class="description">Ukuran 380x380</small>';
            echo '<div class="vr-preview">'.($thumb ? '<img src="'.$thumb.'" />' : '').'</div>';
            echo '<input class="vr-img-id" type="hidden" value="'.$id.'">';
            echo '<div class="vr-button"><button type="button" class="button remove-button">Remove</button> <button type="button" class="button button-secondary vr-select">Select Image</button></div>';
            echo '<label class="mt-3">Banner Title</label><input type="text" class="vr-title-input" value="'.$title.'">';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '<button type="button" class="button button-primary vr-add">Add Item</button>';
        echo '<input type="hidden" class="vr-hidden" value="'. esc_attr($value) .'">';
        echo <<<JS
<script>(function($){var root=$('.velocity-smallbanner-repeater').last(),add=root.nextAll('.vr-add').first(),hidden=add.nextAll('.vr-hidden').first();var limit=parseInt(root.attr('data-limit'))||3;function collect(){var items=[];root.find('.vr-item').each(function(){var id=$(this).find('.vr-img-id').val();var title=$(this).find('.vr-title-input').val();if(id||title){items.push({id:id?parseInt(id):0,title:title||''});}});var json=JSON.stringify(items);hidden.val(json);try{if(window.wp&&wp.customize){wp.customize("{$this->id}",function(s){if(s&&s.set){s.set(json);}});}}catch(e){}}var collectTimer=null;function scheduleCollect(){if(collectTimer){clearTimeout(collectTimer);}collectTimer=setTimeout(collect,150);}root.on('click','.vr-head',function(){var item=$(this).closest('.vr-item');item.find('.vr-body').slideToggle(150);item.toggleClass('collapsed');});root.on('input change','.vr-title-input',function(){var v=$(this).val();var item=$(this).closest('.vr-item');var t=item.find('.vr-title');if(v){t.text(v);}else{var idx=item.index()+1;t.text('row '+idx);}scheduleCollect();});root.on('click','.vr-remove',function(){ $(this).closest('.vr-item').remove(); scheduleCollect(); });var frame=null;root.on('click','.vr-select',function(){var item=$(this).closest('.vr-item'),idInput=item.find('.vr-img-id'),preview=item.find('.vr-preview');if(!frame){var lib=wp.media.query({type:'image'});frame=wp.media({title:'Select Image',library:lib,multiple:false});}frame.off('select');frame.on('select',function(){var att=frame.state().get('selection').first().toJSON();idInput.val(att.id);preview.html('<img src="'+(att.sizes&&att.sizes.thumbnail?att.sizes.thumbnail.url:att.url)+'" />');scheduleCollect();});frame.open();});add.on('click',function(){if(root.find('.vr-item').length>=limit){alert('Limit reached');return;}var count=root.find('.vr-item').length+1;root.append('<div class="vr-item"><div class="vr-head"><strong class="vr-title">row '+count+'</strong><span class="vr-caret"></span></div><div class="vr-body"><label><b>Banner Image</b></label><br/><small class="description">Ukuran 380x380</small><div class="vr-preview"></div><input class="vr-img-id" type="hidden" value=""><div class="vr-button"><button type="button" class="button button-secondary vr-select">Select Image</button><button type="button" class="button-link vr-remove">Remove</button></div><label class="mt-3">Banner Title</label><input type="text" class="vr-title-input" value=""></div></div>');scheduleCollect();});})(jQuery);</script>
JS;
    }
}
}

function velocity_sanitize_smallbanner_repeater($value){
    $arr = json_decode($value, true);
    if (!is_array($arr)) return '[]';
    $out = array();
    foreach ($arr as $item){
        $id = isset($item['id']) ? absint($item['id']) : 0;
        $title = isset($item['title']) ? sanitize_text_field($item['title']) : '';
        if ($id || $title !== '') { $out[] = array('id'=>$id,'title'=>$title); }
    }
    return wp_json_encode($out);
}