<?php
$Address = isset($_GET["address"])? $_GET["address"]: "";
$AddressHTML = htmlspecialchars($Address, ENT_QUOTES, 'UTF-8');
$AddressJS = json_encode($Address);
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="css/styles.css?0" rel="stylesheet" type="text/css" />
        <link href="css/fonts.css" rel="stylesheet" type="text/css" />
        <link href="css/themify-icons.css" rel="stylesheet" type="text/css" />
        <link rel="icon" type="image/x-icon" href="img/favicon.ico">
        <title>Siege Worlds | NFT Weapon Inventory</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
    </head>
    <body>
        <div class="main-wrapper">
            <div id="LoadingNFTs">
                <div class="header">
                    <img class="logo" src="img/sw-logo-white.png" />
                    <h1>Enter your Polygon / MATIC address!</h1>
                    <p id="LoadingProgress"></p>
                    <div class="select">
	                    <input class="input-address" type="text" size="30" id="Address" name="Address" value="<?php print($AddressHTML); ?>" placeholder="Address"/> 
                    </div>
                    <div><button class="button" type="button" style="background-color: rgb(103, 61, 255);margin-top: 26px;margin-left: 100px;"  onclick="loadNFTs()" >Search</button></div>
                    <div id="NFTListData"></div>
                     
                </div>
            </div>
        </div>

        <script type="text/javascript" src="js/jquery-3.6.0.min.js"></script>
        <script type="text/javascript" src="js/qr-code-styling.js"></script>
        <script type="text/javascript" src="js/web3.min.js"></script>
        <script type="text/javascript" src="js/metaMask.js"></script>
        <script type="text/javascript">
            let address = <?php echo $AddressJS; ?>;
            let nftList = [];
                    
           	const showScreen = (newScreen) => {
		        if (screen == newScreen) return
		        $('#' + screen).hide()
		        $('#' + newScreen).show()
		        screen = newScreen
		      }
      
            const loadNFTs = async () => {
	            address = $("#Address").val();
                nftList = await $.get("https://lg.cr/lwGetWeapons/" + address);
				makeTierHTML();
                showScreen("NFTList");
            };

            const makeTierHTML = (_) => {
                let HTML = "";
                if( nftList.length > 0 ){
	                for (let nft of nftList) {
	                    HTML += '<div class="card"><div class="tier-image"><img id="NFTForgedImage" src="' + nft.image +'" /></div><h3 class="title" id="NFTForgedTitle">' + nft.name + '</h3><p class="desc" id="NFTForgedDescription">' + nft.description + '</p></div>';
	                } 
                }else{
	                HTML += '<div style="font-size: 20px; margin: 20px 110px;"><b>You have no weapons yet.</b></div>';
                }
               
                $("#NFTListData").html(HTML);
            };
            
            window.onload = _=> {
	            if(address && address.length>10)	{
		            loadNFTs()
	            }
            }
        </script>
    </body>
</html>
