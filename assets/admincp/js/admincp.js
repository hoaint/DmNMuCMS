$(document).ready(function() {
    $('#permanent_ban').click(function(event) {
        if (this.checked) {
            $("#ban_time").hide();
        } else {
            $("#ban_time").show();
        }
    });

    $('input[id^="trans-"]').on("input", function() {
        var trans = $(this).attr("id").split("-")[1],
            val = $(this).val();
        App.saveTranslation(trans, val);
    });

    $('button[id^="select_all_"]').on("click", function() {
        var id = $(this).attr("id").split("_")[2];
        $('#class_list_' + id + ' option').prop('selected', true);
    });

    $('select[id$=-db]').on("change", function() {
        var db_id = $(this).attr("id").split("-")[0];
        if ($(this).val() == 'custom') {
            $('#' + db_id + '_custom-db').show();
        } else {
            $('#' + db_id + '_custom-db').hide();
        }
    });

    $('input[id^="price-"]').on("input", function() {
        var id = $(this).attr("id").split("-")[1],
            val = $(this).val();
        App.savePrice(id, val);
    });

    $('#storage').on("change", function() {
        if ($(this).val() == 'ipb' || $(this).val() == 'ipb4') {
            $('#ipb_settings').show();
        } else {
            $('#ipb_settings').hide();
        }
        if ($(this).val() == 'rss') {
            $('#rss_settings').show();
        } else {
            $('#rss_settings').hide();
        }
        if ($(this).val() == 'facebook') {
            $('#fb_settings').show();
            $('#per_page').hide();
            $('#news_cache').hide();
        } else {
            $('#fb_settings').hide();
            $('#per_page').show();
            $('#news_cache').show();
        }
    });

    $('#voting_api').on("change", function() {
        if ($(this).val() == 1) {
            $('#xtremetop100').show();
        } else {
            $('#xtremetop100').hide();
        }
        if ($(this).val() == 2) {
            $('#mmotop').show();
        } else {
            $('#mmotop').hide();
        }
        if ($(this).val() == 3) {
            $('#gtop').show();
        } else {
            $('#gtop').hide();
        }
        if ($(this).val() == 4) {
            $('#topg').show();
        } else {
            $('#topg').hide();
        }
        if ($(this).val() == 5) {
            $('#top100arena').show();
        } else {
            $('#top100arena').hide();
        }
        if ($(this).val() == 6) {
            $('#mmoserver').show();
        } else {
            $('#mmoserver').hide();
        }
		if ($(this).val() == 8) {
            $('#ultratop').show();
        } else {
            $('#ultratop').hide();
        }
        if ($(this).val() == 7) {
            $('#muservertop').show();
        } else {
            $('#muservertop').hide();
        }
    });

    $('#system_type').on("change", function() {
        if ($(this).val() == 2) {
            $('#gm_command_list_igcn').show();
        } else {
            $('#gm_command_list_igcn').hide();
        }
    });

    $('#paypal_settings_form').find('#server').on("change", function() {
        App.loadDonationSettings($(this).val(), 'paypal');
    });

    $('#paymentwall_settings_form').find('#server').on("change", function() {
        App.loadDonationSettings($(this).val(), 'paymentwall');
    });

    $('#fortumo_settings_form').find('#server').on("change", function() {
        App.loadDonationSettings($(this).val(), 'fortumo');
    });

    $('#paygol_settings_form').find('#server').on("change", function() {
        App.loadDonationSettings($(this).val(), 'paygol');
    });

    $('#2checkout_settings_form').find('#server').on("change", function() {
        App.loadDonationSettings($(this).val(), '2checkout');
    });

    $('#pagseguro_settings_form').find('#server').on("change", function() {
        App.loadDonationSettings($(this).val(), 'pagseguro');
    });

    $('#superrewards_settings_form').find('#server').on("change", function() {
        App.loadDonationSettings($(this).val(), 'superrewards');
    });

    $('#paycall_settings_form').find('#server').on("change", function() {
        App.loadDonationSettings(server, 'paycall');
    });

    $('#interkassa_settings_form').find('#server').on("change", function() {
        App.loadDonationSettings($(this).val(), 'interkassa');
    });

    $('#wcoin_settings_form').find('#server').on("change", function() {
        App.loadWcoinSettings($(this).val());
    });

    $('#player_settings_form').find('#server').on("change", function() {
        App.loadRankingsSettings($(this).val(), 'player');
    });

    $('#guild_settings_form').find('#server').on("change", function() {
        App.loadRankingsSettings($(this).val(), 'guild');
    });

    $('#gens_settings_form').find('#server').on("change", function() {
        App.loadRankingsSettings($(this).val(), 'gens');
    });

    $('#voter_settings_form').find('#server').on("change", function() {
        App.loadRankingsSettings($(this).val(), 'voter');
    });

    $('#killer_settings_form').find('#server').on("change", function() {
        App.loadRankingsSettings($(this).val(), 'killer');
    });

    $('#online_settings_form').find('#server').on("change", function() {
        App.loadRankingsSettings($(this).val(), 'online');
    });

    $('#online_list_settings_form').find('#server').on("change", function() {
        App.loadRankingsSettings($(this).val(), 'online_list');
    });

    $('#bc_settings_form').find('#server').on("change", function() {
        App.loadRankingsSettings($(this).val(), 'bc');
    });

    $('#ds_settings_form').find('#server').on("change", function() {
        App.loadRankingsSettings($(this).val(), 'ds');
    });

    $('#cc_settings_form').find('#server').on("change", function() {
        App.loadRankingsSettings($(this).val(), 'cc');
    });

    $('#cs_settings_form').find('#server').on("change", function() {
        App.loadRankingsSettings($(this).val(), 'cs');
    });

    $('#duels_settings_form').find('#server').on("change", function() {
        App.loadRankingsSettings($(this).val(), 'duels');
    });

    $('form[id^="votereward_settings_form_"]').on("submit", function(e) {
        e.preventDefault();
        App.saveVoteRewardSettings($(this));
    });

    $('#scheduler_settings_form').on("submit", function(e) {
        e.preventDefault();
        App.saveSchedulerSettings($(this));
    });

    $('#edit_paypal_settings').on('click', function(e) {
        e.preventDefault();
        App.savePaypalSettings();
    });

    $('#edit_paymentwall_settings').on('click', function(e) {
        e.preventDefault();
        App.savePaymentwallSettings();
    });

    $('#edit_fortumo_settings').on('click', function(e) {
        e.preventDefault();
        App.saveFortumoSettings();
    });

    $('#edit_paygol_settings').on('click', function(e) {
        e.preventDefault();
        App.savePaygolSettings();
    });

    $('#edit_2checkout_settings').on('click', function(e) {
        e.preventDefault();
        App.save2CheckOutSettings();
    });

    $('#edit_pagseguro_settings').on('click', function(e) {
        e.preventDefault();
        App.savePagSeguroSettings();
    });

    $('#edit_superrewards_settings').on('click', function(e) {
        e.preventDefault();
        App.saveSuperRewardsSettings();
    });

    $('#edit_paycall_settings').on('click', function(e) {
        e.preventDefault();
        App.savePayCallSettings();
    });

    $('#edit_interkassa_settings').on('click', function(e) {
        e.preventDefault();
        App.saveInterkassaSettings();
    });

    $('#edit_cuenta_digital_settings').on('click', function(e) {
        e.preventDefault();
        App.saveCuentaDigitalSettings();
    });

    $('#edit_wcoin_settings').on('click', function(e) {
        e.preventDefault();
        App.saveWcoinSettings();
    });

    $('#edit_referral_settings').on('click', function(e) {
        e.preventDefault();
        App.saveReferralSettings();
    });

    $('#edit_vip_settings').on('click', function(e) {
        e.preventDefault();
        App.saveVipSettings();
    });

    $('#edit_email_settings').on('click', function(e) {
        e.preventDefault();
        App.saveEmailSettings();
    });

    $('#edit_security_settings').on('click', function(e) {
        e.preventDefault();
        App.saveSecuritySettings();
    });

    $('#edit_lostpassword_settings').on('click', function(e) {
        e.preventDefault();
        App.saveLostPasswordSettings();
    });

    $('#edit_registration_settings').on('click', function(e) {
        e.preventDefault();
        App.saveRegistrationSettings();
    });

    $('#edit_event_settings').on('click', function(e) {
        e.preventDefault();
        App.saveTimerSettings();
    });

    $('#edit_player_settings').on('click', function(e) {
        e.preventDefault();
        App.saveRankingSettings($('#player_settings_form'));
    });

    $('#edit_guild_settings').on('click', function(e) {
        e.preventDefault();
        App.saveRankingSettings($('#guild_settings_form'));
    });

    $('#edit_gens_settings').on('click', function(e) {
        e.preventDefault();
        App.saveRankingSettings($('#gens_settings_form'));
    });

    $('#edit_voter_settings').on('click', function(e) {
        e.preventDefault();
        App.saveRankingSettings($('#voter_settings_form'));
    });

    $('#edit_killer_settings').on('click', function(e) {
        e.preventDefault();
        App.saveRankingSettings($('#killer_settings_form'));
    });

    $('#edit_online_settings').on('click', function(e) {
        e.preventDefault();
        App.saveRankingSettings($('#online_settings_form'));
    });

    $('#edit_online_list_settings').on('click', function(e) {
        e.preventDefault();
        App.saveRankingSettings($('#online_list_settings_form'));
    });

    $('#edit_bc_settings').on('click', function(e) {
        e.preventDefault();
        App.saveRankingSettings($('#bc_settings_form'));
    });

    $('#edit_ds_settings').on('click', function(e) {
        e.preventDefault();
        App.saveRankingSettings($('#ds_settings_form'));
    });

    $('#edit_cc_settings').on('click', function(e) {
        e.preventDefault();
        App.saveRankingSettings($('#cc_settings_form'));
    });

    $('#edit_cs_settings').on('click', function(e) {
        e.preventDefault();
        App.saveRankingSettings($('#cs_settings_form'));
    });

    $('#edit_duels_settings').on('click', function(e) {
        e.preventDefault();
        App.saveRankingSettings($('#duels_settings_form'));
    });

    $('#edit_rankings_status').on('click', function(e) {
        e.preventDefault();
        App.saveRankingsStatus();
    });

    $('#ranking_settings_form').find('#server').on('change', function(e) {
        e.preventDefault();
        App.reloadRankingsStatus();
    });

    $('#sql_table_settings_form').find('#server').on('change', function(e) {
        e.preventDefault();
        App.loadTableSettings($(this).val());
    });

    $('#edit_sql_table_settings').on('click', function(e) {
        e.preventDefault();
        App.saveSqlTableSettings();
    });

    $('#pre_defined_template_form').on('submit', function(e) {
        e.preventDefault();
        App.loadPreDefinedSqlTableSettings();
    });

    $('form[id^="max_level_settings_form_"]').on("submit", function(e) {
        e.preventDefault();
        var server = $(this).attr("id").split('_').slice(4).join('_');
        App.saveMaxLevel(server, $(this).serialize());
    });

    $('#days').on('change', function() {
        var selected_days = [];
        $('#days').find(':selected').each(function() {
            selected_days[$(this).val()] = $(this).val();
        });
        App.showHideTimes(selected_days);
    });

    $('#apply_to_all_classes').on('click', function() {
        var value = $('#bonus_lvl_up_dw').val();
        if ($.trim(value)) {
            if ($.isNumeric(value)) {
                $('input[id^="bonus_lvl_up_"]').each(function() {
                    $(this).val(value);
                });
                $("html, body").animate({
                    scrollTop: $('#bonus_lvl_up_ml').offset().top
                }, 1000);
            } else {
                noty($.parseJSON('{"text":"Value can only be numeric","layout":"topRight","type":"error"}'));
            }
        } else {
            noty($.parseJSON('{"text":"Please enter some numeric value","layout":"topRight","type":"error"}'));
        }

    });



    $('#all_prices').on("change, keyup", function(event) {
        $('input[name^="price"]').val($(this).val());
    });

    $('#check_all').click(function(event) {
        if (this.checked) {
            $("#import_item_form").find("input:checkbox").attr("checked", true);
        } else {
            $("#import_item_form").find("input:checkbox").attr("checked", false);
        }
    });

    $('#check_all_tickets').click(function(event) {
        if (this.checked) {
            $("#ticket_form").find("input:checkbox").each(function() {
                $(this).attr("checked", true);
            });
        } else {
            $("#ticket_form").find("input:checkbox").each(function() {
                $(this).attr("checked", false);
            });
        }
    });

    $('#set_status').change(function() {
        $(this).closest('form').trigger('submit');
    });

    $('ul.main-menu li a').each(function() {
        if ($($(this))[0].href == String(window.location))
            $(this).parent().addClass('active');
    });

    $('ul.main-menu li:not(.nav-header)').hover(function() {
        $(this).animate({
            'margin-left': '+=5'
        }, 300);
    }, function() {
        $(this).animate({
            'margin-left': '-=5'
        }, 300);
    });

    $('.accordion > a').click(function(e) {
        e.preventDefault();
        var $ul = $(this).siblings('ul');
        var $li = $(this).parent();
        if ($ul.is(':visible')) $li.removeClass('active');
        else $li.addClass('active');
        $ul.slideToggle();
    });

	$('a[id^="run_cron_task_"]').on('click', function(e) {
        e.preventDefault();
        App.runCronTask($(this).data('task'));
    });

    $('.accordion li.active:first').parents('ul').slideDown();

    $("#downloads_sortable").find("tbody#downloads_sortable_content").sortable({
        placeholder: 'ui-state-highlight',
        opacity: 0.6,
        cursor: 'move',

        update: function() {
            App.saveDownloadsOrder();
        }

    });

    $("#donate_sortable").find("tbody#donate_sortable_content").sortable({
        placeholder: 'ui-state-highlight',
        opacity: 0.6,
        cursor: 'move',

        update: function() {
            App.savePaypalOrder();
        }

    });

    $("#donate_sortable_checkout").find("tbody#donate_sortable_content_checkout").sortable({
        placeholder: 'ui-state-highlight',
        opacity: 0.6,
        cursor: 'move',

        update: function() {
            App.save2CheckOutOrder();
        }

    });

    $("#donate_sortable_pagseguro").find("tbody#donate_sortable_content_pagseguro").sortable({
        placeholder: 'ui-state-highlight',
        opacity: 0.6,
        cursor: 'move',

        update: function() {
            App.savePagSeguroOrder();
        }

    });

    $("#donate_sortable_paycall").find("tbody#donate_sortable_paycall_content").sortable({
        placeholder: 'ui-state-highlight',
        opacity: 0.6,
        cursor: 'move',

        update: function() {
            App.savePaycallOrder();
        }

    });

    $("#donate_sortable_cuenta_digital").find("tbody#donate_sortable_cuenta_digital_content").sortable({
        placeholder: 'ui-state-highlight',
        opacity: 0.6,
        cursor: 'move',

        update: function() {
            App.saveCuentaDigitalOrder();
        }

    });

    $("#donate_sortable_interkassa").find("tbody#donate_sortable_interkassa_content").sortable({
        placeholder: 'ui-state-highlight',
        opacity: 0.6,
        cursor: 'move',

        update: function() {
            App.saveInterkassaOrder();
        }

    });

    $("#socket_sortable").find("tbody#socket_sortable_content").sortable({
        placeholder: 'ui-state-highlight',
        opacity: 0.6,
        cursor: 'move',
        update: function() {
            App.saveSocketOrder();
        }
    });

    $("#serverlist_sortable").find("tbody#serverlist_sortable_content").sortable({
        placeholder: 'ui-state-highlight',
        opacity: 0.6,
        cursor: 'move',

        update: function() {
            App.saveServerOrder();
        }

    });
	
	$("#event_sortable").find("tbody#event_sortable_content").sortable({
        placeholder: 'ui-state-highlight',
        opacity: 0.6,
        cursor: 'move',

        update: function() {
            App.saveEventOrder();
        }

    });

    $("#current_plugin_sortable").find("tbody#current_plugin_sortable_content").sortable({
        placeholder: 'ui-state-highlight',
        opacity: 0.6,
        cursor: 'move',

        update: function() {
            App.savePluginOrder();
        }

    });

    $('#category').change(function() {
        location.href = DmNConfig.base_url + 'admincp/item-list/1/' + $(this).val();

    });

    $('#category-import').on('change', function() {
        var server = $('#switch_server_file').val();
        var url = location.href = DmNConfig.base_url + 'admincp/import-items/' + $(this).val() + '/' + server;
        if (url) {
            window.location = url;
        }
        return false;
    });
	
	$('#apply_account_filter').on('click', function(e) {
        e.preventDefault();
        App.filterAccounts();
    });

    $('#reset_account_filter').on('click', function(e) {
        e.preventDefault();
        App.resetAccountsFilter();
    });
	
	var accountTable = $('.accounts_datatable').DataTable({
		"dom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'i><'span12 center'p>>",
		"pagingType": "bootstrap",
		"language": {
			"lengthMenu": "_MENU_ records per page",
			"zeroRecords": "Nothing found - sorry",
			"infoEmpty": "No records available",
			"search": "Search: ",
			"searchPlaceholder": "Username",
			"processing": ""
		},
		"order": [
			[1, 'desc']
		],
		"columnDefs": [{
			"targets": 'no-sort',
			"orderable": false,
		}],
		"stateSave": true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: DmNConfig.base_url + 'admincp/load-accounts',
			type: "post"
		}
	});

    docReady();
});
var App = {
    item_slot: 0,
	filterAccounts: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/filter_account_list",
            data: $('#member_filter').serialize(),
            success: function(data) {
                if (data.error) {
                    if ($.isArray(data.error)) {
                        $.each(data.error, function(key, val) {
                            noty($.parseJSON('{"text":"' + val + '","layout":"topRight","type":"error"}'));
                        });
                    } else {
                        noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                    }
                } else {
                    $('.accounts_datatable').DataTable().ajax.reload();
                }
            }
        });
    },
    resetAccountsFilter: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/filter_account_reset",
            success: function(data) {
                if (data.error) {
                    if ($.isArray(data.error)) {
                        $.each(data.error, function(key, val) {
                            noty($.parseJSON('{"text":"' + val + '","layout":"topRight","type":"error"}'));
                        });
                    } else {
                        noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                    }
                } else {
                    $('#joined1').val('');
                    $('#joined2').val('');
                    $('#status').val('').trigger('liszt:updated');
                    $('#country').val('').trigger('liszt:updated');
                    $('#server').prop('selectedIndex', 0);
                    $('.accounts_datatable').DataTable().ajax.reload();
                }
            }
        });
    },
	runCronTask: function(task) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/run-cron-task/" + task,
            beforeSend: function() {
                $("body").css("cursor", "wait");
            },
            complete: function() {
                $("body").css("cursor", "default");
            },
            success: function(data) {
                if (data.error) {
                    if ($.isArray(data.error)) {
                        $.each(data.error, function(key, val) {
                            noty($.parseJSON('{"text":"' + val + '","layout":"topRight","type":"error"}'));
                        });
                    } else {
                        noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                    }
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                }
            }
        });
    },
    changeTaskStatus: function(task, val) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change-task-status",
            data: {
                task: task,
                val: val
            },
            success: function(data) {
                if (data.error) {
                    if ($.isArray(data.error)) {
                        $.each(data.error, function(key, val) {
                            noty($.parseJSON('{"text":"' + val + '","layout":"topRight","type":"error"}'));
                        });
                    } else {
                        noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                    }
                } else {
                    if (val == 1) {
                        $('#change_status_' + task).toggleClass('btn-success', false).toggleClass('btn-danger', true);
                        $('#change_status_' + task).attr('onclick', 'App.changeTaskStatus(\'' + task + '\', 0);').text('Disable');
                        $('#status-' + task).toggleClass('label-success', false).toggleClass('label-important', true).text('Inactive');
                    } else {
                        $('#change_status_' + task).toggleClass('btn-success', true).toggleClass('btn-danger', false);
                        $('#change_status_' + task).attr('onclick', 'App.changeTaskStatus(\'' + task + '\', 1);').text('Enable');
                        $('#status-' + task).toggleClass('label-success', true).toggleClass('label-important', false).text('Active');
                    }
                }
            }
        });
    },
    editTaskSchedule: function(task) {
        var regexp = /^(\d?\/?,?\-?(\*?))+$/;
        var minute = $('#min-' + task).val();
        var hour = $('#hour-' + task).val();
        var dom = $('#dmonth-' + task).val();
        var month = $('#month-' + task).val();
        var dweek = $('#dweek-' + task).val();
        var error = false;
        if (regexp.test(minute) == false) {
            noty($.parseJSON('{"text":"Invalid minute format allowed characters: 0-9,-*/","layout":"topRight","type":"error"}'));
            error = true;
        }
        if (regexp.test(hour) == false) {
            noty($.parseJSON('{"text":"Invalid hour format allowed characters: 0-9,-*/","layout":"topRight","type":"error"}'));
            error = true;
        }
        if (regexp.test(dom) == false) {
            noty($.parseJSON('{"text":"Invalid day of month format allowed characters: 0-9,-*/","layout":"topRight","type":"error"}'));
            error = true;
        }
        if (regexp.test(month) == false) {
            noty($.parseJSON('{"text":"Invalid month format allowed characters: 0-9,-*/","layout":"topRight","type":"error"}'));
            error = true;
        }
        if (regexp.test(dweek) == false) {
            noty($.parseJSON('{"text":"Invalid day of week format allowed characters: 0-9,-*/","layout":"topRight","type":"error"}'));
            error = true;
        }
        if (!error) {
            $.ajax({
                type: "post",
                dataType: "json",
                url: DmNConfig.base_url + "admincp/edit-task-schedule",
                data: {
                    task: task,
                    minute: minute,
                    hour: hour,
                    dom: dom,
                    month: month,
                    dweek: dweek
                },
                success: function(data) {
                    if (data.error) {
                        if ($.isArray(data.error)) {
                            $.each(data.error, function(key, val) {
                                noty($.parseJSON('{"text":"' + val + '","layout":"topRight","type":"error"}'));
                            });
                        } else {
                            noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                        }
                    } else {
                        noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    }
                }
            });
        }
    },
    initializeTooltip: function(elements, load_info, ajax_call) {
        if (load_info == false) {
            elements.tooltip({
                bodyHandler: function() {
                    return elements.attr('data-info');
                },
                showURL: false,
                fade: 10,
                track: true
            });
        } else {
            elements.tooltip({
                bodyHandler: function() {
                    var id = elements,
                        tip = $("<div></div>"),
                        hex = id.attr('data-info'),
                        info = id.attr('data-info2');
                    if (typeof(info) != 'undefined') {
                        if (info.length) {
                            tip.html(info);
                            return tip;
                        }
                    } else {
                        tip.html('<img src="' + DmNConfig.base_url + 'assets/' + DmNConfig.tmp_dir + '/images/loading.gif" />');
                        setTimeout(function() {
                            $.ajax({
                                type: 'POST',
                                dataType: 'json',

                                url: DmNConfig.base_url + ajax_call,
                                data: {
                                    item_hex: hex,
                                    ajax: 1
                                },
                                success: function(data) {
                                    if (data.error) {
                                        id.attr('data-info2', 'Invalid item!');
                                        tip.html('Invalid item!');
                                    } else {
                                        id.attr('data-info2', data.info);
                                        tip.html(data.info);
                                    }
                                }
                            });
                        }, 300);
                        return tip;
                    }
                },
                showURL: false,
                fade: 10,
                track: true
            });
        }
    },
    saveTranslation: function(key, val) {
        var lg = window.location.pathname.split('/');
        var lang = (!isNaN(+lg[lg.length - 1])) ? lg[lg.length - 2] : lg[lg.length - 1];
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_translation/" + lang,
            data: {
                key: key,
                val: val
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"Failed to save","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"Saved","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    disablePlugin: function(plugin) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: DmNConfig.base_url + plugin + "/disable",
            success: function(data) {
                if (data.error) {
                    if ($.isArray(data.error)) {
                        $.each(data.error, function(key, val) {
                            noty($.parseJSON('{"text":"' + val + '","layout":"topRight","type":"error"}'));
                        });
                    } else {
                        noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                    }
                } else {
                    $('#' + plugin + ' #status_icon').html('<span class="label label-important">Inactive</span>');
                    $('#' + plugin + ' #status_button').attr({
                        'class': 'btn btn-success'
                    });
                    $('#' + plugin + ' #status_button').html('<i class="icon-edit icon-white"></i> Enable');
                    $('#' + plugin + ' #status_button').attr('onclick', 'App.enablePlugin(\'' + plugin + '\');');
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    enablePlugin: function(plugin) {
        $.ajax({
            type: "get",
            dataType: "json",
            url: DmNConfig.base_url + plugin + "/enable",
            success: function(data) {
                if (data.error) {
                    if ($.isArray(data.error)) {
                        $.each(data.error, function(key, val) {
                            noty($.parseJSON('{"text":"' + val + '","layout":"topRight","type":"error"}'));
                        });
                    } else {
                        noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                    }
                } else {
                    $('#' + plugin + ' #status_icon').html('<span class="label label-success">Active</span>');
                    $('#' + plugin + ' #status_button').attr({
                        'class': 'btn btn-danger'
                    });
                    $('#' + plugin + ' #status_button').html('<i class="icon-edit icon-white"></i> Disable');
                    $('#' + plugin + ' #status_button').attr('onclick', 'App.disablePlugin(\'' + plugin + '\');');
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    installPlugin: function(plugin) {
        var rowCount = $('#available_plugin_sortable_content tr').length;
        $.ajax({
            type: "get",
            dataType: "json",
            url: DmNConfig.base_url + plugin + "/install",
            success: function(data) {
                if (data.error) {
                    if ($.isArray(data.error)) {
                        $.each(data.error, function(key, val) {
                            noty($.parseJSON('{"text":"' + val + '","layout":"topRight","type":"error"}'));
                        });
                    } else {
                        noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                    }
                }
                if (data.success) {
                    if ($('#no_current_plugins').length > 0) {
                        $('#no_current_plugins').remove();
                    }
                    $('#' + plugin + ' #status_icon').html('<span class="label label-success">Active</span>');
                    $('#' + plugin + ' #status_button').attr({
                        'class': 'btn btn-warning'
                    });
                    $('#' + plugin + ' #status_button').html('<i class="icon-edit icon-white"></i> Disable');
                    $('#' + plugin + ' #status_button').attr('onclick', 'App.disablePlugin(\'' + plugin + '\');');
                    $('<span style="padding-left:2px"></span><a id="uninstall_button" class="btn btn-danger" href="#" onclick="App.uninstallPlugin(\'' + plugin + '\');"><i class="icon-remove icon-white"></i>Remove</a>').insertAfter($('#' + plugin + ' #status_button'));
                    $('#current_plugin_sortable > tbody:last').append('<tr id="' + plugin + '">' + $('#' + plugin).html() + '</tr>');
                    $('#available_plugin_sortable #' + plugin).remove();
                    if (rowCount == 1) {
                        $('#available_plugin_sortable > tbody:last').append('<tr id="no_available_plugins"><td colspan="3"><div class="alert alert-info">No plugins</div></td></tr>')
                    }
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
        });
    },
    uninstallPlugin: function(plugin) {
        if (App.confirmMessage('Are you sure you want to remove this plugin?')) {
            var rowCount = $('#current_plugin_sortable tr').length;

            $.ajax({
                type: "get",
                dataType: "json",
                url: DmNConfig.base_url + plugin + "/uninstall",
                success: function(data) {
                    if (data.error) {
                        if ($.isArray(data.error)) {
                            $.each(data.error, function(key, val) {
                                noty($.parseJSON('{"text":"' + val + '","layout":"topRight","type":"error"}'));
                            });
                        } else {
                            noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                        }
                    }
                    if (data.success) {
                        if ($('#no_available_plugins').length > 0) {
                            $('#no_available_plugins').remove();
                        }
                        $('#' + plugin + ' #status_icon').html('<span class="label">Not Installed</span>');
                        $('#' + plugin + ' #status_button').attr({
                            'class': 'btn btn-success'
                        });
                        $('#' + plugin + ' #status_button').html('<i class="icon-download-alt icon-white"></i> Install');
                        $('#' + plugin + ' #status_button').attr('onclick', 'App.installPlugin(\'' + plugin + '\');');
                        $('#' + plugin + ' #about_button').attr({
                            'class': 'btn btn-inverse'
                        });
                        $('#' + plugin + ' #about_button').html('<i class="icon-leaf icon-white"></i> About');
                        $('#' + plugin + ' #about_button').attr('onclick', 'App.aboutPlugin(\'' + plugin + '\');');
                        $('#' + plugin + ' #uninstall_button').remove();
                        $('#available_plugin_sortable > tbody:last').append('<tr id="' + plugin + '">' + $('#' + plugin).html() + '</tr>');
                        $('#current_plugin_sortable #' + plugin).remove();
                        if (rowCount == 2) {
                            $('#current_plugin_sortable > tbody:last').append('<tr id="no_current_plugins"><td colspan="3"><div class="alert alert-info">No plugins</div></td></tr>')
                        }
                        noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    }
                }
            });
        }
    },
    aboutPlugin: function(plugin) {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: DmNConfig.base_url + plugin + '/about',
            success: function(data) {
                if (typeof data.error != 'undefined') {
                    if ($.isArray(data.error)) {
                        $.each(data.error, function(key, val) {
                            noty($.parseJSON('{"text":"' + val + '","layout":"topRight","type":"error"}'));
                        });
                    } else {
                        noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                    }
                } else {
                    var plugin_dialog = $('<div id="plugin_content"><p></p></div>');
                    plugin_dialog.html(data.about);
                    plugin_dialog.dialog({
                        modal: true,
                        width: 700,
                        height: 350,
                        title: 'About Plugin',
                        show: 'clip',
                        hide: 'clip',
                        close: function() {
                            $(this).dialog('destroy');
                        }
                    });
                }
            }
        });
    },
    savePluginOrder: function() {
        var order = $('#current_plugin_sortable_content').sortable('toArray');
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_plugin_order",
            data: {
                order: order
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                }
            }
        });
    },
    savePrice: function(key, val) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_item_price/" + key,
            data: {
                price: val
            },
            success: function(data) {
                if (typeof data.error != 'undefined') {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                };

            }
        });
    },
    loadDonationSettings: function(server, type) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/load_donation_settings",
            data: {
                server: server,
                type: type
            },
            success: function(data) {
                if (typeof data.error != 'undefined') {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    if (typeof data.info != 'undefined') {
                        if (type == 'paypal') {
                            $('#paypal_settings_form').find('#server option[value=' + server + ']').prop("selected", true);
                            if (data.info.active == 1) {
                                $('#paypal_settings_form').find('#active option[value=1]').prop("selected", true);
                            } else {
                                $('#paypal_settings_form').find('#active option[value=0]').prop("selected", true);
                            }
                            if (data.info.sandbox == 1) {
                                $('#paypal_settings_form #sandbox option[value=1]').prop("selected", true);
                            } else {
                                $('#paypal_settings_form #sandbox option[value=0]').prop("selected", true);
                            }
                            if (data.info.type == 1) {
                                $('#paypal_settings_form #type option[value=1]').prop("selected", true);
                            } else {
                                $('#paypal_settings_form #type option[value=2]').prop("selected", true);
                                $('#express_checkout').show();
                            }
                            $('#paypal_settings_form #email').val(data.info.email);
                            $('#paypal_settings_form #api_username').val(data.info.api_username);
                            $('#paypal_settings_form #api_password').val(data.info.api_password);
                            $('#paypal_settings_form #api_signature').val(data.info.api_signature);
                            if (data.info.punish_player == 1) {
                                $('#paypal_settings_form #punish_player option[value=1]').prop("selected", true);
                            } else {
                                $('#paypal_settings_form #punish_player option[value=0]').prop("selected", true);
                            }
                            if (data.info.reward_type == 1) {
                                $('#paypal_settings_form #reward_type option[value=1]').prop("selected", true);
                            } else {
                                $('#paypal_settings_form #reward_type option[value=2]').prop("selected", true);
                            }
                            if (typeof data.info.paypal_fee != 'undefined') {
                                $('#paypal_settings_form #paypal_fee option[value="' + data.info.paypal_fee + '"]').prop("selected", true);
                            }

                            if (typeof data.info.paypal_fixed_fee != 'undefined') {
                                $('#paypal_settings_form #paypal_fixed_fee').val(data.info.paypal_fixed_fee);
                            }
                        }
                        if (type == 'paymentwall') {
                            $('#paymentwall_settings_form #server option[value=' + server + ']').prop("selected", true);
                            if (data.info.active == 1) {
                                $('#paymentwall_settings_form #active option[value=1]').prop("selected", true);
                            } else {
                                $('#paymentwall_settings_form #active option[value=0]').prop("selected", true);
                            }

                            $('#paymentwall_settings_form #api_key').val(data.info.api_key);
                            $('#paymentwall_settings_form #secret_key').val(data.info.secret_key);
                            $('#paymentwall_settings_form #width').val(data.info.width);
							if (data.info.sign_version == '1') {
                                $('#paymentwall_settings_form #sign_version option[value="1"]').prop("selected", true);
                            } else if (data.info.sign_version == '2') {
                                $('#paymentwall_settings_form #sign_version option[value="2"]').prop("selected", true);
                            } else if (data.info.sign_version == '3') {
                                $('#paymentwall_settings_form #sign_version option[value="3"]').prop("selected", true);
                            } else if (data.info.sign_version == '4') {
                                $('#paymentwall_settings_form #sign_version option[value="4"]').prop("selected", true);
                            } else{
								$('#paymentwall_settings_form #sign_version option[value="1"]').prop("selected", true);
							}

                            if (data.info.widget == 'm2_1') {
                                $('#paymentwall_settings_form #widget option[value="m2_1"]').prop("selected", true);
                            } else if (data.info.widget == 'm1_1') {
                                $('#paymentwall_settings_form #widget option[value="m1_1"]').prop("selected", true);
                            } else if (data.info.widget == 'p4_1') {
                                $('#paymentwall_settings_form #widget option[value="p4_1"]').prop("selected", true);
                            } else if (data.info.widget == 'p3_1') {
                                $('#paymentwall_settings_form #widget option[value="p3_1"]').prop("selected", true);
                            } else if (data.info.widget == 'p2_1') {
                                $('#paymentwall_settings_form #widget option[value="p2_1"]').prop("selected", true);
                            } else if (data.info.widget == 'p1_1') {
                                $('#paymentwall_settings_form #widget option[value="p1_1"]').prop("selected", true);
                            } else if (data.info.widget == 'w1_1') {
                                $('#paymentwall_settings_form #widget option[value="w1_1"]').prop("selected", true);
                            } else if (data.info.widget == 's2_1') {
                                $('#paymentwall_settings_form #widget option[value="s2_1"]').prop("selected", true);
                            } else if (data.info.widget == 's1_1') {
                                $('#paymentwall_settings_form #widget option[value="s1_1"]').prop("selected", true);
                            } else {
                                $('#paymentwall_settings_form #widget option[value="s3_1"]').prop("selected", true);
                            }
                            if (data.info.reward_type == 1) {
                                $('#paymentwall_settings_form #reward_type option[value=1]').prop("selected", true);
                            } else {
                                $('#paymentwall_settings_form #reward_type option[value=2]').prop("selected", true);
                            }
                        }
                        if (type == 'fortumo') {
                            $('#fortumo_settings_form #server option[value=' + server + ']').prop("selected", true);
                            if (data.info.active == 1) {
                                $('#fortumo_settings_form #active option[value=1]').prop("selected", true);
                            } else {
                                $('#fortumo_settings_form #active option[value=0]').prop("selected", true);
                            }
                            if (data.info.sandbox == 1) {
                                $('#fortumo_settings_form #sandbox option[value=1]').prop("selected", true);
                            } else {
                                $('#fortumo_settings_form #sandbox option[value=0]').prop("selected", true);
                            }
                            $('#fortumo_settings_form #service_id').val(data.info.service_id);
                            $('#fortumo_settings_form #secret').val(data.info.secret);
                            $('#fortumo_settings_form #allowed_ip_list').tagsinput('removeAll');
                            $('#fortumo_settings_form #allowed_ip_list').tagsinput('add', data.info.allowed_ip_list);
                            if (data.info.reward_type == 1) {
                                $('#fortumo_settings_form #reward_type option[value=1]').prop("selected", true);
                            } else {
                                $('#fortumo_settings_form #reward_type option[value=2]').prop("selected", true);
                            }
                        }
                        if (type == 'paygol') {
                            $('#paygol_settings_form #server option[value=' + server + ']').prop("selected", true);
                            if (data.info.active == 1) {
                                $('#paygol_settings_form #active option[value=1]').prop("selected", true);
                            } else {
                                $('#paygol_settings_form #active option[value=0]').prop("selected", true);
                            }
                            $('#paygol_settings_form #service_id').val(data.info.service_id);
                            $('#paygol_settings_form #reward').val(data.info.reward);
                            $('#paygol_settings_form #currency_code').val(data.info.currency_code);
                            $('#paygol_settings_form #service_price').val(data.info.service_price);
                            if (data.info.reward_type == 1) {
                                $('#paygol_settings_form #reward_type option[value=1]').prop("selected", true);
                            } else {
                                $('#paygol_settings_form #reward_type option[value=2]').prop("selected", true);
                            }
                        }
                        if (type == '2checkout') {
                            $('#2checkout_settings_form #server option[value=' + server + ']').prop("selected", true);
                            if (data.info.active == 1) {
                                $('#2checkout_settings_form #active option[value=1]').prop("selected", true);
                            } else {
                                $('#2checkout_settings_form #active option[value=0]').prop("selected", true);
                            }
                            $('#2checkout_settings_form #seller_id').val(data.info.seller_id);
                            $('#2checkout_settings_form #private_key').val(data.info.private_key);
                            $('#2checkout_settings_form #private_secret_word').val(data.info.private_secret_word);
                            if (data.info.reward_type == 1) {
                                $('#2checkout_settings_form #reward_type option[value=1]').prop("selected", true);
                            } else {
                                $('#2checkout_settings_form #reward_type option[value=2]').prop("selected", true);
                            }
                        }
                        if (type == 'pagseguro') {
                            $('#pagseguro_settings_form #server option[value=' + server + ']').prop("selected", true);
                            if (data.info.active == 1) {
                                $('#pagseguro_settings_form #active option[value=1]').prop("selected", true);
                            } else {
                                $('#pagseguro_settings_form #active option[value=0]').prop("selected", true);
                            }
                            $('#pagseguro_settings_form #email').val(data.info.email);
                            $('#pagseguro_settings_form #token').val(data.info.token);
                            if (data.info.reward_type == 1) {
                                $('#pagseguro_settings_form #reward_type option[value=1]').prop("selected", true);
                            } else {
                                $('#pagseguro_settings_form #reward_type option[value=2]').prop("selected", true);
                            }
                        }
                        if (type == 'superrewards') {
                            $('#superrewards_settings_form #server option[value=' + server + ']').prop("selected", true);
                            if (data.info.active == 1) {
                                $('#superrewards_settings_form #active option[value=1]').prop("selected", true);
                            } else {
                                $('#superrewards_settings_form #active option[value=0]').prop("selected", true);
                            }
                            $('#superrewards_settings_form #app_hash').val(data.info.app_hash);
                            $('#superrewards_settings_form #postback_key').val(data.info.postback_key);
                            $('#superrewards_settings_form #allowed_ip_list').tagsinput('removeAll');
                            $('#superrewards_settings_form #allowed_ip_list').tagsinput('add', data.info.allowed_ip_list);
                            if (data.info.reward_type == 1) {
                                $('#superrewards_settings_form #reward_type option[value=1]').prop("selected", true);
                            } else {
                                $('#superrewards_settings_form #reward_type option[value=2]').prop("selected", true);
                            }
                        }
                        if (type == 'paycall') {
                            $('#paycall_settings_form #server option[value=' + server + ']').prop("selected", true);
                            if (data.info.active == 1) {
                                $('#paycall_settings_form #active option[value=1]').prop("selected", true);
                            } else {
                                $('#paycall_settings_form #active option[value=0]').prop("selected", true);
                            }
                            if (data.info.sandbox == 1) {
                                $('#paycall_settings_form #sandbox option[value=1]').prop("selected", true);
                            } else {
                                $('#paycall_settings_form #sandbox option[value=0]').prop("selected", true);
                            }
                            $('#paycall_settings_form #business_code').val(data.info.business_code);
                            if (data.info.reward_type == 1) {
                                $('#paycall_settings_form #reward_type option[value=1]').prop("selected", true);
                            } else {
                                $('#paycall_settings_form #reward_type option[value=2]').prop("selected", true);
                            }
                        }
                        if (type == 'interkassa') {
                            $('#interkassa_settings_form #server option[value=' + server + ']').prop("selected", true);
                            if (data.info.active == 1) {
                                $('#interkassa_settings_form #active option[value=1]').prop("selected", true);
                            } else {
                                $('#interkassa_settings_form #active option[value=0]').prop("selected", true);
                            }
                            $('#interkassa_settings_form #shop_id').val(data.info.shop_id);
                            $('#interkassa_settings_form #secret_key').val(data.info.secret_key);
                            if (data.info.reward_type == 1) {
                                $('#interkassa_settings_form #reward_type option[value=1]').prop("selected", true);
                            } else {
                                $('#interkassa_settings_form #reward_type option[value=2]').prop("selected", true);
                            }
                        }
                        if (type == 'cuenta_digital') {
                            $('#cuenta_digital_settings_form #server option[value=' + server + ']').prop("selected", true);
                            if (data.info.active == 1) {
                                $('#cuenta_digital_settings_form #active option[value=1]').prop("selected", true);
                            } else {
                                $('#cuenta_digital_settings_form #active option[value=0]').prop("selected", true);
                            }
                            if (data.info.api_type == 1) {
                                $('#cuenta_digital_settings_form #api_type option[value=1]').prop("selected", true);
                            } else {
                                $('#cuenta_digital_settings_form #api_type option[value=2]').prop("selected", true);
                            }
                            $('#cuenta_digital_settings_form #account_id').val(data.info.account_id);
                            $('#cuenta_digital_settings_form #voucher_api_password').val(data.info.voucher_api_password);
                            if (data.info.reward_type == 1) {
                                $('#cuenta_digital_settings_form #reward_type option[value=1]').prop("selected", true);
                            } else {
                                $('#cuenta_digital_settings_form #reward_type option[value=2]').prop("selected", true);
                            }
                        }
                    }
                }
            },
            error: function(error) {
                $('select', '#' + type + '_settings_form').each(function() {
                    if ($(this).val() == server) {
                        $(this).prop("selected", true);
                    } else {
                        $(this).val($(this).prop('defaultSelected'));
                    }
                });
                $('input[type=text]', '#' + type + '_settings_form').each(function() {
                    $(this).val('');
                });

                if (type == 'fortumo') {
                    $('#fortumo_settings_form #allowed_ip_list').tagsinput('removeAll');
                    $('#fortumo_settings_form #allowed_ip_list').tagsinput('add', '79.125.125.1,79.125.5.205,79.125.5.95,54.72.6.126,54.72.6.27,54.72.6.17,54.72.6.23');
                }
                if (type == 'superrewards') {
                    $('#superrewards #allowed_ip_list').tagsinput('removeAll');
                    $('#superrewards #allowed_ip_list').tagsinput('add', '54.85.0.76,54.84.205.80,54.84.27.163');
                }
            }
        });
    },
    loadRankingsSettings: function(server, type) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/load_ranking_settings",
            data: {
                server: server,
                type: type
            },
            success: function(data) {
                if (typeof data.error != 'undefined') {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    if (typeof data.info != 'undefined') {
                        if (type == 'player' || type == 'killer') {
                            var selector = (type == 'player') ? $('#player_settings_form') : $('#killer_settings_form');
                            selector.find('#server option[value=' + server + ']').prop("selected", true);
                            if (data.info.is_sidebar_module == 1) {
                                selector.find('#is_sidebar_module option[value=1]').prop("selected", true);
                            } else {
                                selector.find('#is_sidebar_module option[value=0]').prop("selected", true);
                            }
                            selector.find("#count").val(data.info.count);
                            selector.find("#count_in_sidebar").val(data.info.count_in_sidebar);
                            selector.find("#cache_time").val(data.info.cache_time);
                            selector.find("#excluded_list").tagsinput('removeAll');
                            selector.find("#excluded_list").tagsinput('add', data.info.excluded_list);
                            if (data.info.display_resets == 1) {
                                selector.find('#display_resets option[value=1]').prop("selected", true);
                            } else {
                                selector.find('#display_resets option[value=0]').prop("selected", true);
                            }
                            if (data.info.display_gresets == 1) {
                                selector.find('#display_gresets option[value=1]').prop("selected", true);
                            } else {
                                selector.find('#display_gresets option[value=0]').prop("selected", true);
                            }
                            if (data.info.display_master_level == 1) {
                                selector.find('#display_master_level option[value=1]').prop("selected", true);
                            } else {
                                selector.find('#display_master_level option[value=0]').prop("selected", true);
                            }
                            if (type == 'player') {
                                if (data.info.display_status == 1) {
                                    selector.find('#display_status option[value=1]').prop("selected", true);
                                } else {
                                    selector.find('#display_status option[value=0]').prop("selected", true);
                                }
                            }
                            if (data.info.display_gms == 1) {
                                selector.find('#display_gms option[value=1]').prop("selected", true);
                            } else {
                                selector.find('#display_gms option[value=0]').prop("selected", true);
                            }
                            if (data.info.display_country == 1) {
                                selector.find('#display_country option[value=1]').prop("selected", true);
                            } else {
                                selector.find('#display_country option[value=0]').prop("selected", true);
                            }
                        }
                        if (type == 'guild' || type == 'online') {
                            var selector2 = (type == 'guild') ? $('#guild_settings_form') : $('#online_settings_form');
                            selector2.find('#server option[value=' + server + ']').prop("selected", true);
                            if (data.info.is_sidebar_module == 1) {
                                selector2.find('#is_sidebar_module option[value=1]').prop("selected", true);
                            } else {
                                selector2.find('#is_sidebar_module option[value=0]').prop("selected", true);
                            }
                            selector2.find("#count").val(data.info.count);
                            selector2.find("#count_in_sidebar").val(data.info.count_in_sidebar);
                            selector2.find("#cache_time").val(data.info.cache_time);
                            selector2.find("#excluded_list").tagsinput('removeAll');
                            selector2.find("#excluded_list").tagsinput('add', data.info.excluded_list);
							if (type == 'guild'){
								 selector2.find('#order_by option[value='+data.info.order_by+']').prop("selected", true);
							}
                            if (type == 'online') {
                                if (data.info.display_country == 1) {
                                    selector2.find('#display_country option[value=1]').prop("selected", true);
                                } else {
                                    selector2.find('#display_country option[value=0]').prop("selected", true);
                                }
                            }
                        }
						
                        if (type == 'gens') {
                            $('#gens_settings_form').find('#server option[value=' + server + ']').prop("selected", true);
                            $('#gens_settings_form').find("#count").val(data.info.count);
                            $('#gens_settings_form').find("#cache_time").val(data.info.cache_time);
                            if (data.info.type == 'scf') {
                                $('#gens_settings_form').find('#type option[value="scf"]').prop("selected", true);
                            } else if (data.info.type == 'zteam') {
                                $('#gens_settings_form').find('#type option[value="zteam"]').prop("selected", true);
                            } else if (data.info.type == 'exteam') {
                                $('#gens_settings_form').find('#type option[value="exteam"]').prop("selected", true);
                            } else if (data.info.type == 'xteam') {
                                $('#gens_settings_form').find('#type option[value="xteam"]').prop("selected", true);
                            } else if (data.info.type == 'muengine') {
                                $('#gens_settings_form').find('#type option[value="muengine"]').prop("selected", true);
                            } else {
                                $('#gens_settings_form').find('#type option[value="igcn"]').prop("selected", true);
                            }
                        }
                        if (type == 'voter' || type == 'bc' || type == 'ds' || type == 'cc' || type == 'duels') {
                            var selector4 = $('#' + type + '_settings_form');
                            selector4.find('#server option[value=' + server + ']').prop("selected", true);
                            if (data.info.is_sidebar_module == 1) {
                                selector4.find('#is_sidebar_module option[value=1]').prop("selected", true);
                            } else {
                                selector4.find('#is_sidebar_module option[value=0]').prop("selected", true);
                            }
                            selector4.find("#count").val(data.info.count);
                            selector4.find("#count_in_sidebar").val(data.info.count_in_sidebar);
                            selector4.find("#cache_time").val(data.info.cache_time);
                            selector4.find("#excluded_list").tagsinput('removeAll');
                            selector4.find("#excluded_list").tagsinput('add', data.info.excluded_list);
                            if (type == 'voter') {
                                if (data.info.display_country == 1) {
                                    selector4.find('#display_country option[value=1]').prop("selected", true);
                                } else {
                                    selector4.find('#display_country option[value=0]').prop("selected", true);
                                }
                            }
                            if (data.info.is_monthly_reward == 1) {
                                selector4.find('#is_monthly_reward option[value=1]').prop("selected", true);
                            } else {
                                selector4.find('#is_monthly_reward option[value=0]').prop("selected", true);
                            }

                            if (typeof data.info.amount_of_players_to_reward != undefined) {
                                selector4.find('#amount_of_players_to_reward option[value=' + data.info.amount_of_players_to_reward + ']').prop("selected", true);
                            }
                            selector4.find("#reward_formula").val(data.info.reward_formula);
                            if (data.info.reward_type == 2) {
                                selector4.find('#reward_type option[value=2]').prop("selected", true);
                            } else {
                                selector4.find('#reward_type option[value=1]').prop("selected", true);
                            }
                        }
                        if (type == 'online_list') {
                            var selector3 = $('#online_list_settings_form');
                            selector3.find('#server option[value=' + server + ']').prop("selected", true);
                            if (data.info.active == 1) {
                                selector3.find('#active option[value=1]').prop("selected", true);
                            } else {
                                selector3.find('#active option[value=0]').prop("selected", true);
                            }
                            selector3.find("#cache_time").val(data.info.cache_time);
                            selector3.find("#excluded_list").tagsinput('removeAll');
                            selector3.find("#excluded_list").tagsinput('add', data.info.excluded_list);
                            if (data.info.display_resets == 1) {
                                selector3.find('#display_resets option[value=1]').prop("selected", true);
                            } else {
                                selector3.find('#display_resets option[value=0]').prop("selected", true);
                            }
                            if (data.info.display_gresets == 1) {
                                selector3.find('#display_gresets option[value=1]').prop("selected", true);
                            } else {
                                selector3.find('#display_gresets option[value=0]').prop("selected", true);
                            }
                            if (data.info.display_gms == 1) {
                                selector3.find('#display_gms option[value=1]').prop("selected", true);
                            } else {
                                selector3.find('#display_gms option[value=0]').prop("selected", true);
                            }
                            if (data.info.display_country == 1) {
                                selector3.find('#display_country option[value=1]').prop("selected", true);
                            } else {
                                selector3.find('#display_country option[value=0]').prop("selected", true);
                            }
                        }
                    }
                }
            },
            error: function(error) {
                $('select', '#' + type + '_settings_form').each(function() {
                    if ($(this).val() == server) {
                        $(this).prop("selected", true);
                    } else {
                        $(this).val($(this).prop('defaultSelected'));
                    }
                });
                $('input[type=text]', '#' + type + '_settings_form').each(function() {
                    $(this).val('');
                });

                if ($("#excluded_list").length) {
                    $("#excluded_list").tagsinput('removeAll');
                }
            }
        });
    },
    loadTableSettings: function(server) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/load_table_settings",
            data: {
                server: server
            },
            success: function(data) {
                if (typeof data.error != 'undefined') {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    if (typeof data.info != 'undefined') {
                        if (typeof data.info.resets != 'undefined') {
                            App.updateSqlSettings(data.info.resets, 'resets');
                        }
                        if (typeof data.info.grand_resets != 'undefined') {
                            App.updateSqlSettings(data.info.grand_resets, 'grand_resets');
                        }
                        if (typeof data.info.wcoins != 'undefined') {
                            App.updateSqlSettings(data.info.wcoins, 'wcoins');
                        }
                        if (typeof data.info.master_level != 'undefined') {
                            App.updateSqlSettings(data.info.master_level, 'master_level');
                        }
                        if (typeof data.info.bc != 'undefined') {
                            App.updateSqlSettings(data.info.bc, 'bc');
                        }
                        if (typeof data.info.ds != 'undefined') {
                            App.updateSqlSettings(data.info.ds, 'ds');
                        }
                        if (typeof data.info.cc != 'undefined') {
                            App.updateSqlSettings(data.info.cc, 'cc');
                        }
                        if (typeof data.info.cs != 'undefined') {
                            App.updateSqlSettings(data.info.cs, 'cs');
                        }
                        if (typeof data.info.duels != 'undefined') {
                            App.updateSqlSettings(data.info.duels, 'duels');
                        }
                    }
                }
            },
            error: function(error) {
                $('select', '#sql_table_settings_form').each(function() {
                    if ($(this).val() == server) {
                        $(this).prop("selected", true);
                    } else {
                        $(this).val($(this).prop('defaultSelected'));
                    }
                });
                $('input[type=text]', '#sql_table_settings_form').each(function() {
                    $(this).val('');
                });
            }
        });
    },
    updateSqlSettings: function(data, id) {
        var db_info = ['account', 'game', 'web'];
        var selector = $('#sql_table_settings_form');
        if (db_info.indexOf(data.db) == -1) {
            selector.find('#' + id + '-db option[value=custom]').prop("selected", true);
            $('#' + id + '_custom-db').val(data.db).show();
        } else {
            selector.find('#' + id + '-db option[value=' + data.db + ']').prop("selected", true);
        }
        selector.find('#' + id + '-table').val(data.table);
        selector.find('#' + id + '-column').val(data.column);
        if (typeof data.column2 != 'undefined') {
            selector.find('#' + id + '-column2').val(data.column2);
        }
        if (typeof data.column3 != 'undefined') {
            selector.find('#' + id + '-column3').val(data.column3);
        }
        selector.find('#' + id + '-identifier_column').val(data.identifier_column);

    },
    loadWcoinSettings: function(server) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/load_wcoin_settings",
            data: {
                server: server,
            },
            success: function(data) {
                if (typeof data.error != 'undefined') {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    if (typeof data.info != 'undefined') {
                        $('#wcoin_settings_form #server option[value=' + server + ']').prop("selected", true);
                        if (data.info.active == 1) {
                            $('#wcoin_settings_form #active option[value=1]').prop("selected", true);
                        } else {
                            $('#wcoin_settings_form #active option[value=0]').prop("selected", true);
                        }
                        $('#wcoin_settings_form #reward_coin').val(data.info.reward_coin);
                        if (data.info.credits_type == 1) {
                            $('#wcoin_settings_form #credits_type option[value=1]').prop("selected", true);
                        } else {
                            $('#wcoin_settings_form #credits_type option[value=2]').prop("selected", true);
                        }
                        if (data.info.change_back == 1) {
                            $('#wcoin_settings_form #change_back option[value=1]').prop("selected", true);
                        } else {
                            $('#wcoin_settings_form #change_back option[value=0]').prop("selected", true);
                        }
                        $('#wcoin_settings_form #min_rate').val(data.info.min_rate);
                        if (data.info.display_wcoins == 1) {
                            $('#wcoin_settings_form #display_wcoins option[value=1]').prop("selected", true);
                        } else {
                            $('#wcoin_settings_form #display_wcoins option[value=0]').prop("selected", true);
                        }
                    }
                }
            },
            error: function(error) {
                $('select', '#wcoin_settings_form').each(function() {
                    if ($(this).val() == server) {
                        $(this).prop("selected", true);
                    } else {
                        $(this).val($(this).prop('defaultSelected'));
                    }
                });
                $('input[type=text]', '#wcoin_settings_form').each(function() {
                    $(this).val('');
                });
            }
        });
    },
    loadReferralSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/load_referral_settings",
            data: {
                load_settings: 1,
            },
            success: function(data) {
                if (typeof data.error != 'undefined') {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    if (typeof data.info != 'undefined') {
                        if (data.info.active == 1) {
                            $('#referral_settings_form #active option[value=1]').prop("selected", true);
                        } else {
                            $('#referral_settings_form #active option[value=0]').prop("selected", true);
                        }
                        $('#referral_settings_form #reward_on_registration').val(data.info.reward_on_registration);
                        if (data.info.reward_type == 1) {
                            $('#referral_settings_form #reward_type option[value=1]').prop("selected", true);
                        } else {
                            $('#referral_settings_form #reward_type option[value=2]').prop("selected", true);
                        }
                        if (data.info.claim_type == 0) {
                            $('#referral_settings_form #claim_type option[value=0]').prop("selected", true);
                        } else {
                            $('#referral_settings_form #claim_type option[value=1]').prop("selected", true);
                        }
                        if (data.info.compare_ips == 0) {
                            $('#referral_settings_form #compare_ips option[value=0]').prop("selected", true);
                        } else {
                            $('#referral_settings_form #compare_ips option[value=1]').prop("selected", true);
                        }
                    }
                }
            },
            error: function(error) {
                $('select', '#referral_settings_form').each(function() {
                    if ($(this).val() == server) {
                        $(this).prop("selected", true);
                    } else {
                        $(this).val($(this).prop('defaultSelected'));
                    }
                });
                $('input[type=text]', '#referral_settings_form').each(function() {
                    $(this).val('');
                });
            }
        });
    },
    saveWcoinSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_wcoin_settings",
            data: $('#wcoin_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    saveEmailSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_email_settings",
            data: $('#email_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    saveSecuritySettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_security_settings",
            data: $('#security_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    saveLostPasswordSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_lostpassword_settings",
            data: $('#lostpassword_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    saveRegistrationSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_registration_settings",
            data: $('#registration_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    saveVipSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_vip_settings",
            data: $('#vip_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    changeVipStatus: function(id, status) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_vip_status",
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    if (status == 1) {
                        $('#vip_status_icon_' + id).html('<span class="label label-success">Active</span>');
                        $('#vip_status_button_' + id).attr({
                            'class': 'btn btn-danger'
                        });
                        $('#vip_status_button_' + id).html('<i class="icon-edit icon-white"></i> Disable');
                        $('#vip_status_button_' + id).attr('onclick', 'App.changeVipStatus(' + id + ', 0);');
                    } else {
                        $('#vip_status_icon_' + id).html('<span class="label label-important">Inactive</span>');
                        $('#vip_status_button_' + id).attr({
                            'class': 'btn btn-success'
                        });
                        $('#vip_status_button_' + id).html('<i class="icon-edit icon-white"></i> Enable');
                        $('#vip_status_button_' + id).attr('onclick', 'App.changeVipStatus(' + id + ', 1);');
                    }
                }
            }
        });
    },
    deleteVipPackage: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/delete_vip_package",
            data: {
                id: id
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    $('tr#' + id).hide();
                }
            }
        });
    },
    saveTimerSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_timer_settings",
            data: $('#event_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    saveRankingSettings: function(element) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_ranking_settings",
            data: element.serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    saveSqlTableSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_table_settings",
            data: $('#sql_table_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    loadPreDefinedSqlTableSettings: function() {
        var team = $('#team_template').val();
        var server = $('#sql_table_settings_form').find('#server').val();
        if (team != '' && server != '') {
            $.ajax({
                type: "post",
                dataType: "json",
                url: DmNConfig.base_url + "admincp/load_pre_defined_table_settings",
                data: {
                    team: team,
                    server: server
                },
                success: function(data) {
                    if (data.error) {
                        noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                    } else {
                        if (typeof data.info != 'undefined') {
                            if (typeof data.info.resets != 'undefined') {
                                App.updateSqlSettings(data.info.resets, 'resets');
                            }
                            if (typeof data.info.grand_resets != 'undefined') {
                                App.updateSqlSettings(data.info.grand_resets, 'grand_resets');
                            }
                            if (typeof data.info.wcoins != 'undefined') {
                                App.updateSqlSettings(data.info.wcoins, 'wcoins');
                            }
                            if (typeof data.info.master_level != 'undefined') {
                                App.updateSqlSettings(data.info.master_level, 'master_level');
                            }
                            if (typeof data.info.bc != 'undefined') {
                                App.updateSqlSettings(data.info.bc, 'bc');
                            }
                            if (typeof data.info.ds != 'undefined') {
                                App.updateSqlSettings(data.info.ds, 'ds');
                            }
                            if (typeof data.info.cc != 'undefined') {
                                App.updateSqlSettings(data.info.cc, 'cc');
                            }
                            if (typeof data.info.cs != 'undefined') {
                                App.updateSqlSettings(data.info.cs, 'cs');
                            }
                            if (typeof data.info.duels != 'undefined') {
                                App.updateSqlSettings(data.info.duels, 'duels');
                            }
                            $("html, body").animate({
                                scrollTop: $('#edit_sql_table_settings').offset().top
                            }, 2000);
                        }
                    }
                }
            });
        }
    },
    saveReferralSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_referral_settings",
            data: $('#referral_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    addReferralReward: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/add_referral_package",
            data: {
                req_lvl: $("#req_lvl").val(),
                req_res: $("#req_res").val(),
                req_gres: $("#req_gres").val(),
                reward: $("#reward").val(),
                reward_type: $("#reward_type_custom").val(),
                server: $("#server").val()
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    var html = '<tr id="' + data.id + '"><td>' + $("#req_lvl").val() + '</td>';
                    html += '<td class="center">' + $("#req_res").val() + '</td>';
                    html += '<td class="center">' + $("#req_gres").val() + '</td>';
                    html += '<td class="center">' + $("#reward").val() + ' ' + data.reward_type + '</td>';
                    html += '<td class="center">' + data.server + '</td>';
                    html += '<td class="center" id="status_icon_' + data.id + '"><span class="label label-success">Active</span></td><td class="center"><a class="btn btn-danger" href="#" id="status_button_' + data.id + '" onclick="App.changeReferralRewardStatus(' + data.id + ', 0);"><i class="icon-edit icon-white"></i> Disable</a>  <a class="btn btn-danger" href="#" onclick="App.deleteReferralReward(' + data.id + ');"><i class="icon-trash icon-white"></i> Delete</a></td></tr>';
                    $('#referral_sortable > tbody:last').append(html);
                }
            }
        });
    },
    changeReferralRewardStatus: function(id, status) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_referral_reward_status",
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    if (status == 1) {
                        $('#status_icon_' + id).html('<span class="label label-success">Active</span>');
                        $('#status_button_' + id).attr({
                            'class': 'btn btn-danger'
                        });
                        $('#status_button_' + id).html('<i class="icon-edit icon-white"></i> Disable');
                        $('#status_button_' + id).attr('onclick', 'App.changeReferralRewardStatus(' + id + ', 0);');
                    } else {
                        $('#status_icon_' + id).html('<span class="label label-important">Inactive</span>');
                        $('#status_button_' + id).attr({
                            'class': 'btn btn-success'
                        });
                        $('#status_button_' + id).html('<i class="icon-edit icon-white"></i> Enable');
                        $('#status_button_' + id).attr('onclick', 'App.changeReferralRewardStatus(' + id + ', 1);');
                    }
                }
            }
        });
    },
    deleteReferralReward: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/delete_referral_reward",
            data: {
                id: id
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    $('tr#' + id).hide();
                }
            }
        });
    },
    deleteEventTimer: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/delete_event_timer",
            data: {
                id: id
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    $('tr#' + id).hide();
                }
            }
        });
    },
    saveSchedulerSettings: function(form) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_scheduler_settings",
            data: form.serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    saveVoteRewardSettings: function(form) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_votereward_settings",
            data: form.serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    savePaypalSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_paypal_settings",
            data: $('#paypal_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    savePaymentwallSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_paymentwall_settings",
            data: $('#paymentwall_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    saveFortumoSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_fortumo_settings",
            data: $('#fortumo_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    savePaygolSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_paygol_settings",
            data: $('#paygol_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    save2CheckOutSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_twocheckout_settings",
            data: $('#2checkout_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    savePagSeguroSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_pagseguro_settings",
            data: $('#pagseguro_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    saveSuperRewardsSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_superrewards_settings",
            data: $('#superrewards_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    savePayCallSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_paycall_settings",
            data: $('#paycall_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    saveInterkassaSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_interkassa_settings",
            data: $('#interkassa_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    saveCuentaDigitalSettings: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_cuenta_digital_settings",
            data: $('#cuenta_digital_settings_form').serialize(),
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    saveDownloadsOrder: function() {
        var order = $('#downloads_sortable_content').sortable('toArray');
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_downloads_order",
            data: {
                order: order
            },
            success: function(data) {}

        });
    },
    savePaypalOrder: function() {
        var order = $('#donate_sortable_content').sortable('toArray');
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_paypal_order",
            data: {
                order: order
            },
            success: function(data) {}

        });
    },
    editPaypal: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/edit_paypal_package",
            data: {
                id: id,
                title: $("#pack_title_" + id).val(),
                price: $("#pack_price_" + id).val(),
                currency: $("#pack_currency_" + id).val(),
                reward: $("#pack_reward_" + id).val(),
                server: $("#pack_server_" + id).val()
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    deletePaypal: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/delete_paypal_package",
            data: {
                id: id
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    $('tr#' + id).hide();
                }
            }
        });
    },
    changePaypalStatus: function(id, status) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_paypal_status",
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    if (status == 1) {
                        $('#status_icon_' + id).html('<span class="label label-success">Active</span>');
                        $('#status_button_' + id).attr({
                            'class': 'btn btn-danger'
                        });
                        $('#status_button_' + id).html('<i class="icon-edit icon-white"></i> Disable');
                        $('#status_button_' + id).attr('onclick', 'App.changePaypalStatus(' + id + ', 0);');
                    } else {
                        $('#status_icon_' + id).html('<span class="label label-important">Inactive</span>');
                        $('#status_button_' + id).attr({
                            'class': 'btn btn-success'
                        });
                        $('#status_button_' + id).html('<i class="icon-edit icon-white"></i> Enable');
                        $('#status_button_' + id).attr('onclick', 'App.changePaypalStatus(' + id + ', 1);');
                    }
                }
            }
        });
    },
    addPaypalPackage: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/add_paypal_package",
            data: {
                title: $("#title_new").val(),
                price: $("#price_new").val(),
                currency: $("#currency_new").val(),
                reward: $("#reward_new").val(),
                server: $("#server_new").val()
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    var html = '<tr id="' + data.id + '"><td><input class="input-medium" type="text" id="pack_title_' + data.id + '" value="' + $("#title_new").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_price_' + data.id + '" value="' + $("#price_new").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_currency_' + data.id + '" value="' + $("#currency_new").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_reward_' + data.id + '" value="' + $("#reward_new").val() + '" /></td>';
                    html += '<td class="center"><select id="pack_server_' + data.id + '" class="input-medium">';
                    $.each(data.servers, function(key, val) {
                        var selected = (key == data.server) ? 'selected="selected"' : '';
                        html += '<option value="' + key + '" ' + selected + '>' + val.title + '</option>';
                    });
                    html += '</select></td>';
                    html += '<td class="center" id="status_icon_' + data.id + '"><span class="label label-success">Active</span></td><td class="center"><a class="btn btn-danger" href="#" id="status_button_' + data.id + '" onclick="App.changePaypalStatus(' + data.id + ', 0);"><i class="icon-edit icon-white"></i> Disable</a>  <a class="btn btn-info" href="#" onclick="App.editPaypal(' + data.id + ');"><i class="icon-edit icon-white"></i> Edit</a>  <a class="btn btn-danger" href="#" onclick="App.deletePaypal(' + data.id + ');"><i class="icon-trash icon-white"></i> Delete</a></td></tr>';
                    $('#donate_sortable > tbody:last').append(html);
                }
            }
        });
    },
    save2CheckOutOrder: function() {
        var order = $('#donate_sortable_content_checkout').sortable('toArray');
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_twocheckout_order",
            data: {
                order: order
            },
            success: function(data) {}

        });
    },
    edit2CheckOut: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/edit_twocheckout_package",
            data: {
                id: id,
                title: $("#pack_title_2checkout_" + id).val(),
                price: $("#pack_price_2checkout_" + id).val(),
                currency: $("#pack_currency_2checkout_" + id).val(),
                reward: $("#pack_reward_2checkout_" + id).val(),
                server: $("#pack_server_2checkout_" + id).val()
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    delete2CheckOut: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/delete_twocheckout_package",
            data: {
                id: id
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    $('tr#2checkout_' + id).hide();
                }
            }
        });
    },
    change2CheckOutStatus: function(id, status) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_twocheckout_status",
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    if (status == 1) {
                        $('#status_icon_2checkout_' + id).html('<span class="label label-success">Active</span>');
                        $('#status_button_2checkout_' + id).html('<i class="icon-edit icon-white"></i> Disable');
                        $('#status_button_2checkout_' + id).attr('onclick', 'App.change2CheckOutStatus(' + id + ', 0);');
                        $('#status_button_2checkout_' + id).attr({
                            'class': 'btn btn-danger'
                        });
                    } else {
                        $('#status_icon_2checkout_' + id).html('<span class="label label-important">Inactive</span>');
                        $('#status_button_2checkout_' + id).html('<i class="icon-edit icon-white"></i> Enable');
                        $('#status_button_2checkout_' + id).attr('onclick', 'App.change2CheckOutStatus(' + id + ', 1);');
                        $('#status_button_2checkout_' + id).attr({
                            'class': 'btn btn-success'
                        });
                    }
                }
            }
        });
    },
    add2CheckOutPackage: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/add_twocheckout_package",
            data: {
                title: $("#title_new_2checkout").val(),
                price: $("#price_new_2checkout").val(),
                currency: $("#currency_new_2checkout").val(),
                reward: $("#reward_new_2checkout").val(),
                server: $("#server_new_2checkout").val()
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    var html = '<tr id="2checkout_' + data.id + '"><td><input class="input-medium" type="text" id="pack_title_' + data.id + '" value="' + $("#title_new_2checkout").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_price_' + data.id + '" value="' + $("#price_new_2checkout").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_currency_' + data.id + '" value="' + $("#currency_new_2checkout").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_reward_' + data.id + '" value="' + $("#reward_new_2checkout").val() + '" /></td>';
                    html += '<td class="center"><select id="pack_server_' + data.id + '" class="input-medium">';
                    $.each(data.servers, function(key, val) {
                        var selected = (key == data.server) ? 'selected="selected"' : '';
                        html += '<option value="' + key + '" ' + selected + '>' + val.title + '</option>';
                    });
                    html += '</select></td>';
                    html += '<td class="center" id="status_icon_2checkout_' + data.id + '"><span class="label label-success">Active</span></td><td class="center"><a class="btn btn-danger" href="#" id="status_button_2checkout_' + data.id + '" onclick="App.change2CheckOutStatus(' + data.id + ', 0);"><i class="icon-edit icon-white"></i> Disable</a>  <a class="btn btn-info" href="#" onclick="App.edit2CheckOut(' + data.id + ');"><i class="icon-edit icon-white"></i> Edit</a>  <a class="btn btn-danger" href="#" onclick="App.delete2CheckOut(' + data.id + ');"><i class="icon-trash icon-white"></i> Delete</a></td></tr>';
                    $('#donate_sortable_checkout > tbody:last').append(html);
                }
            }
        });
    },
    savePagSeguroOrder: function() {
        var order = $('#donate_sortable_content_pagseguro').sortable('toArray');
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_pagseguro_order",
            data: {
                order: order
            },
            success: function(data) {}

        });
    },
    editPagSeguro: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/edit_pagseguro_package",
            data: {
                id: id,
                title: $("#pack_title_pagseguro_" + id).val(),
                price: $("#pack_price_pagseguro_" + id).val(),
                currency: $("#pack_currency_pagseguro_" + id).val(),
                reward: $("#pack_reward_pagseguro_" + id).val(),
                server: $("#pack_server_pagseguro_" + id).val()
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    deletePagSeguro: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/delete_pagseguro_package",
            data: {
                id: id
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    $('tr#pagseguro_' + id).hide();
                }
            }
        });
    },
    changePagSeguroStatus: function(id, status) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_pagseguro_status",
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    if (status == 1) {
                        $('#status_icon_pagseguro_' + id).html('<span class="label label-success">Active</span>');
                        $('#status_button_pagseguro_' + id).html('<i class="icon-edit icon-white"></i> Disable');
                        $('#status_button_pagseguro_' + id).attr('onclick', 'App.changePagSeguroStatus(' + id + ', 0);');
                        $('#status_button_pagseguro_' + id).attr({
                            'class': 'btn btn-danger'
                        });
                    } else {
                        $('#status_icon_pagseguro_' + id).html('<span class="label label-important">Inactive</span>');
                        $('#status_button_pagseguro_' + id).html('<i class="icon-edit icon-white"></i> Enable');
                        $('#status_button_pagseguro_' + id).attr('onclick', 'App.changePagSeguroStatus(' + id + ', 1);');
                        $('#status_button_pagseguro_' + id).attr({
                            'class': 'btn btn-success'
                        });
                    }
                }
            }
        });
    },
    addPagSeguroPackage: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/add_pagseguro_package",
            data: {
                title: $("#title_new_pagseguro").val(),
                price: $("#price_new_pagseguro").val(),
                currency: $("#currency_new_pagseguro").val(),
                reward: $("#reward_new_pagseguro").val(),
                server: $("#server_new_pagseguro").val()
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    var html = '<tr id="pagseguro_' + data.id + '"><td><input class="input-medium" type="text" id="pack_title_pagseguro_' + data.id + '" value="' + $("#title_new_pagseguro").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_price_pagseguro_' + data.id + '" value="' + $("#price_new_pagseguro").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_currency_pagseguro_' + data.id + '" value="' + $("#currency_new_pagseguro").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_reward_pagseguro_' + data.id + '" value="' + $("#reward_new_pagseguro").val() + '" /></td>';
                    html += '<td class="center"><select id="pack_server_pagseguro_' + data.id + '" class="input-medium">';
                    $.each(data.servers, function(key, val) {
                        var selected = (key == data.server) ? 'selected="selected"' : '';
                        html += '<option value="' + key + '" ' + selected + '>' + val.title + '</option>';
                    });
                    html += '</select></td>';
                    html += '<td class="center" id="status_icon_pagseguro_' + data.id + '"><span class="label label-success">Active</span></td><td class="center"><a class="btn btn-danger" href="#" id="status_button_pagseguro_' + data.id + '" onclick="App.changePagSeguroStatus(' + data.id + ', 0);"><i class="icon-edit icon-white"></i> Disable</a>  <a class="btn btn-info" href="#" onclick="App.editPagSeguro(' + data.id + ');"><i class="icon-edit icon-white"></i> Edit</a>  <a class="btn btn-danger" href="#" onclick="App.deletePagSeguro(' + data.id + ');"><i class="icon-trash icon-white"></i> Delete</a></td></tr>';
                    $('#donate_sortable_pagseguro > tbody:last').append(html);
                }
            }
        });
    },
    saveInterkassaOrder: function() {
        var order = $('#donate_sortable_interkassa_content').sortable('toArray');
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_interkassa_order",
            data: {
                order: order
            },
            success: function(data) {}

        });
    },
    editInterkassa: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/edit_interkassa_package",
            data: {
                id: id,
                title: $("#pack_title_interkassa_" + id).val(),
                price: $("#pack_price_interkassa_" + id).val(),
                currency: $("#pack_currency_interkassa_" + id).val(),
                reward: $("#pack_reward_interkassa_" + id).val(),
                server: $("#pack_server_interkassa_" + id).val()
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    deleteInterkassa: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/delete_interkassa_package",
            data: {
                id: id
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    $('tr#interkassa_' + id).hide();
                }
            }
        });
    },
    changeInterkassaStatus: function(id, status) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_interkassa_status",
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    if (status == 1) {
                        $('#status_icon_interkassa_' + id).html('<span class="label label-success">Active</span>');
                        $('#status_button_interkassa_' + id).html('<i class="icon-edit icon-white"></i> Disable');
                        $('#status_button_interkassa_' + id).attr('onclick', 'App.changeInterkassaStatus(' + id + ', 0);');
                        $('#status_button_interkassa_' + id).attr({
                            'class': 'btn btn-danger'
                        });
                    } else {
                        $('#status_icon_interkassa_' + id).html('<span class="label label-important">Inactive</span>');
                        $('#status_button_interkassa_' + id).html('<i class="icon-edit icon-white"></i> Enable');
                        $('#status_button_interkassa_' + id).attr('onclick', 'App.changeInterkassaStatus(' + id + ', 1);');
                        $('#status_button_interkassa_' + id).attr({
                            'class': 'btn btn-success'
                        });
                    }
                }
            }
        });
    },
    addInterkassaPackage: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/add_interkassa_package",
            data: {
                title: $("#title_new_interkassa").val(),
                price: $("#price_new_interkassa").val(),
                currency: $("#currency_new_interkassa").val(),
                reward: $("#reward_new_interkassa").val(),
                server: $("#server_new_interkassa").val()
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    var html = '<tr id="interkassa_' + data.id + '"><td><input class="input-medium" type="text" id="pack_title_interkassa_' + data.id + '" value="' + $("#title_new_interkassa").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_price_interkassa_' + data.id + '" value="' + $("#price_new_interkassa").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_currency_interkassa_' + data.id + '" value="' + $("#currency_new_interkassa").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_reward_interkassa_' + data.id + '" value="' + $("#reward_new_interkassa").val() + '" /></td>';
                    html += '<td class="center"><select id="pack_server_interkassa_' + data.id + '" class="input-medium">';
                    $.each(data.servers, function(key, val) {
                        var selected = (key == data.server) ? 'selected="selected"' : '';
                        html += '<option value="' + key + '" ' + selected + '>' + val.title + '</option>';
                    });
                    html += '</select></td>';
                    html += '<td class="center" id="status_icon_interkassa_' + data.id + '"><span class="label label-success">Active</span></td><td class="center"><a class="btn btn-danger" href="#" id="status_button_interkassa_' + data.id + '" onclick="App.changeInterkassaStatus(' + data.id + ', 0);"><i class="icon-edit icon-white"></i> Disable</a>  <a class="btn btn-info" href="#" onclick="App.editInterkassa(' + data.id + ');"><i class="icon-edit icon-white"></i> Edit</a>  <a class="btn btn-danger" href="#" onclick="App.deleteInterkassa(' + data.id + ');"><i class="icon-trash icon-white"></i> Delete</a></td></tr>';
                    $('#donate_sortable_interkassa > tbody:last').append(html);
                }
            }
        });
    },
    saveCuentaDigitalOrder: function() {
        var order = $('#donate_sortable_cuenta_digital_content').sortable('toArray');
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_cuenta_digital_order",
            data: {
                order: order
            },
            success: function(data) {}

        });
    },
    editCuentaDigital: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/edit_cuenta_digital_package",
            data: {
                id: id,
                title: $("#pack_title_cuenta_digital_" + id).val(),
                price: $("#pack_price_cuenta_digital_" + id).val(),
                currency: $("#pack_currency_cuenta_digital_" + id).val(),
                reward: $("#pack_reward_cuenta_digital_" + id).val(),
                server: $("#pack_server_cuenta_digital_" + id).val()
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    deleteCuentaDigital: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/delete_cuenta_digital_package",
            data: {
                id: id
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    $('tr#cuenta_digital_' + id).hide();
                }
            }
        });
    },
    changeCuentaDigitalStatus: function(id, status) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_cuenta_digital_status",
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    if (status == 1) {
                        $('#status_icon_cuenta_digital_' + id).html('<span class="label label-success">Active</span>');
                        $('#status_button_cuenta_digital_' + id).html('<i class="icon-edit icon-white"></i> Disable');
                        $('#status_button_cuenta_digital_' + id).attr('onclick', 'App.changeCuentaDigitalStatus(' + id + ', 0);');
                        $('#status_button_cuenta_digital_' + id).attr({
                            'class': 'btn btn-danger'
                        });
                    } else {
                        $('#status_icon_cuenta_digital_' + id).html('<span class="label label-important">Inactive</span>');
                        $('#status_button_cuenta_digital_' + id).html('<i class="icon-edit icon-white"></i> Enable');
                        $('#status_button_cuenta_digital_' + id).attr('onclick', 'App.changeCuentaDigitalStatus(' + id + ', 1);');
                        $('#status_button_cuenta_digital_' + id).attr({
                            'class': 'btn btn-success'
                        });
                    }
                }
            }
        });
    },
    addCuentaDigitalPackage: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/add_cuenta_digital_package",
            data: {
                title: $("#title_new_cuenta_digital").val(),
                price: $("#price_new_cuenta_digital").val(),
                currency: $("#currency_new_cuenta_digital").val(),
                reward: $("#reward_new_cuenta_digital").val(),
                server: $("#server_new_cuenta_digital").val()
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    var html = '<tr id="cuenta_digital_' + data.id + '"><td><input class="input-medium" type="text" id="pack_title_cuenta_digital_' + data.id + '" value="' + $("#title_new_cuenta_digital").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_price_cuenta_digital_' + data.id + '" value="' + $("#price_new_cuenta_digital").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_currency_cuenta_digital_' + data.id + '" value="' + $("#currency_new_cuenta_digital").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_reward_cuenta_digital_' + data.id + '" value="' + $("#reward_new_cuenta_digital").val() + '" /></td>';
                    html += '<td class="center"><select id="pack_server_cuenta_digital_' + data.id + '" class="input-medium">';
                    $.each(data.servers, function(key, val) {
                        var selected = (key == data.server) ? 'selected="selected"' : '';
                        html += '<option value="' + key + '" ' + selected + '>' + val.title + '</option>';
                    });
                    html += '</select></td>';
                    html += '<td class="center" id="status_icon_cuenta_digital_' + data.id + '"><span class="label label-success">Active</span></td><td class="center"><a class="btn btn-danger" href="#" id="status_button_cuenta_digital_' + data.id + '" onclick="App.changeCuentaDigitalStatus(' + data.id + ', 0);"><i class="icon-edit icon-white"></i> Disable</a>  <a class="btn btn-info" href="#" onclick="App.editCuentaDigital(' + data.id + ');"><i class="icon-edit icon-white"></i> Edit</a>  <a class="btn btn-danger" href="#" onclick="App.deleteCuentaDigital(' + data.id + ');"><i class="icon-trash icon-white"></i> Delete</a></td></tr>';
                    $('#donate_sortable_cuenta_digital > tbody:last').append(html);
                }
            }
        });
    },
    savePaycallOrder: function() {
        var order = $('#donate_sortable_paycall_content').sortable('toArray');
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_paycall_order",
            data: {
                order: order
            },
            success: function(data) {}

        });
    },
    editPaycall: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/edit_paycall_package",
            data: {
                id: id,
                title: $("#pack_title_paycall_" + id).val(),
                price: $("#pack_price_paycall_" + id).val(),
                reward: $("#pack_reward_paycall_" + id).val(),
                server: $("#pack_server_paycall_" + id).val()
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    deletePaycall: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/delete_paycall_package",
            data: {
                id: id
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    $('tr#paycall_' + id).hide();
                }
            }
        });
    },
    changePaycallStatus: function(id, status) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_paycall_status",
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    if (status == 1) {
                        $('#status_icon_paycall_' + id).html('<span class="label label-success">Active</span>');
                        $('#status_button_paycall_' + id).html('<i class="icon-edit icon-white"></i> Disable');
                        $('#status_button_paycall_' + id).attr('onclick', 'App.changePaycallStatus(' + id + ', 0);');
                        $('#status_button_paycall_' + id).attr({
                            'class': 'btn btn-danger'
                        });
                    } else {
                        $('#status_icon_paycall_' + id).html('<span class="label label-important">Inactive</span>');
                        $('#status_button_paycall_' + id).html('<i class="icon-edit icon-white"></i> Enable');
                        $('#status_button_paycall_' + id).attr('onclick', 'App.changePaycallStatus(' + id + ', 1);');
                        $('#status_button_paycall_' + id).attr({
                            'class': 'btn btn-success'
                        });
                    }
                }
            }
        });
    },
    addPaycallPackage: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/add_paycall_package",
            data: {
                title: $("#title_new_paycall").val(),
                price: $("#price_new_paycall").val(),
                reward: $("#reward_new_paycall").val(),
                server: $("#server_new_paycall").val()
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    var html = '<tr id="paycall_' + data.id + '"><td><input class="input-medium" type="text" id="pack_title_paycall_' + data.id + '" value="' + $("#title_new_paycall").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_price_paycall_' + data.id + '" value="' + $("#price_new_paycall").val() + '" /></td>';
                    html += '<td class="center"><input class="input-small" type="text" id="pack_reward_paycall_' + data.id + '" value="' + $("#reward_new_paycall").val() + '" /></td>';
                    html += '<td class="center"><select id="pack_server_paycall_' + data.id + '" class="input-medium">';
                    $.each(data.servers, function(key, val) {
                        var selected = (key == data.server) ? 'selected="selected"' : '';
                        html += '<option value="' + key + '" ' + selected + '>' + val.title + '</option>';
                    });
                    html += '</select></td>';
                    html += '<td class="center" id="status_icon_paycall_' + data.id + '"><span class="label label-success">Active</span></td><td class="center"><a class="btn btn-danger" href="#" id="status_button_paycall_' + data.id + '" onclick="App.changePaycallStatus(' + data.id + ', 0);"><i class="icon-edit icon-white"></i> Disable</a>  <a class="btn btn-info" href="#" onclick="App.editPaycall(' + data.id + ');"><i class="icon-edit icon-white"></i> Edit</a>  <a class="btn btn-danger" href="#" onclick="App.deletePaycall(' + data.id + ');"><i class="icon-trash icon-white"></i> Delete</a></td></tr>';
                    $('#donate_sortable_paycall > tbody:last').append(html);
                }
            }
        });
    },
    saveSocketOrder: function() {
        var order = $('#socket_sortable_content').sortable('toArray');
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_socket_order",
            data: {
                order: order
            },
            success: function(data) {}

        });
    },
    changeSocketStatus: function(id, status) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_socket_status",
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    if (status == 1) {
                        $('#status_icon_' + id).html('<span class="label label-success">Active</span>');
                        $('#status').html('<i class="icon-edit icon-white"></i> Disable');
                        $('#status').attr('onclick', 'App.changeSocketStatus(' + id + ', 0);');
                    } else {
                        $('#status_icon_' + id).html('<span class="label label-important">Inactive</span>');
                        $('#status').html('<i class="icon-edit icon-white"></i> Enable');
                        $('#status').attr('onclick', 'App.changeSocketStatus(' + id + ', 1);');
                    }
                }
            }
        });
    },
    editSocket: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/edit_socket_package",
            data: {
                id: id,
                name: $("#socketname_" + id).val(),
                price: $("#socketprice_" + id).val(),
                part_type: $("#socketpart_" + id).val()
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    changeHarmonyStatus: function(id, status) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_harmony_status",
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    if (status == 1) {
                        $('#status_icon_' + id).html('<span class="label label-success">Active</span>');
                        $('#status').html('<i class="icon-edit icon-white"></i> Disable');
                        $('#status').attr('onclick', 'App.changeHarmonyStatus(' + id + ', 0);');
                    } else {
                        $('#status_icon_' + id).html('<span class="label label-important">Inactive</span>');
                        $('#status').html('<i class="icon-edit icon-white"></i> Enable');
                        $('#status').attr('onclick', 'App.changeHarmonyStatus(' + id + ', 1);');
                    }
                }
            }
        });
    },
    editHarmony: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/edit_harmony_package",
            data: {
                id: id,
                name: $("#harmonyn_" + id).val(),
                price: $("#harmonyp_" + id).val()
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    deleteItem: function() {
        if (App.confirmMessage('Are you sure you want to delete this item?')) {
            $.ajax({
                type: "post",
                dataType: "json",
                url: DmNConfig.base_url + 'admincp/del_item',
                data: {
                    'ajax': 1,
                    'slot': App.item_slot
                },
                success: function(data) {
                    if (data.error) {
                        if ($.isArray(data.error)) {
                            $.each(data.error, function(key, val) {
                                noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                            });
                        } else {
                            noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                        }
                    } else {
                        noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                        $('#item-slot-' + App.item_slot).hide();
                        $('div[id^="item-slot-"]').each(function() {
                            App.initializeTooltip($(this), true, 'warehouse/item_info');
                        });
                    }
                }
            });
        }
    },
    loadItemList: function(cat) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + 'admincp/load_items',
            data: {
                'ajax': 1,
                'cat': cat
            },
            success: function(data) {
                if (data.error) {
                    if ($.isArray(data.error)) {
                        $.each(data.error, function(key, val) {
                            noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                        });
                    } else {
                        noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                    }
                    if ($('#item_options').is(':visible')) {
                        $('#item_options').hide();
                    }
                } else {
                    var html = '';
                    if ($.isArray(data.items)) {
                        $.each(data.items, function(key, val) {
                            html += '<option value="' + val.id + '">' + val.name + '</option>';
                        });
                        $('#items_wh').html(html);
                        if ($('#item_options').is(':hidden')) {
                            $('#item_options').show();
                        }
                        if ($('#socket_opts').is(':visible')) {
                            $('#socket_opts').hide();
                        }
                        App.resetHarmony();
                    }
                }
            }
        });
    },
    checkItem: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + 'admincp/check_item',
            data: {
                'ajax': 1,
                'id': id
            },
            success: function(data) {
                if (data.error) {
                    if ($.isArray(data.error)) {
                        $.each(data.error, function(key, val) {
                            noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                        });
                    } else {
                        noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                    }
                } else {
                    if (data.sockets != false) {
                        var html = '';
                        if ($.isArray(data.sockets)) {
                            $.each(data.sockets, function(key, val) {
                                html += '<option value="' + val.seed + '-' + val.socket_id + '">' + val.socket_name + '</option>';
                            });
                            $('#socket1').html(html);
                            $('#socket2').html(html);
                            $('#socket3').html(html);
                            $('#socket4').html(html);
                            $('#socket5').html(html);
                            if ($('#socket_opts').is(':hidden')) {
                                $('#socket_opts').show();
                            }
                        }
                    } else {
                        if ($('#socket_opts').is(':visible')) {
                            $('#socket_opts').hide();
                        }
                    }
                }
            }
        });
    },
    resetItemList: function() {
        $('#items_wh').html('<option value="">None</option>');
        if ($('#item_options').is(':visible')) {
            $('#item_options').hide();
        }
    },
    checkHarmony: function() {
        if (!isNaN($('#items_harm option:selected').val()) && $('#items_harm option:selected').val() != '') {
            if (!isNaN($('#category_wh').val()) && $('#category_wh').val() != '') {
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: DmNConfig.base_url + 'admincp/loadharmonylist',
                    data: {
                        ajax: 1,
                        cat: $('#category_wh').val(),
                        hopt: $('#items_harm').val()
                    },
                    success: function(data) {
                        if (typeof data.harmonylist != 'undefined') {
                            var html = '<option value="-1" selected="selected">--select--</option>';
                            $.each(data.harmonylist, function(key, val) {
                                html += '<option value="' + val.hvalue + '">' + val.hname + '</option>';
                            });
                            $('#harmonyvalue').html(html);
                            $('#harmonyoption').show();
                        } else {
                            if (data.error) {
                                noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                            } else {
                                noty($.parseJSON('{"text":"No harmony data found","layout":"topRight","type":"error"}'));
                            }
                            $('#harmonyoption').hide();

                        }
                    }
                });
            } else {
                noty($.parseJSON('{"text":"Please select category first.","layout":"topRight","type":"error"}'));
            }
        } else {
            $('#harmonyoption').hide();
        }
    },
    resetHarmony: function() {
        $('#harmonyvalue').html('<option value="">None</option>');
        $('#harmonyoption').hide();
        $('#items_harm').prop('selectedIndex', 0);
    },
    sentItem: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + 'admincp/add_wh_item',
            data: $('#item_form').serialize() + '&' + $.param({
                'ajax': 1
            }),
            beforeSend: function() {
                $(document.body).css({
                    'cursor': 'wait'
                })
            },
            complete: function() {
                $(document.body).css({
                    'cursor': 'default'
                })
            },
            success: function(data) {
                if (data.error) {
                    if ($.isArray(data.error)) {
                        $.each(data.error, function(key, val) {
                            noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                        });
                    } else {
                        noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                    }
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    $('#item-slot-' + data.slot).replaceWith(data.div);
                    App.initializeTooltip($('#item-slot-' + data.slot), true, 'warehouse/item_info');
                }
            }
        });
    },
    saveServerOrder: function() {
        var order = $('#serverlist_sortable_content').sortable('toArray');
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_server_order",
            data: {
                order: order
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                }
            }
        });
    },
	saveEventOrder: function() {
        var order = $('#event_sortable_content').sortable('toArray');
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_event_order",
            data: {
                order: order
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                }
            }
        });
    },
    changeServerStatus: function(id, status) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_server_status",
            data: {
                id: id,
                status: status
            },
            success: function(data) {

                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    if (status == 1) {
                        $('#status_icon_' + id).html('<span class="label label-success">Active</span>');
                        $('#status_button_' + id).attr({
                            'class': 'btn btn-danger'
                        });
                        $('#status_button_' + id).html('<i class="icon-edit icon-white"></i> Disable');
                        $('#status_button_' + id).attr('onclick', 'App.changeServerStatus("' + id + '", 0);');
                    } else {
                        $('#status_icon_' + id).html('<span class="label label-important">Inactive</span>');
                        $('#status_button_' + id).attr({
                            'class': 'btn btn-success'
                        });
                        $('#status_button_' + id).html('<i class="icon-edit icon-white"></i> Enable');
                        $('#status_button_' + id).attr('onclick', 'App.changeServerStatus("' + id + '", 1);');
                    }
                }
            }
        });
    },
    deleteServer: function(id) {
        if (App.confirmMessage('Are you sure you want to delete this server?')) {
            $.ajax({
                type: "post",
                dataType: "json",
                url: DmNConfig.base_url + "admincp/delete_server",
                data: {
                    id: id
                },
                success: function(data) {
                    if (data.error) {
                        noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                    } else {
                        noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                        $('#' + id).hide();
                    }
                }
            });
        }
    },
    setUseMultiAccountDB: function(status) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_multi_account_db",
            data: {
                status: status
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    changeBuyLevelStatus: function(status, server) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_buylevel_status",
            data: {
                status: status,
                server: server
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    deleteBuyLevelSettings: function(key, server) {
        var rowCount = $('#buylevel-settings-' + server + ' tr').length;
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/delete_buylevel_settings",
            data: {
                key: key,
                server: server
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    $('#level-' + key + '-' + server).remove();
                    if (rowCount == 1) {
                        $('#buylevel-settings-' + server).append('<tr><td colspan="3"><div class="alert alert-info">No settings for this server.</div></td></tr>');
                    }
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    saveMaxLevel: function(server, data) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/buylevel_save_max_level",
            data: '&' + $.param({
                'server': server
            }) + '&' + data,
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    changeResetStatus: function(status, server) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_reset_status",
            data: {
                status: status,
                server: server
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    deleteResSettings: function(key, server) {
        var rowCount = $('#reset-settings-' + server + ' tr').length;
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/delete_reset_settings",
            data: {
                key: key,
                server: server
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    $('#reset-' + key + '-' + server).remove();
                    if (rowCount == 1) {
                        $('#reset-settings-' + server).append('<tr><td colspan="3"><div class="alert alert-info">No settings for this server.</div></td></tr>');
                    }
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    changeGResetStatus: function(status, server) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_greset_status",
            data: {
                status: status,
                server: server
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    deleteGResSettings: function(key, server) {
        var rowCount = $('#greset-settings-' + server + ' tr').length;
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/delete_greset_settings",
            data: {
                key: key,
                server: server
            },
            success: function(data) {
                if (data.error) {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    $('#greset-' + key + '-' + server).remove();
                    if (rowCount == 1) {
                        $('#greset-settings-' + server).append('<tr><td colspan="3"><div class="alert alert-info">No settings for this server.</div></td></tr>');
                    }
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },




    confirmMessage: function(message) {
        var conf = confirm(message);
        return (conf == true);




    },
    changeLanguageStatus: function(id, status) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/change_language_status",
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                if (typeof data.error != "undefined") {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    if (status == 1) {
                        $('#lang_status_icon_' + id).html('<span class="label label-success">Active</span>');
                        $('#status_' + id).html('<i class="icon-edit icon-black"></i> Disable');
                        $('#status_' + id).attr('onclick', 'App.changeLanguageStatus(\'' + id + '\', 0);');
                        $('#status_' + id).attr('class', 'btn btn-danger');
                    } else {
                        $('#lang_status_icon_' + id).html('<span class="label label-important">Inactive</span>');
                        $('#status_' + id).html('<i class="icon-edit icon-black"></i> Enable');
                        $('#status_' + id).attr('onclick', 'App.changeLanguageStatus(\'' + id + '\', 1);');
                        $('#status_' + id).attr('class', 'btn btn-success');
                    }
                }
            }
        });
    },
    deleteLanguage: function(id) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/delete_language",
            data: {
                id: id
            },
            success: function(data) {
                if (typeof data.error != "undefined") {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                    $('#language_' + id).hide();
                }
            }
        });
    },
    checkNewsStorage: function() {
        var storage = $('#storage').val();
        if (storage == 'ipb' || storage == 'ipb4') {
            $('#ipb_settings').show();
        } else {
            $('#ipb_settings').hide();
        }
        if (storage == 'rss') {
            $('#rss_settings').show();
        } else {
            $('#rss_settings').hide();
        }
        if (storage == 'facebook') {
            $('#fb_settings').show();
            $('#per_page').hide();
            $('#news_cache').hide();
        } else {
            $('#fb_settings').hide();
            $('#per_page').show();
            $('#news_cache').show();
        }
    },
    showHideTimes: function(selected_days) {
        if (typeof selected_days[1] != "undefined") {
            $('#timers_monday').show();
        } else {
            $('#timers_monday').hide();
        }
        if (typeof selected_days[2] != "undefined") {
            $('#timers_tuesday').show();
        } else {
            $('#timers_tuesday').hide();
        }
        if (typeof selected_days[3] != "undefined") {
            $('#timers_wednesday').show();
        } else {
            $('#timers_wednesday').hide();
        }
        if (typeof selected_days[4] != "undefined") {
            $('#timers_thursday').show();
        } else {
            $('#timers_thursday').hide();
        }
        if (typeof selected_days[5] != "undefined") {
            $('#timers_friday').show();
        } else {
            $('#timers_friday').hide();
        }
        if (typeof selected_days[6] != "undefined") {
            $('#timers_saturday').show();
        } else {
            $('#timers_saturday').hide();
        }
        if (typeof selected_days[7] != "undefined") {
            $('#timers_sunday').show();
        } else {
            $('#timers_sunday').hide();
        }
        if (typeof selected_days[0] != "undefined") {
            $('#all_timers').show();
            $('#timers_monday').hide();
            $('#timers_tuesday').hide();
            $('#timers_wednesday').hide();
            $('#timers_thursday').hide();
            $('#timers_friday').hide();
            $('#timers_saturday').hide();
            $('#timers_sunday').hide();
        } else {
            $('#all_timers').hide();
        }
    },
    saveRankingsStatus: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/save_rankings_status",
            data: {
                server: $('#server').val(),
                status: $('#active').val()
            },
            success: function(data) {
                if (typeof data.error != "undefined") {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                }
            }
        });
    },
    reloadRankingsStatus: function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: DmNConfig.base_url + "admincp/reload_rankings_status",
            data: {
                server: $('#server').val()
            },
            success: function(data) {
                if (typeof data.error != "undefined") {
                    noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                } else {
                    if (data.status == 1) {
                        $('#ranking_settings_form').find('#active option[value=1]').prop("selected", true);
                    } else {
                        $('#ranking_settings_form').find('#active option[value=0]').prop("selected", true);
                    }
                }
            }
        });
    }
};


