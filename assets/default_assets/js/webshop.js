/* 
 * JavaScript classes for dmnmucms
 * Author: neo6 - salvis1989@gmail.com
 *
 */

function DmNWebshop() {
    this.item_id = -1;
    this.item_cat = 0;
    this.item_price = 0;
    this.credits = 0;
    this.gcredits = 0;
    this.gold_discount = 0;
    this.exe_price = 0;
    this.current_exe_price = 0;
    this.lvl_price = 0;
    this.current_lvl_price = 0;
    this.opt_price = 0;
    this.current_opt_price = 0;
    this.luck_price = 0;
    this.current_luck_price = 0;
    this.skill_price = 0;
    this.current_skill_price = 0;
    this.anc_price = [];
    this.current_anc_price = 0;
    this.fenrir_price = [];
    this.current_fenrir_price = 0;
    this.ref_price = 0;
    this.current_ref_price = 0;
    this.current_harmony_price = 0;
    this.exe_limit = 0;
    this.exe_ancient = true;
    this.harmony_ancient = false;
    this.harmony_selected = false;
    this.options = 0;
	this.s10_opts = 0;
	this.s10_values = ['7','8','9','10'];
    this.discount = 1;
    this.discount_percents = 0;
    this.allow_equal_seeds = 0;
    this.allow_equal_sockets = 0;
    this.socket_price = [0, 0, 0, 0, 0, 0];
    /* element system */
    this.element_type_price = 0;
    this.current_element_type_price = 0;
    this.element_rank_1_price = 0;
    this.current_element_rank_1_price = 0;
    this.element_rank_2_price = 0;
    this.current_element_rank_2_price = 0;
    this.element_rank_3_price = 0;
    this.current_element_rank_3_price = 0;
    this.element_rank_4_price = 0;
    this.current_element_rank_4_price = 0;
    this.element_rank_5_price = 0;
    this.current_element_rank_5_price = 0;
    this.pentagram_anger_slot_price = 0;
    this.current_pentagram_anger_slot_price = 0;
    this.pentagram_blessing_slot_price = 0;
    this.current_pentagram_blessing_slot_price = 0;
    this.pentagram_integrity_slot_price = 0;
    this.current_pentagram_integrity_slot_price = 0;
    this.pentagram_divinity_slot_price = 0;
    this.current_pentagram_divinity_slot_price = 0;
    this.pentagram_gale_slot_price = 0;
    this.current_pentagram_gale_slot_price = 0;
    /* element system end */
	/* new wings */
	this.wing_element_main_price = 0;
	this.current_wing_element_main_price = 0;
	this.wing_element_additional_price = 0;
	this.current_wing_element_additional_price = 0;
	this.wing_element_additional2_price = 0;
	this.current_wing_element_additional2_price = 0;
	this.wing_element_additional3_price = 0;
	this.current_wing_element_additional3_price = 0;
	this.wing_element_additional4_price = 0;
	this.current_wing_element_additional4_price = 0;
	this.wing_element_additional5_price = 0;
	this.current_wing_element_additional5_price = 0;
	/* new wings end */
    this.seeds = [];
    this.is_vip = 0;
    this.vip_discount = 0;
}
DmNWebshop.prototype.set_vip = function (data) {
    this.is_vip = data;
}
DmNWebshop.prototype.set_vip_discount = function (data) {
    this.vip_discount = data;
}
/* element system */
DmNWebshop.prototype.set_pentagram_anger_slot_price = function (data) {
    this.pentagram_anger_slot_price = data;
}
DmNWebshop.prototype.set_pentagram_blessing_slot_price = function (data) {
    this.pentagram_blessing_slot_price = data;
}
DmNWebshop.prototype.set_pentagram_integrity_slot_price = function (data) {
    this.pentagram_integrity_slot_price = data;
}
DmNWebshop.prototype.set_pentagram_divinity_slot_price = function (data) {
    this.pentagram_divinity_slot_price = data;
}
DmNWebshop.prototype.set_pentagram_gale_slot_price = function (data) {
    this.pentagram_gale_slot_price = data;
}
DmNWebshop.prototype.set_element_type_price = function (data) {
    this.element_type_price = data;
}
DmNWebshop.prototype.set_element_rank_1_price = function (data) {
    this.element_rank_1_price = data;
}
DmNWebshop.prototype.set_element_rank_2_price = function (data) {
    this.element_rank_2_price = data;
}
DmNWebshop.prototype.set_element_rank_3_price = function (data) {
    this.element_rank_3_price = data;
}
DmNWebshop.prototype.set_element_rank_4_price = function (data) {
    this.element_rank_4_price = data;
}
DmNWebshop.prototype.set_element_rank_5_price = function (data) {
    this.element_rank_5_price = data;
}
/* element system end */


DmNWebshop.prototype.set_wing_element_main_price = function (data) {
    this.wing_element_main_price = data;
}
DmNWebshop.prototype.set_wing_element_additional_price = function (data) {
    this.wing_element_additional_price = data;
}
DmNWebshop.prototype.set_wing_element_additional2_price = function (data) {
    this.wing_element_additional2_price = data;
}
DmNWebshop.prototype.set_wing_element_additional3_price = function (data) {
    this.wing_element_additional3_price = data;
}
DmNWebshop.prototype.set_wing_element_additional4_price = function (data) {
    this.wing_element_additional4_price = data;
}
DmNWebshop.prototype.set_wing_element_additional5_price = function (data) {
    this.wing_element_additional5_price = data;
}

