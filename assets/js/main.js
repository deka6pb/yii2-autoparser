$(document).ready(function(){
    var postType = {
        "NaN"   : "Nan",
        "txt"   : 1,
        "pic"   : 2,
        "gif"   : 3
    };

    $("#input-ru").fileinput({
        language: "ru",
        uploadUrl: "http://localhost/site/file-upload-batch",
        allowedFileExtensions: ["jpg", "png", "gif"]
    });

    $("#posts-type").on("change", function() {
        var
            btn = $(this),
            item = {

            },
            ctrl = {
                'files' : $('#posts-uploadfiles'),
                'text'  : $('#posts-text'),
                'tags'  : $('#posts-tags')
            },
            handler = {
                init: function(){
                    var type = parseInt(btn.val());

                    if(isNaN(type)) {
                        handler.filesToggle("disable");
                        return;
                    }

                    switch(parseInt(btn.val())) {
                        case postType.txt:
                            handler.filesToggle("disable");
                            break;
                        case postType.pic:
                        case postType.gif:
                            handler.filesToggle("enable");
                            break;
                    }
                },
                filesToggle:function(value){
                    ctrl.files.fileinput("clear");
                    ctrl.files.fileinput(value);
                }
            };

        handler.init();
    })
});