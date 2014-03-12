/* Page config --> Begin */
var page_config = {
    colors : {
        0 : {
            name : 'color-1',
            className : 'color-1'
        },
        1 : {
            name : 'color-2',
            className : 'color-2'
        },
        2 : {
            name : 'color-3',
            className : 'color-3'
        },
        3 : {
            name : 'color-4',
            className : 'color-4'
        },
        4 : {
            name : 'color-5',
            className : 'color-5'
        },
        5 : {
            name : 'color-6',
            className : 'color-6'
        },
        6 : {
            name : 'color-7',
            className : 'color-7'
        },
        7 : {
            name : 'color-8',
            className : 'color-8'
        },
        8 : {
            name : 'color-9',
            className : 'color-9'
        }
    },
    patterns : {
        0 : {
            name : 'Pattern 1',
            className : 'pattern-1'
        },
        1 : {
            name : 'Pattern 2',
            className : 'pattern-2'
        },
        2 : {
            name : 'Pattern 3',
            className : 'pattern-3'
        },
        3 : {
            name : 'Pattern 4',
            className : 'pattern-4'
        },
        4 : {
            name : 'Pattern 5',
            className : 'pattern-5'
        },
        5 : {
            name : 'Pattern 6',
            className : 'pattern-6'
        },
        6 : {
            name : 'Pattern 7',
            className : 'pattern-7'
        },
        7 : {
            name : 'Pattern 8',
            className : 'pattern-8'
        },
        8 : {
            name : 'Pattern 9',
            className : 'pattern-9'
        },
        9 : {
            name : 'Pattern 10',
            className : 'pattern-10'
        }
    },
    backgrounds : {
        0 : {
            name : 'Background 1',
            className : 'background-1'
        },
        1 : {
            name : 'Background 2',
            className : 'background-2'
        },
        2 : {
            name : 'Background 3',
            className : 'background-3'
        },
        3 : {
            name : 'Background 4',
            className : 'background-4'
        },
        4 : {
            name : 'Background 5',
            className : 'background-5'
        },
        5 : {
            name : 'Background 6',
            className : 'background-6'
        },
        6 : {
            name : 'Background 7',
            className : 'background-7'
        },
        7 : {
            name : 'Background 8',
            className : 'background-8'
        },
        8 : {
            name : 'Background 9',
            className : 'background-9'
        },
        9 : {
            name : 'Background 10',
            className : 'background-10'
        },
        10 : {
            name : 'Background 11',
            className : 'background-11'
        },
        11 : {
            name : 'Background 12',
            className : 'background-12'
        },
        12 : {
            name : 'Background 13',
            className : 'background-13'
        },
        13 : {
            name : 'Background 14',
            className : 'background-14'
        },
        14 : {
            name : 'Background 15',
            className : 'background-15'
        }
    },
    styles : {
        headerStyle : {
            name : 'Heading Font',
            id : 'heading_style',
            list : {
                0 : {
                    name : 'PT Sans Narrow',
                    className : 'h-style-1'
                },
				1 : {
                    name : 'Oswald',
                    className : 'h-style-2'
                },
				2 : {
					name : 'Nova Square',
					className : 'h-style-3'
				},
				3 : {
					name : 'Lobster',
					className : 'h-style-4'
				}
            }
        },
        textStyle : {
            name : 'Content Font',
            id : 'text_style',
            list : {
                0 : {
                    name : 'Arial',
                    className : 'text-1'
                },
                1 : {
                    name : 'Tahoma',
                    className : 'text-2'
                },
                2 : {
                    name : 'Verdana',
                    className : 'text-3'
                },
                3 : {
                    name : 'Calibri',
                    className : 'text-4'
                }
            }
        }
    }
}

/* Page config --> End */

