/* Search bar show/hide */
$("#search input[type=text]").on({
  mouseenter: function() {
    $(this).stop().animate({
      backgroundColor: "#FFF",
      borderTopColor: "#D1D1D1",
      borderRightColor: "#d1d1d1",
      borderBottomColor: "#d1d1d1",
      borderLeftColor: "#d1d1d1",
      paddingLeft: 20,
      right: 0,
      width: 180
    }, 500);
  },
  mouseleave: function() {
    $(this).blur();
    
    $(this).stop().animate({
      backgroundColor: "transparent",
      borderTopColor: "transparent",
      borderRightColor: "transparent",
      borderBottomColor: "transparent",
      borderLeftColor: "transparent",
      paddingLeft: 25,
      right: "-6px",
      width: 0
    }, 500);
  }
});