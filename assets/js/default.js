(function($) {
  "use strict"; // Start of use strict

  // Show the navbar when the page is scrolled up
  var MQL = 992;

  //primary navigation slide-in effect
  if ($(window).width() > MQL) {
    var headerHeight = $("#mainNav").height();
    $(window).on(
      "scroll",
      {
        previousTop: 0
      },
      function() {
        var currentTop = $(window).scrollTop();
        //check if user is scrolling up
        if (currentTop < this.previousTop) {
          //if scrolling up...
          if (currentTop > 0 && $("#mainNav").hasClass("is-fixed")) {
            $("#mainNav").addClass("is-visible");
          } else {
            $("#mainNav").removeClass("is-visible is-fixed");
          }
        } else if (currentTop > this.previousTop) {
          //if scrolling down...
          $("#mainNav").removeClass("is-visible");
          if (currentTop > headerHeight && !$("#mainNav").hasClass("is-fixed"))
            $("#mainNav").addClass("is-fixed");
        }
        this.previousTop = currentTop;
      }
    );
  }
})(jQuery); // End of use strict

jQuery.validator.setDefaults({
  errorElement: "div",
  errorPlacement: function(error, element) {
    error.addClass("invalid-feedback");
    element.closest(".form-group").append(error);
  },
  highlight: function(element, errorClass, validClass) {
    $(element).addClass("is-invalid");
  },
  unhighlight: function(element, errorClass, validClass) {
    $(element).removeClass("is-invalid");
  }
});

$.fn.select2.defaults.set("theme", "bootstrap4");

moment().locale("es");
