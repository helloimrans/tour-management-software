// Upload image show 1
$(document).ready(function () {
    var accountUploadImg = $('#upImg1');
    var accountUploadBtn = $('#upImgInput1');
    var accountUserImage = $('.upImg1');
    var accountResetBtn = $('#upImgReset1');

    if (accountUserImage) {
        var resetImage = accountUserImage.attr("src");
        accountUploadBtn.on("change", function (e) {
            var reader = new FileReader(),
                files = e.target.files;
            reader.onload = function () {
                if (accountUploadImg) {
                    accountUploadImg.attr("src", reader.result);
                }
            };
            reader.readAsDataURL(files[0]);
        });

        accountResetBtn.on("click", function () {
            accountUserImage.attr("src", resetImage);
            accountUploadBtn.val(null);
        });
    }
});
// Upload image show 2
$(document).ready(function () {
    var accountUploadImg2 = $('#upImg2');
    var accountUploadBtn2 = $('#upImgInput2');
    var accountUserImage2 = $('.upImg2');
    var accountResetBtn2 = $('#upImgReset2');

    if (accountUserImage2) {
        var resetImage = accountUserImage2.attr("src");
        accountUploadBtn2.on("change", function (e) {
            var reader = new FileReader(),
                files = e.target.files;
            reader.onload = function () {
                if (accountUploadImg2) {
                    accountUploadImg2.attr("src", reader.result);
                }
            };
            reader.readAsDataURL(files[0]);
        });

        accountResetBtn2.on("click", function () {
            accountUserImage2.attr("src", resetImage);
            accountUploadBtn2.val(null);
        });
    }
});

// Upload image show 3
$(document).ready(function () {
    var accountUploadImg3 = $('#upImg3');
    var accountUploadBtn3 = $('#upImgInput3');
    var accountUserImage3 = $('.upImg3');
    var accountResetBtn3 = $('#upImgReset3');

    if (accountUserImage3) {
        var resetImage = accountUserImage3.attr("src");
        accountUploadBtn3.on("change", function (e) {
            var reader = new FileReader(),
                files = e.target.files;
            reader.onload = function () {
                if (accountUploadImg3) {
                    accountUploadImg3.attr("src", reader.result);
                }
            };
            reader.readAsDataURL(files[0]);
        });

        accountResetBtn3.on("click", function () {
            accountUserImage3.attr("src", resetImage);
            accountUploadBtn3.val(null);
        });
    }
});
