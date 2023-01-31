class SA_SwiftMenu2 {
    constructor(main={wrapper:null, radius:100,rotate:0,background:'rgba(0,0,0,.5)'},sub={wrapper:null,radius:100, rotate:0,background:'rgba(0,0,0,.5)'})  {	  	
      this.wrapper =main.wrapper;	    
      this.radius = main.radius;
      this.menu = main.wrapper;
      this.tabs = $('#'+main.wrapper+' div');
      this.stateDataShow = false;
      this.rotate = Number(main.rotate)*1;	    
      this.background = main.background;
      this.background2 = sub.background;
      this.subMenu = sub.wrapper;
      /*console.log(sub.wrapper);*/
      this.radius2 = sub.radius;
      this.rotate2 = Number(sub.rotate)*1;
    }

    switf() {	  		  	
      var $vm = this;
      
            var radius = $vm.radius;
            var radius2 = $vm.radius2;
          var background2 =$vm.background2;
          var fields = $('#'+$vm.wrapper+'> div');
          var fields2 = $('#'+$vm.subMenu+'> div');
          var cover = $('#'+$vm.wrapper);
          var cover2 = $('#'+$vm.subMenu);
          var container = $('#'+$vm.wrapper);
          var width = container.width();
          var height = container.height();
          var Mainwrapper =  $('#'+$vm.wrapper);
          var idmain = 'switf'+$vm.wrapper;
          var idsub = 'switf'+$vm.subMenu;
          
          if(background2 === undefined){
              background2 ='rgba(0,0,0,.5)';
          }

          var stateDataShow2 = false;
          try{
              cover2.append('<p id="'+idsub+'">');
              cover.append('<p id="'+idmain+'">');
          }catch(e){

          }
        Mainwrapper.on('click', function(){

              if ($vm.stateDataShow === false) {

                  fields.css('display','block');
                  $vm.stateDataShow = true;

                  if (fields.length <4) {				
                      //determining interval of menus if less dan 4 they should come closer
                      var angle = $vm.rotate, step = (2*Math.PI) / 7;			
                  }else{
                      //determining interval of menus
                      var angle =$vm.rotate, step = (2*Math.PI) / fields.length;
                  }
                  let count = 0;
                  let top1 = 0;
                  let left1 =0;
                  fields.each(function() {
                      var x = Math.round(width/2 + radius * Math.cos(angle) - $(this).width()/2);
                      var y = Math.round(height/2 + radius * Math.sin(angle) - $(this).height()/2);
                      /*if(window.console) {
                          //console.log($(this).text(), x, y);
                      }*/
                      if (count ===0) {
                          count++;
                          top1 = y-0;					    	
                          left1 = y+45;
                      }
                      $(this).css({
                          left: x +43.3+ 'px',
                          top: y +45+ 'px'
                      });
                      angle += step;
                  })
                  
                  $('#'+$vm.wrapper+'> span:first-child').css({'z-index':'101','position':'relative'});
                  /*left1 -= 30;
                  top1 -=40;*/
                  $vm.pane(idmain,left1, top1, $vm.background,radius,10);

              }else if ($vm.stateDataShow === true) {
                  $vm.minizeAll(idmain,idsub,fields,fields2,stateDataShow2);
                  stateDataShow2 = false;
              }
      }).find($('#'+$vm.subMenu+'> span:first-child')).click(function(event){	
              event.stopPropagation();	
              
              
              var container2 = cover2.first();
              /*console.log(fields2);*/
              var width2 = container2.width();
              var height2 = container2.height();
              var Mainwrapper2 =  container2;
              var left2 = 0;
              var top2 = 0;
              if (stateDataShow2 === false) {

                  //fields2.css('display','block');
                  fields2.addClass('d-show');
                  stateDataShow2 = true;

                  if (fields2.length <4) {				
                      //determining interval of menus if less dan 4 they should come closer
                      var angle = $vm.rotate2, step = (2*Math.PI) / 7;			
                  }else{
                      //determining interval of menus
                      var angle =$vm.rotate2, step = (2*Math.PI) / fields2.length;
                  }

                  let count = 0;
                  fields2.each(function() {
                      var x = Math.round(width2/2 + radius2 * Math.cos(angle) - $(this).width()/2);
                      var y = Math.round(height2/2 + radius2 * Math.sin(angle) - $(this).height()/2);
                      /*if(window.console) {
                          //console.log($(this).text(), x, y);
                      }*/
                      if(count==0){
                          left2 = y;
                          top2 = y;
                          count++;
                      }

                      $(this).css({
                          left: x +'px',
                          top: y + 'px'
                      });
                      angle += step;
                  });
                  $('#'+$vm.subMenu+'> span:first-child').css({'z-index':'103','position':'relative'});
                  left2 -= 30;
                  top2 -=40;
                  $vm.pane(idsub,left2, top2, background2,radius2,100);
              }else if (stateDataShow2 === true) {
                  //$vm.minimizeSub(idsub,fields2,stateDataShow2);
                  $('#'+idsub).attr('style','');
                  //return menu back to ==> hide splitted menu
                  fields2.each(function() {			    
                      $(this).css({
                          left: '0px',
                          top: '0px'
                      });
                  })
                  setTimeout(function() {
                      stateDataShow2 = false;
                      //fields2.css('display','none');
                      fields2.removeClass('d-show');

                  }, 100);

                  /*$('#flatKT').attr('style','');
                  //return menu back to ==> hide splitted menu
                  fields2.each(function() {			    
                      $(this).css({
                          left: '0px',
                          top: '0px'
                      });
                  })
                  setTimeout(function() {
                      stateDataShow2 = false;
                      //fields2.css('display','none');
                      fields2.removeClass('d-show');

                  }, 100);
*/
              }
      });;
       
  
        /*
        */
      
    }
    
    //pane for sub menu
    pane(id,left2, top2, backgroundu,radiusu,pos){
          $('#'+id).attr('style','position:absolute;left:'+left2+'px;top:'+top2+'px;z-index:'+pos+';background:'+backgroundu+';border-radius:50%;padding:10px;width:'+radiusu*3+'px;height:'+radiusu*3+'px;');
    }

  minizeAll(idmain,idsub,fields,fields2,stateDataShow2){
        try{
            var $vm = this ;
            //return menu back to ==> hide splitted menu
                  fields.each(function() {			    
                      $(this).css({
                          left: '0px',
                          top: '0px'
                      });
                  })

                  fields2.each(function() {			    
                      $(this).css({
                          left: '0px',
                          top: '0px'
                      });
                  })

                  try{
                      //remove round pane for sub memu
                      $('#'+idsub).attr('style','');
                      $('#'+idmain).attr('style','');
                  }catch(e){}
                  
                  setTimeout(function() {
                      $vm.stateDataShow = false;
                      fields.css('display','none');
                      
                      fields2.removeClass('d-show');
                      stateDataShow2 =false;

                  }, 100);
      }catch(e){console.log(e)}
    }

     minimizeSub(idsub,fields2,stateDataShow2){
    
            var $vm = this ;
            //return menu back to ==> hide splitted menu
          $('#'+idsub).attr('style','');
                  //return menu back to ==> hide splitted menu
                  fields2.each(function() {			    
                      $(this).css({
                          left: '0px',
                          top: '0px'
                      });
                  })
                  setTimeout(function() {
                      stateDataShow2 = false;
                      //fields2.css('display','none');
                      fields2.removeClass('d-show');

                  }, 100);

          /*fields2.each(function() {			    
              $(this).css({
                  left: '0px',
                  top: '0px'
              });
          })

          try{
              //remove round pane for sub memu
              $('#'+idsub).attr('style','');						
          }catch(e){}
          
          setTimeout(function() {						
              fields2.removeClass('d-show');
              stateDataShow2 =false;

          }, 100);*/
    }
       clickOutside(){
           var $vm = this;
           $('.swift').parents().click(function(e){
                $vm.tabs.each(function() {			    
                  $(this).css({
                      left: '0px',
                      top: '0px'
                  });
              })
              setTimeout(function() {
                  $vm.stateDataShow = false;
                  $vm.tabs.css('display','none');

              }, 100);
            }).children().click(function(event){
                event.stopPropagation();
            });
           /*$("body").click(function(e) { 
           if($(e.target).is('.swift')){ 
                
           }else{
               
           }
       });*/
       }

  }
