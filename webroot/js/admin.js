function confirmDelete(){
    if ( confirm("Delete this item?") ){
        return true;
    } else {
        return false;
    }
}

function checkPassword(){
    var pass = document.getElementById("password");
    var checkPass = document.getElementById("check_password");
    var button = document.getElementById("button_submit");
    if ( pass.value == checkPass.value ) {
        button.style = "display: block";
        checkPass.style = "border: 2px solid green";
    } else {
        button.style = "display: none";
        checkPass.style = "border: 2px solid red";
    }
}

$(".card-slider").each(function(){
    var $slider = $(this),
        $sliderContainer = $slider.find(".inner"),
        $sliderList = $slider.find(".inner > ul"),
        $sliderItem = $slider.find(".inner > ul > li"),
        $sliderButton = $slider.find(".slider-nav button"),
        $slidePrev = $slider.find('[data-direction="prev"]'),
        $slideNext = $slider.find('[data-direction="next"]'),

        setItemWidth = function(){
            $sliderList.removeAttr("style");
            var curWidth = $($sliderItem[0]).outerWidth() * $sliderItem.length;
            $sliderList.css("width", curWidth);
        },

        slide = function(){
            var $button = $(this),
                dir = $button.data("direction"),
                curPos = parseInt($sliderList.css("left")) || 0,
                moveto = 0,
                containerWidth = $sliderContainer.outerWidth(),
                listWidth = $sliderList.outerWidth(),
                before = (curPos + containerWidth),
                after = listWidth + (curPos - containerWidth);

            if(dir=="next"){
                if(after < containerWidth) {
                    moveto = curPos - after;
                    $slideNext.addClass('disabled');
                    $slidePrev.removeClass('disabled');
                }
                else {
                    moveto = curPos - containerWidth;
                    $slidePrev.removeClass('disabled');
                }
            }
            else {
                if(before >= 0) {
                    moveto = 0;
                    $slidePrev.addClass('disabled');
                    $slideNext.removeClass('disabled');
                }
                else {
                    moveto = curPos + containerWidth;
                    $slideNext.removeClass('disabled');
                }
            }

            $sliderList.css('left', moveto);
        };

    $(window).resize(function(){
        setItemWidth();
    });

    setItemWidth();
    $sliderButton.on("click", slide);
});