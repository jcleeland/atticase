

<script>
$(document).ready(function(){
  $('.dropdown span.<?php echo $pagername ?>ddmenu').on("click", function(e){
    $.each($('.last-menu'), function(i, ddmenus) {
        $(this).hide();
    });
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
  });
  
  $('.clearAllOrders').click(function() {
      clearPagerOrder('<?php echo $pagername ?>');
      loadPagerOrder('<?php echo $pagername ?>');
      var functionname='load<?php echo ucfirst($pagername) ?>';
      console.log(functionname);
      window[functionname]();
  })
  
  loadPagerOrder('<?php echo $pagername ?>');
});

</script>
<div class='pager rounded-bottom'>&nbsp;
    <div class="float-right mr-1 ml-0 pl-1 pr-1 small pointer pagerbutton" id="<?php echo $pagername ?>last" title='last page' value=''><img src='images/end.svg' /></div>
    <div class="float-right mr-0 ml-0 pl-1 pr-1 small pointer pagerbutton" id="<?php echo $pagername ?>end" title='next page' value='9'><img src='images/chevron-right.svg' /></div>
    <div class="float-right m-0 p-0 pl-1 pr-1 small pagerposition" id="<?php echo $pagername ?>position"></div>
    <div class="float-right ml-0 mr-0 pl-1 pr-1 small pointer pagerbutton" id="<?php echo $pagername ?>start" title='previous page' value='0'><img src='images/chevron-left.svg' /></div>
    <div class="float-right ml-1 mr-0 pl-1 pr-1 small pointer pagerbutton" id="<?php echo $pagername ?>first" title='first page' value=''><img src='images/start.svg' /></div>
    <div class="float-right mr-1 pl-1 pr-1 text-muted small rounded pagerlight" id="<?php echo $pagername ?>total"></div>
    <div class="float-right m1-1 pl-1 pr-0 text-muted small rounded pagerlight"><input class="text-muted small pagerlight ml-1 pl-1 pr-0 pt-1" style="width: 18px; border: 0" id="<?php echo $pagername ?>qty" title="Quantity shown" value='10' /></div>
    
    <div class="float-left ml-1 pl-1 pr-0 text-muted smaller pagerlight">
        <div class="dropdown">
            <button class="btn dropdown-toggle smaller p-1 m-0" type="button" id="<?php echo $pagername ?>sortOptions" data-toggle="dropdown" aria-haspoup="true" aria-expanded="false">
                Sort
            </button>
            <ul class='dropdown-menu small p-1 m-1' style="background-color: rgba(186, 232, 163, 0.8);" id="pager_name_<?php echo $pagername ?>">
                <?php
                foreach($oct->caseitems as $key=>$val) { ?>
                <li class="dropdown-submenu" id="<?php echo $pagername ?>-order-field-<?php echo $key ?>">
                    <span class="<?php echo $pagername ?>ddmenu pointer" ><?php echo $val['Title'] ?></span>
                    <ul class="dropdown-menu smaller p-1 m-1 last-menu" style="background-color: rgba(186, 232, 163, 0.8);"><?php
                    foreach($val['Sort'] as $skey=>$sval) {?>
                        <li><span class="pointer filterOrder" id="<?php echo $pagername ?>-order-field-<?php echo $key ?>-order-method-<?php echo $skey ?>"><?php echo $sval ?></span></li>    
                    <?php } ?>
                    </ul>
                </li>
                <?php         
                }
                ?>
                <li class="small p-1 m-1">
                    <span class="pointer clearAllOrders" id="<?php echo $pagername ?>-clearAllOrders" >Clear all</span>
                </li>
            </ul>
        </div>       
    </div>
    
    <div class="float-left smaller" id="<?php echo $pagername ?>_order"></div>
    
    <div class="float-left ml-1 pl-1 pr-0 text-muted smaller pagerlight" id="<?php echo $pagername ?>sortitems">
    
    </div>

    <div class="float-left ml-1 pl-1 pr-0 text-muted smaller pagerlight">
        <div>
            <input type='text' class='roundedcorners ml-4 form-control-sm form-transparent-sm text-muted' style='margin-top: 2px' id='<?php echo $pagername ?>-inpage_filter' title='Search currently showing results...' />
        </div>
    </div>    
    <input type="hidden" id="<?php echo $pagername ?>count" value="" />        
</div>
