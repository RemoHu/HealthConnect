var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
function encode64(input) {
  var output = "";
  var chr1, chr2, chr3;
  var enc1, enc2, enc3, enc4;
  var i = 0;
  do {
    chr1 = input.charCodeAt(i++);
    chr2 = input.charCodeAt(i++);
    chr3 = input.charCodeAt(i++);
    enc1 = chr1 >> 2;
    enc2 = (chr1 & 3) << 4 | chr2 >> 4;
    enc3 = (chr2 & 15) << 2 | chr3 >> 6;
    enc4 = chr3 & 63;
    if (isNaN(chr2)) {
      enc3 = enc4 = 64;
    } else {
      if (isNaN(chr3)) {
        enc4 = 64;
      }
    }
    output = output + keyStr.charAt(enc1) + keyStr.charAt(enc2) + keyStr.charAt(enc3) + keyStr.charAt(enc4);
  } while (i < input.length);
  return output;
}
function decode64(input) {
  var output = "";
  var chr1, chr2, chr3;
  var enc1, enc2, enc3, enc4;
  var i = 0;
  input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
  do {
    enc1 = keyStr.indexOf(input.charAt(i++));
    enc2 = keyStr.indexOf(input.charAt(i++));
    enc3 = keyStr.indexOf(input.charAt(i++));
    enc4 = keyStr.indexOf(input.charAt(i++));
    chr1 = enc1 << 2 | enc2 >> 4;
    chr2 = (enc2 & 15) << 4 | enc3 >> 2;
    chr3 = (enc3 & 3) << 6 | enc4;
    output = output + String.fromCharCode(chr1);
    if (enc3 != 64) {
      output = output + String.fromCharCode(chr2);
    }
    if (enc4 != 64) {
      output = output + String.fromCharCode(chr3);
    }
  } while (i < input.length);
  return output;
}
function anim2(imgObj, url) {
  imgObj.src = url;
}
function anim(name, type) {
  if (type == 0) {
    document.images[name].src = "/images/" + name + ".gif";
  }
  if (type == 1) {
    document.images[name].src = "/images/" + name + "_over.gif";
  }
  if (type == 2) {
    document.images[name].src = "/images/" + name + "_down.gif";
  }
}
function checkAll(cbox, formObj) {
  var i = 0;
  if (cbox.checked == true) {
    cbox.checked == false;
  } else {
    cbox.checked == true;
  }
  while (formObj.elements[i] != null) {
    formObj.elements[i].checked = cbox.checked;
    i++;
  }
}
function checkEvent(formObj) {
  var key = -1;
  var shift;
  key = event.keyCode;
  shift = event.shiftKey;
  if (!shift && key == 13) {
    formObj.submit();
  }
}
function show(block) {
  theBlock = document.getElementById(block);
  if (theBlock.style.display == "none") {
    theBlock.style.display = "block";
  } else {
    theBlock.style.display = "none";
  }
}
function confirmClick(message, href) {
  if (confirm(message)) {
    location.href = href;
  } else {
    return false;
  }
}
function showLoadImg(cmd, img_id) {
  loadImg = document.getElementById(img_id);
  if (cmd == "show") {
    loadImg.style.opacity = 1;
    loadImg.style.filter = "alpha(opacity=100)";
  } else {
    loadImg.style.opacity = 0;
    loadImg.style.filter = "alpha(opacity=0)";
  }
}
if (typeof jQuery != "undefined") {
  var tk_twitter_pop = function(text, event) {
    window.open("http://twitter.com/intent/tweet?text=" + text, "", "width=400px,height=550px,left=" + (event.pageX - 300) + "px,top=" + (event.pageY - 300) + "px");
  };
  var validate_addons = function(formObj) {
    var e = formObj.elements;
    var val_arr = "";
    for (x in e) {
      if (typeof e[x].value != "undefined" && e[x].value != "") {
        val_arr += "&" + e[x].name + "=" + e[x].value;
      }
    }
    jQuery.get(sl_includes_base + "/update-keys.php?" + val_arr, function(data) {
      jQuery("#validation_status div").html(data);
      jQuery("#validation_status_link").click();
      showLoadImg("stop", "module-keys");
      setTimeout(function() {
        location.reload();
      }, 1E4);
    });
  };
  var level3_links = function(sublink_obj, mainlink_id, show) {
    if (typeof tsn_link_arr[sublink_obj.id] != "undefined") {
      l3n = document.getElementById("level3_nav");
      if (show == "show") {
        jQuery("#level3_nav").hover(function() {
          l3n.style.position = "absolute";
          l3n.style.width = document.getElementById("inner_div").offsetWidth + "px";
          l3n.style.left = document.getElementById(mainlink_id).offsetLeft + "px";
          l3n.style.top = document.getElementById(mainlink_id).offsetTop + document.getElementById(mainlink_id).offsetHeight + document.getElementById("top_sub_nav").offsetHeight - 0 + "px";
          l3n.style.visibility = "visible";
          jQuery("#level3_nav").html(tsn_link_arr[sublink_obj.id]).fadeIn();
        }, function() {
          jQuery("#level3_nav").html(tsn_link_arr[sublink_obj.id]).fadeOut();
        });
        l3n.style.position = "absolute";
        l3n.style.width = document.getElementById("inner_div").offsetWidth + "px";
        l3n.style.left = document.getElementById(mainlink_id).offsetLeft + "px";
        l3n.style.top = document.getElementById(mainlink_id).offsetTop + document.getElementById(mainlink_id).offsetHeight + document.getElementById("top_sub_nav").offsetHeight - 0 + "px";
        l3n.style.visibility = "visible";
        jQuery("#level3_nav").html(tsn_link_arr[sublink_obj.id]).fadeIn();
      } else {
      }
    }
  };
  var sl_top_nav = function(sl_admin_page, tab_obj, link_array, the_action) {
    if (link_array[sl_admin_page] != "") {
      tsnfC = document.getElementById("top_sub_nav").firstChild;
      tsnfC.style.visibility = "hidden";
      if (the_action == "show") {
        jQuery("#top_sub_nav div").hover(function() {
          tsnfC.style.position = "absolute";
          tsnfC.style.left = tab_obj.offsetLeft + "px";
          tsnfC.style.visibility = "visible";
          tsnfC.innerHTML = link_array[sl_admin_page];
        }, function() {
        });
        tsnfC.style.position = "absolute";
        tsnfC.style.left = tab_obj.offsetLeft + "px";
        tsnfC.style.visibility = "visible";
        tsnfC.innerHTML = link_array[sl_admin_page];
      }
    }
  };
  var sl_top_nav_init = function(link_arr) {
    if (typeof document.getElementById("top_sub_nav") != "undefined") {
      tsnfC = document.getElementById("top_sub_nav").firstChild;
      tsnfC.style.visibility = "hidden";
      tsnfC.style.position = "absolute";
      tsnfC.style.left = document.getElementById("current_top_link").offsetLeft + "px";
      tsnfC.style.visibility = "visible";
    }
    $tnl = jQuery(".top_nav_li");
    jQuery.each($tnl, function($key, $val) {
      $val.onmouseover = function() {
        sl_top_nav($val.className.replace("top_nav_li ", ""), this, link_arr, "show");
      };
    });
  };
  jQuery(document).ready(function() {
    if (typeof tsn_link_arr != "undefined") {
      sl_top_nav_init(tsn_link_arr);
    }
  });
  jQuery(document).ready(function() {
    if (jQuery("#clickme").length > 0) {
      jQuery("#clickme").toggle(function() {
        jQuery("#slideout").animate({width:"+=880px", height:"+=630px"}, {queue:false, duration:500});
        jQuery("#slidecontent").animate({width:"+=880px"}, {queue:false, duration:500, complete:function() {
          jQuery("#slidecontent div").fadeIn();
        }});
      }, function() {
        jQuery("#slidecontent div").fadeOut(function() {
          jQuery("#slideout").animate({width:"-=880px", height:"-=630px"}, {queue:false, duration:500});
          jQuery("#slidecontent").animate({width:"-=880px"}, {queue:false, duration:500});
        });
      });
    }
  });
  jQuery(document).ready(function() {
    if (jQuery("#slidecontainer").length > 0) {
      orig_html = document.getElementById("slidecontainer").innerHTML;
      jQuery(document).on("click", "#readme_button", function() {
        jQuery.get(sl_pages_base + "/readme.php", function(readme_data) {
          jQuery("#slidecontainer").fadeOut(function() {
            jQuery(this).html("<div style='overflow:scroll; height:616px; padding:7px; background-color:white; border:solid silver 1px'><a href='#' class='sl_back_to_dashboard'><b>&larr;&nbsp;Back to Dashboard</b></a><br>" + readme_data + "<br><a href='#' class='sl_back_to_dashboard'><b>&larr;&nbsp;Back to Dashboard</b></a></div>").fadeIn();
          });
        });
      });
      jQuery(document).on("click", ".sl_back_to_dashboard", function() {
        jQuery("#slidecontainer").fadeOut(function() {
          jQuery(this).html(orig_html).fadeIn();
        });
      });
      jQuery(document).on("click", "#server_caps_button", function() {
        jQuery.prettyPhoto.open("#server_caps");
      });
      jQuery(document).on("click", "#shortcode_params_button", function() {
        jQuery.prettyPhoto.open("#shortcode_params");
      });
      jQuery(document).on("click", "#env_vars_button", function() {
        jQuery.prettyPhoto.open("#env_vars");
      });
    }
  });
  if (typeof jQuery.prettyPhoto != "undefined") {
    jQuery(document).ready(function($) {
      $("a[href$='.jpg'], a[href$='.jpeg'], a[href$='.gif'], a[href$='.png'], a[rel^='sl_pop'], input[rel^='sl_pop'], img[rel^='sl_pop']").prettyPhoto({animationSpeed:"fast", padding:40, opacity:.5, showTitle:true, deeplinking:false, social_tools:false});
    });
  }
  jQuery(document).ready(function() {
    jQuery(document).on("click", ".twitter_button", function(e) {
      tk_twitter_pop(jQuery(this).attr("rel"), e);
    });
    jQuery(document).on("click", ".star_button", function() {
      window.open("http://wordpress.org/support/view/plugin-reviews/store-locator?filter=5#postform");
    });
    jQuery('#loc_table tr[id^="sl_tr-"]').mousedown(function(e) {
      $this = jQuery(this);
      $curr_id = $this.attr("id");
      $loc_id = $curr_id.split("-")[1];
      $curr_cbx = jQuery("#" + $curr_id + " input[type='checkbox']");
      if (e.target == jQuery("#edit_loc-" + $loc_id)[0] || e.target == jQuery("#del_loc-" + $loc_id)[0]) {
      } else {
        if (e.target != $curr_cbx[0]) {
          $this.toggleClass("location_selected");
          $curr_cbx[0].checked = $this.hasClass("location_selected") ? true : false;
        } else {
          if ($curr_cbx[0].checked == false) {
            $this.addClass("location_selected");
          } else {
            $this.removeClass("location_selected");
          }
        }
      }
    });
    jQuery(document).keyup(function(e) {
      $act_elem = jQuery(":focus");
      text_field_is_focused = $act_elem.attr("name") == jQuery("#mgmt_bar input:text").attr("name") || $act_elem.attr("name") == jQuery(".mng_loc_forms_links input:text").attr("name");
      if (e.keyCode == 68 && jQuery('input[name="sl_id[]"]:checked').length > 0 && location.search.indexOf("edit=") == -1 && !text_field_is_focused) {
        jQuery("#mgmt_bar input[type='button']")[0].click();
      }
    });
  });
};