$(function() {

    /* Theme controller --> Begin */

    var $body = $('body');
	var $holder = $('.top-holder');
	var $footer = $('footer');
	var $cat = $('.categories_widget');
	var $current = $('.current-menu-item > a');
	var $samples_picker;
    var $theme_control_panel = $('#control_panel');

    function changeBodyClass(className, classesArray) {
        $.each(classesArray,function(idx, val) {
            $body.removeClass(val);
        });
        $body.addClass(className);
    }

    if (typeof page_config != 'undefined' && $theme_control_panel) {

        var color_classes = new Array();
        var pattern_classes = new Array();
        var defaultSettings = {};

		/* Colors --> Begin */
		
        if (page_config.colors) {
            var $color_block = $('<div/>').attr('id','color_scheme');
            var color_change_html = '<span>Samples:</span>';
            color_change_html += '<ul>';
            $.each(page_config.colors, function(idx, val) {
                if ($body.hasClass(val.className)) {
                    defaultSettings.color = idx;
                }
                color_change_html += '<li><a href="' + val.className + '" title="' + val.name + '" class="' + val.className + '"></a></li>';
                color_classes.push(val.className);
            });
			
			color_change_html += '<li><a href="#" title="Samples Picker" id="samplespicker" class="colorPicker"></a></li>';
			color_change_html += '</ul>';
	
            $color_block.html(color_change_html);
            $theme_control_panel.append($color_block);
				
				$samples_picker = $('#samplespicker');
				$samples_picker.css('background-color','#4b04b5').ColorPicker({
                color: '#4b04b5',
                onChange: function (hsb, hex, rgb) {
                    $samples_picker.css('backgroundColor', '#' + hex);
					$holder.add($footer).css('backgroundColor', '#' + hex);
                }
            });
		
            $color_block.find('a').not($samples_picker).click(function() {
				$holder.attr('style','');
                var nextClassName = $(this).attr('href');
                
				if (!$body.hasClass(nextClassName)) {
                    changeBodyClass(nextClassName, color_classes);
                    $color_block.find('.active').removeClass('active');
                    $(this).parent().addClass('active');			
                }
                return false;
            });
        }
		
		/* Colors --> End */
		
		
		/* Patterns --> Begin */
		
        if (page_config.patterns) {
            var $pattern_block = $('<div/>').attr('id','patterns');
            var pattern_change_html = '<span>Patterns:</span>';
            pattern_change_html += '<ul>';
            $.each(page_config.patterns, function(idx, val) {
                if ($body.hasClass(val.className)) {
                    defaultSettings.pattern = idx;
                }
                pattern_change_html += '<li><a href="' + val.className + '" title="' + val.name + '" class="' + val.className + '"></a></li>';
                pattern_classes.push(val.className);
            });
            pattern_change_html += '</ul>';
            $pattern_block.html(pattern_change_html);
            $theme_control_panel.append($pattern_block);

            $pattern_block.find('a').click(function() {
				$holder.css('opacity','1');
                var nextClassName = $(this).attr('href');
                if (!$body.hasClass(nextClassName)) {
                    changeBodyClass(nextClassName, pattern_classes);
                    $pattern_block.find('.active').removeClass('active');
                    $(this).parent().addClass('active');
                }
                return false;
            });
        }
		
		/* Patterns --> End */
		
		/* Backgrounds --> Begin */
		
        if (page_config.backgrounds) {
            var $bg_block = $('<div/>').attr('id','backgrounds');
            var bg_change_html = '<span>Backgrounds:</span>';
            bg_change_html += '<ul>';
            $.each(page_config.backgrounds, function(idx, val) {
                if ($body.hasClass(val.className)) {
                    defaultSettings.pattern = idx;
                }
                bg_change_html += '<li><a href="' + val.className + '" title="' + val.name + '" class="' + val.className + '"></a></li>';
                pattern_classes.push(val.className);
            });
            bg_change_html += '</ul>';
            $bg_block.html(bg_change_html);
            $theme_control_panel.append($bg_block);

            $bg_block.find('a').click(function() {
				$holder.css('opacity','0');
                var nextClassName = $(this).attr('href');
                if (!$body.hasClass(nextClassName)) {
                    changeBodyClass(nextClassName, pattern_classes);
                    $bg_block.find('.active').removeClass('active');
                    $(this).parent().addClass('active');
                }
                return false;
            });
        }
		
		/* Backgrounds --> End */
		
		/* Styles --> Begin */

        if (page_config.styles) {
            var $style_block;
            var $block_label;
            var $select_element;
            var $links_color;
            var $links_color_wrapper;
            var select_html;
            var header_style_classes = [];
            var text_style_classes = [];
            defaultSettings.style = {};
            $.each(page_config.styles, function(idx, val) {
                    $style_block = $('<div/>').addClass('style_block');
                    $block_label = $('<span>' + val.name + ':</span>');
                    $select_element = $('<select/>').attr({
                        id : val.id
                    });
                    select_html = '';
                    $.each(val.list,function(list_idx, list_val) {
                        if ($body.hasClass(list_val.className)) {
                            select_html += '<option value="' + list_val.className + '" selected="selected">' + list_val.name + '</option>';
                            defaultSettings.style[idx] = list_idx;
                        } else {
                            select_html += '<option value="' + list_val.className + '">' + list_val.name + '</option>';
                        }
                    });
                    $select_element.html(select_html);
                    $style_block.append($block_label, $select_element);
                    $theme_control_panel.append($style_block);
                });
				
			/* Text and Heading Fonts --> Begin */
          
            $.each(page_config.styles.headerStyle.list, function(idx, val) {
                header_style_classes.push(val.className);
            });
            $('#heading_style').change(function() {
                if (!$body.hasClass($(this).val())) {
                    changeBodyClass($(this).val(), header_style_classes);
                }
            });
            $.each(page_config.styles.textStyle.list, function(idx, val) {
                text_style_classes.push(val.className);
            });
            $('#text_style').change(function() {
                if (!$body.hasClass($(this).val())) {
                    changeBodyClass($(this).val(), text_style_classes);
                }
            });
            
			/* Text and Heading Fonts --> End */
			
			/* Links Picker --> Begin */
						 
			$links_color = $('<div/>').attr({
                        id : 'linkspicker'
                    }).addClass('colorPicker');
                    $links_color_wrapper = $('<div/>').addClass('links_color_wrapper');
                    $links_color_wrapper.append('<span>Links Color:</span>', $links_color);
                    $theme_control_panel.append($links_color_wrapper);
				
		    var links_picker = $('#linkspicker');
            links_picker.css('background-color','#85b602').ColorPicker({
                color: '#85b602',
                onChange: function (hsb, hex, rgb) {
					$cat.add($current).add(links_picker).css('backgroundColor', '#' + hex);
					$('a').not($current).css('color', '#' + hex);
                }
            });
			
			/* Links Picker --> End */
			
			/* Reset Settings  --> Begin */
			
            var setDefaultsSettings = function() {
				$holder.css('opacity','1').attr('style','');
                changeBodyClass(page_config.patterns[defaultSettings.pattern].className, pattern_classes);
                changeBodyClass(page_config.colors[defaultSettings.color].className, color_classes);
                $theme_control_panel.find('select').val(0);
                changeBodyClass(page_config.styles.headerStyle.list[defaultSettings.style.headerStyle].className, header_style_classes);
                changeBodyClass(page_config.styles.textStyle.list[defaultSettings.style.textStyle].className, text_style_classes);
                $samples_picker.css({'background-color':'#4b04b5'}).ColorPickerSetColor('#4b04b5');
				links_picker.css({'background-color':'#86b602'}).ColorPickerSetColor('#86b602');
				$body.attr('style','');
				$footer.attr('style','');
				$('a').not($samples_picker).attr('style','');
				$theme_control_panel.find('.active').removeClass();
                return false;
            };
            var $restore_button_wrapper = $('<div/>').addClass('restore_button_wrapper');
            var $restore_button = $('<a/>').text('Reset').attr('id','restore_button').addClass('button gray small').click(setDefaultsSettings);
            $restore_button_wrapper.append($restore_button);
            $theme_control_panel.append($restore_button_wrapper);
			
			/* Reset Settings  --> Begin */
			
        }
		
		/* Styles --> End */
				
		/* Control Panel Label --> Begin */		

        var $theme_control_panel_label = $('#control_label');
        $theme_control_panel_label.click(function() {
            if ($theme_control_panel.hasClass('visible')) {
                $theme_control_panel.animate({left: -210}, 400, function() {
                      $theme_control_panel.removeClass('visible');
                });
            } else {
                $theme_control_panel.animate({left: 0}, 400, function() {
                      $theme_control_panel.addClass('visible');
                });
            }
            return false;
        });
		
		/* Control Panel Label --> End */	
    }

    /* Theme controller --> End */

});
