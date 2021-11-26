<!DOCTYPE html>
<html>
<head>    
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Fituška v2.0</title>
    <script src="scripts/login.js" type="text/javascript"></script>
    <script src="scripts/admin.js" type="text/javascript"></script>
    <script src="scripts/url.js" type="text/javascript"></script> 
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


<div></div>
    <a class="fancy-btn position-center" href="#" id="open-modal">Open the modal!</a>

    <div class="modal-container" id="modal">
        <div class="modal">
            <div class="modal-header text-center">
                <h1>Add product</h1>
                <h3>Second-hand Car</h3>
                <small><strong>Category:</strong> Automobiles</small>            

                <a href="#" id="close-modal">
                    <img src="https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTF3_Bp6uIXZ9m4-nPI9UX2rzk5d6Pd41jivkMJ8aZzeQu03AKzztRj8JE" alt="closeIcon">
                </a>
            </div>
            <div class="modal-body">
                <form action="/" id="add-form">                
                    <div class="form-section">          
    
                        <div class="form-item-container">
                            <input class="fancy-btn" type="submit" id="submit" value="Submit"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>




<script src="scripts/modal_window.js" type="text/javascript"></script> 
</body>
</html>