function docReady() {
    $('div[id^="item-slot-"]').on('mousedown', function(e) {
        App.item_slot = $(this).attr('id').split('-')[2];
        switch (e.which) {
            case 3:
                App.deleteItem();
                break;
        }
    });

    $('#category_wh').on('change', function(e) {
        e.preventDefault();
        if ($(this).val() != '') {
            App.loadItemList($(this).val());
            if ($(this).val() < 5) {
                $('#Weapons').prop('disabled', false);
                $('#Staffs').prop('disabled', true);
                $('#Sets').prop('disabled', true);
            } else if ($(this).val() == 5) {
                $('#Weapons').prop('disabled', true);
                $('#Staffs').prop('disabled', false);
                $('#Sets').prop('disabled', true);
            } else {
                $('#Weapons').prop('disabled', true);
                $('#Staffs').prop('disabled', true);
                $('#Sets').prop('disabled', false);
            }
        }
        if ($(this).val() == '') {
            App.resetItemList();
            App.resetHarmony();
        }

    });

    $('#items_wh').on('change', function(e) {
        e.preventDefault();
        if ($(this).val() != '') {
            App.checkItem($(this).val());
        }
    });

    $('#items_exe_type').on('change', function() {
        if ($(this).val() == '') {
            $('input:checkbox').attr('checked', false);
            $('div[id^="exe-"]').hide();
        } else {
            $('input:checkbox').attr('checked', false);
            $('div[id^="exe-"]').hide();
            $('#exe-' + $(this).val()).show();
        }
    });

    $('#items_harm').on('change', function() {
        App.checkHarmony();
    });

    $('#item_form').on('submit', function(e) {
        e.preventDefault();
        App.sentItem();
    });

    $('a[href="#"][data-top!=true]').click(function(e) {
        e.preventDefault();
    });

    $('span[id^="log_item_"]').each(function() {
        App.initializeTooltip($(this), true, 'warehouse/item_info_image');
    });

    $('div[id^="item-slot-"]').each(function() {
        App.initializeTooltip($(this), true, 'warehouse/item_info');
    });

    $('.datepicker').datepicker();
	$('.datepicker_account_filter').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('.datetimepicker').datetimepicker({
        dateFormat: "yy/mm/dd",
        timeFormat: "HH:mm"
    });

    $('.noty').click(function(e) {
        e.preventDefault();
        var options = $.parseJSON($(this).attr('data-noty-options'));
        noty(options);
    });

    $("input:checkbox, input:radio, input:file").not('[data-no-uniform="true"],#uniform-is-ajax').uniform();

    $('[data-rel="chosen"],[rel="chosen"]').chosen();

    $('.sortable').sortable({
        revert: true,
        cancel: '.btn,.box-content,.nav-header',
        update: function(event, ui) {}
    });

    $('.slider').slider({
        range: true,
        values: [10, 65]
    });

    $('[rel="tooltip"],[data-rel="tooltip"]').tooltip({
        "placement": "bottom",
        delay: {
            show: 400,
            hide: 200
        }
    });

    $('textarea.autogrow').autogrow();

    $('[rel="popover"],[data-rel="popover"]').popover();

    $('.iphone-toggle').iphoneStyle();

    $('.raty').raty({
        score: 4
    });

    $('ul.gallery li').hover(function() {
        $('img', this).fadeToggle(1000);
        $(this).find('.gallery-controls').remove();
        $(this).append('<div class="well gallery-controls">' +
            '<p><a href="#" class="gallery-delete btn"><i class="icon-remove"></i></a></p>' +
            '</div>');
        $(this).find('.gallery-controls').stop().animate({
            'margin-top': '-1'
        }, 400, 'easeInQuint');
    }, function() {
        $('img', this).fadeToggle(1000);
        $(this).find('.gallery-controls').stop().animate({
            'margin-top': '-30'
        }, 200, 'easeInQuint', function() {
            $(this).remove();
        });
    });

    $('.thumbnails').on('click', '.gallery-delete', function(e) {
        e.preventDefault();
        var that = $(this);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: {
                id: that.parent().parent().parent().attr('id').split("-")[1]
            },
            url: DmNConfig.base_url + 'admincp/delete_image',
            success: function(data) {
                if (data.error) {
                    alert(data.error);
                } else {
                    that.parents('.thumbnail').fadeOut();
                }
            }
        });

    });


    $('.thumbnails').on('click', '.gallery-edit', function(e) {
        e.preventDefault();
    });

    $('.thumbnail a').colorbox({
        rel: 'thumbnail a',
        transition: "elastic",
        maxWidth: "95%",
        maxHeight: "95%"
    });

    $('.btn-close').click(function(e) {
        e.preventDefault();
        $(this).parent().parent().parent().fadeOut();
    });
    $('.btn-minimize').click(function(e) {
        e.preventDefault();
        var $target = $(this).parent().parent().next('.box-content');
        if ($target.is(':visible')) $('i', $(this)).removeClass('icon-chevron-up').addClass('icon-chevron-down');
        else $('i', $(this)).removeClass('icon-chevron-down').addClass('icon-chevron-up');
        $target.slideToggle();
    });
	
}

