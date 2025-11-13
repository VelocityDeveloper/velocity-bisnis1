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
            echo '<div class="vr-body" style="display:none">';
            echo '<label>Bootstrap Icon slug</label><input class="vr-bi" type="text" value="'.$bi.'" placeholder="bi-gear">';
            echo '<small class="description"><a href="https://icons.getbootstrap.com/" target="_blank">icons.getbootstrap.com</a></small>';
            echo '<label class="mt-2">Content</label><textarea id="'.$id.'" class="vr-content">'.esc_textarea($content).'</textarea>';
            echo '<div class="mt-2"><button type="button" class="button-link vr-remove">Remove</button></div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '<button type="button" class="button button-primary vr-add">Add Item</button>';
        echo '<input type="hidden" class="vr-hidden" value="'. esc_attr($value) .'">';
        echo <<<JS
<script>(function($){var root=$(".velocity-repeater").last(),add=root.nextAll(".vr-add").first(),hidden=add.nextAll(".vr-hidden").first();function getContent(\$ta){var id=\$ta.attr("id");if(window.tinymce&&tinymce.get(id)){return tinymce.get(id).getContent();}return \$ta.val();}function collect(){var items=[];root.find(".vr-item").each(function(){items.push({bi:$(this).find(".vr-bi").val(),content:getContent($(this).find(".vr-content"))});});var json=JSON.stringify(items);hidden.val(json);try{if(window.wp&&wp.customize){wp.customize("{$this->id}",function(s){if(s&&s.set){s.set(json);}});}}catch(e){}}root.on("change input","input",collect);root.on("input change",".vr-bi",function(){var v=$(this).val();var item=$(this).closest(".vr-item");var t=item.find(".vr-title");if(v){t.text(v);}else{var idx=item.index()+1;t.text("Service "+idx);}collect();});root.on("click",".vr-head",function(){var item=$(this).closest(".vr-item"),body=item.find(".vr-body"),ta=item.find(".vr-content"),id=ta.attr("id");body.slideToggle(150,function(){if(body.is(":visible")){try{if(window.wp&&wp.editor&&(!window.tinymce||!tinymce.get(id))){wp.editor.initialize(id,{tinymce:true,quicktags:true});}}catch(e){}}});item.toggleClass("collapsed");});root.on("click",".vr-remove",function(){ $(this).closest(".vr-item").remove(); collect(); });add.on("click",function(){var uid="vr-content-"+Date.now();var count=root.find('.vr-item').length+1;root.append('<div class="vr-item"><div class="vr-head"><strong class="vr-title">row '+count+'</strong><span class="vr-caret"></span></div><div class="vr-body" style="display:none"><label>Bootstrap Icon slug</label><input class="vr-bi" type="text" placeholder="bi-gear"><small class="description"><a href="https://icons.getbootstrap.com/" target="_blank">icons.getbootstrap.com</a></small><label class="mt-2">Content</label><textarea id="'+uid+'" class="vr-content"></textarea><div class="mt-2"><button type="button" class="button-link vr-remove">Remove</button></div></div></div>'); collect();});root.on("keyup",".vr-content",collect);})(jQuery);</script>
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