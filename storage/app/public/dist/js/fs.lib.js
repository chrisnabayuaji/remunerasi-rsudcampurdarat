window.initFsAutoNumeric = function(context) {
    var $ctx = context ? $(context) : $(document);
    if (typeof AutoNumeric !== 'undefined') {
        $ctx.find(".fs-autonumeric").each(function () {
            if (!AutoNumeric.getAutoNumericElement(this)) {
                new AutoNumeric(this, {
                    digitGroupSeparator: '.',
                    decimalCharacter: ',',
                    decimalPlaces: 2,
                    minimumValue: '0',
                    unformatOnSubmit: true
                });
            }
        });
    }
};

$(document).ready(function () {
    $(".fs-chose").each(function () {
        $(this).select2({
            theme: "bootstrap-5",
            dropdownParent: $(this).parent(),
            width: $(this).data("width")
                ? $(this).data("width")
                : $(this).hasClass("w-100")
                  ? "100%"
                  : "style",
        });
    });

    initFsAutoNumeric();
});
