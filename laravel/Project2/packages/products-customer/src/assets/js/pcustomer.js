(function (ProductCustomer,undefined){

    ProductCustomer.loadModal = function (id, type){

        var url = 'products/validate';

        $.post(url, {id:id, type:type})
            .done( function (data) {
                $(data.html).modal('show');
            });
    };

    ProductCustomer.productSearch = function (str){

        var val = $("#search-input-modal").val();
        var url = "products/search";

        $.ajax({
            url: url,
            type: 'post',
            data: {texto:val},
            cache: false,
            success: function(data){
                $("#product-detail").html(data.html);
            }
        });

    };

})(window.ProductCustomer = window.ProductCustomer || {});