!function(a){"use strict";a.fn.fusion_draw_progress=function(){var b,c=a(this);a("html").hasClass("lt-ie9")?(c.css("visibility","visible"),c.each(function(){b=c.find(".progress").attr("aria-valuenow"),c.find(".progress").css("width","0%"),c.find(".progress").animate({width:b+"%"},"slow")})):c.find(".progress").css("width",function(){return a(this).attr("aria-valuenow")+"%"})}}(jQuery),jQuery(document).ready(function(){jQuery(".fusion-progressbar").not(".fusion-modal .fusion-progressbar").each(function(){var a=getWaypointOffset(jQuery(this));jQuery(this).waypoint(function(){jQuery(this).fusion_draw_progress()},{triggerOnce:!0,offset:a})})}),jQuery(window).load(function(){jQuery(".fusion-modal .fusion-progressbar").on("appear",function(){jQuery(this).fusion_draw_progress()})});