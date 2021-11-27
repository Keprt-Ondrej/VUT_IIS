<!DOCTYPE html>
<html>
<head>    
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Fituška v2.0</title>
    <script src="scripts/login.js" type="text/javascript"></script>
    <script src="scripts/admin.js" type="text/javascript"></script>
    <script src="scripts/url.js" type="text/javascript"></script>
    <script src="scripts/moderator.js" type="text/javascript"></script>
    <script src="scripts/registered.js" type="text/javascript"></script>  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/styles.css">
</head>
      
<body>
    <div id="login">        </div>
    <div id="header">       </div>
    <div id="navigation">   </div>
    <div id="content">      </div>
    <script>
        require_user();        
    </script>
    
    <div>
        <div class="modal-container" id="modal">
            <div class="modal">
                <div class="modal-header text-center" id="modal-header">
                    <h1>Ahoj ja jsem tady</h1>  
                </div>
                <a href="#" id="close-modal">
                        <img src="https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTF3_Bp6uIXZ9m4-nPI9UX2rzk5d6Pd41jivkMJ8aZzeQu03AKzztRj8JE" alt="closeIcon">
                    </a>

                <div class="modal-body" id="modal-body">
                </div>
            </div>
        </div>

    </div>

    <script src="scripts/modal_window.js" type="text/javascript"></script> 
</body>
</html>
