;(function($){
  function debounce(fn, wait){var t=null;return function(){var ctx=this,args=arguments;clearTimeout(t);t=setTimeout(function(){fn.apply(ctx,args);},wait||150);};}
  function ensureDefaultScroll(){try{var style='.wp-full-overlay-sidebar-content{overflow-y:auto!important}';if(!$('#vr-customizer-scrollfix').length){$('head').append('<style id="vr-customizer-scrollfix">'+style+'</style>');}else{$('#vr-customizer-scrollfix').text(style);}var content=$('.wp-full-overlay-sidebar-content');if(content.length){content.css({'overflow-y':'auto'});} }catch(e){}}
  function ensureSpacer(){try{var style='#vr-customizer-spacer{height:120px}';if(!$('#vr-customizer-spacer-style').length){$('head').append('<style id="vr-customizer-spacer-style">'+style+'</style>');}var ct=$('#customize-theme-controls');if(ct.length&&!$('#vr-customizer-spacer').length){ct.append('<div id="vr-customizer-spacer"></div>');}}catch(e){}}
  function setSidebarHeight(){try{var content=$('.wp-full-overlay-sidebar-content');if(content.length){var header=$('.wp-full-overlay-header');var footer=$('.wp-full-overlay-footer');var hh=(header.length?header.outerHeight():0);var fh=(footer.length?footer.outerHeight():0);var h=Math.max(200,window.innerHeight-hh-fh);var pb=fh+120;content.css({'height':h+'px','max-height':h+'px','padding-bottom':pb+'px','overflow-y':'auto','box-sizing':'border-box'});ensureSpacer();$('#vr-customizer-spacer').css('height',(pb+200)+'px');console.log('CustomizerHeights',{ctx:'set',win:window.innerHeight,headerH:hh,footerH:fh,calcH:h,applied:h,paddingBottom:pb,client:content[0].clientHeight,scroll:content[0].scrollHeight});}}catch(e){}}
  function installObserver(){try{var target=($('#customize-theme-controls')[0]||$('.wp-full-overlay-sidebar-content')[0]);if(!target||target._vrObserved){return;}var mo=new MutationObserver(function(records){var structural=false;for(var i=0;i<records.length;i++){var r=records[i];if((r.addedNodes&&r.addedNodes.length)||(r.removedNodes&&r.removedNodes.length)){structural=true;break;}}if(structural){adjustCustomizerScroll();}});mo.observe(target,{childList:true,subtree:true});target._vrObserved=true;}catch(e){}}
  var _vrScrollLock=false,_vrUnlockTimer=null;
  function adjustCustomizerScroll(){try{if(_vrScrollLock){return;}ensureDefaultScroll();setSidebarHeight();}catch(e){}}
  function lockScrollAdjust(){_vrScrollLock=true;try{if(_vrUnlockTimer){clearTimeout(_vrUnlockTimer);} _vrUnlockTimer=setTimeout(function(){_vrScrollLock=false;},250);}catch(e){}}
  function getScrollContainer(){try{var sc=$('.wp-full-overlay-sidebar-content')[0]||$('#customize-theme-controls')[0]||$('#customize-controls')[0];return sc;}catch(e){return null;}}
  function smoothScrollToEl(el){try{var sc=getScrollContainer();if(!sc||!el){return;}var top=($(el).offset().top-$(sc).offset().top)+sc.scrollTop-80;if(sc.scrollTo){sc.scrollTo({top:top,behavior:'smooth'});}else{$(sc).stop().animate({scrollTop:top},200);} }catch(e){}}
  function adjustAndSmooth(el){try{adjustCustomizerScroll();setTimeout(function(){smoothScrollToEl(el);},60);}catch(e){}}

  function initServices(container){
    var add=container.nextAll('.vr-add').first();
    var hidden=add.nextAll('.vr-hidden').first();
    var setting=hidden.data('setting');var lastCaret={type:null,id:null,bookmark:null,selStart:null,selEnd:null};
    function getContent($ta){var id=$ta.attr('id');if(window.tinymce&&tinymce.get(id)){return tinymce.get(id).getContent();}return $ta.val();}
    function collect(){var tiny=null,ta=null,start=null,end=null,bm=null;try{if(lastCaret.type==='tiny'&&lastCaret.id&&window.tinymce){tiny=tinymce.get(lastCaret.id);bm=lastCaret.bookmark||(tiny?tiny.selection.getBookmark(2):null);}else if(lastCaret.type==='ta'&&lastCaret.id){ta=$('#'+lastCaret.id);start=lastCaret.selStart;end=lastCaret.selEnd;}else{var ae=document.activeElement;if(window.tinymce&&tinymce.activeEditor){tiny=tinymce.activeEditor;bm=tiny.selection.getBookmark(2);}else if(ae&&$(ae).is('textarea.vr-content')){ta=$(ae);start=ta.prop('selectionStart');end=ta.prop('selectionEnd');}}}catch(e){}var items=[];container.find('.vr-item').each(function(){items.push({bi:$(this).find('.vr-bi').val(),content:getContent($(this).find('.vr-content'))});});var json=JSON.stringify(items);hidden.val(json);try{if(window.wp&&wp.customize){wp.customize(setting,function(s){if(s&&s.set){s.set(json);}});}}catch(e){}try{if(tiny){tiny.focus();if(bm){tiny.selection.moveToBookmark(bm);}}else if(ta&&ta.length){ta.focus();if(start!=null&&end!=null){ta[0].setSelectionRange(start,end);}}}catch(e){}}
    var scheduleCollect=debounce(collect,300);
    function bindEditor(id){var attempts=0;function tryBind(){var ed=(window.tinymce?tinymce.get(id):null);if(ed&&!ed._vrBound){ed.on('keyup change input',function(){try{lastCaret.type='tiny';lastCaret.id=ed.id;lastCaret.bookmark=ed.selection.getBookmark(2);}catch(e){}scheduleCollect();});ed._vrBound=true;}else if(attempts<10){attempts++;setTimeout(tryBind,100);}}tryBind();}
    function initEditorIfNeeded(id){try{if(window.wp&&wp.editor&&(!window.tinymce||!tinymce.get(id))){wp.editor.initialize(id,{tinymce:true,quicktags:true});}}catch(e){}bindEditor(id);}

    container.on('input change','.vr-bi',function(){var v=$(this).val();var item=$(this).closest('.vr-item');var t=item.find('.vr-title');if(v){t.text(v);}else{var idx=item.index()+1;t.text('Service '+idx);}scheduleCollect();});container.on('input','.vr-content',function(){lastCaret.type='ta';lastCaret.id=$(this).attr('id');try{lastCaret.selStart=this.selectionStart;lastCaret.selEnd=this.selectionEnd;}catch(e){}scheduleCollect();});
    container.on('click','.vr-head',function(){
      var item=$(this).closest('.vr-item'),body=item.find('.vr-body'),ta=item.find('.vr-content'),id=ta.attr('id');
      body.toggle();
      if(body.is(':visible')){initEditorIfNeeded(id);try{body[0].scrollIntoView({block:'end'});}catch(e){}}    
      item.toggleClass('collapsed');
      adjustAndSmooth(item[0]);
    });
    container.on('click','.vr-remove',function(){var item=$(this).closest('.vr-item');var ta=item.find('.vr-content');var id=ta.attr('id');try{if(window.wp&&wp.editor&&id){wp.editor.remove(id);}}catch(e){}item.remove();scheduleCollect();adjustCustomizerScroll();});
    add.on('click',function(){var uid='vr-content-'+Date.now();var count=container.find('.vr-item').length+1;container.append('<div class="vr-item"><div class="vr-head"><strong class="vr-title">Service '+count+'</strong><span class="vr-caret"></span></div><div class="vr-body"><label>Bootstrap Icon slug</label><input class="vr-bi" type="text" placeholder="bi-gear"><small class="description"><a href="https://icons.getbootstrap.com/" target="_blank">icons.getbootstrap.com</a></small><br/><label class="mt-2">Content</label><textarea id="'+uid+'" class="vr-content"></textarea><div class="vr-button"><button type="button" class="button remove-button vr-remove">Remove</button></div></div></div>');initEditorIfNeeded(uid);scheduleCollect();adjustAndSmooth(container.find('.vr-item').last()[0]);});

    container.find('.vr-content').each(function(){initEditorIfNeeded($(this).attr('id'));});
    $(document).on('click','.media-modal-close',function(){setTimeout(function(){container.find('.vr-content').each(function(){initEditorIfNeeded($(this).attr('id'));});adjustCustomizerScroll();},100);});
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
      adjustAndSmooth(item[0]);
    });
    container.on('click','.vr-remove',function(){ $(this).closest('.vr-item').remove(); scheduleCollect(); adjustCustomizerScroll(); });
    var frame=null;
    container.on('click','.vr-select',function(){var item=$(this).closest('.vr-item'),idInput=item.find('.vr-img-id'),preview=item.find('.vr-preview');if(!frame){var lib=wp.media.query({type:'image'});frame=wp.media({title:'Select Image',library:lib,multiple:false});}frame.off('select');frame.on('select',function(){var att=frame.state().get('selection').first().toJSON();idInput.val(att.id);preview.html('<img src="'+(att.sizes&&att.sizes.thumbnail?att.sizes.thumbnail.url:att.url)+'" />');scheduleCollect();});frame.open();});
    add.on('click',function(){if(container.find('.vr-item').length>=limit){alert('Limit reached');return;}var count=container.find('.vr-item').length+1;container.append('<div class="vr-item"><div class="vr-head"><strong class="vr-title">Logo '+count+'</strong><span class="vr-caret"></span></div><div class="vr-body" style="display:none"><div class="vr-preview"></div><input class="vr-img-id" type="hidden" value=""><div class="vr-button"><button type="button" class="button button-secondary vr-select">Select Image</button> <button type="button" class="button remove-button vr-remove">Remove</button></div></div></div>');scheduleCollect();adjustAndSmooth(container.find('.vr-item').last()[0]);});
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
      adjustAndSmooth(item[0]);
    });
    container.on('input change','.vr-title-input',function(){var v=$(this).val();var item=$(this).closest('.vr-item');var t=item.find('.vr-title');if(v){t.text(v);}else{var idx=item.index()+1;t.text('row '+idx);}scheduleCollect();});
    container.on('click','.vr-remove',function(){ $(this).closest('.vr-item').remove(); scheduleCollect(); adjustCustomizerScroll(); });
    var frame=null;
    container.on('click','.vr-select',function(){var item=$(this).closest('.vr-item'),idInput=item.find('.vr-img-id'),preview=item.find('.vr-preview');if(!frame){var lib=wp.media.query({type:'image'});frame=wp.media({title:'Select Image',library:lib,multiple:false});}frame.off('select');frame.on('select',function(){var att=frame.state().get('selection').first().toJSON();idInput.val(att.id);preview.html('<img src="'+(att.sizes&&att.sizes.thumbnail?att.sizes.thumbnail.url:att.url)+'" />');scheduleCollect();});frame.open();});
    add.on('click',function(){if(container.find('.vr-item').length>=limit){alert('Limit reached');return;}var count=container.find('.vr-item').length+1;container.append('<div class="vr-item"><div class="vr-head"><strong class="vr-title">row '+count+'</strong><span class="vr-caret"></span></div><div class="vr-body"><label><b>Banner Image</b></label><br/><small class="description">Ukuran 380x380</small><div class="vr-preview"></div><input class="vr-img-id" type="hidden" value=""><div class="vr-button"><button type="button" class="button remove-button vr-remove">Remove</button> <button type="button" class="button button-secondary vr-select">Select Image</button></div><label class="mt-3"><b>Banner Title</b></label><input type="text" class="vr-title-input" value=""></div></div>');scheduleCollect();adjustAndSmooth(container.find('.vr-item').last()[0]);});
  }



  $(function(){
    $('.velocity-repeater').each(function(){initServices($(this));});
    $('.velocity-media-repeater').each(function(){initMedia($(this));});
    $('.velocity-smallbanner-repeater').each(function(){initSmallBanner($(this));});
    ensureDefaultScroll();
    adjustCustomizerScroll();
    installObserver();
    $(window).on('resize',debounce(function(){adjustCustomizerScroll();},150));
    $(document).on('scroll','.wp-full-overlay-sidebar-content, #customize-theme-controls, #customize-controls',function(){lockScrollAdjust();});
  });
})(jQuery);