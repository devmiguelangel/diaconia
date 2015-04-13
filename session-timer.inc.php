<script type="text/javascript" src="js/idle-timer.js"></script>
<script type="text/javascript">
    var time = (1000 * 60 * 3);
    //var time = (1000 * 5);
    $(document).idleTimer(time);
    $(document).on("idle.idleTimer", function(){
        var msg = "Su Sesión esta a punto de expirar debido a un largo periodo " +
            "de inactividad. \n Para SALIR del Sistema haga click en ACEPTAR, " +
            "\n para permanecer en el mismo haga click en CANCELAR.";

        if(confirm(msg)){
            $.get('logout.php',{logout: true}, function(data){
                if(parseInt(data) === 1){
                    location.href = "index.php?err=<?=md5(1);?>";
                }
            });
        }else{
            window.scrollTo(0,0);
            $('html, body').animate({scrollTop: 590}, 'slow');
        }
    });
</script>