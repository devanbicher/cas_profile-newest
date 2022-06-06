/**
 * @fileC
 * Conatins JS function to show correct tab info on profile page
 */

(function ($, Drupal, drupalSettings) {
  "use strict";
  var originalTitle = document.title;
  var url = window.location.pathname;
  var tab = window.location.hash.substr(1);
  if (tab == '') {
    tab = drupalSettings.tabselect.pagename;
  }
  var pagename = url.split("/").pop();

  Drupal.behaviors.jsTabSelect = {
    attach: function (context, settings) {
      
      document.title = originalTitle + " - " + capitalize(tab);

      $(".tab-content--" + tab).once("jsTabSelect").addClass("visible");
      $('#' + tab).addClass('active');

      $("#field-tabs").once("jsTabSelect").bind("click", (event) => {
          if (event.target.className === "profile-button") {
            if (tab != event.target.id) {
                $('.profile-button').removeClass('active');
                $(event.target).addClass('active');
                tab = event.target.id;
                history.pushState(null, "", [pagename + "#" + tab]);
                document.title = originalTitle + " - " + capitalize(tab);
                $(".tab-content").removeClass("visible");
                $(".tab-content--" + tab).addClass("visible");
            }
          }
        });

        $(window).once("jsTabSelect").bind('popstate', (event) => {
            tab = window.location.hash.substring(1);
            $('.profile-button').removeClass('active');
            $('#' + tab).addClass('active');
            $(".tab-content").removeClass("visible");
            $(".tab-content--" + tab).addClass("visible");
        });
    },
  };
  function capitalize(word) {
    const lower = word.toLowerCase();
    return word.charAt(0).toUpperCase() + lower.slice(1);
  }

})(jQuery, Drupal, drupalSettings);
