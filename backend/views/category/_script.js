$('#parent_title').autocomplete({
    source: function (request, response) {
        var result = [];
        var limit = 10;
        var term = request.term.toLowerCase();
//      alert(request.term);
        $.each(_opts.categorys, function () {
            var menu = this;
            if (term == '' || menu.title.toLowerCase().indexOf(term) >= 0 ||
                (menu.parent_title && menu.parent_title.toLowerCase().indexOf(term) >= 0)) {
                result.push(menu);
                limit--;
                if (limit <= 0) {
                    return false;
                }
            }
        });
        response(result);
    },
    focus: function (event, ui) {
        $('#parent_title').val(ui.item.title);
        return false;
    },
    select: function (event, ui) {
        $('#parent_title').val(ui.item.title);
        $('#parentid').val(ui.item.id);
        return false;
    },
    search: function () {
        $('#parentid').val('');
    }
}).autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append($('<a>').append($('<b>').text(item.title)).append('<br>')
            .append($('<i>').text(item.parent_title)))
        .appendTo(ul);
};

//$('#route').autocomplete({
//    source: _opts.routes,
//});