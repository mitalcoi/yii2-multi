$(document).on("click", ".delete-multi", function () {
    $(this).parent().remove();
    return false;
});
$(function () {
    if ($("ul.multi-ul li").length===0) {
        var id = $("ul.multi-ul").parent().attr("id");
        addMulti(id);
    }
});
function addMulti(multi_relation_id) {
    var url = $("#" + multi_relation_id + " input.multi_url").val();
    $.ajax({
        url: url,
        type: "get",
        data: {index: $("#" + multi_relation_id + " li").size()},
        cache: false,
        dataType: "html",
        success: function (html) {
            $("#" + multi_relation_id + " ul").append(html);
        }
    });
}
