<?php
function emailit_admin_init() {
    register_setting('emailit_options', 'emailit_options');
}

function emailit_settings_page() {
    ?>
    <div>
        <script type="text/javascript" src="<?php echo plugins_url('../js/jquery.js', __FILE__) ?>"></script>
        <script type="text/javascript" src="<?php echo plugins_url('../js/jquery-ui.min.js', __FILE__) ?>"></script>
        <script type="text/javascript" src="<?php echo plugins_url('../js/colorpicker/js/colorpicker.js', __FILE__) ?>"></script>
        <script type="text/javascript" src="<?php echo plugins_url('../js/colorpicker/js/colorpicker.js', __FILE__) ?>"></script>
        <link href="<?php echo plugins_url('../css/jquery-ui.min.css', __FILE__) ?>" rel="stylesheet">
        <link href="<?php echo plugins_url('../js/colorpicker/css/colorpicker.css', __FILE__) ?>" rel="stylesheet">
        <link href="<?php echo plugins_url('../js/colorpicker/css/layout.css', __FILE__) ?>" rel="stylesheet">
        <link href="<?php echo plugins_url('../css/style.css', __FILE__) ?>" rel="stylesheet">
        <h2>E-MAILiT Share Settings</h2>
        <form onsubmit="return validate()" id="emailit_options" action="options.php" method="post">
            <?php
            settings_fields('emailit_options');
            $emailit_options = get_option('emailit_options');
            ?>
            <script type="text/javascript">
                function validate() {
                    emailit_domain_verification = document.getElementById('emailit_domain_verification');
                    if (emailit_domain_verification && emailit_domain_verification.value != "" && emailit_domain_verification.value.substr(0, 9) != "E-MAILiT_") {
                        alert("Error! Paste not valid. Domain Verification Publisher Key, always starts with 'E-MAILiT_'");
                        return false;
                    }
                    emailit_button_id = document.getElementById('emailit_button_id');
                    if (emailit_button_id && emailit_button_id.value != "" && isNaN(emailit_button_id.value)) {
                        alert("Error! Paste not valid. ''Your Button ID'', must contain only digits.");
                        return false;
                    }

                    var e_mailit_default_servises = $.map($('#social_services li:visible'), function (element) {
                        return $(element).attr('class').replace(/E_mailit_/gi, '').replace(/ ui-sortable-handle/gi, '');
                    }).join(',');

                    $('#default_services').val(e_mailit_default_servises);
					if(!$('#emailit_showonhome').is(':checked') && !$('#emailit_showonarchives').is(':checked') 
					&& !$('#emailit_showoncats').is(':checked') && !$('#emailit_showonpages').is(':checked') && !$('#emailit_showonexcerpts').is(':checked') )
						alert("Select a placement option to display the button.");
					
                    return true;
                }
                $(function () {
                    $("#tabs").tabs();

                    $(document).tooltip({
                        position: {
                            my: "center bottom-20",
                            at: "center top",
                            using: function (position, feedback) {
                                $(this).css(position);
                                $("<div>")
                                        .addClass("arrow")
                                        .addClass(feedback.vertical)
                                        .addClass(feedback.horizontal)
                                        .appendTo(this);
                            }
                        }
                    });

                    $("#sel_buttons").sortable({
                        cancel: ".ui-state-disabled",
                        update: function (event, ui) {
                            $("#buttons_order").val("");
                            $("#sel_buttons li").each(function () {
                                $("#buttons_order").val($("#buttons_order").val() + $(this).attr("id") + ",");
                            });
                        }
                    });
                    $("#sel_buttons").disableSelection();

                    $('#colorSelector div,#colorSelector2 div').css('backgroundColor', '<?php echo $emailit_options["back_color"] ?>');
                    $('#colorSelector,#colorSelector2').ColorPicker({
                        color: '<?php echo $emailit_options["back_color"] ?>',
                        onShow: function (colpkr) {
                            if (!$(this).attr('disabled'))
                                $(colpkr).fadeIn(500);
                            return false;
                        },
                        onHide: function (colpkr) {
                            $(colpkr).fadeOut(500);
                            return false;
                        },
                        onChange: function (hsb, hex, rgb) {
                            $("#colorpickerField").val("#" + hex);
                            $("#colorpickerField2").val("#" + hex);
                            $('#colorSelector div').css('backgroundColor', '#' + hex);
                            $('#colorSelector2 div').css('backgroundColor', '#' + hex);
                        }
                    })
                            .bind('keyup', function () {
                                $(this).ColorPickerSetColor(this.value);
                            });

                    $("#colorpickerField, #colorpickerField2").change(function () {
                        $('#colorSelector div').css('backgroundColor', $(this).val());
                        $('#colorSelector2 div').css('backgroundColor', $(this).val());
                        $("#colorpickerField, #colorpickerField2").val($(this).val());
                    });

                    $('#colorSelector3 div').css('backgroundColor', '<?php echo $emailit_options["text_color"] ?>');
                    $('#colorSelector3').ColorPicker({
                        color: '<?php echo $emailit_options["text_color"] ?>',
                        onShow: function (colpkr) {
                            if (!$(this).attr('disabled'))
                                $(colpkr).fadeIn(500);
                            return false;
                        },
                        onHide: function (colpkr) {
                            $(colpkr).fadeOut(500);
                            return false;
                        },
                        onChange: function (hsb, hex, rgb) {
                            $("#colorpickerField3").val("#" + hex);
                            $('#colorSelector3 div').css('backgroundColor', '#' + hex);
                        }
                    })
                            .bind('keyup', function () {
                                $(this).ColorPickerSetColor(this.value);
                            });
                    $("#colorpickerField3").change(function () {
                        $('#colorSelector3 div').css('backgroundColor', $(this).val());
                    });

                    $(".toolbar-style").click(function () {
                        $(this).find("input[type='radio']").prop('checked', true);
                        $(".toolbar-styles").change();
                    });
                    $(".toolbar-styles").change(function () {
                        $(".toolbar-styles input[type='radio']").parent().css("opacity", "0.3");
                        $(".toolbar-style input[type='checkbox']").prop('disabled', true);
                        $(".toolbar-styles input[type='radio']:checked").parent().css("opacity", "1");
                        $(".toolbar-styles input[type='radio']:checked").parent().find("input[type='checkbox']").prop('disabled', false);


                        if ($(".toolbar-style input[type='radio']:checked").val() !== "") {
                            $("#display_linkedin_button input, #display_vkontakte_button input, #display_gplus_button input, " +
                                    "#display_fb_button input, #display_fb_like_share_button input, #display_odnoklassniki_button input").prop('disabled', true);
                            $("#display_linkedin_button, #display_vkontakte_button, #display_gplus_button, " +
                                    "#display_fb_button, #display_fb_like_share_button, #display_odnoklassniki_button").addClass("ui-state-disabled");
                        } else {
                            $("#display_linkedin_button input, #display_vkontakte_button input, #display_gplus_button input, " +
                                    "#display_fb_button input, #display_fb_like_share_button input, #display_odnoklassniki_button input").prop('disabled', false);
                            $("#display_linkedin_button, #display_vkontakte_button, #display_gplus_button, " +
                                    "#display_fb_button, #display_fb_like_share_button, #display_odnoklassniki_button").removeClass("ui-state-disabled");
                        }
                        if ($(".toolbar-style input[type='radio']:checked").val() !== "" && $(".toolbar-style input[type='radio']:checked").val() !== "3") {
                            $("#emailit_button_options").css("opacity", "0.3");
                            $("#emailit_button_options input").prop('disabled', true);
                            $("#emailit_button_options div").attr('disabled', true);
                        } else {
                            $("#emailit_button_options").css("opacity", "1");
                            $("#emailit_button_options input").prop('disabled', false);
                            $("#emailit_button_options div").attr('disabled', false);
                        }
                    });
                    $(".toolbar-styles").change();

                    $.getScript("//www.e-mailit.com/widget/button/js/button.js", function () {
                        var share = def_services.split(","); // Get buttons
                        for (var key in share) {
                            if (def_services) {
                                var services = share[key];
                                var name = services.replace(/_/gi, " ");
                                if (name == "G plus")
                                    name = "Google+";
                                var sharelink = "<input type=\"checkbox\" id=\"checkbox" + services + "\" name=\"" + services + "\" /><label for=\"checkbox" + services + "\" class='services_list' id=\"" + services + "\" ><div class=\"E_mailit_" + services + "\" style='margin-right:5px;'> </div> <span class='services_list_name'>" + name + "</span></label>";

                                $(sharelink).appendTo('#servicess');
                            }
                        }

    <?php
    if (isset($emailit_options["default_services"]) && $emailit_options["default_services"] !== "") {
        echo "defaultButton.share_services_new ='" . $emailit_options["default_services"] . "';";
    }
    ?>
                        var new_share = defaultButton.share_services_new.split(","); // Get buttons
                        for (var key in new_share) {
                            if (defaultButton.share_services_new) {
                                var services = new_share[key];
                                var name = services;//.replace(/_/gi, " ");
                                if (name == "G plus")
                                    name = "Google+";
                                var sharelink = "<li class=\"E_mailit_" + name + "\" id=\"def\"></li>";

                                $(sharelink).appendTo('#social_services');
                            }
                        }
                        $('#sharess input:checkbox').each(function () {
                            $('#checkbox_counter1').removeAttr("checked");
                        });



                        $("#social_services,#social_services_share,#social_services2,#social_services_share2").sortable({
                            revert: true,
                            opacity: 0.8
                        });
                        $("ul, li").disableSelection();
                        var i = 0;
                        $("#check").button();
                        $("#servicess").buttonset();
                        $(".uncheck_all").click(function () {
                            $("#servicess input[type=checkbox]").attr('checked', false);
                            $("#servicess input[type=checkbox]").button("refresh");
                            $("#social_services #custom,.counter_servises span").remove();
                            $("#servicess input:not(:checked)").button("option", "disabled", false);
                            $(".message_good").show("fast");
                            i = 0;
                        });

                        $("#servicess input[type=checkbox]").click(function () {
                            if ($(this).is(':checked')) {
                                i = i + 1;
                                var class_name = this.name;
                                if (class_name == "Google+")
                                    class_name = "G_plus";

                                $('#social_services').append('<li class="E_mailit_' + class_name + '" id="custom"></li>');
                                $("#E_mailit_" + this.name + "").effect("transfer", {
                                    to: "#social_services ." + this.name + "#custom"
                                }, 500);
                                $(".counter_servises span").remove();
                                $(".counter_servises").append("<span>" + i + "</span>");
                            }
                            else {
                                i = i - 1;
                                $("#social_services .E_mailit_" + this.name + "#custom").effect("transfer", {
                                    to: "#" + this.name + ""
                                }, 500).delay(500).remove();
                                $(".counter_servises span").remove();
                                $(".counter_servises").append("<span>" + i + "</span>");
                            }
                            if (i >= 10) {
                                $("#servicess input:not(:checked)").button({
                                    disabled: true
                                });
                                $(".message_good").hide("fast");
                            }
                            else {
                                $("#servicess input:not(:checked)").button("option", "disabled", false);
                                $(".message_good").show("fast");
                            }
                        });

                        $("#sharess input[type=checkbox]").click(function () {
                            if ($(this).attr('checked')) {
                                $('#social_services_share').append('<li class="' + this.name + '" id="customc"></li>');
                                $("#sharess #" + this.name + "").effect("transfer", {
                                    to: "#social_services_share ." + this.name + "#customc"
                                }, 500);

                            }
                            else {
                                $("#social_services_share ." + this.name + "#customc").effect("transfer", {
                                    to: "#sharess #" + this.name + ""
                                }, 500).delay(500).remove();
                            }
                        });


                        $("#radio_position2").click(function () {
                            $("#button_display_menu").hide('fast');
                        });
                        $("#radio_position1").click(function () {
                            $("#button_display_menu").show('fast');
                        });


                        $(".social_services_default").click(function () {
                            $('#social_services #def,#servises_customize').show('fast');
                            $("#social_services #custom,#servicess,.filterinput,.social_services_default,.message_good,.message_bad,.counter_servises,.uncheck_all").hide('fast');
                        });

                        $("#servises_customize").click(function () {
                            $('#social_services #def,#servises_customize').hide('fast');
                            $("#social_services #custom,#servicess,.filterinput,.message_good,.social_services_default,.counter_servises,.uncheck_all").show('fast');
                        });

                        jQuery.expr[':'].Contains = function (a, i, m) {//boitheia gia to search me ta grammata tis :contains
                            return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
                        };
                        $('.filterinput').keyup(function (event) {
                            var filter = $('.filterinput').val();
                            if (filter == '' || filter.length < 1) {
                                $(".services_list").show();
                            }
                            else {
                                $(".services_list").find(".services_list_name:not(:Contains(" + filter + "))").parent().parent().hide();
                                $(".services_list").find(".services_list_name:Contains(" + filter + ")").parent().parent().show();
                            }
                        });


                        $("#counter_customize").click(function () {
                            $('#social_services_share #defc,#counter_customize').hide('fast');
                            $("#sharess,#social_services_share #customc,.social_share_default,.delete_counter").show('fast');

                        });
                        $(".social_share_default").click(function () {
                            $('#social_services_share #defc,#counter_customize').show('fast');
                            $("#sharess,#social_services_share #customc,.social_share_default,.delete_counter").hide('fast');
                        });


                        $("#radio_campaign2").click(function () {
                            $('.insert_ad_loc').show('fast');
                        });
                        $("#radio_campaign1").click(function () {
                            $('.insert_ad_loc').hide('fast');
                            $('#ad_location').val('');
                        });
                        $("#ad_example").click(function () {
                            $('#ad_example_code').show('fast');
                        });
                        $(".delete_counter").click(function () {
                            $('#sharess,.delete_counter,.social_share_default,#social_services_share').hide('fast', function () {
                                $('.appent_counter').show('fast');
                            });
                        });
                        $(".appent_counter").click(function () {
                            $('.appent_counter').hide('fast', function () {
                                $('#sharess,.delete_counter,.social_share_default,#social_services_share').show('fast');
                            });
                        });
                    });
                });
            </script>
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-standard">Standard</a></li>
                    <li><a href="#tabs-advanced">Advanced</a></li>
                </ul>
                <div id="tabs-standard">
                    <div class="toolbar-styles">			
                        <div class="toolbar-style" style="background: url('<?php echo plugins_url('../images/style1.png', __FILE__) ?>') no-repeat right;">
                            <input style="float:left;margin-top: 14px;" type="radio"  name="emailit_options[toolbar_type]" value="1" <?php echo ($emailit_options["toolbar_type"] == "1" ? 'checked="checked"' : ''); ?>>
                            <div style="float:left;margin-left:240px;"><strong>Circular: </strong><input title="Circular buttons" type="checkbox" name="emailit_options[circular]" value="true" <?php echo ($emailit_options['circular'] == true ? 'checked="checked"' : ''); ?>/></div>				
                        </div>
                        <div class="toolbar-style"  style="background: url('<?php echo plugins_url('../images/style2.png', __FILE__) ?>') no-repeat right;">
                            <input style="float:left;margin-top: 14px;" type="radio"  name="emailit_options[toolbar_type]" value="2" <?php echo ($emailit_options["toolbar_type"] == "2" ? 'checked="checked"' : ''); ?>>
                            <div style="float:left;margin-left:240px;"><strong>Circular: </strong><input title="Circular buttons" type="checkbox" name="emailit_options[circular]" value="true" <?php echo ($emailit_options['circular'] == true ? 'checked="checked"' : ''); ?>/></div>				
                            <input style="float:left;margin-top: 7px;" type="text" name="emailit_options[back_color]" maxlength="7" size="7" id="colorpickerField" value="<?php echo $emailit_options["back_color"] ?>" />
                            <div style="float:left;margin-top: 3px;" id="colorSelector"><div style="background-color: #0000ff"></div></div>
                        </div>
                        <div class="toolbar-style"  style="background: url('<?php echo plugins_url('../images/style3.png', __FILE__) ?>') no-repeat right;">
                            <input type="radio"  name="emailit_options[toolbar_type]" value="3" <?php echo ($emailit_options["toolbar_type"] == "3" ? 'checked="checked"' : ''); ?>>
                        </div>
                        <div class="toolbar-style"  style="background: url('<?php echo plugins_url('../images/style4.png', __FILE__) ?>') no-repeat right;">
                            <input type="radio"  name="emailit_options[toolbar_type]" value="" <?php echo ($emailit_options["toolbar_type"] == "" ? 'checked="checked"' : ''); ?>>
                        </div>
                    </div>
                    <div>
                        <?php
                        $buttons = array(
                            "display_fb_like_share_button" => "Facebook Like + Share",
                            "display_fb_button" => "Facebook Like",
                            "display_fb_share_button" => "Facebook Share",
                            "display_tweeter_button" => "Twitter",
                            "display_gplus_button" => "Google+",
                            "display_pinterest_button" => "Pinterest",
                            "display_linkedin_button" => "LinkedIn",
                            "display_vkontakte_button" => "Vkontakte",
                            "display_odnoklassniki_button" => "Odnoklassniki",
                            "display_emailit_button" => "E-MAILiT",
                        );
                        ?>
                        <ul title="Drag to reorder" id="sel_buttons">
                            <?php
                            $sel_buttons = array_filter(explode(",", $emailit_options['buttons_order']));

                            foreach ($sel_buttons as $sel_button) {
                                ?>
                                <li class="ui-state-default ui-sortable-handle" id="<?php echo $sel_button; ?>">
                                    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span><i></i><?php echo $buttons[$sel_button]; ?>
                                    <input type="checkbox" name="emailit_options[<?php echo $sel_button ?>]" value="true" <?php echo ($emailit_options[$sel_button] == true ? 'checked="checked"' : ''); ?>/>
                                </li>
                                <?php
                            }
                            foreach ($buttons as $button_key => $button) {
                                if (!in_array($button_key, $sel_buttons)) {
                                    ?>
                                    <li class="ui-state-default ui-sortable-handle" id="<?php echo $button_key; ?>"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><i></i><?php echo $button ?><input type="checkbox" name="emailit_options[<?php echo $button_key ?>]" value="true" <?php echo ($emailit_options[$button_key] == true ? 'checked="checked"' : ''); ?>/></li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                        <input id="buttons_order" name="emailit_options[buttons_order]" value="<?php echo $emailit_options['buttons_order']; ?>" type="hidden"/>
                    </div>
                    <div id="emailit_button_options">
                        <input title="EMAILiT Button Text" style="vertical-align:middle;" type="text" name="emailit_options[text_display]" value="<?php if ($emailit_options['text_display'])
                            echo $emailit_options['text_display'];
                        else
                            echo "Share";
                        ?>"/>
                        <div style="display:inline-block;vertical-align:middle;" id="colorSelector2"><div style="background-color: #0000ff"></div></div>
                        <input title="EMAILiT Button Background" style=";vertical-align:middle;" type="text" name="emailit_options[back_color]" maxlength="7" size="7" id="colorpickerField2" value="<?php echo $emailit_options["back_color"] ?>" />
                        <div style="display:inline-block;vertical-align:middle;" id="colorSelector3"><div style="background-color: #0000ff"></div></div>
                        <input title="EMAILiT Button Text Color" style=";vertical-align:middle;" type="text" name="emailit_options[text_color]" maxlength="7" size="7" id="colorpickerField3" value="<?php echo $emailit_options["text_color"] ?>" />
                        <strong style="vertical-align:middle;">Show counter: </strong><input title="EMAILiT Button counter" type="checkbox" name="emailit_options[display_counter]" value="true" <?php echo ($emailit_options['display_counter'] == true ? 'checked="checked"' : ''); ?>/><br />
                    </div>			
                    <div id="gener_div">
                        <h3>Add/Remove Services</h3>
                        <div id="radio_ads">
                            <span style="float:left">Services on initial hover sharing menu:</span> 
                            <a style="float:right; display:none;" class="social_services_default">Default Services</a>
                            <a style="float:right; display:none;" class="uncheck_all">Uncheck All</a>
                            <ul id="social_services">

                            </ul>
                            <br />
                            <a id="servises_customize" title="Choose the Sharing Services you want to appear on the share menu">Customize Services</a> 
                            <br />
                            <div class="message_good" style="display:none">Select your 10 Most Popular Services to show up on initial hover</div>
                            <input type="text" class="filterinput" style="display:none" > <span class="counter_servises"></span>

                            <div id="servicess">
                            </div>
                            <input id="default_services" name="emailit_options[default_services]" value="<?php echo $emailit_options['default_services']; ?>" type="hidden"/>
                            <!--end services-->
                        </div>

                        <div id="show_follow" style="padding-top:50px;">
                            <h3>Following Channels </h3>
                            <ul id="social_services_follow">
                                <li><span class="E_mailit_Facebook"></span><label class="follow_label">http://www.facebook.com/</label><input class="form-field" name="emailit_options[follow_facebook]" type="text" value="<?php echo $emailit_options["follow_facebook"] ?>"></li>
                                <li><span class="E_mailit_Twitter"></span><label class="follow_label">http://twitter.com/ </label><input class="form-field" name="emailit_options[follow_twiiter]" type="text" value="<?php echo $emailit_options["follow_twiiter"] ?>"></li>
                                <li><span class="E_mailit_LinkedIn"></span><label class="follow_label">http://www.linkedin.com/company/ </label><input class="form-field" name="emailit_options[follow_linkedin]" type="text" value="<?php echo $emailit_options["follow_linkedin"] ?>"></li>
                                <li><span class="E_mailit_Pinterest"></span><label class="follow_label">http://www.pinterest.com/</label><input class="form-field" name="emailit_options[follow_pinterest]" type="text" value="<?php echo $emailit_options["follow_pinterest"] ?>"></li>
                                <li><span class="E_mailit_G_plus"></span><label class="follow_label">https://plus.google.com/u/0/</label><input class="form-field" name="emailit_options[follow_google]" type="text" value="<?php echo $emailit_options["follow_google"] ?>"></li>
                                
                                <li><span class="E_mailit_YouTube"></span><label class="follow_label">http://www.youtube.com/user/</label><input class="form-field" name="emailit_options[follow_youtube]" type="text" value="<?php echo $emailit_options["follow_youtube"] ?>"></li>
                                <li><span class="E_mailit_Vimeo"></span><label class="follow_label">http://www.vimeo.com/</label><input class="form-field" name="emailit_options[follow_vimeo]" type="text" value="<?php echo $emailit_options["follow_vimeo"] ?>"></li>
                                <li><span class="E_mailit_Instagram"></span><label class="follow_label">https://instagram.com/</label><input class="form-field" name="emailit_options[follow_instagram]" type="text" value="<?php echo $emailit_options["follow_instagram"] ?>"></li>
                                <li><span class="E_mailit_FourSquare"></span><label class="follow_label">http://foursquare.com/</label><input class="form-field" name="emailit_options[follow_foursquare]" type="text" value="<?php echo $emailit_options["follow_foursquare"] ?>"></li>
                                <li><span class="E_mailit_Tumblr"></span><label class="follow_label">http://</label><input class="form-field" name="emailit_options[follow_tumblr]" type="text" value="<?php echo $emailit_options["follow_tumblr"] ?>"><label>.tumblr.com</label></li>
                                
                                <li><span class="E_mailit_Rss"></span><label class="follow_label">http://</label><input class="form-field" name="emailit_options[follow_rss]" type="text" value="<?php echo $emailit_options["follow_rss"] ?>"></li>
                            </ul>	
                        </div> 
                        <div id="promo" style="padding-top:10px;">
                            <h3>Create your Advertising Campaign</h3>
                            <label>Insert your Ad Unit location</label><input placeholder="http://" name="emailit_options[promo_ad]" type="text" value="<?php echo $emailit_options["promo_ad"] ?>">
                            <p>
                                Create revenue and keep it all - Begin creating money from your sharing button. <a href="http://www.e-mailit.com/features#create-campaign" target="_blank">Learn how</a> to create a campaign
                            </p>
                        </div>
                    </div>		
                </div>				
                <div id="tabs-advanced">
                    <h3>Get Stats</h3>
                    <table width="650px" >
                        <tr><td style="padding-bottom:20px;width:380px">Google Analytics property ID (UA-xxxxxxx-x):</td><td style="padding-bottom:20px"><input type="text" name="emailit_options[GA_id]" value="<?php echo $emailit_options['GA_id']; ?>"/></td></tr>
                    </table>
                    <h3>Placement</h3>
                    <table width="750px" id="content_options">
                        <tbody>
                            <tr><td width="300px">homepage:</td>
                                <td><input id="emailit_showonhome" type="checkbox" name="emailit_options[emailit_showonhome]" value="true" <?php echo ($emailit_options['emailit_showonhome'] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                            <tr><td>archives:</td>
                                <td><input id="emailit_showonarchives" type="checkbox" name="emailit_options[emailit_showonarchives]" value="true" <?php echo ($emailit_options['emailit_showonarchives'] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                            <tr><td>categories:</td>
                                <td><input id="emailit_showoncats" type="checkbox" name="emailit_options[emailit_showoncats]" value="true" <?php echo ($emailit_options['emailit_showoncats'] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                            <tr><td>pages:</td>
                                <td><input id="emailit_showonpages" type="checkbox" name="emailit_options[emailit_showonpages]" value="true" <?php echo ($emailit_options['emailit_showonpages'] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                            <tr><td>excerpts:</td>
                                <td><input id="emailit_showonexcerpts" type="checkbox" name="emailit_options[emailit_showonexcerpts]" value="true" <?php echo ($emailit_options['emailit_showonexcerpts'] == true ? 'checked="checked"' : ''); ?>/></td></tr>    
                            <tr><td style="padding-bottom:20px">button position on page:</td>
                                <td style="padding-bottom:20px">
                                    <select name="emailit_options[button_position]">
                                        <option value="top" <?php echo ($emailit_options['button_position'] == 'top' ? 'selected="selected"' : ''); ?>>Top</option>                                
                                        <option value="bottom" <?php echo ($emailit_options['button_position'] == 'bottom' ? 'selected="selected"' : ''); ?>>Bottom</option>
                                        <option value="both" <?php echo ($emailit_options['button_position'] == 'both' ? 'selected="selected"' : ''); ?>>Both</option>
                                    </select></td></tr>
                            <tr><td colspan="2"><strong>Edit Sidebar (Widget) Settings:</strong> Go to Appearance > Widgets > Main Sidebar > E-MAILiT Share</td></tr>
                        </tbody>
                    </table>
                    <br /><br /><br />	
                    <table width="650px" >
                        <tr><td style="padding-bottom:20px;width:300px">Tweet via (your Twitter username):</td><td style="padding-bottom:20px"><input type="text" name="emailit_options[TwitterID]" value="<?php echo $emailit_options['TwitterID']; ?>"/></td></tr>
                        <tr><td style="padding-bottom:20px">Open the widget Menu:</td><td style="padding-bottom:20px">					
                                <select title="EMAILiT Menu open on" name="emailit_options[open_on]">
                                    <option value="onmouseover" <?php echo ($emailit_options['open_on'] == 'onmouseover' ? 'selected="selected"' : ''); ?>>hover</option>
                                    <option value="onclick" <?php echo ($emailit_options['open_on'] == 'onclick' ? 'selected="selected"' : ''); ?>>click</option>                                
                                </select></td>
                        </tr>
                        <tr><td style="padding-bottom:20px">Auto-show the widget Menu after:</td><td style="padding-bottom:20px">					
			<input style="width:70px" min="0" max="1000" type="number" name="emailit_options[auto_popup]" value="<?php if ($emailit_options['auto_popup']) echo $emailit_options['auto_popup'];else echo '0';?>"/> sec
			</td>
                        </tr>
                        <tr><td style="padding-bottom:20px">Promo display:</td><td style="padding-bottom:20px">					
                                <select title="Promo display" name="emailit_options[display_ads]">
                                    <option value="yes" <?php echo ($emailit_options['display_ads'] == 'yes' ? 'selected="selected"' : ''); ?>>opened</option>
                                    <option value="no" <?php echo ($emailit_options['display_ads'] == 'no' ? 'selected="selected"' : ''); ?>>closed</option>                                
                                </select></td>
                        </tr>			
                        <tr><td style="padding-bottom:20px">After Share Ad:</td><td style="padding-bottom:20px">					
                                <select title="Display Promo on share" name="emailit_options[promo_on_share]">
                                    <option value="yes" <?php echo ($emailit_options['promo_on_share'] == 'yes' ? 'selected="selected"' : ''); ?>>Turn on ads</option>
                                    <option value="no" <?php echo ($emailit_options['promo_on_share'] == 'no' ? 'selected="selected"' : ''); ?>>Turn off ads</option>                                
                                </select></td>
                        </tr>
                    </table>					
                </div>
                <p>
                    <input id="submit" name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
                    <br/>
            </div>	
            <p>
                <strong>&nbsp;&nbsp;Like our share widget?&nbsp;&nbsp;</strong><br><br>
                <a href="http://wordpress.org/support/view/plugin-reviews/e-mailit" target="_blank">Give E-MAILiT your rating and review</a> on WordPress.org<br>
                <a href="http://www.e-mailit.com/sharer?url=http://www.e-mailit.com&title=I love E-MAILiT Share Buttons" target="_blank">Share</a> and follow <a href="http://www.e-mailit.com">E-MAILiT</a> on <a href="http://twitter.com/emailit" target="_blank">Twitter</a>.
            </p>
            <p>		
                <br/><strong>&nbsp;&nbsp;Need support?&nbsp;&nbsp;</strong><br><br>
                Support via <a href="http://twitter.com/emailit" target="_blank">Twitter</a>.<br/>
                Search the <a href="http://wordpress.org/support/plugin/e-mailit" target="_blank">WordPress Forum</a> or
                see the <a href="https://wordpress.org/plugins/e-mailit/faq" target="_blank">FAQs</a>.<br/>
                <a href="mailto:support@e-mailit.com">Ask a Question</a>.
            </p>	
        </form>
    </div>

    <?php
}