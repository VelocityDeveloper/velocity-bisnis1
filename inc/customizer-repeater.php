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
        echo '<div class="velocity-repeater">';
        $i = 0;
        foreach ($items as $item) {
            $bi = isset($item['bi']) ? esc_attr($item['bi']) : '';
            $content = isset($item['content']) ? $item['content'] : '';
            $id = 'vr-content-'.intval(++$i);
            echo '<div class="vr-item">';
            echo '<label>Bootstrap Icon slug</label><input class="vr-bi" type="text" value="'.$bi.'" placeholder="bi-gear">';
            echo '<small class="description"><a href="https://icons.getbootstrap.com/" target="_blank">icons.getbootstrap.com</a></small>';
            echo '<label class="mt-2">Content</label><textarea id="'.$id.'" class="vr-content">'.esc_textarea($content).'</textarea>';
            echo '<button type="button" class="button vr-remove">Remove</button>';
            echo '</div>';
            echo '<script>(function($){if(window.wp&&wp.editor){wp.editor.initialize("'.$id.'",{tinymce:true,quicktags:true});}})(jQuery);</script>';
        }
        echo '</div>';
        echo '<button type="button" class="button button-primary vr-add">Add Item</button>';
        echo '<input type="hidden" class="vr-hidden" value="'. esc_attr($value) .'">';
        echo <<<JS
<script>(function($){var root=$(".velocity-repeater").last(),add=root.nextAll(".vr-add").first(),hidden=add.nextAll(".vr-hidden").first();function getContent(\$ta){var id=\$ta.attr("id");if(window.tinymce&&tinymce.get(id)){return tinymce.get(id).getContent();}return \$ta.val();}function collect(){var items=[];root.find(".vr-item").each(function(){items.push({bi:$(this).find(".vr-bi").val(),content:getContent($(this).find(".vr-content"))});});var json=JSON.stringify(items);hidden.val(json);wp.customize("{$this->id}",function(s){s.set(json);});}root.on("change input","input",collect);root.on("click",".vr-remove",function(){ $(this).closest(".vr-item").remove(); collect(); });add.on("click",function(){var uid="vr-content-"+Date.now();root.append('<div class="vr-item"><label>Bootstrap Icon slug</label><input class="vr-bi" type="text" placeholder="bi-gear"><small class="description"><a href="https://icons.getbootstrap.com/" target="_blank">icons.getbootstrap.com</a></small><label class="mt-2">Content</label><textarea id="'+uid+'" class="vr-content"></textarea><button type="button" class="button vr-remove">Remove</button></div>');if(window.wp&&wp.editor){wp.editor.initialize(uid,{tinymce:true,quicktags:true});} collect();});root.on("keyup",".vr-content",collect);})(jQuery);</script>
JS;
    }
}
}
function velocity_sanitize_services_repeater($value){
    $arr = json_decode($value, true);
    if (!is_array($arr)) return '[]';
    $out = array();
    foreach ($arr as $item){
        $bi = isset($item['bi']) ? sanitize_key($item['bi']) : '';
        $content = isset($item['content']) ? wp_kses_post($item['content']) : '';
        $out[] = array('bi'=>$bi,'content'=>$content);
    }
    return wp_json_encode($out);
}