
(function(){
    angular.module("admin").controller("PromocionController", ['$scope', 'DataService',
        function($scope, DataService)
        {       
            $scope.autores = [];
            $scope.tipos = [];
            $scope.canciones = [];
            
            $scope.autor_select = {text:"-1",value:""};
            $scope.album_cancion_select = {};
            $scope.cancion_select = {};
            $scope.album_select = {id_formato:""};
            
            $scope.promocionValidada = false;
            $scope.promociones = [];
            $scope.codigos = "";
            
            $scope.iniciar = function()
            {
               $scope.obtenerTipoPromociones();
            };
            
            $scope.iniciarCodigo = function(){
                $scope.obtenerPromociones();
            };
            
            $scope.validarCodigos = function(){
                DataService.validarCodigosPromocionales($scope.codigos).then(
                    function success(data){
                        
                        if(data.exists === true){
                            $scope.promocionValidada = false;
                            alertify.alert('Estos codigos ya existen',
                            ""+JSON.stringify(data.codigos));
                        }
                        else {
                            $scope.promocionValidada = true;
                            alertify.success("Los codigos fueron validados!");
                        }
                    },
                    function error(data){
                        console.log("Error ajax: al validar codigos.");
                    });
            };
            
            $scope.obtenerPromociones = function(){
                DataService.obtenerPromociones().then(
                    function success(data){
                        $scope.promociones = data;
                    },
                    function error(data){
                        console.log("Error ajax: al buscar promociones.");
                    });
            };
            
            $scope.obtenerArtistas = function(){                
                DataService.obtenerArtistas().then(
                    function success(data){
                        $scope.autores = data;
                        $scope.autor_select = $scope.autores[0];
                    },
                    function error(data){
                        console.log("Error ajax: al buscar artistas.");
                    });
            };
            
            $scope.changedArtista = function() {
                $scope.obtenerAlbumes();
            };
            
            $scope.changedAlbum = function() {
                $scope.obtenerCanciones();
            };
            
            $scope.obtenerAlbumes = function() {

                var id = $scope.autor_select['value'];
                
                DataService.obtenerAlbumesArtista(id).then(
                    function success(data){
                        $scope.albumes = data;
                        $scope.album_select = $scope.albumes[0];
                        $scope.obtenerCanciones();
                    },
                    function error(data){
                        console.log("Error ajax: al buscar albumes.");
                    });
            };
            
            $scope.obtenerCanciones = function() {

                var id = $scope.album_select['id_formato'];
                
                DataService.obtenerCancionesAlbum(id).then(
                    function success(data){
                        $scope.canciones = data;
                    },
                    function error(data){
                        console.log("Error ajax: al buscar canciones.");
                    });
            };
            
            $scope.obtenerTipoPromociones = function()
            {                
                DataService.obtenerTipoPromociones().then(
                    function success(data){
                        $scope.tipos = data;
                    },
                    function error(data){
                        console.log("Error ajax: al buscar tipo de promociones.");
                    });
            };
    
    
            /************ LISTADO ************/
            
            $scope.activar = function(item){
                 var id = item.currentTarget.getAttribute("data-id");
                 
                 DataService.cambiarStatusPromo(id,'activar').then(
                    function success(data){
                        alertify.success('Se activo la promocion #'+id);
                        setTimeout(function(){window.location.reload()},2000);
                    },
                    function error(data){
                        console.log("Error ajax: al cambiar status promo.");
                    });
            };
            
            $scope.inactivar = function(item){
                var id = item.currentTarget.getAttribute("data-id");
                 
                 DataService.cambiarStatusPromo(id,'inactivar').then(
                    function success(data){
                        alertify.success('Se desactivo la promocion #'+id);
                        setTimeout(function(){window.location.reload()},2000);
                    },
                    function error(data){
                        console.log("Error ajax: al cambiar status promo.");
                    });
            };
    
        }]);
})();