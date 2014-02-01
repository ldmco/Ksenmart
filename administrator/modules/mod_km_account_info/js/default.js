    
    var ctrl        = false;
    //var img_loader  = '<div class="loader"><img src="'+root+'components/com_ksenmart/css/i/ajax-loader_2.gif" alt="Загрузка" /></div>';
    var img_loader  = '<div class="spinner" role="spinner"><div class="spinner-icon"></div></div>';
    var default_img = '<div style="padding: 0px; border: 1px solid #9C9C9C;"><img src="http://ldmco.ru/api/images/avatars/default_avatar.png" style="margin: 0px; padding: 0px;" width="80px" height="80px" align="center" valign="middle"></div>';
    
    function stepsWindow(e){
        
        var a = null;
        
        function step(butn){
            NProgress.start();
            eval(a.data().callback);
            var current_id  = butn.attr('id');
            var step        = parseInt(current_id.replace(/\D+/g,""));
            
            if(butn.hasClass('prev_step')){
                var current_step = step+1;
            }else{
                var current_step = step-1;
            }
            
            if(!butn.hasClass('finish_step')){
                jQuery('#step_page_'+(current_step)).fadeOut(400, function(){
                    jQuery('#step_page_'+step).fadeIn(400);
                });
            }
            
            if(butn.hasClass('prev_step')){
                NProgress.done();
            }            
            /*if (callback && typeof(callback) === "function") { 
                callback();
            }*/
        }
        
        e.find('[id ^= step_page_]').on('keydown', function(event){
            switch (event.keyCode) {
                case 13:
                    a = jQuery(this).find('[id ^= l-step_page_]');
                    if(!a.hasClass('btn-dsbl')){
                        step(a);
                    }
                break;
            }
        });
        
        e.find('[id ^= l-step_page_]').on('click', function(){
            a = jQuery(this);
            if(!a.hasClass('btn-dsbl')){
                step(jQuery(this));
            }
        });
    }
    
    function checkUserMenu(e){
        if(e.parents('div.user_menu').length == 1){
            return true;
        }
        return false;
    }
    
	jQuery(document).keydown(function(e){
		if (e.which == 17){
            ctrl = true;
		}
	});
	
	jQuery(document).keyup(function(e){
		if (e.which == 17){
            ctrl = false;
		}
	});
    
    var popup_body_tickets  = null;
    var popup_body_credits  = null;
    var popup_body_domains  = null;
    var popup_body_vhosts   = null;
    var popup_body_ticket   = null;
    var popup_ticket_create = null;
    var popup_profile       = null;
    var popup_body_settings = null;
    var popup_rerun_domain  = null;
    var popup_renew_domain  = null;
    var popup_domain_edit   = null;
    var popup_avatar_load   = null;
    
    var popup_body_archived_tickets     = null;
    var popup_body_new_credit           = null;
    var popup_body_new_domain           = null;
    var popup_body_new_vhost            = null;
    var popup_body_credit_qiwi_pay      = null;
    var popup_domaincontact_create      = null;
    
    jQuery(document).ready(function(){
        
        jQuery('body').on('click', '.getAccountActivity', function(){
            if(!checkUserMenu(jQuery(this))){
                getAccountActivity();
            }else{
                closePopup(jQuery(this));
                getAccountActivity();
            }
        });
        
        jQuery('body').on('click', '.getAllMessage', function(){
            if(!checkUserMenu(jQuery(this))){
                getAllMessage();
            }else{
                closePopup(jQuery(this));
                getAllMessage();
            }
        });
        
        jQuery('body').on('click', '.getDomains', function(){
            if(!checkUserMenu(jQuery(this))){
                getDomainsData();
            }else{
                closePopup(jQuery(this));
                getDomainsData();
            }
        });
        
        jQuery('body').on('click', '.getVHost', function(){
            if(!checkUserMenu(jQuery(this))){
                getVHostData();
            }else{
                closePopup(jQuery(this));
                getVHostData();
            }
        });
        
        jQuery('body').on('click', '.getArchivedTickets', function(e){
            NProgress.start();
            e.stopPropagation();
            createPopup('Архив', 'archived_tickets', false);
            popup_body_archived_tickets = jQuery('.popup.archived_tickets .body');
            jQuery('.overlay.archived_tickets').fadeIn(400, function(){
                jQuery('.popup.archived_tickets').fadeIn(400, function(){
                    popup_body_archived_tickets.html(img_loader);
                    getArchivedTickets();
                });
            });
        });
        
        jQuery('body').on('click', '.popup .close', function(){
            closePopup(jQuery(this));
        });
        
        jQuery('body').on('click', '.create_ticket, a[href="index.php?option=com_ksenmart&view=account&layout=ticket_create"]', function(e){
            e.preventDefault();
            NProgress.start();
            createPopup('Создать тикет', 'ticket_create', true);
            popup_ticket_create = jQuery('.popup.ticket_create');
            jQuery('.overlay.ticket_create').fadeIn(400, function(){
                popup_ticket_create.fadeIn(400, function(){
                    jQuery(this).children('.body').html(img_loader);
                    getTicketCreate();
                });
            });
        });
        
        jQuery('body').on('click', '.l-avatar_load', function(e){
            e.stopPropagation();
            e.preventDefault();
            NProgress.start();
            createPopup('Загрузить аватар', 'avatar_load', true);
            popup_avatar_load = jQuery('.popup.avatar_load');
            jQuery('.overlay.avatar_load').fadeIn(400, function(){
                popup_avatar_load.fadeIn(400, function(){
                    jQuery(this).children('.body').html(img_loader);
                    getAvatarLoad();
                });
            });
        });
        
        jQuery('body').on('click', '.l-rerun_domain', function(){
            if(jQuery(this).hasClass('disable')){
                return;
            }
            NProgress.start();
            createPopup('Оплатить', 'rerun_domain', false);
            popup_rerun_domain = jQuery('.popup.rerun_domain');
            jQuery('.overlay.rerun_domain').fadeIn(400, function(){
                popup_rerun_domain.fadeIn(400, function(){
                    jQuery(this).children('.body').html(img_loader);
                    getRerunDomain();
                });
            });
        });
        
        jQuery('body').on('click', '.js-domaincontact_create', function(){
            NProgress.start();
            createPopup('Создание анкеты ', 'domaincontact_create', true);
            popup_domaincontact_create = jQuery('.popup.domaincontact_create');
            jQuery('.overlay.domaincontact_create').fadeIn(400, function(){
                popup_domaincontact_create.fadeIn(400, function(){
                    jQuery(this).children('.body').html(img_loader);
                    getDomainContactCreate();
                });
            });
        });
        
        jQuery('body').on('click', '.l-renew_domain', function(){
            if(jQuery(this).hasClass('disable')){
                return;
            }
            NProgress.start();
            getRenewDomain();
        });
        
        jQuery('body').on('click', '.l-domain_edit', function(){
            if(jQuery(this).hasClass('disable')){
                return;
            }
            var click = jQuery(this);
            NProgress.start();
            createPopup('Редактирование домена', 'domain_edit', true);
            popup_domain_edit = jQuery('.popup.domain_edit');
            jQuery('.overlay.domain_edit').fadeIn(400, function(){
                popup_domain_edit.fadeIn(400, function(){
                    jQuery(this).children('.body').html(img_loader);
                    getDomainEdit(click);
                });
            });
        });
        
        jQuery('body').on('click', '.new_credit-l', function(e){
            e.stopPropagation();
            NProgress.start();
            createPopup('Создать платеж', 'new_credit', true);
            popup_body_new_credit = jQuery('.popup.new_credit .body');
            jQuery('.overlay.new_credit').fadeIn(400, function(){
                jQuery('.popup.new_credit').fadeIn(400, function(){
                    popup_body_new_credit.html(img_loader);
                    getNewCredit();
                });
            });
        });
        
        jQuery('body').on('click', '.new_domain-l', function(e){
            e.stopPropagation();
            NProgress.start();
            createPopup('Создать домен', 'new_domain', false);
            popup_body_new_domain = jQuery('.popup.new_domain .body');
            jQuery('.overlay.new_domain').fadeIn(400, function(){
                jQuery('.popup.new_domain').fadeIn(400, function(){
                    popup_body_new_domain.html(img_loader);
                    getNewDomain();
                });
            });
        });
        
        jQuery('body').on('click', '.new_vhost-l', function(e){
            e.stopPropagation();
            NProgress.start();
            createPopup('Создать хостинг', 'new_vhost', true);
            popup_body_new_vhost = jQuery('.popup.new_vhost .body');
            jQuery('.overlay.new_vhost').fadeIn(400, function(){
                jQuery('.popup.new_vhost').fadeIn(400, function(){
                    popup_body_new_vhost.html(img_loader);
                    getNewVhost();
                });
            });
        });
        
        jQuery('body').on('click', '.archived-l', function(e){
            e.stopPropagation();
            openCloseTickets(jQuery(this), 'moveFromArchive');
        });
        
        jQuery('body').on('click', '.open_tickets-l', function(e){
            e.stopPropagation();
            openCloseTickets(jQuery(this), 'moveToArchive');
        });
        
        jQuery('.user_profile-l').on('click', function(){
            NProgress.start();
            createPopup('Личный кабинет', 'profile', false);
            popup_profile = jQuery('.popup.profile');
            jQuery('.overlay.profile').fadeIn(400, function(){
                jQuery('.popup.profile').fadeIn(400, function(){
                    jQuery(this).children('.body').html(img_loader);
                    getProfile();
                });
            });
        });
        
        jQuery('body').on('click', '.settings-l', function(e){
            e.stopPropagation();
            NProgress.start();
            createPopup('Настройки', 'settings', true);
            popup_body_settings = jQuery('.popup.settings .body');
            jQuery('.overlay.settings').fadeIn(400, function(){
                jQuery('.popup.settings').fadeIn(400, function(){
                    popup_body_settings.html(img_loader);
                    getSettings();
                });
            });
        });
        
        jQuery('body').on('click', '.remove_payment-l', function(e){
            removeListItem(jQuery(this), 'removePayments');
        });
        
        jQuery('body').on('click', '.payment-l', function(){
            creditPay(jQuery(this));
        });
        
        jQuery('body').on('click', '.remove_vhost-l', function(e){
            removeListItem(jQuery(this), 'removeVhosts');
        });
        
        jQuery('body').on('dblclick', '.content.credits .list tbody tr td', function(e){
            e.stopPropagation();
            creditPay(jQuery(this));
        });
        
        jQuery('body').on('dblclick', '.content.domains .list tbody tr td', function(e){
            e.stopPropagation();
            NProgress.start();
            var click = jQuery(this);
            createPopup('Редактирование домена', 'domain_edit', true);
            popup_domain_edit = jQuery('.popup.domain_edit');
            jQuery('.overlay.domain_edit').fadeIn(400, function(){
                popup_domain_edit.fadeIn(400, function(){
                    jQuery(this).children('.body').html(img_loader);
                    getDomainEdit(click);
                });
            });
        });
        
        jQuery('body').on('click', '.content.tickets_list .list tbody tr td, .content.archived_tickets .list tbody tr td', function(e){
            var td2 = jQuery(this).hasClass('titleTableItem');

            if(td2){
                e.stopPropagation();
                var ticket_id = jQuery(this).parent().data().id;
                
                NProgress.start();
                createPopup('Сообщение', 'ticket', false);
                popup_body_ticket = jQuery('.popup.ticket .body');
    
                jQuery('.popup.ticket').fadeIn(400, function(){
                    popup_body_ticket.html(img_loader);
                    getTicket(ticket_id);
                });
            }
        });
        
        jQuery('body').on('click', '.content .list tbody tr', function(e){
            var tr      = jQuery(this);
            var allTr   = tr.parents('table').find('tr.active');
            
    		if(tr.is('.active')){
    			tr.removeClass('active');
    		}else{
    			if (!ctrl){
    				allTr.removeClass('active');
                }
    			tr.addClass('active');
    		}
            
            var countActive = tr.parents('.content ').find('.list tbody tr.active').length;
    		if (countActive > 0){
                tr.parents('.content').find('.toggleLink').removeClass('disable');
    		}else{
                tr.parents('.content').find('.toggleLink').addClass('disable');
            }
                        
            if (countActive == 1){
                tr.parents('.content').find('.singleLink').removeClass('disable');
            }else if(countActive == 0 || countActive > 1){
                tr.parents('.content').find('.singleLink').addClass('disable');
            }
        });
        
        jQuery('body').on('click', '.content .disable, .content .btn-dsbl', function(e){
            e.stopPropagation();
        });
        
        jQuery('body').on('click', '.go_vhost-l', function(e){
            /*var tr = jQuery(this).parents('.popup').find('table').find('tr.active');
            var elid     = tr.data().id;
            var username = tr.data().username;
*/
            //var link    = 'https://ldmco.ru/mancgi/goserver?elid='+elid+'&username='+username+'&key='+key+'&func=auth&checkcookie=no&lang=ru';
            //var link    = 'https://78.46.70.46/manager/ispmgr?username='+username+'&key='+key+'&func=auth&checkcookie=no&lang=ru';
            var link    = 'https://78.46.70.46/manager/ispmgr?lang=ru';
            var wndws   = window.open(link);
        });
        
        function creditPay(el){
            
            if(el.parent().hasClass('dblclick')){
                var tr = el.parent();
                var elid  = tr.data().id;
                var type  = tr.data().type;
                var state = tr.data().state;
            }else{
                var elid  = el.parents('.content').find('.list tbody tr.active').data().id;
                var type  = el.parents('.content').find('.list tbody tr.active').data().type;
                var state = el.parents('.content').find('.list tbody tr.active').data().state;
            }

            if(state != 'оплачен'){
                if(type == 'qiwi'){
                    NProgress.start();
                    createPopup('Оплатить', 'qiwi_pay', true);
                    popup_body_credit_qiwi_pay = jQuery('.popup.qiwi_pay .body');
                    jQuery('.overlay.qiwi_pay').fadeIn(400, function(){
                        jQuery('.popup.qiwi_pay').fadeIn(400, function(){
                            popup_body_credit_qiwi_pay.html(img_loader);
                            getQiwiPay(elid);
                        });
                    });
                }else{
                    var link    = 'https://ldmco.ru/mancgi/roboxpayment?elid='+elid+'&auth='+auth;
                    var wndws   = window.open(link);
                }
                return true;
            }
            return false;
        }
        
        function openCloseTickets(e, action){
            var flag = false;
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.'+action;
            var ids = [];
            var tr = e.parents('.content').find('.list tbody tr.active');
            
            if(tr.length == 0){
                var tr = e;
                flag = true;
            }

            tr.each(function(){
                ids.push(jQuery(this).data().id);
            });
            
            if(ids.length > 0){
    			$.ajax({
    				type: 'POST',
                    url: href,
                    data: {ids:ids},
                    beforeSend: function(){
                        NProgress.inc();
                    },
    				success: function(data){
                        if(data != ''){
                            
                        }else{
                            if(flag){
                                tr.parents('tr').fadeOut(400, function(){jQuery(this).remove();});
                            }else{
                                tr.fadeOut(400, function(){jQuery(this).remove();});
                            }
                        }
                        NProgress.done();
                        tr.removeClass('active');
                        tr.parents('.content').find('.toggleLink').addClass('disable');
    				}
    			});
            }
        }

        function removeListItem(e, action){
            var tr      = e.parents('.popup').find('.list tbody tr.active');
            var href    = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.'+action;
            var ids     = [];
            
            tr.each(function(){
                ids.push(jQuery(this).data().id);
            });
            
            if(ids.length > 0){
    			$.ajax({
    				type: 'POST',
                    url: href,
                    data: {ids:ids},
                    beforeSend: function(){
                        NProgress.inc();
                    },
    				success: function(data){
                        if(data != ''){
                            
                        }else{
                            tr.fadeOut(400, function(){jQuery(this).remove();});
                        }
                        tr.removeClass('active');
                        tr.parents('.content').find('.toggleLink').addClass('disable');
                        NProgress.done();
    				}
    			});
            }
        }
        
        function getAvatarLoad(){
            var href = root+'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=avatar_load';
            var data = '';

            jQuery.ajax({
                type: 'POST',
                url: href,
                data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
                success: function(data){
                    popup_avatar_load.children('.body').html(data);
                    NProgress.done();
                }
            });
        }
        
        function getSettings(){
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=settings';
            var data = '';

			$.ajax({
                type: 'POST',
				url: href,
				data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
				success: function(data){
				    popup_body_settings.html(data);
                    NProgress.done();
				}
			});
        }
        
        function getVHost(){
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=vhost';
            var data = '';

			$.ajax({
                type: 'POST',
				url: href,
				data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
				success: function(data){
				    popup_body_vhosts.html(data);
                    popupBlockResize(popup_body_vhosts.find('.list'));
                    NProgress.done();
				}
			});
        }
        
        function getArchivedTickets(){
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=archived_tickets';
            var data = '';

			$.ajax({
                type: 'POST',
				url: href,
				data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
				success: function(data){
				    popup_body_archived_tickets.html(data);
                    popupBlockResize(popup_body_archived_tickets.find('.list'));
                    NProgress.done();
				}
			});
        }
        
        function getProfile(){
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=profile';
            var data = '';
            
			$.ajax({
                type: 'POST',
				url: href,
				data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
				success: function(data){
				    popup_profile.children('.body').html(data);
                    NProgress.done();
				}
			});
        }
        
        function getTicketCreate(){
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=ticket_create';
            var data = {popup: 'popup'};
            
			$.ajax({
                type: 'POST',
				url: href,
				data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
				success: function(data){
				    console.log(data.length);
                    if(data.length == 78){
                        data = JSON.parse(data);
                        window.location.href = data.redirect;
                    }else{
                        popup_ticket_create.children('.body').html(data);
                    }
                    NProgress.done();
				}
			});
        }
        
        function getDomainContactCreate(){
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=default_domaincontact_create';
            
			$.ajax({
                type: 'POST',
				url: href,
                beforeSend: function(){
                    NProgress.inc();
                },
				success: function(data){
				    popup_domaincontact_create.children('.body').html(data);
                    popupBlockResize(popup_domaincontact_create.find('.list'));
                    NProgress.done();
				}
			});
        }
        
        function getRerunDomain(){
            var tr      = popup_body_domains.find('.list tbody tr.active');
            var elid    = tr.data().id;
            
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=domain_rerun';
            var data = {elid: elid};
            
			$.ajax({
                type: 'POST',
				url: href,
				data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
				success: function(data){
				    popup_rerun_domain.children('.body').html(data);
                    NProgress.done();
				}
			});
        }
        
        function getRenewDomain(){
            var tr      = popup_body_domains.find('.list tbody tr.active');
            var elid    = tr.data().id;
            
            var data = {elid: elid};
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&task=account.setDomainRenew';
    
            jQuery.ajax({
                type: "POST",
    			url:href,
                data:data,
                dataType: 'json',
                beforeSend: function(){
                    NProgress.inc();
                },
    			success: function(data){
                    console.log(data);
    			    if(data.result == 'OK'){
                        
    			    }else{
                        createPopupNotice(data.error.msg, '.domains');
    			    }
                    NProgress.done();
    			}
    		});
            /*var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=domain_renew';
            var data = {elid: elid};
            
			$.ajax({
                type: 'POST',
				url: href,
				data: data,
                beforeSend: function(){
                    NProgress.start();
                },
				success: function(data){
				    popup_renew_domain.children('.body').html(data);
				},
                complete: function(){
                    NProgress.done();
                }
			});*/
        }
        
        function getDomainEdit(el){
            if(el.parent().hasClass('dblclick')){
                var tr    = el.parent();
                var elid  = tr.data().id;
            }else{
                var elid    = el.parents('.content').find('.list tbody tr.active').data().id;;
            }
            
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=domain_edit';
            var data = {elid: elid};
            
			$.ajax({
                type: 'POST',
				url: href,
				data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
				success: function(data){
				    popup_domain_edit.children('.body').html(data);
                    NProgress.done();
				}
			});
        }
        
        function getNewCredit(){
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=credit_create';
            var data = '';
            
			$.ajax({
                type: 'POST',
				url: href,
				data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
				success: function(data){
				    popup_body_new_credit.html(data);
                    NProgress.done();
				}
			});
        }
        
        function getQiwiPay(elid){
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=credit_qiwi_pay';
            var data = {elid: elid};
            
			$.ajax({
                type: 'POST',
				url: href,
				data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
				success: function(data){
				    popup_body_credit_qiwi_pay.html(data);
                    NProgress.done();
				}
			});
        }
        
        function getNewDomain(){
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=domain_create';
            var data = '';
            
			$.ajax({
                type: 'POST',
				url: href,
				data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
				success: function(data){
				    popup_body_new_domain.html(data);
                    NProgress.done();
				}
			});
        }
        
        function getNewVhost(){
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=vhost_create';
            var data = '';
            
			$.ajax({
                type: 'POST',
				url: href,
				data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
				success: function(data){
				    popup_body_new_vhost.html(data);
                    NProgress.done();
				}
			});
        }
                        
        function getTicketsList(){
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=tickets_list';
            var data = '';
            
			$.ajax({
                type: 'POST',
				url: href,
				data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
				success: function(data){
				    popup_body_tickets.html(data);
                    popupBlockResize(popup_body_tickets.find('.list'));
                    NProgress.done();
				}
			});
        }

        function getCreditsList(){
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=credits_list';
            var data = '';
            
			$.ajax({
                type: 'POST',
				url: href,
				data: data,
                beforeSend: function(){
                    NProgress.inc();
                },
				success: function(data){
				    popup_body_credits.html(data);
                    popupBlockResize(popup_body_credits.find('.list'));
                    NProgress.done();
				}
			});
        }
        
        function getTicket(ticket_id){
            var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=ticket';
			$.ajax({
                type: 'POST',
                url: href,
                data: {elid:ticket_id},
                beforeSend: function(){
                    NProgress.inc();
                },
                success: function(data){
                    popup_body_ticket.html(data);
                    NProgress.done();
                }
			});
        }
        
        /**
         * Гет даты по клику
         * * * * * * * НАЧАЛО * * * * * * 
         */
         
        function getAccountActivity(){
            NProgress.start();
            createPopup('Платежи', 'credits', false);
            popup_body_credits = jQuery('.popup.credits .body');
            jQuery('.overlay.credits').fadeIn(400, function(){
                jQuery('.popup.credits').fadeIn(400, function(){
                    popup_body_credits.html(img_loader);
                    getCreditsList();
                });
            });
        }
        
        function getAllMessage(){
            NProgress.start();
            createPopup('Список сообщений', 'tickets', false);
            popup_body_tickets = jQuery('.popup.tickets .body');
            jQuery('.overlay.tickets').fadeIn(400, function(){
                jQuery('.popup.tickets').fadeIn(400, function(){
                    popup_body_tickets.html(img_loader);
                    getTicketsList();
                });
            });
        }
        
        function getVHostData(){
            NProgress.start();
            createPopup('Хостинг', 'vhosts', false);
            popup_body_vhosts = jQuery('.popup.vhosts .body');
            jQuery('.overlay.vhosts').fadeIn(400, function(){
                jQuery('.popup.vhosts').fadeIn(400, function(){
                    popup_body_vhosts.html(img_loader);
                    getVHost();
                });
            });
        }
        
        function getDomainsData(){
            NProgress.start();
            createPopup('Список доменов', 'domains', false);
            popup_body_domains = jQuery('.popup.domains .body');
            jQuery('.overlay.domains').fadeIn(400, function(){
                jQuery('.popup.domains').fadeIn(400, function(){
                    popup_body_domains.html(img_loader);
                    getDomains();
                });
            });
        }
        /**
         * Гет даты по клику
         * * * * * * * КОНЕЦ * * * * * * 
         */
    });
    
    function getDomains(order_field){
        var href = 'index.php?option=com_ksenmart&view=account&tmpl=ksenmart&layout=domains';
        var data = {order_field: order_field};

		$.ajax({
            type: 'POST',
			url: href,
			data: data,
			success: function(data){
			    popup_body_domains.html(data);
                popupBlockResize(popup_body_domains.find('.list'));
                NProgress.done();
                return true;
			}
		});
        return true;
    }