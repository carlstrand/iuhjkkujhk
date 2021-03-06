/**
 * @category    CleverSoft
 * @package     CleverLayeredNavigation
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */
define([
    "jquery",
    "jquery/ui",
    "CleverSoft_CleverLayeredNavigation/js/cleverLayeredNavigation",
    "productListToolbarForm"
], function ($) {
    'use strict';
    $.widget('mage.cleverLayeredNavigationAjax',{
        options:{
            _isCleshopbyAjaxProcessed: false
        },
        _create: function (){
            var self = this;
            $(function(){
                self.initAjax();
                if (typeof window.history.replaceState === "function") {
                    window.history.replaceState({url: document.URL}, document.title);
                    setTimeout(function() {
                        window.onpopstate = function(e){
                            if(e.state){
                                self.updateContent(e.state.url, false);
                            }
                        };
                    }, 0)
                }
            });

        },

        updateContent: function(link, isPushState){
            var self = this;
            $("#cleversoft-shopby-overlay").show();
            if (typeof window.history.pushState === 'function' && isPushState) {
                window.history.pushState({url: link}, '', link);
            }
            $.ajax({
                method: "POST",
                url: link,
                dataType:'json',
                data: { isAjax: 1, CLN: 1 }
            }).done(function(data){
                $('#zoo-layer-navigation').parent('.zoo-sidebar-main').html(data.navigation);
                $('#zoo-layer-navigation,#shop-sidebar-left,#layered-filter-block').trigger('contentUpdated');
                $('#cleversoft-shopby-product-list').replaceWith(data.categoryProducts);
                $('#cleversoft-shopby-product-list').trigger('contentUpdated');

                $('#page-title-heading').parent().replaceWith(data.h1);
                $('#page-title-heading').trigger('contentUpdated');

                $('title').html(data.title);
                if(data.categoryData != '') {
                    if($(".category-view").length == 0) {
                        $('<div class="category-view"></div>').insertAfter('.page.messages');
                    }
                    $(".category-view").replaceWith(data.categoryData);
                }
                $("#cleversoft-shopby-overlay").hide();
                if(window.quickViewModal) window.quickViewModal();
                //if(window.infiniteScrollListing) window.infiniteScrollListing();
                if(window.listingContainer && window.infiniteScrollListing && $.fn.infinitescroll) {
                    $(window).unbind('.infscr');
                    $(window).unbind('scroll');
                    $('#'+window.listingContainer).infinitescroll('destroy');
                    $('#'+window.listingContainer).infinitescroll({
                        loading: {
                            img:window.imgInf,
                            msgText: window.msgTextInf
                        },
                        navSelector: ".toolbar-bottom",
                        // selector for the paged navigation (it will be hidden)
                        nextSelector: "a.next:last",
                        // selector for the NEXT link (to page 2)
                        itemSelector: ".product.product-item",
                        // selector for all items you'll retrieve
                        bufferPx:window.bufferPxInf,
                        extraScrollPx: 0,
                        behavior: window.behavior ? window.behavior : undefined,
                        maxPage: window.lastpage ? window.lastpage : undefined
                    }, function (newElements, data, opt) {
                        //var path = data.path[0] + data.state.currPage;
                        var $newElems = $(newElements).hide(); // hide to begin with
                        // ensure that images load before adding to masonry layout
                        //                        $newElems.imagesLoaded(function () {
                        $newElems.fadeIn(); // fade in when ready
                        $('#'+window.listingContainer).append($newElems);
                        $('#'+window.listingContainer).attr('data-mage-init', JSON.stringify({'equalHeight': {'target': ' .product-item .product-item-info'}}));
                        $('#'+window.listingContainer).trigger('contentUpdated');
                        $('.pager.clever-infinite-scroll .action.next').show();
                        if(data.state.currPage == window.lastpage) {
                            $(window).unbind('.infscr');
                            $(window).unbind('scroll');
                            $('.toolbar-bottom .pager.clever-infinite-scroll').html('<span class="clever-end-loaded">All products loaded.</span>');
                            $('.toolbar-bottom, .toolbar-bottom .pager.clever-infinite-scroll').show();
                        }
                        $('#infscr-loading').remove();
                        //                        });

                    });
                }
                if(window.shopSidebarLeftToggle) window.shopSidebarLeftToggle();
                if(window.is_stickey) window.is_stickey();
                if(window.shopSidebarRightToggle) window.shopSidebarRightToggle();
				if(window.shopSidebarWithoutLeftRightToggle) window.shopSidebarWithoutLeftRightToggle();
                self.initAjax();
            });
        },

        initAjax: function()
        {
            var self = this;
            $.mage.cleverLayeredNavigationFilterAbstract.prototype.apply = function(link){
                self.updateContent(link, true);
            }
            self.options._isCleshopbyAjaxProcessed = false;
            $.mage.productListToolbarForm.prototype.changeUrl = function (paramName, paramValue, defaultValue) {
                if(self.options._isCleshopbyAjaxProcessed) {
                    return;
                }
                self.options._isCleshopbyAjaxProcessed = true;
                var urlPaths = self.options.url.split('?'),
                    baseUrl = urlPaths[0],
                    urlParams = urlPaths[1] ? urlPaths[1].split('&') : [],
                    paramData = {},
                    parameters;
                for (var i = 0; i < urlParams.length; i++) {
                    parameters = urlParams[i].split('=');
                    paramData[parameters[0]] = parameters[1] !== undefined
                        ? window.decodeURIComponent(parameters[1].replace(/\+/g, '%20'))
                        : '';
                }
                paramData[paramName] = paramValue;
                if (paramValue == defaultValue) {
                    delete paramData[paramName];
                }
                paramData = $.param(paramData);

                self.updateContent(baseUrl + (paramData.length ? '?' + paramData : ''), true);
            }
            var changeFunction = function(e){
                var link = $(this).prop('href') ? $(this).prop('href') : $(this).val();
                self.updateContent(link, true);
                e.stopPropagation();
                e.preventDefault();
            };

            $(".swatch-option-link-layered").bind('click', changeFunction);
            $(".filter-current a").bind('click',changeFunction);
            $(".filter-actions a").bind('click', changeFunction);
            $(".toolbar .pages a").bind('click', changeFunction);
            $(".toolbar-sorter #sorter").on('change', changeFunction);
        }
    });

});
