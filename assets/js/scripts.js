// Custom JavaScript for dynamic elements

$(document).ready(function() {
    // Toggle navbar for mobile
    $(".navbar-toggler").click(function() {
        $(".navbar-collapse").toggle();
    });

    // Smooth scroll for anchor links
    $("a[href^='#']").on('click', function(e) {
        e.preventDefault();
        var target = this.hash;
        $('html, body').animate({
            scrollTop: $(target).offset().top
        }, 800);
    });

    // Example of handling dynamic modal functionality
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus');
    });
});
