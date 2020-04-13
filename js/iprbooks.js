var type = 'book';
$(document).ready(function () {
    // init
    send_request(type);
});

// tab
$(".ipr-tab").click(function () {
    type = $(this).data("type");
    send_request(type);
});

// filter
$("#ipr-filter-apply").click(function () {
    send_request(type);
});

// clear filter
$("#ipr-filter-clear").click(function () {
    $(".ipr-filter").val("");
    send_request(type);
});


function send_request(type, page = 0) {
    var filter = $(".ipr-filter")
        .map(function () {
            return this.id + "=" + $(this).val();
        })
        .get()
        .join('&');

    $.ajax({
        url: M.cfg.wwwroot + "/blocks/iprbooks/ajax.php?action=getlist&type=" + type + "&page=" + page + "&" + encodeURI(filter)
    }).done(function (data) {

        // hide read button
        $("#ipr-item-detail-read").hide();

        // set data
        $("#ipr-items-list").scrollTop(0);
        $("#ipr-items-list").html(data.html);

        // set details click listener
        $(".ipr-item").click(function () {
            set_details($(this).data("id"));
        });

        // pagination
        $(".ipr-page").click(function () {
            send_request(type, $(this).data('page'));
        });

        // init detail view
        set_details($(".ipr-item").data("id"));
    });
}

function set_details(id) {
    this.clear_details();
    $("#ipr-item-detail-image").html($("#ipr-item-image-" + id).html());
    $("#ipr-item-detail-title").html($("#ipr-item-title-" + id).html());
    $("#ipr-item-detail-pubhouse").html($("#ipr-item-pubhouse-" + id).html());
    $("#ipr-item-detail-authors").html($("#ipr-item-authors-" + id).html());
    $("#ipr-item-detail-pubyear").html($("#ipr-item-pubyear-" + id).html());
    $("#ipr-item-detail-description").html($("#ipr-item-description-" + id).html());
    $("#ipr-item-detail-keywords").html($("#ipr-item-keywords-" + id).html());
    $("#ipr-item-detail-pubtype").html($("#ipr-item-pubtype-" + id).html());

    var rb = $("#ipr-item-detail-read");
    rb.attr("href", $("#ipr-item-url-" + id).attr("href"));
    if ($("#ipr-item-url-" + id).attr("href")) {
        rb.show();
    }
}

function clear_details() {
    $("#ipr-item-detail-image").html('');
    $("#ipr-item-detail-title").html('');
    $("#ipr-item-detail-pubhouse").html('');
    $("#ipr-item-detail-authors").html('');
    $("#ipr-item-detail-pubyear").html('');
    $("#ipr-item-detail-description").html('');
    $("#ipr-item-detail-keywords").html('');
    $("#ipr-item-detail-pubtype").html('');
}

