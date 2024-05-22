<?php 

\Co\run(function() {
    go(function (){
        Co::sleep(2);
        echo "Após 2 segundos\n";
    });

    go(function (){
        Co::sleep(1);
        echo "Após 1 segundo\n";
    });
});
