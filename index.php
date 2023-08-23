<?php include ('includes/init.php'); ?>
<!doctype html>
<html lang="en">
<head>

<title>Flag - game </title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link rel="stylesheet" type="text/css" href="css/style.css">


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script>

</head>
<body id="all">
	<div id="pocetak">
		<div class="sredina">
    		<label>Unesi ime <input type="text" name="ime" id="ime" /></label>
    		<hr>
    		<button id="pocetak_igre">IGRAJ</button>
		</div>
	</div>
	
		
	
	<div id="content">

	  <div id="countryFlags"> </div>
	  <div id="countryNames"> </div>

	  <div id="successMessage">
	  
	  	<div id="clock">
    		<div id="time">
                <span id="hours">00</span> :
                <span id="minutes">00</span> :
                <span id="seconds">00</span> 
            </div>
    	</div>
	  	
		<h2>Bravo</h2>
		<label for="broj">Broj zastava: <input type="number" id="broj" name="broj" min="1" max="50" value="<?php echo Zastava::getLimit()->broj;?>" /></label>
		<button id="start" onclick="">Igraj ponovno</button>
	  </div>
		<p class="clear"></p>
	</div>

<script>


/* STOPWATCH */ 
$(function() {


	var correctCards = 0;
	$( init );

	function init() {

	  // Hide the success message
	  $('#successMessage').hide();
	  $('#successMessage').css( {
	    left: '580px',
	    top: '250px',
	    width: 0,
	    height: 0
	  } );

	  // Reset the game
	  correctCards = 0;
	  $('#countryFlags').html( '' );
	  $('#countryNames').html( '' );

	  // Create the pile of shuffled cards
	  <?php 
	  $z = new Zastava();
	  $rez = $z->flags(); ?> 
	  var flags = [<?php foreach ($rez as $red){echo $red->id.',';}?>];
	  flags = flags.sort( function() { return Math.random() - .3 } );

	  <?php 
	    $rez1 = twodshuffle($rez);
	    foreach ($rez1 as $red){
	  ?>
	    $('<div><img src="images/sve/<?php echo $red->slika;?>"></div>').data( 'number', <?php echo $red->id;?> ).attr( 'id', 'card'+<?php  echo $red->id; ?> ).appendTo( '#countryFlags' ).draggable( {
	      containment: '#content',
	      stack: '#countryFlags div',
	      cursor: 'move',
	      revert: true
	    } );
	  <?php }?>

	  // Create the card slots
	<?php 

	foreach ($rez as $red){?>
	    $('<div><?php echo $red->naziv;?></div>').data( 'number', <?php echo $red->id;?> ).appendTo( '#countryNames' ).droppable( {
	      accept: '#countryFlags div',
	      hoverClass: 'hovered',
	      drop: handleCardDrop
	    } );
	  <?php }?>

	}


	

	// postavke sata
    var hours = minutes = seconds = milliseconds = 0;
    var prev_hours = prev_minutes = prev_seconds = prev_milliseconds = undefined;
    var timeUpdate;


    $(document).ready(function(){

		$("#pocetak_igre").click(function(){
			var time = 0;
			var ime = $('#ime').val();
			
			if(ime == ''){
				alert('Molimo unesite Vaše ime!')
			}else{
    			// Start button
    	        if($(this).text() == "IGRAJ"){ 
    				// Pokreni sat	
    	             updateTime(0,0,0,0);
    	        }
    			$("#pocetak").hide();

			}
			
		})

	
	  $("#start").click(function(){  
		  var broj = $('#broj').val();	
		  if(broj > 0 && broj< 40){
		  $.ajax({
	            type: "POST",
	            url: "brojZastava.php",
	            data: {broj: broj},
	            dataType: "html",
	            success: function (msg) {
	          	//ajax vraća nešto i to nešto stavljam u 
	          	    location.reload();
	            	$( init );
	            }
	    	});	
		  }else{
			alert ("Uneseni broj mora biti veći od 0 ili manji od 50!")
				}
		  
	  });
});

	

	
	function handleCardDrop( event, ui ) {
	  var slotNumber = $(this).data( 'number' );
	  var cardNumber = ui.draggable.data( 'number' );

	  // If the card was dropped to the correct slot,
	  // change the card colour, position it directly
	  // on top of the slot, and prevent it being dragged
	  // again

	  if ( slotNumber == cardNumber ) {
	    ui.draggable.addClass( 'correct' );
	    ui.draggable.draggable( 'disable' );
	    $(this).droppable( 'disable' );
	    ui.draggable.position( { of: $(this), my: 'left top', at: 'left top' } );
	    ui.draggable.draggable( 'option', 'revert', false );
	    correctCards++;
	  } 
	  
	  // If all the cards have been placed correctly then display a message
	  // and reset the cards for another go

	  if ( correctCards == <?php echo Zastava::getLimit()->broj;?> ) {

			var vrijeme = $('#time').text();
			var ime = $('#ime').val();

			$('<span> '+ime+' </span>').appendTo( '#successMessage h2' );

    		<?php 
    		  
    		?>
		  
		// zaustavi sat	
		clearInterval(timeUpdate);
		  
	    $('#successMessage').show();
	    $('#successMessage').animate( {
	      left: '50%',
	      top: '50%',
	      width: '400px',
	      height: '150px',
	      opacity: 1
	    } );
	  }

	}


    

    
    
    // Update time in stopwatch periodically - every 25ms
    function updateTime(prev_hours, prev_minutes, prev_seconds, prev_milliseconds){
        var startTime = new Date();    // fetch current time
        
        timeUpdate = setInterval(function () {
            var timeElapsed = new Date().getTime() - startTime.getTime();    // calculate the time elapsed in milliseconds
            
            // calculate hours                
            hours = parseInt(timeElapsed / 1000 / 60 / 60) + prev_hours;
            
            // calculate minutes
            minutes = parseInt(timeElapsed / 1000 / 60) + prev_minutes;
            if (minutes > 60) minutes %= 60;
            
            // calculate seconds
            seconds = parseInt(timeElapsed / 1000) + prev_seconds;
            if (seconds > 60) seconds %= 60;
            
            // calculate milliseconds 
            milliseconds = timeElapsed + prev_milliseconds;
            if (milliseconds > 1000) milliseconds %= 1000;
            
            // set the stopwatch
            setStopwatch(hours, minutes, seconds, milliseconds);
            
        }, 25); // update time in stopwatch after every 25ms
        
    }
    
    // Set the time in stopwatch
    function setStopwatch(hours, minutes, seconds, milliseconds){
        $("#hours").html(prependZero(hours, 2));
        $("#minutes").html(prependZero(minutes, 2));
        $("#seconds").html(prependZero(seconds, 2));
        $("#milliseconds").html(prependZero(milliseconds, 3));
    }
    
    // Prepend zeros to the digits in stopwatch
    function prependZero(time, length) {
        time = new String(time);    // stringify time
        return new Array(Math.max(length - time.length + 1, 0)).join("0") + time;
    }
});



</script>

</body>
</html>


		
	
		 
		

