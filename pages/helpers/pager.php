<?php
/*
 * Copyright [2022] [Jason Alexander Cleeland, Melbourne Australia]
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */
?>

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
      //console.log('ID: '+$(this).attr('id'));
      var currentItem=$(this).attr('id').substring(0, $(this).attr('id').indexOf("-"));
      //console.log('Pagername: <?php echo $pagername ?>');
      //console.log('Current item: '+currentItem);
      if(currentItem=='<?php echo $pagername ?>') {
          clearPagerOrder('<?php echo $pagername ?>');
          loadPagerOrder('<?php echo $pagername ?>');
          var functionname='load<?php echo ucfirst($pagername) ?>';
          console.log(functionname);
          window[functionname]();
      }
  })
  
  loadPagerOrder('<?php echo $pagername ?>');
});

</script>
<div class='pager rounded-bottom'>&nbsp;
    <div class="float-right mr-1 ml-0 pl-1 pr-1 smallish pointer pagerbutton" id="<?php echo $pagername ?>last" title='last page' value=''><img src='images/end.svg' /></div>
    <div class="float-right mr-0 ml-0 pl-1 pr-1 smallish pointer pagerbutton" id="<?php echo $pagername ?>end" title='next page' value='49'><img src='images/chevron-right.svg' /></div>
    <div class="float-right m-0 p-0 pl-1 pr-1 smallish pagerposition" id="<?php echo $pagername ?>position"></div>
    <div class="float-right ml-0 mr-0 pl-1 pr-1 smallish pointer pagerbutton" id="<?php echo $pagername ?>start" title='previous page' value='0'><img src='images/chevron-left.svg' /></div>
    <div class="float-right ml-1 mr-0 pl-1 pr-1 smallish pointer pagerbutton" id="<?php echo $pagername ?>first" title='first page' value=''><img src='images/start.svg' /></div>
    <div class="float-right mr-1 pl-1 pr-1 text-muted smallish rounded pagerlight" id="<?php echo $pagername ?>total"></div>
    <div class="float-right m1-1 pl-1 pr-0 text-muted smallish rounded pagerlight"><input class="text-muted smallish pagerlight ml-1 p-0" style="width: 18px; border: 0; margin-top: 1px" id="<?php echo $pagername ?>qty" title="Quantity shown" value='<?php echo $configsettings['general']['pager_default_qty']['value'] ?>' /></div>
    
    <div class="float-left ml-1 pl-1 pr-0 text-muted smaller pagerlight">
        <div class="dropdown">
            <button class="btn dropdown-toggle smaller p-1 m-0" type="button" id="<?php echo $pagername ?>sortOptions" data-toggle="dropdown" aria-haspoup="true" aria-expanded="false">
                Sort
            </button>
            <ul class='dropdown-menu smallish p-1 m-1' style="background-color: rgba(186, 232, 163, 0.8);" id="pager_name_<?php echo $pagername ?>">
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
                <li class="smallish p-1 m-1">
                    <span class="pointer clearAllOrders" id="<?php echo $pagername ?>-clearAllOrders" >Clear all</span>
                </li>
            </ul>
        </div>       
    </div>
    
    <div class="float-left smaller" id="<?php echo $pagername ?>_order"></div>
    
    <div class="float-left ml-1 pl-1 pr-0 text-muted smaller pagerlight" id="<?php echo $pagername ?>sortitems">
    
    </div>

    <div class="float-left ml-1 pl-1 pr-0 text-muted smaller pagerlight d-none d-sm-block d-md-block d-lg-block d-xl-block">
        <div>
            <input type='text' class='roundedcorners ml-4 form-control-sm form-transparent-sm text-muted' style='margin-top: 2px' id='<?php echo $pagername ?>-inpage_filter' title='Search currently showing results...' />
        </div>
    </div>    
    <input type="hidden" id="<?php echo $pagername ?>count" value="" />        
</div>
