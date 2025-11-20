;(function($){
  function debounce(fn, wait){var t=null;return function(){var ctx=this,args=arguments;clearTimeout(t);t=setTimeout(function(){fn.apply(ctx,args);},wait||150);};}

  function initServices(container){
    var add=container.nextAll('.vr-add').first();
    var hidden=add.nextAll('.vr-hidden').first();
    var setting=hidden.data('setting');
    function getContent($ta){var id=$ta.attr('id');if(window.tinymce&&tinymce.get(id)){return tinymce.get(id).getContent();}return $ta.val();}
    function collect(){var items=[];container.find('.vr-item').each(function(){items.push({bi:$(this).find('.vr-bi').val(),content:getContent($(this).find('.vr-content'))});});var json=JSON.stringify(items);hidden.val(json);try{if(window.wp&&wp.customize){wp.customize(setting,function(s){if(s&&s.set){s.set(json);}});}}catch(e){}}
    var scheduleCollect=debounce(collect,150);
    function bindEditor(id){var attempts=0;function tryBind(){var ed=(window.tinymce?tinymce.get(id):null);if(ed&&!ed._vrBound){ed.on('change keyup input NodeChange',function(){$('#'+id).trigger('keyup');});ed._vrBound=true;}else if(attempts<10){attempts++;setTimeout(tryBind,100);}}tryBind();}
    function initEditorIfNeeded(id){try{if(window.wp&&wp.editor&&(!window.tinymce||!tinymce.get(id))){wp.editor.initialize(id,{tinymce:true,quicktags:true});}}catch(e){}bindEditor(id);}
    container.on('change input','input',scheduleCollect);
    container.on('input change','.vr-bi',function(){var v=$(this).val();var item=$(this).closest('.vr-item');var t=item.find('.vr-title');if(v){t.text(v);}else{var idx=item.index()+1;t.text('Service '+idx);}scheduleCollect();});
    container.on('click','.vr-head',function(){
      var item=$(this).closest('.vr-item'),body=item.find('.vr-body'),ta=item.find('.vr-content'),id=ta.attr('id');
      body.toggle();
      if(body.is(':visible')){initEditorIfNeeded(id);}    
      item.toggleClass('collapsed');
    });
    container.on('click','.vr-remove',function(){var item=$(this).closest('.vr-item');var ta=item.find('.vr-content');var id=ta.attr('id');try{if(window.wp&&wp.editor&&id){wp.editor.remove(id);}}catch(e){}item.remove();scheduleCollect();});
    add.on('click',function(){var uid='vr-content-'+Date.now();var count=container.find('.vr-item').length+1;container.append('<div class="vr-item"><div class="vr-head"><strong class="vr-title">Service '+count+'</strong><span class="vr-caret"></span></div><div class="vr-body"><label>Bootstrap Icon slug</label><input class="vr-bi" type="text" placeholder="bi-gear"><small class="description"><a href="https://icons.getbootstrap.com/" target="_blank">icons.getbootstrap.com</a></small><br/><label class="mt-2">Content</label><textarea id="'+uid+'" class="vr-content"></textarea><div class="vr-button"><button type="button" class="button remove-button vr-remove">Remove</button></div></div></div>');initEditorIfNeeded(uid);scheduleCollect();});
    container.on('keyup','.vr-content',scheduleCollect);
  }

  function initMedia(container){
    var add=container.nextAll('.vr-add').first();
    var hidden=add.nextAll('.vr-hidden').first();
    var setting=hidden.data('setting');
    var limit=parseInt(container.attr('data-limit'))||20;
    function collect(){var items=[];container.find('.vr-item').each(function(){var id=$(this).find('.vr-img-id').val();if(id){items.push({id:parseInt(id)});}});var json=JSON.stringify(items);hidden.val(json);try{if(window.wp&&wp.customize){wp.customize(setting,function(s){if(s&&s.set){s.set(json);}});}}catch(e){}}
    var scheduleCollect=debounce(collect,150);
    container.on('click','.vr-head',function(){
      var item=$(this).closest('.vr-item');
      item.find('.vr-body').toggle();
      item.toggleClass('collapsed');
    });
    container.on('click','.vr-remove',function(){ $(this).closest('.vr-item').remove(); scheduleCollect(); });
    var frame=null;
    container.on('click','.vr-select',function(){var item=$(this).closest('.vr-item'),idInput=item.find('.vr-img-id'),preview=item.find('.vr-preview');if(!frame){var lib=wp.media.query({type:'image'});frame=wp.media({title:'Select Image',library:lib,multiple:false});}frame.off('select');frame.on('select',function(){var att=frame.state().get('selection').first().toJSON();idInput.val(att.id);preview.html('<img src="'+(att.sizes&&att.sizes.thumbnail?att.sizes.thumbnail.url:att.url)+'" />');scheduleCollect();});frame.open();});
    add.on('click',function(){if(container.find('.vr-item').length>=limit){alert('Limit reached');return;}var count=container.find('.vr-item').length+1;container.append('<div class="vr-item"><div class="vr-head"><strong class="vr-title">Logo '+count+'</strong><span class="vr-caret"></span></div><div class="vr-body" style="display:none"><div class="vr-preview"></div><input class="vr-img-id" type="hidden" value=""><div class="vr-button"><button type="button" class="button button-secondary vr-select">Select Image</button> <button type="button" class="button remove-button vr-remove">Remove</button></div></div></div>');scheduleCollect();});
  }

  function initSmallBanner(container){
    var add=container.nextAll('.vr-add').first();
    var hidden=add.nextAll('.vr-hidden').first();
    var setting=hidden.data('setting');
    var limit=parseInt(container.attr('data-limit'))||3;
    function collect(){var items=[];container.find('.vr-item').each(function(){var id=$(this).find('.vr-img-id').val();var title=$(this).find('.vr-title-input').val();if(id||title){items.push({id:id?parseInt(id):0,title:title||''});}});var json=JSON.stringify(items);hidden.val(json);try{if(window.wp&&wp.customize){wp.customize(setting,function(s){if(s&&s.set){s.set(json);}});}}catch(e){}}
    var scheduleCollect=debounce(collect,150);
    container.on('click','.vr-head',function(){
      var item=$(this).closest('.vr-item');
      item.find('.vr-body').toggle();
      item.toggleClass('collapsed');
    });
    container.on('input change','.vr-title-input',function(){var v=$(this).val();var item=$(this).closest('.vr-item');var t=item.find('.vr-title');if(v){t.text(v);}else{var idx=item.index()+1;t.text('row '+idx);}scheduleCollect();});
    container.on('click','.vr-remove',function(){ $(this).closest('.vr-item').remove(); scheduleCollect(); });
    var frame=null;
    container.on('click','.vr-select',function(){var item=$(this).closest('.vr-item'),idInput=item.find('.vr-img-id'),preview=item.find('.vr-preview');if(!frame){var lib=wp.media.query({type:'image'});frame=wp.media({title:'Select Image',library:lib,multiple:false});}frame.off('select');frame.on('select',function(){var att=frame.state().get('selection').first().toJSON();idInput.val(att.id);preview.html('<img src="'+(att.sizes&&att.sizes.thumbnail?att.sizes.thumbnail.url:att.url)+'" />');scheduleCollect();});frame.open();});
    add.on('click',function(){if(container.find('.vr-item').length>=limit){alert('Limit reached');return;}var count=container.find('.vr-item').length+1;container.append('<div class="vr-item"><div class="vr-head"><strong class="vr-title">row '+count+'</strong><span class="vr-caret"></span></div><div class="vr-body"><label><b>Banner Image</b></label><br/><small class="description">Ukuran 380x380</small><div class="vr-preview"></div><input class="vr-img-id" type="hidden" value=""><div class="vr-button"><button type="button" class="button remove-button vr-remove">Remove</button> <button type="button" class="button button-secondary vr-select">Select Image</button></div><label class="mt-3"><b>Banner Title</b></label><input type="text" class="vr-title-input" value=""></div></div>');scheduleCollect();});
  }

  function initAllServicesEditors(){ $('.velocity-repeater .vr-content').each(function(){var id=$(this).attr('id');try{if(window.wp&&wp.editor&&(!window.tinymce||!tinymce.get(id))){wp.editor.initialize(id,{tinymce:true,quicktags:true});}}catch(e){}var ed=(window.tinymce?tinymce.get(id):null);if(ed&&!ed._vrBound){ed.on('change keyup input NodeChange', function(){ $('#'+id).trigger('keyup'); });ed._vrBound=true;} }); }

  $(function(){
    $('.velocity-repeater').each(function(){initServices($(this));});
    $('.velocity-media-repeater').each(function(){initMedia($(this));});
    $('.velocity-smallbanner-repeater').each(function(){initSmallBanner($(this));});
    $(document).on('click','.media-modal-close',function(){setTimeout(initAllServicesEditors,100);});
    setTimeout(initAllServicesEditors,200);
  });
})(jQuery);