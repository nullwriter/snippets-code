(function(){

    angular.module("admin")
        .service("DataService", ['$http',function($http)
        {
            this.base_url = window.location.origin;
            
            /* Obtiene una lista de artistas {text,value} */
            this.obtenerArtistas = function()
            {
                return $http.get(this.base_url+"/admin/autor/getAutores.json")
                            .then(
                                function successCallBack(res){
                                    return res.data.Autores;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Obtiene albumes de un artista */
            this.obtenerAlbumesArtista = function(id) {

                return $http.post(this.base_url+"/admin/formato/getFormatos",{id:id})
                            .then(
                                function successCallBack(res){
                                    return res.data.Formatos;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Obtiene canciones de un album */
            this.obtenerCancionesAlbum = function(id) {

                return $http.post(this.base_url+"/admin/formato/getCancionesFormato",{id:id})
                            .then(
                                function successCallBack(res){
                                    return res.data.Canciones;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Obtiene datos de un producto con el id_producto */
            this.obtenerDatosProducto = function(id) {
              
                return $http.post(this.base_url+"/admin/merch/datosProducto.json",{id:id})
                            .then(
                                function successCallBack(res){
                                    return res.data.datos;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Elimina imagen de producto */
            this.eliminarImagenProducto = function(id)
            {
                return $http.post(this.base_url+"/admin/merch/eliminarImagen.json",{id:id})
                            .then(
                                function successCallBack(res){
                                    return res.data.result;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Obtiene lista de categorias de accesorios de productos */
            this.obtenerAccesoriosMerch = function ()
            {
                return $http.get(this.base_url+"/admin/merch/categoriasAccesorio")
                            .then(
                                function successCallBack(res){
                                    return res.data;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Obtiene todo el detalle de una compra */
            this.obtenerDetalleCompra = function(id){
                
                return $http.post(this.base_url+"/admin/compra/detalleCompra",{id:id})
                            .then(
                                function successCallBack(res){
                                    return res.data;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Obtiene los statuses de una compra */
            this.obtenerBitacora = function(id){
                
                return $http.post(this.base_url+"/admin/compra/obtenerBitacora",{id:id})
                            .then(
                                function successCallBack(res){
                                    return res.data;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Obtiene el ultimo update de una compra merch */
            this.ultimoUpdateCompraMerch = function(id){
                
                return $http.post(this.base_url+"/admin/compra/ultimaActializacionMerch",{id:id})
                            .then(
                                function successCallBack(res){
                                    return res.data.data;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Obtiene el ultimo update de una compra merch */
            this.obtenerTipoPromociones = function(){
                
                return $http.get(this.base_url+"/admin/promociones/tipoPromociones.json")
                            .then(
                                function successCallBack(res){
                                    return res.data.tipos;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Obtiene las promociones */
            this.obtenerPromociones = function(){
                
                return $http.get(this.base_url+"/admin/promociones/obtenerPromociones.json")
                            .then(
                                function successCallBack(res){
                                    return res.data.promos;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Validar codigos promocionales si no existen ya */
            this.validarCodigosPromocionales = function(codigos){
                
                return $http.post(this.base_url+"/admin/promociones/validarCodigos.json",{codigos:codigos})
                            .then(
                                function successCallBack(res){
                                    return res.data;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Validar codigos promocionales si no existen ya */
            this.cambiarStatusPromo = function(id,status){
                
                return $http.post(this.base_url+"/admin/promociones/cambiarStatus",{id:id,status:status})
                            .then(
                                function successCallBack(res){
                                    return res.data;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            this.obtenerTipoGiftcards = function(){
                
                return $http.get(this.base_url+"/admin/giftcards/obtenerTipoGiftcards")
                            .then(
                                function successCallBack(res){
                                    return res.data;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            // devuelve codigos alfanumericos 
            // (tamano string, cantidad, mayusculas)
            this.generarCodigos = function(size,cant,uppercase){
                size = (typeof size !== 'undefined' ? size : 10);
                cant = (typeof cant !== 'undefined' ? cant : 1);
                uppercase = (typeof uppercase !== 'undefined' ? uppercase : true);
                
                return $http.post(this.base_url+"/admin/giftcards/obtenerCodigos",
                                    {size:size,cantidad:cant,uppercase:uppercase})
                            .then(
                                function successCallBack(res){
                                    return JSON.parse(res.data);
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Validar codigos giftcard si no existen ya */
            this.validarCodigosGiftcard = function(codigos){
      
                return $http.post(this.base_url+"/admin/giftcards/validarCodigos",{codigos:codigos})
                            .then(
                                function successCallBack(res){
                                    return res.data;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Validar codigos giftcard si no existen ya */
            this.obtenerTiposMerch = function(id){
      
                return $http.post(this.base_url+"/admin/merch/obtenerTipos.json",{id:id})
                            .then(
                                function successCallBack(res){
                                    return res.data;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Obtiene lista de categoria_tipo de productos */
            this.obtenerSuperTiposMerch = function (id_super_categoria)
            {
                return $http.post(this.base_url+"/admin/merch/obtenerSuperTipos.json",
                                    {id:id_super_categoria})
                            .then(
                                function successCallBack(res){
                                    return res.data.data;
                                },
                                function errorCallBack(res){
                                    return res.data;
                                }
                            );
            };
            
            /* Cambiar status promocion horizontal */
            this.cambiarStatusPromoH = function(id,status){
                
                return $http.post(this.base_url+"/admin/promociones/cambiarStatusPromoH",
                            {id:id,status:status})
                            .then(
                                function successCallBack(res){
                                    return res;
                                },
                                function errorCallBack(res){
                                    return res;
                                }
                            );
            };
            
            this.eliminarPromoH = function(id){
              
                return $http.post(this.base_url+"/admin/promociones/eliminarPromoH",
                        {id:id})
                        .then(
                            function successCallBack(res){
                                return res;
                            },
                            function errorCallBack(res){
                                return res;
                            }
                        );
            };
            
            this.cambiarPosicionPromoH = function(id,pos){
              
                return $http.post(this.base_url+"/admin/promociones/cambiarPosicionPromoH",
                        {id:id,posicion:pos})
                        .then(
                            function successCallBack(res){
                                return res;
                            },
                            function errorCallBack(res){
                                return res;
                            }
                        );
            };

        }]);

})();