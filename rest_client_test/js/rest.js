'use strict';

var rest = {
    initialize: function() {
        var url = 'http://192.168.1.130/rest/web/app.php/products';
        var product = {product: {name:"pototso", price:12, description:"pirulo"}},
            upProduct = {product: {name:"pototoss", price:12, description:"pirulo"}},
            newProduct;

        jQuery.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            success: function (data, status, jqXHR) {
                console.log("-----------GET-----------");
                console.log(JSON.stringify(data));
            },

            error: function (jqXHR, status) {
                console.log("-----------ERROR GET-----------");
                console.log(status);
                console.log(JSON.stringify(jqXHR));
            }
        });

        jQuery.ajax({
            type:"POST",
            url: url,
            dataType: "json",
            data: product,
            success:function (data) {
                console.log("-----------POST-----------");
                console.log(JSON.stringify(data));
                newProduct = data;

                jQuery.ajax({
                    type: "GET",
                    url: url+'/'+newProduct.id,
                    dataType: "json",
                    success: function (data, status, jqXHR) {
                        console.log("-----------GET-----------");
                        console.log(JSON.stringify(data));
                        
                        jQuery.ajax({
                            type:"PUT",
                            url: url+'/'+newProduct.id,
                            dataType: "json",
                            data: upProduct,
                            success:function (data) {
                                console.log("-----------PUT-----------");
                                console.log(JSON.stringify(data));
                                newProduct = data;

                                jQuery.ajax({
                                    type: "GET",
                                    url: url+'/'+newProduct.id,
                                    dataType: "json",
                                    success: function (data, status, jqXHR) {
                                        console.log("-----------GET-----------");
                                        console.log(JSON.stringify(data));

                                        jQuery.ajax({
                                            type:"DELETE",
                                            url: url+'/'+newProduct.id,
                                            dataType: "json",
                                            success:function (data, status, jqXHR) {
                                                console.log("-----------DELETE-----------");
                                                console.log(jqXHR.status);
                                            },

                                            error: function (jqXHR, status) {
                                                console.log("-----------ERROR DELETE-----------");
                                                console.log(status);
                                                console.log(jqXHR);
                                            }
                                        });

                                    },

                                    error: function (jqXHR, status) {
                                        console.log("-----------ERROR GET AFTER PUT-----------");
                                        console.log(status);
                                    }
                                });
                            }
                        });                
                    },

                    error: function (jqXHR, status) {
                        console.log("-----------ERROR GET AFTER POST-----------");
                        console.log(status);
                    }
                });


            }
        });
    }
};