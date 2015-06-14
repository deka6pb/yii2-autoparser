$(document).ready(function(){
    var postType = {
        "NaN"   : "Nan",
        "txt"   : 1,
        "pic"   : 2,
        "gif"   : 3
    };

    $("#posts-type").on("change", function() {
        var
            btn = $(this),
            item = {

            },
            ctrl = {
                'url'   : $('#posts-url'),
                'text'  : $('#posts-text'),
                'tags'  : $('#posts-tags')
            },
            handler = {
                init: function(){
                    var type = parseInt(btn.val());

                    if(isNaN(type)) {
                        handler.disabled(true);
                        return;
                    }
                    handler.disabled(false);

                    switch(parseInt(btn.val())) {
                        case postType.txt:
                            ctrl.url.attr("disabled", true);
                            break;
                        case postType.pic:
                        case postType.gif:
                            ctrl.url.removeAttr("disabled");
                            break;
                    }
                },
                change:function(){
                    ctrl.url.attr("disabled", true);
                },
                disabled:function(value) {
                    if(value) {
                        ctrl.url.attr("disabled", true);
                        ctrl.text.attr("disabled", true);
                        ctrl.tags.attr("disabled", true);
                    } else {
                        ctrl.url.removeAttr("disabled");
                        ctrl.text.removeAttr("disabled");
                        ctrl.tags.removeAttr("disabled");
                    }
                }
            };

        handler.init();
    })
});