function ask_url(ask, url, target) {
    var detStatus = confirm(ask);
    if (detStatus) {
        if (target) {
            location.href = url;
        } else {
            top.location = url;


        }

    } else {
        return false;
    }
}


$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
    return {
        "iStart": oSettings._iDisplayStart,
        "iEnd": oSettings.fnDisplayEnd(),
        "iLength": oSettings._iDisplayLength,
        "iTotal": oSettings.fnRecordsTotal(),
        "iFilteredTotal": oSettings.fnRecordsDisplay(),
        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
    };
}
$.extend($.fn.dataTableExt.oPagination, {
    "bootstrap": {
        "fnInit": function(oSettings, nPaging, fnDraw) {
            var oLang = oSettings.oLanguage.oPaginate;
            var fnClickHandler = function(e) {
                e.preventDefault();
                if (oSettings.oApi._fnPageChange(oSettings, e.data.action)) {
                    fnDraw(oSettings);
                    var targetOffset = $('.accounts_datatable').offset().top - 550;
                    $('html,body').animate({
                        scrollTop: targetOffset
                    }, 500);
                }
            };

            $(nPaging).addClass('pagination').append(
                '<ul>' +
                '<li class="prev disabled"><a href="#">&#171;</a></li>' +
                '<li class="next disabled"><a href="#">&#187;</a></li>' +
                '</ul>'
            );
            var els = $('a', nPaging);
            $(els[0]).bind('click.DT', {
                action: "previous"
            }, fnClickHandler);
            $(els[1]).bind('click.DT', {
                action: "next"
            }, fnClickHandler);
        },

        "fnUpdate": function(oSettings, fnDraw) {
            var iListLength = 5;
            var oPaging = oSettings.oInstance.fnPagingInfo();
            var an = oSettings.aanFeatures.p;
            var i, j, sClass, iStart, iEnd, iHalf = Math.floor(iListLength / 2);

            if (oPaging.iTotalPages < iListLength) {
                iStart = 1;
                iEnd = oPaging.iTotalPages;
            } else if (oPaging.iPage <= iHalf) {
                iStart = 1;
                iEnd = iListLength;
            } else if (oPaging.iPage >= (oPaging.iTotalPages - iHalf)) {
                iStart = oPaging.iTotalPages - iListLength + 1;
                iEnd = oPaging.iTotalPages;
            } else {
                iStart = oPaging.iPage - iHalf + 1;
                iEnd = iStart + iListLength - 1;
            }

            for (i = 0, iLen = an.length; i < iLen; i++) {
                $('li:gt(0)', an[i]).filter(':not(:last)').remove();


                for (j = iStart; j <= iEnd; j++) {
                    sClass = (j == oPaging.iPage + 1) ? 'class="active"' : '';
                    $('<li ' + sClass + '><a href="#">' + j + '</a></li>')
                        .insertBefore($('li:last', an[i])[0])
                        .bind('click', function(e) {
                            e.preventDefault();
                            oSettings._iDisplayStart = (parseInt($('a', this).text(), 10) - 1) * oPaging.iLength;
                            fnDraw(oSettings);
                            var targetOffset = $('.accounts_datatable').offset().top - 550;
                            $('html,body').animate({
                                scrollTop: targetOffset
                            }, 500);
                        });
                }

                if (oPaging.iPage === 0) {




                    $('li:first', an[i]).addClass('disabled');
                } else {
                    $('li:first', an[i]).removeClass('disabled');
                }


                if (oPaging.iPage === oPaging.iTotalPages - 1 || oPaging.iTotalPages === 0) {
                    $('li:last', an[i]).addClass('disabled');



                } else {
                    $('li:last', an[i]).removeClass('disabled');
                }
            }
        }



    }
});