$(document).ready(function() {
    $(".table-sortable").tablesorter({
        headers: {
            // Disable actions row
            3: {
                sorter: false,
            },
        },
    });

    $('.table-sortable th:not(.disabled)').click(function() {
        $('.table-sortable th').each(function() {
            $(this).removeClass('active');
            // Remove carets
        });

        $(this).addClass('active');
        // Add caret
    });
});