DmNWebshop.prototype.set_item_id = function (data) {
    this.item_id = data;
}
DmNWebshop.prototype.set_item_cat = function (data) {
    this.item_cat = data;
}
DmNWebshop.prototype.set_item_price = function (data) {
    this.item_price = data;
}
DmNWebshop.prototype.set_gold_discount = function (data) {
    this.gold_discount = data;
}
DmNWebshop.prototype.set_exe_price = function (data) {
    this.exe_price = data;
}
DmNWebshop.prototype.set_lvl_price = function (data) {
    this.lvl_price = data;
}
DmNWebshop.prototype.set_opt_price = function (data) {
    this.opt_price = data;
}
DmNWebshop.prototype.set_luck_price = function (data) {
    this.luck_price = data;
}
DmNWebshop.prototype.set_skill_price = function (data) {
    this.skill_price = data;
}
DmNWebshop.prototype.set_anc_price = function (data) {
    this.anc_price = data;
}
DmNWebshop.prototype.set_fenrir_price = function (data) {
    this.fenrir_price = data;
}
DmNWebshop.prototype.set_ref_price = function (data) {
    this.ref_price = data;
}
DmNWebshop.prototype.set_exe_limit = function (data) {
    this.exe_limit = data;
}
DmNWebshop.prototype.set_allow_exeanc = function (data) {
    this.exe_ancient = data;
}
DmNWebshop.prototype.set_allow_harmonyanc = function (data) {
    this.harmony_ancient = data;
}
DmNWebshop.prototype.set_discount = function (data) {
    this.discount = data;
}
DmNWebshop.prototype.set_discount_percents = function (data) {
    this.discount_percents = data;
}
DmNWebshop.prototype.calc_discount = function (price) {
    return (this.discount == 1) ? Math.floor(price - ((price / 100) * this.discount_percents)) : price;
}
DmNWebshop.prototype.equal_seeds = function (data) {
    this.allow_equal_seeds = data;
}
DmNWebshop.prototype.equal_sockets = function (data) {
    this.allow_equal_sockets = data;
}
DmNWebshop.prototype.buyItem = function (payment_method, method) {
    if (method == 1) {
        if (App.confirmMessage(App.lc.translate('Are you sure you want to buy this item?').fetch())) {
            if (App.checkUserStatus() == true) {
                if (this.credits == 0)
                    this.credits = this.item_price;
                if (this.gcredits == 0)
                    this.gcredits = Math.floor(this.item_price + ((this.gold_discount * this.item_price) / 100));
                if (App.checkCredits(payment_method, this.credits, this.gcredits) == true) {
                    this.provideItem(payment_method, this.item_id, $('#item_form').serialize());
                }
            }
        }
    }
    else {
        this.addCardItem(payment_method, this.item_id, $('#item_form').serialize());
    }
}
DmNWebshop.prototype.provideItem = function (payment_method, name, info) {
    $.ajax({
        url: DmNConfig.base_url + 'shop/senditem/' + name + '/direct',
        data: info + '&' + $.param({'payment_method': payment_method, 'ajax': 1, 'dmn_csrf_protection': DmNConfig.dmn_csrf_token}),
        beforeSend: function () {
            App.showLoader();
        },
        complete: function () {
            App.hideLoader();
        },
        success: function (data) {
            if (data.error) {
                if ($.isArray(data.error)) {
                    $.each(data.error, function (key, val) {
                        App.notice(App.lc.translate('Error').fetch(), 'error', val);
                    });
                }
                else {
                    App.notice(App.lc.translate('Error').fetch(), 'error', data.error);
                }
            }
            else {
                App.closeBuyWindows(1);
                $('#item_content').dialog('destroy');
                $('#total_bought').fadeOut('slow').html(parseInt($('#total_bought').text()) + 1).fadeIn('slow');
                var credits = $('#my_credits').text().replace(/,/g, ''),
                    gcredits = $('#my_gold_credits').text().replace(/,/g, '');
                if (data.payment_method == 1) {
                    $('#my_credits').fadeOut('slow').html(App.formatMoney(parseInt(credits) - parseInt(data.price), 0, ',', ',')).fadeIn('slow');
                }
                else {
                    $('#my_gold_credits').fadeOut('slow').html(App.formatMoney(parseInt(gcredits) - parseInt(data.price), 0, ',', ',')).fadeIn('slow');
                }
                App.notice(App.lc.translate('Success').fetch(), 'success', data.success);
            }
        }
    });
}
DmNWebshop.prototype.addCardItem = function (payment_method, name, info) {
    $.ajax({
        url: DmNConfig.base_url + 'shop/senditem/' + name + '/card',
        data: info + '&' + $.param({'payment_method': payment_method, 'ajax': 1, 'dmn_csrf_protection': DmNConfig.dmn_csrf_token}),
        beforeSend: function () {
            App.showLoader();
        },
        complete: function () {
            App.hideLoader();
        },
        success: function (data) {
            if (data.error) {
                if ($.isArray(data.error)) {
                    $.each(data.error, function (key, val) {
                        App.notice(App.lc.translate('Error').fetch(), 'error', val);
                    });
                }
                else {
                    App.notice(App.lc.translate('Error').fetch(), 'error', data.error);
                }
            }
            else {
                App.closeBuyWindows(2);
                App.notice(App.lc.translate('Success').fetch(), 'success', data.success);
            }
        }
    });
}
DmNWebshop.prototype.intializeCalculator = function () {
    var that = this;
    /* element system */
    $('#slot_anger').on('change', function () {
        that.current_pentagram_anger_slot_price = $(this).is(':checked') ? that.calc_discount(that.pentagram_anger_slot_price) : 0;
        that.setPricesHTML();
    });
    $('#slot_blessing').on('change', function () {
        that.current_pentagram_blessing_slot_price = $(this).is(':checked') ? that.calc_discount(that.pentagram_blessing_slot_price) : 0;
        that.setPricesHTML();
    });
    $('#slot_integrity').on('change', function () {
        that.current_pentagram_integrity_slot_price = $(this).is(':checked') ? that.calc_discount(that.pentagram_integrity_slot_price) : 0;
        that.setPricesHTML();
    });
    $('#slot_divinity').on('change', function () {
        that.current_pentagram_divinity_slot_price = $(this).is(':checked') ? that.calc_discount(that.pentagram_divinity_slot_price) : 0;
        that.setPricesHTML();
    });
    $('#slot_gale').on('change', function () {
        that.current_pentagram_gale_slot_price = $(this).is(':checked') ? that.calc_discount(that.pentagram_gale_slot_price) : 0;
        that.setPricesHTML();
    });
    $('#element_type').on('change', function () {
        var selected = isNaN($('#element_type option:selected').val()) ? 0 : $('#element_type option:selected').val();
        that.current_element_type_price = (selected != 0) ? that.calc_discount(that.element_type_price) : 0;
        that.setPricesHTML();
    });

    $('#element_rank_1').on('change', function () {
        var selected = isNaN($('#element_rank_1 option:selected').val()) ? 0 : $('#element_rank_1 option:selected').val();
        that.current_element_rank_1_price = (selected != 0) ? that.calc_discount(that.element_rank_1_price) : 0;
        if ($('#rank_1_lvl').val() > 0) {
            that.current_element_rank_1_price = (parseInt($('#rank_1_lvl').val()) + 1) * that.calc_discount(that.element_rank_1_price)
        }
        if (selected == 0) {
            $('#rank_1_lvl').val(0);
            $('#rank_2_lvl').val(0);
            $('#rank_3_lvl').val(0);
            $('#rank_4_lvl').val(0);
            $('#rank_5_lvl').val(0);
            $('#element_rank_2').val(0);
            $('#element_rank_3').val(0);
            $('#element_rank_4').val(0);
            $('#element_rank_5').val(0);
            that.current_element_rank_1_price = 0;
            that.current_element_rank_2_price = 0;
            that.current_element_rank_3_price = 0;
            that.current_element_rank_4_price = 0;
            that.current_element_rank_5_price = 0;
        }
        that.setPricesHTML();
    });

    $('#element_rank_2').on('change', function () {
        if ($('#element_rank_1 option:selected').val() == 0) {
            App.notice(App.lc.translate('Error').fetch(), 'error', App.sprintf(App.lc.translate('Please select Rank %d option first!').fetch(), 1));
            $('#element_rank_2').val(0);
            return false;
        }
        var selected = isNaN($('#element_rank_2 option:selected').val()) ? 0 : $('#element_rank_2 option:selected').val();
        that.current_element_rank_2_price = (selected != 0) ? that.calc_discount(that.element_rank_2_price) : 0;
        if ($('#rank_2_lvl').val() > 0) {
            that.current_element_rank_2_price = (parseInt($('#rank_2_lvl').val()) + 1) * that.calc_discount(that.element_rank_2_price);
        }
        if (selected == 0) {
            $('#rank_2_lvl').val(0);
            $('#rank_3_lvl').val(0);
            $('#rank_4_lvl').val(0);
            $('#rank_5_lvl').val(0);
            $('#element_rank_3').val(0);
            $('#element_rank_4').val(0);
            $('#element_rank_5').val(0);
            that.current_element_rank_2_price = 0;
            that.current_element_rank_3_price = 0;
            that.current_element_rank_4_price = 0;
            that.current_element_rank_5_price = 0;
        }
        that.setPricesHTML();
    });

    $('#element_rank_3').on('change', function () {
        if ($('#element_rank_2 option:selected').val() == 0) {
            App.notice(App.lc.translate('Error').fetch(), 'error', App.sprintf(App.lc.translate('Please select Rank %d option first!').fetch(), 2));
            $('#element_rank_3').val(0);
            return false;
        }
        var selected = isNaN($('#element_rank_3 option:selected').val()) ? 0 : $('#element_rank_3 option:selected').val();
        that.current_element_rank_3_price = (selected != 0) ? that.calc_discount(that.element_rank_3_price) : 0;
        if ($('#rank_3_lvl').val() > 0) {
            that.current_element_rank_3_price = (parseInt($('#rank_3_lvl').val()) + 1) * that.calc_discount(that.element_rank_3_price);
        }
        if (selected == 0) {
            $('#rank_3_lvl').val(0);
            $('#rank_4_lvl').val(0);
            $('#rank_5_lvl').val(0);
            $('#element_rank_4').val(0);
            $('#element_rank_5').val(0);
            that.current_element_rank_3_price = 0;
            that.current_element_rank_4_price = 0;
            that.current_element_rank_5_price = 0;
        }
        that.setPricesHTML();
    });

    $('#element_rank_4').on('change', function () {
        if ($('#element_rank_3 option:selected').val() == 0) {
            App.notice(App.lc.translate('Error').fetch(), 'error', App.sprintf(App.lc.translate('Please select Rank %d option first!').fetch(), 3));
            $('#element_rank_4').val(0);
            return false;
        }
        var selected = isNaN($('#element_rank_4 option:selected').val()) ? 0 : $('#element_rank_4 option:selected').val();
        that.current_element_rank_4_price = (selected != 0) ? that.calc_discount(that.element_rank_4_price) : 0;
        if ($('#rank_4_lvl').val() > 0) {
            that.current_element_rank_4_price = (parseInt($('#rank_4_lvl').val()) + 1) * that.calc_discount(that.element_rank_4_price);

        }
        if (selected == 0) {
            $('#rank_4_lvl').val(0);
            $('#rank_5_lvl').val(0);
            $('#element_rank_5').val(0);
            that.current_element_rank_4_price = 0;
            that.current_element_rank_5_price = 0;
        }
        that.setPricesHTML();
    });

    $('#element_rank_5').on('change', function () {
        if ($('#element_rank_4 option:selected').val() == 0) {
            App.notice(App.lc.translate('Error').fetch(), 'error', App.sprintf(App.lc.translate('Please select Rank %d option first!').fetch(), 4));
            $('#element_rank_5').val(0);
            return false;
        }
        var selected = isNaN($('#element_rank_5 option:selected').val()) ? 0 : $('#element_rank_5 option:selected').val();
        that.current_element_rank_5_price = (selected != 0) ? that.calc_discount(that.element_rank_5_price) : 0;
        if ($('#rank_5_lvl').val() > 0) {
            that.current_element_rank_5_price = (parseInt($('#rank_5_lvl').val()) + 1) * that.calc_discount(that.element_rank_5_price);

        }
        if (selected == 0) {
            $('#rank_5_lvl').val(0);
            that.current_element_rank_5_price = 0;
        }
        that.setPricesHTML();
    });

    $('#rank_1_lvl').on('change', function () {
        var selected = isNaN($('#element_rank_1 option:selected').val()) ? 0 : $('#element_rank_1 option:selected').val();
        if (selected != 0) {
            that.current_element_rank_1_price = (parseInt($('#rank_1_lvl').val()) + 1) * that.calc_discount(that.element_rank_1_price);
        }
        else {
            that.current_element_rank_1_price = 0;
        }
        that.setPricesHTML();
    });

    $('#rank_2_lvl').on('change', function () {
        var selected = isNaN($('#element_rank_2 option:selected').val()) ? 0 : $('#element_rank_2 option:selected').val();
        if (selected != 0) {
            that.current_element_rank_2_price = (parseInt($('#rank_2_lvl').val()) + 1) * that.calc_discount(that.element_rank_2_price);
        }
        else {
            that.current_element_rank_2_price = 0;
        }
        that.setPricesHTML();
    });

    $('#rank_3_lvl').on('change', function () {
        var selected = isNaN($('#element_rank_3 option:selected').val()) ? 0 : $('#element_rank_3 option:selected').val();
        if (selected != 0) {
            that.current_element_rank_3_price = (parseInt($('#rank_3_lvl').val()) + 1) * that.calc_discount(that.element_rank_3_price);
        }
        else {
            that.current_element_rank_3_price = 0;
        }
        that.setPricesHTML();
    });

    $('#rank_4_lvl').on('change', function () {
        var selected = isNaN($('#element_rank_4 option:selected').val()) ? 0 : $('#element_rank_4 option:selected').val();
        if (selected != 0) {
            that.current_element_rank_4_price = (parseInt($('#rank_4_lvl').val()) + 1) * that.calc_discount(that.element_rank_4_price);
        }
        else {
            that.current_element_rank_4_price = 0;
        }
        that.setPricesHTML();
    });

    $('#rank_5_lvl').on('change', function () {
        var selected = isNaN($('#element_rank_5 option:selected').val()) ? 0 : $('#element_rank_5 option:selected').val();
        if (selected != 0) {
            that.current_element_rank_5_price = (parseInt($('#rank_5_lvl').val()) + 1) * that.calc_discount(that.element_rank_5_price);
        }
        else {
            that.current_element_rank_5_price = 0;
        }
        that.setPricesHTML();
    });
    /* element system end */
	
	$('#wing_main_element').on('change', function () {
        var selected = isNaN($('#wing_main_element option:selected').val()) ? 0 : $('#wing_main_element option:selected').val();
        that.current_wing_element_main_price = (selected != 0) ? that.calc_discount(that.wing_element_main_price) : 0;
        if ($('#wing_main_element_lvl').val() > 0) {
            that.current_wing_element_main_price = (parseInt($('#wing_main_element_lvl').val()) + 1) * that.calc_discount(that.wing_element_main_price)
        }
        if (selected == 0) {
            $('#wing_main_element').val(0);
            $('#wing_main_element_lvl').val(0);
            that.current_wing_element_main_price = 0;
        }
        that.setPricesHTML();
    });
	
	$('#wing_additional_element').on('change', function () {
        var selected = isNaN($('#wing_additional_element option:selected').val()) ? 0 : $('#wing_additional_element option:selected').val();
        that.current_wing_element_additional_price = (selected != 0) ? that.calc_discount(that.wing_element_additional_price) : 0;
        if ($('#wing_additional_element_lvl').val() > 0) {
            that.current_wing_element_additional_price = (parseInt($('#wing_additional_element_lvl').val()) + 1) * that.calc_discount(that.wing_element_additional_price)
        }
        if (selected == 0) {
            $('#wing_additional_element').val(0);
            $('#wing_additional_element_lvl').val(0);
            that.current_wing_element_additional_price = 0;
        }
        that.setPricesHTML();
    });
	
	$('#wing_additional2_element').on('change', function () {
        var selected = isNaN($('#wing_additional2_element option:selected').val()) ? 0 : $('#wing_additional2_element option:selected').val();
        that.current_wing_element_additional2_price = (selected != 0) ? that.calc_discount(that.wing_element_additional2_price) : 0;
        if ($('#wing_additional2_element_lvl').val() > 0) {
            that.current_wing_element_additional2_price = (parseInt($('#wing_additional2_element_lvl').val()) + 1) * that.calc_discount(that.wing_element_additional2_price)
        }
        if (selected == 0) {
            $('#wing_additional2_element').val(0);
            $('#wing_additional2_element_lvl').val(0);
            that.current_wing_element_additional2_price = 0;
        }
        that.setPricesHTML();
    });
	
	$('#wing_additional3_element').on('change', function () {
        var selected = isNaN($('#wing_additional3_element option:selected').val()) ? 0 : $('#wing_additional3_element option:selected').val();
        that.current_wing_element_additional3_price = (selected != 0) ? that.calc_discount(that.wing_element_additional3_price) : 0;
        if ($('#wing_additional3_element_lvl').val() > 0) {
            that.current_wing_element_additional3_price = (parseInt($('#wing_additional3_element_lvl').val()) + 1) * that.calc_discount(that.wing_element_additional3_price)
        }
        if (selected == 0) {
            $('#wing_additional3_element').val(0);
            $('#wing_additional3_element_lvl').val(0);
            that.current_wing_element_additional3_price = 0;
        }
        that.setPricesHTML();
    });
	
	$('#wing_additional4_element').on('change', function () {
        var selected = isNaN($('#wing_additional4_element option:selected').val()) ? 0 : $('#wing_additional4_element option:selected').val();
        that.current_wing_element_additional4_price = (selected != 0) ? that.calc_discount(that.wing_element_additional4_price) : 0;
        if ($('#wing_additional4_element_lvl').val() > 0) {
            that.current_wing_element_additional4_price = (parseInt($('#wing_additional4_element_lvl').val()) + 1) * that.calc_discount(that.wing_element_additional4_price)
        }
        if (selected == 0) {
            $('#wing_additional4_element').val(0);
            $('#wing_additional4_element_lvl').val(0);
            that.current_wing_element_additional4_price = 0;
        }
        that.setPricesHTML();
    });
	
	$('#wing_additional5_element').on('change', function () {
        var selected = isNaN($('#wing_additional5_element option:selected').val()) ? 0 : $('#wing_additional5_element option:selected').val();
        that.current_wing_element_additional5_price = (selected != 0) ? that.calc_discount(that.wing_element_additional5_price) : 0;
        if ($('#wing_additional5_element_lvl').val() > 0) {
            that.current_wing_element_additional5_price = (parseInt($('#wing_additional5_element_lvl').val()) + 1) * that.calc_discount(that.wing_element_additional5_price)
        }
        if (selected == 0) {
            $('#wing_additional5_element').val(0);
            $('#wing_additional5_element_lvl').val(0);
            that.current_wing_element_additional5_price = 0;
        }
        that.setPricesHTML();
    });
	
	$('#wing_main_element_lvl').on('change', function () {
        var selected = isNaN($('#wing_main_element option:selected').val()) ? 0 : $('#wing_main_element option:selected').val();
        if (selected != 0) {
            that.current_wing_element_main_price = (parseInt($('#wing_main_element_lvl').val()) + 1) * that.calc_discount(that.wing_element_main_price);
        }
        else {
            that.current_wing_element_main_price = 0;
        }
        that.setPricesHTML();
    });
	
	$('#wing_additional_element_lvl').on('change', function () {
        var selected = isNaN($('#wing_additional_element option:selected').val()) ? 0 : $('#wing_additional_element option:selected').val();
        if (selected != 0) {
            that.current_wing_element_additional_price = (parseInt($('#wing_additional_element_lvl').val()) + 1) * that.calc_discount(that.wing_element_additional_price);
        }
        else {
            that.current_wing_element_additional_price = 0;
        }
        that.setPricesHTML();
    });
	
	$('#wing_additional2_element_lvl').on('change', function () {
        var selected = isNaN($('#wing_additional2_element option:selected').val()) ? 0 : $('#wing_additional2_element option:selected').val();
        if (selected != 0) {
            that.current_wing_element_additional2_price = (parseInt($('#wing_additional2_element_lvl').val()) + 1) * that.calc_discount(that.wing_element_additional2_price);
        }
        else {
            that.current_wing_element_additional2_price = 0;
        }
        that.setPricesHTML();
    });
	
	$('#wing_additional3_element_lvl').on('change', function () {
        var selected = isNaN($('#wing_additional3_element option:selected').val()) ? 0 : $('#wing_additional3_element option:selected').val();
        if (selected != 0) {
            that.current_wing_element_additional3_price = (parseInt($('#wing_additional3_element_lvl').val()) + 1) * that.calc_discount(that.wing_element_additional3_price);
        }
        else {
            that.current_wing_element_additional3_price = 0;
        }
        that.setPricesHTML();
    });
	
	$('#wing_additional4_element_lvl').on('change', function () {
        var selected = isNaN($('#wing_additional4_element option:selected').val()) ? 0 : $('#wing_additional4_element option:selected').val();
        if (selected != 0) {
            that.current_wing_element_additional4_price = (parseInt($('#wing_additional4_element_lvl').val()) + 1) * that.calc_discount(that.wing_element_additional4_price);
        }
        else {
            that.current_wing_element_additional4_price = 0;
        }
        that.setPricesHTML();
    });
	
	$('#wing_additional5_element_lvl').on('change', function () {
        var selected = isNaN($('#wing_additional5_element option:selected').val()) ? 0 : $('#wing_additional5_element option:selected').val();
        if (selected != 0) {
            that.current_wing_element_additional5_price = (parseInt($('#wing_additional5_element_lvl').val()) + 1) * that.calc_discount(that.wing_element_additional5_price);
        }
        else {
            that.current_wing_element_additional5_price = 0;
        }
        that.setPricesHTML();
    });

    $('#item_level').on('change', function () {
        var selected = isNaN($('#item_level option:selected').val()) ? 0 : $('#item_level option:selected').val();
        that.current_lvl_price = selected * that.calc_discount(that.lvl_price);
        that.setPricesHTML();
    });
    $('#item_opt').on('change', function () {
        var selected = isNaN($('#item_opt option:selected').val()) ? 0 : $('#item_opt option:selected').val();
        that.current_opt_price = selected * that.calc_discount(that.opt_price);
        that.setPricesHTML();
    });
    $('#item_luck').on('change', function () {
        that.current_luck_price = $(this).is(':checked') ? that.calc_discount(that.luck_price) : 0;
        that.setPricesHTML();
    });
    $('#item_skill').on('change', function () {
        that.current_skill_price = $(this).is(':checked') ? that.calc_discount(that.skill_price) : 0;
        that.setPricesHTML();
    });
    $('#item_ref').on('change', function () {
        that.current_ref_price = $(this).is(':checked') ? that.calc_discount(that.ref_price) : 0;
        that.setPricesHTML();
    });
    $('#item_anc').on('change', function () {
        that.current_anc_price = isNaN($('#item_anc option:selected').val()) ? 0 : that.calc_discount(that.anc_price[$('#item_anc option:selected').val() - 1]);
        that.resetExeAnc();
        that.resetHarmonyAnc();
        that.setPricesHTML();
    });
    $('input[id^="ex"]').each(function () {
        $(this).on('change', function () {
            if ($(this).is(':checked')) {
                that.checkExe($(this), true);
            }
            else {
                that.checkExe($(this), false);
            }
            that.setPricesHTML();
        });
    });
    $('#item_harm').on('change', function () {
        if (!isNaN($('#item_harm option:selected').val()) && $('#item_harm option:selected').val() != '') {
            $.ajax({
                url: DmNConfig.base_url + 'shop/loadharmonylist',
                data: {
                    ajax: 1,
                    cat: that.item_cat,
                    hopt: $('#item_harm').val()
                },
                success: function (data) {
                    if (typeof data.harmonylist != 'undefined') {
                        var html = '<select name="harmonyvalue" id="harmonyvalue"><option value="-1" selected="selected">--select--</option>';
                        $.each(data.harmonylist, function (key, val) {
                            html += '<option value="' + val.hvalue + '">' + val.hname + '</option>';
                        });
                        html += '</select>';
                        $('#harmonyvalue').html(html);
                        $('#harmonyoption').show();
                        that.harmony_selected = true;
                        that.resetHarmonyAnc();
                    }
                    else {
                        if (data.error) {
                            App.notice(App.lc.translate('Error').fetch(), 'error', data.error);
                        }
                        else {
                            App.notice(App.lc.translate('Error').fetch(), 'error', App.lc.translate('No harmony data found').fetch());
                        }
                        that.current_harmony_price = 0;
                        that.setPricesHTML(true);
                        $('#harmonyvalue').html(App.lc.translate('Select harmony option').fetch());
                        $('#harmonyoption').hide();

                    }
                }
            });
        }
        else {
            that.current_harmony_price = 0;
            that.setPricesHTML(true);
            $('#harmonyvalue').html(App.lc.translate('Select harmony option').fetch());
            $('#harmonyoption').hide();
        }
    });
    $('#harmonyvalue').on('change', function () {
        if (!isNaN($('#harmonyvalue option:selected').val()) || ($('#harmonyvalue option:selected').val() != -1)) {
            $.ajax({
                url: DmNConfig.base_url + 'shop/getharmonyprice',
                data: {
                    ajax: 1,
                    cat: that.item_cat,
                    hopt: $('#item_harm').val(),
                    hval: $('#harmonyvalue option:selected').val()
                },
                success: function (data) {
                    if (data.hprice) {
                        that.current_harmony_price = parseInt(data.hprice);
                    }
                    else {
                        if (data.error) {
                            App.notice(App.lc.translate('Error').fetch(), 'error', data.error);
                        }
                        else {
                            App.notice(App.lc.translate('Error').fetch(), 'error', App.lc.translate('Unable to get harmony price').fetch());
                        }
                        that.current_harmony_price = 0;
                    }
                    that.setPricesHTML(true);
                    that.resetHarmonyAnc();
                }
            });
        }
        else {
            that.current_harmony_price = 0;
        }
    });
    $('select[id^="socket"]').each(function () {
        $(this).on('change', function () {
            var div = this;
            if ($("option:selected", this).length && $("option:selected", this).val() != 'no') {
                var opt = $("option:selected", this).val().split("-");
                $.ajax({
                    url: DmNConfig.base_url + 'shop/getsocketprice',
                    data: {
                        ajax: 1,
                        option: opt[1]
                    },
                    success: function (data) {
                        if (data.socket_price) {
                            that.socket_price[$(div).attr('id').charAt($(div).attr('id').length - 1)] = parseInt(data.socket_price);
                        }
                        else {
                            if (data.error) {
                                App.notice(App.lc.translate('Error').fetch(), 'error', data.error);
                                that.socket_price[$(div).attr('id').charAt($(div).attr('id').length - 1)] = 0;
                            }
                        }
                        that.setPricesHTML(false, true);
                    }
                });
                that.removeSocketOpt(opt[0], $(this));
            }
        });
    });
    $('input[id^="fenrir"]').each(function () {
        $(this).on('change', function () {
            var num = $(this).attr('id').charAt($(this).attr('id').length - 1)
            that.current_fenrir_price = that.calc_discount(that.fenrir_price[num - 1]);
            that.setPricesHTML();
        });
    });
}
DmNWebshop.prototype.resetExeAnc = function () {
    var that = this;
    if (!isNaN($('#item_anc option:selected').val())) {
        if (!that.exe_ancient) {
            if (that.options > 0) {
                $('#item_anc').val('');
                that.current_anc_price = 0;
                $('input[id^="ex"]').each(function () {
                    $(this).attr('checked', false);
                    that.options = 0;
                    that.current_exe_price = 0;
                });
                App.notice(App.lc.translate('Error').fetch(), 'error', App.lc.translate('Please choose only ancient or only excelent options.').fetch());
            }
        }
    }
}
DmNWebshop.prototype.resetHarmonyAnc = function () {
    var that = this;
    if (!isNaN($('#item_anc option:selected').val())) {
        if (!that.harmony_ancient) {
            if (that.harmony_selected) {
                $('#item_anc').val('');
                $('#item_harm').prop('selectedIndex', 0);
                $('#harmonyoption').hide();
                that.current_anc_price = 0;
                that.current_harmony_price = 0;
                App.notice(App.lc.translate('Error').fetch(), 'error', App.lc.translate('Please choose only ancient or only harmony options.').fetch());
            }
        }
    }
}
DmNWebshop.prototype.checkExe = function(cur_opt, check){
	if(check){
		if(this.s10_values.indexOf(cur_opt.val()) != -1){
			this.s10_opts = this.s10_opts + 1;
		}
		this.options = this.options + 1;
		this.current_exe_price = this.current_exe_price+this.calc_discount(this.exe_price);
	}
	else{
		if(this.s10_values.indexOf(cur_opt.val()) != -1){
			this.s10_opts = this.s10_opts - 1;
		}
		this.options = this.options - 1;
		this.current_exe_price = this.current_exe_price-this.calc_discount(this.exe_price);
	}
	
	this.resetExeAnc();
	if(this.options > this.exe_limit){
		App.notice(App.lc.translate('Error').fetch(), 'error', App.sprintf(App.lc.translate('You can have only %d excellent options per item.').fetch(), this.exe_limit));
		cur_opt.attr('checked', false);
		this.options = this.options - 1;
		this.current_exe_price = this.current_exe_price-this.exe_price;
	}
	if(this.s10_opts > 3){
		App.notice(App.lc.translate('Error').fetch(), 'error', App.sprintf(App.lc.translate('You can have only 3 Season 10+ excellent options per item.').fetch(), this.exe_limit));
		cur_opt.attr('checked', false);
		this.options = this.options - 1;
		this.s10_opts = this.s10_opts - 1;
		this.current_exe_price = this.current_exe_price-this.exe_price;
	}
}
DmNWebshop.prototype.setPricesHTML = function (h_price, s_price) {
    /* element system */
    if ($('#credits_slot_anger').length > 0) {
        $('#credits_slot_anger').html(this.current_pentagram_anger_slot_price);
    }
    if ($('#credits_slot_blessing').length > 0) {
        $('#credits_slot_blessing').html(this.current_pentagram_blessing_slot_price);
    }
    if ($('#credits_slot_integrity').length > 0) {
        $('#credits_slot_integrity').html(this.current_pentagram_integrity_slot_price);
    }
    if ($('#credits_slot_divinity').length > 0) {
        $('#credits_slot_divinity').html(this.current_pentagram_divinity_slot_price);
    }
    if ($('#credits_slot_gale').length > 0) {
        $('#credits_slot_gale').html(this.current_pentagram_gale_slot_price);
    }
    if ($('#credits_element_type').length > 0) {
        $('#credits_element_type').html(this.current_element_type_price);
    }
    if ($('#element_rank_1').length > 0) {
        $('#credits_element_rank_1').html(this.current_element_rank_1_price);
    }
    if ($('#element_rank_2').length > 0) {
        $('#credits_element_rank_2').html(this.current_element_rank_2_price);
    }
    if ($('#element_rank_3').length > 0) {
        $('#credits_element_rank_3').html(this.current_element_rank_3_price);
    }
    if ($('#element_rank_4').length > 0) {
        $('#credits_element_rank_4').html(this.current_element_rank_4_price);
    }
    if ($('#element_rank_5').length > 0) {
        $('#credits_element_rank_5').html(this.current_element_rank_5_price);
    }

    /* element system end */
	
	if ($('#wing_main_element').length > 0) {
        $('#credits_wing_main_element').html(this.current_wing_element_main_price);
    }
	if ($('#wing_additional_element').length > 0) {
        $('#credits_wing_additional_element').html(this.current_wing_element_additional_price);
    }
	if ($('#wing_additional2_element').length > 0) {
        $('#credits_wing_additional2_element').html(this.current_wing_element_additional2_price);
    }
	if ($('#wing_additional3_element').length > 0) {
        $('#credits_wing_additional3_element').html(this.current_wing_element_additional3_price);
    }
	if ($('#wing_additional4_element').length > 0) {
        $('#credits_wing_additional4_element').html(this.current_wing_element_additional4_price);
    }
	if ($('#wing_additional5_element').length > 0) {
        $('#credits_wing_additional5_element').html(this.current_wing_element_additional5_price);
    }

    if ($('#credits_level').length > 0) {
        $('#credits_level').html(this.current_lvl_price);
    }
    if ($('#credits_opt').length > 0) {
        $('#credits_opt').html(this.current_opt_price);
    }
    if ($('#credits_luck').length > 0) {
        $('#credits_luck').html(this.current_luck_price);
    }
    if ($('#credits_skill').length > 0) {
        $('#credits_skill').html(this.current_skill_price);
    }
    if ($('#credits_ref').length > 0) {
        $('#credits_ref').html(this.current_ref_price);
    }
    if ($('#credits_ancient').length > 0) {
        $('#credits_ancient').html(this.current_anc_price);
    }
    if ($('#credits_exe').length > 0) {
        $('#credits_exe').html(this.current_exe_price);
    }
    if ($('#credits_fenrir').length > 0) {
        $('#credits_fenrir').html(this.current_fenrir_price);
    }
    if (h_price) {
        $('#credits_harm').html(this.current_harmony_price);
    }
    if (s_price) {
        $('#credits_socket').html(parseInt(this.socket_price[1] + this.socket_price[2] + this.socket_price[3] + this.socket_price[4] + this.socket_price[5]));
    }

    this.credits = parseInt(this.item_price + this.current_lvl_price + this.current_opt_price + this.current_luck_price + this.current_skill_price + this.current_ref_price + this.current_anc_price + this.current_exe_price + this.current_fenrir_price + this.current_harmony_price + this.socket_price[1] + this.socket_price[2] + this.socket_price[3] + this.socket_price[4] + this.socket_price[5] + this.current_element_type_price + this.current_element_rank_1_price + this.current_element_rank_2_price + this.current_element_rank_3_price + this.current_element_rank_4_price + this.current_element_rank_5_price + this.current_pentagram_anger_slot_price + this.current_pentagram_blessing_slot_price + this.current_pentagram_integrity_slot_price + this.current_pentagram_divinity_slot_price + this.current_pentagram_gale_slot_price + this.current_wing_element_main_price + this.current_wing_element_additional_price + this.current_wing_element_additional2_price + this.current_wing_element_additional3_price + this.current_wing_element_additional4_price + this.current_wing_element_additional5_price);
    this.gcredits = Math.floor(this.credits + ((this.gold_discount * this.credits) / 100));
    if (this.is_vip == 1) {
        this.credits -= Math.floor((this.credits / 100) * this.vip_discount);
        this.gcredits -= Math.floor((this.gcredits / 100) * this.vip_discount);
    }
    $('#total_credits').html(this.credits);
    $('#total_credits_g').html(this.gcredits);

}
DmNWebshop.prototype.removeSocketOpt = function (seed, socket) {
    var remove_socket = true;
    if ($("option:selected", socket).val().split('-')[1] != '254' && $("option:selected", socket).val() != 'no') {
        this.seeds[socket.attr('id')] = seed;
    }
    else {
        remove_socket = false;
        delete this.seeds[socket.attr('id')];
    }

    $('option[id^="socket"]').show().prop('disabled', false);

    if (this.allow_equal_seeds == 0) {
        if (this.seeds.socket1 !== undefined) {
            $('option[id^="socket2-seed-' + this.seeds.socket1 + '-"]').prop('disabled', true);
            $('option[id^="socket3-seed-' + this.seeds.socket1 + '-"]').prop('disabled', true);
            $('option[id^="socket4-seed-' + this.seeds.socket1 + '-"]').prop('disabled', true);
            $('option[id^="socket5-seed-' + this.seeds.socket1 + '-"]').prop('disabled', true);
        }
        if (this.seeds.socket2 !== undefined) {
            $('option[id^="socket1-seed-' + this.seeds.socket2 + '-"]').prop('disabled', true);
            $('option[id^="socket3-seed-' + this.seeds.socket2 + '-"]').prop('disabled', true);
            $('option[id^="socket4-seed-' + this.seeds.socket2 + '-"]').prop('disabled', true);
            $('option[id^="socket5-seed-' + this.seeds.socket2 + '-"]').prop('disabled', true);
        }
        if (this.seeds.socket3 !== undefined) {
            $('option[id^="socket1-seed-' + this.seeds.socket3 + '-"]').prop('disabled', true);
            $('option[id^="socket2-seed-' + this.seeds.socket3 + '-"]').prop('disabled', true);
            $('option[id^="socket4-seed-' + this.seeds.socket3 + '-"]').prop('disabled', true);
            $('option[id^="socket5-seed-' + this.seeds.socket3 + '-"]').prop('disabled', true);
        }
        if (this.seeds.socket4 !== undefined) {
            $('option[id^="socket1-seed-' + this.seeds.socket4 + '-"]').prop('disabled', true);
            $('option[id^="socket2-seed-' + this.seeds.socket4 + '-"]').prop('disabled', true);
            $('option[id^="socket3-seed-' + this.seeds.socket4 + '-"]').prop('disabled', true);
            $('option[id^="socket5-seed-' + this.seeds.socket4 + '-"]').prop('disabled', true);
        }
        if (this.seeds.socket5 !== undefined) {
            $('option[id^="socket1-seed-' + this.seeds.socket5 + '-"]').prop('disabled', true);
            $('option[id^="socket2-seed-' + this.seeds.socket5 + '-"]').prop('disabled', true);
            $('option[id^="socket3-seed-' + this.seeds.socket5 + '-"]').prop('disabled', true);
            $('option[id^="socket4-seed-' + this.seeds.socket5 + '-"]').prop('disabled', true);
        }
    }
    else {
        if (this.allow_equal_sockets == 0) {
            if (remove_socket == true) {
                var arr = $.map(
                    $('select[id^="socket"] option:selected'), function (n) {
                        return n.value;
                    }
                );

                $('select[id^="socket"] option').filter(function () {
                    return $.inArray($(this).val(), arr) >= 1;
                }).hide();
            }
        }
    }
}