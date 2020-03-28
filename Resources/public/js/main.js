$(function() {

    var $advancedFields = $("[data-advanced-field]").closest(".control-group");
    if ($advancedFields.length && $advancedFields.find(".form-errors").size() === 0) {
        $advancedFields.hide();
    }
    $("[data-action='toggle-advanced-fields']").click(function(e) {
        e.preventDefault();
        $advancedFields.slideToggle();
    });

    $(".delete").click(function(e) {
        var modal = $("#deleteRedirect");
        modal.css("opacity","1");
        modal.css("display","block");
        modal.css("position","relative");
        modal.find("form").attr("action", $(this).attr("data-deleteurl"));
        modal.find("button").click(function () {
            e.preventDefault();
            e.stopPropagation();
            modal.find("form").attr("action","");
            modal.css("opacity","0");
            modal.css("display","none");
            modal.css("position","fixed");
        });
    });

});